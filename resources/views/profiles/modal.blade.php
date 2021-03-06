<!-- modal data -->
<div id="EditModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="EditModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Edit Data</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <!--isi modal-->
                <form class="m-t-10" name="formEditProfiles" method="post" action="{{route('profile.update')}}">
                @csrf
                <div class="form-group">
                    <label for="operator_nama">Nama Lengkap</label>
                    <div class="controls">
                    <input type="text" class="form-control" id="operator_nama" name="operator_nama" placeholder="Nama Operator" value="{{Auth::user()->nama}}" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="operator_email">E-mail</label>
                    <div class="controls">
                    <input type="text" class="form-control" id="operator_email" name="operator_email" placeholder="Email untuk sent password" value="{{Auth::user()->email}}" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="operator_no_wa">WhatsApp</label>
                    <div class="controls">
                    <input type="text" class="form-control" id="operator_no_wa" name="operator_no_wa" placeholder="Nomor WhatsApp" value="{{Auth::user()->nohp}}">
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
<!-- modal tambah operator -->
<div id="GantiPasswordModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Ganti Password</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <!--isi modal-->
                <form class="m-t-10" name="formGantiPassword" method="post" action="{{route('profile.gantipassword')}}">
                 @csrf
                 <div class="form-group">
                    <label for="peg_password">Password Lama</label>
                    <div class="controls">
                    <input type="password" class="form-control" id="passwd_lama" name="passwd_lama" placeholder="Password Lama" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="passwd_baru">Password Baru</label>
                    <div class="controls">
                    <input type="password" class="form-control" id="passwd_baru" name="passwd_baru" placeholder="Password" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="passwd_baru_ulangi">Ulangi Password Baru</label>
                    <div class="controls">
                    <input type="password" class="form-control" id="passwd_baru_ulangi" name="passwd_baru_ulangi" placeholder="Ulangi Password" required>
                    </div>
                </div>
                <div class="form-group">
                   <span id="pesan_error" class="text-danger"></span>
                </div>
            </span>
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
