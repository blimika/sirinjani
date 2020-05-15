<button class="btn btn-circle btn-sm btn-success" data-toggle="modal" data-target="#DetilModal" data-nipbps="{{$item->nipbps}}"><i class="fas fa-search"></i></button>
@if (Auth::user())
    @if (Auth::user()->level > 3)
        <button class="btn btn-circle btn-sm btn-info" data-toggle="modal" @if ($item->isLokal==1)
            data-target="#EditLokalModal"
        @else
            data-target="#EditPegModal"
        @endif data-nipbps="{{$item->nipbps}}"><i class="fas fa-pencil-alt"></i></button>
        <button class="btn btn-circle btn-sm btn-warning flagPegawai" data-id="{{$item->id}}" data-flag="{{$item->aktif}}"><i class="fas fa-flag"></i></button>
        @if ($item->isLokal==1)
        <button class="btn btn-circle btn-sm btn-danger hapuspegawai" data-id="{{$item->id}}" data-nama="{{$item->nama}}"><i class="fas fa-trash"></i></button>
        @endif
    @endif
@endif

