<?php

namespace App\Http\Controllers\Cabang;

use App\Http\Controllers\Controller;
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
use App\Models\Dealer;
use Illuminate\Http\Request;
use App\Models\Pusat;
use Illuminate\Database\Eloquent\Builder;
use App\Models\FinanceCompany;
use App\Models\AdditionalProposal;

class ProposalController extends Controller
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

    public function index()
    {
        if(request()->metode == 'hapus') {
            $hapus = Proposal::find(request()->id);
            $hapus->delete();
            return redirect()->back()->withFlashSuccess('Proposal Berhasil Terhapus  ! ✅');
        }

        $datalokasi   = Lokasi::get();
        $datakategori = KategoriProposal::get();
        $datas        = Proposal::where('dealer_proposal', Auth::guard('cabang')->user()->dealer)
                            ->pj(request()->namapj)
                            ->kategori(request()->kategori)
                            ->lokasi(request()->lokasi)
                            ->statusProposal(request()->status)
                            ->tanggal(request()->tanggal)
                            ->orderBy('created_at', 'DESC')
                            ->paginate(10);
        return view('cabang.proposal.index', compact('datas', 'datalokasi', 'datakategori'));
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
        $datas        = Proposal::where('dealer_proposal', Auth::guard('cabang')->user()->dealer)
                            ->pj(request()->namapj)
                            ->kategori(request()->kategori)
                            ->lokasi(request()->lokasi)
                            ->statusProposal(request()->status)
                            ->tanggal(request()->tanggal)
                            ->where('inbox_d', true)
                            ->orderBy('updated_at', 'DESC')
                            ->paginate(10);
        return view('cabang.proposal.index', compact('datas', 'datalokasi', 'datakategori'));
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
            return view('cabang.proposal.upload');
        } else {
            return redirect()->route('cabang.pameran.index');
        }

    }

    public function getTess()
    {
        $oke =  Pusat::orderBy('jabatan', 'asc')->get();
        foreach ($oke as $app) {
            $d[]   = $app->id;
        }
        return $d;
    }

    public function getPilihJenisProposal()
    {
        $datas = KategoriProposal::orderBy('keterangan_kategori', 'asc')->get();
        return view('cabang.proposal.jenis', compact('datas'));
    }

    public function postPilihJenisProposalBuat()
    {
        if (request()->metode == 'buat') {
            $buat                     = new Proposal;
            $buat->uuid               = Str::uuid();
            $buat->user_proposal      = Auth::guard('cabang')->user()->id;
            $buat->dealer_proposal    = Auth::guard('cabang')->user()->dealer;
            $buat->kategori_proposal  = request()->kategori;
            $buat->status_proposal    = 1;

            $buat->save();

            return redirect()->to(route('cabang.proposal.getCreate').'?id='.$buat->uuid);
        } else {
            return redirect()->route('cabang.proposal.index');
        }

    }

    public function getCreate()
    {
        // if (request()->id == null) {
        //     return redirect()->route('cabang.proposal.index');
        // }

        $kota = Dealer::find(Auth::guard('cabang')->user()->dealer);

        $datalokasi         = Lokasi::where('kota_lokasi', 'LIKE', '%'.$kota->kota_dealer.'%')->get();
        $datadisplay        = Display::get();
        $datafinance        = FinanceCompany::get();
        $salespeople        = SalesPeople::where('dealer_sales_people', Auth::guard('cabang')->user()->dealer)->where('isActive',1)->get();
        $data               = Proposal::where('uuid', request()->id)->first();
        $datadana           = json_decode($data->dana_proposal ?? null, true);
        $datasalespeople    = json_decode($data->sales_people_proposal ?? null, true);
        $datadisplayunit    = json_decode($data->display_proposal ?? null, true);
        $dataunitentry      = json_decode($data->unit_entry_proposal ?? null, true);
        $cektitikaktif      = Proposal::where('status_proposal', 4)->where('kategori_proposal', $data->kategori_proposal)
                        // ->where('dealer_proposal', Auth::guard('cabang')->user()->dealer)
                        ->get();

        if (null == $data) {
            return redirect()->route('cabang.proposal.index');
        }

        return view('cabang.proposal.create', compact('data', 'dataunitentry', 'datalokasi', 'datadisplay', 'salespeople', 'datadana', 'datasalespeople', 'datafinance', 'datadisplayunit', 'cektitikaktif', 'kota'));
    }
    public function getShow()
    {
        // dd(request()->id);
        // if (!null == request()->id) {
            $kota = Dealer::find(Auth::guard('cabang')->user()->dealer);

            $datalokasi         = Lokasi::get();
            $datadisplay        = Display::get();
            $datafinance        = FinanceCompany::get();
            $salespeople        = SalesPeople::where('isActive',1)->get();
            $data               = Proposal::where('uuid', request()->id)->first();
            $datadana           = json_decode($data->dana_proposal ?? null, true);
            $datasalespeople    = json_decode($data->sales_people_proposal ?? null, true);
            $datadisplayunit    = json_decode($data->display_proposal ?? null, true);
            $dataunitentry      = json_decode($data->unit_entry_proposal ?? null, true);

            // if (null == $data || $data->status_proposal == 1) {
            //     return redirect()->route('cabang.proposal.index');
            // }

            $cektitikaktif      = Proposal::where('status_proposal', 4)->where('kategori_proposal', $data->kategori_proposal)->where('dealer_proposal', Auth::guard('cabang')->user()->dealer)->get();

            $dataapproval = ApprovalProposal::where('id_proposal', $data->id)
                                    ->select(['approval_proposals.*', 'pusats.jabatan as jabatan_p'])
                                    ->join('pusats', 'approval_proposals.user_approval', '=', 'pusats.id')
                                    ->orderBy('approval_proposals.created_at')
                                    ->orderBy('pusats.jabatan')
                                    ->get();

            //Update Inbox Open
            if ($data->status_proposal != 5 ) {
                $data->inbox_d = false;
                $data->save();
            }


            return view('cabang.proposal.show', compact('data', 'datalokasi', 'datadisplay', 'salespeople', 'datadana', 'datasalespeople', 'dataapproval', 'datafinance', 'datadisplayunit', 'cektitikaktif', 'kota', 'dataunitentry'));
        // } else {
        //     return redirect()->route('cabang.proposal.index');
        // }
    }

    public function postStore(Request $request)
    {
        $data  = Proposal::firstWhere('uuid', request()->uuid);

        if(data_get(request()->ket_dana, 0) != null){
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
            foreach (request()->iddisplayunit as $keydis => $item_d) {
                $displaydata[] = [
                    'iddisplayunit'     => request()->iddisplayunit[$keydis],
                    'displayunit'       => request()->displayunit[$keydis],
                ];
            }
        } else {
            $displaydata = null;
        }

        if(request()->finance){
            foreach (request()->finance as $keyfin => $item_f) {
                    $finance = json_encode(request()->finance);
                }
        } else {
            $finance = null;
        }

        $dt         = Carbon::now();
        $no_init    = Proposal::whereYear('created_at', $dt->year)->where('status_proposal', !null)->count();
        $no         = $no_init == 0 ? $no_init+1 : $no_init;

        if (request()->b == 'upload' || request()->b == 'draft' || request()->b == 'hapusfoto' || request()->b == 'refresh') {
            $st = 'draft';
        } elseif (request()->b == 'done') {
            $st = 'done';
        } else {
            $st = 'draft';
        }

        $addtional_proposal = AdditionalProposal::where('id_proposal', $data->id)->first();
        if ($addtional_proposal) {
            $addtional_proposal->jasa = request()->jasa ?? null;
            $addtional_proposal->part = request()->part ?? null;
            $addtional_proposal->oli  = request()->oli ?? null;
            $addtional_proposal->save();
        } else {
            $addtional_proposal              = new AdditionalProposal;
            $addtional_proposal->id_proposal = $data->id;
            $addtional_proposal->jasa        = request()->jasa ?? null;
            $addtional_proposal->part        = request()->part ?? null;
            $addtional_proposal->oli         = request()->oli ?? null;
            $addtional_proposal->save();
        }

        if (request()->idunitentry) {
            foreach (request()->idunitentry as $keyue => $item_d) {
                $unitentrydata[] = [
                    'idunitentry'     => request()->idunitentry[$keyue],
                    'unitentry'       => request()->unitentry[$keyue],
                ];

                if (request()->idunitentry[$keyue]     == 1) { $addtional_proposal->kpb_1 = request()->unitentry[$keyue];}
                elseif (request()->idunitentry[$keyue] == 2) { $addtional_proposal->kpb_2 = request()->unitentry[$keyue];}
                elseif (request()->idunitentry[$keyue] == 3) { $addtional_proposal->kpb_3 = request()->unitentry[$keyue];}
                elseif (request()->idunitentry[$keyue] == 4) { $addtional_proposal->kpb_4 = request()->unitentry[$keyue];}
                elseif (request()->idunitentry[$keyue] == 5) { $addtional_proposal->psl   = request()->unitentry[$keyue];}
                elseif (request()->idunitentry[$keyue] == 6) { $addtional_proposal->psr   = request()->unitentry[$keyue];}
                elseif (request()->idunitentry[$keyue] == 7) { $addtional_proposal->go    = request()->unitentry[$keyue];}
                elseif (request()->idunitentry[$keyue] == 8) { $addtional_proposal->lr    = request()->unitentry[$keyue];}

                $addtional_proposal->save();
            }
        } else {
            $unitentrydata = null;
        }

        $total_dana_ = request()->total ? str_replace(",","",request()->total) : null;
        $total_dana_ = request()->total ? str_replace(".","",$total_dana_) : null;

        $data->no_proposal                      = $st == 'draft' ? null : $no.'/'.$dt->year.'/'.$dt->month.'/'.$dt->day.'/'.$data->kategori_proposal.'/'.$data->dealer->kode_dealer ;
        $data->status_proposal                  = 1;
        $data->lokasi_proposal                  = request()->lokasi ?? null ;
        $data->display_proposal                 = !null == $displaydata ? json_encode($displaydata) : null ;
        $data->finance_proposal                 = !null == $finance ? $finance : null ;
        $data->target_database_proposal         = request()->targetdata ?? null ;
        $data->target_penjualan_proposal        = request()->targetjual ?? null ;
        $data->target_prospectus_proposal       = request()->targetpros ?? null ;
        $data->target_downloader_proposal       = request()->targetdown ?? null ;
        $data->periode_start_proposal           = request()->tanggalstart ?? null ;
        $data->periode_end_proposal             = request()->tanggalend ?? null ;
        $data->program_proposal                 = request()->program ?? null ;
        $data->tempat_proposal                  = request()->tempat ?? null ;
        $data->lat_proposal                     = request()->lat ? Str::replace(',', '', request()->lat) : null ;
        $data->long_proposal                    = request()->long ? Str::replace(',', '', request()->long) : null ; ;
        $data->dana_proposal                    = !null == $dana ? json_encode($dana) : null ;
        $data->penanggung_jawab_proposal        = request()->pjid ?? null ;
        $data->sales_people_proposal            = request()->idsales ? json_encode(request()->idsales) : null ;
        $data->history_penjualan_proposal       = request()->historypenjualan ?? null ;
        $data->latar_belakang_proposal          = request()->latarbelakang ?? null ;
        $data->latar_kompetitor_proposal        = request()->latarkompetitor ?? null ;
        $data->kondisi_penjualan_m_1_proposal   = request()->mmin1 ?? null ;
        $data->kondisi_penjualan_m1_proposal    = request()->m1 ?? null ;
        $data->tujuan_proposal                  = request()->tujuan ?? null ;
        // $data->fotolokasi_proposal              = cekarray(request()->fotolokasi) ?? null ;
        $data->total_dana_proposal              = $total_dana_ ;
        $data->unit_entry_proposal              = $unitentrydata;
        $data->total_unit_entry_proposal        = request()->totaluniten ?? null ;
        $data->total_revenue_proposal           = request()->totalrev ?? null ;

        $data->save();


        if($st == 'done'){
            $request->validate([
                'lokasi'            => 'required',
                'display'           => 'required',
                'finance'           => 'required',
                'targetdata'        => 'required',
                'targetjual'        => 'required',
                'targetdown'        => 'required',
                'tanggalstart'      => 'required',
                'tanggalend'        => 'required',
                'program'           => 'required',
                'tempat'            => 'required',
                'lat'               => 'required',
                'long'              => 'required',
                'ket_dana'          => 'required',
                'pjid'              => 'required',
                'idsales'           => 'required',
                'historypenjualan'  => 'required',
                'latarbelakang'     => 'required',
                'latarkompetitor'   => 'required',
                'mmin1'             => 'required',
                'm1'                => 'required',
                'tujuan'            => 'required',
            ], [ // 2nd array is the rules custom message
                'required'  => 'Kolom :attribute Harus Diisi !.'
            ], [ // 3rd array is the fields custom name
                'lokasi'            => 'Lokasi',
                'display'           => 'Display',
                'finance'           => 'Finance Company',
                'targetdata'        => 'Target Database',
                'targetjual'        => 'Target Penjualan',
                'targetpros'        => 'Target Prospectus',
                'targetdown'        => 'Target Downloader',
                'tanggalstart'      => 'Periode Start',
                'tanggalend'        => 'Periode End',
                'program'           => 'Program',
                'tempat'            => 'Tempat',
                'lat'               => 'Titik Lokasi Map Latitude',
                'long'              => 'Titik Lokasi Map Longtitude',
                'ket_dana'          => 'List Dana',
                'pjid'              => 'Penanggung Jawab',
                'idsales'           => 'Sales People',
                'historypenjualan'  => 'History Penjualan',
                'latarbelakang'     => 'Latar Belakang',
                'latarkompetitor'   => 'Latar Kompetitor',
                'mmin1'             => 'M-1',
                'm1'                => 'M1',
                'tujuan'            => 'Tujuan',
            ]);

            if ($data->foto_lokasi_proposal == null) {
                return redirect()->back()->withFlashDanger('Foto Belum Ada ! Upload Terlebih Dahulu');
            }

            if ($data->display_proposal == null) {
                return redirect()->back()->withFlashDanger('Data Display Belum Ada !');
            }

            if(data_get(request()->ket_dana, 0) == null){
                return redirect()->back()->withFlashDanger('Data Dana Masih Kosong !');
            }

            //Pamerak Ruko - Update Status Sales People
            if ($data->kategori_proposal == 5) {
                foreach (json_decode($data->sales_people_proposal) as $sp_ruko) {
                    $update_sp = SalesPeople::find($sp_ruko);
                    if ($update_sp->pameran_ruko_start != $data->periode_start_proposal &&
                        $update_sp->pameran_ruko_start != $data->periode_end_proposal &&
                        $update_sp->pameran_ruko_end != $data->periode_start_proposal &&
                        $update_sp->pameran_ruko_end != $data->periode_end_proposal ) {
                            $update_sp->pameran_ruko_start = $data->periode_start_proposal;
                            $update_sp->pameran_ruko_end   = $data->periode_end_proposal;
                            $update_sp->save();
                    } else {
                        return redirect()->back()->withFlashDanger('Sales People sedang di Pameran Ruko lain di waktu yang sama ! Mohon Diganti.');
                    }
                }
            }

            $data->status_proposal = 2;
            $data->submit_date     = Carbon::now();
            $data->user_approval   = 1;
            $data->inbox_md        = true;
            $data->inbox_d         = false;
            $data->save();

            $batas = $data->kategori->batas_approval;

            ApprovalProposal::where('status_approval', null)
                                ->where('id_proposal', $data->id)
                                ->delete();
            $approval = Pusat::orderBy('jabatan', 'ASC')->get();
            foreach ($approval->sortBy('jabatan') as $app) {
                if ($app->jabatan <= $batas) {
                    $isiapproval = new ApprovalProposal;
                    $isiapproval->id_proposal   = $data->id;
                    $isiapproval->user_approval = $app->id;
                    $isiapproval->save();
                    // $ok[] = $app->jabatan;
                }
            }
        }

        if (request()->b == 'refresh') {
            return redirect()->back();
        } elseif (request()->b == 'upload') {
            return redirect()->to(route('cabang.proposal.getUpload').'?uuid='.request()->uuid);
        } elseif (request()->b == 'done' || request()->b == 'draft') {
            return redirect()->route('cabang.proposal.index')->withFlashSuccess('Data Proposal Tersimpan  ! ✅');
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


}
