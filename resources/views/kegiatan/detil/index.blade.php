@extends('layouts.default')

@section('konten')
<!-- ============================================================== -->
<!-- Bread crumb and right sidebar toggle -->
<!-- ============================================================== -->
<div class="row page-titles">
    <div class="col-md-5 align-self-center">
        <h4 class="text-themecolor">Kegiatan Detil</h4>
    </div>
    <div class="col-md-7 align-self-center text-right">
        <div class="d-flex justify-content-end align-items-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{route('kegiatan.list')}}">Kegiatan List</a></li>
                <li class="breadcrumb-item active">Kegiatan Detil</li>
            </ol>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-sm-12">
        @if (Session::has('message'))
        <div class="alert alert-{{ Session::get('message_type') }}" id="waktu2" style="margin-top:10px;">
            <h3 class="text-info"><i class="fa fa-exclamation-circle"></i> Informasi</h3>
            {{ Session::get('message') }}
        </div>
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
    <!-- Column -->
    @if ($status == true)
        @include('kegiatan.detil.true')
    @else
        @include('kegiatan.detil.false')
    @endif
    <!-- Column -->
</div>
<!-- ============================================================== -->
<!-- End PAge Content -->
<!-- ============================================================== -->
@include('kegiatan.detil.modal')
@include('kegiatan.detil.modalSpj')
@endsection

@section('css')
<meta name="csrf-token" content="{{ csrf_token() }}">
<!--alerts CSS -->
<link href="{{asset('assets/node_modules/sweetalert2/dist/sweetalert2.min.css')}}" rel="stylesheet">
<!-- Date picker plugins css -->
<link href="{{asset('assets/node_modules/bootstrap-datepicker/bootstrap-datepicker.min.css')}}" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="{{asset('assets/node_modules/html5-editor/bootstrap-wysihtml5.css')}}" />
@endsection

@section('js')
    <!-- Date Picker Plugin JavaScript -->
     <script src="{{asset('assets/node_modules/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>

    <!-- Sweet-Alert  -->
    <script src="{{asset('assets/node_modules/sweetalert2/dist/sweetalert2.all.min.js')}}"></script>
    <!-- wysuhtml5 Plugin JavaScript -->
    <script src="{{asset('assets/node_modules/html5-editor/wysihtml5-0.3.0.js')}}"></script>
    <script src="{{asset('assets/node_modules/html5-editor/bootstrap-wysihtml5.js')}}"></script>
    @include('kegiatan.detil.js')
    @include('kegiatan.detil.jsSpj')
@endsection
