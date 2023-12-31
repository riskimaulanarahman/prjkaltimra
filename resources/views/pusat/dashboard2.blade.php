@extends('pusat.layouts.pusat')

@section('title', __('Dashboard LPJ'))

@section('content')
@php
    $h_ = 320;
    $i_ = 250;
@endphp

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script type="text/javascript" src="https://cdn3.devexpress.com/jslib/23.1.4/js/dx.all.js"></script>

<style>
    .graphic-container {
        min-height: 320px;
        max-height: 320px;
        overflow-y: auto;
        overflow-x: hidden;
    }
</style>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <form class="row" action="{{ url()->current() }}">
                        <div class="col-12">
                            <div class="row">
                                <div class="pb-2 col-sm">
                                    <div  style="padding-top: 5px; padding-bottom: 5px">
                                        <label class="form-check-label">Activity</label>
                                    </div>
                                    <select class="form-control" name="activity" id="activity" >
                                        <option value="SEMUA">Semua</option>
                                        @foreach ($datakategori as $data_ac)
                                            <option value="{{ $data_ac->id }}" {{ request()->activity == $data_ac->id ? 'selected' : '' }}>{{ $data_ac->nama_kategori }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="pb-2 col-sm">
                                    <div  style="padding-top: 5px; padding-bottom: 5px">
                                        <label class="form-check-label">Area</label>
                                    </div>
                                    <select class="form-control data-lokasi" name="lokasi" >
                                        <option value="SEMUA">Semua</option>
                                        @foreach ($datalokasikota as $data_kt)
                                            <option value="{{ $data_kt->kota_lokasi }}" {{ request()->lokasi == $data_kt->kota_lokasi ? 'selected' : '' }}>
                                                {{ Str::title($data_kt->kota_lokasi) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="pb-2 col-sm">
                                    @if(request()->lokasi && request()->lokasi != 'SEMUA')
                                        <div style="padding-top: 5px; padding-bottom: 5px">
                                            <label class="form-check-label">Dealer</label>
                                        </div>
                                        <select name="dealer" class="data-dealer form-control" >
                                            <option value="SEMUA">Semua</option>
                                            @foreach ($datadealer as $data_d)
                                                <option value="{{ $data_d->id }}" {{ request()->dealer == $data_d->id ? 'selected' : '' }}>{{ $data_d->nama_dealer }}, {{ Str::title($data_d->kota_dealer) }}</option>
                                            @endforeach
                                        </select>
                                    @endif
                                </div>
                                <div class="pb-2 col-sm">
                                    @if(request()->lokasi && request()->lokasi != 'SEMUA')
                                        <div  style="padding-top: 5px; padding-bottom: 5px">
                                            <label class="form-check-label">Kecamatan</label>
                                        </div>
                                        <select class="form-control data-lokasi" name="kecamatan" >
                                            <option value="SEMUA">Semua</option>
                                            @foreach ($datalokasikecamatan as $data_kec)
                                                <option value="{{ $data_kec->kecamatan_lokasi }}" {{ request()->kecamatan == $data_kec->kecamatan_lokasi ? 'selected' : '' }}>
                                                    {{ Str::title($data_kec->kecamatan_lokasi) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    @endif
                                </div>
                                <div class="pb-2 col-sm">
                                    @if(request()->lokasi && request()->lokasi != 'SEMUA' && request()->kecamatan && request()->kecamatan != 'SEMUA')
                                        <div  style="padding-top: 5px; padding-bottom: 5px">
                                            <label class="form-check-label">Kelurahan</label>
                                        </div>
                                        <select class="form-control data-lokasi" name="kelurahan" >
                                            <option value="SEMUA">Semua</option>
                                            @foreach ($datalokasikelurahan as $data_kel)
                                                <option value="{{ $data_kel->kelurahan_lokasi }}" {{ request()->kelurahan == $data_kel->kelurahan_lokasi ? 'selected' : '' }}>
                                                    {{ Str::title($data_kel->kelurahan_lokasi) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    @endif
                                </div>
                                <div class="pb-2 col-sm">
                                    <div  style="padding-top: 5px; padding-bottom: 5px">
                                        <label class="form-check-label">Start Date</label>
                                    </div>
                                    {{-- <input type="date" class="form-control" name="startdate" value="2023-05-01" required> --}}
                                    <input type="date" class="form-control" name="startdate" value="{{ request()->input('startdate') ?? '' }}" required>
                                </div>
                                <div class="pb-2 col-sm">
                                    <div  style="padding-top: 5px; padding-bottom: 5px">
                                        <label class="form-check-label">End Date</label>
                                        {{-- <input type="date" class="form-control" name="enddate" value="2023-05-31" required> --}}
                                    </div>
                                    <input type="date" class="form-control" name="enddate" value="{{ request()->input('enddate') ?? '' }}" required>
                                </div>
                            </div>
                            <div class="row pull-right">
                                <div class="col-sm pt-2">
                                    <button type="submit" class="btn btn-primary"><i class="fas fa-search pr-3"></i> Submit</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if (request()->input('startdate') && request()->input('enddate'))

        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-6">
                        <div id="chart4"></div>
                    </div>
                    <div class="col-sm-6">
                        <div class="row">
                            <div class="col-12">
                                <label class="font-weight-bold">Leaderboard Penjualan Dealer</label>
                            </div>
                            <div class="col-12">
                                <div class="row p-2" style="font-size: 9px">
                                    <div class="col-1 p-0 pl-2">No.</div>
                                    <div class="col font-weight-bold justify-content-start">
                                        Nama Dealer
                                    </div>
                                    <div class="col font-weight-bold text-right">
                                        Jumlah Penjualan
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 graphic-container">
                                @php
                                    $no_pen = 1;
                                    $data_pen = 400;
                                @endphp
                                @foreach ($data_leaderboard_penjualan_dealer as $dealer_pen)
                                    <div class="row p-2" style="font-size: 9px">
                                        <div class="col-1 p-0 pl-2">{{ $no_pen++; }}</div>
                                        <div class="col font-weight-bold justify-content-start" style="color: #ec1b25;">
                                            {{ data_get($dealer_pen, 'dealer_') }}
                                        </div>
                                        <div class="col-3 font-weight-bold text-right">{{ data_get($dealer_pen, 'penjualan_') }}</div>
                                    </div>
                                    <hr class="m-0 p-0">
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="row">
                            <div class="col-12">
                                <label class="font-weight-bold">Leaderboard Sales People</label>
                            </div>
                            <div class="col-12">
                                <div class="row p-2" style="font-size: 9px">
                                    <div class="col-1 p-0 pl-2">No.</div>
                                    <div class="col font-weight-bold justify-content-start">
                                        Nama
                                    </div>
                                    <div class="col font-weight-bold justify-content-start">
                                        Dealer
                                    </div>
                                    <div class="col font-weight-bold text-right">
                                        Jumlah SSU
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 graphic-container">
                                @php
                                    $no_lpj = 1;
                                    $data_lpj = 36;
                                @endphp
                                @foreach ($leaderboardsales as $leaderboard_sales)
                                    <div class="row p-2" style="font-size: 9px">
                                        <div class="col-1 p-0 pl-2">{{ $no_lpj++ }}</div>
                                        @php
                                            $namaDealer = $leaderboard_sales->sales->dealer->nama_dealer;
                                            $words = explode(' ', $namaDealer);
                                            $singkatandealer = '';

                                            foreach ($words as $word) {
                                                $singkatandealer .= strtoupper(substr($word, 0, 1));
                                            }
                                        @endphp
                                        <div class="col font-weight-bold justify-content-start" style="color: #ec1b25;">
                                            {{ $leaderboard_sales->sales->nama_sales_people }}
                                        </div>
                                        <div class="col font-weight-bold justify-content-start">{{$namaDealer}}</div>
                                        <div class="col-3 font-weight-bold text-right">{{ $leaderboard_sales->total_ssu }}</div>
                                    </div>
                                    <hr class="m-0 p-0">
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div id="chart17"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <h4>Summary Cost/Unit </h4><i>(monthly & all dealer)</i>
                        <div class="row">

                            <div class="col-md-6" style="height: 400px; overflow:scroll;">
                                <table class="table table-bordered">
                                    <thead style="position: sticky; top: 0; background-color: white;">
                                        <tr>
                                            <th>Nama Dealer</th>
                                            <th>Nama Kategori</th>
                                            <th>{{ $bulanHeader }} : Cost/Unit</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tablea">
                                        @php
                                            $currentDealer = null; // Inisialisasi dealer saat ini
                                        @endphp

                                        @foreach ($datacostunit as $item)
                                        <tr class="nama_kategori {{ $item->nama_kategori === 'Total' ? 'total' : '' }}" @if ($item->nama_kategori === 'Total') style="background-color: lightgray;" @endif>
                                            <td>{{ $item->nama_dealer }} ({{$item->target_penjualan_lpj}})</td>
                                            <td>{{ $item->nama_kategori }}</td>
                                            <td>{{ $item->costunit }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-6" style="height: 400px; overflow:scroll;">
                                <table class="table table-bordered">
                                    <thead style="position: sticky; top: 0; background-color: white;">
                                        <tr>
                                            <th>Nama Dealer</th>
                                            <th>Nama Kategori</th>
                                            <th>{{ $bulanHeader2 }} : Cost/Unit</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tableb">
                                        @foreach ($datacostunit2 as $item)
                                        <tr class="nama_kategori2 {{ $item->nama_kategori === 'Total' ? 'total' : '' }}" @if ($item->nama_kategori === 'Total') style="background-color: lightgray;" @endif>
                                            <td>{{ $item->nama_dealer }} ({{$item->target_penjualan_lpj}})</td>
                                            <td>{{ $item->nama_kategori }}</td>
                                            <td>{{ number_format($item->costunit, 0, ',', '.') }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="mt-3">
                            <strong>Grand Total Cost/Unit {{ $bulanHeader }} : {{number_format($totaldana, 0, ',', '.') }} / {{$targetpenjualan}} = {{number_format($totaldana/$targetpenjualan, 0, ',', '.')}}</strong> 
                            <strong style="float: right;">Grand Total Cost/Unit {{ $bulanHeader2 }} : {{number_format($totaldana2, 0, ',', '.') }} / {{$targetpenjualan2}} = {{number_format($totaldana2/$targetpenjualan2, 0, ',', '.')}}</strong> 
                        </div>


                        {{-- <div id="costunit"></div> --}}
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-9">
                        <div class="row">
                            <div class="col-sm-4">
                                <div id="chart7"></div>
                            </div>
                            <div class="col-sm-4">
                                <div id="chart8"></div>
                            </div>
                            <div class="col-sm-4">
                                <h4>Penjualan</h4>
                                @php
                                    $konsumen_hasil = data_get($statistik, 'konsumen_hasil');
                                    $angka_50 = $konsumen_hasil[0];
                                @endphp
                                {{-- <div id="chart9"></div> --}}
                                <div style="margin-top: 10%; margin-left:10%">
                                    <b style="font-size: 90px">{{$angka_50}}</b>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div id="chart10"></div>
                            </div>
                            <div class="col-sm-4">
                                <div id="chart11"></div>
                            </div>
                            <div class="col-sm-4">
                                <div id="chart12"></div>
                            </div>
                            <div class="col-sm-4">
                                <div id="chart14"></div>
                            </div>
                            <div class="col-sm-4">
                                <div id="chart15"></div>
                            </div>
                            <div class="col-sm-4">
                                <div id="chart16"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div id="chart13"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h4>Unit Entry & Revenue</h4>
                <div class="row">
                    <div class="col-md-6">
                        <div id="chartunitentry"></div>
                    </div>
                    <div class="col-md-6">
                        <div id="chartrevenue"></div>
                    </div>
                </div>
            </div>
        </div>



<script>

var currentUrl = window.location.href;

// Mengekstrak parameter startdate dari URL
var urlParams = new URLSearchParams(currentUrl);
var startdateparam = urlParams.get('startdate');
// $(document).ready(function(){

    setTimeout(() => {
        $('.nama_kategori:not(.total)').hide();
        $('.nama_kategori.total').click(function() {
            console.log('click')
            $('.nama_kategori:not(.total)').toggle();
        });
    }, 300);

    setTimeout(() => {
        $('.nama_kategori2:not(.total)').hide();
        $('.nama_kategori2.total').click(function() {
            $('.nama_kategori2:not(.total)').toggle();
        });
    }, 300);

    function sortTable() {
            var $tbody = $('#tablea');
            var $rows = $tbody.find('tr');

            // Create an object to store and calculate total "Cost/Unit" for each dealer
            var dealerTotals = {};

            // Calculate and store the total "Cost/Unit" for each dealer
            $rows.each(function () {
                var namaKategori = $(this).find('td:eq(1)').text().trim(); // Get the Nama Kategori

                    var dealerName = $(this).find('td:eq(0)').text().split(' (')[0].trim();
                    var costUnit = parseFloat($(this).find('td:eq(2)').text().replace(/[^0-9.]/g, ''));

                    if (!dealerTotals[dealerName]) {
                        dealerTotals[dealerName] = {
                            totalCost: 0,
                            rows: []
                        };
                    }

                    if (namaKategori == 'Total') {
                        dealerTotals[dealerName].totalCost += costUnit;
                    }

                    dealerTotals[dealerName].rows.push(this);
            });

            console.log(dealerTotals)

            // Sort dealers based on total "Cost/Unit" from lowest to highest
            var sortedDealers = Object.keys(dealerTotals).sort(function (a, b) {
                var totalA = dealerTotals[a].totalCost;
                var totalB = dealerTotals[b].totalCost;

                // If the totals are equal, sort by dealer name
                if (totalA === totalB) {
                    return a.localeCompare(b);
                }

                return totalA - totalB;
            });
            // Combine the sorted dealer groups into a single array of rows
            var sortedRows = [];
            sortedDealers.forEach(function (dealerName) {
                var dealer = dealerTotals[dealerName];
                sortedRows = sortedRows.concat(dealer.rows);
            });

            // Append sorted rows back to the table
            $tbody.empty().append(sortedRows);
    }

    function sortTable2() {
            var $tbody = $('#tableb');
            var $rows = $tbody.find('tr');

            // Create an object to store and calculate total "Cost/Unit" for each dealer
            var dealerTotals = {};

            // Calculate and store the total "Cost/Unit" for each dealer
            $rows.each(function () {
                var namaKategori = $(this).find('td:eq(1)').text().trim(); // Get the Nama Kategori

                    var dealerName = $(this).find('td:eq(0)').text().split(' (')[0].trim();
                    var costUnit = parseFloat($(this).find('td:eq(2)').text().replace(/[^0-9.]/g, ''));

                    if (!dealerTotals[dealerName]) {
                        dealerTotals[dealerName] = {
                            totalCost: 0,
                            rows: []
                        };
                    }

                    if (namaKategori == 'Total') {
                        dealerTotals[dealerName].totalCost += costUnit;
                    }

                    dealerTotals[dealerName].rows.push(this);
            });

            console.log(dealerTotals)

            // Sort dealers based on total "Cost/Unit" from lowest to highest
            var sortedDealers = Object.keys(dealerTotals).sort(function (a, b) {
                var totalA = dealerTotals[a].totalCost;
                var totalB = dealerTotals[b].totalCost;

                // If the totals are equal, sort by dealer name
                if (totalA === totalB) {
                    return a.localeCompare(b);
                }

                return totalA - totalB;
            });
            // Combine the sorted dealer groups into a single array of rows
            var sortedRows = [];
            sortedDealers.forEach(function (dealerName) {
                var dealer = dealerTotals[dealerName];
                sortedRows = sortedRows.concat(dealer.rows);
            });

            // Append sorted rows back to the table
            $tbody.empty().append(sortedRows);
    }

        // Initially, sort the table when the page loads
        sortTable();
        sortTable2();

    // $.getJSON('/main/getcostunit', { startdate: startdateparam },function(items){

    //     // console.log(items)
    //     $('#costunit').dxPivotGrid({
    //         allowSortingBySummary: true,
    //         allowSorting: true,
    //         allowFiltering: true,
    //         allowExpandAll: true,
	// 		showColumnGrandTotals: true,
    //         height: 440,
    //         showBorders: true,
    //         fieldChooser: {
    //             enabled: true,
    //             height: 600,
    //             allowSearch: true,
    //         },
    //         dataSource: {
    //         fields: [{
    //             width: 120,
    //             dataField: 'nama_dealer',
    //             area: 'row',
    //             sortBySummaryField: "costunit",
    //             sortOrder: 'desc'
    //         }, 
    //         {
    //             dataField: 'nama_kategori',
    //             width: 150,
    //             area: 'row',
    //         }, 
    //         {
    //             dataField: 'bulan',
    //             area: 'column',
    //         }, 
    //         {
    //             caption: 'Jumlah',
    //             dataField: 'costunit',
    //             dataType: 'number',
    //             summaryType: 'sum',
    //             format: 'fixedPoint',
    //             area: 'data',
    //         }],
    //         store: items,
    //         },
    //     });
    // })

    $.getJSON('/main/getunitentrychart',function(items){

        $('#chartunitentry').dxChart({
            dataSource : items,
            palette: 'soft',
            commonSeriesSettings: {
                argumentField: 'nama_bulan',
                type: 'bar',
                hoverMode: 'allArgumentPoints',
                selectionMode: 'allArgumentPoints',
                label: {
                    visible: true,
                    format: {
                        type: 'fixedPoint',
                        precision: 0,
                    },
                },
            },
            tooltip: {
            enabled: true,
            location: 'edge',
            customizeTooltip(arg) {
                return {
                    text: `${arg.seriesName} : ${arg.valueText}`,
                    };
                },
            },
            series: [
                { valueField: 'kpb_1', name: 'kpb_1'},
                { valueField: 'kpb_2', name: 'kpb_2'},
                { valueField: 'kpb_3', name: 'kpb_3'},
                { valueField: 'kpb_4', name: 'kpb_4'},
                { valueField: 'psl', name: 'psl'},
                { valueField: 'psr', name: 'psr'},
                { valueField: 'go', name: 'go'},
                { valueField: 'lr', name: 'lr'},
            ],
            title: 'Unit Entrys',
            legend: {
                verticalAlignment: 'bottom',
                horizontalAlignment: 'center',
            },
            export: {
                enabled: true,
            },
            onPointClick(e) {
                e.target.select();
            },
            onLegendClick(e) {
                const series = e.target;
                if (series.isVisible()) {
                    series.hide();
                } else {
                    series.show();
                }
            },
        });
    });

    $.getJSON('/main/getrevenuechart',function(items){

        $('#chartrevenue').dxChart({
            dataSource : items,
            commonSeriesSettings: {
            argumentField: 'nama_bulan',
            type: 'bar',
            hoverMode: 'allArgumentPoints',
            selectionMode: 'allArgumentPoints',
            label: {
                visible: true,
                format: {
                type: 'fixedPoint',
                precision: 0,
                },
            },
            },
            tooltip: {
            enabled: true,
            location: 'edge',
            customizeTooltip(arg) {
                return {
                text: `${arg.seriesName} : ${arg.valueText}`,
                };
            },
            },
            series: [
            { valueField: 'jasa', name: 'jasa' },
            { valueField: 'part', name: 'part' },
            { valueField: 'oli', name: 'oli' },
            ],
            title: 'Unit Revenue',
            legend: {
                verticalAlignment: 'bottom',
                horizontalAlignment: 'center',
            },
            export: {
                enabled: true,
            },
            onPointClick(e) {
                e.target.select();
            },
            onLegendClick(e) {
                const series = e.target;
                if (series.isVisible()) {
                    series.hide();
                } else {
                    series.show();
                }
            },
        });
    });
// })
</script>

        {{-- 4 CHART BARIS 2 BAGIAN KIRI --}}
        <script>
            var options = {
                series: [
                    {
                        name: 'Downloader Motorku-X',
                        data: [
                            @foreach ($datakategori as $kat_ac_3_a)
                                @php
                                    $target = 0;
                                @endphp
                                @foreach ($kat_ac_3_a->finalactivity($kat_ac_3_a->id, request()->lokasi, request()->dealer, request()->get('startdate'), request()->get('enddate'))->get() as $target_lpj_a)
                                    @php
                                        $target = $target + $target_lpj_a->lpj->sum('target_downloader_lpj');
                                    @endphp
                                @endforeach
                                {{ $target }},
                            @endforeach
                        ]
                    },
                    {
                        name: 'Database',
                        data: [
                            @foreach ($datakategori as $kat_ac_3_b)
                                @php
                                    $target = 0;
                                @endphp
                                @foreach ($kat_ac_3_b->finalactivity($kat_ac_3_b->id, request()->lokasi, request()->dealer, request()->get('startdate'), request()->get('enddate'))->get() as $target_lpj_a)
                                    @php
                                        $target = $target + $target_lpj_a->lpj->sum('target_database_lpj');
                                    @endphp
                                @endforeach
                                {{ $target }},
                            @endforeach
                        ]
                    },
                    {
                        name: 'Prospecting',
                        data: [
                            @foreach ($datakategori as $kat_ac_3_c)
                                @php
                                    $target = 0;
                                @endphp
                                @foreach ($kat_ac_3_c->finalactivity($kat_ac_3_c->id, request()->lokasi, request()->dealer, request()->get('startdate'), request()->get('enddate'))->get() as $target_lpj_a)
                                    @php
                                        $target = $target + $target_lpj_a->lpj->sum('target_prospectus_lpj');
                                    @endphp
                                @endforeach
                                {{ $target }},
                            @endforeach
                        ]
                    },
                    {
                        name: 'Penjualan',
                        data: [
                            @foreach ($datakategori as $kat_ac_3_d)
                                @php
                                    $target = 0;
                                @endphp
                                @foreach ($kat_ac_3_d->finalactivity($kat_ac_3_d->id, request()->lokasi, request()->dealer, request()->get('startdate'), request()->get('enddate'))->get() as $target_lpj_a)
                                    @php
                                        // dd($target_lpj_a->lpj);
                                        $target = $target + $target_lpj_a->lpj->where('status_lpj',2)->sum('target_penjualan_lpj');
                                    @endphp
                                @endforeach
                                {{ $target }},
                            @endforeach
                        ]
                    }
                ],
                plotOptions: {
                    bar: {
                        horizontal: true,
                        endingShape: 'rounded',
                        borderRadius: 4,
                    }
                },
                chart: {
                    type: 'bar',
                    height: 400,
                    stacked: true,
                },
                title: {
                    text: 'Data'
                },
                theme: {
                    palette: 'palette4' // upto palette10
                },
                xaxis: {
                    categories: [
                        @foreach ($datakategori as $kat_ac_4)
                            "{{ $kat_ac_4->nama_kategori }}",
                        @endforeach
                    ],
                },
                fill: {
                    opacity: 1
                },
                legend: {
                    position: 'top',
                    horizontalAlign: 'left',
                    offsetX: 40
                },
                dataLabels: {
                    enabled: true,
                    style: {
                        fontSize: '10px',
                        colors: ["#222"]
                    }
                },
            };

            var chart = new ApexCharts(document.querySelector("#chart4"), options);

            chart.render();
        </script>

        {{-- 7 CHART BARIS 3 Gender--}}
        <script>
            var options = {
                series: {{ json_encode(data_get($statistik, 'konsumen_gender')) }},
                chart: {
                    height: {{ $i_ }},
                    type: 'pie',
                },
                title: {
                    text: "Gender"
                },
                theme: {
                    palette: 'palette2' // upto palette10
                },
                legend: {
                    position: 'top'
                },
                labels: ['Laki-laki', 'Perempuan'],
                dataLabels: {
                    enabled: true,
                        style: {
                            fontSize: '10px'
                        }
                },
                responsive: [{
                    breakpoint: 480,
                    options: {
                        chart: {
                            width: 200
                        },
                        legend: {
                            position: 'top'
                        }
                    }
                }]
            };

            var chart = new ApexCharts(document.querySelector("#chart7"), options);

            chart.render();
        </script>

        {{-- 8 CHART BARIS 3 Usia--}}
        <script>
            var options = {
            chart: {
                height: {{ $i_ }},
                type: "line"
            },
            plotOptions: {
            bar: {
                borderRadius: 4,
            }
            },
            title: {
                text: "Usia (Tahun)"
            },
            labels: ["Jumlah"],
            fill: {
                    opacity: 1
            },
            legend: {
                position: 'top'
            },
            series: [
                {
                    name: "> 17 Thn",
                    type: "column",
                    data: [{{ json_encode(data_get($statistik, 'konsumen_umur_1')) }}]
                },
                {
                    name: "17 - 25 Thn",
                    type: "column",
                    data: [{{ json_encode(data_get($statistik, 'konsumen_umur_2')) }}]
                },
                {
                    name: "26 - 35 Thn",
                    type: "column",
                    data: [{{ json_encode(data_get($statistik, 'konsumen_umur_3')) }}]
                },
                {
                    name: "36 - 45 Thn",
                    type: "column",
                    data: [{{ json_encode(data_get($statistik, 'konsumen_umur_4')) }}]
                },
                {
                    name: "46 - 55 Thn",
                    type: "column",
                    data: [{{ json_encode(data_get($statistik, 'konsumen_umur_5')) }}]
                },
                {
                    name: "> 55 Thn",
                    type: "column",
                    data: [{{ json_encode(data_get($statistik, 'konsumen_umur_6')) }}]
                },
            ],
            stroke: {
                width: 4,
                curve: 'smooth',
                colors: ['transparent']
            },
            theme: {
                palette: 'palette5' // upto palette10
            },
            dataLabels: {
            enabled: true,
                style: {
                    fontSize: '11px',
                    colors: ["#304758"]
                }
            },
            xaxis: {
                labels: {
                    style: {
                        fontSize: '11px'
                    }
                }
            },
            };

            var chart = new ApexCharts(document.querySelector("#chart8"), options);

            chart.render();
        </script>

        {{-- 9 CHART BARIS 3 Hasil--}}
        {{-- <script>
            var options = {
            chart: {
                height: {{ $i_ }},
                type: "bar"
            },
            plotOptions: {
            bar: {
                horizontal: true,
                endingShape: 'rounded',
                borderRadius: 4,
            }
            },
            legend: {
                position: 'top'
            },
            series: [
                {
                    name: "Hasil",
                    type: "column",
                    data: {{ json_encode(data_get($statistik, 'konsumen_hasil')) }}
                },
            ],
            title: {
                text: "Hasil"
            },
            theme: {
                palette: 'palette9' // upto palette10
            },
            dataLabels: {
            enabled: true,
                style: {
                    fontSize: '10px',
                    colors: ["#fff"]
                }
            },
            xaxis: {
                categories: [
                    // 'Database',
                    // 'Prospecting',
                    // 'Polling',
                    'SSU',
                    // 'Reject',
                ],
                labels: {
                    style: {
                        fontSize: '9px'
                    }
                }
            }
            };

            var chart = new ApexCharts(document.querySelector("#chart9"), options);

            chart.render();
        </script> --}}

        {{-- 10 CHART BARIS 3 Range DP--}}
        <script>

            var options = {
            chart: {
                height: {{ $i_ }},
                type: "bar"
            },
            plotOptions: {
            bar: {
                horizontal: true,
                endingShape: 'rounded',
                borderRadius: 4,
            }
            },
            legend: {
                position: 'top'
            },
            series: [
                {
                    name: "DP",
                    type: "column",
                    data: {{ json_encode(data_get($statistik, 'konsumen_dp')) }}
                },
            ],
            title: {
                text: "Range DP"
            },
            theme: {
                palette: 'palette2' // upto palette10
            },
            dataLabels: {
            enabled: true,
                style: {
                    fontSize: '10px',
                    colors: ["#fff"]
                }
            },
            xaxis: {
                categories: [
                    '≤ 1 jt',
                    '1 - 2 jt',
                    '2 - 3 jt',
                    '3 - 4 jt',
                    '4 - 5 jt',
                    '5 - 6 jt',
                    '6 - 7 jt',
                    '7 - 8 jt',
                    '8 - 9 jt',
                    '9 - 10 jt',
                    '> 10 jt'
                ],
                labels: {
                    style: {
                        fontSize: '9px'
                    }
                }
            }
            };

            var chart = new ApexCharts(document.querySelector("#chart10"), options);

            chart.render();
        </script>

        {{-- 11 CHART BARIS 3 Pengeluaran--}}
        <script>
            var options = {
            chart: {
                height: {{ $i_ }},
                type: "bar"
            },
            plotOptions: {
            bar: {
                horizontal: true,
                endingShape: 'rounded',
                borderRadius: 4,
            }
            },
            legend: {
                position: 'top'
            },
            series: [
                {
                    name: "Pengeluaran",
                    type: "column",
                    data: {{ json_encode(data_get($statistik, 'konsumen_pengeluaran')) }}
                },
            ],
            title: {
                text: "Pengeluaran"
            },
            theme: {
                palette: 'palette8' // upto palette10
            },
            dataLabels: {
            enabled: true,
                style: {
                    fontSize: '10px',
                    colors: ["#fff"]
                }
            },
            xaxis: {
                categories: [
                    '< Rp 900.000,- ',
                    'Rp 900.001,- s/d Rp 1.250.000,- ',
                    'Rp 1.250.001,- s/d Rp 1.750.000,- ',
                    'Rp 1.750.001,- s/d Rp 2.500.000,- ',
                    'Rp 2.500.001,- s/d Rp 4.000.000,- ',
                    'Rp 4.000.001.- s/d Rp 6.000.000,- ',
                    '> Rp 6.000.000,- '
                ],
                labels: {
                    style: {
                        fontSize: '9px'
                    }
                }
            }
            };

            var chart = new ApexCharts(document.querySelector("#chart11"), options);

            chart.render();
        </script>

        {{-- 12 CHART BARIS 3 Finance Company--}}
        <script>
            var options = {
            chart: {
                height: {{ $i_ }},
                type: "bar"
            },
            plotOptions: {
            bar: {
                horizontal: true,
                endingShape: 'rounded',
                borderRadius: 4,
            }
            },
            legend: {
                position: 'top'
            },
            series: [
                {
                    name: "Finance Company",
                    type: "column",
                    data: {{ json_encode(data_get($statistik, 'konsumen_fincoy')) }}
                },
            ],
            title: {
                text: "Finance Company"
            },
            theme: {
                palette: 'palette3' // upto palette10
            },
            dataLabels: {
            enabled: true,
                style: {
                    fontSize: '10px',
                    colors: ["#fff"]
                }
            },
            xaxis: {
                categories: [
                    @foreach ($datafincoy as $fincoy)
                        "{{ $fincoy->nama }}",
                    @endforeach
                ],
                labels: {
                    style: {
                        fontSize: '9px'
                    }
                }
            }
            };

            var chart = new ApexCharts(document.querySelector("#chart12"), options);

            chart.render();
        </script>

        {{-- 13 CHART BARIS 3 Pekerjaan--}}
        <script>

            var options = {
            chart: {
                height: 518,
                type: "bar"
            },
            plotOptions: {
            bar: {
                horizontal: true,
                endingShape: 'rounded',
                borderRadius: 4,
            }
            },
            legend: {
                position: 'top'
            },
            series: [
                {
                    name: "Pekerjaan",
                    type: "column",
                    data: {{ json_encode(data_get($statistik, 'konsumen_pekerjaan')) }}
                },
            ],
            title: {
                text: "Pekerjaan"
            },
            theme: {
                palette: 'palette7' // upto palette10
            },
            dataLabels: {
            enabled: true,
                style: {
                    fontSize: '10px',
                    colors: ["#fff"]
                }
            },
            xaxis: {
                categories: [
                    'Pegawai Negeri',
                    'Pegawai Swasta Pertanian/ Perkebunan/ Kehutanan/ Perikanan/ Peternakan',
                    'Pegawai Swasta Industri',
                    'Pegawai Swasta Konstruksi',
                    'Pegawai Swasta Pertambangan',
                    'Pegawai Swasta Jasa',
                    'Pegawai Swasta Perdagangan (Retail)',
                    'Ojek',
                    'Pertanian/ Perkebunan/ Kehutanan/ Perikanan/ Peternakan',
                    'Industri',
                    'Konstruksi',
                    'Pertambangan',
                    'Jasa',
                    'Perdagangan (Retail)',
                    'Mahasiswa/ Pelajar',
                    'Guru / Dosen',
                    'TNI/ Polri',
                    'Ibu Rumah Tangga',
                    'Dokter',
                    'Pengacara',
                    'Wartawan',
                    'Petani',
                    'Nelayan',
                    'Lainnya..',
                ],
                labels: {
                    style: {
                        fontSize: '9px'
                    }
                }
            }
            };

            var chart = new ApexCharts(document.querySelector("#chart13"), options);

            chart.render();
        </script>

        {{-- 14 CHART BARIS 4 Cash Credit--}}
        <script>
            var options = {
            chart: {
                height: {{ $i_ }},
                type: "pie"
            },
            plotOptions: {
            bar: {
                horizontal: true,
                endingShape: 'rounded',
                borderRadius: 4,
            }
            },
            legend: {
                position: 'top'
            },
            series: {{ json_encode(data_get($statistik, 'konsumen_cash_credit')) }},
            title: {
                text: "Jenis Pembayaran"
            },
            theme: {
                palette: 'palette7' // upto palette10
            },
            dataLabels: {
            enabled: true,
                style: {
                    fontSize: '10px',
                    colors: ["#fff"]
                }
            },
            labels: ['Cash', 'Credit ',],
            xaxis: {
                categories: [
                    'Cash',
                    'Credit ',
                ],
                labels: {
                    style: {
                        fontSize: '9px'
                    }
                }
            }
            };

            var chart = new ApexCharts(document.querySelector("#chart14"), options);

            chart.render();
        </script>

        {{-- 15 CHART BARIS 4 Merk Motor Sebelumnya--}}
        <script>
            var options = {
            chart: {
                height: {{ $i_ }},
                type: "bar"
            },
            plotOptions: {
            bar: {
                horizontal: true,
                endingShape: 'rounded',
                borderRadius: 4,
            }
            },
            legend: {
                position: 'top'
            },
            series: [
                {
                    name: "Merk Motor Sebelumnya",
                    type: "column",
                    data: {{ json_encode(data_get($statistik, 'konsumen_merk_motor')) }}
                },
            ],
            title: {
                text: "Merk Motor Sebelumnya"
            },
            theme: {
                palette: 'palette4' // upto palette10
            },
            dataLabels: {
            enabled: true,
                style: {
                    fontSize: '10px',
                    colors: ["#fff"]
                }
            },
            xaxis: {
                categories: [
                    'Honda',
                    'Yamaha',
                    'Suzuki',
                    'Kawasaki',
                    'Motor Lain',
                    'Belum Pernah Memiliki',
                ],
                labels: {
                    style: {
                        fontSize: '9px'
                    }
                }
            }
            };

            var chart = new ApexCharts(document.querySelector("#chart15"), options);

            chart.render();
        </script>

        {{-- 16 CHART BARIS 4 Jenis Motor Sebelumnya--}}
        <script>
            var options = {
            chart: {
                height: {{ $i_ }},
                type: "bar"
            },
            plotOptions: {
            bar: {
                horizontal: true,
                endingShape: 'rounded',
                borderRadius: 4,
            }
            },
            legend: {
                position: 'top'
            },
            series: [
                {
                    name: "Jenis Motor Sebelumnya",
                    type: "column",
                    data: {{ json_encode(data_get($statistik, 'konsumen_jenis_motor')) }}
                },
            ],
            title: {
                text: "Jenis Motor Sebelumnya"
            },
            theme: {
                palette: 'palette1' // upto palette10
            },
            dataLabels: {
            enabled: true,
                style: {
                    fontSize: '10px',
                    colors: ["#fff"]
                }
            },
            xaxis: {
                categories: [
                    'Sport',
                    'Cub (Bebek)',
                    'AT (Automatic)',
                    'Belum Pernah Memiliki',
                ],
                labels: {
                    style: {
                        fontSize: '9px'
                    }
                }
            }
            };

            var chart = new ApexCharts(document.querySelector("#chart16"), options);

            chart.render();
        </script>

        {{-- 17 CHART BARIS 2 Type Unit--}}
        <script>
            var options = {
            chart: {
                height: 400,
                type: "bar"
            },
            plotOptions: {
            bar: {
                horizontal: true,
                endingShape: 'rounded',
                borderRadius: 4,
            }
            },
            legend: {
                position: 'top'
            },
            series: [
                {
                    name: "Type Unit SSU",
                    type: "column",
                    data: {{ json_encode(data_get($statistik, 'konsumen_type_unit')) }}
                },
            ],
            title: {
                text: "Type Unit SSU"
            },
            theme: {
                palette: 'palette1' // upto palette10
            },
            dataLabels: {
            enabled: true,
                style: {
                    fontSize: '10px',
                    colors: ["#fff"]
                }
            },
            xaxis: {
                categories: [
                    @foreach ($datadisplay as $motor_)
                        "{{ $motor_->nama_display }}",
                    @endforeach
                ],
                labels: {
                    style: {
                        fontSize: '9px'
                    }
                }
            }
            };

            var chart = new ApexCharts(document.querySelector("#chart17"), options);

            chart.render();
        </script>
    @else

    @endif


@endsection
