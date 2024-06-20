@extends('layouts.default')

@section('konten')
<!-- ============================================================== -->
<!-- Bread crumb and right sidebar toggle -->
<!-- ============================================================== -->
<div class="row page-titles hidden-md-up">
    <div class="col-md-5 align-self-center">
        <h4 class="text-themecolor">Sistem Monitoring Kinerja Online v2.0 <br />BPS Provinsi Nusa Tenggara Barat</h4>
    </div>
    <div class="col-md-7 align-self-center text-right">
        <div class="d-flex justify-content-end align-items-center">

        </div>
    </div>
</div>
<!-- ============================================================== -->
<!-- End Bread crumb and right sidebar toggle -->
<!-- ============================================================== -->
<!-- ============================================================== -->
<!-- Start Page Content -->
<!-- ============================================================== -->
@if (Generate::TotalKegiatan(Carbon\Carbon::now()->format('Y')) > 0)
    @include('dashboard.baris2')
@else
    @include('dashboard.kosong')
@endif
<!-- ============================================================== -->
<!-- End PAge Content -->
<!-- ============================================================== -->
@endsection

@section('css')
<!--highcharts-->
<link href="{{asset('dist/grafik/highcharts.css')}}" rel="stylesheet">
<link href="{{asset('dist/css/pages/tab-page.css')}}" rel="stylesheet">
<!--This page css - Morris CSS -->
<link href="{{asset('assets/node_modules/morrisjs/morris.css')}}" rel="stylesheet">
@endsection

@section('js')
    <!-- This is data table -->
    <script src="{{asset('dist/grafik/highcharts.js')}}"></script>
    <script src="{{asset('dist/grafik/exporting.js')}}"></script>
    <script src="{{asset('dist/grafik/offline-exporting.js')}}"></script>
    <script src="{{asset('dist/grafik/export-data.js')}}"></script>
    <script src="{{asset('dist/grafik/series-label.js')}}"></script>
    <script src="{{asset('dist/grafik/accessibility.js')}}"></script>
    <script src="{{asset('assets/node_modules/raphael/raphael-min.js')}}"></script>
    <script src="{{asset('assets/node_modules/morrisjs/morris.js')}}"></script>
    @if (Generate::TotalKegiatan(Carbon\Carbon::now()->format('Y')) > 0)
        @include('dashboard.GrafikNilai')
        @include('dashboard.GrafikNilaiRataRata')
        @include('dashboard.GrafikNilaiTahunan')
        @include('dashboard.grafik-kegiatan')
    @endif
@endsection
