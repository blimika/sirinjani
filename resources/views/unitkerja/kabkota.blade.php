@extends('layouts.default')

@section('konten')
<!-- ============================================================== -->
<!-- Bread crumb and right sidebar toggle -->
<!-- ============================================================== -->
<div class="row page-titles">
    <div class="col-md-5 align-self-center">
        <h4 class="text-themecolor">Unitkerja/TIM di Kabupaten/Kota</h4>
    </div>
    <div class="col-md-7 align-self-center text-right">
        <div class="d-flex justify-content-end align-items-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
                <li class="breadcrumb-item active">Unitkerja</li>
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
                <div class="row">
                    <div class="col-lg-12 col-sm-12 col-xs-12">
                        <a href="#" class="btn btn-info btn-rounded waves-effect waves-light m-b-20 float-right" data-toggle="modal" data-target="#TambahModal">Tambah</a>
                    </div>
                </div>
                <div class="table-responsive">
                    <h4 class="card-title">List Unitkerja / TIM di Kabupaten/Kota</h4>
                    <table id="prov" class="table table-bordered table-hover table-striped" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                        <th>ID</th>
                        <th>unit_kode</th>
                        <th>unit_nama</th>
                        <th>unit_parent</th>
                        <th>unit_eselon</th>
                        <th>unit_jenis</th>
                        <th>unit_flag</th>
                        <th>jumlah_keg</th>
                        <th>Aksi</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- ============================================================== -->
<!-- End PAge Content -->
<!-- ============================================================== -->
@include('unitkerja.modalprov')
@endsection

@section('css')
<meta name="csrf-token" content="{{ csrf_token() }}">
<!--alerts CSS -->
<link href="{{asset('assets/node_modules/sweetalert2/dist/sweetalert2.min.css')}}" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="{{asset('assets/node_modules/datatables.net-bs4/css/dataTables.bootstrap4.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/node_modules/datatables.net-bs4/css/responsive.dataTables.min.css')}}">
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
    <script type="text/javascript">
         $(document).ready(function() {
            // DataTable
            $('#prov').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('unitprov.pagelist') }}",
                columns: [
                    { data: 'id' },
                    { data: 'unit_kode' },
                    { data: 'unit_nama' },
                    { data: 'unit_parent' },
                    { data: 'unit_eselon' },
                    { data: 'unit_jenis' },
                    { data: 'unit_flag' },
                    { data: 'jumlah_keg' },
                    { data: 'aksi', orderable: false },
                ],
                dom: 'Bfrtip',
                iDisplayLength: 10,
                buttons: [
                    'copy', 'excel', 'print'
                ],
                order: [1, 'asc'],
                responsive: true,
                "fnDrawCallback": function() {
                    //ubah flag unitkerja
                    $(".ubahflagunit").click(function (e) {
                        e.preventDefault();
                        var id = $(this).data('id');
                        var nama = $(this).data('nama');
                        var unit_kode = $(this).data('kode');
                        var unit_flag = $(this).data('flag');
                        var flag_nama;
                        if (unit_flag == 0)
                        {
                            //akan diubah ke
                            flag_nama = "Aktif";
                            flag_baru = 1;
                        }
                        else
                        {
                            flag_nama = "Nonaktif";
                            flag_baru = 0;
                        }
                        Swal.fire({
                                    title: 'Akan diubah?',
                                    text: "Data Unitkerja ("+unit_kode+") "+nama+" akan diubah ke "+flag_nama,
                                    type: 'warning',
                                    showCancelButton: true,
                                    confirmButtonColor: '#3085d6',
                                    cancelButtonColor: '#d33',
                                    confirmButtonText: 'Ubah'
                                }).then((result) => {
                                    if (result.value) {
                                        //response ajax disini
                                        $.ajaxSetup({
                                            headers: {
                                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                            }
                                        });
                                        $.ajax({
                                            url : '{{route('unitprov.ubahflag')}}',
                                            method : 'post',
                                            data: {
                                                id: id,
                                                unit_nama: nama,
                                                unit_kode: unit_kode,
                                                unit_flag: unit_flag,
                                                flag_baru: flag_baru,
                                            },
                                            cache: false,
                                            dataType: 'json',
                                            success: function(data){
                                                if (data.status == true)
                                                {
                                                    Swal.fire(
                                                        'Berhasil!',
                                                        ''+data.hasil+'',
                                                        'success'
                                                    ).then(function() {
                                                        $('#prov').DataTable().ajax.reload(null,false);
                                                    });
                                                }
                                                else
                                                {
                                                    Swal.fire(
                                                        'Error!',
                                                        ''+data.hasil+'',
                                                        'error'
                                                    );
                                                }

                                            },
                                            error: function(){
                                                Swal.fire(
                                                    'Error',
                                                    'Koneksi Error '+data.hasil+'',
                                                    'error'
                                                );
                                            }

                                        });

                                    }
                                })
                    });
                    //ubah flag unitkerja
                    //hapus unitkerja
                    $(".hapusunitkerja").click(function (e) {
                    e.preventDefault();
                    var id = $(this).data('id');
                    var nama = $(this).data('nama');
                    var unit_kode = $(this).data('kode');
                    var unit_flag = $(this).data('flag');
                    var jumlah_keg = $(this).data('jumlahkeg');
                    Swal.fire({
                                title: 'Akan dihapus?',
                                text: "Data unitkerja ("+unit_kode+") "+nama+" akan dihapus permanen",
                                type: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Hapus'
                            }).then((result) => {
                                if (result.value) {
                                    //response ajax disini
                                    $.ajaxSetup({
                                        headers: {
                                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                        }
                                    });
                                    $.ajax({
                                        url : '{{route('unitprov.hapus')}}',
                                        method : 'post',
                                        data: {
                                            id: id,
                                            unit_nama: nama,
                                            unit_kode: unit_kode,
                                            unit_flag: unit_flag,
                                            jumlah_keg:jumlah_keg,
                                        },
                                        cache: false,
                                        dataType: 'json',
                                        success: function(data){
                                            if (data.status == true)
                                            {
                                                Swal.fire(
                                                    'Berhasil!',
                                                    ''+data.hasil+'',
                                                    'success'
                                                ).then(function() {
                                                    $('#prov').DataTable().ajax.reload(null,false);
                                                });
                                            }
                                            else
                                            {
                                                Swal.fire(
                                                    'Error!',
                                                    ''+data.hasil+'',
                                                    'error'
                                                );
                                            }

                                        },
                                        error: function(){
                                            Swal.fire(
                                                'Error',
                                                'Koneksi Error',
                                                'error'
                                            );
                                        }

                                    });

                                }
                            })
                });
                    //batas hapus unit kerja
                }
            });
            $('.buttons-copy, .buttons-csv, .buttons-print, .buttons-pdf, .buttons-excel').addClass('btn btn-primary mr-1');
        });
    </script>
    <!-- Sweet-Alert  -->
    <script src="{{asset('assets/node_modules/sweetalert2/dist/sweetalert2.all.min.js')}}"></script>
    @include('unitkerja.jsprov')
@endsection
