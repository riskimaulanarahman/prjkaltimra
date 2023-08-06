@extends('cabang.layouts.cabang')

@section('title', 'Buat LPJ')
@section('content')

<style>
    .table td {
        vertical-align: baseline;
    }
</style>
    <div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <form id="form2" class="card-body" action="">
                        @csrf
                        <input type="hidden" name="id" value="{{ request()->id }}">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="float-left">
                                        <h5>Form LPJ</h5>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-12">
                                    <table class="table table-striped border datatable table-sm table-bordered">
                                        <thead class="text-center">
                                            <tr>
                                                <th colspan="2">Database</th>
                                                <th colspan="2">Prospecting</th>
                                                <th colspan="2">Penjualan</th>
                                                <th colspan="2">Downloader Motorku-X</th>
                                                <th colspan="2">Biaya</th>
                                            </tr>
                                            <tr>
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
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <input class="form-control" type="number"  value="{{ $data->proposal->target_database_proposal }}" disabled>
                                                </td>
                                                <td>
                                                    <input class="form-control" type="number" name="database" value="{{ $data->target_database_lpj }}" required>
                                                </td>
                                                <td>
                                                    <input class="form-control" type="number"  value="{{ $data->proposal->target_prospectus_proposal }}" disabled>
                                                </td>
                                                <td>
                                                    <input class="form-control" type="number" name="prospectus" value="{{ $data->target_prospectus_lpj }}" required>
                                                </td>
                                                <td>
                                                    <input class="form-control" type="number"  value="{{ $data->proposal->target_penjualan_proposal }}" disabled>
                                                </td>
                                                <td>
                                                    <input class="form-control" type="number" name="penjualan" value="{{ $data->target_penjualan_lpj }}" required>
                                                </td>
                                                <td>
                                                    <input class="form-control" type="number"  value="{{ $data->proposal->target_downloader_proposal }}" disabled>
                                                </td>
                                                <td>
                                                    <input class="form-control" type="number" name="downloader" value="{{ $data->target_downloader_lpj }}" required>
                                                </td>
                                                <td>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                        <span class="input-group-text" id="basic-addon1">Rp. </span>
                                                        </div>
                                                        <input class="form-control" value="{{ number_format($data->proposal->total_dana_proposal,0,',',','); }}" disabled>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                        <span class="input-group-text" id="basic-addon1">Rp. </span>
                                                        </div>
                                                        <input class="form-control" name="totaldana" value="{{ $data->total_dana_lpj }}" required>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <input type="hidden" id="kategoriform" value="{{$data->proposal->kategori->id}}">
                                @if($data->proposal->kategori->id == 5)
                                
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
                                                            <td><input class="form-control" type="number" name="kpb1_act" value="0"></td>
                                                        </tr>
                                                        <tr>
                                                            <td>KPB 2</td>
                                                            <td><input class="form-control" type="number" name="kpb2" value="0" readonly></td>
                                                            <td><input class="form-control" type="number" name="kpb2_act" value="0"></td>
                                                        </tr>
                                                        <tr>
                                                            <td>KPB 3</td>
                                                            <td><input class="form-control" type="number" name="kpb3" value="0" readonly></td>
                                                            <td><input class="form-control" type="number" name="kpb3_act" value="0"></td>
                                                        </tr>
                                                        <tr>
                                                            <td>KPB 4</td>
                                                            <td><input class="form-control" type="number" name="kpb4" value="0" readonly></td>
                                                            <td><input class="form-control" type="number" name="kpb4_act" value="0"></td>
                                                        </tr>
                                                        <tr>
                                                            <td>PSL</td>
                                                            <td><input class="form-control" type="number" name="psl" value="0" readonly></td>
                                                            <td><input class="form-control" type="number" name="psl_act" value="0"></td>
                                                        </tr>
                                                        <tr>
                                                            <td>PSR</td>
                                                            <td><input class="form-control" type="number" name="psr" value="0" readonly></td>
                                                            <td><input class="form-control" type="number" name="psr_act" value="0"></td>
                                                        </tr>
                                                        <tr>
                                                            <td>GO+</td>
                                                            <td><input class="form-control" type="number" name="go" value="0" readonly></td>
                                                            <td><input class="form-control" type="number" name="go_act" value="0"></td>
                                                        </tr>
                                                        <tr>
                                                            <td>LR</td>
                                                            <td><input class="form-control" type="number" name="lr" value="0" readonly></td>
                                                            <td><input class="form-control" type="number" name="lr_act" value="0"></td>
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
                                                                <input class="form-control" type="number" id="jasa_act" name="jasa_act"/>
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
                                                                <input class="form-control" type="number" min="0" id="part_act" name="part_act" />
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
                                                                <input class="form-control" type="number" min="0" id="oli_act" name="oli_act" />
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

                                @endif
                            </div>

                            <div class="mb-2 row fixed-bottom position-sticky p-4 border-top" style="background-color: #fff; ">
                                <div class="col-12">
                                    <a href="{{ route('cabang.lpj.getCreateOne') }}?id={{ request()->id }}" class="btn btn-outline-secondary">Kembali</a>
                                    <div class="float-right">
                                        <button class="btn btn-outline-info savethisdraft" type="text" name="submit" value="draft">Simpan Sebagai Draft</button>
                                        {{-- <button class="btn btn-primary savethis" type="button" name="submit" value="submit" onclick="konfirmasi();">Next</button> --}}
                                        <button class="btn btn-primary savethis" type="text" name="submit" value="submit" onclick="return confirm('Konfirmasi')">Next</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div><!--col-md-10-->
            
        </div><!--row-->
    </div><!--container-->

