@extends('pusat.layouts.pusat')

@section('title', 'LPJ')
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <div class="float-left">
                            <h5>Data LPJ</h5>
                        </div>
                        <div class="float-right">
                            {{-- <a href="#" class="btn btn-success">Buat Proposal </a> --}}
                        </div>
                    </div>
                </div>
                <hr>
                <div>
                    <form class="row" action="{{ url()->current() }}">
                        <div class="col-12">
                            <div class="row">
                                <div class="pb-2 col">
                                    <div  style="padding-top: 5px; padding-bottom: 5px">
                                        <label class="form-check-label">Status</label>
                                    </div>
                                    <select id="status" name="status" class="form-control">
                                        <option value="">Semua</option>
                                        <option value="1" {{ request()->status == 1 ? 'selected' : ''}}>Draft</option>
                                        <option value="2" {{ request()->status == 2 ? 'selected' : ''}}>Submit</option>
                                    </select>
                                </div>
                                <div class="pb-2 col">
                                    <div  style="padding-top: 5px; padding-bottom: 5px">
                                        <label class="form-check-label">Kategori Proposal</label>
                                    </div>
                                    <select class="form-control" name="kategori" id="kategori">
                                        <option value="SEMUA">Semua</option>
                                        @foreach ($datakategori as $data_k)
                                            <option value="{{ $data_k->id }}" {{ request()->kategori == $data_k->id ? 'selected' : '' }}>{{ $data_k->nama_kategori }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="pb-2 col">
                                    <div  style="padding-top: 5px; padding-bottom: 5px">
                                        <label class="form-check-label">Area</label>
                                    </div>
                                    <select name="area" class="data-area form-control">
                                        <option value="SEMUA">Semua</option>
                                        @foreach ($dataarea as $data_are)
                                        <option value="{{ $data_are->kota_dealer }}" {{ request()->area == $data_are->kota_dealer ? 'selected' : '' }}>
                                            {{ Str::title($data_are->kota_dealer) }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="pb-2 col">
                                    <div  style="padding-top: 5px; padding-bottom: 5px">
                                        <label class="form-check-label">Lokasi</label>
                                    </div>
                                    <select class="form-control data-lokasi" name="lokasi">
                                        <option value="SEMUA">Semua</option>
                                        @foreach ($datalokasi as $data_l)
                                        <option value="{{ $data_l->id }}" {{ request()->lokasi == $data_l->id ? 'selected' : '' }}>
                                            {{ $data_l->kelurahan_lokasi }}, {{ $data_l->kecamatan_lokasi }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="pb-2 col">
                                    <div  style="padding-top: 5px; padding-bottom: 5px">
                                        <label class="form-check-label">Dealer</label>
                                    </div>
                                    <select name="dealer" class="data-dealer form-control">
                                        <option value=""></option>
                                        @foreach ($datadealer as $data_d)
                                        <option value="{{ $data_d->id }}" {{ request()->dealer == $data_d->id ? 'selected' : '' }}>
                                            {{ $data_d->nama_dealer }}, {{ Str::title($data_d->kota_dealer) }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="p-2 pl-3 col-2-sm">
                                    <div  style="padding-top: 5px; padding-bottom: 5px">
                                        <label class="form-check-label">Filter Waktu</label>
                                    </div>
                                    <select class="form-control" name="type"  onchange='if(this.value != 0) { this.form.submit(); }'>
                                        <option value="by-date" {{ request()->input('type') == "by-date" ? 'selected' : ''}}>By Date</option>
                                        <option value="by-month" {{ request()->input('type') == "by-month" ? 'selected' : ''}}>By Month</option>
                                    </select>
                                </div>
                                @if (request()->type == 'by-month')
                                    <div class="p-2 col-3-sm">
                                        <div  style="padding-top: 5px; padding-bottom: 5px">
                                            <label class="form-check-label">Bulan</label>
                                        </div>
                                        <select class="form-control" name="bulan">
                                            <option value="SEMUA">SEMUA</option>
                                            @php
                                            $NamaBulan = array('1'=>'Januari', '2'=>'Februari', '3'=>'Maret', '4'=>'April', '5'=>'Mei', '6'=>'Juni', '7'=>'Juli', '8'=>'Agustus', '9'=>'September', '10'=>'Oktober', '11'=>'November', '12'=>'Desember');
                                            @endphp
                                            @for ($j = 1; $j <= 12 ; $j++)
                                            <option value="{{ $j }}" {{ request()->input('bulan') == $j ? 'selected' : ''}}>{{ $NamaBulan[$j] }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="p-2 col-3-sm">
                                        <div  style="padding-top: 5px; padding-bottom: 5px">
                                            <label class="form-check-label">Tahun</label>
                                        </div>
                                        <select class="form-control" name="tahun">
                                            <option value="SEMUA">SEMUA</option>
                                            @for ($i = date("Y"); $i >= 2021 ; $i--)
                                            <option value="{{ $i }}" {{ request()->input('tahun') == $i ? 'selected' : ($i == date("Y") ? 'selected' : '')  }}>{{ $i }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                @else
                                    <div class="p-2 col-3-sm">
                                        <div  style="padding-top: 5px; padding-bottom: 5px">
                                            <label class="form-check-label">Start Date</label>
                                        </div>
                                        <input type="date" class="form-control" name="tanggal_mulai">
                                    </div>
                                    <div class="p-2 col-3-sm">
                                        <div  style="padding-top: 5px; padding-bottom: 5px">
                                            <label class="form-check-label">End Date</label>
                                        </div>
                                        <input type="date" class="form-control" name="tanggal_akhir">
                                    </div>
                                @endif
                                <div class="col"></div>
                                <div class="p-2 pr-3 col-3">
                                    <div  style="padding-top: 5px; padding-bottom: 5px">
                                        <label class="form-check-label">Submit Date</label>
                                    </div>
                                    <input class="form-control" type="text" value="{{ request()->input('tanggal-submit') ?? '' }}" name="tanggal_submit" style="font-size: small" />
                                    <input type="hidden" name="tanggal_submit_start">
                                    <input type="hidden" name="tanggal_submit_end">
                                </div>
                            </div>
                        </div>
                        <div class="col-12 mt-2">
                            <div class="row">
                                <div class="col-md-8">
                                </div>
                                <div class="col-md-4 text-right" style="padding-bottom:20px;">
                                    <div class="align-middle">
                                        <button type="submit" class="btn btn-primary btn-sm btn-block" > <i class="fas fa-search"></i> Cari</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="card mb-2 bg-gray-500 text-white" style="border-radius: 5px; font-size: 12px;">
                    <div class="row p-2 font-weight-bold">
                        <div class="col p-0 pl-3">Status</div>
                        <div class="col p-0">Start Date</div>
                        <div class="col p-0">End Date</div>
                        <div class="col p-0">Proposal</div>
                        <div class="col p-0">Dealer</div>
                        <div class="col p-0">Database</div>
                        <div class="col p-0">Prospecting</div>
                        <div class="col p-0">Penjualan</div>
                        <div class="col p-0">Biaya</div>
                        <div class="col p-0">Submit Date</div>
                        <div class="col p-0"></div>
                    </div>
                </div>

                @php
                $first  = 0;
                $end    = 0;
                @endphp

                @foreach($datas as $key => $data)
                    <div href="#" style="text-decoration: none;">
                        <div class="card mb-2" style="border-radius: 5px; font-size: 12px">
                            <div class="row p-2 align-items-center">
                                <div class="col p-0 pl-3">
                                    <div class="row">
                                        <div class="pl-3" style="">
                                            <div class="font-weight-bold">
                                                @if ($data->status_lpj == 1)
                                                    <span class="btn btn-sm btn-outline-info ms-auto">
                                                        DRAFT
                                                    </span>
                                                @elseif ($data->status_lpj == 2)
                                                    <span class="btn btn-sm btn-success ms-auto">
                                                        SUBMIT
                                                    </span>
                                                @endif

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col p-0 pl-3">
                                    {{ $data->periode_start_lpj ? \Carbon\Carbon::createFromFormat('Y-m-d', $data->periode_start_lpj)->format('d-m-Y') : '' }}
                                </div>
                                <div class="col p-0 pl-3">
                                    {{ $data->periode_end_lpj ? \Carbon\Carbon::createFromFormat('Y-m-d', $data->periode_end_lpj)->format('d-m-Y') : ''  }}
                                </div>
                                <div class="col p-0 pl-3">
                                    <div class="font-weight-bold" style="color: #ec1b25">
                                        {{ $data->proposal->kategori->nama_kategori ?? '-' }}
                                    </div>
                                </div>
                                <div class="col p-0 pl-3">
                                    <div class="font-weight-bold" style="color: #222222">
                                        {{ $data->proposal->dealer->nama_dealer ?? '' }}
                                    </div>
                                </div>
                                <div class="col p-0 pl-3">
                                    {{ $data->target_database_lpj ?? '' }}
                                </div>
                                <div class="col p-0 pl-3">
                                    {{ $data->target_prospectus_lpj ?? '' }}
                                </div>
                                <div class="col p-0 pl-3">
                                    {{ $data->target_penjualan_lpj ?? '' }}
                                </div>
                                <div class="col p-0 pl-3">
                                    Rp. {{ number_format($data->total_dana_lpj,0,',',','); }}
                                </div>
                                <div class="col p-0">
                                    <div class="text-muted" style="font-size: 10px">
                                        {{ $data->updated_at }}
                                    </div>
                                </div>
                                <div class="col text-right pr-4">
                                    <div class="btn-group dropleft">
                                        <a href="{{ route('pusat.lpj.getShow') }}?id={{ $data->uuid }}" class="btn btn-sm btn-outline-dark">
                                            <i class="cil-search"></i> Lihat
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @php
                        $first  = $datas->firstItem();
                        $end    = $data->id;
                        @endphp
                    @endforeach
                <div class="row">
                    <div class="col-7">
                        <div class="float-left">
                            {!! $first !!} - {!! $end !!} From {!! $datas->total() !!} Data
                        </div>
                    </div><!--col-->

                    <div class="col-5">
                        <div class="float-right">
                            {!! $datas->appends(request()->query())->links() !!}
                        </div>
                    </div><!--col-->
                </div><!--row-->
            </div>
        </div>
    </div><!--col-md-10-->
</div><!--row-->
<script type="text/javascript">
    $('.data-lokasi').select2();
</script>
<script type="text/javascript">
    $(document).ready(function(){
        $('input[name="tanggal_submit"]').daterangepicker({
            autoUpdateInput: false,
            locale: {
                cancelLabel: 'Clear'
            }
        });

        $('input[name="tanggal_submit"]').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));
        });

        $('input[name="tanggal_submit"]').on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
        });

        $('input[name="tanggal_submit"]').on('apply.daterangepicker', function(ev, picker) {
            $('input[name="tanggal_submit_start"]').val(picker.startDate.format('YYYY-MM-DD'));
            $('input[name="tanggal_submit_end"]').val(picker.endDate.format('YYYY-MM-DD'));
        });
    });
</script>

@endsection
