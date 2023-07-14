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
            $datas = Lpj::where('status_lpj', 2)
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
                    ->paginate(10);;
        } else {
            $datas = Lpj::where('status_lpj', 2)
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
                    ->paginate(10);
        }


        return view('pusat.lpj.index', compact('datas', 'datakategori', 'datadealer', 'datalokasi', 'dataarea'));
    }

    public function getShow(Request $request) {

        $data           = Lpj::where('uuid', request()->id)->first();

        $datafinance    = FinanceCompany::get();
        $datadana       = json_decode($data->dana_lpj ?? null, true);
        $datakonsumen   = LpjKonsumen::where('id_lpj', $data->id)->get();
        $datadisplay    = Display::get();

        return view('pusat.lpj.show', compact('datafinance', 'data', 'datadana', 'datakonsumen', 'datadisplay'));
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
}
