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
                <h1 class="text-center">Error : tidak dapat mengakses halaman ini</h1>
                @if ($keg_id > 0)
                    <center><a href="{{route('kegiatan.detil',$keg_id)}}" class="btn btn-success m-t-20"><i class="fas fa-arrow-left"></i> KEMBALI</a></center>
                @else
                <center><a href="{{route('kegiatan.list')}}" class="btn btn-success m-t-20"><i class="fas fa-arrow-left"></i> KEMBALI</a></center>
                @endif
                
            </div>
        </div>
    </div>
</div>
<!-- ============================================================== -->
<!-- End PAge Content -->
<!-- ============================================================== -->
@endsection