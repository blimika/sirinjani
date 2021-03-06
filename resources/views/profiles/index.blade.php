@extends('layouts.default')

@section('konten')
<!-- ============================================================== -->
<!-- Bread crumb and right sidebar toggle -->
<!-- ============================================================== -->
<div class="row page-titles">
    <div class="col-md-5 align-self-center">
        <h4 class="text-themecolor">Profilku</h4>
    </div>
    <div class="col-md-7 align-self-center text-right">
        <div class="d-flex justify-content-end align-items-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
                <li class="breadcrumb-item active">Profilku</li>
            </ol>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-sm-12">
        @if (Session::has('message'))
        <div class="alert alert-{{ Session::get('message_type') }}" id="waktu2" style="margin-top:10px;">{!! Session::get('message') !!}</div>
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
                    <h3><b>{{Auth::user()->nama}}</b></h3>
                    <hr>
                    <div class="row">
                        <div class="col-lg-2 col-md-2"><b>ID</b></div>
                        <div class="col-lg-5 col-md-5">#{{Auth::user()->id}}</div>
                        <div class="col-lg-5 col-md-5"></div>
                    </div>
                    <div class="row">
                        <div class="col-lg-2 col-md-2"><b>Username</b></div>
                        <div class="col-lg-5 col-md-5">{{Auth::user()->username}}</div>
                        <div class="col-lg-5 col-md-5"></div>
                    </div>
                    <div class="row">
                        <div class="col-lg-2 col-md-2"><b>Email</b></div>
                        <div class="col-lg-5 col-md-5">{{Auth::user()->email}}</div>
                        <div class="col-lg-5 col-md-5"></div>
                    </div>
                    <div class="row">
                        <div class="col-lg-2 col-md-2"><b>Nomor WhatsApp</b></div>
                        <div class="col-lg-5 col-md-5">{{Auth::user()->nohp}}</div>
                        <div class="col-lg-5 col-md-5"></div>
                    </div>
                    <div class="row">
                        <div class="col-lg-2 col-md-2"><b>Level</b></div>
                        <div class="col-lg-5 col-md-5">{{Auth::user()->Level->level_nama}}</div>
                        <div class="col-lg-5 col-md-5"></div>
                    </div>
                    <div class="row">
                        <div class="col-lg-2 col-md-2"><b>Unit Kerja</b></div>
                        <div class="col-lg-5 col-md-5">{{Auth::user()->Unitkerja->unit_nama}}</div>
                        <div class="col-lg-5 col-md-5"></div>
                    </div>
                    <div class="row">
                        <div class="col-lg-2 col-md-2"><b>Register Tanggal</b></div>
                        <div class="col-lg-5 col-md-5">{{Tanggal::LengkapHariPanjang(Auth::user()->created_at)}}</div>
                        <div class="col-lg-5 col-md-5"></div>
                    </div>
                    <div class="row">
                        <div class="col-lg-2 col-md-2"><b>Terakhir Login</b></div>
                        <div class="col-lg-5 col-md-5">{{Tanggal::LengkapHariPanjang(Auth::user()->lastlogin)}}</div>
                        <div class="col-lg-5 col-md-5"></div>
                    </div>
                    <div class="row">
                        <div class="col-lg-2 col-md-2"><b>Dari IP</b></div>
                        <div class="col-lg-5 col-md-5">{{Auth::user()->lastip}}</div>
                        <div class="col-lg-5 col-md-5"></div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 m-t-30">
                            <button class="btn btn-rounded btn-success" data-toggle="modal" data-target="#EditModal" data-id="{{Auth::user()->id}}" data-nama="{{Auth::user()->nama}}" data-email="{{Auth::user()->email}}"><i class="fas fa-pencil-alt" data-toggle="tooltip" title="Edit Data"></i> EDIT</button>
                            <button class="btn btn-rounded btn-danger" data-toggle="modal" data-target="#GantiPasswordModal" data-id="{{Auth::user()->id}}" data-nama="{{Auth::user()->nama}}" data-email="{{Auth::user()->email}}"><i class="fas fa-key" data-toggle="tooltip" title="Ganti Password"></i> GANTI PASSWORD</button>
                        </div>
                    </div>
            </div>
        </div>
    </div>
</div>
<!-- ============================================================== -->
<!-- End PAge Content -->
<!-- ============================================================== -->
@include('profiles.modal')
@endsection

@section('css')
<meta name="csrf-token" content="{{ csrf_token() }}">
<!--alerts CSS -->
<link href="{{asset('assets/node_modules/sweetalert2/dist/sweetalert2.min.css')}}" rel="stylesheet">
@endsection

@section('js')
    <!-- Sweet-Alert  -->
    <script src="{{asset('assets/node_modules/sweetalert2/dist/sweetalert2.all.min.js')}}"></script>
    @include('profiles.js')
@endsection
