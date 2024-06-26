@extends('layouts.default')

@section('konten')
<!-- ============================================================== -->
<!-- Bread crumb and right sidebar toggle -->
<!-- ============================================================== -->
<div class="row page-titles">
    <div class="col-md-5 align-self-center">
        <h4 class="text-themecolor">Master Operator</h4>
    </div>
    <div class="col-md-7 align-self-center text-right">
        <div class="d-flex justify-content-end align-items-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
                <li class="breadcrumb-item active">Operator List</li>
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
                @if (Auth::user())
                    @if (Auth::user()->role == 3 or Auth::user()->role > 4 or Auth::User()->username=='admin')
                    <div class="row">
                        <div class="col-lg-12 col-sm-12 col-xs-12">
                        <button class="btn btn-info btn-rounded waves-effect waves-light m-b-20 float-right" data-toggle="modal" data-target="#TambahOperator">Tambah</button>
                        @if (Auth::User()->role > 5 or Auth::User()->username=='admin')
                        <a href="" class="btn btn-success btn-rounded waves-effect waves-light m-b-20 float-right" id="perbaikirole">Perbaiki Role</a>
                        @endif
                        </div>
                    </div>
                    @endif
                @endif
                @if (Auth::user()->role > 5)
                    @include('operator.filter')
                @endif
                <div class="row">
                    <div class="table-responsive">
                        <table id="pegawai" class="table table-bordered table-hover table-striped" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Username</th>
                            <th>WhatsApp</th>
                            <th>Lastlogin</th>
                            <th>Status</th>
                            @if (Auth::user()->level > 5)
                            <th>Flag CKP</th>
                            <th>Level</th>
                            <th>Role</th>
                            <th>Akses</th>
                            @endif
                            <th>Aksi</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach ($dataOperator as $item)
                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td>
                                            {{$item->nama}}
                                            <br />
                                            <span class="label label-rounded label-info">{{$item->Role->level_nama}}</span>
                                        </td>
                                        <td>{{$item->username}}</td>
                                        <td>
                                            @if ($item->nohp)
                                            <a href="http://wa.me/62{{substr($item->nohp,1)}}" target="_blank" class="btn waves-effect btn-success btn-xs waves-light"><i class="fab fa-whatsapp fa-2x"></i></a>
                                            <!--{{$item->nohp}}-->
                                            @endif
                                        </td>
                                        <td>
                                            @if ($item->lastlogin)
                                            {{$item->lastip}} -
                                            {{Tanggal::LengkapPendek($item->lastlogin)}}
                                            @endif
                                        </td>
                                        <td>@if ($item->aktif==1)
                                            <span class="label label-rounded label-info">Aktif</span>
                                            @else
                                            <span class="label label-rounded label-danger">Tidak aktif</span>
                                            @endif
                                        </td>
                                        @if (Auth::user()->level > 5)
                                        <td>
                                            @if ($item->flag_liatckp==1)
                                            <span class="label label-rounded label-info">Aktif</span>
                                            @else
                                            <span class="label label-rounded label-danger">NonAktif</span>
                                            @endif
                                        </td>
                                        <td>{{$item->level}}</td>
                                        <td>{{$item->role}}</td>
                                        <td>
                                            <ul class="list-icons">
                                            @if ($item->HakAkses)
                                                @foreach ($item->HakAkses as $h)
                                                <li><i class="ti-angle-right"></i>{{$h->TimKerja->unit_nama}} </li>
                                                @endforeach
                                            @else
                                                {{$item->TimKerja->unit_nama}}
                                            @endif
                                            </ul>
                                        </td>
                                        @endif
                                        <td>
                                            <button class="btn btn-circle btn-sm btn-success waves-light waves-effect" data-toggle="modal" data-target="#DetilModal" data-idop="{{$item->id}}"><i class="fas fa-search" data-toggle="tooltip" title="View Operator"></i></button>
                                            @if (Auth::user()->role > 2 and $item->role < 9)
                                                @if ((Auth::User()->role == 3 or Auth::User()->role > 4) and Auth::user()->username != $item->username)
                                                    @include('operator.aksi')
                                                @endif
                                            @endif
                                         </td>
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
<!-- ============================================================== -->
<!-- End PAge Content -->
<!-- ============================================================== -->
@include('operator.modal')
@endsection

@section('css')
<meta name="csrf-token" content="{{ csrf_token() }}">
<!--alerts CSS -->
<link href="{{asset('assets/node_modules/sweetalert2/dist/sweetalert2.min.css')}}" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="{{asset('assets/node_modules/datatables.net-bs4/css/dataTables.bootstrap4.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/node_modules/datatables.net-bs4/css/responsive.dataTables.min.css')}}">
<link href="{{asset('assets/node_modules/select2/dist/css/select2.min.css')}}" rel="stylesheet" type="text/css" />
@endsection

@section('js')
    <!-- This is data table -->
    <script src="{{asset('assets/node_modules/datatables.net/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('assets/node_modules/datatables.net-bs4/js/dataTables.responsive.min.js')}}"></script>
    <!-- start - This is for export functionality only -->
    <script src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.print.min.js"></script>
    <!-- end - This is for export functionality only -->
    <script src="{{asset('assets/node_modules/select2/dist/js/select2.full.min.js')}}" type="text/javascript"></script>
    <script>
        $(function () {
            $('#pegawai').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'excel', 'pdf', 'print'
                ],
                "displayLength": 30,

            });
            $('.buttons-copy, .buttons-csv, .buttons-print, .buttons-pdf, .buttons-excel').addClass('btn btn-primary mr-1');
            $("#hak_akses_list").select2({
                placeholder: 'Pilih Hak Akses'
            });
        });

    </script>
    <!-- Sweet-Alert  -->
    <script src="{{asset('assets/node_modules/sweetalert2/dist/sweetalert2.all.min.js')}}"></script>

    @if (Auth::user()->level > 5)
        @include('operator.jssuper')
    @elseif (Auth::user()->level == 5)
        @include('operator.jsadminprov')
    @elseif (Auth::user()->level == 4)
        @include('operator.jsadminkab')
    @endif
    @include('operator.jsumum')
@endsection
