@extends('cabang.layouts.konsumen')

@section('title', 'Buat LPJ')
@section('content')
<style>
    .image{
        position:relative;
        overflow:hidden;
        padding-bottom:35%;
        width: 50%;
    }
    .image img{
        position:absolute;
    }
    .img-thumbnail {
        background-color: #ffffff;
        border: 0px solid #ffffff;
    }
</style>
    <div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="text-center">
                                        <h5>Laporan Pertanggung Jawaban</h5>
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
                                    <strong>No Proposal</strong>
                                </label>
                                <label class="col-sm-10 col-form-label font-weight-bold">
                                    {{ $data->proposal->no_proposal }}
                                </label>
                            </div>
                            <div class="mb-2 row">
                                <label class="col-sm-2 col-form-label">
                                    <strong>Penanggung Jawab</strong>
                                </label>
                                <label class="col-sm-10 col-form-label">
                                    {{ $data->proposal->pj->nama_sales_people ?? '' }}
                                </label>
                            </div>
                            <div class="mb-2 row">
                                <label class="col-sm-2 col-form-label">
                                    <strong>Submit Date</strong>
                                </label>
                                <label class="col-sm-10 col-form-label">
                                    {{ $data->submit_date ?? '' }}
                                </label>
                            </div>
                            <div class="pt-3 row">
                                <div class="col-12">
                                    <table class="table table-striped border datatable table-sm table-bordered">
                                        <thead class="text-center">
                                            <tr>
                                                <th style="vertical-align: middle;" colspan="2">Periode</th>
                                                <th style="vertical-align: middle;" rowspan="2">Durasi</th>
                                                <th style="vertical-align: middle;" rowspan="2">Kode Dealer</th>
                                                <th style="vertical-align: middle;" rowspan="2">Nama Dealer</th>
                                                <th style="vertical-align: middle;" rowspan="2">Lokasi</th>
                                                <th style="vertical-align: middle;" colspan="2">Database</th>
                                                <th style="vertical-align: middle;" colspan="2">Prospecting</th>
                                                <th style="vertical-align: middle;" colspan="2">Penjualan</th>
                                                <th style="vertical-align: middle;" colspan="2">Downloader Motorku-X</th>
                                                <th style="vertical-align: middle;" rowspan="2">Sales Forces</th>
                                                <th style="vertical-align: middle;" colspan="2">Biaya</th>
                                            </tr>
                                            <tr>
                                                <th>Start</th>
                                                <th>End</th>
                                                <th>Target</th>
                                                <th>Aktual</th>
                                                <th>Target</th>
                                                <th>Aktual</th>
                                                <th>Target</th>
                                                <th>Aktual</th>
                                                <th>Target</th>
                                                <th>Aktual</th>
                                                <th>Target</th>
                                                <th>Aktual</th>
                                            </tr>
                                        </thead>
                                        <tbody  class="text-center">
                                            <tr>
                                                <td style="vertical-align: middle;">
                                                    {{ $data->periode_start_lpj }}
                                                </td>
                                                <td style="vertical-align: middle;">
                                                    {{ $data->periode_end_lpj }}
                                                </td>
                                                <td style="vertical-align: middle;">
                                                    {{ $data->durasi($data->periode_start_lpj, $data->periode_end_lpj ) }} Hari
                                                </td>
                                                <td style="vertical-align: middle;">
                                                    {{ $data->proposal->dealer->kode_dealer }}
                                                </td>
                                                <td style="vertical-align: middle;">
                                                    {{ $data->proposal->dealer->nama_dealer }}
                                                </td>
                                                <td style="vertical-align: middle;">
                                                    {{ $data->tempat_lpj }}. {{ $data->lokasi->kelurahan_lokasi }}, {{ $data->lokasi->kecamatan_lokasi }}, {{ Str::title($data->lokasi->kota_lokasi) }}
                                                </td>
                                                <td style="vertical-align: middle;">
                                                    {{ $data->proposal->target_database_proposal }}
                                                </td>
                                                <td style="vertical-align: middle;">
                                                     {{ $data->target_database_lpj }}
                                                </td>
                                                <td style="vertical-align: middle;">
                                                    {{ $data->proposal->target_penjualan_proposal }}
                                                </td>
                                                <td style="vertical-align: middle;">
                                                     {{ $data->target_penjualan_lpj }}
                                                </td>
                                                <td style="vertical-align: middle;">
                                                    {{ $data->proposal->target_prospectus_proposal }}
                                                </td>
                                                <td style="vertical-align: middle;">
                                                     {{ $data->target_prospectus_lpj }}
                                                </td>
                                                <td style="vertical-align: middle;">
                                                    {{ $data->proposal->target_downloader_proposal }}
                                                </td>
                                                <td style="vertical-align: middle;">
                                                     {{ $data->target_downloader_lpj }}
                                                </td>
                                                <td style="vertical-align: middle;">
                                                    {{ count(json_decode($data->proposal->sales_people_proposal)) }}
                                                </td>
                                                <td style="vertical-align: middle;">
                                                    Rp. {{ number_format($data->proposal->total_dana_proposal,0,',',','); }}
                                                </td>
                                                <td style="vertical-align: middle;">
                                                    Rp. {{ number_format($data->total_dana_lpj,0,',',','); }}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="row p-3">
                                <div class="col-6">
                                    <div class="row p-2 border rounded">
                                        @php
                                            $datakanvasing = DB::table('lpj_dokumentasis')->where('id_lpj', $data->id)->where('kode', 1)->get();
                                        @endphp
                                        @foreach ($datakanvasing as $item_1)
                                        <div class="image">
                                            <div class="col-6 img-thumbnail img img-responsive full-height">
                                                <a href="{{ Request::root() }}/upload-foto-lpj/{{ $item_1->foto }}" target="_blank">
                                                    <img src="{{ Request::root() }}/upload-foto-lpj/{{ $item_1->foto }}" alt="" class="img-fluid border">
                                                </a>
                                            </div>
                                        </div>
                                        @endforeach
                                        <div class="col-12 text-center font-weight-bold">
                                            Kanvasing Sebelum kegiatan
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="row p-2 border rounded">
                                        @php
                                            $capture = DB::table('lpj_dokumentasis')->where('id_lpj', $data->id)->where('kode', 2)->get();
                                        @endphp
                                        @foreach ($capture as $item_2)
                                        <div class="image">
                                            <div class="col-6 img-thumbnail img img-responsive full-height">
                                                <a href="{{ Request::root() }}/upload-foto-lpj/{{ $item_2->foto }}" target="_blank">
                                                    <img src="{{ Request::root() }}/upload-foto-lpj/{{ $item_2->foto }}" alt="" class="img-fluid border">
                                                </a>
                                            </div>
                                        </div>
                                        @endforeach
                                        <div class="col-12 text-center font-weight-bold">
                                            Capture Blast WA kepada konsumen
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="row p-2 border rounded">
                                        @php
                                            $posting = DB::table('lpj_dokumentasis')->where('id_lpj', $data->id)->where('kode', 3)->get();
                                        @endphp
                                        @foreach ($posting as $item_3)
                                        <div class="image">
                                            <div class="col-6 img-thumbnail img img-responsive full-height">
                                                <a href="{{ Request::root() }}/upload-foto-lpj/{{ $item_3->foto }}" target="_blank">
                                                    <img src="{{ Request::root() }}/upload-foto-lpj/{{ $item_3->foto }}" alt="" class="img-fluid border">
                                                </a>
                                            </div>
                                        </div>
                                        @endforeach
                                        <div class="col-12 text-center font-weight-bold">
                                            Posting flyer kegiatan di media sosial dealer
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="row p-2 border rounded">
                                        @php
                                            $interaksi = DB::table('lpj_dokumentasis')->where('id_lpj', $data->id)->where('kode', 4)->get();
                                        @endphp
                                        @foreach ($interaksi as $item_4)
                                        <div class="image">
                                            <div class="col-6 img-thumbnail img img-responsive full-height">
                                                <a href="{{ Request::root() }}/upload-foto-lpj/{{ $item_4->foto }}" target="_blank">
                                                    <img src="{{ Request::root() }}/upload-foto-lpj/{{ $item_4->foto }}" alt="" class="img-fluid border">
                                                </a>
                                            </div>
                                        </div>
                                        @endforeach
                                        <div class="col-12 text-center font-weight-bold">
                                            Interaksi konsumen
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="row p-2 border rounded">
                                        @php
                                            $unit = DB::table('lpj_dokumentasis')->where('id_lpj', $data->id)->where('kode', 5)->get();
                                        @endphp
                                        @foreach ($unit as $item_5)
                                        <div class="image">
                                            <div class="col-6 img-thumbnail img img-responsive full-height">
                                                <a href="{{ Request::root() }}/upload-foto-lpj/{{ $item_5->foto }}" target="_blank">
                                                    <img src="{{ Request::root() }}/upload-foto-lpj/{{ $item_5->foto }}" alt="" class="img-fluid border">
                                                </a>
                                            </div>
                                        </div>
                                        @endforeach
                                        <div class="col-12 text-center font-weight-bold">
                                            Unit Display
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="row p-2 border rounded">
                                        @php
                                            $live = DB::table('lpj_dokumentasis')->where('id_lpj', $data->id)->where('kode', 6)->get();
                                        @endphp
                                        @foreach ($live as $item_6)
                                        <div class="image">
                                            <div class="col-6 img-thumbnail img img-responsive full-height">
                                                <a href="{{ Request::root() }}/upload-foto-lpj/{{ $item_6->foto }}" target="_blank">
                                                    <img src="{{ Request::root() }}/upload-foto-lpj/{{ $item_6->foto }}" alt="" class="img-fluid border">
                                                </a>
                                            </div>
                                        </div>
                                        @endforeach
                                        <div class="col-12 text-center font-weight-bold">
                                            Live Season saat kegiatan berlangsung
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-2 row">
                                <label class="col-sm-2 col-form-label"><strong>Problem Identification</strong></label>
                                <div class="col-sm-10">
                                    <textarea class="form-control" name="problem" id="" rows="2" readonly>{{ $data->problem_identification_lpj }}</textarea>
                                </div>
                            </div>
                            <div class="mb-2 row">
                                <label class="col-sm-2 col-form-label"><strong>Corrective Action</strong></label>
                                <div class="col-sm-10">
                                    <textarea class="form-control" name="corrective" id="" rows="2" readonly>{{ $data->corrective_action_lpj }}</textarea>
                                </div>
                                <div class="col-12">
                                </div>
                            </div>
                            <hr>
                            @if($data->proposal->kategori->id == 5)
                            <div class="row">

                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <h4>Unit Entry :</h4>
                                            <hr>
                                            <form id="unitentryForm">
                                            
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>Nama</th>
                                                            <th>Target</th>
                                                            <th>Aktual</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>KPB 1</td>
                                                            <td><input class="form-control" type="number" name="kpb1" value="0" readonly></td>
                                                            <td><input class="form-control" type="number" name="kpb1_act" value="0" readonly></td>
                                                        </tr>
                                                        <tr>
                                                            <td>KPB 2</td>
                                                            <td><input class="form-control" type="number" name="kpb2" value="0" readonly></td>
                                                            <td><input class="form-control" type="number" name="kpb2_act" value="0" readonly></td>
                                                        </tr>
                                                        <tr>
                                                            <td>KPB 3</td>
                                                            <td><input class="form-control" type="number" name="kpb3" value="0" readonly></td>
                                                            <td><input class="form-control" type="number" name="kpb3_act" value="0" readonly></td>
                                                        </tr>
                                                        <tr>
                                                            <td>KPB 4</td>
                                                            <td><input class="form-control" type="number" name="kpb4" value="0" readonly></td>
                                                            <td><input class="form-control" type="number" name="kpb4_act" value="0" readonly></td>
                                                        </tr>
                                                        <tr>
                                                            <td>PSL</td>
                                                            <td><input class="form-control" type="number" name="psl" value="0" readonly></td>
                                                            <td><input class="form-control" type="number" name="psl_act" value="0" readonly></td>
                                                        </tr>
                                                        <tr>
                                                            <td>PSR</td>
                                                            <td><input class="form-control" type="number" name="psr" value="0" readonly></td>
                                                            <td><input class="form-control" type="number" name="psr_act" value="0" readonly></td>
                                                        </tr>
                                                        <tr>
                                                            <td>GO+</td>
                                                            <td><input class="form-control" type="number" name="go" value="0" readonly></td>
                                                            <td><input class="form-control" type="number" name="go_act" value="0" readonly></td>
                                                        </tr>
                                                        <tr>
                                                            <td>LR</td>
                                                            <td><input class="form-control" type="number" name="lr" value="0" readonly></td>
                                                            <td><input class="form-control" type="number" name="lr_act" value="0" readonly></td>
                                                        </tr>
                                                        <tr>
                                                            <td><b>Total</b></td>
                                                            <td><input class="form-control" type="number" name="unittotal" value="0" readonly></td>
                                                            <td><input class="form-control" type="number" name="unittotal_act" value="0" readonly></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                {{-- <div>
                                                    <button class="btn btn-xs btn-success" type="button" id="simpanunitentry" style="margin-top:10px">Simpan</button>
                                                </div> --}}
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <h4>Revenue :</h4>
                                            <hr>
                                            <form id="revenueForm">
                                                <div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <label for="jasa">Jasa Target:</label>
                                                            <div class="input-group mb-3">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text">Rp.</span>
                                                                </div>
                                                                <input class="form-control" type="number" min="0" id="jasa" name="jasa" readonly />
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label for="jasa">Jasa Aktual:</label>
                                                            <div class="input-group mb-3">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text">Rp.</span>
                                                                </div>
                                                                <input class="form-control" type="number" min="0" id="jasa_act" name="jasa_act" readonly/>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div style="margin-top: 5px;">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <label for="part">Part Target:</label>
                                                            <div class="input-group mb-3">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text">Rp.</span>
                                                                </div>
                                                                <input class="form-control" type="number" min="0" id="part" name="part" readonly />
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label for="part">Part Aktual:</label>
                                                            <div class="input-group mb-3">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text">Rp.</span>
                                                                </div>
                                                                <input class="form-control" type="number" min="0" id="part_act" name="part_act" readonly/>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div style="margin-top: 5px;">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <label for="oli">Oli Target:</label>
                                                            <div class="input-group mb-3">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text">Rp.</span>
                                                                </div>
                                                                <input class="form-control" type="number" min="0" id="oli" name="oli" readonly />
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label for="oli">Oli Aktual:</label>
                                                            <div class="input-group mb-3">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text">Rp.</span>
                                                                </div>
                                                                <input class="form-control" type="number" min="0" id="oli_act" name="oli_act" readonly/>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div style="margin-top: 5px;">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <label for="total">Total Target:</label>
                                                            <div class="input-group mb-3">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text">Rp.</span>
                                                                </div>
                                                                <input class="form-control" type="number" min="0" id="total" name="total" readonly />
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label for="total">Total Aktual:</label>
                                                            <div class="input-group mb-3">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text">Rp.</span>
                                                                </div>
                                                                <input class="form-control" type="number" min="0" id="total_act" name="total_act" readonly />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                {{-- <div>
                                                    <button class="btn btn-xs btn-success" type="button" id="simpanrevenue" style="margin-top:10px">Simpan</button>
                                                </div> --}}
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @endif
                            <hr>
                            <div class="mb-2 row">
                                <label class="col-sm-2 col-form-label">
                                    <strong>Konsumen</strong>
                                </label>
                                <div class="col-sm-12">
                                    <div class="row pt-3" style="font-size: 11px">
                                        <div class="col-12">
                                            <table class="table datatable" id="tablekonsumen" style="width:100%">
                                                <thead>
                                                    <tr class="text-center">
                                                        <th>No</th>
                                                        <th>Nama</th>
                                                        <th>Gender</th>
                                                        <th>Tgl Lahir</th>
                                                        <th>Usia</th>
                                                        <th>Alamat</th>
                                                        {{-- <th>No Telepon</th> --}}
                                                        <th>Pekerjaan</th>
                                                        <th>Pengeluaran</th>
                                                        <th>Range DP</th>
                                                        <th>Merk Motor Sblmnya</th>
                                                        <th>Jenis Motor Sblmnya</th>
                                                        <th>Kelurahan</th>
                                                        <th>Kecamatan</th>
                                                        <th>Kota</th>
                                                        <th>Nomor Mesin</th>
                                                        <th>Type Unit</th>
                                                        <th>Sales Prople</th>
                                                        <th>CASH/CREDIT</th>
                                                        <th>Finance Company</th>
                                                        <th>Hasil</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php
                                                        $no = 1;
                                                    @endphp
                                                    @foreach ($datakonsumen as $data_ko)
                                                        <tr class="text-center">
                                                            <td>{{ $no++}}</td>
                                                            <td>{{ $data_ko->nama }}</td>
                                                            <td>{{ $data_ko->gender_($data_ko->gender) }}</td>
                                                            <td>{{ $data_ko->tgl_lahir }}</td>
                                                            <td>{{ \Carbon\Carbon::parse($data_ko->tgl_lahir)->age }} Tahun</td>
                                                            <td>{{ $data_ko->alamat }}</td>
                                                            {{-- <td>{{ $data_ko->notelp }}</td> --}}
                                                            <td>{{ $data_ko->pekerjaan_($data_ko->pekerjaan) }}</td>
                                                            <td>{{ $data_ko->pengeluaran_($data_ko->pengeluaran) }}</td>
                                                            <td>{{ $data_ko->dp_($data_ko->dp) }}</td>
                                                            <td>{{ $data_ko->merkMotor_($data_ko->merkmotor) }}</td>
                                                            <td>{{ $data_ko->jenisMotor_($data_ko->jenismotor) }}</td>
                                                            <td>{{ $data_ko->lokasi_->kelurahan_lokasi }}</td>
                                                            <td>{{ $data_ko->lokasi_->kecamatan_lokasi }}</td>
                                                            <td>{{ $data_ko->lokasi_->kota_lokasi }}</td>
                                                            <td>{{ $data_ko->nomor_mesin }}</td>
                                                            <td>{{ null != $data_ko->unit ? $data_ko->display->nama_display : '' }}</td>
                                                            <td>{{ $data_ko->sales->nama_sales_people }}</td>
                                                            <td>{{ $data_ko->jenis($data_ko->cash_credit)}}</td>
                                                            <td>{{ $data_ko->finance ? $data_ko->finance->nama : '-' }}</td>
                                                            <td>{{ $data_ko->hasil_($data_ko->hasil) }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-4 row p-4 border-top" style="background-color: #fff; ">
                                <div class="col-12 mb-4">
                                    <div class="mb-2 row">
                                        <label class="col-sm-2 col-form-label">History Approval:</label>
                                        <div class="col-sm-10">
                                            <table class="table table-sm text-center">
                                                <thead class="table-dark">
                                                    <tr>
                                                        <th>No.</th>
                                                        <th>Waktu</th>
                                                        <th>Created By</th>
                                                        <th></th>
                                                        <th>Status</th>
                                                        <th>Keterangan</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>1.</td>
                                                        <td>{{ $data->created_at }}</td>
                                                        {{-- <td>{{ $data->create_proposal == 1 ? $data->userpusat->name : $data->usercabang->name }}</td> --}}
                                                        <td>{{ $data->proposal->usercabang->name }}</td>
                                                        <td></td>
                                                        <td>
                                                            <label class="badge bg-info" style="color: #fff">Submit Proposal <i class="fas fa-check"></i> </label>
                                                        </td>
                                                        <td></td>
                                                    </tr>
                                                    @php
                                                        $n = 2;
                                                    @endphp
                                                    @foreach ($dataapproval as $data_h)
                                                        <tr>
                                                            <td>{{ $n++ }}</td>
                                                            <td>
                                                                @if (!null == $data_h->status_approval && $data_h->keterangan_approval != '-')
                                                                    {{ $data_h->updated_at }}
                                                                @endif
                                                            </td>
                                                            <td>{{ $data_h->userapp->name }}</td>
                                                            <td>{{ $data_h->userapp->jabatanpusat->nama_jabatan }}</td>
                                                            <td>
                                                                @php
                                                                    if ($data_h->status_approval == 1) {
                                                                        $st = 'Approve';
                                                                        $si = 'info';
                                                                        $ic = 'check';
                                                                    } elseif ($data_h->status_approval == 2) {
                                                                        $st = 'Revise';
                                                                        $si = 'warning';
                                                                        $ic = 'exclamation-triangle';
                                                                    } elseif ($data_h->status_approval == 3) {
                                                                        $st = 'Rejected';
                                                                        $si = 'danger';
                                                                        $ic = 'times';
                                                                    } else {
                                                                        $st = '';
                                                                        $si = '';
                                                                        $ic = '';
                                                                    }
                                                                @endphp
                                                                @if (!null == $data_h->status_approval)
                                                                    <label class="badge bg-{{ $si }}"  style="color: #fff">{{ $st }} <i class="fas fa-{{ $ic }}"></i></label>
                                                                @endif
                                                            </td>
                                                            <td>{{ $data_h->keterangan_approval }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div><!--col-md-10-->
        </div><!--row-->
    </div><!--container-->
    {{-- <script type="text/javascript">
        $('.data-lokasi').select2();
    </script>
    <script type="text/javascript">
        $('.data-sales').select2();
    </script> --}}
    <input type="hidden" name="id" value="{{ request()->id }}">
    <script>
        $(document).ready(function() {
        
            var id = $('input[name="id"]').val();
        
            $.ajax({
                url: '/cabang/getunitentrylpj',
                type: 'POST',
                data: {id: id,_token: $('input[name="_token"]').val()},
                success: function(response) {
                    if(response.status == "success") {
                        var items = response.data
                        var kpb1_act = $('input[name="kpb1_act"]').val(items[0].unit_jumlahact);
                        var kpb2_act = $('input[name="kpb2_act"]').val(items[1].unit_jumlahact);
                        var kpb3_act = $('input[name="kpb3_act"]').val(items[2].unit_jumlahact);
                        var kpb4_act = $('input[name="kpb4_act"]').val(items[3].unit_jumlahact);
                        var psl_act = $('input[name="psl_act"]').val(items[4].unit_jumlahact);
                        var psr_act = $('input[name="psr_act"]').val(items[5].unit_jumlahact);
                        var go_act = $('input[name="go_act"]').val(items[6].unit_jumlahact);
                        var lr_act = $('input[name="lr_act"]').val(items[7].unit_jumlahact);
    
                        var kpb1 = $('input[name="kpb1"]').val(items[0].unit_jumlah);
                        var kpb2 = $('input[name="kpb2"]').val(items[1].unit_jumlah);
                        var kpb3 = $('input[name="kpb3"]').val(items[2].unit_jumlah);
                        var kpb4 = $('input[name="kpb4"]').val(items[3].unit_jumlah);
                        var psl = $('input[name="psl"]').val(items[4].unit_jumlah);
                        var psr = $('input[name="psr"]').val(items[5].unit_jumlah);
                        var go = $('input[name="go"]').val(items[6].unit_jumlah);
                        var lr = $('input[name="lr"]').val(items[7].unit_jumlah);
        
                        var unittotal = items[0].unit_jumlah + items[1].unit_jumlah + items[2].unit_jumlah + items[3].unit_jumlah + items[4].unit_jumlah + items[5].unit_jumlah + items[6].unit_jumlah + items[7].unit_jumlah;
                        var unittotal_act = items[0].unit_jumlahact + items[1].unit_jumlahact + items[2].unit_jumlahact + items[3].unit_jumlahact + items[4].unit_jumlahact + items[5].unit_jumlahact + items[6].unit_jumlahact + items[7].unit_jumlahact;
        
                        $('input[name="unittotal"]').val(unittotal);
                        $('input[name="unittotal_act"]').val(unittotal_act);
    
                        // console.log(items)
                    }
                },
                error: function(xhr, status, error) {
                    if(response.status == "error") {
                        alert(response.message)
                    }
                }
            });
        
            $('input[name="kpb1_act"], input[name="kpb2_act"], input[name="kpb3_act"], input[name="kpb4_act"], input[name="psl_act"], input[name="psr_act"], input[name="go_act"], input[name="lr_act"]').on('input', function() {
                var kpb1_act = parseInt($('input[name="kpb1_act"]').val()) || 0;
                var kpb2_act = parseInt($('input[name="kpb2_act"]').val()) || 0;
                var kpb3_act = parseInt($('input[name="kpb3_act"]').val()) || 0;
                var kpb4_act = parseInt($('input[name="kpb4_act"]').val()) || 0;
                var psl_act = parseInt($('input[name="psl_act"]').val()) || 0;
                var psr_act = parseInt($('input[name="psr_act"]').val()) || 0;
                var go_act = parseInt($('input[name="go_act"]').val()) || 0;
                var lr_act = parseInt($('input[name="lr_act"]').val()) || 0;
    
                var unittotal_act = kpb1_act + kpb2_act + kpb3_act + kpb4_act + psl_act + psr_act + go_act + lr_act;
        
                $('input[name="unittotal_act"]').val(unittotal_act);
            })
        
        })
    </script>
    <script>
    $(document).ready(function() {
    
        $('#jasa_act').val(0);
        $('#part_act').val(0);
        $('#oli_act').val(0);
    
        var id = $('input[name="id"]').val();
    
        $.ajax({
            url: '/cabang/getrevenuelpj',
            type: 'POST',
            data: {id: id,_token: $('input[name="_token"]').val()},
            success: function(response) {
                if(response.status == "success") {
                    var items = response.data
                    $('#jasa').val(items.jasa);
                    $('#part').val(items.part);
                    $('#oli').val(items.oli);
                    $('#jasa_act').val(items.jasa_act);
                    $('#part_act').val(items.part_act);
                    $('#oli_act').val(items.oli_act);
                    var total = items.jasa + items.part + items.oli;
                    var totalact = items.jasa_act + items.part_act + items.oli_act;
    
                    $('#total').val(total);
                    $('#total_act').val(totalact);
                }
            },
            error: function(xhr, status, error) {
                if(response.status == "error") {
                    alert(response.message)
                }
            }
        });
    
        $('#jasa_act, #part_act, #oli_act').on('input', function() {
            var jasaact = parseInt($('#jasa_act').val()) || 0;
            var partact = parseInt($('#part_act').val()) || 0;
            var oliact = parseInt($('#oli_act').val()) || 0;
    
            var totalact = jasaact + partact + oliact;
    
            $('#total_act').val(totalact);
        })
    
    })
    </script>

    <script>
        $(document).ready( function () {
            $('#tablekonsumen').DataTable({
                    scrollX: true,
                    dom: 'Blfrtip',
                    buttons: [{
                            extend: 'excelHtml5',
                            autoFilter: true,
                            sheetName: 'Data Konsumen',
                            text: 'Download excel',
                            messageTop: 'LPJ Data Konsumen',
                    }]
                });
        });
    </script>

@endsection
