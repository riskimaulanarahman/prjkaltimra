<?php

namespace App\Http\Controllers\Cabang;

use App\Http\Controllers\Controller;
use App\Models\Lpj;
use App\Models\LpjKonsumen;
use Response;
use App\Models\Lokasi;
use App\Models\SalesPeople;
use App\Models\FinanceCompany;
use Illuminate\Support\Str;
use App\Models\Proposal;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use App\Models\KategoriProposal;
use App\Models\Dealer;
use Illuminate\Http\Request;
use App\Models\Display;
use Intervention\Image\Facades\Image;
use App\Models\LpjDokumentasi;
use App\Models\LpjRevenue;
use App\Models\LpjUnitentry;
use App\Models\ApprovalLpj;
use App\Models\Pusat;
use Illuminate\Support\Carbon;

class LpjController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('cabang.auth:cabang');
    }

    public function index() {
        $datakategori = KategoriProposal::get();
        $datas = Lpj::whereHas('proposal', function (Builder $query) {
                    $query->where('dealer_proposal', Auth::guard('cabang')->user()->dealer);
                 })
                 ->pj(request()->namapj)
                 ->kategori(request()->kategori)
                 ->submitDate(request()->tanggal)
                 ->orderBy('submit_date', 'asc')
                 ->paginate(10);

        return view('cabang.lpj.index', compact('datas', 'datakategori'));
    }

    public function getCreateLpj() {
        $datalokasi   = Lokasi::get();
        $datakategori = KategoriProposal::get();
        $datas        = Proposal::where('dealer_proposal', Auth::guard('cabang')->user()->dealer)
                            ->whereDoesntHave('lpj', function (Builder $query) {
                                $query->where('status_lpj', 2)
                                // ->whereNull('submit_date')
                                ;
                            })
                            ->leftJoin('lpjs','proposals.id','=','lpjs.id_proposal')
                            ->pj(request()->namapj)
                            ->kategori(request()->kategori)
                            ->lokasi(request()->lokasi)
                            ->statusProposal(request()->status)
                            ->tanggal(request()->tanggal)
                            ->where('proposals.status_proposal', 4)
                            ->whereNull('lpjs.submit_date')
                            // ->doesnthave('lpj')
                            ->orderBy('proposals.created_at', 'DESC')
                            ->paginate(10);
        // return $datas;


        return view('cabang.lpj.create-data', compact('datas', 'datalokasi', 'datakategori'));
    }

    public function getDataLpj() {
        $datakategori = KategoriProposal::get();
        // return $datakategori;
        $datas = Lpj::whereHas('proposal', function (Builder $query) {
                    $query->where('dealer_proposal', Auth::guard('cabang')->user()->dealer);
                 })
                 ->pj(request()->namapj)
                 ->kategori(request()->kategori)
                 ->submitDate(request()->tanggal)
                 ->whereIn('status_lpj', [2,3,4,5,6])
                 ->orderBy('lpjs.updated_at', 'desc')
                 ->paginate(10);

        // return $datas;

        return view('cabang.lpj.index', compact('datas', 'datakategori'));
    }

    public function getStoreInit() {

        $proposal = Proposal::where('uuid', request()->id)->first();

        $cek = Lpj::where('id_proposal', $proposal->id)->first();

        if ($cek) {
            $init = Lpj::where('id_proposal', $proposal->id)->first();
            if ($init->status_lpj == 2) {
                return redirect()->to(route('cabang.lpj.getShow').'/?id='.$init->uuid);
            }
        } else {
            $init              = new Lpj;
            $init->uuid        = Str::uuid();
            $init->id_proposal = $proposal->id;
            $init->status_lpj  = 1;
            $init->save();
        }

        return redirect()->to(route('cabang.lpj.getCreateOne').'/?id='.$init->uuid);
    }

    public function getCreateOne(Request $request) {

        $kota           = Dealer::find(Auth::guard('cabang')->user()->dealer);
        $datalokasi     = Lokasi::where('kota_lokasi', 'LIKE', '%'.$kota->kota_dealer.'%')->get();
        $datafinance    = FinanceCompany::get();
        $data           = Lpj::where('uuid', request()->id)->first();

        if (request()->submit == 'draft' || request()->submit == 'submit') {
            if(request()->finance){
                foreach (request()->finance as $keyfin => $item_f) {
                        $finance = json_encode(request()->finance);
                    }
            } else {
                $finance = null;
            }

            $data->finance_lpj          = !null == $finance ? $finance : null;
            $data->tempat_lpj           = request()->tempat;
            $data->lokasi_lpj           = request()->lokasi;
            $data->periode_start_lpj    = request()->start;
            $data->periode_end_lpj      = request()->end;
            $data->save();

            if (request()->submit == 'draft') {
                return redirect()->route('cabang.lpj.index')->withFlashSuccess('Lpj Berhasil Tersimpan  ! ✅');
            } elseif (request()->submit == 'submit') {
                $request->validate([
                    'tempat' => 'required',
                    'lokasi' => 'required',
                    'start'  => 'required',
                    'end'    => 'required',
                ]);
                return redirect()->to(route('cabang.lpj.getCreateTwo').'?id='.$data->uuid);
            }
        }

        return view('cabang.lpj.1', compact('datalokasi', 'data', 'datafinance'));
    }

    public function getCreateTwo(Request $request) {

        $data           = Lpj::where('uuid', request()->id)->with('proposal')->first();

        $dataunitentry      = json_decode($data->unit_entry_proposal ?? null, true);

        if (request()->submit == 'draft' || request()->submit == 'submit') {
            $total_dana_ = request()->totaldana ? str_replace(",","",request()->totaldana) : null;
            $total_dana_ = request()->totaldana ? str_replace(".","",$total_dana_) : null;

            $data->target_database_lpj    = request()->database;
            $data->target_penjualan_lpj   = request()->penjualan;
            $data->target_prospectus_lpj  = request()->prospectus;
            $data->target_downloader_lpj  = request()->downloader;
            $data->total_dana_lpj         = $total_dana_;
            $data->save();

            if (request()->submit == 'draft') {
                return redirect()->route('cabang.lpj.index')->withFlashSuccess('Lpj Berhasil Tersimpan  ! ✅');
            } elseif (request()->submit == 'submit') {
                $request->validate([
                    'database'  => 'required',
                    'penjualan' => 'required',
                    'prospectus'=> 'required',
                    'downloader'=> 'required',
                    'totaldana' => 'required',
                ]);
                return redirect()->to(route('cabang.lpj.getCreateThree').'?id='.$data->uuid);
            }
        }

        return view('cabang.lpj.2', compact('data', 'dataunitentry'));
    }

    public function getCreateThree(Request $request) {

        $data           = Lpj::where('uuid', request()->id)->first();

        if (request()->submit == 'draft' || request()->submit == 'submit') {

            $data->problem_identification_lpj    = request()->problem;
            $data->corrective_action_lpj   = request()->corrective;
            $data->save();

            if (request()->submit == 'draft') {
                return redirect()->route('cabang.lpj.index')->withFlashSuccess('Lpj Berhasil Tersimpan  ! ✅');
            } elseif (request()->submit == 'submit') {
                $request->validate([
                    'problem'  => 'required',
                    'corrective'  => 'required',
                ]);
                return redirect()->to(route('cabang.lpj.getCreateFour').'?id='.$data->uuid);
            }
        }

        return view('cabang.lpj.3', compact('data'));
    }

    public function getCreateFour(Request $request) {

        $data            = Lpj::where('uuid', request()->id)->first();
        if (request()->submit == 'draft' || request()->submit == 'submit') {
            if (request()->submit == 'draft') {
                return redirect()->route('cabang.lpj.index')->withFlashSuccess('Lpj Berhasil Tersimpan  ! ✅');
            } elseif (request()->submit == 'submit') {
                $cek1 = LpjDokumentasi::where('id_lpj', $data->id)->where('kode', 1)->count();
                $cek2 = LpjDokumentasi::where('id_lpj', $data->id)->where('kode', 2)->count();
                $cek3 = LpjDokumentasi::where('id_lpj', $data->id)->where('kode', 3)->count();
                $cek4 = LpjDokumentasi::where('id_lpj', $data->id)->where('kode', 4)->count();
                $cek5 = LpjDokumentasi::where('id_lpj', $data->id)->where('kode', 5)->count();
                $cek6 = LpjDokumentasi::where('id_lpj', $data->id)->where('kode', 6)->count();
                if($cek1 < 2){ return redirect()->back()->withFlashDanger('Foto Kanvasing Sebelum kegiatan Kurang/Belum Diupload !');}
                elseif($cek2 < 2){ return redirect()->back()->withFlashDanger('Foto Capture Blast WA kepada konsumen Kurang/Belum Diupload !');}
                elseif($cek3 < 2){ return redirect()->back()->withFlashDanger('Foto Posting flyer kegiatan di media sosial dealer Kurang/Belum Diupload !');}
                elseif($cek4 < 2){ return redirect()->back()->withFlashDanger('Foto Interaksi konsumen Kurang/Belum Diupload !');}
                elseif($cek5 < 2){ return redirect()->back()->withFlashDanger('Foto Unit Display Kurang/Belum Diupload !');}
                elseif($cek6 < 2){ return redirect()->back()->withFlashDanger('Foto Live Season saat kegiatan berlangsung Kurang/Belum Diupload !');}
                else{return redirect()->to(route('cabang.lpj.getCreateFive').'?id='.$data->uuid);}
            }
        }

        return view('cabang.lpj.4', compact('data'));
    }

    public function getCreateFourUpload(Request $request) {

        $data           = Lpj::where('uuid', request()->id)->first();

        $datauploads = collect([
            ['id'   => '1','nama' => 'Kanvasing Sebelum kegiatan'],
            ['id'   => '2','nama' => 'Capture Blast WA kepada konsumen'],
            ['id'   => '3','nama' => 'Posting flyer kegiatan di media sosial dealer'],
            ['id'   => '4','nama' => 'Interaksi konsumen'],
            ['id'   => '5','nama' => 'Unit Display'],
            ['id'   => '6','nama' => 'Live Season saat kegiatan berlangsung'],
        ]);

        $dataupload = $datauploads->firstWhere('id', request()->upload);

        return view('cabang.lpj.4-upload', compact('data', 'dataupload'));
    }

    public function getCreateFourUploadHapus(Request $request) {

        $hapus = LpjDokumentasi::find(request()->id);
        $hapus->delete();

        return redirect()->back()->withFlashSuccess('Foto Berhasil Dihapus ! ✅');
    }

    public function getTess()
    {
        $a = Proposal::find(15);
        $b = json_decode($a->display_proposal);
        $b[] = ['oke' => 'tambah', 'okeeee' => '1'];
        return $b;
    }

    public function postCreateUpload(Request $request) {

        try {

            request()->validate([
                'filepond' => 'required|image|max:10240',
            ]);
            $file_foto      = request()->file('filepond');

            $data       = Lpj::firstWhere('uuid', request()->uuid);
            $namafoto   = $data->id.'-'.substr($data->uuid,0,8);
            $n          = '-'.Str::random(5).'.';
            $datafoto   = $namafoto.$n.$file_foto->getClientOriginalExtension();

            $tambah = new LpjDokumentasi;
            $tambah->id_lpj = $data->id;
            $tambah->kode   = request()->upload;
            $tambah->foto   = $datafoto;
            $tambah->save();

            // $foto   = json_decode($data->dokumentasi_lpj);
            // $foto[] = ['id' => request()->upload, 'foto' => $datafoto ];

            $fotolpj   = Image::make($file_foto);
            $lokasiupload   = public_path('/upload-foto-lpj/');
            $fotolpj->resize(800, null, function ($constraint) {
                $constraint->aspectRatio();
            });
            $fotolpj->save($lokasiupload.$datafoto);

            // $data->dokumentasi_lpj = json_encode($foto);
            // $data->save();

            return response()->json(['info' => 'berhasil', 'data' => $tambah]);
        } catch (\Throwable $th) {
            return response()->json(['eror' => $th]);
        }

    }

    public function getCreateFive(Request $request) {

        $data           = Lpj::where('uuid', request()->id)->first();

        if (request()->method == 'konsumen') {
            $tambahkonsumen = new LpjKonsumen;
            $tambahkonsumen->id_lpj             = $data->id;
            $tambahkonsumen->nama               = $request->nama;
            $tambahkonsumen->alamat             = $request->alamat;
            $tambahkonsumen->lokasi             = $request->lokasi;
            $tambahkonsumen->tgl_lahir          = $request->tgllahir;
            $tambahkonsumen->gender             = $request->gender;
            $tambahkonsumen->notelp             = $request->notelp;
            $tambahkonsumen->pekerjaan          = $request->pekerjaan;
            $tambahkonsumen->nomor_mesin        = $request->nomormesin;
            $tambahkonsumen->unit               = $request->unit;
            $tambahkonsumen->id_lokasi          = $request->lokasi;
            $tambahkonsumen->id_sales_people    = $request->sales;
            $tambahkonsumen->finance_company    = $request->finance;
            $tambahkonsumen->cash_credit        = $request->jenis;
            $tambahkonsumen->hasil              = $request->hasil;
            $tambahkonsumen->pengeluaran        = $request->pengeluaran;
            $tambahkonsumen->dp                 = $request->dp;
            $tambahkonsumen->merkmotor          = $request->merkmotor;
            $tambahkonsumen->jenismotor         = $request->jenismotor;
            $tambahkonsumen->save();

            return redirect()->back()->withFlashSuccess('Konsumen Berhasil Ditambahkan  ! ✅');
        } elseif (request()->method == 'hapuskonsumen') {
            $hapuskonsumen = LpjKonsumen::find(request()->id);
            $hapuskonsumen->delete();
            return redirect()->back()->withFlashSuccess('Konsumen Berhasil Dihapus ! ✅');
        } elseif (request()->method == 'updatekonsumen') {
            $updatekonsumen = LpjKonsumen::find(request()->idkonsumen);
            $updatekonsumen->id_lpj             = $data->id;
            $updatekonsumen->nama               = $request->nama;
            $updatekonsumen->alamat             = $request->alamat;
            $updatekonsumen->lokasi             = $request->lokasi;
            $updatekonsumen->tgl_lahir          = $request->tgllahir;
            $updatekonsumen->gender             = $request->gender;
            $updatekonsumen->notelp             = $request->notelp;
            $updatekonsumen->pekerjaan          = $request->pekerjaan;
            $updatekonsumen->nomor_mesin        = $request->nomormesin;
            $updatekonsumen->unit               = $request->unit;
            $updatekonsumen->id_lokasi          = $request->lokasi;
            $updatekonsumen->id_sales_people    = $request->sales;
            $updatekonsumen->finance_company    = $request->finance;
            $updatekonsumen->cash_credit        = $request->jenis;
            $updatekonsumen->hasil              = $request->hasil;
            $updatekonsumen->pengeluaran        = $request->pengeluaran;
            $updatekonsumen->dp                 = $request->dp;
            $updatekonsumen->merkmotor          = $request->merkmotor;
            $updatekonsumen->jenismotor         = $request->jenismotor;
            $updatekonsumen->save();

            return redirect()->to(route('cabang.lpj.getCreateFive').'/?id='.request()->id)->withFlashSuccess('Konsumen Berhasil Ditambahkan  ! ✅');
        }

        $kota           = Dealer::find(Auth::guard('cabang')->user()->dealer);
        $datalokasi     = Lokasi::where('kota_lokasi', 'LIKE', '%'.$kota->kota_dealer.'%')->get();
        $salespeople    = SalesPeople::where('dealer_sales_people', Auth::guard('cabang')->user()->dealer)->where('isActive',1)->get();
        $datafinance    = FinanceCompany::get();
        $datadana       = json_decode($data->dana_lpj ?? null, true);
        $datakonsumen   = LpjKonsumen::where('id_lpj', $data->id)->get();
        $datadisplay    = Display::get();
        $konsumen       = LpjKonsumen::find(request()->idkonsumen);

        if (request()->submit == 'draft') {
            return redirect()->route('cabang.lpj.index')->withFlashSuccess('Lpj Berhasil Tersimpan  ! ✅');
        } elseif (request()->submit == 'submit') {
            $cekkonsumen = LpjKonsumen::where('id_lpj', $data->id )->where('hasil', 4)->count();
            if($cekkonsumen != $data->target_penjualan_lpj){
                return redirect()->back()->withFlashDanger('Jumlah Konsumen Tidak Sesuai Inputan Data Penjualan. Mohon Sesuaikan !');
            } else {
                $data->status_lpj  = 4;
                $data->submit_date = Carbon::now();
                $data->save();

                return redirect()->to(route('cabang.lpj.index'))->withFlashSuccess('Lpj Berhasil Dibuat ! ✅');
            }
        }

        return view('cabang.lpj.5', compact('datalokasi', 'salespeople', 'datafinance', 'data', 'datadana', 'datakonsumen', 'datadisplay', 'konsumen'));
    }

    public function getCreateFiveUpload(Request $request) {

        $data           = Lpj::where('uuid', request()->id)->first();

        $kota           = Dealer::find(Auth::guard('cabang')->user()->dealer);
        $datalokasi     = Lokasi::where('kota_lokasi', 'LIKE', '%'.$kota->kota_dealer.'%')->get();
        $salespeople    = SalesPeople::where('dealer_sales_people', Auth::guard('cabang')->user()->dealer)->where('isActive',1)->get();
        $datafinance    = FinanceCompany::get();
        $datadana       = json_decode($data->dana_lpj ?? null, true);
        $datakonsumen   = LpjKonsumen::where('id_lpj', $data->id)->get();
        $datadisplay    = Display::get();
        $konsumen       = LpjKonsumen::find(request()->idkonsumen);

        $k_pekerjaan = collect([
            ['value' => 1, 'text' => 'Pegawai Negeri'],
            ['value' => 2, 'text' => 'Pegawai Swasta Pertanian/ Perkebunan/ Kehutanan/ Perikanan/ Peternakan'],
            ['value' => 3, 'text' => 'Pegawai Swasta Industri'],
            ['value' => 4, 'text' => 'Pegawai Swasta Konstruksi'],
            ['value' => 5, 'text' => 'Pegawai Swasta Pertambangan'],
            ['value' => 6, 'text' => 'Pegawai Swasta Jasa'],
            ['value' => 7, 'text' => 'Pegawai Swasta Perdagangan (Retail)'],
            ['value' => 8, 'text' => 'Ojek'],
            ['value' => 9, 'text' => 'Pertanian/ Perkebunan/ Kehutanan/ Perikanan/ Peternakan'],
            ['value' => 10, 'text' => 'Industri'],
            ['value' => 11, 'text' => 'Konstruksi'],
            ['value' => 12, 'text' => 'Pertambangan'],
            ['value' => 13, 'text' => 'Jasa'],
            ['value' => 14, 'text' => 'Perdagangan (Retail)'],
            ['value' => 15, 'text' => 'Mahasiswa/ Pelajar'],
            ['value' => 16, 'text' => 'Guru / Dosen'],
            ['value' => 17, 'text' => 'TNI/ Polri'],
            ['value' => 18, 'text' => 'Ibu Rumah Tangga'],
            ['value' => 19, 'text' => 'Dokter'],
            ['value' => 20, 'text' => 'Pengacara'],
            ['value' => 21, 'text' => 'Wartawan'],
            ['value' => 22, 'text' => 'Petani'],
            ['value' => 23, 'text' => 'Nelayan'],
            ['value' => 24, 'text' => 'Lainnya..']
        ]);

        $k_pengeluaran = collect([
            ['value' => 1, 'text' => '< Rp 900.000,- '],
            ['value' => 2, 'text' => 'Rp 900.001,- s/d Rp 1.250.000,- '],
            ['value' => 3, 'text' => 'Rp 1.250.001,- s/d Rp 1.750.000,- '],
            ['value' => 4, 'text' => 'Rp 1.750.001,- s/d Rp 2.500.000,- '],
            ['value' => 5, 'text' => 'Rp 2.500.001,- s/d Rp 4.000.000,- '],
            ['value' => 6, 'text' => 'Rp 4.000.001.- s/d Rp 6.000.000,- '],
            ['value' => 7, 'text' => '> Rp 6.000.000,- '],
        ]);

        $k_dp = collect([
            ['value' => 1, 'text' => '≤ 1 jt'],
            ['value' => 2, 'text' => '1 - 2 jt'],
            ['value' => 3, 'text' => '2 - 3 jt'],
            ['value' => 4, 'text' => '3 - 4 jt'],
            ['value' => 5, 'text' => '4 - 5 jt'],
            ['value' => 6, 'text' => '5 - 6 jt'],
            ['value' => 7, 'text' => '6 - 7 jt'],
            ['value' => 8, 'text' => '7 - 8 jt'],
            ['value' => 9, 'text' => '8 - 9 jt'],
            ['value' => 10, 'text' => '9 - 10 jt'],
            ['value' => 11, 'text' => '> 10 jt'],
        ]);

        $k_merkmotor = collect([
            ['value' => 1, 'text' => 'Honda'],
            ['value' => 2, 'text' => 'Yamaha'],
            ['value' => 3, 'text' => 'Suzuki'],
            ['value' => 4, 'text' => 'Kawasaki'],
            ['value' => 5, 'text' => 'Motor Lain'],
            ['value' => 6, 'text' => 'Belum pernah memiliki'],
        ]);

        $k_jenismotor = collect([
            ['value' => 1, 'text' => 'Sport'],
            ['value' => 2, 'text' => 'Cub (Bebek)'],
            ['value' => 3, 'text' => 'AT (Automatic)'],
            ['value' => 4, 'text' => 'Belum pernah memiliki'],
        ]);

        $k_hasil = collect([
            ['value' => 1, 'text' => 'Database'],
            ['value' => 2, 'text' => 'Prospecting'],
            ['value' => 3, 'text' => 'Polling'],
            ['value' => 4, 'text' => 'SSU'],
            ['value' => 5, 'text' => 'Reject'],
        ]);

        return view('cabang.lpj.5-upload-konsumen',
                compact('datalokasi', 'salespeople', 'datafinance', 'data', 'datadana', 'datakonsumen', 'datadisplay', 'konsumen',
                            'k_pekerjaan', 'k_pengeluaran', 'k_dp', 'k_merkmotor', 'k_jenismotor', 'k_hasil'));
    }

    public function postCreateFiveUploadData(Request $request) {
        try {
            $data = LpjKonsumen::find(request()->pk);
            $n = request()->name;
            $v = request()->value;

            $n == 1 ? $data->nama             = $v : null;
            $n == 2 ? $data->gender           = $v : null;
            $n == 3 ? $data->tgl_lahir        = $v : null;
            $n == 4 ? $data->alamat           = $v : null;
            $n == 5 ? $data->notelp           = $v : null;
            $n == 6 ? $data->pekerjaan        = $v : null;
            $n == 7 ? $data->pengeluaran      = $v : null;
            $n == 8 ? $data->merkmotor        = $v : null;
            $n == 9 ? $data->jenismotor       = $v : null;
            $n == 10 ? $data->id_lokasi       = $v : null;
            // $n == 11 ? $data->kecamatan       = $v : null;
            // $n == 12 ? $data->kelurahan       = $v : null;
            $n == 13 ? $data->nomor_mesin     = $v : null;
            $n == 14 ? $data->dp              = $v : null;
            $n == 15 ? $data->unit            = $v : null;
            $n == 16 ? $data->id_sales_people = $v : null;
            $n == 17 ? $data->cash_credit     = $v : null;
            $n == 18 ? $data->finance_company = $v : null;
            $n == 19 ? $data->hasil           = $v : null;

            $data->save();

            return response()->json([
                            'status' => 'Berhasil Disimpan',
                            'info' => $data
                        ]);
        } catch (\Throwable $th) {
            return response()->json([
                            'status' => 'Terjadi Kesalahan',
                            'info' => $th
                        ]);
        }
    }

    public function getCreateFiveUploadDataLokasi(Request $request) {
        $datalokasi = Lokasi::where('kota_lokasi', 'LIKE', '%'.request()->input('query').'%')
                        ->orWhere('kecamatan_lokasi', 'LIKE', '%'.request()->input('query').'%')
                        ->orWhere('kelurahan_lokasi', 'LIKE', '%'.request()->input('query').'%')
                        ->get();
        foreach ($datalokasi as $lokasi) {
            $data[] = collect([
                'id' => $lokasi->id , 'text' => $lokasi->kota_lokasi.' - '.$lokasi->kecamatan_lokasi.' - '.$lokasi->kelurahan_lokasi
            ]);
        }
        if(!empty($data)) {
            return $data;
        } else {
            return response()->json(['info' => 'Tidak Ada Data']);
        }
    }

    public function getShow(Request $request) {

        $data           = Lpj::where('uuid', request()->id)->first();

        $kota           = Dealer::find(Auth::guard('cabang')->user()->dealer);
        $datalokasi     = Lokasi::where('kota_lokasi', 'LIKE', '%'.$kota->kota_dealer.'%')->get();
        $salespeople    = SalesPeople::where('dealer_sales_people', Auth::guard('cabang')->user()->dealer)->where('isActive',1)->get();
        $datafinance    = FinanceCompany::get();
        $datadana       = json_decode($data->dana_lpj ?? null, true);
        $datakonsumen   = LpjKonsumen::where('id_lpj', $data->id)->get();
        $datadisplay    = Display::get();
        $konsumen       = LpjKonsumen::find(request()->idkonsumen);

        $dataapproval = ApprovalLpj::where('id_lpj', $data->id)
        ->select(['approval_lpjs.*', 'pusats.jabatan as jabatan_p'])
        ->join('pusats', 'approval_lpjs.user_approval', '=', 'pusats.id')
        ->orderBy('approval_lpjs.created_at')
        ->orderBy('pusats.jabatan')
        ->get();

        if (request()->submit == 'draft') {
            return redirect()->route('cabang.lpj.index')->withFlashSuccess('Lpj Berhasil Tersimpan  ! ✅');
        } elseif (request()->submit == 'submit') {
            $cekkonsumen = LpjKonsumen::where('id_lpj', $data->id )->count();
            if($cekkonsumen < 1){return redirect()->back()->withFlashDanger('Tidak Ada data Konsumen !');}
            else{
                $data->status_lpj = 2;
                $data->submit_date = Carbon::now();
                $data->save();

                return redirect()->to(route('cabang.lpj.index'));
            }
        }

        return view('cabang.lpj.show', compact('datalokasi', 'salespeople', 'datafinance', 'data', 'datadana', 'datakonsumen', 'datadisplay', 'konsumen','dataapproval'));
    }

    public function getCreate(Request $request) {

        $kota           = Dealer::find(Auth::guard('cabang')->user()->dealer);
        $datalokasi     = Lokasi::where('kota_lokasi', 'LIKE', '%'.$kota->kota_dealer.'%')->get();
        $salespeople    = SalesPeople::where('dealer_sales_people', Auth::guard('cabang')->user()->dealer)->where('isActive',1)->get();
        $datafinance    = FinanceCompany::get();
        $data           = Lpj::where('uuid', request()->id)->first();
        $datadana       = json_decode($data->dana_lpj ?? null, true);
        $datakonsumen   = LpjKonsumen::where('id_lpj', $data->id)->get();


        return view('cabang.lpj.create', compact('datalokasi', 'salespeople', 'datafinance', 'data', 'datadana', 'datakonsumen'));
    }

    public function postStore() {

        // foreach (request()->ket_dana as $key => $item) {
            $dokumentasi[] = [
                'canvasing_1'             => '',
                'canvasing_2'             => '',
                'capture_blast_wa_1'      => '',
                'capture_blast_wa_2'      => '',
                'posting_flyer_1'         => '',
                'posting_flyer_2'         => '',
                'interaksi_konsumen_1'    => '',
                'interaksi_konsumen_2'    => '',
                'unit_display_1'          => '',
                'unit_display_2'          => '',
                'live_season_1'           => '',
                'live_season_2'           => '',
            ];
        // }

        foreach (request()->ket_dana as $key => $item) {
            $dana[] = [
                'ket_dana'          => request()->ket_dana[$key],
                'beban_dealer_dana' => request()->beban_dealer_dana[$key],
                'beban_fincoy_dana' => request()->beban_fincoy_dana[$key],
                'beban_md_dana'     => request()->beban_md_dana[$key],
            ];
        }

        function cekarray($data) {
            if ($data == 'null' || $data == null) {
                return null;
            } else {
                return json_encode($data);
            }
        }

        if (request()->b == 'upload' || request()->b == 'draft' || request()->b == 'hapusfoto') {
            $st = 'draft';
        } elseif (request()->b == 'done') {
            $st = 'done';
        } else {
            $st = 'draft';
        }

        $data                               = Lpj::firstWhere('uuid', request()->uuid);
        $data->periode_start_lpj            = request()->tanggalstart ?? null ;
        $data->periode_end_lpj              = request()->tanggalend ?? null ;
        $data->lokasi_lpj                   = request()->lokasi ?? null ;
        $data->target_database_lpj          = request()->database ?? null ;
        $data->target_prospectus_lpj        = request()->prospectus ?? null ;
        $data->target_penjualan_lpj         = request()->penjualan ?? null ;
        $data->dana_lpj                     = cekarray($dana);
        $data->status_lpj                   = $st == 'done' ? 2 : 1;
        $data->submit_date                  = $st == 'done' ? Carbon::now() : null;
        $data->dokumentasi_lpj              = cekarray($dokumentasi);
        $data->problem_identification_lpj   = request()->problem ?? null ;
        $data->corrective_action_lpj        = request()->corrective ?? null ;
        $data->save();

        if (request()->b == 'konsumen') {
            $konsumen = new LpjKonsumen;
            $konsumen->id_lpj             = $data->id;
            $konsumen->nama               = request()->namakonsumen;
            $konsumen->alamat             = request()->alamatkonsumen;
            $konsumen->id_lokasi          = request()->lokasikonsumen;
            $konsumen->notelp             = request()->notelpkonsumen;
            $konsumen->type               = request()->typekonsumen;
            // $konsumen->status             = request()->konsumen;
            $konsumen->id_sales_people    = request()->saleskonsumen;
            $konsumen->cash_credit        = request()->jeniskonsumen;
            $konsumen->finance_company    = request()->financekonsumen;
            $konsumen->database           = request()->dbkonsumen ? true : false;
            $konsumen->prospecting        = request()->proskonsumen ? true : false;
            $konsumen->polling            = request()->polkonsumen ? true : false;
            $konsumen->reject             = request()->rejkonsumen ? true : false;
            $konsumen->ssu                = request()->ssukonsumen ? true : false;

            $lokasi = Lokasi::find(request()->lokasikonsumen);
            $konsumen->kecamatan = $lokasi->kecamatan_lokasi;
            $konsumen->kelurahan = $lokasi->kelurahan_lokasi;

            $konsumen->save();

            return redirect()->back()->withFlashSuccess('Data Konsumen Berhasil Ditambahkan  ! ✅');
        }

        return redirect()->route('cabang.lpj.index')->withFlashSuccess('Data Berhasil Tersimpan  ! ✅');
    }

    public function getKonsumen() {

        $datalokasi   = Lokasi::get();
        $salespeople  = SalesPeople::where('dealer_sales_people', Auth::guard('cabang')->user()->dealer)->where('isActive',1)->get();
        $datafinance  = FinanceCompany::get();
        $data         = LpjKonsumen::find(request()->id);

        return view('cabang.lpj.konsumen', compact('datalokasi', 'salespeople', 'datafinance', 'data'));
    }

    public function postUpdateKonsumen() {

        $update                     = LpjKonsumen::find(request()->id);
        $update->nama               = request()->namakonsumen;
        $update->alamat             = request()->alamatkonsumen;
        $update->id_lokasi          = request()->kelurahankonsumen;
        $update->notelp             = request()->notelpkonsumen;
        $update->type               = request()->typekonsumen;
        $update->id_sales_people    = request()->spkonsumen;
        $update->cash_credit        = request()->jeniskonsumen;
        $update->finance_company    = request()->financekonsumen;
        $update->database           = request()->dbkonsumen ? true : false;
        $update->prospecting        = request()->proskonsumen ? true : false;
        $update->polling            = request()->polkonsumen ? true : false;
        $update->reject             = request()->rejkonsumen ? true : false;
        $update->ssu                = request()->ssukonsumen ? true : false;
        $update->save();

        return redirect()->to(route('cabang.lpj.getCreate').'/?id='.request()->uuid)->withFlashSuccess('Data Konsumen Berhasil Ditambahkan  ! ✅');
    }

    public function getHapusLpj()
    {
        try {
            $hapus = Lpj::find(request()->id);
            $hapuskonsumen = LpjKonsumen::where('id_lpj', $hapus->id)->get();

            $banyakkonsumen = 0;
            if ($hapuskonsumen) {
                foreach ($hapuskonsumen as $key => $datakonsumen) {
                    $hap_ = LpjKonsumen::find($datakonsumen->id);
                    $hap_->delete();
                    $banyakkonsumen++;
                }
            }

            $hapus->delete();

            return redirect()->to(route('cabang.lpj.getCreateLpj'))->withFlashSuccess('Data LPJ Berhasil Dihapus !. Dengan memiliki '.$banyakkonsumen.' Data Konsumen ✅');
        } catch (\Throwable $th) {
            return redirect()->back()->withFlashDanger('Terjadi Kesalahan !, '.$th);
        }
    }

    public function lpjcancel(Request $request) {
        try {
            $data = Lpj::where('uuid',$request->uuid)->first();
            $getproposal = Proposal::find($data->id_proposal);

            $data->status_lpj = 3;
            $data->isCancel = 1;
            $data->reason = $request->reason;
            $data->actionplan = $request->actionplan;
            $data->tempat_lpj = $getproposal->tempat_proposal;
            $data->periode_start_lpj = $getproposal->periode_start_proposal;
            $data->periode_end_lpj = $getproposal->periode_end_proposal;
            $data->save();

            $getproposal->isCancel = 1;
            $getproposal->save();
            // return $data;

            return response()->json(["status" => "success", "message" => "Berhasil Simpan Data"]);

        } catch (\Throwable $th) {
            return redirect()->back()->withFlashDanger('Terjadi Kesalahan !, '.$th);
        }
    }

    public function lpjcontinue(Request $request) {
        try {
            $data = Lpj::where('uuid',$request->uuid)->first();
            $data->isCancel = 2;
            $data->save();

            return response()->json(["status" => "success", "message" => "Berhasil Simpan Data"]);

        } catch (\Throwable $th) {
            return redirect()->back()->withFlashDanger('Terjadi Kesalahan !, '.$th);
        }
    }

    public function getlpjdata(Request $request) {
        try {
            $data = Lpj::where('uuid',$request->uuid)->first();

            
            $checkapprlpj = ApprovalLpj::where('id_lpj', $data->id)->get();
            if(count($checkapprlpj) < 1) {
                $batas = 2; // promotion processor & promotion supervisor
                $approval = Pusat::orderBy('jabatan', 'ASC')->get();
                foreach ($approval->sortBy('jabatan') as $app) {
                    if ($app->jabatan <= $batas) {
                        $isiapproval = new ApprovalLpj;
                        $isiapproval->id_lpj   = $data->id;
                        $isiapproval->user_approval = $app->id;
                        $isiapproval->save();
                    }
                }
            }

            return response()->json($data)->setEncodingOptions(JSON_NUMERIC_CHECK);

            // return response()->json(["status" => "success", "message" => "Berhasil Simpan Data"]);

        } catch (\Throwable $th) {
            return redirect()->back()->withFlashDanger('Terjadi Kesalahan !, '.$th);
        }
    }

    public function getrevenuelpj(Request $request) {
        try {
            $id = $request->input('id');
            $model = $request->input('model');

            if($model == 'proposal') {
                $getid = Proposal::where('uuid',$id)->first();
                $data = LpjRevenue::where('proposal_id',$getid->id)->with('lpj')->first();
            } else {
                $getid = Lpj::leftJoin('proposals','lpjs.id_proposal','=','proposals.id')
                ->select('lpjs.*','proposals.id as idproposal')
                ->where('lpjs.uuid',$id)
                ->first();
                $data = LpjRevenue::where('proposal_id',$getid->idproposal)->with('lpj')->first();
            }

            if (!$data) {
                $data = new LpjRevenue();
                $data->proposal_id = $getid->id;
                $data->jasa = 0;
                $data->jasa_act = 0;
                $data->part = 0;
                $data->part_act = 0;
                $data->oli = 0;
                $data->oli_act = 0;
                $data->save();

            }
    
            return response()->json(["status" => "success", "message" => "Data Berhasil Disimpan", "data" => $data])->setEncodingOptions(JSON_NUMERIC_CHECK);

        } catch (\Throwable $th) {

            return response()->json(["status" => "error", "message" => $th]);

        }
    }

    public function updaterevenuelpj(Request $request) {
        try {
            //actual
            $id = $request->input('id');
            $model = $request->input('model');

            if($model == 'proposal') {
                $jasa = $request->input('jasa');
                $part = $request->input('part');
                $oli = $request->input('oli');
                $getid = Proposal::where('uuid',$id)->first();
                $data = LpjRevenue::where('proposal_id',$getid->id)->first();
    
                if (!$data) {
                    $data = new LpjRevenue();
                    $data->proposal_id = $getid->id;
                }
        
                $data->jasa = $jasa;
                $data->part = $part;
                $data->oli = $oli;
                $data->save();

            } else {

                $jasa = $request->input('jasa_act');
                $part = $request->input('part_act');
                $oli = $request->input('oli_act');
                $lpj = Lpj::leftJoin('proposals','lpjs.id_proposal','=','proposals.id')
                ->select('lpjs.*','proposals.id as idproposal')
                ->where('lpjs.uuid',$id)->first();
                $data = LpjRevenue::where('proposal_id',$lpj->idproposal)->first();
    
                if (!$data) {
                    $data = new LpjRevenue();
                    $data->proposal_id = $lpj->idproposal;
                }
        
                $data->jasa_act = $jasa;
                $data->part_act = $part;
                $data->oli_act = $oli;
                $data->save();
            }


            return response()->json(["status" => "success", "message" => "Data Berhasil Disimpan"])->setEncodingOptions(JSON_NUMERIC_CHECK);

        } catch (\Throwable $th) {

            return response()->json(["status" => "error", "message" => $th]);

        }
    }

    public function getunitentrylpj(Request $request) {
        try {
            $id = $request->input('id');
            $model = $request->input('model');

            if($model == 'proposal') {
                $getid = Proposal::where('uuid',$id)->first();
                $proposalid = $getid->id;
                $data = LpjUnitentry::where('proposal_id',$getid->id)->with('lpj')->get();
            } else {
                $getid = Lpj::
                // $getid = Lpj::leftJoin('proposals','lpjs.id_proposal','=','proposals.id')
                // ->select('lpjs.*','proposals.id as idproposal')
                where('uuid',$id)
                ->get();
                $getproposalid = Lpj::where('uuid',$id)->first();
                $proposalid = $getproposalid->id_proposal;
                $data = LpjUnitentry::where('proposal_id',$getproposalid->id_proposal)->with('lpj')->get();
            }

            if (count($data) < 1) {
                $unitname = [
                    'kpb_1',
                    'kpb_2',
                    'kpb_3',
                    'kpb_4',
                    'psl',
                    'psr',
                    'go',
                    'lr'
                ];
                foreach ($unitname as $key) {
                    $data = new LpjUnitentry();
                    $data->proposal_id = $proposalid;
                    $data->unit_nama = $key;
                    $data->unit_jumlah = 0;
                    $data->unit_jumlahact = 0;
                    $data->save();
                }

            }
    
            return response()->json(["status" => "success", "message" => "Data Berhasil Disimpan", "data" => $data])->setEncodingOptions(JSON_NUMERIC_CHECK);

        } catch (\Throwable $th) {

            return response()->json(["status" => "error", "message" => $th]);

        }
    }

    public function updatunitentrylpj(Request $request) {
        try {
            $id = $request->input('id');
            $model = $request->input('model');

            if($model == 'proposal') {
                $getid = Proposal::where('uuid',$id)->first();
                $data = LpjUnitentry::where('proposal_id',$getid->id)->with('lpj')->first();

                $unitJumlahData = [
                    'go' => $request->input('go'),
                    'kpb_1' => $request->input('kpb1'),
                    'kpb_2' => $request->input('kpb2'),
                    'kpb_3' => $request->input('kpb3'),
                    'kpb_4' => $request->input('kpb4'),
                    'lr' => $request->input('lr'),
                    'psl' => $request->input('psl'),
                    'psr' => $request->input('psr'),
                ];
        
                foreach ($unitJumlahData as $unitNama => $unitJumlah) {
                    LpjUnitentry::where('proposal_id', $data->proposal_id)
                        ->where('unit_nama', $unitNama)
                        ->update(['unit_jumlah' => $unitJumlah]);
                }

            } else {
                $getid = Lpj::leftJoin('proposals','lpjs.id_proposal','=','proposals.id')
                ->select('lpjs.*','proposals.id as idproposal')
                ->where('lpjs.uuid',$id)
                ->first();
                $data = LpjUnitentry::where('proposal_id',$getid->idproposal)->with('lpj')->first();

                $unitJumlahActData = [
                    'go' => $request->input('go_act'),
                    'kpb_1' => $request->input('kpb1_act'),
                    'kpb_2' => $request->input('kpb2_act'),
                    'kpb_3' => $request->input('kpb3_act'),
                    'kpb_4' => $request->input('kpb4_act'),
                    'lr' => $request->input('lr_act'),
                    'psl' => $request->input('psl_act'),
                    'psr' => $request->input('psr_act'),
                ];
        
                foreach ($unitJumlahActData as $unitNama => $unitJumlahAct) {
                    LpjUnitentry::where('proposal_id', $data->proposal_id)
                        ->where('unit_nama', $unitNama)
                        ->update(['unit_jumlahact' => $unitJumlahAct]);
                }
            }

            
    
            return response()->json(["status" => "success", "message" => "Data Berhasil Disimpan"])->setEncodingOptions(JSON_NUMERIC_CHECK);
    
        } catch (\Throwable $th) {
            // Catch any exceptions or errors and return an error response
            return response()->json(["status" => "error", "message" => $th->getMessage()]);
        }
    }

}
