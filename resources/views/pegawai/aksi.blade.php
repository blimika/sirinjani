<button class="btn btn-circle btn-sm btn-success" data-toggle="modal" data-target="#DetilModal" data-nipbps="{{$item->nipbps}}"><i class="fas fa-search"></i></button>
<button class="btn btn-circle btn-sm btn-info" data-toggle="modal" data-nipbps="{{$item->nipbps}}" @if ($item->isLokal==1)
    data-target="#EditLokalModal"
@else
    data-target="#EditPegModal"
@endif  ><i class="fas fa-pencil-alt"></i></button>
<button class="btn btn-circle btn-sm btn-warning flagPegawai" data-id="{{$item->id}}" data-flag="{{$item->aktif}}"><i class="fas fa-flag"></i></button>
@if ($item->isLokal==1)
<button class="btn btn-circle btn-sm btn-danger" data-toggle="modal" data-target="#HapusModal"><i class="fas fa-trash"></i></button>
@endif
