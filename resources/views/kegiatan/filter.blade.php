<div class="row">
    <div class="col-md-9">
<form class="form-horizontal">
      <div class="form-group row">
        <label for="bulan" class="col-sm-1 control-label">Filter</label>
        <div class="col-md-2">
            <select name="bulan" id="bulan" class="form-control">
             <option value="0">Pilih Bulan</option>
             @for ($i = 1; $i <= 12; $i++)
                 <option value="{{$i}}" @if (request('bulan')==$i or $bulan==$i)
                     selected
                 @endif>{{$dataBulan[$i]}}</option>
             @endfor
            </select>
        </div>
        <div class="col-md-2">
            <select name="tahun" id="tahun" class="form-control">
             <option value="0">Pilih Tahun</option>
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