@extends('cabang.layouts.upload-konsumen')

@section('title', 'Buat LPJ | Upload Data Konsumen')
@section('content')
    <style>
        .glyphicon-ok::before {
        content: "\f00c";
        }

        .glyphicon-remove::before {
        content: "\f00d";
        }

        .glyphicon {
        font-family: 'FontAwesome';
        font-style: normal;
        }

        .{
            border-bottom: 1px dashed #849cae;
        }

        .editable-popup {
            transform: translate3d(500px, 350px, 0px);
        }
    </style>
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            // $.fn.editable.defaults.mode = 'inline';
        });
    </script>

    <div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="float-left">
                                    <h5>Upload Konsumen</h5>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="mb-2 row">
                            <label class="col-sm-4 col-form-label">
                                <strong> Pilih File Data Konsumen</strong>
                                <div class="text-muted text-uppercase">
                                    Maksimal <strong>50</strong> Data Dalam 1 Kali Upload
                                </div>
                            </label>
                            <div class="col-sm-4">
                                <div class="mb-2 row">
                                    <div class="col-12">
                                        <input name="filedata" class="form-control" type="file" placeholder="Pilih File">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row fixed-bottom position-sticky" style="background-color: #fff; ">
                            <div class="col-12">
                                <hr>
                                <a href="{{ route('cabang.lpj.getCreateFive') }}?id={{ request()->id }}" class="btn btn-outline-secondary">Cancel</a>
                                <div class="float-right">
                                    <a href="{{ route('cabang.lpj.getCreateFiveUpload') }}?id={{ request()->id }}" class="btn btn-primary" type="text" name="submit" value="submit"  onclick="return confirm('Konfirmasi')">Upload</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!--col-md-10-->
        </div><!--row-->

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row p-2">
                            <div class="col text-right">
                                <div class="text-muted font-italic">
                                    *Klik Tulisan yang bergaris putus-putus untuk merubah Data
                                </div>
                            </div>
                        </div>
                        @php
                            $no = 1;
                        @endphp
                        @foreach ($datakonsumen as $data_ko)
                            <div class="row p-2 mb-2 mr-1 ml-1" style="font-size:11px; border: 1px solid; border-radius: 0.25rem; border-color: #d8dbe0;">
                                {{ $no++}}
                                <div class="col-md">
                                    <div class="row">
                                        <div class="col-4">Nama</div>
                                        <div class="col-1 pr-0 pl-0">:</div>
                                        <div class="col pl-0">
                                            <i class="fa fa-pen" style="color: #d8dbe0"></i>
                                            <span href="#" class="font-weight-bold nama{{ $no }}"
                                                data-title="{{ $no }} - Nama Konsumen" data-name="1" data-placement="top"  data-pk="{{ $data_ko->id }}" data-url="{{ route('cabang.lpj.postCreateFiveUploadData') }}">
                                                {{ $data_ko->nama }}
                                            </span>
                                        </div>
                                        <script>$(document).ready(function() { $('.nama{{ $no }}').editable();});</script>
                                    </div>
                                    <div class="row">
                                        <div class="col-4">Gender</div>
                                        <div class="col-1 pr-0 pl-0">:</div>
                                        <div class="col pl-0">
                                            <i class="fa fa-pen" style="color: #d8dbe0"></i>
                                            <span href="#" class="font-weight-bold gender{{ $no }}"
                                                data-type="select" data-title="{{ $no }} - Gender Konsumen" data-name="2" data-placement="top"  data-pk="{{ $data_ko->id }}" data-url="{{ route('cabang.lpj.postCreateFiveUploadData') }}">
                                                {{ $data_ko->gender_($data_ko->gender) }}
                                                </span>
                                        </div>
                                        <script>$(document).ready(function() { $('.gender{{ $no }}').editable({value:{{ $data_ko->gender }},source: [ {value: 1, text: 'Laki-laki'}, {value: 2, text: 'Perempuan'} ],});});</script>
                                    </div>
                                    <div class="row">
                                        <div class="col-4">Tanggal Lahir</div>
                                        <div class="col-1 pr-0 pl-0">:</div>
                                        <div class="col pl-0">
                                            <i class="fa fa-pen" style="color: #d8dbe0"></i>
                                            <span href="#" class="font-weight-bold tgllahir{{ $no }}"
                                                data-type="date" data-title="{{ $no }} - Tanggal Lahir Konsumen" data-name="3" data-placement="top"  data-pk="{{ $data_ko->id }}" data-url="{{ route('cabang.lpj.postCreateFiveUploadData') }}">
                                                {{ $data_ko->tgl_lahir }}
                                                </span>
                                        </div>
                                        <script>$(document).ready(function() { $('.tgllahir{{ $no }}').editable({format: 'mm/dd/yyyy', });});</script>
                                    </div>
                                    <div class="row">
                                        <div class="col-4">Alamat</div>
                                        <div class="col-1 pr-0 pl-0">:</div>
                                        <div class="col pl-0">
                                            <i class="fa fa-pen" style="color: #d8dbe0"></i>
                                            <span href="#" class="font-weight-bold alamat{{ $no }}"
                                                data-title="{{ $no }} - Alamat Konsumen" data-name="4" data-placement="top"  data-pk="{{ $data_ko->id }}" data-url="{{ route('cabang.lpj.postCreateFiveUploadData') }}">
                                                {{ $data_ko->alamat }}
                                                </span>
                                        </div>
                                        <script>$(document).ready(function() { $('.alamat{{ $no }}').editable();});</script>
                                    </div>
                                    <div class="row">
                                        <div class="col-4">No. Telepon</div>
                                        <div class="col-1 pr-0 pl-0">:</div>
                                        <div class="col pl-0">
                                            <i class="fa fa-pen" style="color: #d8dbe0"></i>
                                            <span href="#" class="font-weight-bold notelp{{ $no }}"
                                                data-title="{{ $no }} - No. Telepon Konsumen" data-name="5" data-placement="top"  data-pk="{{ $data_ko->id }}" data-url="{{ route('cabang.lpj.postCreateFiveUploadData') }}">
                                                {{ $data_ko->notelp }}
                                                </span>
                                        </div>
                                        <script>$(document).ready(function() { $('.notelp{{ $no }}').editable();});</script>
                                    </div>
                                    <div class="row">
                                        <div class="col-4">Pekerjaan</div>
                                        <div class="col-1 pr-0 pl-0">:</div>
                                        <div class="col pl-0">
                                            <i class="fa fa-pen" style="color: #d8dbe0"></i>
                                            <span href="#" class="font-weight-bold pekerjaan{{ $no }}"
                                                data-type="select" data-title="{{ $no }} - Pekerjaan Konsumen" data-name="6" data-placement="top"  data-pk="{{ $data_ko->id }}" data-url="{{ route('cabang.lpj.postCreateFiveUploadData') }}">
                                                {{ $data_ko->pekerjaan_($data_ko->pekerjaan) }}
                                                </span>
                                        </div>
                                        <script>$(document).ready(function() { $('.pekerjaan{{ $no }}').editable({value: {{ $data_ko->pekerjaan }}, source: [ @foreach ($k_pekerjaan as $k_p) { value: {{ data_get($k_p, 'value') }}, text: '{{ data_get($k_p, "text") }}' }, @endforeach ], });});</script>
                                    </div>
                                </div>
                                <div class="col-md">
                                    <div class="row">
                                        <div class="col-5">Pengeluaran</div>
                                        <div class="col-1 pr-0 pl-0">:</div>
                                        <div class="col pl-0">
                                            <i class="fa fa-pen" style="color: #d8dbe0"></i>
                                            <span href="#" class="font-weight-bold pengeluaran{{ $no }}"
                                                data-type="select" data-title="{{ $no }} - Pengeluaran Konsumen" data-name="7" data-placement="top"  data-pk="{{ $data_ko->id }}" data-url="{{ route('cabang.lpj.postCreateFiveUploadData') }}">
                                                {{ $data_ko->pengeluaran_($data_ko->pengeluaran) }}
                                                </span>
                                        </div>
                                        <script>$(document).ready(function() { $('.pengeluaran{{ $no }}').editable({value: {{ $data_ko->pengeluaran }}, source: [ @foreach ($k_pengeluaran as $k_pe) { value: {{ data_get($k_pe, 'value') }}, text: '{!! data_get($k_pe, "text") !!}' }, @endforeach ], });});</script>
                                    </div>
                                    <div class="row">
                                        <div class="col-5">Range DP</div>
                                        <div class="col-1 pr-0 pl-0">:</div>
                                        <div class="col pl-0">
                                            <i class="fa fa-pen" style="color: #d8dbe0"></i>
                                            <span href="#" class="font-weight-bold dp{{ $no }}"
                                                data-type="select" data-title="{{ $no }} - Range DP Konsumen" data-name="14" data-placement="top"  data-pk="{{ $data_ko->id }}" data-url="{{ route('cabang.lpj.postCreateFiveUploadData') }}">
                                                {{ $data_ko->dp_($data_ko->dp) }}
                                                </span>
                                        </div>
                                        <script>$(document).ready(function() { $('.dp{{ $no }}').editable({value: {{ $data_ko->dp}}, source: [ @foreach ($k_dp as $k_d) { value: {{ data_get($k_d, 'value') }}, text: '{!! data_get($k_d, "text") !!}' }, @endforeach ], });});</script>
                                    </div>
                                    <div class="row">
                                        <div class="col-5">Merk Motor Sblmnya</div>
                                        <div class="col-1 pr-0 pl-0">:</div>
                                        <div class="col pl-0">
                                            <i class="fa fa-pen" style="color: #d8dbe0"></i>
                                            <span href="#" class="font-weight-bold merkmotor{{ $no }}"
                                                data-type="select" data-title="{{ $no }} - Merk Motor Sblmnya Konsumen" data-name="8" data-placement="top"  data-pk="{{ $data_ko->id }}" data-url="{{ route('cabang.lpj.postCreateFiveUploadData') }}">
                                                {{ $data_ko->merkMotor_($data_ko->merkmotor) }}
                                                </span>
                                        </div>
                                        <script>$(document).ready(function() { $('.merkmotor{{ $no }}').editable({value: {{ $data_ko->merkmotor }}, source: [ @foreach ($k_merkmotor as $k_me) { value: {{ data_get($k_me, 'value') }}, text: '{{ data_get($k_me, "text") }}' }, @endforeach ], });});</script>
                                    </div>
                                    <div class="row">
                                        <div class="col-5">Jenis Motor Sblmnya</div>
                                        <div class="col-1 pr-0 pl-0">:</div>
                                        <div class="col pl-0">
                                            <i class="fa fa-pen" style="color: #d8dbe0"></i>
                                            <span href="#" class="font-weight-bold jenismotor{{ $no }}"
                                                data-type="select" data-title="{{ $no }} - Jenis Motor Sblmnya Konsumen" data-name="9" data-placement="top"  data-pk="{{ $data_ko->id }}" data-url="{{ route('cabang.lpj.postCreateFiveUploadData') }}">
                                                {{ $data_ko->jenisMotor_($data_ko->jenismotor) }}
                                                </span>
                                        </div>
                                        <script>$(document).ready(function() { $('.jenismotor{{ $no }}').editable({value: {{ $data_ko->jenismotor}}, source: [ @foreach ($k_jenismotor as $k_je) { value: {{ data_get($k_je, 'value') }}, text: '{{ data_get($k_je, "text") }}' }, @endforeach ], });});</script>
                                    </div>
                                    <div class="row">
                                        <div class="col-5">Lokasi</div>
                                        <div class="col-1 pr-0 pl-0">:</div>
                                        <div class="col pl-0">
                                            <i class="fa fa-pen" style="color: #d8dbe0"></i>
                                            <span href="#" class="font-weight-bold kota{{ $no }}"
                                                data-type="select2" data-value="{{ $data_ko->id }}" data-title="{{ $no }} - Pilih Nama Kelurahan" data-name="10" data-placement="top"  data-pk="{{ $data_ko->id }}" data-url="{{ route('cabang.lpj.postCreateFiveUploadData') }}" data-source="{{ route('cabang.lpj.getCreateFiveUploadDataLokasi') }}">
                                                {{ $data_ko->lokasi_->kota_lokasi }} - {{ $data_ko->lokasi_->kecamatan_lokasi }} - {{ $data_ko->lokasi_->kelurahan_lokasi }}
                                                </span>
                                        </div>
                                        <script>$(document).ready(function() { $('.kota{{ $no }}')
                                            .editable({
                                                select2: {
                                                    placeholder: 'Pilih Lokasi Kelurahan',
                                                    minimumInputLength: 3,
                                                    allowClear: true
                                                }
                                            });
                                        });</script>
                                    </div>
                                </div>
                                <div class="col-md">
                                    <div class="row">
                                        <div class="col-5">Nomor Mesin</div>
                                        <div class="col-1 pr-0 pl-0">:</div>
                                        <div class="col pl-0">
                                            <i class="fa fa-pen" style="color: #d8dbe0"></i>
                                            <span href="#" class="font-weight-bold mesin{{ $no }}"
                                                data-title="{{ $no }} - Nomor Mesin Konsumen" data-name="13" data-placement="top"  data-pk="{{ $data_ko->id }}" data-url="{{ route('cabang.lpj.postCreateFiveUploadData') }}">
                                                {{ $data_ko->nomor_mesin }}
                                                </span>
                                        </div>
                                        <script>$(document).ready(function() { $('.mesin{{ $no }}').editable();});</script>
                                    </div>
                                    <div class="row">
                                        <div class="col-5">Type Unit</div>
                                        <div class="col-1 pr-0 pl-0">:</div>
                                        <div class="col pl-0">
                                            <i class="fa fa-pen" style="color: #d8dbe0"></i>
                                            <span href="#" class="font-weight-bold unit{{ $no }}"
                                                data-type="select" data-title="{{ $no }} - Type Unit Konsumen" data-name="15" data-placement="top"  data-pk="{{ $data_ko->id }}" data-url="{{ route('cabang.lpj.postCreateFiveUploadData') }}">
                                                {{ null != $data_ko->unit ? $data_ko->display->nama_display : '' }}
                                                </span>
                                        </div>
                                        <script>$(document).ready(function() { $('.unit{{ $no }}').editable({value: {{ $data_ko->display->id }}, source: [ @foreach ($datadisplay as $k_di) { value: {!! $k_di->id !!}, text: '{!! $k_di->nama_display !!}' }, @endforeach ], });});</script>
                                    </div>
                                    <div class="row">
                                        <div class="col-5">Sales People</div>
                                        <div class="col-1 pr-0 pl-0">:</div>
                                        <div class="col pl-0">
                                            <i class="fa fa-pen" style="color: #d8dbe0"></i>
                                            <span href="#" class="font-weight-bold sales{{ $no }}"
                                                data-type="select" data-title="{{ $no }} - Sales People Konsumen" data-name="16" data-placement="top"  data-pk="{{ $data_ko->id }}" data-url="{{ route('cabang.lpj.postCreateFiveUploadData') }}">
                                                {{ $data_ko->sales->nama_sales_people }}
                                                </span>
                                        </div>
                                        <script>$(document).ready(function() { $('.sales{{ $no }}').editable({value: {{ $data_ko->id_sales_people }}, source: [ @foreach ($salespeople as $k_sp) { value: {!! $k_sp->id !!}, text: '{!! $k_sp->nama_sales_people !!}' }, @endforeach ], });});</script>
                                    </div>
                                    <div class="row">
                                        <div class="col-5">CASH/CREDIT</div>
                                        <div class="col-1 pr-0 pl-0">:</div>
                                        <div class="col pl-0">
                                            <i class="fa fa-pen" style="color: #d8dbe0"></i>
                                            <span href="#" class="font-weight-bold jenis{{ $no }}"
                                                data-type="select" data-title="{{ $no }} - CASH/CREDIT Konsumen" data-name="17" data-placement="top"  data-pk="{{ $data_ko->id }}" data-url="{{ route('cabang.lpj.postCreateFiveUploadData') }}">
                                                {{ $data_ko->jenis($data_ko->cash_credit)}}
                                                </span>
                                        </div>
                                        <script>$(document).ready(function() { $('.jenis{{ $no }}').editable({value:{{ $data_ko->cash_credit }},source: [ {value: 1, text: 'CASH'}, {value: 2, text: 'CREDIT'} ],});});</script>
                                    </div>
                                    <div class="row">
                                        <div class="col-5">Finance Company</div>
                                        <div class="col-1 pr-0 pl-0">:</div>
                                        <div class="col pl-0">
                                            <i class="fa fa-pen" style="color: #d8dbe0"></i>
                                            <span href="#" class="font-weight-bold finance{{ $no }}"
                                                data-type="select" data-title="{{ $no }} - Finance Company Konsumen" data-name="18" data-placement="top"  data-pk="{{ $data_ko->id }}" data-url="{{ route('cabang.lpj.postCreateFiveUploadData') }}">
                                                {{ $data_ko->finance ? $data_ko->finance->kode : '-' }}
                                                </span>
                                        </div>
                                        <script>$(document).ready(function() { $('.finance{{ $no }}').editable({value: {{ $data_ko->finance->id }}, source: [ @foreach ($datafinance as $k_df) { value: {!! $k_df->id !!}, text: '{!! $k_df->kode !!}' }, @endforeach ], });});</script>
                                    </div>
                                    <div class="row">
                                        <div class="col-5">Hasil</div>
                                        <div class="col-1 pr-0 pl-0">:</div>
                                        <div class="col pl-0">
                                            <i class="fa fa-pen" style="color: #d8dbe0"></i>
                                            <span href="#" class="font-weight-bold hasil{{ $no }}"
                                                data-type="select" data-title="{{ $no }} - Hasil Konsumen" data-name="19" data-placement="top"  data-pk="{{ $data_ko->id }}" data-url="{{ route('cabang.lpj.postCreateFiveUploadData') }}">
                                                {{ $data_ko->hasil_($data_ko->hasil) }}
                                                </span>
                                        </div>
                                        <script>$(document).ready(function() { $('.hasil{{ $no }}').editable({value: {{ $data_ko->hasil}}, source: [ @foreach ($k_hasil as $k_ha) { value: {{ data_get($k_ha, 'value') }}, text: '{{ data_get($k_ha, "text") }}' }, @endforeach ], });});</script>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <a onclick="return confirm('Konfirmasi Hapus Konsumen')" href="{{ route('cabang.lpj.getCreateFive') }}/?method=hapuskonsumen&id={{ $data_ko->id }}" class="btn btn-danger delete-post btn-sm m-0" style="padding: 0.15rem 0.3rem; font-size: 0.7rem;">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div><!--col-md-10-->
        </div><!--row-->
    </div><!--container-->
@endsection
