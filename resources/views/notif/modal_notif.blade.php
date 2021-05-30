<!-- modal view notifikasi -->
<div id="ViewNotifikasi" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="ViewNotifikasi" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Lihat Notifikasi</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <!--isi modal-->
                <dl class="row">
                    <dt class="col-sm-4">Dari</dt>
                    <dd class="col-sm-8"><span id="notif_dari"></span></dd>
                    <dt class="col-sm-4">Untuk</dt>
                    <dd class="col-sm-8"><span id="notif_untuk"></span></dd>
                    <dt class="col-sm-4">Jenis</dt>
                    <dd class="col-sm-8"><span id="notif_jenis"></span></dd>
                    <dt class="col-sm-4">Tanggal dibuat</dt>
                    <dd class="col-sm-8"><span id="notif_tgl_dibuat"></span></dd>
                    <dt class="col-sm-4">Tanggal diupdate</dt>
                    <dd class="col-sm-8"><span id="notif_tgl_diupdate"></span></dd>
                </dl>
                <h4 id="notif_isi"></h4>
            </div>
               <div class="modal-footer">
                    <a href="" class="btn btn-success waves-effect waves-light" id="ViewKegiatanDetil">Kegiatan Detil</a>
                   <button type="button" class="btn btn-primary waves-effect" data-dismiss="modal">CLOSE</button>
               </div>
        </div>
    </div>
</div>
<!-- /.modal view notifikasi -->
