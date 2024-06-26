<div class="form-group">
    <label for="super_wilayah">Wilayah</label>
    <div class="controls">
    <select class="form-control" name="wilayah" id="super_wilayah" required>
        <option value="">Pilih Wilayah</option>
        @foreach ($dataWilayah as $item)
            <option value="{{$item->bps_kode}}">[{{$item->bps_kode}}] {{$item->bps_nama}}</option>
        @endforeach
    </select>
    </div>
</div>
<div class="form-group">
    <label for="super_level">Role</label>
    <div class="controls">
    <select class="form-control" name="operator_level" id="super_level" required>
        <option value="">Pilih Role</option>
        @foreach ($dataLevel as $l)
            <option value="{{$l->level_id}}">{{$l->level_nama}}</option>
        @endforeach
    </select>
    </div>
</div>
<div class="form-group">
    <label for="super_unitkode">Tim Kerja Utama</label>
    <div class="controls">
    <select class="form-control" name="unitkode_prov" id="super_unitkode">
        <option value="">Pilih Tim Kerja Provinsi</option>
        @foreach ($dataFungsi->where('unit_flag','1') as $item)
            <option value="{{$item->unit_kode}}">[{{$item->unit_kode}}] {{$item->unit_nama}}</option>
        @endforeach
    </select>
    </div>
</div>
<div class="form-group">
    <label for="operator_nama">Nama Lengkap</label>
    <div class="controls">
    <input type="text" class="form-control" id="operator_nama" name="operator_nama" placeholder="Nama Operator" required>
    </div>
</div>
<div class="form-group">
    <label for="super_username">Username</label>
    <div class="input-group">
    <input type="text" class="form-control" id="super_username" name="operator_username" placeholder="Username untuk login" required>
    <div class="input-group-append">
        <button type="button" class="btn btn-success" id="cek_username">CEK</button>
    </div>
    </div>
    <small class="text-danger" id="super_error_username"><span id="super_error_teks"></span></small>
</div>
<div class="form-group">
    <label for="operator_password">Password</label>
    <div class="controls">
    <input type="password" class="form-control" id="operator_password" name="operator_password" placeholder="Password" required>
    </div>
</div>
<div class="form-group">
    <label for="operator_ulangi_password">Ulangi Password</label>
    <div class="controls">
    <input type="password" class="form-control" id="operator_ulangi_password" name="operator_ulangi_password" placeholder="Ulangi Password" required>
    </div>
    <small class="text-danger" id="super_error_password"><span id="super_error_password_teks"></span></small>
</div>
<div class="form-group">
    <label for="operator_email">E-mail</label>
    <div class="controls">
    <input type="text" class="form-control" id="operator_email" name="operator_email" placeholder="Email untuk sent password" required>
    </div>
</div>
<div class="form-group">
    <label for="operator_no_wa">WhatsApp</label>
    <div class="controls">
    <input type="text" class="form-control" id="operator_no_wa" name="operator_no_wa" placeholder="Nomor WhatsApp">
    </div>
</div>
