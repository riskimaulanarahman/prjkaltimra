<?php

namespace App\Http\Controllers\Pusat;

use App\Http\Controllers\Controller;
use App\Models\Dealer;
use App\Models\Lokasi;
use App\Models\KategoriProposal;
use App\Models\Proposal;
use Illuminate\Support\Facades\DB;
use App\Models\LpjKonsumen;
use App\Models\FinanceCompany;
use Illuminate\Support\Carbon;
use App\Models\Display;

class HomeController extends Controller
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
    public function index() {
        $datadealer           = Dealer::cariKota(request()->lokasi)->get();
        $datadisplay          = Display::get();
        $datalokasikota       = Lokasi::select('kota_lokasi')->groupBy('kota_lokasi')->get();
        $dataactivitykota     = Lokasi::select('kota_lokasi')->groupBy('kota_lokasi')->where('kota_lokasi', request()->lokasi)->get();
        $datakategori         = KategoriProposal::orderBy('keterangan_kategori')->get();
        $dataproposal         = Proposal::finalProposal();
        $leaderboardsales     = LpjKonsumen::select('id_sales_people', DB::raw('count(id_sales_people) as total_ssu'))
                                        ->where('hasil', 4)
                                        ->groupBy('id_sales_people')
                                        ->orderBy('total_ssu', 'desc')
                                        ->areaKota(request()->lokasi)
                                        ->dataDealer(request()->dealer)
                                        ->tanggal(request()->get('date-analisys') ?? null)->bulan(request()->get('month-analisys') ?? null)->tahun(request()->get('year-analisys') ?? null)
                                        ->skip(0)
                                        ->take(20)
                                        ->get();
        $datakonsumen          = LpjKonsumen::areaKota(request()->lokasi)->where('hasil', 4)->submitLpj()
                                            ->dataDealer(request()->dealer)
                                            ->tanggal(request()->get('date-analisys') ?? null)->bulan(request()->get('month-analisys') ?? null)->tahun(request()->get('year-analisys') ?? null)
                                            ->get();

        $datafincoy            = FinanceCompany::get();

        if (request()->lokasi) { $datalokasikecamatan = Lokasi::where('kota_lokasi', request()->lokasi)->select('kecamatan_lokasi')->groupBy('kecamatan_lokasi')->get(); }
        else { $datalokasikecamatan = null; }

        if (request()->kecamatan) { $datalokasikelurahan = Lokasi::where('kota_lokasi', request()->lokasi)->where('kecamatan_lokasi', request()->kecamatan)->get(); }
        else { $datalokasikelurahan = null; }

        // LEADERBOARD LPJ Penjualan
        foreach ($datadealer as $dealer_pen) {
            $penjualan_ = 0;
            foreach ($dealer_pen->proposal as $a) {
                $penjualan_ = $penjualan_ + $a->lpj_lead(request()->get('date-analisys'), request()->get('month-analisys'), request()->get('year-analisys'))->count();
            }

            $data[] = array(
                'dealer_'    => $dealer_pen->nama_dealer,
                'penjualan_' => $penjualan_
            );
        }

        if ($datadealer->isEmpty()) {
            $data_leaderboard_penjualan_dealer = [];
        } else {
            $data_leaderboard_penjualan_dealer = collect($data)->sortBy('penjualan_')->reverse()->toArray();
        }


        // DATA STATISTIK KONSUMEN
        for ($k = 1; $k <= 2; $k++) {
            $konsumengender_[]          = LpjKonsumen::areaKota(request()->lokasi, request()->kecamatan, request()->kelurahan)->where('hasil', 4)->submitLpj()->dataDealer(request()->dealer)->dataGender($k)->tanggal(request()->get('date-analisys') ?? null)->bulan(request()->get('month-analisys') ?? null)->tahun(request()->get('year-analisys') ?? null)->count() ;
        }
        for ($k = 1; $k <= 5; $k++) {
            $konsumenhasil_[]           = LpjKonsumen::areaKota(request()->lokasi, request()->kecamatan, request()->kelurahan)->dataDealer(request()->dealer)->dataHasil($k)->tanggal(request()->get('date-analisys') ?? null)->bulan(request()->get('month-analisys') ?? null)->tahun(request()->get('year-analisys') ?? null)->count() ;
        }
        for ($k = 1; $k <= 11; $k++) {
            $konsumendp_[]              = LpjKonsumen::areaKota(request()->lokasi, request()->kecamatan, request()->kelurahan)->where('hasil', 4)->submitLpj()->dataDealer(request()->dealer)->dataDp($k)->tanggal(request()->get('date-analisys') ?? null)->bulan(request()->get('month-analisys') ?? null)->tahun(request()->get('year-analisys') ?? null)->count() ;
        }
        for ($k = 1; $k <= 11; $k++) {
            $konsumenpengeluaran_[]     = LpjKonsumen::areaKota(request()->lokasi, request()->kecamatan, request()->kelurahan)->where('hasil', 4)->submitLpj()->dataDealer(request()->dealer)->dataPengeluaran($k)->tanggal(request()->get('date-analisys') ?? null)->bulan(request()->get('month-analisys') ?? null)->tahun(request()->get('year-analisys') ?? null)->count() ;
        }
        for ($k = 1; $k <= 24; $k++) {
            $konsumenpekerjaan_[]       = LpjKonsumen::areaKota(request()->lokasi, request()->kecamatan, request()->kelurahan)->where('hasil', 4)->submitLpj()->dataDealer(request()->dealer)->dataPekerjaan($k)->tanggal(request()->get('date-analisys') ?? null)->bulan(request()->get('month-analisys') ?? null)->tahun(request()->get('year-analisys') ?? null)->count() ;
        }
        foreach ($datafincoy as $fincoy){
            $konsumenfincoy_[]          = LpjKonsumen::areaKota(request()->lokasi, request()->kecamatan, request()->kelurahan)->where('hasil', 4)->submitLpj()->dataDealer(request()->dealer)->dataFincoy($fincoy->id)->tanggal(request()->get('date-analisys') ?? null)->bulan(request()->get('month-analisys') ?? null)->tahun(request()->get('year-analisys') ?? null)->count();
        }
        $a = 0;
        $b = 0;
        $c = 0;
        $d = 0;
        $e = 0;
        $f = 0;
        foreach ($datakonsumen as $data_k){
            if ($data_k->tgl_lahir) {
                $kategori_umur = Carbon::parse($data_k->tgl_lahir)->age;
                $tes[] =  $kategori_umur;
                if ($kategori_umur < 17) {
                    $a++;
                } elseif ($kategori_umur >= 17 && $kategori_umur <= 25) {
                    $b++;
                } elseif ($kategori_umur >= 26 && $kategori_umur <= 35) {
                    $c++;
                } elseif ($kategori_umur >= 36 && $kategori_umur <= 45) {
                    $d++;
                } elseif ($kategori_umur >= 46 && $kategori_umur <= 55) {
                    $e++;
                } elseif ($kategori_umur > 55) {
                    $f++;
                }
            }
        }
        for ($k = 1; $k <= 2; $k++) {
            $cashcredit_[]         = LpjKonsumen::areaKota(request()->lokasi, request()->kecamatan, request()->kelurahan)->where('hasil', 4)->submitLpj()->dataDealer(request()->dealer)->dataCashCredit($k)->tanggal(request()->get('date-analisys') ?? null)->bulan(request()->get('month-analisys') ?? null)->tahun(request()->get('year-analisys') ?? null)->count() ;
        }
        for ($k = 1; $k <= 6; $k++) {
            $merkmotor_[]          = LpjKonsumen::areaKota(request()->lokasi, request()->kecamatan, request()->kelurahan)->where('hasil', 4)->submitLpj()->dataDealer(request()->dealer)->dataMerkMotor($k)->tanggal(request()->get('date-analisys') ?? null)->bulan(request()->get('month-analisys') ?? null)->tahun(request()->get('year-analisys') ?? null)->count() ;
        }
        for ($k = 1; $k <= 4; $k++) {
            $jenismotor_[]         = LpjKonsumen::areaKota(request()->lokasi, request()->kecamatan, request()->kelurahan)->where('hasil', 4)->submitLpj()->dataDealer(request()->dealer)->dataJenisMotor($k)->tanggal(request()->get('date-analisys') ?? null)->bulan(request()->get('month-analisys') ?? null)->tahun(request()->get('year-analisys') ?? null)->count() ;
        }
        foreach ($datadisplay as $motor_){
            $typeunit_[]           = LpjKonsumen::areaKota(request()->lokasi, request()->kecamatan, request()->kelurahan)->where('hasil', 4)->submitLpj()->dataDealer(request()->dealer)->dataTypeUnit($motor_->id)->tanggal(request()->get('date-analisys') ?? null)->bulan(request()->get('month-analisys') ?? null)->tahun(request()->get('year-analisys') ?? null)->count() ;
        }
        $statistik = [
            "konsumen_gender"       => $konsumengender_,
            "konsumen_hasil"        => $konsumenhasil_,
            "konsumen_dp"           => $konsumendp_,
            "konsumen_pengeluaran"  => $konsumenpengeluaran_,
            "konsumen_pekerjaan"    => $konsumenpekerjaan_,
            "konsumen_fincoy"       => $konsumenfincoy_,
            "konsumen_umur_1"       => $a,
            "konsumen_umur_2"       => $b,
            "konsumen_umur_3"       => $c,
            "konsumen_umur_4"       => $d,
            "konsumen_umur_5"       => $e,
            "konsumen_umur_6"       => $f,
            "konsumen_cash_credit"  => $cashcredit_,
            "konsumen_merk_motor"   => $merkmotor_,
            "konsumen_jenis_motor"  => $jenismotor_,
            "konsumen_type_unit"    => $typeunit_,
        ];
        // dd(data_get($statistik, 'konsumen_umur_5'));


        return view('pusat.dashboard',
                compact('datadealer', 'datalokasikota', 'dataactivitykota', 'datalokasikecamatan',
                        'datalokasikelurahan', 'datakategori', 'dataproposal', 'data_leaderboard_penjualan_dealer',
                        'datakonsumen', 'leaderboardsales', 'statistik', 'datafincoy', 'datadisplay'
                    ));
    }

    public function index2() {
        $datadealer           = Dealer::cariKota(request()->lokasi)->get();
        $datadisplay          = Display::get();
        $datalokasikota       = Lokasi::select('kota_lokasi')->groupBy('kota_lokasi')->get();
        $dataactivitykota     = Lokasi::select('kota_lokasi')->groupBy('kota_lokasi')->where('kota_lokasi', request()->lokasi)->get();
        $datakategori         = KategoriProposal::orderBy('keterangan_kategori')->get();
        $dataproposal         = Proposal::finalProposal();
        $leaderboardsales     = LpjKonsumen::select('id_sales_people', DB::raw('count(id_sales_people) as total_ssu'))
                                        ->leftJoin('lpjs','lpj_konsumens.id_lpj','lpjs.id')
                                        ->leftJoin('proposals','lpjs.id_proposal','proposals.id')
                                        ->where('hasil', 4)
                                        ->groupBy('id_sales_people')
                                        ->orderBy('total_ssu', 'desc')
                                        ->areaKota(request()->lokasi)
                                        ->dataDealer(request()->dealer)
                                        // ->whereBetween('lpjs.periode_start_lpj', [request()->get('startdate'), request()->get('enddate')])
                                        // ->whereBetween('lpjs.periode_end_lpj', [request()->get('startdate'), request()->get('enddate')])
                                        ->whereBetween('proposals.periode_start_proposal', [request()->get('startdate'), request()->get('enddate')])
                                        ->whereBetween('proposals.periode_end_proposal', [request()->get('startdate'), request()->get('enddate')])
                                        ->skip(0)
                                        ->take(20)
                                        ->get();
        $datakonsumen          = LpjKonsumen::areaKota(request()->lokasi)
                                        ->where('hasil', 4)
                                        ->submitLpj()
                                        ->dataDealer(request()->dealer)
                                        // ->tanggal(request()->get('date-analisys') ?? null)->bulan(request()->get('month-analisys') ?? null)->tahun(request()->get('year-analisys') ?? null)
                                        ->tanggalrange(request()->get('startdate'), request()->get('enddate'))
                                        ->get();

        $datafincoy            = FinanceCompany::get();

        if (request()->lokasi) { $datalokasikecamatan = Lokasi::where('kota_lokasi', request()->lokasi)->select('kecamatan_lokasi')->groupBy('kecamatan_lokasi')->get(); }
        else { $datalokasikecamatan = null; }

        if (request()->kecamatan) { $datalokasikelurahan = Lokasi::where('kota_lokasi', request()->lokasi)->where('kecamatan_lokasi', request()->kecamatan)->get(); }
        else { $datalokasikelurahan = null; }

        // LEADERBOARD LPJ Penjualan
        // dd($datadealer);
        
            foreach ($datadealer as $dealer_pen) {
                $penjualan_ = 0;
                foreach ($dealer_pen->proposal as $a) {
                    $penjualan_ = $penjualan_ + $a->lpj_lead(request()->get('startdate'), request()->get('enddate'))->count();
                }

                // dd($penjualan_);

                
                $data[] = array(
                    'dealer_'    => $dealer_pen->nama_dealer,
                    'penjualan_' => $penjualan_
                );
            }

        if ($datadealer->isEmpty()) {
            $data_leaderboard_penjualan_dealer = [];
        } else {
            $data_leaderboard_penjualan_dealer = collect($data)->sortBy('penjualan_')->reverse()->toArray();
        }



        // DATA STATISTIK KONSUMEN
        for ($k = 1; $k <= 2; $k++) {
            $konsumengender_[]          = LpjKonsumen::areaKota(request()->lokasi, request()->kecamatan, request()->kelurahan)
                                            ->where('hasil', 4)
                                            ->submitLpj()
                                            ->dataDealer(request()->dealer)
                                            ->dataGender($k)
                                            // ->tanggal(request()->get('date-analisys') ?? null)->bulan(request()->get('month-analisys') ?? null)->tahun(request()->get('year-analisys') ?? null)
                                            ->tanggalrange(request()->get('startdate'), request()->get('enddate'))
                                            ->count() ;
        }
        for ($k = 4; $k <= 5; $k++) {
            $konsumenhasil_[]           = LpjKonsumen::areaKota(request()->lokasi, request()->kecamatan, request()->kelurahan)
                                            ->dataDealer(request()->dealer)
                                            ->dataHasil($k)
                                            // ->tanggal(request()->get('date-analisys') ?? null)->bulan(request()->get('month-analisys') ?? null)->tahun(request()->get('year-analisys') ?? null)
                                            ->tanggalrange(request()->get('startdate'), request()->get('enddate'))
                                            ->count() ;
        }
        for ($k = 1; $k <= 11; $k++) {
            $konsumendp_[]              = LpjKonsumen::areaKota(request()->lokasi, request()->kecamatan, request()->kelurahan)
                                            ->where('hasil', 4)
                                            ->submitLpj()
                                            ->dataDealer(request()->dealer)->dataDp($k)
                                            // ->tanggal(request()->get('date-analisys') ?? null)->bulan(request()->get('month-analisys') ?? null)->tahun(request()->get('year-analisys') ?? null)
                                            ->tanggalrange(request()->get('startdate'), request()->get('enddate'))
                                            ->count() ;
        }
        for ($k = 1; $k <= 11; $k++) {
            $konsumenpengeluaran_[]     = LpjKonsumen::areaKota(request()->lokasi, request()->kecamatan, request()->kelurahan)
                                            ->where('hasil', 4)
                                            ->submitLpj()
                                            ->dataDealer(request()->dealer)
                                            ->dataPengeluaran($k)
                                            // ->tanggal(request()->get('date-analisys') ?? null)->bulan(request()->get('month-analisys') ?? null)->tahun(request()->get('year-analisys') ?? null)
                                            ->tanggalrange(request()->get('startdate'), request()->get('enddate'))
                                            ->count() ;
        }
        for ($k = 1; $k <= 24; $k++) {
            $konsumenpekerjaan_[]       = LpjKonsumen::areaKota(request()->lokasi, request()->kecamatan, request()->kelurahan)
                                            ->where('hasil', 4)
                                            ->submitLpj()
                                            ->dataDealer(request()->dealer)
                                            ->dataPekerjaan($k)
                                            // ->tanggal(request()->get('date-analisys') ?? null)->bulan(request()->get('month-analisys') ?? null)->tahun(request()->get('year-analisys') ?? null)
                                            ->tanggalrange(request()->get('startdate'), request()->get('enddate'))
                                            ->count() ;
        }
        foreach ($datafincoy as $fincoy){
            $konsumenfincoy_[]          = LpjKonsumen::areaKota(request()->lokasi, request()->kecamatan, request()->kelurahan)
                                            ->where('hasil', 4)
                                            ->submitLpj()
                                            ->dataDealer(request()->dealer)
                                            ->dataFincoy($fincoy->id)
                                            // ->tanggal(request()->get('date-analisys') ?? null)->bulan(request()->get('month-analisys') ?? null)->tahun(request()->get('year-analisys') ?? null)
                                            ->tanggalrange(request()->get('startdate'), request()->get('enddate'))
                                            ->count();
        }
        $a = 0;
        $b = 0;
        $c = 0;
        $d = 0;
        $e = 0;
        $f = 0;
        foreach ($datakonsumen as $data_k){
            if ($data_k->tgl_lahir) {
                $kategori_umur = Carbon::parse($data_k->tgl_lahir)->age;
                $tes[] =  $kategori_umur;
                if ($kategori_umur < 17) {
                    $a++;
                } elseif ($kategori_umur >= 17 && $kategori_umur <= 25) {
                    $b++;
                } elseif ($kategori_umur >= 26 && $kategori_umur <= 35) {
                    $c++;
                } elseif ($kategori_umur >= 36 && $kategori_umur <= 45) {
                    $d++;
                } elseif ($kategori_umur >= 46 && $kategori_umur <= 55) {
                    $e++;
                } elseif ($kategori_umur > 55) {
                    $f++;
                }
            }
        }
        for ($k = 1; $k <= 2; $k++) {
            $cashcredit_[]         = LpjKonsumen::areaKota(request()->lokasi, request()->kecamatan, request()->kelurahan)
                                        ->where('hasil', 4)
                                        ->submitLpj()
                                        ->dataDealer(request()->dealer)->dataCashCredit($k)
                                        // ->tanggal(request()->get('date-analisys') ?? null)->bulan(request()->get('month-analisys') ?? null)->tahun(request()->get('year-analisys') ?? null)
                                        ->tanggalrange(request()->get('startdate'), request()->get('enddate'))
                                        ->count() ;
        }
        for ($k = 1; $k <= 6; $k++) {
            $merkmotor_[]          = LpjKonsumen::areaKota(request()->lokasi, request()->kecamatan, request()->kelurahan)
                                        ->where('hasil', 4)
                                        ->submitLpj()
                                        ->dataDealer(request()->dealer)
                                        ->dataMerkMotor($k)
                                        // ->tanggal(request()->get('date-analisys') ?? null)->bulan(request()->get('month-analisys') ?? null)->tahun(request()->get('year-analisys') ?? null)
                                        ->tanggalrange(request()->get('startdate'), request()->get('enddate'))
                                        ->count() ;
        }
        for ($k = 1; $k <= 4; $k++) {
            $jenismotor_[]         = LpjKonsumen::areaKota(request()->lokasi, request()->kecamatan, request()->kelurahan)
                                        ->where('hasil', 4)
                                        ->submitLpj()
                                        ->dataDealer(request()->dealer)
                                        ->dataJenisMotor($k)
                                        // ->tanggal(request()->get('date-analisys') ?? null)->bulan(request()->get('month-analisys') ?? null)->tahun(request()->get('year-analisys') ?? null)
                                        ->tanggalrange(request()->get('startdate'), request()->get('enddate'))
                                        ->count() ;
        }
        foreach ($datadisplay as $motor_){
            $typeunit_[]           = LpjKonsumen::areaKota(request()->lokasi, request()->kecamatan, request()->kelurahan)
                                        ->where('hasil', 4)
                                        ->submitLpj()
                                        ->dataDealer(request()->dealer)
                                        ->dataTypeUnit($motor_->id)
                                        // ->tanggal(request()->get('date-analisys') ?? null)->bulan(request()->get('month-analisys') ?? null)->tahun(request()->get('year-analisys') ?? null)
                                        ->tanggalrange(request()->get('startdate'), request()->get('enddate'))
                                        ->count() ;
        }
        $statistik = [
            "konsumen_gender"       => $konsumengender_,
            "konsumen_hasil"        => $konsumenhasil_,
            "konsumen_dp"           => $konsumendp_,
            "konsumen_pengeluaran"  => $konsumenpengeluaran_,
            "konsumen_pekerjaan"    => $konsumenpekerjaan_,
            "konsumen_fincoy"       => $konsumenfincoy_,
            "konsumen_umur_1"       => $a,
            "konsumen_umur_2"       => $b,
            "konsumen_umur_3"       => $c,
            "konsumen_umur_4"       => $d,
            "konsumen_umur_5"       => $e,
            "konsumen_umur_6"       => $f,
            "konsumen_cash_credit"  => $cashcredit_,
            "konsumen_merk_motor"   => $merkmotor_,
            "konsumen_jenis_motor"  => $jenismotor_,
            "konsumen_type_unit"    => $typeunit_,
        ];

        $tanggal = date('Y-m-d',strtotime(request('startdate')));
        $currentMonth = date('n', strtotime($tanggal));
        $currentYear = date('Y', strtotime($tanggal));
        $monthsAgo1 = $currentMonth - 1;
        $interval1 = 1;
        $monthsAgo2 = $currentMonth - 2;
        $interval2 = 2;

        // return $typeunit_;
        // dd(data_get($statistik, 'konsumen_umur_5'));
        $datacostunit = DB::select("
            SELECT 
                dealers.nama_dealer,
                kategori_proposals.nama_kategori,
                CASE WHEN MONTH(lpjs.periode_start_lpj) IS NULL THEN $monthsAgo1 ELSE MONTH(lpjs.periode_start_lpj) END AS bulan,
                CASE WHEN DATE_FORMAT(lpjs.periode_start_lpj, '%M') IS NULL THEN DATE_FORMAT(DATE_SUB($tanggal, INTERVAL $interval1 MONTH), '%M') ELSE DATE_FORMAT(lpjs.periode_start_lpj, '%M') END AS nama_bulan,
                COALESCE(SUM(total_dana_lpj), 0) AS total_dana_lpj,
                COALESCE(SUM(target_penjualan_lpj), 0) AS target_penjualan_lpj,
                COALESCE(ROUND(SUM(total_dana_lpj) / SUM(target_penjualan_lpj)), 0) AS costunit
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
                kategori_proposals.nama_kategori <> 'Showroom Event Virtual' AND
                dealers.nama_dealer IS NOT NULL
            GROUP BY
                dealers.nama_dealer,
                kategori_proposals.nama_kategori

            UNION ALL

            SELECT 
                dealers.nama_dealer AS nama_dealer,
                'Total' AS nama_kategori,
                NULL AS bulan,
                NULL AS nama_bulan,
                COALESCE(SUM(total_dana_lpj), 0) AS total_dana_lpj,
                COALESCE(SUM(target_penjualan_lpj), 0) AS target_penjualan_lpj,
                COALESCE(ROUND(SUM(total_dana_lpj) / SUM(target_penjualan_lpj)), 0) AS costunit
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
                kategori_proposals.nama_kategori <> 'Showroom Event Virtual' AND
                dealers.nama_dealer IS NOT NULL
            GROUP BY
                dealers.nama_dealer

            ORDER BY nama_dealer, nama_kategori"
        );

        $datacostunit2 = DB::select("
            SELECT 
                dealers.nama_dealer,
                kategori_proposals.nama_kategori,
                CASE WHEN MONTH(lpjs.periode_start_lpj) IS NULL THEN $monthsAgo2 ELSE MONTH(lpjs.periode_start_lpj) END AS bulan,
                CASE WHEN DATE_FORMAT(lpjs.periode_start_lpj, '%M') IS NULL THEN DATE_FORMAT(DATE_SUB($tanggal, INTERVAL $interval2 MONTH), '%M') ELSE DATE_FORMAT(lpjs.periode_start_lpj, '%M') END AS nama_bulan,
                COALESCE(SUM(total_dana_lpj), 0) AS total_dana_lpj,
                COALESCE(SUM(target_penjualan_lpj), 0) AS target_penjualan_lpj,
                COALESCE(ROUND(SUM(total_dana_lpj) / SUM(target_penjualan_lpj)), 0) AS costunit
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
                kategori_proposals.nama_kategori <> 'Showroom Event Virtual' AND
                dealers.nama_dealer IS NOT NULL
            GROUP BY
                dealers.nama_dealer,
                kategori_proposals.nama_kategori

            UNION ALL

            SELECT 
                dealers.nama_dealer AS nama_dealer,
                'Total' AS nama_kategori,
                NULL AS bulan,
                NULL AS nama_bulan,
                COALESCE(SUM(total_dana_lpj), 0) AS total_dana_lpj,
                COALESCE(SUM(target_penjualan_lpj), 0) AS target_penjualan_lpj,
                COALESCE(ROUND(SUM(total_dana_lpj) / SUM(target_penjualan_lpj)), 0) AS costunit
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
                kategori_proposals.nama_kategori <> 'Showroom Event Virtual' AND
                dealers.nama_dealer IS NOT NULL
            GROUP BY
                dealers.nama_dealer

            ORDER BY nama_dealer, nama_kategori"
        );

        $bulanHeader = null;
        foreach ($datacostunit as $item) {
            if (!is_null($item->nama_bulan)) {
                $bulanHeader = $item->nama_bulan;
                break;
            }
        }

        $bulanHeader2 = null;
        foreach ($datacostunit2 as $item) {
            if (!is_null($item->nama_bulan)) {
                $bulanHeader2 = $item->nama_bulan;
                break;
            }
        }

        $totaldana = collect($datacostunit)->where('nama_kategori','Total')->sum('total_dana_lpj');
        $targetpenjualan = collect($datacostunit)->where('nama_kategori','Total')->sum('target_penjualan_lpj');

        $totaldana2 = collect($datacostunit2)->where('nama_kategori','Total')->sum('total_dana_lpj');
        $targetpenjualan2 = collect($datacostunit2)->where('nama_kategori','Total')->sum('target_penjualan_lpj');

        // $datacostunit = collect($datacostunit)->sortBy(function ($item) {
        //     if ($item->nama_kategori === 'Total') {
        //         return $item->costunit;
        //     } else {
        //         return PHP_INT_MAX; // Pastikan data dengan "Total" selalu di bagian terakhir
        //     }
        // })->values()->all();

        // $datacostunit2 = collect($datacostunit2)->sortBy(function ($item) {
        //     if ($item->nama_kategori === 'Total') {
        //         return $item->costunit;
        //     } else {
        //         return PHP_INT_MAX; // Pastikan data dengan "Total" selalu di bagian terakhir
        //     }
        // })->values()->all();


        return view('pusat.dashboard2',
                compact('datadealer', 'datalokasikota', 'dataactivitykota', 'datalokasikecamatan',
                        'datalokasikelurahan', 'datakategori', 'dataproposal', 'data_leaderboard_penjualan_dealer',
                        'datakonsumen', 'leaderboardsales', 'statistik', 'datafincoy', 'datadisplay',
                        'datacostunit','bulanHeader','totaldana','targetpenjualan',
                        'datacostunit2','bulanHeader2','totaldana2','targetpenjualan2'
                    ));
    }
}