@endsection
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {

        $('.savethisdraft, .savethis').on('click',function(){
            simpanunitentry();
            simpanrevenue();
        })
    
        var id = $('input[name="id"]').val();
    
        $.ajax({
            url: '/cabang/getunitentrylpj',
            type: 'POST',
            data: {id: id,_token: $('input[name="_token"]').val()},
            success: function(response) {
                if(response.status == "success") {
                    var items = response.data
                    console.log(items)

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
            nexbutton();

        })
    

        function simpanunitentry() {

            
            var id = $('input[name="id"]').val();

            var kpb1_act = $('input[name="kpb1_act"]').val() || 0;
            var kpb2_act = $('input[name="kpb2_act"]').val() || 0;
            var kpb3_act = $('input[name="kpb3_act"]').val() || 0;
            var kpb4_act = $('input[name="kpb4_act"]').val() || 0;
            var psl_act = $('input[name="psl_act"]').val() || 0;
            var psr_act = $('input[name="psr_act"]').val() || 0;
            var go_act = $('input[name="go_act"]').val() || 0;
            var lr_act = $('input[name="lr_act"]').val() || 0;
    
            var formData = {
                id: id,
                kpb1_act: parseInt(kpb1_act),
                kpb2_act: parseInt(kpb2_act),
                kpb3_act: parseInt(kpb3_act),
                kpb4_act: parseInt(kpb4_act),
                psl_act: parseInt(psl_act),
                psr_act: parseInt(psr_act),
                go_act: parseInt(go_act),
                lr_act: parseInt(lr_act),
                _token: $('input[name="_token"]').val()
            };

            var cektotalunit = $('input[name="unittotal_act"]').val();

    
            console.log(formData)
            if(cektotalunit > 0) {

                $.ajax({
                    url: '/cabang/updatunitentrylpj',
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        if(response.status == "success") {
                            // alert(response.message)
                        }
                    },
                    error: function(xhr, status, error) {
                        if(response.status == "error") {
                            alert(response.message)
                        }
                    }
                });
            } else {
                alert('Silahkan isi Unit Entry')
            }

        } 

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
        nexbutton();
    })

    function simpanrevenue() {

        var id = $('input[name="id"]').val();
        var jasaact = $('#jasa_act').val() || 0;
        var partact = $('#part_act').val() || 0;
        var oliact = $('#oli_act').val() || 0;

        var formData = {
            id: id,
            jasa_act: parseInt(jasaact),
            part_act: parseInt(partact),
            oli_act: parseInt(oliact),
            _token: $('input[name="_token"]').val()
        };

        console.log(formData)
        cektotalrevenue = $('#total_act').val();

        if(cektotalrevenue > 0) {

            $.ajax({
                url: '/cabang/updaterevenuelpj',
                type: 'POST',
                data: formData,
                success: function(response) {
                    if(response.status == "success") {
                        // alert(response.message)
                    }
                },
                error: function(xhr, status, error) {
                    if(response.status == "error") {
                        alert(response.message)
                    }
                }
            });
        } else {
            alert('Silahkan isi Revenue')
        }
    }

    if($('#kategoriform').val() == 5) {

        $('.savethis').prop('disabled', true);  // Disable the button

        setTimeout(function() {
            nexbutton()
        }, 2000);

        function nexbutton() {

            var cektotalunit = $('input[name="unittotal_act"]').val();
            var cektotalrevenue = $('#total_act').val();

            console.log(cektotalunit)
            console.log(cektotalrevenue)

            if (cektotalunit > 0 && cektotalrevenue > 0) {
                $('.savethis').prop('disabled', false); // Enable the button
            } else {
                $('.savethis').prop('disabled', true);  // Disable the button
            }
        }
    }


})


</script>
