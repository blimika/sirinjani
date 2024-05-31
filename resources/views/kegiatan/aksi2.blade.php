<div class="btn-group">
    <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="ti-settings"></i>
    </button>
    <div class="dropdown-menu">
        <a class="dropdown-item" href="{{route('kegiatan.edit',$item->keg_id)}}">Edit</a>
        <div class="dropdown-divider"></div>
        <a class="dropdown-item" href="{{route('kegiatan.edit',$item->keg_id)}}">Copy</a>
        <div class="dropdown-divider"></div>
        <a class="dropdown-item hapuskegiatan" href="#" data-kegid="{{$item->keg_id}}" data-kegnama="{{$item->keg_nama}}">Hapus</a>

    </div>
</div>
