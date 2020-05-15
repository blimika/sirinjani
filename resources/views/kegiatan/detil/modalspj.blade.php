<!-- modal pengiriman spj-->
<div id="PengirimanSpjModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Konfirmasi Pengiriman SPJ</h4>
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
                <form class="m-t-10" name="formTerimaSpj" method="post" action="{{route('spj.pengiriman')}}">
                 @csrf
                 <input type="hidden" name="keg_id" id="keg_id" value="" />
                 <input type="hidden" name="spj_r_unitkerja" id="spj_r_unitkerja" value="" />
                <div class="form-group">
                    <label for="spj_r_tgl">Tanggal Pengiriman SPJ</label>
                    <div class="controls">
                    <input type="text" class="form-control tglkirimspj" id="spj_r_tgl" name="spj_r_tgl" placeholder="Tanggal Kirim SPJ" autocomplete="off" required>
                    </div>
                </div> 
                <div class="form-group">
                    <label for="spj_r_jumlah">Jumlah</label>
                    <div class="controls">
                    <input type="number" class="form-control" id="spj_r_jumlah" name="spj_r_jumlah" placeholder="Jumlah di Kirim" required>
                    </div>
                </div> 
                <div class="form-group">
                    <label for="spj_r_ket">Dikirim melalui</label>
                    <div class="controls">
                    <input type="text" class="form-control" id="spj_r_ket" name="spj_r_ket" placeholder="dikirim via apa? cth: Email, WhatsApp, Telegram, dst" required>
                    </div>
                </div>  
                <div class="form-group">
                    <label for="spj_r_link">Link Download</label>
                    <div class="controls">
                    <input type="text" class="form-control" id="spj_r_link" name="spj_r_link" placeholder="Url : https://...link download bila dikirim via cloud">
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

<!-- modal edit pengiriman spj-->
<div id="EditPengirimanSpjModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Konfirmasi Edit Pengiriman SPJ</h4>
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
                <form class="m-t-10" name="formTerimaSpj" method="post" action="{{route('spj.updatepengiriman')}}">
                 @csrf
                 <input type="hidden" name="keg_id" id="keg_id" value="" />
                 <input type="hidden" name="spj_r_id" id="spj_r_id" value="" />
                 <input type="hidden" name="spj_r_unitkerja" id="spj_r_unitkerja" value="" />
                <div class="form-group">
                    <label for="spj_r_tgl">Tanggal Pengiriman SPJ</label>
                    <div class="controls">
                    <input type="text" class="form-control tglkirimspj" id="spj_r_tgl" name="spj_r_tgl" placeholder="Tanggal Kirim SPJ" autocomplete="off" required>
                    </div>
                </div> 
                <div class="form-group">
                    <label for="spj_r_jumlah">Jumlah</label>
                    <div class="controls">
                    <input type="number" class="form-control" id="spj_r_jumlah" name="spj_r_jumlah" placeholder="Jumlah di Kirim" required>
                    </div>
                </div> 
                <div class="form-group">
                    <label for="spj_r_ket">Dikirim melalui</label>
                    <div class="controls">
                    <input type="text" class="form-control" id="spj_r_ket" name="spj_r_ket" placeholder="dikirim via apa? cth: Email, WhatsApp, Telegram, dst" required>
                    </div>
                </div>  
                <div class="form-group">
                    <label for="spj_r_link">Link Download</label>
                    <div class="controls">
                    <input type="text" class="form-control" id="spj_r_link" name="spj_r_link" placeholder="Url : https://...link download bila dikirim via cloud">
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
<!-- /.edit pengiriman spj modal -->

<!-- modal penerimaan spj -->
<div id="PenerimaanSpjModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Konfirmasi Penerimaan SPJ</h4>
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
                    <dt class="col-sm-4">Target SPJ</dt>
                    <dd class="col-sm-8"><span id="keg_target"></span></dd>
                    <dt class="col-sm-4">Total Pengiriman SPJ</dt>
                    <dd class="col-sm-8"><span id="total_pengiriman"></span></dd>
                </dl>
                <form class="m-t-10" name="formPenerimaanSpj" method="post" action="{{route('spj.penerimaan')}}">
                 @csrf
                 <input type="hidden" name="keg_id" id="keg_id" value="" />
                 <input type="hidden" name="spj_r_unitkerja" id="spj_r_unitkerja" value="" />
                <div class="form-group">
                    <label for="keg_r_tgl">Tanggal Penerimaan SPJ</label>
                    <div class="controls">
                    <input type="text" class="form-control tglterimaspj" id="spj_r_tgl" name="spj_r_tgl" placeholder="Tanggal Kirim" autocomplete="off" required>
                    </div>
                </div> 
                <div class="form-group">
                    <label for="spj_r_jumlah">Jumlah SPJ diterima</label>
                    <div class="controls">
                    <input type="number" class="form-control" id="spj_r_jumlah" name="spj_r_jumlah" placeholder="Jumlah SPJ di terima" required>
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

<!-- modal penerimaan spj -->
<div id="EditPenerimaanSpjModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Edit Konfirmasi Penerimaan SPJ</h4>
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
                    <dt class="col-sm-4">Target SPJ</dt>
                    <dd class="col-sm-8"><span id="keg_target"></span></dd>
                    <dt class="col-sm-4">Total Pengiriman SPJ</dt>
                    <dd class="col-sm-8"><span id="total_pengiriman"></span></dd>
                </dl>
                <form class="m-t-10" name="formPenerimaanSpj" method="post" action="{{route('spj.updatepenerimaan')}}">
                 @csrf
                 <input type="hidden" name="keg_id" id="keg_id" value="" />
                 <input type="hidden" name="spj_r_id" id="spj_r_id" value="" />
                <div class="form-group">
                    <label for="keg_r_tgl">Tanggal Penerimaan SPJ</label>
                    <div class="controls">
                    <input type="text" class="form-control tglterimaspj" id="spj_r_tgl" name="spj_r_tgl" placeholder="Tanggal Kirim" autocomplete="off" required>
                    </div>
                </div> 
                <div class="form-group">
                    <label for="spj_r_jumlah">Jumlah SPJ diterima</label>
                    <div class="controls">
                    <input type="number" class="form-control" id="spj_r_jumlah" name="spj_r_jumlah" placeholder="Jumlah SPJ di terima" required>
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