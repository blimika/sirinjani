<div class="form-group">
    <label for="edit_adminprov_level">Level</label>
    <div class="controls">
    <select class="form-control" name="operator_level" id="edit_adminprov_level" required>
        <option value="">Pilih Level Akses</option>
        @foreach ($dataLevel as $l)
            <option value="{{$l->level_id}}">{{$l->level_nama}}</option>
        @endforeach
    </select>
    </div>
</div>
<div class="form-group">
    <label for="edit_adminprov_unitkode">Unitkerja</label>
    <div class="controls">
    <select class="form-control" name="unitkode_prov" id="edit_adminprov_unitkode">
        <option value="">Pilih Unitkerja Provinsi</option>
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
    <label for="edit_adminprov_username">Username</label>
    <input type="text" class="form-control" id="edit_adminprov_username" name="operator_username" placeholder="Username untuk login">

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
