<!-- modal penerimaan -->
<div id="PenerimaanModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Konfirmasi Penerimaan</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <!--isi modal-->
                <dl class="row">
                    <dt class="col-sm-4">Nama Kegiatan</dt>
                    <dd class="col-sm-8"><span id="keg_nama"></span></dd>
                    <dt class="col-sm-4">SM</dt>
                    <dd class="col-sm-8"><span id="sm"></span></dd>
                    <dt class="col-sm-4">Unitkerja</dt>
                    <dd class="col-sm-8"><span id="kabkota"></span></dd>
                    <dt class="col-sm-4">Batas waktu</dt>
                    <dd class="col-sm-8"><span id="keg_end"></span></dd>
                    <dt class="col-sm-4">Target</dt>
                    <dd class="col-sm-8"><span id="keg_target"></span></dd>
                    <dt class="col-sm-4">Total Pengiriman</dt>
                    <dd class="col-sm-8"><span id="total_pengiriman"></span></dd>
                </dl>
                <form class="m-t-10" name="formPenerimaan" method="post" action="{{route('kegiatan.penerimaan')}}">
                 @csrf
                 <input type="hidden" name="keg_id" id="keg_id" value="" />
                 <input type="hidden" name="keg_r_unitkerja" id="keg_r_unitkerja" value="" />
                <div class="form-group">
                    <label for="keg_r_tgl">Tanggal Penerimaan</label>
                    <div class="controls">
                    <input type="text" class="form-control tglterima" id="keg_r_tgl" name="keg_r_tgl" placeholder="Tanggal Kirim" autocomplete="off" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="keg_r_jumlah">Jumlah diterima</label>
                    <div class="controls">
                    <input type="number" class="form-control" id="keg_r_jumlah" name="keg_r_jumlah" placeholder="Jumlah di Kirim" required>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary waves-effect" data-dismiss="modal">CLOSE</button>
                <button type="submit" class="btn btn-success waves-effect waves-light" >TERIMA</button>
            </div>
        </form>
        </div>
    </div>
</div>
<!-- /.penerimaan -->

<!-- modal edit penerimaan -->
<div id="EditPenerimaanModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Edit Konfirmasi Penerimaan</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <!--isi modal-->
                <dl class="row">
                    <dt class="col-sm-4">Nama Kegiatan</dt>
                    <dd class="col-sm-8"><span id="keg_nama"></span></dd>
                    <dt class="col-sm-4">SM</dt>
                    <dd class="col-sm-8"><span id="sm"></span></dd>
                    <dt class="col-sm-4">Unitkerja</dt>
                    <dd class="col-sm-8"><span id="kabkota"></span></dd>
                    <dt class="col-sm-4">Batas waktu</dt>
                    <dd class="col-sm-8"><span id="keg_end"></span></dd>
                    <dt class="col-sm-4">Target</dt>
                    <dd class="col-sm-8"><span id="keg_target"></span></dd>
                </dl>
                <form class="m-t-10" name="formPenerimaan" method="post" action="{{route('penerimaan.update')}}">
                 @csrf
                 <input type="hidden" name="keg_r_id" id="keg_r_id" value="" />
                 <input type="hidden" name="keg_id" id="keg_id" value="" />
                <div class="form-group">
                    <label for="keg_r_tgl">Tanggal Penerimaan</label>
                    <div class="controls">
                    <input type="text" class="form-control tglterima" id="keg_r_tgl" name="keg_r_tgl" placeholder="Tanggal Kirim" autocomplete="off" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="keg_r_jumlah">Jumlah</label>
                    <div class="controls">
                    <input type="number" class="form-control" id="keg_r_jumlah" name="keg_r_jumlah" placeholder="Jumlah di Kirim" required>
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
<!-- /.edit penerimaan modal -->

