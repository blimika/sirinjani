<form method="POST" name="kegiatan" action="{{route('kegiatan.simpan') }}">
    @csrf
    <div class="form-group">
        <label for="keg_nama">Nama Kegiatan</label>
        <input type="text" class="form-control" id="keg_nama" name="keg_nama" value="" placeholder="Nama Kegiatan" required>
    </div>
    <div class="form-group">
        <label for="keg_unitkerja">Unit Kerja</label>
        <select name="keg_unitkerja" id="keg_unitkerja" class="form-control" required>
            <option value="">Pilih Unitkerja</option>
            @foreach ($unitProv as $u)
                <option value="{{$u->unit_kode}}">{{$u->unit_nama}}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label for="keg_jenis">Jenis Kegiatan</label>
        <select name="keg_jenis" id="keg_jenis" class="form-control" required>
            <option value="">Pilih Jenis Kegiatan</option>
            @foreach ($kegJenis as $item)
                <option value="{{$item->jkeg_id}}">{{$item->jkeg_nama}}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label for="keg_start">Durasi Tanggal Kegiatan</label>
        <div class="input-daterange input-group" id="tanggal_kegiatan">
            <input type="text" class="form-control" id="keg_start" name="keg_start" autocomplete="off" placeholder="Tanggal kegiatan mulai" required>
            <div class="input-group-append" >
                <span class="input-group-text bg-info b-0 text-white">s/d</span>
            </div>
            <input type="text" class="form-control" id="keg_end" name="keg_end" autocomplete="off" placeholder="Tanggal kegiatan selesai" required>
        </div>
            
    </div>
    <div class="form-group">
        <label for="keg_satuan">Satuan</label>
        <input type="text" class="form-control" id="keg_satuan" name="keg_satuan" placeholder="kegiatan satuan" required>
    </div>
    <div class="form-group">
        <label for="keg_total_target">Total Target</label>
        <input type="text" class="form-control" id="keg_total_target" name="keg_total_target" value="" placeholder="kegiatan satuan" required>
    </div>
    <div class="form-group">
        <label for="keg_total_target">Laporan SPJ</label>
        <div class="custom-control custom-radio">
            <input type="radio" id="spj1" name="keg_spj" class="custom-control-input" value="1">
            <label class="custom-control-label" for="spj1">Ada SPJ</label>
        </div>
        <div class="custom-control custom-radio">
            <input type="radio" id="spj2" name="keg_spj" class="custom-control-input" value="2" required checked="checked">
            <label class="custom-control-label" for="spj2">Tidak Ada</label>
        </div>
    </div>
   