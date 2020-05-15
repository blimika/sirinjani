@if (Auth::user())
    @if (Auth::user()->level > 4)
    <div class="form-group">
        <label for="wilayah">Wilayah</label>
        <div class="controls">
        <select class="form-control" name="wilayah" id="wilayah" required>
            <option value="">Pilih Wilayah</option>
            @foreach ($dataWilayah as $item)
                <option value="{{$item->bps_kode}}">{{$item->bps_nama}}</option>
            @endforeach
        </select>
        </div>
    </div>
    @else 
    <div class="form-group">
        <label for="wilayah_nama">Wilayah</label>
        <div class="controls">
        <input type="text" class="form-control" id="wilayah_nama" name="wilayah_nama" value="{{Auth::user()->NamaWilayah->bps_nama}}" readonly>
        </div>
    </div>
    <input type="hidden" name="wilayah" id="wilayah" value="{{Auth::user()->kodebps}}" />
    @endif
@endif

<div class="form-group">
    <label for="peg_nama">Nama Lengkap</label>
    <div class="controls">
    <input type="text" class="form-control" id="peg_nama" name="peg_nama" placeholder="Nama Operator" required>
    </div>
</div>
<div class="form-group">
    <label for="peg_username">Username</label>
    <div class="controls">
    <input type="text" class="form-control" id="peg_username" name="peg_username" placeholder="Username untuk login" required>
    </div>
</div>
<div class="form-group">
    <label for="peg_password">Password</label>
    <div class="controls">
    <input type="password" class="form-control" id="peg_password" name="peg_password" placeholder="Password" required>
    </div>
</div>
<div class="form-group">
    <label for="peg_ulangi_password">Ulangi Password</label>
    <div class="controls">
    <input type="password" class="form-control" id="peg_ulangi_password" name="peg_ulangi_password" placeholder="Ulangi Password" required>
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