<!-- modal pengiriman -->
<div id="PengirimanModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Konfirmasi Pengiriman</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <!--isi modal-->
                <dl class="row">
                    <dt class="col-sm-4">Nama Kegiatan</dt>
                    <dd class="col-sm-8"><span id="keg_nama"></span></dd>
                    <dt class="col-sm-4">SM</dt>
                    <dd class="col-sm-8"><span id="sm"></span></dd>
                    <dt class="col-sm-4">Unitkerja</dt>
                    <dd class="col-sm-8"><span id="kabkota"></span></dd>
                    <dt class="col-sm-4">Batas waktu</dt>
                    <dd class="col-sm-8"><span id="keg_end"></span></dd>
                    <dt class="col-sm-4">Target</dt>
                    <dd class="col-sm-8"><span id="keg_target"></span></dd>
                </dl>
                <form class="m-t-10" name="formPenerimaan" method="post" action="{{route('kegiatan.pengiriman')}}">
                 @csrf
                 <input type="hidden" name="keg_id" id="keg_id" value="" />
                 <input type="hidden" name="keg_r_unitkerja" id="keg_r_unitkerja" value="" />
                <div class="form-group">
                    <label for="keg_r_tgl">Tanggal Pengiriman</label>
                    <div class="controls">
                    <input type="text" class="form-control tglkirim" id="keg_r_tgl" name="keg_r_tgl" placeholder="Tanggal Kirim" autocomplete="off" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="keg_r_jumlah">Jumlah</label>
                    <div class="controls">
                    <input type="number" class="form-control" id="keg_r_jumlah" name="keg_r_jumlah" placeholder="Jumlah di Kirim" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="keg_r_ket">Dikirim melalui</label>
                    <div class="controls">
                    <input type="text" class="form-control" id="keg_r_ket" name="keg_r_ket" placeholder="dikirim via apa? cth: Email, WhatsApp, Telegram, dst" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="keg_r_link">Link Download</label>
                    <div class="controls">
                    <input type="text" class="form-control" id="keg_r_link" name="keg_r_link" placeholder="Url : https://...link download bila dikirim via cloud">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary waves-effect" data-dismiss="modal">CLOSE</button>
                <button type="submit" class="btn btn-success waves-effect waves-light" >KIRIM</button>
            </div>
        </form>
        </div>
    </div>
</div>
<!-- /.pengiriman modal -->

<!-- modal edit pengiriman -->
<div id="EditPengirimanModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Edit Konfirmasi Pengiriman</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <!--isi modal-->
                <dl class="row">
                    <dt class="col-sm-4">Nama Kegiatan</dt>
                    <dd class="col-sm-8"><span id="keg_nama"></span></dd>
                    <dt class="col-sm-4">SM</dt>
                    <dd class="col-sm-8"><span id="sm"></span></dd>
                    <dt class="col-sm-4">Unitkerja</dt>
                    <dd class="col-sm-8"><span id="kabkota"></span></dd>
                    <dt class="col-sm-4">Batas waktu</dt>
                    <dd class="col-sm-8"><span id="keg_end"></span></dd>
                    <dt class="col-sm-4">Target</dt>
                    <dd class="col-sm-8"><span id="keg_target"></span></dd>
                </dl>
                <form class="m-t-10" name="formPenerimaan" method="post" action="{{route('pengiriman.update')}}">
                 @csrf
                 <input type="hidden" name="keg_r_id" id="keg_r_id" value="" />
                 <input type="hidden" name="keg_id" id="keg_id" value="" />
                <div class="form-group">
                    <label for="keg_r_tgl">Tanggal Pengiriman</label>
                    <div class="controls">
                    <input type="text" class="form-control tglkirim" id="keg_r_tgl" name="keg_r_tgl" placeholder="Tanggal Kirim" autocomplete="off" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="keg_r_jumlah">Jumlah</label>
                    <div class="controls">
                    <input type="number" class="form-control" id="keg_r_jumlah" name="keg_r_jumlah" placeholder="Jumlah di Kirim" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="keg_r_ket">Dikirim melalui</label>
                    <div class="controls">
                    <input type="text" class="form-control" id="keg_r_ket" name="keg_r_ket" placeholder="dikirim via apa? cth: Email, WhatsApp, Telegram, dst" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="keg_r_link">Link Download</label>
                    <div class="controls">
                    <input type="text" class="form-control" id="keg_r_link" name="keg_r_link" placeholder="Url : https://...link download bila dikirim via cloud">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary waves-effect" data-dismiss="modal">CLOSE</button>
                <button type="submit" class="btn btn-success waves-effect waves-light" >UPDATE</button>
            </div>
        </form>
        </div>
    </div>
</div>
<!-- /.edit pengiriman modal -->

<!-- modal edit info lanjutan -->
<div id="EditInfoLanjutanModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Edit Informasi Lanjutan</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <!--isi modal-->
                <dl class="row">
                    <dt class="col-sm-4">Nama Kegiatan</dt>
                    <dd class="col-sm-8"><span id="keg_nama"></span></dd>
                    <dt class="col-sm-4">SM</dt>
                    <dd class="col-sm-8"><span id="sm"></span></dd>
                    <dt class="col-sm-4">Tanggal Mulai</dt>
                    <dd class="col-sm-8"><span id="keg_start"></span></dd>
                    <dt class="col-sm-4">Batas waktu</dt>
                    <dd class="col-sm-8"><span id="keg_end"></span></dd>
                    <dt class="col-sm-4">Target</dt>
                    <dd class="col-sm-8"><span id="keg_target"></span></dd>
                </dl>
                <form class="m-t-10" name="formInfoLanjutan" method="post" action="{{route('info.update')}}">
                 @csrf
                 <input type="hidden" name="keg_id" id="keg_id" value="" />
                <div class="form-group">
                    <label for="keg_r_tgl">Informasi</label>
                    <div class="controls">
                        <textarea class="form-control" rows="8" name="keg_info" id="keg_info"></textarea>
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
<!-- /.edit info lanjutan modal -->
