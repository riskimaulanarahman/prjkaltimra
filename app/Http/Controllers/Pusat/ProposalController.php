<?php

namespace App\Http\Controllers\Pusat;

use App\Http\Controllers\Controller;
use App\Models\Dealer;
use App\Models\Display;
use App\Models\KategoriProposal;
use App\Models\Proposal;
use App\Models\Lokasi;
use App\Models\SalesPeople;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Image;
use App\Models\ApprovalProposal;
use Illuminate\Http\Request;
use App\Models\FinanceCompany;
use App\Exports\ProposalMainDealer;
use App\Models\Lpj;

class ProposalController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('pusat.auth:pusat');
    }

    /**
     * Show the Pusat dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    // public function index() {
    //     $datas = Proposal::where('status_proposal', Auth::guard('pusat')->user()->dealer)
    //             ->orderBy('updated_at', 'DESC')
    //             ->paginate()
    //             ;
    //     return view('pusat.proposal.index', compact('datas'));
    // }

    public function index()
    {
        if(request()->metode == 'hapus') {
            $hapus = Proposal::find(request()->id);
            $hapus->delete();
            return redirect()->back()->withFlashSuccess('Proposal Berhasil Terhapus  ! ✅');
        }

        $datalokasi   = Lokasi::get();
        $datakategori = KategoriProposal::get();
        $datadealer   = Dealer::get();
        $datas        = Proposal::orderBy('submit_date', 'ASC')
                            ->pj(request()->namapj)
                            ->kategori(request()->kategori)
                            ->lokasi(request()->lokasi)
                            ->statusProposal(request()->status)
                            ->tanggal(request()->tanggal)
                            ->cariDealer(request()->dealer)
                            ->where('status_proposal', '!=', 1)
                            ->where('user_approval', null)
                            ->paginate(10);
        return view('pusat.proposal.index', compact('datas', 'datalokasi', 'datakategori', 'datadealer'));
    }

    public function getInbox()
    {
        if(request()->metode == 'hapus') {
            $hapus = Proposal::find(request()->id);
            $hapus->delete();
            return redirect()->back()->withFlashSuccess('Proposal Berhasil Terhapus  ! ✅');
        }

        $datalokasi   = Lokasi::get();
        $datakategori = KategoriProposal::get();
        $datadealer   = Dealer::get();
        $dataarea     = Dealer::select('kota_dealer')->groupBy('kota_dealer')->get();
        if(request()->type == 'by-month'){
            $datas  = Proposal::orderBy('updated_at', 'ASC')
                        ->pj(request()->namapj)
                        ->kategori(request()->kategori)
                        ->lokasi(request()->lokasi)
                        ->statusProposal(request()->status)
                        ->cariDealer(request()->dealer)
                        ->area(request()->area)
                        ->where('status_proposal', '!=', 1)
                        ->when(request()->bulan, function ($query) {
                            if(request()->bulan != 'SEMUA' ){
                                $query->whereMonth('periode_start_proposal', request()->bulan);
                            }
                        })
                        ->when(request()->tahun, function ($query) {
                            $query->whereYear('periode_start_proposal', request()->tahun);
                        })
                        ->when(request()->tanggal_submit, function ($query) {
                            $query->whereBetween('submit_date', [request()->tanggal_submit_start, request()->tanggal_submit_end]);
                        })
                        ->where('inbox_md', true)
                        ->where('user_approval', Auth::guard('pusat')->user()->jabatan)
                        ->paginate(10);
        } else {
            $datas   = Proposal::orderBy('updated_at', 'ASC')
                        ->pj(request()->namapj)
                        ->kategori(request()->kategori)
                        ->lokasi(request()->lokasi)
                        ->statusProposal(request()->status)
                        ->cariDealer(request()->dealer)
                        ->area(request()->area)
                        ->where('status_proposal', '!=', 1)
                        ->when(request()->tanggal_mulai, function ($query) {
                            $query->whereBetween('periode_start_proposal', [request()->tanggal_mulai, request()->tanggal_akhir])
                                  ->whereBetween('periode_end_proposal', [request()->tanggal_mulai, request()->tanggal_akhir]);
                        })
                        ->when(request()->tanggal_submit, function ($query) {
                            $query->whereBetween('submit_date', [request()->tanggal_submit_start, request()->tanggal_submit_end]);
                        })
                        ->where('inbox_md', true)
                        ->where('user_approval', Auth::guard('pusat')->user()->jabatan)
                        ->paginate(10);
        }

        // $datas        = Proposal::orderBy('updated_at', 'ASC')
        //                     ->pj(request()->namapj)
        //                     ->kategori(request()->kategori)
        //                     ->lokasi(request()->lokasi)
        //                     ->statusProposal(request()->status)
        //                     ->tanggal(request()->tanggal)
        //                     ->cariDealer(request()->dealer)
        //                     ->where('status_proposal', '!=', 1)
        //                     ->where('inbox_md', true)
        //                     ->where('user_approval', Auth::guard('pusat')->user()->jabatan)
        //                     ->paginate(10);
        return view('pusat.proposal.inbox', compact('datas', 'datalokasi', 'datakategori', 'datadealer', 'dataarea'));
    }

    public function getData()
    {
        if(request()->metode == 'hapus') {
            $hapus = Proposal::find(request()->id);
            $hapus->delete();
            return redirect()->back()->withFlashSuccess('Proposal Berhasil Terhapus  ! ✅');
        }

        $datalokasi   = Lokasi::areaKota(request()->area)->get();
        $datakategori = KategoriProposal::get();
        $datadealer   = Dealer::cariKota(request()->area)->get();
        $dataarea     = Dealer::select('kota_dealer')->groupBy('kota_dealer')->get();
        if(request()->type == 'by-month'){
            $datas  = Proposal::orderBy('submit_date', 'DESC')
                        ->pj(request()->namapj)
                        ->kategori(request()->kategori)
                        ->lokasi(request()->lokasi)
                        ->statusProposal(request()->status)
                        ->cariDealer(request()->dealer)
                        ->area(request()->area)
                        ->where('status_proposal', '!=', 1)
                        ->when(request()->bulan, function ($query) {
                            if(request()->bulan != 'SEMUA' ){
                                $query->whereMonth('periode_start_proposal', request()->bulan);
                            }
                        })
                        ->when(request()->tahun, function ($query) {
                            $query->whereYear('periode_start_proposal', request()->tahun);
                        })
                        ->when(request()->tanggal_submit, function ($query) {
                            $query->whereBetween('submit_date', [request()->tanggal_submit_start, request()->tanggal_submit_end]);
                        })
                        ->paginate(10);
        } else {
            $datas   = Proposal::orderBy('submit_date', 'DESC')
                        ->pj(request()->namapj)
                        ->kategori(request()->kategori)
                        ->lokasi(request()->lokasi)
                        ->statusProposal(request()->status)
                        ->cariDealer(request()->dealer)
                        ->area(request()->area)
                        ->where('status_proposal', '!=', 1)
                        ->when(request()->tanggal_mulai, function ($query) {
                            $query->whereBetween('periode_start_proposal', [request()->tanggal_mulai, request()->tanggal_akhir])
                                  ->whereBetween('periode_end_proposal', [request()->tanggal_mulai, request()->tanggal_akhir]);
                        })
                        ->when(request()->tanggal_submit, function ($query) {
                            $query->whereBetween('submit_date', [request()->tanggal_submit_start, request()->tanggal_submit_end]);
                        })
                        ->paginate(10);
        }

        return view('pusat.proposal.data', compact('datas', 'datalokasi', 'datakategori', 'datadealer', 'dataarea'));
    }

    public function postStatusHistory()
    {
        $proposal  = Proposal::find(request()->id);

        if (request()->idapproval) {
            $approval = ApprovalProposal::find(request()->idapproval);
        } else {
            $approval = ApprovalProposal::where('id_proposal', $proposal->id)->where('user_approval', Auth::guard('pusat')->user()->id)->latest()->first();
        }
        $approval->status_approval        = request()->status;
        $approval->keterangan_approval    = request()->keterangan;
        $approval->save();

        $dataappr = ApprovalProposal::where('status_approval', null)->where('id_proposal', $approval->id_proposal)->get();

        if (request()->status == 1) { // approve
            if (Auth::guard('pusat')->user()->jabatan == $proposal->kategori->batas_approval || Auth::guard('pusat')->user()->jabatan == 5){
                $proposal->status_proposal = 4; // Final Approve
                $proposal->inbox_md        = false;
            } else {
                $proposal->status_proposal = 3; // Partial Approve
                $proposal->user_approval   = Auth::guard('pusat')->user()->jabatan+1;
                $proposal->inbox_md        = true;
            }

        } elseif (request()->status == 2) { // revise
            $proposal->status_proposal = 5;
        } elseif (request()->status == 3) { // rejected
            $proposal->status_proposal = 6;
            foreach ($dataappr as $reject) {
                $rejectapproval                         = ApprovalProposal::find($reject->id);
                $rejectapproval->status_approval        = '-';
                $rejectapproval->keterangan_approval    = '-';
                $rejectapproval->save();
            }
        }
        $proposal->inbox_d  = true;
        $proposal->save();

        return redirect()->route('pusat.proposal.getInbox')->withFlashSuccess('Update Approval Proposal Berhasil ! ✅');
    }

    public function getTes()
    {
        return view('pusat.proposal.tes');
    }

    public function getDataLokasi()
    {
        $datalokasi = [];

        if(request()->q){
            $datalokasi  = Lokasi::select("kota_lokasi", "kecamatan_lokasi", "kelurahan_lokasi")
                    ->whereRaw('LOWER(kota_lokasi) LIKE ? ', '%' . strtolower(request()->q) . '%')
                    ->orWhereRaw('LOWER(kecamatan_lokasi) LIKE ? ', '%' . strtolower(request()->q) . '%')
                    ->orWhereRaw('LOWER(kota_lokasi) LIKE ? ', '%' . strtolower(request()->q) . '%')
            		->get();
        }
        return response()->json($datalokasi);
    }

    public function getUpload()
    {
        if (!null == request()->uuid) {
            return view('pusat.proposal.upload');
        } else {
            return redirect()->route('pusat.pameran.index');
        }

    }

    public function getPilihJenisProposal()
    {
        $datas = KategoriProposal::orderBy('keterangan_kategori', 'asc')->get();
        return view('pusat.proposal.jenis', compact('datas'));
    }

    public function postPilihJenisProposalBuat()
    {
        if (request()->metode == 'buat') {
            $buat                     = new Proposal;
            $buat->uuid               = Str::uuid();
            $buat->user_proposal      = Auth::guard('pusat')->user()->id;
            $buat->kategori_proposal  = request()->kategori;
            $buat->status_proposal    = 1;

            $buat->save();

            return redirect()->to(route('pusat.proposal.getCreate').'?id='.$buat->uuid);
        } else {
            return redirect()->route('pusat.proposal.index');
        }

    }

    public function getCreate()
    {
        // if (request()->id == null) {
        //     return redirect()->route('pusat.proposal.index');
        // }

        $datalokasi         = Lokasi::get();
        $datadisplay        = Display::get();
        $datadealer         = Dealer::get();
        $salespeople        = SalesPeople::get();
        $datafinance        = FinanceCompany::get();
        $data               = Proposal::where('uuid', request()->id)->first();
        $datadana           = json_decode($data->dana_proposal  ?? null, true);
        $datasalespeople    = json_decode($data->sales_people_proposal  ?? null, true);
        $datadisplayunit    = json_decode($data->display_proposal  ?? null, true);

        // if (null == $data) {
        //     return redirect()->route('pusat.proposal.index');
        // }
        $cektitikaktif      = Proposal::where('status_proposal', 4)->where('kategori_proposal', $data->kategori_proposal)->get();
        $kota               = Dealer::find($data->dealer_proposal);

        return view('pusat.proposal.create', compact('data', 'datalokasi', 'datadisplay', 'datadealer', 'salespeople', 'datadana', 'datasalespeople', 'datafinance', 'datadisplayunit', 'cektitikaktif', 'kota'));
    }

    public function getShow()
    {
        // dd(request()->id);
        if (!null == request()->id) {

            $datalokasi         = Lokasi::get();
            $datadisplay        = Display::get();
            $datadealer         = Dealer::get();
            $salespeople        = SalesPeople::get();
            $datafinance        = FinanceCompany::get();
            $data               = Proposal::where('uuid', request()->id)->first();
            $datadana           = json_decode($data->dana_proposal  ?? null, true);
            $datasalespeople    = json_decode($data->sales_people_proposal  ?? null, true);
            $datadisplayunit    = json_decode($data->display_proposal  ?? null, true);


            // if (null == $data || $data->status_proposal == 1) {
            //     return redirect()->route('pusat.proposal.index');
            // }

            $cektitikaktif      = Proposal::where('status_proposal', 4)->where('kategori_proposal', $data->kategori_proposal)->get();

            $dataapproval = ApprovalProposal::where('id_proposal', $data->id)
                                    ->select(['approval_proposals.*', 'pusats.jabatan as jabatan_p'])
                                    ->join('pusats', 'approval_proposals.user_approval', '=', 'pusats.id')
                                    ->orderBy('approval_proposals.created_at')
                                    ->orderBy('pusats.jabatan')
                                    ->get();
            $kota               = Dealer::find($data->dealer_proposal);


            //Update Inbox Open
            // if ($data->user_approval == Auth::guard('pusat')->user()->jabatan) {
                // $data->inbox_md = false;
                // $data->save();
            // }

            return view('pusat.proposal.show', compact('data', 'datalokasi', 'datadisplay', 'datadealer', 'salespeople', 'datadana', 'datasalespeople', 'dataapproval', 'datafinance', 'datadisplayunit', 'cektitikaktif', 'kota'));
        } else {
            return redirect()->route('pusat.proposal.index');
        }
    }

    public function postStore(Request $request)
    {
        if(request()->ket_dana){
            foreach (request()->ket_dana as $key => $item) {
                $dana[] = [
                    'ket_dana'          => request()->ket_dana[$key],
                    'beban_dealer_dana' => request()->beban_dealer_dana[$key],
                    'beban_fincoy_dana' => request()->beban_fincoy_dana[$key],
                    'beban_md_dana'     => request()->beban_md_dana[$key],
                ];
            }
        } else {
            $dana = null;
        }

        if(request()->iddisplayunit){
        foreach (request()->iddisplayunit as $keydis => $item) {
                $displaydata[] = [
                    'iddisplayunit'     => request()->iddisplayunit[$keydis],
                    'displayunit'       => request()->displayunit[$keydis],
                ];
            }
        } else {
            $displaydata = null;
        }

        $dt = Carbon::now();
        $no = Proposal::whereYear('submit_date', $dt->year)->where('status_proposal', !null)->count();

        if (request()->b == 'upload' || request()->b == 'draft' || request()->b == 'hapusfoto') {
            $st = 'draft';
        } elseif (request()->b == 'done') {
            $st = 'done';
        } else {
            $st = 'draft';
        }

        $total_dana_ = request()->total ? str_replace(",","",request()->total) : null;
        $total_dana_ = request()->total ? str_replace(".","",$total_dana_) : null;

        $data                                   = Proposal::firstWhere('uuid', request()->uuid);
        $data->no_proposal                      = $st == 'draft' ? null : $no.'/'.$dt->year.'/'.$dt->month.'/'.$dt->day.'/'.$data->kategori_proposal.'/'.$data->dealer->kode_dealer ;
        $data->status_proposal                  = 1;
        $data->create_proposal                  = 1;
        $data->lokasi_proposal                  = request()->lokasi ?? null ;
        $data->dealer_proposal                  = request()->dealer ?? null;
        $data->display_proposal                 = !null == $displaydata ? json_encode($displaydata) : null ;
        $data->finance_proposal                 = request()->finance ? json_encode(request()->finance) : null ;
        $data->target_database_proposal         = request()->targetdata ?? null ;
        $data->target_penjualan_proposal        = request()->targetjual ?? null ;
        $data->target_prospectus_proposal       = request()->targetpros ?? null ;
        $data->target_downloader_proposal       = request()->targetdown ?? null ;
        $data->periode_start_proposal           = request()->tanggalstart ?? null ;
        $data->periode_end_proposal             = request()->tanggalend ?? null ;
        $data->program_proposal                 = request()->program ?? null ;
        $data->tempat_proposal                  = request()->tempat ?? null ;
        $data->lat_proposal                     = request()->lat ?? null ;
        $data->long_proposal                    = request()->long ?? null ;
        $data->dana_proposal                    = !null == $dana ? json_encode($dana) : null ;
        // $data->penanggung_jawab_proposal        = request()->pjid ?? null ;
        // $data->sales_people_proposal            = request()->idsales ? json_encode(request()->idsales) : null ;
        $data->history_penjualan_proposal       = request()->historypenjualan ?? null ;
        $data->latar_belakang_proposal          = request()->latarbelakang ?? null ;
        $data->latar_kompetitor_proposal        = request()->latarkompetitor ?? null ;
        $data->kondisi_penjualan_m_1_proposal   = request()->mmin1 ?? null ;
        $data->kondisi_penjualan_m1_proposal    = request()->m1 ?? null ;
        $data->tujuan_proposal                  = request()->tujuan ?? null ;
        // $data->fotolokasi_proposal              = cekarray(request()->fotolokasi) ?? null ;
        $data->total_dana_proposal              = $total_dana_ ;
        $data->save();


        //
        if($st == 'done'){
            $request->validate([
                'lokasi'     => 'required',
                'display'    => 'required',
                'finance'    => 'required',
                'targetdata' => 'required',
                'targetjual' => 'required',
                'targetpros' => 'required',
                'targetdown' => 'required',
                'tanggalstart' => 'required',
                'tanggalend' => 'required',
                'program' => 'required',
                'tempat' => 'required',
                'lat' => 'required',
                'long' => 'required',
                'ket_dana' => 'required',
                // 'pjid' => 'required',
                // 'idsales' => 'required',
                'historypenjualan' => 'required',
                'latarbelakang' => 'required',
                'latarkompetitor' => 'required',
                'mmin1' => 'required',
                'm1' => 'required',
                'tujuan' => 'required',
            ], [ // 2nd array is the rules custom message
                'required' => 'Kolom :attribute Harus Diisi !.'
            ], [ // 3rd array is the fields custom name
                'lokasi'     => 'Lokasi',
                'display'    => 'Display',
                'finance'    => 'Finance Company',
                'targetdata' => 'Target Database',
                'targetjual' => 'Target Penjualan',
                'targetpros' => 'Target Prospectus',
                'targetdown' => 'Target Downloader',
                'tanggalstart' => 'Periode Start',
                'tanggalend' => 'Periode End',
                'program' => 'Program',
                'tempat' => 'Tempat',
                'lat' => 'Titik Lokasi Map Latitude',
                'long' => 'Titik Lokasi Map Longtitude',
                'ket_dana' => 'List Dana',
                // 'pjid' => 'Penanggung Jawab',
                // 'idsales' => 'Sales People',
                'historypenjualan' => 'History Penjualan',
                'latarbelakang' => 'Latar Belakang',
                'latarkompetitor' => 'Latar Kompetitor',
                'mmin1' => 'M-1',
                'm1' => 'M1',
                'tujuan' => 'Tujuan',
            ]);

            if ($data->foto_lokasi_proposal == null) {
                return redirect()->back()->withFlashDanger('Foto Belum Ada ! Upload Terlebih Dahulu');
            }

            $data->status_proposal = 4;
            $data->save();

        }


        if (request()->b == 'upload') {
            return redirect()->to(route('pusat.proposal.getUpload').'?uuid='.request()->uuid);
        } elseif (request()->b == 'done' || request()->b == 'draft') {
            return redirect()->route('pusat.proposal.index')->withFlashSuccess('Data Proposal Tersimpan  ! ✅');
        } else {
            $d    = json_decode($data->foto_lokasi_proposal);
            foreach ($d as $foto_) {
                if ($foto_  != request()->b) {
                    $foto[] = $foto_ ;
                }
            }
            $data->foto_lokasi_proposal = !empty($foto) ? json_encode($foto) : null;
            $data->save();
            return redirect()->back()->withFlashSuccess('Foto Berhasil Terhapus  ! ✅');
        }

    }

    public function postUploadFoto()
    {
        request()->validate([
            'filepond' => 'required|image|max:10240',
        ]);
        $file_foto      = request()->file('filepond');

        $data       = Proposal::firstWhere('uuid', request()->uuid);
        $namafoto   = $data->id.'-'.substr($data->uuid,0,8);
        $n          = '-'.Str::random(5).'.';
        $datafoto   = $namafoto.$n.$file_foto->getClientOriginalExtension();

        if (!null == $data->foto_lokasi_proposal) {
            $d = json_decode($data->foto_lokasi_proposal);
            foreach ($d as $foto_) {
                $foto[] = $foto_;
            }
            $foto[] = $datafoto;
        } else {
            $foto[] = $datafoto;
        }


        $fotoproposal   = Image::make($file_foto);
        // if ($this->cekeventaktifinvoice) {
        //     $lokasiupload   = public_path('../../../public_html/'.$this->cekeventaktifinvoice->produk->domain.'/app/app/public/bukti-transfer/');
        // } else {
            $lokasiupload   = public_path('/upload-foto/');
        // }
        $fotoproposal->resize(800, null, function ($constraint) {
            $constraint->aspectRatio();
        });
        $fotoproposal->save($lokasiupload.$datafoto);

        $data->foto_lokasi_proposal = json_encode($foto);
        $data->save();
    }

    public function postExportDataProposal()
    {
        if(request()->type == 'date'){
            return (new ProposalMainDealer)
                    ->status(request()->status ?? null)
                    ->kategori(request()->kategori ?? null)
                    ->area(request()->area ?? null)
                    ->dealer(request()->dealer ?? null)
                    ->tanggalst(request()->tanggal_start_e ?? null)
                    ->tanggalen(request()->tanggal_end_e ?? null)
                    ->type(1)
                    ->download('Data Proposal.xlsx');
        } elseif(request()->type == 'month') {
            return (new ProposalMainDealer)
                    ->status(request()->status ?? null)
                    ->kategori(request()->kategori ?? null)
                    ->area(request()->area ?? null)
                    ->dealer(request()->dealer ?? null)
                    ->bulan(request()->bulan ?? null)
                    ->tahun(request()->tahun ?? null)
                    ->type(2)
                    ->download('Data Proposal.xlsx');
        }

        return redirect()->route('pusat.proposal.getData');

    }
}
