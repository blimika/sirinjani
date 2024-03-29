@extends('layouts.default')

@section('konten')
<!-- ============================================================== -->
<!-- Bread crumb and right sidebar toggle -->
<!-- ============================================================== -->
<div class="row page-titles">
    <div class="col-md-5 align-self-center">
        <h4 class="text-themecolor">Rekap Nilai Bulanan</h4>
    </div>
    <div class="col-md-7 align-self-center text-right">
        <div class="d-flex justify-content-end align-items-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
                <li class="breadcrumb-item active">Rekap Nilai Bulanan</li>
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
                                <label for="unit" class="col-sm-2 control-label">Rekap nilai berdasarkan</label>
                                <div class="col-md-4">
                                    <select name="unit" id="unit" class="form-control">
                                    <option value="0">BPS Provinsi NTB</option>
                                    @foreach ($dataUnitkerja as $d)
                                    <option value="{{$d->unit_kode}}" @if (request('unit')==$d->unit_kode or $unit==$d->unit_kode)
                                        selected
                                       @endif>{{$d->unit_nama}}</option>
                                    @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <select name="tahun" id="tahun" class="form-control">
                                     @foreach ($dataTahun as $iTahun)
                                     <option value="{{$iTahun->tahun}}" @if (request('tahun')==$iTahun->tahun or $tahun==$iTahun->tahun)
                                     selected
                                    @endif>{{$iTahun->tahun}}</option>
                                     @endforeach
                                    </select>
                                </div>

                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-success">Filter</button>
                                </div>
                              </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <h4 class="card-title text-center">Nilai Poin Kabupaten/Kota @if (request('unit')) Menurut {{$unit_nama}} @endif </h4>
                    <a href="{{route("rekapnilai.export",[$unit,$tahun])}}" class="btn btn-success m-t-10 m-b-20"><i class="fas fa-file-excel"></i> Export ke Excel</a>
                    <p class="text-right">Keadaan : {{Tanggal::LengkapHariPanjang(\Carbon\Carbon::now())}}</p>
                    <table class="table table-bordered table-hover table-striped" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th rowspan="2" class="text-center">Nama Kabkota</th>
                            <th colspan="12" class="text-center">Tahun {{$tahun}} </th>
                        </tr>
                        <tr>
                            @for ($i = 1; $i <= 12; $i++)
                               <td>{{$dataBulan[$i]}}</td>
                            @endfor
                        </tr>
                        </thead>
                        <tbody>
                            @foreach ($dataKabkota as $item)
                                <tr>
                                    <td>{{$item->unit_nama}}</td>
                                    @for ($i = 1; $i <= 12; $i++)
                                        <td>
                                            @if (request('unit') == 0)
                                                @if ((int) Generate::ListNilaiTotal($item->unit_kode,$i,$tahun)['nilai_total'] == 0)
                                                -
                                                @else
                                                    @if (date('Y')==$tahun && $i > date('m'))
                                                    -
                                                    @else
                                                        {{Generate::ListNilaiTotal($item->unit_kode,$i,$tahun)['nilai_total']}}
                                                    @endif
                                                @endif

                                            @else
                                                @if ((int) Generate::ListNilaiMenurutFungsi(request('unit'),$item->unit_kode,$i,$tahun)['nilai_total'] == 0)
                                                -
                                                @else
                                                    @if (date('Y')==$tahun && $i > date('m'))
                                                    -
                                                    @else
                                                        {{Generate::ListNilaiMenurutFungsi(request('unit'),$item->unit_kode,$i,$tahun)['nilai_total']}}
                                                    @endif
                                                @endif
                                            @endif

                                        </td>
                                    @endfor
                                </tr>
                            @endforeach
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
<meta name="csrf-token" content="{{ csrf_token() }}">
<!--alerts CSS -->
<link href="{{asset('assets/node_modules/sweetalert2/dist/sweetalert2.min.css')}}" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="{{asset('assets/node_modules/datatables.net-bs4/css/dataTables.bootstrap4.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/node_modules/datatables.net-bs4/css/responsive.dataTables.min.css')}}">
<!--highcharts-->
<link href="{{asset('dist/grafik/highcharts.css')}}" rel="stylesheet">
<link href="{{asset('dist/css/pages/tab-page.css')}}" rel="stylesheet">
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
            $('#nilai').DataTable({
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
    <!--highcharts-->
    <script src="{{asset('dist/grafik/highcharts.js')}}"></script>
    <script src="{{asset('dist/grafik/exporting.js')}}"></script>
    <script src="{{asset('dist/grafik/offline-exporting.js')}}"></script>
    <script src="{{asset('dist/grafik/export-data.js')}}"></script>
    <script src="{{asset('dist/grafik/series-label.js')}}"></script>
    <script src="{{asset('dist/grafik/accessibility.js')}}"></script>

@endsection
