@if (Auth::user()->level>4)
<a href="{{route('kegiatan.edit',$item->keg_id)}}" class="btn btn-sm btn-circle btn-success"><i class="fas fa-pencil-alt"></i></a>
<button class="btn btn-danger btn-sm btn-circle hapuskegiatan" data-kegid="{{$item->keg_id}}" data-kegnama="{{$item->keg_nama}}"><i class="fas fa-trash"></i></button>
@elseif (Auth::user()->level == 3)
    @if ($item->Unitkerja->unit_parent == Auth::user()->kodeunit)
        <a href="{{route('kegiatan.edit',$item->keg_id)}}" class="btn btn-sm btn-circle btn-success"><i class="fas fa-pencil-alt"></i></a>
        <button class="btn btn-danger btn-sm btn-circle hapuskegiatan" data-kegid="{{$item->keg_id}}" data-kegnama="{{$item->keg_nama}}"><i class="fas fa-trash"></i></button>
    @endif
@endif
