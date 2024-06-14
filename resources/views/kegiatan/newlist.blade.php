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
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <form class="form-horizontal">
                              <div class="form-group row">
                                <label for="unit" class="col-sm-2 control-label">Tampilkan data berdasarkan </label>
                                <div class="col-md-4">
                                    <select name="unit" id="unit" class="form-control">
                                    <option value="">Pilih Bidang/Fungsi/Tim</option>
                                    @foreach ($dataUnit as $d)
                                    <option value="{{$d->unit_kode}}" @if (request('unit')==$d->unit_kode or $unit_filter==$d->unit_kode)
                                        selected
                                       @endif>{{$d->unit_nama}}</option>
                                    @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <select name="bulan" id="bulan" class="form-control">
                                        <option value="0">Pilih Bulan</option>
                                     @for ($i = 1; $i <= 12; $i++)
                                         <option value="{{$i}}" @if (request('bulan')==$i or $bulan_filter==$i)
                                             selected
                                         @endif>{{$dataBulan[$i]}}</option>
                                     @endfor
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <select name="tahun" id="tahun" class="form-control">
                                     @foreach ($dataTahun as $iTahun)
                                     <option value="{{$iTahun->tahun}}" @if (request('tahun')==$iTahun->tahun or $tahun_filter==$iTahun->tahun)
                                     selected
                                    @endif>{{$iTahun->tahun}}</option>
                                     @endforeach
                                    </select>
                                </div>

                                <div class="col-md-2">
                                    <button type="button" class="btn btn-success">Filter</button>
                                </div>
                              </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                @if (Auth::user())
                    @if (Auth::user()->role > 3)
                    <div class="row">
                        <div class="col-lg-6 col-sm-12 col-xs-12">
                        <a href="{{route('kegiatan.tambah')}}" class="btn btn-info btn-rounded waves-effect waves-light m-b-20">Tambah</a>
                        </div>
                    </div>
                    @endif
                @endif
                <div class="row">
                    <div class="table-responsive">
                        <table id="kegiatan" class="table table-bordered table-hover table-striped" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Kegiatan</th>
                                <th>Tim Kerja</th>
                                <th>Mulai</th>
                                <th>Berakhir</th>
                                <th>Target</th>
                                <th>Realisasi</th>
                                <th>Satuan</th>
                                <th>SPJ</th>
                                <th width="65px">Aksi</th>
                            </tr>
                            </thead>
                            <tbody>

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
<style>
    .tgl_hide
    {
        display: none;
    }
</style>
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
                ajax: {
                    url: '{{ route('kegiatan.pagelist') }}',
                    type: 'GET'
                },
                columns: [
                    { data: 'keg_id' },
                    { data: 'keg_nama' },
                    { data: 'keg_timkerja' },
                    {
                        data: {
                            _: 'keg_start.teks',
                            sort: 'keg_start.sort'
                        }
                    },
                    {
                        data: {
                            _: 'keg_end.teks',
                            sort: 'keg_end.sort'
                        }
                    },
                    { data: 'keg_total_target' },
                    { data: 'keg_realisasi' },
                    { data: 'keg_target_satuan' },
                    { data: 'keg_spj' },
                    { data: 'aksi', orderable: false },
                ],
                processing: true,
                serverSide: true,
                dom: 'Bfrtip',
                iDisplayLength: 10,
                buttons: [
                    'copy', 'excel', 'print'
                ],
                order: [1, 'asc'],
                responsive: true,

            });
            $('.buttons-copy, .buttons-csv, .buttons-print, .buttons-pdf, .buttons-excel').addClass('btn btn-primary mr-1');
        });

    </script>
    <!-- Sweet-Alert  -->
    <script src="{{asset('assets/node_modules/sweetalert2/dist/sweetalert2.all.min.js')}}"></script>
@endsection
