<div class="row">
    <div class="col-md-9">
<form class="form-horizontal">
      <div class="form-group row">
        <label for="wilayah" class="col-sm-1 control-label">Filter</label>
        <div class="col-md-5">
            <select name="wilayah" id="wilayah" class="form-control">
            <option value="0">Pilih Wilayah</option>
            @foreach ($dataWilayah as $wil)
            <option value="{{$wil->bps_kode}}" @if (request('wilayah')==$wil->bps_kode or $wilayah==$wil->bps_kode)
                selected
               @endif>[{{$wil->bps_kode}}] {{$wil->bps_nama}}</option>
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
