<dl class="row">
    <dt class="col-lg-4 col-md-4">ID</dt>
    <dd class="col-lg-8 col-sm-8"><span id="hak_op_id"></span></dd>
    <dt class="col-lg-4 col-md-4">Nama</dt>
    <dd class="col-lg-8 col-sm-8"><span id="hak_nama"></span></dd>
    <dt class="col-lg-4 col-md-4">Username</dt>
    <dd class="col-lg-8 col-sm-8"><span id="hak_username"></span></dd>
    <dt class="col-lg-4 col-md-4">Tim Kerja Utama</dt>
    <dd class="col-lg-8 col-sm-8"><span id="hak_timkerja"></span></dd>
</dl>
<div class="form-group">
    <label for="edit_adminprov_username">Hak Akses</label>
    <select class="hak_akses_list m-b-10 select2-multiple" style="width: 100%" multiple="multiple" data-placeholder="Choose" name="hak_akses[]" id="hak_akses_list" required>
        @foreach ($dataFungsi->where('unit_eselon','3') as $item)
            <option value="{{$item->unit_kode}}">[{{$item->unit_kode}}] {{$item->unit_nama}}</option>
        @endforeach
    </select>
</div>
