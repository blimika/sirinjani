<div class="row">
    <div class="col-md-12">
<form class="form-horizontal">
      <div class="form-group row">
        <label for="jenis_log" class="col-sm-1 control-label">Filter</label>
        <div class="col-md-2">
            <select name="operator" id="operator" class="form-control">
            <option value="0">Pilih Operator</option>
            @foreach ($dataOperator as $op)
            <option value="{{$op->username}}" @if (request('operator')==$op->username or $operator==$op->username)
                selected
               @endif>{{$op->username}}</option>
            @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <select name="jenis_log" id="jenis_log" class="form-control">
            <option value="0">Pilih JenisLog</option>
            @foreach ($dataJenisLog as $jenis)
            <option value="{{$jenis->jlog_id}}" @if (request('jenis_log')==$jenis->jlog_id or $jenis_log==$jenis->jlog_id)
                selected
               @endif>[{{$jenis->jlog_id}}] {{$jenis->jlog_nama}}</option>
            @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <select name="bulan" id="bulan" class="form-control">
             <option value="0">Semua Bulan</option>
             @for ($i = 1; $i <= 12; $i++)
                 <option value="{{$i}}" @if (request('bulan')==$i or $bulan==$i)
                     selected
                 @endif>{{$dataBulan[$i]}}</option>
             @endfor
            </select>
        </div>
        <div class="col-md-2">
            <select name="tahun" id="tahun" class="form-control">
             @foreach ($dataTahun as $iTahun)
             <option value="{{$iTahun->tahun}}" @if (request('tahun')==$iTahun->tahun or $tahun==$iTahun->tahun)
             selected
            @endif>{{$iTahun->tahun}}</option>
             @endforeach
            </select>
        </div>

        <div class="col-md-2">
            <button type="submit" class="btn btn-success">Filter</button>
        </div>
      </div>
</form>
    </div>
</div>
