@extends('pusat.layouts.pusat')

@section('title', __('Dashboard Proposal'))

@section('content')
@php
    $h_ = 320;
    $i_ = 250;
@endphp
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
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
                                    <select class="form-control" name="activity" id="activity">
                                        <option value="SEMUA">Semua</option>
                                        {{-- @foreach ($datakategori as $data_ac)
                                            <option value="{{ $data_ac->id }}" {{ request()->activity == $data_ac->id ? 'selected' : '' }}>{{ $data_ac->nama_kategori }}</option>
                                        @endforeach --}}
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
                                {{-- <div class="pb-2 col-sm">
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
                                </div> --}}
                                <div class="pb-2 col-sm">
                                    <div  style="padding-top: 5px; padding-bottom: 5px">
                                        <label class="form-check-label">Start Date</label>
                                    </div>
                                    <input type="date" class="form-control" name="startdate" value="{{ request()->input('startdate') ?? '' }}" required>
                                    {{-- <select class="form-control data-lokasi" name="analisys" onchange='if(this.value != 0) { this.form.submit(); }'>
                                        <option value="1" {{ request()->analisys == 1 ? 'selected' : '' }}>Daily</option>
                                        <option value="2" {{ request()->analisys == 2 || empty(request()->analisys) ? 'selected' : '' }}>Monthly</option>
                                        <option value="3" {{ request()->analisys == 3 ? 'selected' : '' }}>Yearly</option>
                                    </select> --}}
                                </div>
                                <div class="pb-2 col-sm">
                                    <div  style="padding-top: 5px; padding-bottom: 5px">
                                        <label class="form-check-label">End Date</label>
                                        <input type="date" class="form-control" name="enddate" value="{{ request()->input('enddate') ?? '' }}" required>
                                    </div>
                                    {{-- <select class="form-control data-lokasi" name="analisys" onchange='if(this.value != 0) { this.form.submit(); }'>
                                        <option value="1" {{ request()->analisys == 1 ? 'selected' : '' }}>Daily</option>
                                        <option value="2" {{ request()->analisys == 2 || empty(request()->analisys) ? 'selected' : '' }}>Monthly</option>
                                        <option value="3" {{ request()->analisys == 3 ? 'selected' : '' }}>Yearly</option>
                                    </select> --}}
                                </div>
                                {{-- @if (request()->analisys == 1)
                                    <div class="pb-2 col-sm">
                                        <div  style="padding-top: 5px; padding-bottom: 5px">
                                            <label class="form-check-label">Daily</label>
                                        </div>
                                        <input type="date" class="form-control" name="date-analisys" value="{{ request()->input('date-analisys') ?? '' }}" required>
                                    </div>
                                @elseif (request()->analisys == 3)
                                    <div class="pb-2 col-sm">
                                        <div  style="padding-top: 5px; padding-bottom: 5px">
                                            <label class="form-check-label">Yearly</label>
                                        </div>
                                        <select class="form-control data-lokasi" name="year-analisys" required>
                                            <option value="">Pilih...</option>
                                            @for ($i = 2021; $i <= date("Y"); $i++)
                                                <option value="{{ $i }}" {{ request()->input('year-analisys') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                @else
                                    <div class="pb-2 col-sm">
                                        <div  style="padding-top: 5px; padding-bottom: 5px">
                                            <label class="form-check-label">Monthly</label>
                                        </div>
                                        <input type="month" class="form-control" name="month-analisys" value="{{ request()->input('month-analisys') ?? '' }}" required>
                                    </div>
                                @endif --}}
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
                    <div class="col-sm-4">
                        <div id="chart"></div>
                    </div>
                    <div class="col-sm-3">
                        <div id="chart2"></div>
                    </div>
                    <div class="col-sm-5">
                        <div id="chart3"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- 1 CHART BARIS 1 BAGIAN KIRI --}}
        <script>
            var options = {
            chart: {
                height: {{ $h_ }},
                type: "line"
            },
            legend: {
                position: 'top'
            },
            series: [
                @php
                    $total_ac = 0;
                @endphp
                @foreach ( $datakategori as $kat_ac_1)
                    @php
                        $total_ac = $total_ac + $kat_ac_1->finalactivity($kat_ac_1->id, request()->lokasi, request()->dealer, request()->get('startdate'), request()->get('enddate'))->count();
                    @endphp
                    {
                        name: "{{ $kat_ac_1->nama_kategori }}",
                        type: "column",
                        data: [{{ $kat_ac_1->finalactivity($kat_ac_1->id, request()->lokasi, request()->dealer, request()->get('startdate'), request()->get('enddate'))->count() }}]
                    },
                @endforeach
            ],
            stroke: {
                width: 4,
                curve: 'smooth',
                colors: ['transparent']
            },
            title: {
                text: "Activity"
            },
            labels: ["Activity - {{ $total_ac }}"],
            dataLabels: {
            enabled: true,
                style: {
                    fontSize: '10px',
                    colors: ["#304758"]
                }
            },
            };

            var chart = new ApexCharts(document.querySelector("#chart"), options);

            chart.render();
        </script>

        {{-- 2 CHART BARIS 1 BAGIAN TENGAH--}}
        <script>
            var options = {
            chart: {
                height: {{ $h_ }},
                type: "bar",
                stacked: true,
            },
            legend: {
                position: 'top'
            },
            stroke: {
                width: 4,
                curve: 'smooth',
                colors: ['transparent']
            },
            series: [
                @php
                    $total_ab = 0;
                    $total_sb = 0;
                    $total_s  = 0;
                @endphp
                @foreach ( $datakategori as $kat_ac_2)
                    @php
                        $total_ab = $total_ab + $kat_ac_2->akanberjalan($kat_ac_2->id, request()->lokasi, request()->dealer, request()->get('startdate'), request()->get('enddate'))->count();
                        $total_sb = $total_sb + $kat_ac_2->sedangberjalan($kat_ac_2->id, request()->lokasi, request()->dealer, request()->get('startdate'), request()->get('enddate'))->count();
                        $total_s  = $total_s + $kat_ac_2->selesai($kat_ac_2->id, request()->lokasi, request()->dealer, request()->get('startdate'), request()->get('enddate'))->count();
                    @endphp

                        {
                            name: "{{ $kat_ac_2->nama_kategori }}",
                            type: "column",
                            data: [
                                    {{ $kat_ac_2->akanberjalan($kat_ac_2->id, request()->lokasi, request()->dealer, request()->get('startdate'), request()->get('enddate'))->count() }},
                                    {{ $kat_ac_2->sedangberjalan($kat_ac_2->id, request()->lokasi, request()->dealer, request()->get('startdate'), request()->get('enddate'))->count() }},
                                    {{ $kat_ac_2->selesai($kat_ac_2->id, request()->lokasi, request()->dealer, request()->get('startdate'), request()->get('enddate'))->count() }}
                            ]
                        },
                @endforeach
            ],
            title: {
                text: "Status Activity"
            },
            labels: ["Akan Berjalan - {{ $total_ab }}", "Sedang Berjalan - {{ $total_sb }}", "Selesai - {{ $total_s }}"],
            dataLabels: {
                enabled: true,
                style: {
                        fontSize: '10px',
                        colors: ["#222"]
                    }
            },
            xaxis: {
                labels: {
                    style: {
                        fontSize: '9px'
                    }
                }
            }
            };

            var chart = new ApexCharts(document.querySelector("#chart2"), options);

            chart.render();
        </script>

        {{-- 3 CHART BARIS 1 BAGIAN KANAN --}}
        @if (request()->lokasi && request()->lokasi != 'SEMUA')
        <script>
            var options = {
            chart: {
                height: {{ $h_ }},
                type: "bar",
                stacked: true,
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
                @foreach ($datakategori as $kat_ac_1)
                    {
                        name: "{{ $kat_ac_1->nama_kategori }}",
                        type: "column",
                        data: [
                            @foreach ($datadealer as $dealer_)
                                {{ $kat_ac_1->finalactivitydealer($kat_ac_1->id, $dealer_->id, request()->get('startdate'), request()->get('enddate'))->count() }},
                            @endforeach
                        ]
                    },
                @endforeach
            ],
            title: {
                text: "Activity Area"
            },
            dataLabels: {
            enabled: true,
                style: {
                    fontSize: '10px',
                    colors: ["#304758"]
                }
            },
            xaxis: {
                categories: [
                    @foreach ($datadealer as $dealer_)
                        @php
                            $total_ac_dealer = 0;
                        @endphp
                        @foreach ($datakategori as $kat_ac_1)
                            @php
                                $total_ac_dealer = $total_ac_dealer + $kat_ac_1->finalactivitydealer($kat_ac_1->id, $dealer_->id, request()->get('startdate'), request()->get('enddate'))->count();
                            @endphp
                        @endforeach
                        '{{ Str::title($dealer_->nama_dealer) }} - {{ $total_ac_dealer }}',
                    @endforeach
                ],
                labels: {
                    style: {
                        fontSize: '9px'
                    }
                }
            }
            };

            var chart = new ApexCharts(document.querySelector("#chart3"), options);

            chart.render();
        </script>
        @else
        <script>
            var options = {
            chart: {
                height: {{ $h_ }},
                type: "bar",
                stacked: true,
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
                @foreach ($datakategori as $kat_ac_1)
                    {
                        name: "{{ $kat_ac_1->nama_kategori }}",
                        type: "column",
                        data: [
                            @foreach ($datalokasikota as $kota)
                                {{ $kat_ac_1->finalactivitykota($kat_ac_1->id, $kota->kota_lokasi, request()->get('startdate'), request()->get('enddate'))->count() }},
                            @endforeach
                        ]
                    },
                @endforeach
            ],
            title: {
                text: "Activity Area"
            },
            dataLabels: {
            enabled: true,
                style: {
                    fontSize: '10px',
                    colors: ["#304758"]
                }
            },
            xaxis: {
                categories: [
                    @foreach ($datalokasikota as $kota)
                        @php
                            $total_ac_kota = 0;
                        @endphp
                        @foreach ($datakategori as $kat_ac_1)
                            @php
                                $total_ac_kota = $total_ac_kota + $kat_ac_1->finalactivitykota($kat_ac_1->id, $kota->kota_lokasi, request()->get('startdate'), request()->get('enddate'))->count();
                            @endphp
                        @endforeach
                        '{{ Str::title($kota->kota_lokasi) }} - {{ $total_ac_kota }}',
                    @endforeach
                ],
                labels: {
                    style: {
                        fontSize: '9px'
                    }
                }
            }
            };

            var chart = new ApexCharts(document.querySelector("#chart3"), options);

            chart.render();
        </script>
        @endif


       
    @else

    @endif


@endsection
