@extends('layouts.default')

@section('konten')
<!-- ============================================================== -->
<!-- Bread crumb and right sidebar toggle -->
<!-- ============================================================== -->
<div class="row page-titles">
    <div class="col-md-5 align-self-center">
        <h4 class="text-themecolor">Tambah Kegiatan</h4>
    </div>
    <div class="col-md-7 align-self-center text-right">
        <div class="d-flex justify-content-end align-items-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{url('')}}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{route('kegiatan.list')}}">Kegiatan List</a></li>
                <li class="breadcrumb-item active">Tambah Kegiatan</li>
            </ol>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-sm-12">
        @if (Session::has('message'))
        <div class="alert alert-{{ Session::get('message_type') }}" id="waktu2" style="margin-top:10px;">{{ Session::get('message') }}</div>
        @endif
    </div>
</div>
<!-- ============================================================== -->
<!-- End Bread crumb and right sidebar toggle -->
<!-- ============================================================== -->
<!-- ============================================================== -->
<!-- Start Page Content -->
<!-- ============================================================== -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-7 col-sm-7">
                        <h4 class="card-title">Kegiatan Baru</h4>
                        <h6 class="card-subtitle">input kegiatan baru</h6>
                        <hr>
                        @include('kegiatan.form')
                    </div>
                    <div class="col-lg-5 col-sm-5">
                        <h4 class="card-title">Target</h4>
                        <h6 class="card-subtitle">untuk kabupaten/kota yang tidak terpilih isian dikosongkan</h6>
                        <hr>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                 <thead>
                                 <tr class="bg-info text-white">
                                     <th class="text-center" width="60%">Nama Unit</th>
                                     <th class="text-center">Kegiatan</th>
                                     <th class="text-center">SPJ</th>
                                 </tr>
                                 </thead>
                                 <tbody>
                                     @foreach ($unitTarget as $t)
                                         <tr>
                                             <td>{{$t->unit_nama}}</td>
                                             <td><input type="number" name="keg_kabkota[{{$t->unit_kode}}]" id="keg_kabkota[{{$t->unit_kode}}]" class="form-control input-sm target_kabkota" placeholder="....." / required="required"></td>
                                             <td><input type="number" name="spj_kabkota[{{$t->unit_kode}}]" id="spj_kabkota[{{$t->unit_kode}}]" class="form-control input-sm spj" placeholder="....." readonly="readonly" /></td>
                                         </tr>
                                     @endforeach
                                 </tbody>
                            </table>
                        </div>
                        <button type="button" class="btn btn-success mr-2" id="simpan" name="simpan">SIMPAN</button>
                        <button type="reset" class="btn btn-dark">RESET</button>
                    </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- ============================================================== -->
<!-- End PAge Content -->
<!-- ============================================================== -->
@endsection

@section('css')
     <!-- Date picker plugins css -->
     <link href="{{asset('assets/node_modules/bootstrap-datepicker/bootstrap-datepicker.min.css')}}" rel="stylesheet" type="text/css" />
     <!-- Daterange picker plugins css -->
     <link href="{{asset('assets/node_modules/timepicker/bootstrap-timepicker.min.css')}}" rel="stylesheet">
     <link href="{{asset('assets/node_modules/bootstrap-daterangepicker/daterangepicker.css')}}" rel="stylesheet">
     <!--alerts CSS -->
    <link href="{{asset('assets/node_modules/sweetalert2/dist/sweetalert2.min.css')}}" rel="stylesheet">
@endsection

@section('js')
    <!-- Date Picker Plugin JavaScript -->
    <script src="{{asset('assets/node_modules/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
    <!-- Date range Plugin JavaScript -->
    <script src="{{asset('assets/node_modules/timepicker/bootstrap-timepicker.min.js')}}"></script>
    <script src="{{asset('assets/node_modules/bootstrap-daterangepicker/daterangepicker.js')}}"></script>
    <!-- Sweet-Alert  -->
    <script src="{{asset('assets/node_modules/sweetalert2/dist/sweetalert2.all.min.js')}}"></script>
    @include('kegiatan.js')
@endsection