<?php

namespace App\Http\Controllers\Pusat;

use App\Http\Controllers\Controller;
use App\Models\Lpj;
use App\Models\LpjKonsumen;
use App\Models\FinanceCompany;
use App\Models\KategoriProposal;
use Illuminate\Http\Request;
use App\Models\Display;
use App\Models\Dealer;
use App\Models\Lokasi;
use App\Models\Proposal;
use App\Models\LpjRevenue;
use App\Models\LpjUnitentry;
use App\Models\ApprovalLpj;
use Auth;
use Illuminate\Support\Facades\DB;

class LpjController extends Controller
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

    public function index() {
        $datalokasi   = Lokasi::areaKota(request()->area)->get();
        $datakategori = KategoriProposal::get();
        $datadealer   = Dealer::cariKota(request()->area)->get();
        $dataarea     = Dealer::select('kota_dealer')->groupBy('kota_dealer')->get();

        if(request()->type == 'by-month'){
            $datas = Lpj::whereIn('status_lpj', [2,3,4,5,6])
                    ->when(request()->dealer, function ($query) {
                        $query->dealer(request()->dealer);
                    })
                    ->kategori(request()->kategori)
                    ->cariLokasi(request()->lokasi)
                    ->area(request()->area)
                    ->orderBy('submit_date', 'DESC')
                    ->when(request()->bulan, function ($query) {
                        if(request()->bulan != 'SEMUA' ){
                            $query->whereMonth('periode_start_lpj', request()->bulan);
                        }
                    })
                    ->when(request()->tahun, function ($query) {
                        $query->whereYear('periode_start_lpj', request()->tahun);
                    })
                    ->when(request()->tanggal_submit, function ($query) {
                        $query->whereBetween('submit_date', [request()->tanggal_submit_start, request()->tanggal_submit_end]);
                    })
                    ->with('approval')
                    ->paginate(10);;
        } else {
            $datas = Lpj::whereIn('status_lpj', [2,3,4,5,6])
                    ->when(request()->dealer, function ($query) {
                        $query->dealer(request()->dealer);
                    })
                    ->kategori(request()->kategori)
                    ->cariLokasi(request()->lokasi)
                    ->area(request()->area)
                    ->orderBy('submit_date', 'DESC')
                    ->when(request()->tanggal_mulai, function ($query) {
                        $query->whereBetween('periode_start_lpj', [request()->tanggal_mulai, request()->tanggal_akhir])
                              ->whereBetween('periode_end_lpj', [request()->tanggal_mulai, request()->tanggal_akhir]);
                    })
                    ->when(request()->tanggal_submit, function ($query) {
                        $query->whereBetween('submit_date', [request()->tanggal_submit_start, request()->tanggal_submit_end]);
                    })
                    ->with('approval')
                    ->paginate(10);
        }

        // return $datas;

        return view('pusat.lpj.index', compact('datas', 'datakategori', 'datadealer', 'datalokasi', 'dataarea'));
    }

    public function getShow(Request $request) {

        $data           = Lpj::where('uuid', request()->id)->first();

        $datafinance    = FinanceCompany::get();
        $datadana       = json_decode($data->dana_lpj ?? null, true);
        $datakonsumen   = LpjKonsumen::where('id_lpj', $data->id)->get();
        $datadisplay    = Display::get();

        $dataapproval = ApprovalLpj::where('id_lpj', $data->id)
        ->select(['approval_lpjs.*', 'pusats.jabatan as jabatan_p'])
        ->join('pusats', 'approval_lpjs.user_approval', '=', 'pusats.id')
        ->orderBy('approval_lpjs.created_at')
        ->orderBy('pusats.jabatan')
        ->get();

        $getlastappr = ApprovalLpj::where('id_lpj', $data->id)
        ->select(['approval_lpjs.*', 'pusats.jabatan as jabatan_p'])
        ->join('pusats', 'approval_lpjs.user_approval', '=', 'pusats.id')
        ->where('approval_lpjs.status_approval',null)
        ->orderBy('approval_lpjs.created_at')
        ->orderBy('pusats.jabatan')
        ->first();
        $appruser = Auth::guard('pusat')->user()->id;

        if($data->status_lpj == 4 && $getlastappr->user_approval == $appruser) {
            $isme = 1;
        } else {
            $isme = 0;
        }

        return view('pusat.lpj.show', compact('datafinance', 'data', 'datadana', 'datakonsumen', 'datadisplay','dataapproval','isme'));
    }

    public function getPerbaikanKonsumen()
    {
        $datak = LpjKonsumen::whereNotNull('id_lokasi')->get();
        $n = 0;

        foreach($datak as $konsumen){
            $lokasi = null;

            $lokasi = Lokasi::find($konsumen->id_lokasi);
            $konsumen->kecamatan = $lokasi->kecamatan_lokasi;
            $konsumen->kelurahan = $lokasi->kelurahan_lokasi;
            $konsumen->save();

            $n++;
        }

        return $n;
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
                $data = LpjUnitentry::where('proposal_id',$getid->id)->with('lpj')->get();
            } else {
                $getid = Lpj::leftJoin('proposals','lpjs.id_proposal','=','proposals.id')
                ->select('lpjs.*','proposals.id as idproposal')
                ->where('lpjs.uuid',$id)
                ->first();
                $data = LpjUnitentry::where('proposal_id',$getid->idproposal)->with('lpj')->get();
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
                    $data->proposal_id = $getid->id;
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

    public function updatelpjapproval(Request $request) {
        try {
            $id = $request->input('id');
            $userid = $request->input('userid');

            $lpj = Lpj::where('uuid',$id)->first();

            $data = ApprovalLpj::where('id_lpj',$lpj->id)->where('user_approval',$userid)->first();


            $data->status_approval = $request->input('status_approval');
            $data->keterangan_approval = $request->input('keterangan_approval');
            $data->save();

            $checkdata = ApprovalLpj::where('id_lpj',$lpj->id)->get();

            $final = 0;
            $statusApprovals = [];

            foreach ($checkdata as $approval) {
                $statusApprovals[] =(int)$approval->status_approval;
            }

            if (in_array(2, $statusApprovals)) {
                $lpj->status_lpj = 6;
                // echo 6;
            } elseif (in_array(3, $statusApprovals)) {
                $lpj->status_lpj = 5;
                // echo 5;
            } elseif (in_array(1, $statusApprovals)) {
                if (array_sum($statusApprovals) == 2) {
                    $final = 1;
                    $lpj->status_lpj = 2;
                    // echo 2;
                } else {
                    $lpj->status_lpj = 4;
                    // echo 4;
                }
            }
            $lpj->save();
    
            return response()->json(["status" => "success", "message" => "Data Berhasil Disimpan", "data" => $data])->setEncodingOptions(JSON_NUMERIC_CHECK);

        } catch (\Throwable $th) {

            return response()->json(["status" => "error", "message" => $th]);

        }
    }

    public function getcostunit() {
        $currentMonth = date('n');
        $currentYear = date('Y');
        $monthsAgo1 = $currentMonth - 1;
        $interval1 = 1;
        $monthsAgo2 = $currentMonth - 2;
        $interval2 = 2;

        $result = DB::select("
            SELECT 
            dealers.nama_dealer,
            kategori_proposals.nama_kategori,
            case when MONTH(lpjs.periode_start_lpj) is null then $monthsAgo1 else MONTH(lpjs.periode_start_lpj) end AS bulan,
            case when DATE_FORMAT(lpjs.periode_start_lpj, '%M') is null then DATE_FORMAT(DATE_SUB(NOW(), INTERVAL $interval1 MONTH), '%M') else DATE_FORMAT(lpjs.periode_start_lpj, '%M') end AS nama_bulan,
            COALESCE(sum(total_dana_lpj), 0) AS total_dana_lpj,
            COALESCE(sum(target_penjualan_lpj), 0) AS target_penjualan_lpj,
            COALESCE(round(sum(total_dana_lpj) / sum(target_penjualan_lpj)), 0) AS costunit
        FROM
            kategori_proposals
        LEFT JOIN proposals ON kategori_proposals.id = proposals.kategori_proposal
        LEFT JOIN dealers ON proposals.dealer_proposal = dealers.id
        LEFT JOIN lpjs ON proposals.id = lpjs.id_proposal 
                    AND lpjs.status_lpj = 2
                    AND MONTH(lpjs.periode_start_lpj) = $monthsAgo1
                    AND MONTH(lpjs.periode_end_lpj) = $monthsAgo1
                    AND YEAR(lpjs.periode_start_lpj) = $currentYear
                    AND YEAR(lpjs.periode_end_lpj) = $currentYear
        WHERE 
            kategori_proposals.nama_kategori <> 'Showroom Event Virtual' and
            dealers.nama_dealer is not null
            
        GROUP BY
        dealers.nama_dealer,
            kategori_proposals.nama_kategori
    

        
        UNION


        SELECT 
            dealers.nama_dealer,
            kategori_proposals.nama_kategori,
            case when MONTH(lpjs.periode_start_lpj) is null then $monthsAgo2 else MONTH(lpjs.periode_start_lpj) end AS bulan,
            case when DATE_FORMAT(lpjs.periode_start_lpj, '%M') is null then DATE_FORMAT(DATE_SUB(NOW(), INTERVAL $interval2 MONTH), '%M') else DATE_FORMAT(lpjs.periode_start_lpj, '%M') end AS nama_bulan,
            COALESCE(sum(total_dana_lpj), 0) AS total_dana_lpj,
            COALESCE(sum(target_penjualan_lpj), 0) AS target_penjualan_lpj,
            COALESCE(round(sum(total_dana_lpj) / sum(target_penjualan_lpj)), 0) AS costunit
        FROM
            kategori_proposals
        LEFT JOIN proposals ON kategori_proposals.id = proposals.kategori_proposal
        LEFT JOIN dealers ON proposals.dealer_proposal = dealers.id
        LEFT JOIN lpjs ON proposals.id = lpjs.id_proposal 
                    AND lpjs.status_lpj = 2
                    AND MONTH(lpjs.periode_start_lpj) = $monthsAgo2
                    AND MONTH(lpjs.periode_end_lpj) = $monthsAgo2
                    AND YEAR(lpjs.periode_start_lpj) = $currentYear
                    AND YEAR(lpjs.periode_end_lpj) = $currentYear
        WHERE 
            kategori_proposals.nama_kategori <> 'Showroom Event Virtual' and
            dealers.nama_dealer is not null
        GROUP BY
        dealers.nama_dealer,
            kategori_proposals.nama_kategori
        
            ");
        return $result;
        // return response()->json(["status" => "success", "message" => "Data Ditampilkan", "data" => $result])->setEncodingOptions(JSON_NUMERIC_CHECK);
    }

    public function getrevenuechart() {
        $currentMonth = date('n');
        $currentYear = date('Y');
        $monthsAgo1 = $currentMonth - 1;
        $interval1 = 1;
        $monthsAgo2 = $currentMonth - 2;
        $interval2 = 2;

        $result = DB::select("
        SELECT
        CASE 
                WHEN DATE_FORMAT(lpjs.periode_start_lpj, '%M') IS NULL THEN DATE_FORMAT(DATE_SUB(NOW(), INTERVAL $interval1 MONTH), '%M') 
                ELSE DATE_FORMAT(lpjs.periode_start_lpj, '%M') 
        END AS nama_bulan,
        COALESCE(sum(lpj_revenues.jasa_act),0) as jasa,
        COALESCE(sum(lpj_revenues.part_act),0) as part,
        COALESCE(sum(lpj_revenues.oli_act),0) as oli
        FROM
        lpjs
        LEFT JOIN lpj_revenues ON lpjs.id_proposal = lpj_revenues.proposal_id
        where lpjs.status_lpj = 2
         AND MONTH(lpjs.periode_start_lpj) = $monthsAgo1
         AND MONTH(lpjs.periode_end_lpj) = $monthsAgo1
         AND YEAR(lpjs.periode_start_lpj) = YEAR(NOW())
         AND YEAR(lpjs.periode_end_lpj) = YEAR(NOW())
         
         UNION
         
         SELECT
        CASE 
                WHEN DATE_FORMAT(lpjs.periode_start_lpj, '%M') IS NULL THEN DATE_FORMAT(DATE_SUB(NOW(), INTERVAL $interval2 MONTH), '%M') 
                ELSE DATE_FORMAT(lpjs.periode_start_lpj, '%M') 
        END AS nama_bulan,
        COALESCE(sum(lpj_revenues.jasa_act),0) as jasa,
        COALESCE(sum(lpj_revenues.part_act),0) as part,
        COALESCE(sum(lpj_revenues.oli_act),0) as oli
        FROM
        lpjs
        LEFT JOIN lpj_revenues ON lpjs.id_proposal = lpj_revenues.proposal_id
        where lpjs.status_lpj = 2
         AND MONTH(lpjs.periode_start_lpj) = $monthsAgo2
         AND MONTH(lpjs.periode_end_lpj) = $monthsAgo2
         AND YEAR(lpjs.periode_start_lpj) = YEAR(NOW())
         AND YEAR(lpjs.periode_end_lpj) = YEAR(NOW())
        
            ");
        return response()->json($result)->setEncodingOptions(JSON_NUMERIC_CHECK);

    }

    public function getunitentrychart() {
        $currentMonth = date('n');
        $currentYear = date('Y');
        $monthsAgo1 = $currentMonth - 1;
        $interval1 = 1;
        $monthsAgo2 = $currentMonth - 2;
        $interval2 = 2;

        $result = DB::select("
        SELECT
    nama_bulan,
    sum(CASE WHEN unit_nama = 'kpb_1' THEN jumlah END) AS kpb_1,
    sum(CASE WHEN unit_nama = 'kpb_2' THEN jumlah END) AS kpb_2,
    sum(CASE WHEN unit_nama = 'kpb_3' THEN jumlah END) AS kpb_3,
    sum(CASE WHEN unit_nama = 'kpb_4' THEN jumlah END) AS kpb_4,
    sum(CASE WHEN unit_nama = 'psl' THEN jumlah END) AS psl,
    sum(CASE WHEN unit_nama = 'psr' THEN jumlah END) AS psr,
    sum(CASE WHEN unit_nama = 'go' THEN jumlah END) AS `go`,
    sum(CASE WHEN unit_nama = 'lr' THEN jumlah END) AS lr
FROM (
    SELECT
        CASE 
            WHEN DATE_FORMAT(lpjs.periode_start_lpj, '%M') IS NULL THEN DATE_FORMAT(DATE_SUB(NOW(), INTERVAL $interval1 MONTH), '%M') 
            ELSE DATE_FORMAT(lpjs.periode_start_lpj, '%M') 
        END AS nama_bulan,
        unit_nama,
        COALESCE(SUM(unit_jumlah),0) AS jumlah
    FROM
        lpjs
        LEFT JOIN lpj_unitentrys ON lpjs.id_proposal = lpj_unitentrys.proposal_id
    WHERE
        lpjs.status_lpj = 2
        AND MONTH(lpjs.periode_start_lpj) = $monthsAgo1
        AND MONTH(lpjs.periode_end_lpj) = $monthsAgo1
        AND YEAR(lpjs.periode_start_lpj) = YEAR(NOW())
        AND YEAR(lpjs.periode_end_lpj) = YEAR(NOW())
    GROUP BY
        nama_bulan, unit_nama
				
) AS transposed_data

UNION

SELECT
    nama_bulan,
    sum(CASE WHEN unit_nama = 'kpb_1' THEN jumlah END) AS kpb_1,
    sum(CASE WHEN unit_nama = 'kpb_2' THEN jumlah END) AS kpb_2,
    sum(CASE WHEN unit_nama = 'kpb_3' THEN jumlah END) AS kpb_3,
    sum(CASE WHEN unit_nama = 'kpb_4' THEN jumlah END) AS kpb_4,
    sum(CASE WHEN unit_nama = 'psl' THEN jumlah END) AS psl,
    sum(CASE WHEN unit_nama = 'psr' THEN jumlah END) AS psr,
    sum(CASE WHEN unit_nama = 'go' THEN jumlah END) AS `go`,
    sum(CASE WHEN unit_nama = 'lr' THEN jumlah END) AS lr
FROM (
    SELECT
        CASE 
            WHEN DATE_FORMAT(lpjs.periode_start_lpj, '%M') IS NULL THEN DATE_FORMAT(DATE_SUB(NOW(), INTERVAL $interval2 MONTH), '%M') 
            ELSE DATE_FORMAT(lpjs.periode_start_lpj, '%M') 
        END AS nama_bulan,
        unit_nama,
        COALESCE(SUM(unit_jumlah),0) AS jumlah
    FROM
        lpjs
        LEFT JOIN lpj_unitentrys ON lpjs.id_proposal = lpj_unitentrys.proposal_id
    WHERE
        lpjs.status_lpj = 2
        AND MONTH(lpjs.periode_start_lpj) = $monthsAgo2
        AND MONTH(lpjs.periode_end_lpj) = $monthsAgo2
        AND YEAR(lpjs.periode_start_lpj) = YEAR(NOW())
        AND YEAR(lpjs.periode_end_lpj) = YEAR(NOW())
    GROUP BY
        nama_bulan, unit_nama
				
) AS transposed_data;

        
            ");
        // return $result;
        return response()->json($result)->setEncodingOptions(JSON_NUMERIC_CHECK);
    }

}
