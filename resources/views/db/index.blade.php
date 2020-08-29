@extends('layouts.default')

@section('konten')
<!-- ============================================================== -->
<!-- Bread crumb and right sidebar toggle -->
<!-- ============================================================== -->
<div class="row page-titles">
    <div class="col-md-5 align-self-center">
        <h4 class="text-themecolor">Database</h4>
    </div>
    <div class="col-md-7 align-self-center text-right">
        <div class="d-flex justify-content-end align-items-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{url('')}}">Dashboard</a></li>
                <li class="breadcrumb-item active">Database Sinkronisasi</li>
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
    <div class="col-lg-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-body">
                
                <div class="col-lg-6">
                    <h4 class="card-title">Database sinkronisasi</h4>
                    Sinkronisasi data dari SiRinjani v1.5 ke sistem baru v2.0
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Nama Tabel</th>
                                <th>v1.5</th>
                                <th>v.2</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Kegiatan</td>
                                <td>{{$keg_lama}}</td>
                                <td>{{$keg}}</td>
                            </tr>
                            <tr>
                                <td>Target</td>
                                <td>{{$keg_target_lama}}</td>
                                <td>{{$keg_target}}</td>
                            </tr>
                            <tr>
                                <td>Realisasi</td>
                                <td>{{$keg_realisasi_lama}}</td>
                                <td>{{$keg_realisasi}}</td>
                            </tr>
                            <tr>
                                <td>SPJ Target</td>
                                <td>{{$spj_realisasi_lama}}</td>
                                <td>{{$spj_realisasi}}</td>
                            </tr>
                            <tr>
                                <td>SPJ Realisasi</td>
                                <td>{{$spj_realisasi_lama}}</td>
                                <td>{{$spj_realisasi}}</td>
                            </tr>
                            <tr>
                                <td>User Pemantau</td>
                                <td>{{$user_lama}}</td>
                                <td>{{$data_user}}</td>
                            </tr>
                            @if (Auth::user()->level == 9)
                            <tr>
                                <td colspan="3" class="text-right">
                                    <a href="{{route('db.sinkron')}}" class="btn btn-info">SINKRON</a>
                                </td>
                            </tr>
                            @endif
                            
                        </tbody>
                    </table>
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
<!--alerts CSS -->
<link href="{{asset('assets/node_modules/sweetalert2/dist/sweetalert2.min.css')}}" rel="stylesheet">
<link href="{{asset('dist/css/pages/tab-page.css')}}" rel="stylesheet">
@endsection

@section('js')
   
    <!-- Sweet-Alert  -->
    <script src="{{asset('assets/node_modules/sweetalert2/dist/sweetalert2.all.min.js')}}"></script>    
@endsection