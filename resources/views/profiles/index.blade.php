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
                    <dl class="row">
                        <dt class="col-lg-2 col-md-2">ID</dt>
                        <dd class="col-lg-10 col-sm-10">#{{Auth::user()->id}}</dd>
                        <dt class="col-lg-2 col-md-2">Username</dt>
                        <dd class="col-lg-10 col-sm-10">{{Auth::user()->username}}</dd>
                        <dt class="col-lg-2 col-md-2">Email</dt>
                        <dd class="col-lg-10 col-sm-10">{{Auth::user()->email}}</dd>
                        <dt class="col-lg-2 col-md-2">Nomor WhatsApp</dt>
                        <dd class="col-lg-10 col-sm-10">
                            @if (Auth::user()->nohp)
                                {{Auth::user()->nohp}}
                            @else
                                -
                            @endif
                        </dd>
                        <dt class="col-lg-2 col-md-2">Level</dt>
                        <dd class="col-lg-10 col-sm-10">[{{Auth::user()->level}}] {{Auth::user()->Level->level_nama}}</dd>
                        <dt class="col-lg-2 col-md-2">Unit Kerja</dt>
                        <dd class="col-lg-10 col-sm-10">[{{Auth::user()->Unitkerja->unit_kode}}] {{Auth::user()->Unitkerja->unit_nama}}</dd>
                        <dt class="col-lg-2 col-md-2">Register Tanggal</dt>
                        <dd class="col-lg-10 col-sm-10">{{Tanggal::LengkapHariPanjang(Auth::user()->created_at)}}</dd>
                        <dt class="col-lg-2 col-md-2">Terakhir Login</dt>
                        <dd class="col-lg-10 col-sm-10">{{Tanggal::LengkapHariPanjang(Auth::user()->lastlogin)}}</dd>
                        <dt class="col-lg-2 col-md-2">Dari IP</dt>
                        <dd class="col-lg-10 col-sm-10">{{Auth::user()->lastip}}</dd>
                        <dt class="col-lg-2 col-md-2">Username Telegram</dt>
                        <dd class="col-lg-10 col-sm-10">
                            @if (Auth::user()->user_tg)
                            {{Auth::user()->user_tg}}
                            @else
                                -belum tersedia-
                            @endif

                        </dd>
                        <dt class="col-lg-2 col-md-2">ChatID Telegram</dt>
                        <dd class="col-lg-10 col-sm-10">
                            @if (Auth::user()->chatid_tg)
                            {{Auth::user()->chatid_tg}}
                            @else
                                <b>-belum tersedia-</b>
                            @endif
                        </dd>
                        <dt class="col-lg-2 col-md-2">Token Telegram</dt>
                        <dd class="col-lg-10 col-sm-10">
                            @if (Auth::user()->token_tg)
                            {{Auth::user()->token_tg}}
                            @else
                                Silakan generate token baru
                            @endif
                        </dd>
                    </dl>

                    <div class="row">
                        <div class="col-lg-8 m-t-30">
                            <button class="btn btn-rounded btn-success" data-toggle="modal" data-target="#EditModal" data-id="{{Auth::user()->id}}" data-nama="{{Auth::user()->nama}}" data-email="{{Auth::user()->email}}"><i class="fas fa-pencil-alt" data-toggle="tooltip" title="Edit Data"></i> EDIT</button>
                            <button class="btn btn-rounded btn-danger" data-toggle="modal" data-target="#GantiPasswordModal" data-id="{{Auth::user()->id}}" data-nama="{{Auth::user()->nama}}" data-email="{{Auth::user()->email}}"><i class="fas fa-key" data-toggle="tooltip" title="Ganti Password"></i> GANTI PASSWORD</button>
                            <button class="btn btn-rounded btn-warning generatetoken" data-id="{{Auth::user()->id}}" data-nama="{{Auth::user()->nama}}"><i class="fas fa-key" data-toggle="tooltip" title="Generate Token Telegram Baru"></i> GENERATE TOKEN BARU</button>
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
