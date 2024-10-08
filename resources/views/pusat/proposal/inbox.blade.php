@extends('pusat.layouts.pusat')

@section('title', 'Inbox Proposal')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">

                <div class="row">
                    <div class="col-12">
                        <div class="float-left">
                            <h5>Data Proposal</h5>
                        </div>
                        <div class="float-right">
                            <a href="{{ route('pusat.proposal.getPilihJenisProposal') }}" class="btn btn-success">Buat Proposal <i class="fas fa-plus"></i></a>
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
                                        {{-- <option value="1" {{ request()->status == 1 ? 'selected' : ''}}>Draft</option> --}}
                                        <option value="2" {{ request()->status == 2 ? 'selected' : ''}}>Waiting Approval</option>
                                        <option value="3" {{ request()->status == 3 ? 'selected' : ''}}>Partial Approval</option>
                                        <option value="4" {{ request()->status == 4 ? 'selected' : ''}}>Final</option>
                                        {{-- <option value="5" {{ request()->status == 5 ? 'selected' : ''}}>Revise</option>
                                        <option value="6" {{ request()->status == 6 ? 'selected' : ''}}>Rejected</option> --}}
                                    </select>
                                </div>
                                <div class="pb-2 col">
                                    <div  style="padding-top: 5px; padding-bottom: 5px">
                                        <label class="form-check-label">Kategori</label>
                                    </div>
                                    <select class="form-control" name="kategori">
                                        <option value="SEMUA">Semua</option>
                                        @foreach ($datakategori as $data_k)
                                        <option value="{{ $data_k->id }}" {{ request()->kategori == $data_k->id ? 'selected' : ''}}>{{ $data_k->nama_kategori }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="pb-2 col">
                                    <div  style="padding-top: 5px; padding-bottom: 5px">
                                        <label class="form-check-label" style="font-size: 10px">Penanggung Jawab</label>
                                    </div>
                                    <input class="form-control" type="text" value="{{ request()->namapj ?? '' }}" name="namapj" style="font-size: small" />
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
                        <div class="col p-0">Lokasi</div>
                        <div class="col p-0">Penanggung Jawab</div>
                        <div class="col p-0">Dealer</div>
                        <div class="col p-0">Submit Date</div>
                        <div class="col p-0"></div>
                    </div>
                </div>

                @php
                $first  = 0;
                $end    = 0;
                @endphp

                @foreach($datas as $key => $data)
                    <div style="text-decoration: none;">
                        <div class="card mb-2" style="border-radius: 5px; font-size: 12px">
                            <div class="row p-2 align-items-center">
                                <div class="col p-0 pl-3">
                                    <div class="row">
                                        <div class="pl-3" style="">
                                            <div class="font-weight-bold">
                                                <span class="btn btn-sm btn-{{ $data->statusp->warna_status ?? ''}} ms-auto">
                                                    {{ $data->statusp->nama_status ?? ''}}
                                                </span>
                                                @if ($data->status_proposal == 4)
                                                    @php
                                                        $a = \Carbon\Carbon::create($data->periode_start_proposal);
                                                        $b = \Carbon\Carbon::create($data->periode_end_proposal);
                                                        $c = \Carbon\Carbon::now()->addDays(1);
                                                    @endphp
                                                    @if ($a->greaterThan($c) == true)
                                                        <span class="btn btn-sm btn-outline-dark ms-auto">
                                                            Akan Berjalan
                                                        </span>
                                                    @else
                                                        @if ($b->greaterThan($c) == true)
                                                            <span class="btn btn-sm btn-outline-primary ms-auto">
                                                                Sedang Berjalan
                                                            </span>
                                                        @else
                                                            <span class="btn btn-sm btn-outline-info ms-auto">
                                                                Selesai
                                                            </span>
                                                        @endif
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col p-0">
                                    <div class="text-muted" style="color: #ec1b25">
                                        {{ $data->periode_start_proposal ? \Carbon\Carbon::createFromFormat('Y-m-d', $data->periode_start_proposal)->format('d-m-Y') : '' }}
                                    </div>
                                </div>
                                <div class="col p-0">
                                    <div class="text-muted" style="color: #ec1b25">
                                        {{ $data->periode_end_proposal ? \Carbon\Carbon::createFromFormat('Y-m-d', $data->periode_end_proposal)->format('d-m-Y') : '' }}
                                    </div>
                                </div>
                                <div class="col p-0">
                                    <div class="font-weight-bold" style="color: #ec1b25">
                                        {{ $data->kategori->nama_kategori ?? '-' }}
                                    </div>
                                </div>
                                <div class="col p-0">
                                    <div class="font-weight-bold" style="color: #222222">
                                        {{ $data->lokasi->kelurahan_lokasi ?? '' }}, {{ $data->lokasi->kecamatan_lokasi ?? '' }}
                                    </div>
                                </div>
                                <div class="col p-0">
                                    <div class="font-weight-bold" style="color: #222222">
                                        {{ $data->pj->nama_sales_people ?? '' }}
                                    </div>
                                </div>
                                <div class="col p-0">
                                    <div class="font-weight-bold" style="color: #222222">
                                        {{ $data->dealer->nama_dealer ?? '' }}
                                    </div>
                                </div>
                                <div class="col p-0 ">
                                    <div class="text-muted" style="font-size: 10px">
                                        {{ $data->submit_date }}
                                    </div>
                                </div>
                                <div class="col p-0 text-right pr-4">
                                    <div class="btn-group dropleft">
                                        @if ($data->status_proposal == 1)
                                            <a href="{{ route('pusat.proposal.getCreate') }}?id={{ $data->uuid }}" class="btn btn-sm btn-outline-warning">
                                                <i class="cil-pencil"></i> Edit
                                            </a>
                                            <a  class="btn btn-sm btn-outline-danger" href="{{ route('pusat.proposal.index') }}?metode=hapus&id={{ $data->id }}" onclick="return confirm('Konfirmasi Hapus Proposal')">
                                                <i class="cil-trash"></i> Hapus
                                            </a>
                                        @else
                                            <a href="{{ route('pusat.proposal.getShow') }}?id={{ $data->uuid }}" class="btn btn-sm btn-outline-dark">
                                                <i class="cil-search"></i> Lihat
                                            </a>
                                        @endif
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
    $('.data-dealer').select2();
</script>
<script type="text/javascript">
    $(document).ready(function(){
          $("#kategori").val("{!! request()->kategori !!}");
          $("#lokasi").val("{!! request()->lokasi !!}");
          $("#status").val("{!! request()->status !!}");
    });
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
