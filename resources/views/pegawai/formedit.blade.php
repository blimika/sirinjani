<div class="form-group">
    <label for="wilayah_nama">Wilayah</label>
    <div class="controls">
    <input type="text" class="form-control" id="wilayah_nama" name="wilayah_nama" value="" readonly>
    </div>
</div>
<div class="form-group">
    <label for="level">Level</label>
    <div class="controls">
    <select class="form-control" name="peg_level" id="peg_level" required>
        <option value="">Pilih Level</option>
        @foreach ($dataLevel as $l)
            <option value="{{$l->level_id}}">{{$l->level_nama}}</option>
        @endforeach
    </select>
    </div>
</div>
@if (Auth::user()->NamaWilayah->bps_jenis==1)
<div class="form-group">
    <label for="peg_kodeunit">Unitkerja</label>
    <div class="controls">
    <select class="form-control" name="peg_kodeunit" id="peg_kodeunit">
        <option value="">Pilih Unitkerja</option>
        @foreach ($dataUnitkerja as $item)
            <option value="{{$item->unit_kode}}">{{$item->unit_nama}}</option>
        @endforeach
    </select>
    </div>
</div>
@endif
<div class="form-group">
    <label for="peg_username">Username</label>
    <div class="controls">
    <input type="text" class="form-control" id="peg_username" name="peg_username" placeholder="Username untuk login" readonly>
    </div>
</div>
<div class="form-group">
    <label for="peg_nama">Nama Lengkap</label>
    <div class="controls">
    <input type="text" class="form-control" id="peg_nama" name="peg_nama" placeholder="Nama Operator" required>
    </div>
</div>
<div class="form-group">
    <label for="peg_email">E-mail</label>
    <div class="controls">
    <input type="text" class="form-control" id="peg_email" name="peg_email" placeholder="Email untuk sent password" required>
    </div>
</div>
<div class="form-group">
    <label for="peg_nohp">WhatsApp</label>
    <div class="controls">
    <input type="text" class="form-control" id="peg_nohp" name="peg_nohp" placeholder="Nomor WhatsApp">
    </div>
</div>