@extends('layouts.default')

@section('konten')
<!-- ============================================================== -->
<!-- Bread crumb and right sidebar toggle -->
<!-- ============================================================== -->
<div class="row page-titles">
    <div class="col-md-5 align-self-center">
        <h4 class="text-themecolor">Kegiatan</h4>
    </div>
    <div class="col-md-7 align-self-center text-right">
        <div class="d-flex justify-content-end align-items-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
                <li class="breadcrumb-item active">Kegiatan List</li>
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
                @if (Auth::user())
                    @if (Auth::user()->level == 3 or Auth::user()->level > 4)
                    <div class="row">
                        <div class="col-lg-6 col-sm-12 col-xs-12">
                        <a href="{{route('kegiatan.tambah')}}" class="btn btn-info btn-rounded waves-effect waves-light m-b-20">Tambah</a>

                        </div>
                    </div>
                    @endif
                @endif

                <div class="row">
                    <div class="col-lg-12 col-sm-12 col-xs-12">
                        @include('kegiatan.filter')
                    </div>
                </div>
                <div class="row">
                    <div class="table-responsive">
                        <table id="kegiatan" class="table table-bordered table-hover table-striped" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                            <th>No</th>
                            <th>Kegiatan</th>
                            <th>Unitkerja</th>
                            <th>Mulai</th>
                            <th>Berakhir</th>
                            <th>Target</th>
                            <th>Realisasi</th>
                            <th>Satuan</th>
                            <th>SPJ</th>
                            @if (Auth::user())
                                @if (Auth::user()->level > 4 or Auth::user()->level == 3)
                                    <th width="65px">Aksi</th>
                                @endif
                            @endif
                            </tr>
                            </thead>
                            <tbody>
                               @foreach ($dataKeg as $item)
                                   <tr>
                                       <td>{{$loop->iteration}}</td>
                                       <td>
                                           <div class="text-info">
                                            <a href="{{route('kegiatan.detil',$item->keg_id)}}" class="text-info">{{$item->keg_nama}}</a>
                                           </div>
                                           <small class="badge badge-success">{{$item->JenisKeg->jkeg_nama}}</small>
                                           <div class="progress m-t-10">
                                               <div class="progress-bar
                                               @if (($item->RealisasiKirim->sum('keg_r_jumlah')/$item->keg_total_target)*100 >= 100)
                                               bg-info
                                               @elseif (($item->RealisasiKirim->sum('keg_r_jumlah')/$item->keg_total_target)*100 > 80)
                                               bg-success
                                               @elseif (($item->RealisasiKirim->sum('keg_r_jumlah')/$item->keg_total_target)*100 > 50)
                                               bg-warning
                                               @else
                                               bg-danger
                                               @endif
                                                wow animated progress-animated" style="width: {{number_format(($item->RealisasiKirim->sum('keg_r_jumlah')/$item->keg_total_target)*100,2,".",",")}}%; height:7px;" role="progressbar">
                                                <span class="sr-only">{{number_format(($item->RealisasiKirim->sum('keg_r_jumlah')/$item->keg_total_target)*100,2,".",",")}} Terkirim</span>
                                                </div>
                                            </div>
                                       </td>
                                       <td>{{$item->unit_nama}}</td>
                                       <td>{{Tanggal::Panjang($item->keg_start)}}</td>
                                       <td>{{Tanggal::Panjang($item->keg_end)}}</td>
                                       <td>{{$item->keg_total_target}}</td>
                                       <td>{{$item->RealisasiTerima->sum('keg_r_jumlah')}}</td>
                                       <td>{{$item->keg_target_satuan}}</td>
                                       <td>@include('kegiatan.spj')</td>
                                       @if (Auth::user())
                                        @if (Auth::user()->level > 4 or Auth::user()->level == 3)
                                            <td>
                                                @include('kegiatan.aksi')
                                            </td>
                                        @endif
                                       @endif
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
@endsection

@section('css')
<meta name="csrf-token" content="{{ csrf_token() }}">
<!--alerts CSS -->
<link href="{{asset('assets/node_modules/sweetalert2/dist/sweetalert2.min.css')}}" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="{{asset('assets/node_modules/datatables.net-bs4/css/dataTables.bootstrap4.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/node_modules/datatables.net-bs4/css/responsive.dataTables.min.css')}}">
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
    <script>
        $(function () {
            $('#kegiatan').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'excel', 'pdf', 'print'
                ],
                "displayLength": 30,
                responsive: true

            });
            $('.buttons-copy, .buttons-csv, .buttons-print, .buttons-pdf, .buttons-excel').addClass('btn btn-primary mr-1');
        });

    </script>
    <!-- Sweet-Alert  -->
    <script src="{{asset('assets/node_modules/sweetalert2/dist/sweetalert2.all.min.js')}}"></script>
    @include('kegiatan.jshapus')
@endsection
