<div class="modal fade" id="TambahModal" tabindex="-1" role="dialog" aria-labelledby="vcenter">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h4 class="modal-title text-white">Tambah Unitkerja Provinsi</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form class="form-horizontal m-t-4" name="formTambahUnitkerjaProv" id="formTambahUnitkerjaProv" action="#"  method="POST">
                    <div class="form-group row">
                        <label class="control-label col-md-3">Unit Kode</label>
                        <div class="input-group col-md-9">
                            <input type="text" class="form-control" name="unit_kode" id="unit_kode" placeholder="Kode Unitkerja (max 5 digit)" />
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="control-label col-md-3">Unit Nama</label>
                        <div class="input-group col-md-9">
                            <input type="text" class="form-control" name="unit_nama" id="unit_nama" placeholder="Nama Unitkerja" />
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="input-group col-md-12"><i>*) Mulai tahun 2022, semua Bidang/Fungsi diubah ke TIM</i>
                        </div>
                    </div>
                    <div class="form-group">
                        <span id="unit_error" class="text-danger"></span>
                    </div>

            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success waves-effect" id="simpan_unitkerja" data-dismiss="modal">Simpan</button>
                <button type="button" class="btn btn-warning waves-effect" id="reset_unitkerja">RESET</button>
                <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">CLOSE</button>
            </div>
        </form>
        </div>
    </div>
</div>

<div class="modal fade" id="EditModal" tabindex="-1" role="dialog" aria-labelledby="vcenter">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h4 class="modal-title text-white">Edit Unitkerja Provinsi</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form class="form-horizontal m-t-4" name="formEditUnitkerjaProv" id="formEditUnitkerjaProv" action="#"  method="POST">
                    <div class="form-group row">
                        <label class="control-label col-md-3">ID</label>
                        <div class="input-group col-md-9">
                            <span id="edit_teks_id"></span>
                        </div>
                    </div>
                    <input type="hidden" name="edit_id" id="edit_id" />
                    <div class="form-group row">
                        <label class="control-label col-md-3">Unit Kode</label>
                        <div class="input-group col-md-9">
                            <input type="text" class="form-control" name="edit_unitkode" id="edit_unitkode" placeholder="Kode Unitkerja (max 5 digit)" />
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="control-label col-md-3">Unit Nama</label>
                        <div class="input-group col-md-9">
                            <input type="text" class="form-control" name="edit_unitnama" id="edit_unitnama" placeholder="Nama Unitkerja" />
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="control-label col-md-3">Unit Flag</label>
                        <div class="input-group col-md-9">
                            <select class="form-control" name="edit_unitflag" id="edit_unitflag">
                                <option value="">Pilih Flag</option>
                                <option value="0">Non Aktif</option>
                                <option value="1">Aktif</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="input-group col-md-12"><i>*) Mulai tahun 2022, semua Bidang/Fungsi diubah ke TIM</i>
                        </div>
                    </div>
                    <div class="form-group">
                        <span id="edit_uniterror" class="text-danger"></span>
                    </div>

            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success waves-effect" id="update_unitprov" data-dismiss="modal">UPDATE</button>
                <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">CLOSE</button>
            </div>
        </form>
        </div>
    </div>
</div>
