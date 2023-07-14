<?php

namespace App\Http\Controllers\Cabang;

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
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
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

    /**
     * Show the Cabang dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function indexold() {
        // $chart1 = Proposal::select('id', 'created_at')
        //             ->get()
        //             ->groupBy(function($date) {
        //                 return Carbon::parse($date->created_at)->format('Y'); // grouping by years
        //                 return Carbon::parse($date->created_at)->format('m'); // grouping by months
        //             });

        // $datalokasi = Lokasi::get();
        // $datadealer = Dealer::get();

        $datadealer           = Dealer::cariKota(request()->lokasi)->get();
        $datadisplay          = Display::get();
        $datalokasikota       = Lokasi::select('kota_lokasi')->groupBy('kota_lokasi')->get();
        $dataactivitykota     = Lokasi::select('kota_lokasi')->groupBy('kota_lokasi')->where('kota_lokasi', request()->lokasi)->get();
        $datakategori         = KategoriProposal::orderBy('keterangan_kategori')->get();
        $dataproposal         = Proposal::where('dealer_proposal', Auth::guard('cabang')->user()->dealer)->finalProposal();
        $leaderboardsales     = LpjKonsumen::dashboardDealer()->select('id_sales_people', DB::raw('count(id_sales_people) as total_ssu'))
                                        ->where('hasil', 4)
                                        ->groupBy('id_sales_people')
                                        ->orderBy('total_ssu', 'desc')
                                        ->areaKota(request()->lokasi)
                                        ->dataDealer(request()->dealer)
                                        ->tanggal(request()->get('date-analisys') ?? null)->bulan(request()->get('month-analisys') ?? null)->tahun(request()->get('year-analisys') ?? null)
                                        ->skip(0)
                                        ->take(20)
                                        ->get();
        $datakonsumen          = LpjKonsumen::dashboardDealer()->areaKota(request()->lokasi)->where('hasil', 4)->submitLpj()
                                            ->dataDealer(request()->dealer)
                                            ->tanggal(request()->get('date-analisys') ?? null)->bulan(request()->get('month-analisys') ?? null)->tahun(request()->get('year-analisys') ?? null)
                                            ->get();

        $datafincoy            = FinanceCompany::get();

        $kota_ = Dealer::find(Auth::guard('cabang')->user()->dealer);

        $datalokasikecamatan = Lokasi::where('kota_lokasi', $kota_->kota_dealer)->select('kecamatan_lokasi')->groupBy('kecamatan_lokasi')->get();

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
        $data_leaderboard_penjualan_dealer = collect($data)->sortBy('penjualan_')->reverse()->toArray();


        // DATA STATISTIK KONSUMEN
        for ($k = 1; $k <= 2; $k++) {
            $konsumengender_[]          = LpjKonsumen::dashboardDealer()->areaKota(request()->lokasi)->where('hasil', 4)->submitLpj()->dataDealer(request()->dealer)->dataGender($k)->tanggal(request()->get('date-analisys') ?? null)->bulan(request()->get('month-analisys') ?? null)->tahun(request()->get('year-analisys') ?? null)->count() ;
        }
        for ($k = 1; $k <= 5; $k++) {
            $konsumenhasil_[]           = LpjKonsumen::dashboardDealer()->areaKota(request()->lokasi)->dataDealer(request()->dealer)->dataHasil($k)->tanggal(request()->get('date-analisys') ?? null)->bulan(request()->get('month-analisys') ?? null)->tahun(request()->get('year-analisys') ?? null)->count() ;
        }
        for ($k = 1; $k <= 11; $k++) {
            $konsumendp_[]              = LpjKonsumen::dashboardDealer()->areaKota(request()->lokasi)->where('hasil', 4)->submitLpj()->dataDealer(request()->dealer)->dataDp($k)->tanggal(request()->get('date-analisys') ?? null)->bulan(request()->get('month-analisys') ?? null)->tahun(request()->get('year-analisys') ?? null)->count() ;
        }
        for ($k = 1; $k <= 11; $k++) {
            $konsumenpengeluaran_[]     = LpjKonsumen::dashboardDealer()->areaKota(request()->lokasi)->where('hasil', 4)->submitLpj()->dataDealer(request()->dealer)->dataPengeluaran($k)->tanggal(request()->get('date-analisys') ?? null)->bulan(request()->get('month-analisys') ?? null)->tahun(request()->get('year-analisys') ?? null)->count() ;
        }
        for ($k = 1; $k <= 24; $k++) {
            $konsumenpekerjaan_[]       = LpjKonsumen::dashboardDealer()->areaKota(request()->lokasi)->where('hasil', 4)->submitLpj()->dataDealer(request()->dealer)->dataPekerjaan($k)->tanggal(request()->get('date-analisys') ?? null)->bulan(request()->get('month-analisys') ?? null)->tahun(request()->get('year-analisys') ?? null)->count() ;
        }
        foreach ($datafincoy as $fincoy){
            $konsumenfincoy_[]          = LpjKonsumen::dashboardDealer()->areaKota(request()->lokasi)->where('hasil', 4)->submitLpj()->dataDealer(request()->dealer)->dataFincoy($fincoy->id)->tanggal(request()->get('date-analisys') ?? null)->bulan(request()->get('month-analisys') ?? null)->tahun(request()->get('year-analisys') ?? null)->count();
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
            $cashcredit_[]         = LpjKonsumen::dashboardDealer()->areaKota(request()->lokasi)->where('hasil', 4)->submitLpj()->dataDealer(request()->dealer)->dataCashCredit($k)->tanggal(request()->get('date-analisys') ?? null)->bulan(request()->get('month-analisys') ?? null)->tahun(request()->get('year-analisys') ?? null)->count() ;
        }
        for ($k = 1; $k <= 6; $k++) {
            $merkmotor_[]          = LpjKonsumen::dashboardDealer()->areaKota(request()->lokasi)->where('hasil', 4)->submitLpj()->dataDealer(request()->dealer)->dataMerkMotor($k)->tanggal(request()->get('date-analisys') ?? null)->bulan(request()->get('month-analisys') ?? null)->tahun(request()->get('year-analisys') ?? null)->count() ;
        }
        for ($k = 1; $k <= 4; $k++) {
            $jenismotor_[]         = LpjKonsumen::dashboardDealer()->areaKota(request()->lokasi)->where('hasil', 4)->submitLpj()->dataDealer(request()->dealer)->dataJenisMotor($k)->tanggal(request()->get('date-analisys') ?? null)->bulan(request()->get('month-analisys') ?? null)->tahun(request()->get('year-analisys') ?? null)->count() ;
        }
        foreach ($datadisplay as $motor_){
            $typeunit_[]           = LpjKonsumen::dashboardDealer()->areaKota(request()->lokasi)->where('hasil', 4)->submitLpj()->dataDealer(request()->dealer)->dataTypeUnit($motor_->id)->tanggal(request()->get('date-analisys') ?? null)->bulan(request()->get('month-analisys') ?? null)->tahun(request()->get('year-analisys') ?? null)->count() ;
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

        return view('cabang.dashboard',
                compact('datadealer', 'datalokasikota', 'dataactivitykota', 'datalokasikecamatan',
                        'datalokasikelurahan', 'datakategori', 'dataproposal', 'data_leaderboard_penjualan_dealer',
                        'datakonsumen', 'leaderboardsales', 'statistik', 'datafincoy', 'datadisplay'
                    ));
    }

    public function index() {
        $user = Auth::guard('cabang')->user();
        $getdealer = Dealer::find($user->dealer);
        // dd($getdealer->kota_dealer);

        $datadealer           = Dealer::where('id',$getdealer->id)->get();
        // $datadealer           = Dealer::cariKota($getdealer->kota_dealer)->get();
        $datadisplay          = Display::get();
        $datalokasikota       = Lokasi::select('kota_lokasi')->where('kota_lokasi',$getdealer->kota_dealer)->groupBy('kota_lokasi')->get();
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

        // if (request()->lokasi) { 
            $datalokasikecamatan = Lokasi::where('kota_lokasi', $getdealer->kota_dealer)->select('kecamatan_lokasi')->groupBy('kecamatan_lokasi')->get(); 
        // }
        // else { $datalokasikecamatan = null; }

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


        return view('cabang.dashboard',
                compact('datadealer', 'datalokasikota', 'dataactivitykota', 'datalokasikecamatan',
                        'datalokasikelurahan', 'datakategori', 'dataproposal', 'data_leaderboard_penjualan_dealer',
                        'datakonsumen', 'leaderboardsales', 'statistik', 'datafincoy', 'datadisplay'
                    ));
    }

    public function index2() {
        $user = Auth::guard('cabang')->user();
        $getdealer = Dealer::find($user->dealer);
        // dd($getdealer->kota_dealer);

        // $datadealer           = Dealer::where('id',$getdealer->id)->get();
        $datadealer           = Dealer::cariKota(request()->lokasi)->get();
        $datadisplay          = Display::get();
        $datalokasikota       = Lokasi::select('kota_lokasi')->where('kota_lokasi',$getdealer->kota_dealer)->groupBy('kota_lokasi')->get();
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

        // if (request()->lokasi) { 
            $datalokasikecamatan = Lokasi::where('kota_lokasi', $getdealer->kota_dealer)->select('kecamatan_lokasi')->groupBy('kecamatan_lokasi')->get(); 
        // }
        // else { $datalokasikecamatan = null; }

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


        return view('cabang.dashboard2',
                compact('datadealer', 'datalokasikota', 'dataactivitykota', 'datalokasikecamatan',
                        'datalokasikelurahan', 'datakategori', 'dataproposal', 'data_leaderboard_penjualan_dealer',
                        'datakonsumen', 'leaderboardsales', 'statistik', 'datafincoy', 'datadisplay'
                    ));
    }
}
