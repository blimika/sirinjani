<!-- modal sync -->
<div id="SyncDataModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Sync data Community</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <!--isi modal-->
                <form class="m-t-10" name="formSyncData" method="post" action="{{route('pegawai.sync')}}">
                 @csrf
                 <div class="form-group">
                    <label for="wilayah">Wilayah</label>
                    <div class="controls">
                    <select class="form-control" name="wilayah" id="wilayah" required>
                        <option value="">Pilih Wilayah</option>
                        @foreach ($dataWilayah as $item)
                            <option value="{{$item->bps_kode}}">{{$item->bps_nama}}</option>
                        @endforeach
                    </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="peg_username">Username Community</label>
                    <div class="controls">
                    <input type="text" class="form-control" id="peg_username" name="peg_username" placeholder="Username" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="peg_password">Password Community</label>
                    <div class="controls">
                    <input type="password" class="form-control" id="peg_password" name="peg_password" placeholder="Password" required>
                    </div>
                </div>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary waves-effect" data-dismiss="modal">CLOSE</button>
                <button type="submit" class="btn btn-success waves-effect waves-light" >SYNC</button>
            </div>
        </form>
        </div>
    </div>
</div>
<!-- /.sync -->

<!-- modal detil -->
<div id="DetilModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Detil Pegawai</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <!--isi modal-->
                <center class="m-t-30"> <img src="" id="profil" class="img-circle" width="150px" height="150px" />
                    <h3 class="card-title m-t-10" id="nama"></h3>
                    <h6 class="card-subtitle">(<span id="username"></span>)</h6>
                    <h5 class="card-title m-t-10" id="level"></h5>
                    <h6 class="card-subtitle" id="unitnama"></h6>
                    <h6 class="card-subtitle m-t-5" id="bpskode"></h6>
                    <div class="row text-center justify-content-md-center">
                        <div class="col-12" id="nip"></div>
                    </div>
                </center>
                <div>
                    <hr> </div>
                <div class="card-body"> 
                    <small class="text-muted">Alamat E-mail </small>
                    <h6 id="email"></h6> 
                    <small class="text-muted p-t-30 db">Akses akun</small>
                    <h6 id="akses"></h6> 
                    <small class="text-muted p-t-30 db">Status akun</small>
                    <h6 id="status"></h6>
                    <small class="text-muted p-t-30 db">Last IP</small>
                    <h6 id="lastip"></h6>
                    <small class="text-muted p-t-30 db">Last login</small>
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

<!-- modal edit pegawai -->
<div id="EditPegModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Edit Akses Pegawai</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <!--isi modal-->
                <form class="m-t-10" name="formEditPegawai" method="post" action="{{route('pegawai.updatenet')}}">
                    @csrf
                    <input type="hidden" name="peg_id" id="peg_id" value="" />
                    <div class="form-group">
                       <label for="peg_nama">Nama</label>
                       <div class="controls">
                       <input type="text" class="form-control" id="peg_nama" name="peg_nama" placeholder="Nama" readonly>
                       </div>
                   </div>
                   <div class="form-group">
                    <label for="peg_nipbps">NIP</label>
                    <div class="controls">
                    <input type="text" class="form-control" id="peg_nipbps" name="peg_nipbps" placeholder="NIP BPS" readonly>
                    </div>
                   </div>
                   <div class="form-group">
                       <label for="peg_unitkerja">Unitkerja</label>
                       <div class="controls">
                       <input type="text" class="form-control" id="peg_unitkerja" name="peg_unitkerja" placeholder="unitkerja" readonly>
                       </div>
                   </div>
                   <div class="form-group">
                    <label for="peg_level">Level Akses</label>
                    <div class="controls">
                    <select class="form-control" name="peg_level" id="peg_level" required>
                        <option value="">Pilih Level Akses</option>
                        @foreach ($dataLevel as $l)
                            <option value="{{$l->level_id}}">{{$l->level_nama}}</option>
                        @endforeach
                    </select>
                    </div>
                </div>
                   <div class="form-group">
                    <label for="peg_nohp">No WhatsApp</label>
                    <div class="controls">
                    <input type="text" class="form-control" id="peg_nohp" name="peg_nohp" placeholder="No WhatsApp">
                    </div>
                    </div>
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

<!-- modal edit operator lokal -->
<div id="EditLokalModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Edit Operator Lokal</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <!--isi modal-->
                <form class="m-t-10" name="formEditLokal" method="post" action="{{route('pegawai.updatelokal')}}">
                @csrf
                <input type="hidden" name="peg_id" id="peg_id" value="" />
                @include('pegawai.formedit')
                
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
            <div class="modal-header">
                <h4 class="modal-title">Tambah Lokal Operator</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <!--isi modal-->
                <form class="m-t-10" name="formTambahOperator" method="post" action="{{route('pegawai.simpan')}}">
                 @csrf
                 @include('pegawai.formopt')
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary waves-effect" data-dismiss="modal">CLOSE</button>
                <button type="submit" class="btn btn-success waves-effect waves-light" >SIMPAN</button>
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
            <div class="modal-header">
                <h4 class="modal-title">Ganti Password Operator Lokal</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <!--isi modal-->
                <form class="m-t-10" name="formGantiPassword" method="post" action="{{route('pegawai.gantipasswd')}}">
                 @csrf
                 <input type="hidden" name="peg_id" id="peg_id" value="" />
                 <div class="form-group">
                    <label for="peg_nama">Nama</label>
                    <div class="controls">
                    <input type="text" class="form-control" id="peg_nama" name="peg_nama" readonly>
                    </div>
                </div>
                <div class="form-group">
                    <label for="peg_password">Password Baru</label>
                    <div class="controls">
                    <input type="password" class="form-control" id="peg_password_baru" name="peg_password_baru" placeholder="Password" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="peg_password_ulangi">Ulangi Password Baru</label>
                    <div class="controls">
                    <input type="password" class="form-control" id="peg_password_baru_ulangi" name="peg_password_baru_ulangi" placeholder="Ulangi Password" required>
                    </div>
                </div>
                <div class="form-group">
                   <span id="pesan_error"></span>
                </div>
            </span>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary waves-effect" data-dismiss="modal">CLOSE</button>
                <button type="submit" class="btn btn-success waves-effect waves-light" id="btn_gantipasswd">GANTI PASSWORD</button>
            </div>
        </form>
        </div>
    </div>
</div>
<!-- /.tambah operator -->