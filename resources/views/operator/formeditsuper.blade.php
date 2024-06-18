<div class="form-group">
    <label for="edit_super_wilayah">Wilayah</label>
    <div class="controls">
    <select class="form-control" name="wilayah" id="edit_super_wilayah" required>
        <option value="">Pilih Wilayah</option>
        @foreach ($dataWilayah as $item)
            <option value="{{$item->bps_kode}}">[{{$item->bps_kode}}] {{$item->bps_nama}}</option>
        @endforeach
    </select>
    </div>
</div>
<div class="form-group">
    <label for="edit_super_level">Level</label>
    <div class="controls">
    <select class="form-control" name="operator_level" id="edit_super_level" required>
        <option value="">Pilih Level Akses</option>
        @foreach ($dataLevel as $l)
            <option value="{{$l->level_id}}">{{$l->level_nama}}</option>
        @endforeach
    </select>
    </div>
</div>
<div class="form-group">
    <label for="edit_super_unitkode">Tim Kerja Utama</label>
    <div class="controls">
    <select class="form-control" name="unitkode_prov" id="edit_super_unitkode">
        <option value="">Pilih Tim Kerja Provinsi</option>
        @foreach ($dataFungsi as $item)
            <option value="{{$item->unit_kode}}">[{{$item->unit_kode}}] {{$item->unit_nama}}</option>
        @endforeach
    </select>
    </div>
</div>
<div class="form-group">
    <label for="edit_operator_nama">Nama Lengkap</label>
    <div class="controls">
    <input type="text" class="form-control" id="edit_operator_nama" name="operator_nama" placeholder="Nama Operator" required>
    </div>
</div>
<div class="form-group">
    <label for="edit_super_username">Username</label>
    <input type="text" class="form-control" id="edit_super_username" name="operator_username" placeholder="Username untuk login">

</div>
<div class="form-group">
    <label for="edit_operator_email">E-mail</label>
    <div class="controls">
    <input type="text" class="form-control" id="edit_operator_email" name="operator_email" placeholder="Email untuk sent password" required>
    </div>
</div>
<div class="form-group">
    <label for="edit_operator_no_wa">WhatsApp</label>
    <div class="controls">
    <input type="text" class="form-control" id="edit_operator_no_wa" name="operator_no_wa" placeholder="Nomor WhatsApp">
    </div>
</div>
