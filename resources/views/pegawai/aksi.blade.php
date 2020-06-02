@if (Auth::user()->level > 3)
    <button class="btn btn-circle btn-sm btn-info" data-toggle="modal" @if ($item->isLokal==1)
        data-target="#EditLokalModal" data-unitjenis="{{$item->NamaWilayah->bps_jenis}}"
    @else
        data-target="#EditPegModal"
    @endif data-nipbps="{{$item->nipbps}}"><i class="fas fa-pencil-alt" data-toggle="tooltip" title="Edit Pegawai/Operator"></i></button>
    <button class="btn btn-circle btn-sm btn-warning flagPegawai" data-id="{{$item->id}}" data-flag="{{$item->aktif}}"><i class="fas fa-flag" data-toggle="tooltip" title="Ubah Flag Pegawai/Operator"></i></button>
    @if ($item->isLokal==1)
    <button class="btn btn-circle btn-sm btn-primary" data-toggle="modal" data-target="#GantiPasswordModal" data-nipbps="{{$item->nipbps}}"><i class="fas fa-key" data-toggle="tooltip" title="Ganti Password"></i></button>
    <button class="btn btn-circle btn-sm btn-danger hapuspegawai" data-id="{{$item->id}}" data-nama="{{$item->nama}}"><i class="fas fa-trash" class="fas fa-key" data-toggle="tooltip" title="Hapus pegawai/operator"></i></button>
    @endif
@endif


