@extends('layouts.default')

@section('konten')
<!-- ============================================================== -->
<!-- Bread crumb and right sidebar toggle -->
<!-- ============================================================== -->
<div class="row page-titles">
    <div class="col-md-5 align-self-center">
        <h4 class="text-themecolor">Copy Kegiatan</h4>
    </div>
    <div class="col-md-7 align-self-center text-right">
        <div class="d-flex justify-content-end align-items-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{url('')}}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{route('kegiatan.list')}}">Kegiatan List</a></li>
                <li class="breadcrumb-item active">Copy Kegiatan</li>
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
                        <h4 class="card-title">Copy Kegiatan</h4>
                        <h6 class="card-subtitle">copy kegiatan yang sudah dinput</h6>
                        <hr>
                        @include('kegiatan.copyform')
                    </div>
                    <div class="col-lg-5 col-sm-5">
                        <h4 class="card-title">Target</h4>
                        <h6 class="card-subtitle">untuk kabupaten/kota yang tidak terpilih isikan 0 (nol)</h6>
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
                                     @foreach ($dataKegiatan->Target as $t)
                                         <tr>
                                             <td>{{$t->Unitkerja->unit_nama}}</td>
                                             <td><input type="number" name="keg_kabkota[{{$t->keg_t_unitkerja}}]" id="keg_kabkota[{{$t->keg_t_unitkerja}}]" class="form-control input-sm target_kabkota" placeholder="....." value="{{$t->keg_t_target}}" required="required"></td>
                                             <td><input type="number" name="spj_kabkota[{{$t->keg_t_unitkerja}}]" id="spj_kabkota[{{$t->keg_t_unitkerja}}]" class="form-control input-sm spj" placeholder="....." @if ($dataKegiatan->keg_spj==2)
                                                value=""
                                                readonly="readonly"
                                                @else 
                                                value="{{$t->TargetSpj->spj_t_target}}"
                                             @endif  /></td>
                                         </tr>
                                     @endforeach
                                 </tbody>
                            </table>
                        </div>
                        <a href="{{route('kegiatan.detil',$dataKegiatan->keg_id)}}" class="btn btn-warning m-r-5"><i class="fas fa-arrow-left"></i> KEMBALI</a>
                        <span class="float-right">
                            <button type="button" class="btn btn-success mr-2" id="simpan" name="simpan">SIMPAN</button>
                            <button type="reset" class="btn btn-dark">RESET</button>
                            <input type="hidden" id="keg_id" name="keg_id" value="{{$dataKegiatan->keg_id}}" />
                        </span>
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