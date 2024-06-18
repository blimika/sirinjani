<!-- modal detil -->
<div id="DetilModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Detil Operator</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <!--isi modal-->
                <center class="m-t-30">
                    <b><h3 class="card-title m-t-10" id="nama"></h3></b>
                    <h6 class="card-subtitle">(<span id="username"></span>)</h6>
                    <h5 class="card-title m-t-10" id="level"></h5>
                    <h6 class="card-subtitle" id="unitnama"></h6>
                </center>
                <div>
                    <hr>
                </div>
                <div class="card-body">
                    <small class="text-muted">Hak Akses</small>
                    <h6 id="akses"></h6>
                    <small class="text-muted">Alamat E-mail </small>
                    <h6 id="email"></h6>
                    <small class="text-muted p-t-30">No WA</small>
                    <h6 id="nowa"></h6>
                    <small class="text-muted p-t-30">Status akun</small>
                    <h6><span class="label label-danger" id="status"></span></h6>
                    <small class="text-muted p-t-30">Last IP</small>
                    <h6 id="lastip"></h6>
                    <small class="text-muted p-t-30">Last login</small>
                    <h6 id="lastlogin"></h6>

                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary waves-effect" data-dismiss="modal">CLOSE</button>
            </div>
        </div>
    </div>
</div>
<!-- /.sync -->

<!-- modal edit operator lokal -->
<div id="EditModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h4 class="modal-title">Edit Operator</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <!--isi modal-->
                @if (Auth::user())
                @if (Auth::user()->level > 5)
                    <form class="m-t-10" name="formEditOperator" id="formEditOperator" method="post" action="{{route('operator.superupdate')}}">
                    @csrf
                    @include('operator.formeditsuper')
                @elseif (Auth::user()->level == 5)
                    <form class="m-t-10" name="formEditOperator" id="formEditOperator" method="post" action="{{route('operator.adminprovupdate')}}">
                    @csrf
                    @include('operator.formeditadminprov')
                @elseif (Auth::user()->level == 4)
                    <form class="m-t-10" name="formEditOperator" id="formEditOperator" method="post" action="{{route('operator.adminkabupdate')}}">
                    @csrf
                    @include('operator.formeditadminkab')
                @endif
                @endif
                <input type="hidden" name="operator_id" id="edit_operator_id" value="" />


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary waves-effect" data-dismiss="modal">CLOSE</button>
                <button type="submit" class="btn btn-success waves-effect waves-light">UPDATE</button>
            </div>
        </form>
        </div>
    </div>
</div>
<!-- /.modal edit pegawai -->

<!-- modal tambah operator -->
<div id="TambahOperator" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h4 class="modal-title">Tambah Operator</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <!--isi modal-->
                @if (Auth::user())
                    @if (Auth::user()->role > 5)
                        <form class="m-t-10" name="FormTambahOperator" id="FormTambahOperator" method="post" action="{{ route('operator.supersimpan') }}">
                        @csrf
                        @include('operator.formsuper')
                    @elseif (Auth::user()->role == 5)
                        <form class="m-t-10" name="formTambahOperator" id="FormTambahOperator" method="post" action="{{route('operator.adminprovsimpan')}}">
                        @csrf
                        @include('operator.formadminprov')
                    @elseif (Auth::user()->role == 3)
                        <form class="m-t-10" name="formTambahOperator" method="post" action="{{route('operator.adminkabsimpan')}}">
                        @csrf
                        @include('operator.formadminkab')
                    @endif
                @endif

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary waves-effect" data-dismiss="modal">CLOSE</button>
                <button type="submit" class="btn btn-success waves-effect waves-light" id="btn_tambahoperator">SIMPAN</button>
            </div>
        </form>
        </div>
    </div>
</div>
<!-- /.tambah operator -->

<!-- modal tambah operator -->
<div id="GantiPasswordModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h4 class="modal-title">Ganti Password Operator</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <!--isi modal-->
                <form class="m-t-10" name="formGantiPassword" method="post" action="{{route('operator.gantipasswd')}}">
                 @csrf
                 <input type="hidden" name="operator_id" id="pass_operator_id" value="" />
                 <div class="form-group">
                    <label for="peg_nama">Nama</label>
                    <div class="controls">
                    <input type="text" class="form-control" id="pass_operator_nama" name="operator_nama" readonly>
                    </div>
                </div>
                <div class="form-group">
                    <label for="peg_password">Password Baru</label>
                    <div class="controls">
                    <input type="password" class="form-control" id="pass_operator_password_baru" name="operator_password_baru" placeholder="Password" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="peg_password_ulangi">Ulangi Password Baru</label>
                    <div class="controls">
                    <input type="password" class="form-control" id="pass_password_baru_ulangi" name="operator_password_baru_ulangi" placeholder="Ulangi Password" required>
                    </div>
                </div>
                <div class="form-group">
                   <span id="pesan_error"></span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary waves-effect" data-dismiss="modal">CLOSE</button>
                <button type="submit" class="btn btn-success waves-effect waves-light" id="btn_gantipasswd">GANTI PASSWORD</button>
            </div>
        </form>
        </div>
    </div>
</div>
<!-- /.tambah operator -->

<!-- modal tambah operator -->
<div id="EditHakAksesModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="EditHakAksesModal" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h4 class="modal-title">Edit Hak Akses Operator Provinsi</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <!--isi modal-->
                <form class="m-t-10" name="formHakAkses" method="post" action="{{route('operator.updatehakakses')}}">
                    @csrf
                    <input type="hidden" name="hak_opid" id="hak_opid" value="" />
                @include('operator.formhakakses')
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary waves-effect" data-dismiss="modal">CLOSE</button>
                <button type="submit" class="btn btn-success waves-effect waves-light" id="btn_hak_akses">UPDATE HAK AKSES</button>
            </div>
        </form>
        </div>
    </div>
</div>
<!-- /.tambah operator -->
