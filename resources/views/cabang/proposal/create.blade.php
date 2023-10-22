@extends('cabang.layouts.cabang')

@section('title', 'Buat Proposal')
@section('content')
{{-- <link rel="stylesheet"  type="text/css" href="/filepond/app.css"/> --}}
{{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/filepond/4.30.4/filepond.css" integrity="sha512-OwkTbucz29JjQUeii4ZRkjY/E+Xdg4AfffPZICCf98rYKWIHxX87AwwuIQ73rbVrev8goqrKmaXyu+VxyDqr1A==" crossorigin="anonymous" referrerpolicy="no-referrer" /> --}}
<link href="https://unpkg.com/filepond/dist/filepond.css" rel="stylesheet" />
<link
href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css"
rel="stylesheet"
/>

    @if ($data->status_proposal == 1 || $data->status_proposal == 5)
    <style>
        .form-control:disabled, .form-control[readonly] {
            background-color: #ffffff;
            opacity: 1;
        }
    </style>
    @else
    <style>
        .form-control {
            pointer-events: none;
            background-color: #efefef;

        }
    </style>
    @endif
    <script>
        $("input.number-only").bind({
            keydown: function(e) {
                if (e.shiftKey === true ) {
                    if (e.which == 9) {
                        return true;
                    }
                    return false;
                }
                if (e.which > 57) {
                    return false;
                }
                if (e.which==32) {
                    return false;
                }
                return true;
            }
        });
    </script>
    <div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    @if ($data->status_proposal == 1 || $data->status_proposal == 5)
                    <form id="formproposal" class="card-body" action="{{ route('cabang.proposal.postStore') }}" method="POST">
                        @csrf
                        <input type="hidden" name="uuid" value="{{ $data->uuid }}">
                    @else
                    <div class="card-body">
                    @endif
                        <div class="row">
                            <div class="col-12">
                                <div class="float-left">
                                    <h5>Form Pengajuan Pameran </h5>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="mb-2 row">
                            <label class="col-sm-2 col-form-label">
                                <strong>Dealer</strong>
                            </label>
                            <label class="col-sm-10 col-form-label">
                                @php
                                    $dealer = DB::table('dealers')->where('id', Auth::guard('cabang')->user()->dealer)->first();
                                @endphp
                                {{ $dealer->nama_dealer }}
                            </label>
                        </div>
                        <div class="mb-2 row">
                            <label class="col-sm-2 col-form-label">
                                <strong>Jenis Proposal</strong>
                            </label>
                            <div class="col-sm-10 col-form-label">
                                <strong>{{ $data->kategori->nama_kategori }}</strong>
                                <hr>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-2 col-form-label">
                                <strong>Latar Belakang</strong>
                            </div>
                            <div class="col-sm-10">
                                <div class="mb-2 row">
                                    <label class="col-sm-2 col-form-label">Latar Belakang Honda<strong style="color:rgb(243, 0, 0)">*</strong></label>
                                    <div class="col-sm-10">
                                        <textarea class="form-control" name="latarbelakang" id="" rows="2">{{ $data->latar_belakang_proposal }}</textarea>
                                    </div>
                                </div>
                                <div class="mb-2 row">
                                    <label class="col-sm-2 col-form-label">Latar Belakang Kompetitor<strong style="color:rgb(243, 0, 0)">*</strong></label>
                                    <div class="col-sm-10">
                                        <textarea class="form-control" name="latarkompetitor" id="" rows="2">{{ $data->latar_kompetitor_proposal }}</textarea>
                                    </div>
                                </div>
                                <div class="mb-2 row">
                                    <label class="col-sm-2 col-form-label">Kondisi Penjualan<strong style="color:rgb(243, 0, 0)">*</strong></label>
                                    <div class="col-sm-6">
                                        <table class="table table-striped border datatable table-sm">
                                            <thead class="text-center">
                                                <tr>
                                                    <th>Sales M -1</th>
                                                    <th>Sales M 1</th>
                                                </tr>
                                                <tr>
                                                    <th>H</th>
                                                    <th>H</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <input class="form-control" type="number" name="mmin1" value="{{ $data->kondisi_penjualan_m_1_proposal }}">
                                                    </td>
                                                    <td>
                                                        <input class="form-control" type="number" name="m1" value="{{ $data->kondisi_penjualan_m1_proposal }}">
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="mb-2 row">
                                    <label class="col-sm-2 col-form-label">
                                        Tujuan / Benefit<strong style="color:rgb(243, 0, 0)">*</strong>
                                    </label>
                                    <div class="col-sm-10">
                                        <textarea class="form-control" name="tujuan" id="" rows="2">{{ $data->tujuan_proposal }}</textarea>
                                    </div>
                                </div>
                                <hr>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-2 col-form-label">
                                <strong>Konsep / Kegiatan Acara</strong>
                            </div>
                            <div class="col-sm-10">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="mb-2 row">
                                            <label class="col-sm-3 col-form-label">Tempat<strong style="color:rgb(243, 0, 0)">*</strong></label>
                                            <div class="col-sm-9">
                                                <input class="form-control" type="text"  name="tempat" value="{{ $data->tempat_proposal }}">
                                            </div>
                                        </div>
                                        <div class="mb-2 row">
                                            <label class="col-sm-3 col-form-label">Lokasi<strong style="color:rgb(243, 0, 0)">*</strong></label>
                                            <div class="col-sm-9">
                                                <select class="form-control data-lokasi" name="lokasi">
                                                    @if (!null == $data->lokasi_proposal)
                                                        <option value="{{ $data->lokasi_proposal }}" selected>{{ $data->lokasi->kelurahan_lokasi }}, {{ $data->lokasi->kecamatan_lokasi }}, {{ Str::title($data->lokasi->kota_lokasi) }}</option>
                                                    @endif
                                                    @foreach ($datalokasi as $data_l)
                                                        <option value="{{ $data_l->id }}">{{ $data_l->kelurahan_lokasi }}, {{ $data_l->kecamatan_lokasi }}, {{ Str::title($data_l->kota_lokasi) }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="mb-2 row">
                                            <label class="col-sm-3 col-form-label">Finance Company<strong style="color:rgb(243, 0, 0)">*</strong></label>
                                            <div class="col-sm-9">
                                                <select class="form-control data-finance" name="finance[]" multiple="multiple">
                                                    @if ($data->finance_proposal)
                                                        @foreach (json_decode($data->finance_proposal) as $key => $data_dis)
                                                            @php
                                                                $finance_ = DB::table('finance_companies')->where('id', $data_dis)->first();
                                                            @endphp
                                                            <option value="{{ $data_dis }}" selected>{{ $finance_->kode }}</option>
                                                        @endforeach
                                                    @endif
                                                    @foreach ($datafinance as $data_fin)
                                                        <option value="{{ $data_fin->id }}">{{ $data_fin->kode }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="mb-2 row">
                                            <label class="col-sm-4 col-form-label">History Penjualan<strong style="color:rgb(243, 0, 0)">*</strong></label>
                                            <div class="col-sm-8">
                                                <div class="input-group">
                                                    <input class="form-control" type="number" name="historypenjualan" value="{{ $data->history_penjualan_proposal }}">
                                                    {{-- <span class="input-group-text">Unit</span> --}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="mb-2 row">
                                            <label class="col-sm-5 col-form-label">Target Database<strong style="color:rgb(243, 0, 0)">*</strong></label>
                                            <div class="col-sm-7">
                                                <input class="form-control" type="number" name="targetdata" value="{{ $data->target_database_proposal }}">
                                            </div>
                                        </div>
                                        <div class="mb-2 row">
                                            <label class="col-sm-5 col-form-label">Target Penjualan<strong style="color:rgb(243, 0, 0)">*</strong></label>
                                            <div class="col-sm-7">
                                                <input class="form-control" type="number" name="targetjual" value="{{ $data->target_penjualan_proposal }}">
                                            </div>
                                        </div>
                                        <div class="mb-2 row">
                                            <label class="col-sm-5 col-form-label">Target Prospecting<strong style="color:rgb(243, 0, 0)">*</strong></label>
                                            <div class="col-sm-7">
                                                <input class="form-control" type="number" name="targetpros" value="{{ $data->target_prospectus_proposal }}">
                                            </div>
                                        </div>
                                        <div class="mb-2 row">
                                            <label class="col-sm-5 col-form-label">Target Downloader Motorku-X<strong style="color:rgb(243, 0, 0)">*</strong></label>
                                            <div class="col-sm-7">
                                                <input class="form-control" type="number" name="targetdown" value="{{ $data->target_downloader_proposal }}">
                                            </div>
                                        </div>

                                        <div class="mb-2 row">
                                            <label class="col-sm-5 col-form-label">Program Penjualan<strong style="color:rgb(243, 0, 0)">*</strong></label>
                                            <div class="col-sm-7">
                                                <textarea class="form-control" id="" rows="2" name="program">{{ $data->program_proposal }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="row pb-3">
                                            <div class="col-sm-6">
                                                <div class="mb-2 row">
                                                    <label class="col-sm-3 col-form-label">Start<strong style="color:rgb(243, 0, 0)">*</strong></label>
                                                    <div class="col-sm-9">
                                                        <input class="form-control" id="tanggalstart" onchange="tanggalevent()" name="tanggalstart" type="date" value="{{ $data->periode_start_proposal }}" min="{{ date('Y') }}-{{ date('m') }}-01">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-2 row">
                                                    <label class="col-sm-3 col-form-label">End<strong style="color:rgb(243, 0, 0)">*</strong></label>
                                                    <div class="col-sm-9">
                                                        <input class="form-control" id="tanggalend" name="tanggalend" type="date" value="{{ $data->periode_end_proposal }}" min="{{ date('Y') }}-{{ date('m') }}-01">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            {{-- <div class="col-md-6 pb-4">
                                                <div class="row">
                                                    <label class="col-sm-12 col-form-label text-center pb-3 font-weight-bold">Unit Entry</label>
                                                    <div class="col-sm-12">
                                                        <div class="row pb-2">
                                                            <div class="col-4"></div>
                                                            <div class="col-md-4">
                                                                <select class="form-control" id="dataunitentry">
                                                                    @foreach ($data->unitentry() as $data_u)
                                                                        <option value="{{ data_get($data_u, 'id') }}-{{ data_get($data_u, 'nama') }}">{{ data_get($data_u, 'nama') }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <a class="btn btn-outline-success" onclick="tambahunitentry(); return false;" href="#">
                                                                    Pilih Unit Entry <i class="fas fa-plus"></i>
                                                                </a>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <table class="table table-striped border datatable table-sm" id="unitentrydata">
                                                                    <thead class="text-center">
                                                                        <tr>
                                                                            <th>Nama</th>
                                                                            <th>Jumlah</th>
                                                                            <th></th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @if ($data->unit_entry_proposal)
                                                                            @foreach ($dataunitentry as $data_ue_)
                                                                                <tr>
                                                                                    <td>
                                                                                        <input type="hidden" name="idunitentry[]" value="{{ data_get($data_ue_, 'idunitentry') }}">
                                                                                        <input class="form-control unitentry_nama" type="text" name="unitentry_nama[]" value="{{ $data->unitentry_(data_get($data_ue_, 'idunitentry')) }}" readonly>
                                                                                    </td>
                                                                                    <td>
                                                                                        <input class="form-control unitentry" type="text" name="unitentry[]" value="{{ data_get($data_ue_, 'unitentry') }}">
                                                                                    </td>
                                                                                    <td>
                                                                                        <a href="javascript:void(0);" class="removeunitentry btn btn-danger"><i class="fas fa-minus-circle"></i>
                                                                                        </a>
                                                                                    </td>
                                                                                </tr>
                                                                            @endforeach
                                                                        @endif
                                                                    </tbody>
                                                                </table>
                                                                <table class="float-right">
                                                                    <tbody>
                                                                        <tr>
                                                                            <td>
                                                                                <label class="col-sm-12 col-form-label">Total</label>
                                                                            </td>
                                                                            <td>
                                                                                <input class="form-control text-center" type="text" name="totaluniten" id="totaluniten" value="{{ $data->total_unit_entry_proposal ?? 0 }}" readonly >
                                                                            </td>
                                                                            <td>
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div> --}}
                                            
                                            {{-- <div class="col-md-6">
                                                <div class="row">
                                                    <label class="col-sm-12 col-form-label text-center pb-3 font-weight-bold">Revenue</label>
                                                    <div class="col-sm-12">
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <table class="table border datatable table-sm" id="revenuedata">
                                                                    <thead class="text-center">
                                                                        <tr>
                                                                            <th>Nama</th>
                                                                            <th>Jumlah</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody style="background-color: #f2f2f3">
                                                                        <tr>
                                                                            <td>
                                                                                <label class="col-sm-12 col-form-label">Jasa</label>
                                                                            </td>
                                                                            <td>
                                                                                <div class="input-group">
                                                                                    <div class="input-group-prepend">
                                                                                        <span class="input-group-text" id="basic-addon1">Rp. </span>
                                                                                    </div>
                                                                                    <input oninput="totalrevenue()" class="form-control revenue" type="number" name="jasa" pattern="[0-9]+" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"
                                                                                            value="{{ $data->additional->jasa ?? 0 }}" id="jasa">
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>
                                                                                <label class="col-sm-12 col-form-label">Part</label>
                                                                            </td>
                                                                            <td>
                                                                                <div class="input-group">
                                                                                    <div class="input-group-prepend">
                                                                                        <span class="input-group-text" id="basic-addon1">Rp. </span>
                                                                                    </div>
                                                                                    <input oninput="totalrevenue()" class="form-control revenue" type="number" name="part" pattern="[0-9]+" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"
                                                                                            value="{{ $data->additional->part ?? 0 }}" id="part">
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>
                                                                                <label class="col-sm-12 col-form-label">Oli</label>
                                                                            </td>
                                                                            <td>
                                                                                <div class="input-group">
                                                                                    <div class="input-group-prepend">
                                                                                        <span class="input-group-text" id="basic-addon1">Rp. </span>
                                                                                    </div>
                                                                                    <input oninput="totalrevenue()" class="form-control revenue" type="number" name="oli" pattern="[0-9]+" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"
                                                                                            value="{{ $data->additional->oli ?? 0 }}" id="oli">
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                    <tbody>
                                                                        <tr>
                                                                            <td>
                                                                                <label class="col-sm-12 col-form-label">Total</label>
                                                                            </td>
                                                                            <td>
                                                                                <div class="input-group">
                                                                                    <div class="input-group-prepend">
                                                                                        <span class="input-group-text" id="basic-addon1">Rp. </span>
                                                                                    </div>
                                                                                    <input readonly class="form-control revenue" type="text" name="totalrev" value="{{ $data->total_revenue_proposal ?? 0 }}" id="totalrev">
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div> --}}
                                            <input type="hidden" id="idkategori" value="{{$data->kategori->id}}">
                                            @if($data->kategori->id == 5 )
                                                <div class="col-md-6">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <h4>Unit Entry :</h4>
                                                            <hr>
                                                            <div id="unitentryForm">
                                                            
                                                                <table class="table table-bordered">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Nama</th>
                                                                            <th>Jumlah</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <tr>
                                                                            <td>KPB 1</td>
                                                                            <td><input class="form-control" type="number" name="kpb1" value="0"></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>KPB 2</td>
                                                                            <td><input class="form-control" type="number" name="kpb2" value="0"></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>KPB 3</td>
                                                                            <td><input class="form-control" type="number" name="kpb3" value="0"></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>KPB 4</td>
                                                                            <td><input class="form-control" type="number" name="kpb4" value="0"></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>PSL</td>
                                                                            <td><input class="form-control" type="number" name="psl" value="0"></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>PSR</td>
                                                                            <td><input class="form-control" type="number" name="psr" value="0"></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>GO+</td>
                                                                            <td><input class="form-control" type="number" name="go" value="0"></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>LR</td>
                                                                            <td><input class="form-control" type="number" name="lr" value="0"></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td><b>Total</b></td>
                                                                            <td><input class="form-control" type="number" name="unittotal" value="0" readonly></td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                                <div>
                                                                    <button class="btn btn-xs btn-success" type="button" id="simpanunitentry" style="margin-top:10px">Simpan</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <h4>Revenue :</h4>
                                                            <hr>
                                                            <div id="revenueForm">
                                                                <div>
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <label for="jasa">Jasa:</label>
                                                                            <div class="input-group mb-3">
                                                                                <div class="input-group-prepend">
                                                                                    <span class="input-group-text">Rp.</span>
                                                                                </div>
                                                                                <input class="form-control" type="number" min="0" id="jasa" name="jasa" />
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div style="margin-top: 5px;">
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <label for="part">Part:</label>
                                                                            <div class="input-group mb-3">
                                                                                <div class="input-group-prepend">
                                                                                    <span class="input-group-text">Rp.</span>
                                                                                </div>
                                                                                <input class="form-control" type="number" min="0" id="part" name="part" />
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div style="margin-top: 5px;">
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <label for="oli">Oli:</label>
                                                                            <div class="input-group mb-3">
                                                                                <div class="input-group-prepend">
                                                                                    <span class="input-group-text">Rp.</span>
                                                                                </div>
                                                                                <input class="form-control" type="number" min="0" id="oli" name="oli" />
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div style="margin-top: 5px;">
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <label for="total">Total:</label>
                                                                            <div class="input-group mb-3">
                                                                                <div class="input-group-prepend">
                                                                                    <span class="input-group-text">Rp.</span>
                                                                                </div>
                                                                                <input class="form-control" type="number" min="0" id="total" name="total" readonly />
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div>
                                                                    <button class="btn btn-xs btn-success" type="button" id="simpanrevenue" style="margin-top:10px">Simpan</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>

                                        <hr>
                                        <div class="row">
                                            <label class="col-sm-2 col-form-label">Display<strong style="color:rgb(243, 0, 0)">*</strong></label>

                                            <div class="col-sm-10">
                                                <div class="row pb-2">
                                                    <div class="col-4">
                                                        <select class="form-control data-display-unit" name="display[]" id="datadisplayunit">
                                                            @foreach ($datadisplay as $data_dis)
                                                                <option value="{{ $data_dis->id }}-{{ $data_dis->nama_display }}">{{ $data_dis->nama_display }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-4">
                                                        <a class="btn btn-outline-success" onclick="tambahdisplayunit(); return false;" href="#">
                                                            Pilih Display / Produk <i class="fas fa-plus"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-12">
                                                        <table class="table table-striped border datatable table-sm" id="displayunitdata">
                                                            <thead class="text-center">
                                                                <tr>
                                                                    <th>Nama</th>
                                                                    <th>Jumlah Unit</th>
                                                                    <th></th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @if ($data->display_proposal)
                                                                    @foreach ($datadisplayunit as $data_diu)
                                                                        @php
                                                                            $diu = DB::table('displays')->where('id', data_get($data_diu, 'iddisplayunit'))->first();
                                                                        @endphp
                                                                        <tr>
                                                                            <td>
                                                                                <input type="hidden" name="iddisplayunit[]" value="{{ $diu->id }}">
                                                                                <input class="form-control displayunit_nama" type="text" name="displayunit_nama[]" value="{{ $diu->nama_display }}" readonly>
                                                                            </td>
                                                                            <td>
                                                                                <input class="form-control displayunit text-center" type="text" name="displayunit[]" value="{{ data_get($data_diu, 'displayunit') }}">
                                                                            </td>
                                                                            <td>
                                                                                <a href="javascript:void(0);" class="removedisplayunit btn btn-danger"><i class="fas fa-minus-circle"></i>
                                                                                </a>
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach
                                                                @endif
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                            </div>
                        </div>
                        <div class="mb-2 row">
                            <label class="col-sm-2 col-form-label">
                                <strong>Perkiraan Dana<strong style="color:rgb(243, 0, 0)">*</strong></strong>
                            </label>
                            <div class="col-sm-10">
                                <table class="table table-striped border datatable table-sm" id="dataDana">
                                    <thead class="text-center">
                                        <tr>
                                            <th width="300">Keterangan</th>
                                            <th>Beban Dealer</th>
                                            <th>Beban Fincoy</th>
                                            <th>Beban MD(Jika Ada)</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (!null == $datadana)
                                            @foreach ($datadana as $key => $data_dana)
                                                <tr class="row_to_clone_dana">
                                                    <td>
                                                        <input class="form-control" type="text" name="ket_dana[{{ $key }}]" id="ket_dana[{{ $key }}]" value="{{ data_get($data_dana, 'ket_dana') }}">
                                                    </td>
                                                    <td>
                                                        <input oninput="totaldana()" class="form-control beban_dealer_" type="number" name="beban_dealer_dana[{{ $key }}]" id="beban_dealer_dana[{{ $key }}]" value="{{ data_get($data_dana, 'beban_dealer_dana') }}">
                                                    </td>
                                                    <td>
                                                        <input oninput="totaldana()" class="form-control beban_fincoy_" type="number" name="beban_fincoy_dana[{{ $key }}]" id="beban_fincoy_dana[{{ $key }}]" value="{{ data_get($data_dana, 'beban_fincoy_dana') }}">
                                                    </td>
                                                    <td>
                                                        <input oninput="totaldana()" class="form-control beban_md_" type="number" name="beban_md_dana[{{ $key }}]" id="beban_md_dana[{{ $key }}]" value="{{ data_get($data_dana, 'beban_md_dana') }}">
                                                    </td>
                                                    <td>
                                                        <a href="javascript:void(0);" class="removeDana btn btn-danger">
                                                            <i class="fas fa-minus-circle"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr class="row_to_clone_dana">
                                                <td>
                                                    <input class="form-control" type="text" name="ket_dana[0]" id="ket_dana[]">
                                                </td>
                                                <td>
                                                    <input oninput="totaldana()" class="form-control beban_dealer_" type="number" name="beban_dealer_dana[0]" id="beban_dealer_dana[]">
                                                </td>
                                                <td>
                                                    <input oninput="totaldana()" class="form-control beban_fincoy_" type="number" name="beban_fincoy_dana[0]" id="beban_fincoy_dana[]">
                                                </td>
                                                <td>
                                                    <input oninput="totaldana()" class="form-control beban_md_" type="number" name="beban_md_dana[0]" id="beban_md_dana[]">
                                                </td>
                                                <td>
                                                    <a href="javascript:void(0);" class="removeDana btn btn-danger">
                                                        <i class="fas fa-minus-circle"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endif
                                    </tbody>
                                    <tbody>
                                        <tr>
                                            <td></td>
                                            <td>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                      <span class="input-group-text" id="basic-addon1">Rp. </span>
                                                    </div>
                                                    <input class="form-control" id="subtotala" readonly>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                      <span class="input-group-text" id="basic-addon1">Rp. </span>
                                                    </div>
                                                    <input class="form-control" id="subtotalb" readonly>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                      <span class="input-group-text" id="basic-addon1">Rp. </span>
                                                    </div>
                                                    <input class="form-control" id="subtotalc" readonly>
                                                </div>
                                            </td>
                                            <td></td>
                                        </tr>
                                    </tbody>
                                </table>
                                <a class="btn btn-outline-success" onclick="addRowDana(); return false;" href="#">
                                    Tambah Perkiraan Dana <i class="fas fa-plus-circle"></i>
                                </a>
                                <div class="row">
                                    <div class="col-form-label col-8 text-right">
                                        TOTAL :
                                    </div>
                                    <div class="col-4">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                              <span class="input-group-text" id="basic-addon1">Rp. </span>
                                            </div>
                                            <input class="form-control" name="total" id="total" value="{{ $data->total_dana_proposal }}">
                                        </div>
                                    </div>
                                </div>
                                <hr>
                            </div>
                        </div>
                        <div class="mb-2 row">
                            <label class="col-sm-2 col-form-label">
                                <strong>Penanggung Jawab<strong style="color:rgb(243, 0, 0)">*</strong></strong>
                            </label>
                            <div class="col-sm-10">
                                <div class="row pb-2">
                                    <div class="col-4">
                                        <select class="form-control data-pj" name="pj" id="datapj">
                                            {{-- <option value="">Pilih..</option> --}}
                                            @foreach ($salespeople as $data_pj)
                                                <option value="{{ $data_pj->id }}-{{ $data_pj->nama_sales_people }}-{{ $data_pj->honda_id_sales_people }}-{{ $data_pj->hso_id_sales_people }}-{{ $data_pj->jabatan_sales_people }}">{{ $data_pj->nama_sales_people }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-4">
                                        <a class="btn btn-outline-success" onclick="pj(); return false;" href="#">
                                            Pilih Penanggung Jawab <i class="fas fa-check-circle"></i>
                                        </a>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <table class="table table-striped border datatable table-sm">
                                            <thead class="text-center">
                                                <tr>
                                                    <th>Nama</th>
                                                    <th>HONDA ID</th>
                                                    <th>HSO ID</th>
                                                    <th>Jabatan</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <input class="form-control" id="pjid" type="hidden" name="pjid" value="{{ $data->penanggung_jawab_proposal ?? '' }}">
                                                        <input class="form-control" id="pjnama" type="text" name="pjnama" value="{{ $data->pj->nama_sales_people ?? '' }}" disabled>
                                                    </td>
                                                    <td>
                                                        <input class="form-control" id="pjhondaid" type="text" name="pjhondaid" value="{{ $data->pj->honda_id_sales_people ?? '' }}" disabled>
                                                    </td>
                                                    <td>
                                                        <input class="form-control" id="pjhsoid" type="text" name="pjhsoid" value="{{ $data->pj->hso_id_sales_people ?? '' }}" disabled>
                                                    </td>
                                                    <td>
                                                        <input class="form-control" id="pjjabatan" type="text" name="pjjabatan[]" value="{{ $data->pj->jabatan_sales_people ?? '' }}" disabled="">
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-2 row">
                            <label class="col-sm-2 col-form-label">
                                <strong>Sales People<strong style="color:rgb(243, 0, 0)">*</strong></strong>
                            </label>
                            <div class="col-sm-10">
                                <div class="row pb-2">
                                    <div class="col-4">
                                        <select class="form-control data-sales sales" name="sales[]" id="datasalespeople">
                                            {{-- <option value="">Pilih..</option> --}}
                                            @foreach ($salespeople as $data_s)
                                                <option value="{{ $data_s->id }}-{{ $data_s->nama_sales_people }}-{{ $data_s->honda_id_sales_people }}-{{ $data_s->hso_id_sales_people }}-{{ $data_s->jabatan_sales_people }}">{{ $data_s->nama_sales_people }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-4">
                                        <a class="btn btn-outline-success" onclick="tambahsales(); return false;" href="#">
                                            Tambah Sales People <i class="fas fa-plus-circle"></i>
                                        </a>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <table class="table table-striped border datatable table-sm" id="salesdata">
                                            <thead class="text-center">
                                                <tr>
                                                    <th>Nama</th>
                                                    <th>HONDA ID</th>
                                                    <th>HSO ID</th>
                                                    <th>Jabatan</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if ($data->sales_people_proposal)
                                                    @foreach ($datasalespeople as $data_sal)
                                                        @php
                                                            $sp = DB::table('sales_people')->where('id', $data_sal)->first();
                                                        @endphp
                                                        <tr>
                                                            <td>
                                                                <input type="hidden" name="idsales[]" value="{{ $sp->id }}">
                                                                <input class="form-control sales_nama" type="text" name="sales_nama[]" value="{{ $sp->nama_sales_people }}" disabled="">
                                                            </td>
                                                            <td>
                                                                <input class="form-control honda_id" type="text" name="honda_id[]" value="{{ $sp->honda_id_sales_people }}" disabled="">
                                                            </td>
                                                            <td>
                                                                <input class="form-control hso_id" type="text" name="hso_id[]" value="{{ $sp->hso_id_sales_people }}" disabled="">
                                                            </td>
                                                            <td>
                                                                <input class="form-control jabatan" type="text" name="jabatan[]" value="{{ $sp->jabatan_sales_people }}" disabled="">
                                                            </td>
                                                            <td>
                                                                <a href="javascript:void(0);" class="removeSales btn btn-danger"><i class="fas fa-minus-circle"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @else

                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <hr>
                            </div>
                        </div>
                        <div class="mb-2 row">
                            <label class="col-sm-2 col-form-label">
                                <strong>Lokasi Pameran</strong>
                            </label>
                            <div class="col-sm-10">
                                <div class="mb-2 row">
                                    <label class="col-sm-2 col-form-label">Foto<strong style="color:rgb(243, 0, 0)">*</strong></label>
                                    <div class="col-sm-10">
                                        @if (!null == $data->foto_lokasi_proposal)
                                            <div class="row p-2">
                                                @foreach (json_decode($data->foto_lokasi_proposal) as $item)
                                                    <div class="col-4 img-thumbnail">
                                                        <div class="float-left" style="position: absolute">
                                                            <button type="text" name="b" value="{{ $item }}" class="float-right btn btn-sm btn-danger"  onclick="return confirm('Konfirmasi Menghapus foto?')">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </div>
                                                        <a href="{{ Request::root() }}/upload-foto/{{ $item }}" target="_blank">
                                                            <img src="/upload-foto/{{ $item }}" alt="" class="img-fluid">
                                                        </a>
                                                    </div>
                                                @endforeach
                                                <div class="pb-2"></div>
                                            </div>
                                        @endif
                                        <div class="row pt-2">
                                            <div class="col-4">
                                                <button type="text" name="b" value="upload" class="btn btn-info btn-block" >
                                                    <div class="float-left">
                                                        Upload Foto
                                                    </div>
                                                    <div class="text-right">
                                                        <i class="fas fa-chevron-right"></i>
                                                    </div>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-2 row">
                                    <button id="refresh" name="b" value="refresh" type="text" class="d-none"></button>
                                    <label class="col-sm-2 col-form-label">Latitude<strong style="color:rgb(243, 0, 0)">*</strong></label>
                                    <div class="col-sm-5">
                                        <input class="form-control" name="lat" id="lat" type="text" value="{{ $data->lat_proposal }}">
                                    </div>
                                </div>
                                <div class="mb-2 row">
                                    <label class="col-sm-2 col-form-label">Longtitude<strong style="color:rgb(243, 0, 0)">*</strong></label>
                                    <div class="col-sm-5">
                                        <input class="form-control" name="long" id="long" type="text" value="{{ $data->long_proposal }}">
                                    </div>
                                </div>
                                <script>
                                    $(document).ready(function(){
                                        $("#long").on('keypress', function (event) {
                                            if (event.keyCode === 13 || event.key === 'Enter') {
                                                if (confirm("Apakah anda yakin titik map sudah benar ?")) {
                                                    simpanmap();
                                                    event.preventDefault();
                                                } else {
                                                    event.preventDefault();
                                                }
                                            }
                                        });
                                        $("#lat").on('keypress', function (event) {
                                            if (event.keyCode === 13 || event.key === 'Enter') {
                                                if (confirm("Apakah anda yakin titik map sudah benar ?")) {
                                                    simpanmap();
                                                    event.preventDefault();
                                                } else {
                                                    event.preventDefault();
                                                }
                                            }
                                        });
                                    });
                                </script>
                                <script>
                                    function simpanmap() {
                                        $("#refresh").click();
                                    }
                                </script>
                                <div id="mapid"></div>
                                <div class="row pt-1">
                                    <div class="col-sm-8 text-muted" style="font-size: 10px">
                                        <strong>Keterangan Maker</strong>
                                        <table>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <img src="https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png" width="15" alt="">
                                                    </td>
                                                    <td>
                                                        :
                                                    </td>
                                                    <td>
                                                        Marker Lokasi Event/Pameran yang Akan Berjalan
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <img src="https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png" width="15" alt="">
                                                    </td>
                                                    <td>
                                                        :
                                                    </td>
                                                    <td>
                                                        Marker Lokasi Event/Pameran yang Sedang Berjalan
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <img src="https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-blue.png" width="15" alt="">
                                                    </td>
                                                    <td>
                                                        :
                                                    </td>
                                                    <td>
                                                        Marker Lokasi Event/Pameran yang Akan Dipilih
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if ($data->status_proposal == 1 || $data->status_proposal == 5)
                            <div class="mb-2 row fixed-bottom position-sticky p-4 border-top" style="background-color: #fff; ">
                                <div class="col-12">
                                    <a href="#" class="btn btn-outline-secondary">Cancel</a>
                                    <div class="float-right">
                                        <button class="btn btn-outline-info" type="text" name="b" value="draft">Simpan Sebagai Draft</button>
                                        <button class="btn btn-primary" type="text" name="b" value="done"  onclick="return confirm('Konfirmasi Pengajuan Proposal')">Submit</button>
                                    </div>
                                </div>
                            </div>
                    </form>
                        @else
                    </div>
                        @endif
                </div>
            </div><!--col-md-10-->
        </div><!--row-->
    </div><!--container-->
    <input type="hidden" name="id" value="{{ request()->id }}">

<script>
    var kategori = $('#idkategori').val();
    if(kategori == 5) {
        $(document).ready(function() {
        
            var id = $('input[name="id"]').val();
        
            $.ajax({
                url: '/cabang/getunitentrylpj',
                type: 'POST',
                data: {id: id,model:'proposal',_token: $('input[name="_token"]').val()},
                success: function(response) {
                    if(response.status == "success") {
                        var items = response.data

                        var kpb1 = $('input[name="kpb1"]').val(items[0].unit_jumlah);
                        var kpb2 = $('input[name="kpb2"]').val(items[1].unit_jumlah);
                        var kpb3 = $('input[name="kpb3"]').val(items[2].unit_jumlah);
                        var kpb4 = $('input[name="kpb4"]').val(items[3].unit_jumlah);
                        var psl = $('input[name="psl"]').val(items[4].unit_jumlah);
                        var psr = $('input[name="psr"]').val(items[5].unit_jumlah);
                        var go = $('input[name="go"]').val(items[6].unit_jumlah);
                        var lr = $('input[name="lr"]').val(items[7].unit_jumlah);
        
                        var unittotal = items[0].unit_jumlah + items[1].unit_jumlah + items[2].unit_jumlah + items[3].unit_jumlah + items[4].unit_jumlah + items[5].unit_jumlah + items[6].unit_jumlah + items[7].unit_jumlah;
        
                        $('input[name="unittotal"]').val(unittotal);

                        console.log(items)
                    }
                },
                error: function(xhr, status, error) {
                    if(response.status == "error") {
                        alert(response.message)
                    }
                }
            });
        
            $('input[name="kpb1"], input[name="kpb2"], input[name="kpb3"], input[name="kpb4"], input[name="psl"], input[name="psr"], input[name="go"], input[name="lr"]').on('input', function() {
                var kpb1 = parseInt($('input[name="kpb1"]').val()) || 0;
                var kpb2 = parseInt($('input[name="kpb2"]').val()) || 0;
                var kpb3 = parseInt($('input[name="kpb3"]').val()) || 0;
                var kpb4 = parseInt($('input[name="kpb4"]').val()) || 0;
                var psl = parseInt($('input[name="psl"]').val()) || 0;
                var psr = parseInt($('input[name="psr"]').val()) || 0;
                var go = parseInt($('input[name="go"]').val()) || 0;
                var lr = parseInt($('input[name="lr"]').val()) || 0;

                var unittotal = kpb1 + kpb2 + kpb3 + kpb4 + psl + psr + go + lr;
        
                $('input[name="unittotal"]').val(unittotal);
            })
        
            $('#simpanunitentry').on('click',function(event) {
        
                var id = $('input[name="id"]').val();

                var kpb1 = $('input[name="kpb1"]').val() || 0;
                var kpb2 = $('input[name="kpb2"]').val() || 0;
                var kpb3 = $('input[name="kpb3"]').val() || 0;
                var kpb4 = $('input[name="kpb4"]').val() || 0;
                var psl = $('input[name="psl"]').val() || 0;
                var psr = $('input[name="psr"]').val() || 0;
                var go = $('input[name="go"]').val() || 0;
                var lr = $('input[name="lr"]').val() || 0;
        
                var formData = {
                    id: id,
                    model: 'proposal',
                    kpb1: parseInt(kpb1),
                    kpb2: parseInt(kpb2),
                    kpb3: parseInt(kpb3),
                    kpb4: parseInt(kpb4),
                    psl: parseInt(psl),
                    psr: parseInt(psr),
                    go: parseInt(go),
                    lr: parseInt(lr),
                    _token: $('input[name="_token"]').val()
                };
        
                console.log(formData)
        
                $.ajax({
                    url: '/cabang/updatunitentrylpj',
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        if(response.status == "success") {
                            alert(response.message)
                        }
                    },
                    error: function(xhr, status, error) {
                        if(response.status == "error") {
                            alert(response.message)
                        }
                    }
                });
            });
        
        })
    }
</script>

<script>
    var kategori = $('#idkategori').val();
    if(kategori == 5) {
        $(document).ready(function() {
        
            $('#jasa').val(0);
            $('#part').val(0);
            $('#oli').val(0);
        
            var id = $('input[name="id"]').val();
        
            $.ajax({
                url: '/cabang/getrevenuelpj',
                type: 'POST',
                data: {id: id,model:'proposal',_token: $('input[name="_token"]').val()},
                success: function(response) {
                    if(response.status == "success") {
                        var items = response.data
                        $('#jasa').val(items.jasa);
                        $('#part').val(items.part);
                        $('#oli').val(items.oli);
                        var total = items.jasa + items.part + items.oli;
        
                        $('#total').val(total);
                    }
                },
                error: function(xhr, status, error) {
                    if(response.status == "error") {
                        alert(response.message)
                    }
                }
            });
        
            $('#jasa, #part, #oli').on('input', function() {
                var jasa = parseInt($('#jasa').val()) || 0;
                var part = parseInt($('#part').val()) || 0;
                var oli = parseInt($('#oli').val()) || 0;
        
                var total = jasa + part + oli;
        
                $('#total').val(total);
            })
        
            $('#simpanrevenue').on('click',function(event) {
        
                var id = $('input[name="id"]').val();
                var jasaact = $('#jasa').val() || 0;
                var partact = $('#part').val() || 0;
                var oliact = $('#oli').val() || 0;
        
                var formData = {
                    id: id,
                    model: 'proposal',
                    jasa: parseInt(jasaact),
                    part: parseInt(partact),
                    oli: parseInt(oliact),
                    _token: $('input[name="_token"]').val()
                };
        
                // console.log(formData)
        
                $.ajax({
                    url: '/cabang/updaterevenuelpj',
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        if(response.status == "success") {
                            alert(response.message)
                        }
                    },
                    error: function(xhr, status, error) {
                        if(response.status == "error") {
                            alert(response.message)
                        }
                    }
                });
            });
        
        })
    }
</script>
<script>
    $("#formproposal").submit(function(e) {
        // e.preventDefault(); // Ini untuk mencegah pengiriman formulir
        $("#simpanunitentry").trigger("click");
        $("#simpanrevenue").trigger("click");
    });
</script>
    <script type="text/javascript">
        $('.data-lokasi').select2(
        //     {
        //     placeholder: 'Cari Lokasi',
        //     ajax: {
        //         url: 'data-lokasi',
        //         dataType: 'json',
        //         delay: 250,
        //         processResults: function (data) {
        //             return {
        //                 results: $.map(data, function (item) {
        //                     return {
        //                         text: item.kelurahan_lokasi+', '+item.kecamatan_lokasi+', '+item.kota_lokasi,
        //                         id: item.id
        //                     }
        //                 })
        //             };
        //         },
        //         cache: true
        //     }
        // }
        );
    </script>
    <script type="text/javascript">
        $('.data-display').select2(
        //     {
        //     placeholder: 'Cari Display',
        //     ajax: {
        //         url: '/display',
        //         dataType: 'json',
        //         delay: 250,
        //         processResults: function (data) {
        //             return {
        //                 results: $.map(data, function (item) {
        //                     return {
        //                         text: item.name+' - '+item.email,
        //                         id: item.id
        //                     }
        //                 })
        //             };
        //         },
        //         cache: true
        //     }
        // }
        );
    </script>
    <script type="text/javascript">
        $('.data-finance').select2(
        //     {
        //     placeholder: 'Cari Finance',
        //     ajax: {
        //         url: '/finance',
        //         dataType: 'json',
        //         delay: 250,
        //         processResults: function (data) {
        //             return {
        //                 results: $.map(data, function (item) {
        //                     return {
        //                         text: item.name+' - '+item.email,
        //                         id: item.id
        //                     }
        //                 })
        //             };
        //         },
        //         cache: true
        //     }
        // }
        );
    </script>
    <script type="text/javascript">
        $('.data-sales').select2(
        //     {
        //     placeholder: 'Cari Sales People',
        //     ajax: {
        //         url: '/sales',
        //         dataType: 'json',
        //         delay: 250,
        //         processResults: function (data) {
        //             return {
        //                 results: $.map(data, function (item) {
        //                     return {
        //                         text: item.name+' - '+item.email,
        //                         id: item.id
        //                     }
        //                 })
        //             };
        //         },
        //         cache: true
        //     }
        // }
        );
    </script>
    <script type="text/javascript">
        $('.data-pj').select2(
        //     {
        //     placeholder: 'Cari Penanggung Jawab',
        //     ajax: {
        //         url: '/pj',
        //         dataType: 'json',
        //         delay: 250,
        //         processResults: function (data) {
        //             return {
        //                 results: $.map(data, function (item) {
        //                     return {
        //                         text: item.name+' - '+item.email,
        //                         id: item.id
        //                     }
        //                 })
        //             };
        //         },
        //         cache: true
        //     }
        // }
        );
    </script>

    {{-- Tambah data dana --}}
    <script>
        function addRowDana() {
            /* Declare variables */
            var elements, templateRow, rowCount, row, className, newRow, element;
            var i, s, t;

            /* Get and count all "tr" elements with class="row".    The last one will
            * be serve as a template. */
            if (!document.getElementsByTagName)
                return false; /* DOM not supported */
            elements = document.getElementsByTagName("tr");
            templateRow = null;
            rowCount = 0;
            for (i = 0; i < elements.length; i++) {
                row = elements.item(i);

                /* Get the "class" attribute of the row. */
                className = null;
                if (row.getAttribute)
                    className = row.getAttribute('class')
                if (className == null && row.attributes) {    // MSIE 5
                    /* getAttribute('class') always returns null on MSIE 5, and
                    * row.attributes doesn't work on Firefox 1.0.    Go figure. */
                    className = row.attributes['class'];
                    if (className && typeof(className) == 'object' && className.value) {
                        // MSIE 6
                        className = className.value;
                    }
                }

                /* This is not one of the rows we're looking for.    Move along. */
                if (className != "row_to_clone_dana")
                    continue;

                /* This *is* a row we're looking for. */
                templateRow = row;
                rowCount++;
            }
            if (templateRow == null)
                return false; /* Couldn't find a template row. */

            /* Make a copy of the template row */
            newRow = templateRow.cloneNode(true);

            /* Change the form variables e.g. price[x] -> price[rowCount] */
            elements = newRow.getElementsByTagName("input");
            for (i = 0; i < elements.length; i++) {

                // name
                element = elements.item(i);
                s = null;
                s = element.getAttribute("name");
                if (s == null)
                    continue;
                t = s.split("[");
                if (t.length < 2)
                    continue;
                s = t[0] + "[" + rowCount.toString() + "]";
                element.setAttribute("name", s);
                element.value = "";
            }

            /* Add the newly-created row to the table */
            templateRow.parentNode.appendChild(newRow);
            return true;

        }
    </script>

    <script>
        $(document).ready(function(){
            $("#dataDana").on('click','.removeDana',function(){
                $(this).parent().parent().remove();
                totaldana();
            });
            $("#salesdata").on('click','.removeSales',function(){
                $(this).parent().parent().remove();
            });
            $("#displayunitdata").on('click','.removedisplayunit',function(){
                $(this).parent().parent().remove();
            });
            $("#unitentrydata").on('click','.removeunitentry',function(){
                $(this).parent().parent().remove();
            });
            totaldana();
            // totalrevenue();
            // totalunitentry();
        });
    </script>

    <script>
        function totaldana(){

            var sum_a = 0;
            var sum_b = 0;
            var sum_c = 0;

            $('.beban_dealer_').each(function () {
                sum_a += Number($(this).val());
            });
            $('.beban_fincoy_').each(function () {
                sum_b += Number($(this).val());
            });
            $('.beban_md_').each(function () {
                sum_c += Number($(this).val());
            });

            $sumtotal = sum_a + sum_b + sum_c;
            $sumsubtotal_a = sum_a;
            $sumsubtotal_b = sum_b;
            $sumsubtotal_c = sum_c;
            document.getElementById("total").value = $sumtotal.toLocaleString();
            document.getElementById("subtotala").value = $sumsubtotal_a.toLocaleString();
            document.getElementById("subtotalb").value = $sumsubtotal_b.toLocaleString();
            document.getElementById("subtotalc").value = $sumsubtotal_c.toLocaleString();

            console.log(sum_a + sum_b + sum_c);
        }
    </script>

    <script>
        function totalunitentry(){

            var sum_a = 0;

            $('.unitentry').each(function () {
                sum_a += Number($(this).val());
            });

            document.getElementById("totaluniten").value = sum_a.toLocaleString();

            console.log(sum_a);
        }
    </script>

    <script>
        function totalrevenue(){

            var sum_a = 0;
            var sum_b = 0;
            var sum_c = 0;

            sum_a = Number(document.getElementById("jasa").value);
            sum_b = Number(document.getElementById("part").value);
            sum_c = Number(document.getElementById("oli").value);

            $sumtotal = sum_a + sum_b + sum_c;
            document.getElementById("totalrev").value = $sumtotal.toLocaleString();

            console.log(sum_a + sum_b + sum_c);
        }
    </script>

    <script>
        function tambahdisplayunit() {
            var table         = document.getElementById("displayunitdata");
            var rowCount      = table.rows.length;
            var row           = table.insertRow(rowCount);
            var nama          = row.insertCell(0);
            var unit          = row.insertCell(1);
            var remv          = row.insertCell(2);

            //console.log($('#datadisplayunit').val());

            var dataid      = $('#datadisplayunit').val().split('-')[0];
            var datanama    = $('#datadisplayunit').val().split('-')[1];

            nama.innerHTML    = '<input type="hidden" name="iddisplayunit[]" value="'+dataid+'"><input class="form-control displayunit_nama" type="text" name="displayunit_nama[]" value="'+datanama+'" readonly>';
            unit.innerHTML    = '<input class="form-control displayunit" type="number" name="displayunit[]" value="1">'
            remv.innerHTML    = '<a href="javascript:void(0);" class="removedisplayunit btn btn-danger"><i class="fas fa-minus-circle"></i></a>'
        }
    </script>

    <script>
        function tambahunitentry() {
            var table         = document.getElementById("unitentrydata");
            var rowCount      = table.rows.length;
            var row           = table.insertRow(rowCount);
            var nama          = row.insertCell(0);
            var unit          = row.insertCell(1);
            var remv          = row.insertCell(2);

            //console.log($('#dataunitentry').val());

            var dataid      = $('#dataunitentry').val().split('-')[0];
            var datanama    = $('#dataunitentry').val().split('-')[1];

            nama.innerHTML    = '<input type="hidden" name="idunitentry[]" value="'+dataid+'"><input class="form-control unitentry_nama" type="text" name="unitentry_nama[]" value="'+datanama+'" readonly >';
            unit.innerHTML    = '<input class="form-control unitentry" type="number" name="unitentry[]" value="0" oninput="totalunitentry()" >'
            remv.innerHTML    = '<a href="javascript:void(0);" class="removeunitentry btn btn-danger"><i class="fas fa-minus-circle"></i></a>'
        }
    </script>

    <script>
        function tambahsales() {
            var table         = document.getElementById("salesdata");
            var rowCount      = table.rows.length;
            var row           = table.insertRow(rowCount);
            var nama          = row.insertCell(0);
            var hondaid       = row.insertCell(1);
            var hsoid         = row.insertCell(2);
            var jabatan       = row.insertCell(3);
            var remv          = row.insertCell(4);


            var dataid      = $('#datasalespeople').val().split('-')[0];
            var datanama    = $('#datasalespeople').val().split('-')[1];
            var datahondaid = $('#datasalespeople').val().split('-')[2];
            var datahsoid   = $('#datasalespeople').val().split('-')[3];
            var datajabatan = $('#datasalespeople').val().split('-')[4];


            nama.innerHTML    = '<input type="hidden" name="idsales[]" value="'+dataid+'"><input class="form-control sales_nama" type="text" name="sales_nama[]" value="'+datanama+'" disabled>';
            hondaid.innerHTML = '<input class="form-control honda_id" type="text" name="honda_id[]" value="'+datahondaid+'" disabled>';
            hsoid.innerHTML   = '<input class="form-control hso_id" type="text" name="hso_id[]" value="'+datahsoid+'" disabled>';
            jabatan.innerHTML = '<input class="form-control jabatan" type="text" name="jabatan[]" value="'+datajabatan+'" disabled>';
            remv.innerHTML    = '<a href="javascript:void(0);" class="removeSales btn btn-danger"><i class="fas fa-minus-circle"></i></a>';
        }
    </script>

    <script>
        function pj() {
            var dataid      = $('#datapj').val().split('-')[0];
            var datanama    = $('#datapj').val().split('-')[1];
            var datahondaid = $('#datapj').val().split('-')[2];
            var datahsoid   = $('#datapj').val().split('-')[3];
            var jabatan     = $('#datapj').val().split('-')[4];

            console.log($('#datapj').val());
            document.getElementById("pjid").value = dataid;
            document.getElementById("pjnama").value = datanama;
            document.getElementById("pjhondaid").value = datahondaid;
            document.getElementById("pjhsoid").value = datahsoid ;
            document.getElementById("pjjabatan").value = jabatan ;
        }
    </script>

    <script>
        function tanggalevent() {
            var start     = document.getElementById("tanggalstart").value;
            var datestart = new Date(start);
            datestart.setDate(datestart.getDate()+{{ $data->kategori->waktu_minimum-1 ?? 0 }});
            // var y = datestart.getFullYear();
            // var m = datestart.getMonth()+1;
            // var d = datestart.getDate();
            // var getend = new Date(y+'-'+m+'-'+d);
            var minend = datestart.toISOString().substring(0,10);
            document.getElementById("tanggalend").min = minend;
            @if ($data->kategori->id == 2 ||$data->kategori->id == 3 ||$data->kategori->id == 4)
            document.getElementById("tanggalend").value = minend;
            document.getElementById("tanggalend").max = minend;
            @endif
            // console.log(minend);
        }
    </script>

    {{-- Map Leaflet JS --}}
    <script src="https://unpkg.com/leaflet@1.3.1/dist/leaflet.js"
    integrity="sha512-/Nsx9X4HebavoBvEBuyp3I7od5tA0UzAxs+j83KgC8PU0kgB4XiK4Lfe4y4cgBtaRJQEIFCW+oC506aPT2L1zw=="
    crossorigin=""></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script src='https://api.mapbox.com/mapbox.js/v3.3.1/mapbox.js'></script>
    <script src='https://api.mapbox.com/mapbox.js/plugins/leaflet-locatecontrol/v0.43.0/L.Control.Locate.min.js'></script>


    {{-- <script>
    var mapCenter = [{{ request('latitude', config('leaflet.map_center_latitude')) }}, {{ request('longitude', config('leaflet.map_center_longitude')) }}];
    var map = L.map('mapid--').setView(mapCenter, {{ config('leaflet.zoom_level') }});

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    var marker = L.marker(mapCenter).addTo(map);
    function updateMarker(lat, lng) {
        marker
        .setLatLng([lat, lng])
        .bindPopup("Your location :  " + marker.getLatLng().toString())
        .openPopup();
        return false;
    };

    map.on('click', function(e) {
        let latitude = e.latlng.lat.toString().substring(0, 15);
        let longitude = e.latlng.lng.toString().substring(0, 15);
        $('#latitude').val(latitude);
        $('#longitude').val(longitude);
        updateMarker(latitude, longitude);
    });

    var updateMarkerByInputs = function() {
        return updateMarker( $('#latitude').val() , $('#longitude').val());
    }
    $('#latitude').on('input', updateMarkerByInputs);
    $('#longitude').on('input', updateMarkerByInputs);
    </script> --}}

    {{-- <script src="https://unpkg.com/leaflet.markercluster@1.4.1/dist/leaflet.markercluster.js"></script> --}}
    @php
        $lat_  = $data->lat_proposal ?? $kota->titikmap->latitude;
        $long_ = $data->long_proposal ?? $kota->titikmap->longtitude;
        $zoom_ = $kota->titikmap->zoom;
    @endphp
    <script>

    var redIcon = new L.Icon({
    iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
    shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
    iconSize: [25, 41],
    iconAnchor: [12, 41],
    popupAnchor: [1, -34],
    shadowSize: [41, 41]
    });

    var greenIcon = new L.Icon({
    iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
    shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
    iconSize: [25, 41],
    iconAnchor: [12, 41],
    popupAnchor: [1, -34],
    shadowSize: [41, 41]
    });
    // please replace this with your own mapbox token!
    // var token = 'pk.eyJ1IjoicmltYmFib3JuZSIsImEiOiJja3Z5bjZrZGwwMWtyMnVvN2xqaWRwdWh4In0.xUbfIJAJn2vMBTd_IKDzTQ';
    // var mapboxUrl = 'https://api.mapbox.com/styles/v1/mapbox/streets-v10/tiles/{z}/{x}/{y}@2x?access_token=' + token;
    // var mapboxAttrib = 'Map data © <a href="http://osm.org/copyright">OpenStreetMap</a> contributors. Tiles from <a href="https://www.mapbox.com">Mapbox</a>.';
    // var mapbox = new L.TileLayer(mapboxUrl, {
    //         attribution: mapboxAttrib,
    //         tileSize: 512,
    //         zoomOffset: -1
    // });

    // var map = L.map('mapid', {
    //         tap: false, // ref https://github.com/Leaflet/Leaflet/issues/7255
    //         layers: [mapbox],
    //         center: [{{ config('leaflet.map_center_latitude') }}, {{ config('leaflet.map_center_longitude') }}],
    //         zoom: {{ config('leaflet.zoom_level') }},
    //         zoomControl: true
    //         }
    //     );
        // .setView([{{ config('leaflet.map_center_latitude') }}, {{ config('leaflet.map_center_longitude') }}], {{ config('leaflet.zoom_level') }});

    // inisiasi mapbox
    L.mapbox.accessToken = 'pk.eyJ1IjoicmltYmFib3JuZSIsImEiOiJja3Z5bjZrZGwwMWtyMnVvN2xqaWRwdWh4In0.xUbfIJAJn2vMBTd_IKDzTQ';
    var map = L.mapbox.map('mapid')
                .setView(['{{ $lat_ }}', '{{ $long_ }}'], '{{ $zoom_ }}')
                .addLayer(L.mapbox.styleLayer('mapbox://styles/mapbox/streets-v11'))
                .addControl(L.mapbox.geocoderControl('mapbox.places', {
                autocomplete: true
            }));

    // lokasi titik posisi
    L.control.locate().addTo(map);

    // legend. ukuran skala jarak peta
    L.control.scale({position: 'topright'}).addTo(map);

    // lisensi
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    })
    .addTo(map);

    // untuk group marker, tapi jika tidak pake circle
    // var markers = L.markerClusterGroup();

    // ngambil data
    // axios.get('https://honda-map.test/api/outlets', {
    //     headers: {
    //         'Access-Control-Allow-Origin' : '*',
    //         'Access-Control-Allow-Methods':'GET,PUT,POST,DELETE,PATCH,OPTIONS',
    //     }
    // })
    // .then(function (response) {
    //     var marker = L.geoJSON(response.data, {
    //         pointToLayer: function(geoJsonPoint, latlng, layer) {
    //             markerr  = L.marker(latlng).bindPopup('Dealer Tes');
    //             circle   = L.circle(latlng, {radius: 1000, color: 'red', opacity:.5});
    //             return L.featureGroup([markerr, circle])
    //                     // .bindPopup(nama)
    //                     .addTo(map);
    //         }
    //     });
    //     markers.addLayer(marker);
    // })
    // .catch(function (error) {
    //     console.log(error);
    // });

    //Data Contoh
    // L.marker([-1.242292, 116.879625], {icon: redIcon}).bindPopup('Dealer A').addTo(map);
    // L.circle([-1.242292, 116.879625], {radius: 1000, color: 'red', opacity:.5}).addTo(map);

    // L.marker([-1.252223, 116.851946], {icon: redIcon}).bindPopup('Dealer B').addTo(map);
    // L.circle([-1.252223, 116.851946], {radius: 1000, color: 'red', opacity:.5}).addTo(map);

    // L.marker([-1.242223, 116.852946], {icon: redIcon}).bindPopup('Dealer C').addTo(map);
    // L.circle([-1.242223, 116.852946], {radius: 1000, color: 'red', opacity:.5}).addTo(map);

    // L.marker([-1.239848, 116.916119], {icon: redIcon}).bindPopup('Dealer D').addTo(map);
    // L.circle([-1.239848, 116.916119], {radius: 1000, color: 'red', opacity:.5}).addTo(map);

    @foreach ($cektitikaktif as $titik)
        @php
            $a = \Carbon\Carbon::create($titik->periode_start_proposal);
            $b = \Carbon\Carbon::create($titik->periode_end_proposal);
            $c = \Carbon\Carbon::now();
            $d = \Carbon\Carbon::now()->addDays(1);

            $a_ = \Carbon\Carbon::create($titik->periode_start_proposal)->format('Y-m-d');
            $b_ = \Carbon\Carbon::create($titik->periode_end_proposal)->format('Y-m-d');
        @endphp
        @if($a->greaterThan($c) == true)
            L.marker([{!! $titik->lat_proposal !!}, {!! $titik->long_proposal !!}], {icon: greenIcon}).bindPopup('{!! $titik->kategori->nama_kategori !!} {!! $titik->dealer->nama_dealer !!} ( {{ $a_ }} - {{ $b_ }} )').addTo(map);
            L.circle([{!! $titik->lat_proposal !!}, {!! $titik->long_proposal !!}], {radius: 1000, color: 'green', opacity:.5}).addTo(map);
        @else
            @if ($b->greaterThan($c) == true)
                L.marker([{!! $titik->lat_proposal !!}, {!! $titik->long_proposal !!}], {icon: redIcon}).bindPopup('{!! $titik->kategori->nama_kategori !!} {!! $titik->dealer->nama_dealer !!} ( {{ $a_ }} - {{ $b_ }} )').addTo(map);
                L.circle([{!! $titik->lat_proposal !!}, {!! $titik->long_proposal !!}], {radius: 1000, color: 'red', opacity:.5}).addTo(map);
            @endif
        @endif
    @endforeach


    // map.addLayer(marker);

    // tambah data manual
    // var markers = [
    //         [ -1.242292, 116.879625, "Dealer A" ],
    //      ];

    //      //Loop through the markers array
    //      for (var i=0; i<markers.length; i++) {

    //         var lon = markers[i][0];
    //         var lat = markers[i][1];
    //         var popupText = markers[i][2];

    //         // markerr  = L.marker(lat, lon).bindPopup('Dealer Tes');
    //         // circle   = L.circle(lat, lon, {radius: 1000, color: 'red', opacity:.5});
    //         // L.featureGroup([markerr, circle])
    //         //             .bindPopup(popupText)
    //         //             .addTo(map);

    //          var markerLocation = new L.LatLng(lat, lon);
    //          var marker = new L.Marker(markerLocation);
    //          map.addLayer(marker);

    //         //  marker.bindPopup(popupText);

        //  }

    // untuk group marker, tapi jika tidak pake circle
    // map.addLayer(markers);


    var mapCenter = [ {!!  $data->lat_proposal ?? $lat_ !!}, {!!  $data->long_proposal ?? $long_ !!}];

    // Tambah Data Marker
    var theMarker = L.marker(mapCenter).addTo(map);

    function updateMarker(lat, lng) {
        theMarker
        .setLatLng([lat, lng])
        .bindPopup("Tandai Lokasi")
        .openPopup();
        return false;
    };

    map.on('click', function(e) {
        let latitude = e.latlng.lat.toString().substring(0, 15);
        let longitude = e.latlng.lng.toString().substring(0, 15);

        // if (theMarker != undefined) {
        //     map.removeLayer(theMarker);
        // };

        // var popupContent = "Tandai Disini";
        // popupContent += '<br><a href="latitude=' + latitude + '&longitude=' + longitude + '">Add new outlet here</a>';


        // theMarker.bindPopup(popupContent)
        // .openPopup();

        // update kolom
        $('#lat').val(latitude);
        $('#long').val(longitude);
        updateMarker(latitude, longitude);
    });

    var updateMarkerByInputs = function() {
        return updateMarker( $('#lat').val() , $('#long').val());
    }

    $('#lat').on('input', updateMarkerByInputs);
    $('#long').on('input', updateMarkerByInputs);
    </script>


@endsection
