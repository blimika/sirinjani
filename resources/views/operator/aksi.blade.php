@if (Auth::user()->level > 3 )
    <button class="btn btn-circle btn-sm btn-info" data-toggle="modal" data-target="#EditModal" data-opid="{{$item->id}}"><i class="fas fa-pencil-alt" data-toggle="tooltip" title="Edit Operator {{ $item->nama }}"></i></button>
    <button class="btn btn-circle btn-sm btn-warning flagoperator" data-id="{{$item->id}}" data-flag="{{$item->aktif}}"><i class="fas fa-flag" data-toggle="tooltip" title="Ubah Flag Operator {{ $item->nama }}"></i></button>
    <button class="btn btn-circle btn-sm btn-primary" data-toggle="modal" data-target="#GantiPasswordModal" data-id="{{$item->id}}" data-nama="{{$item->nama}}"><i class="fas fa-key" data-toggle="tooltip" title="Ganti Password"></i></button>
    <button class="btn btn-circle btn-sm btn-danger hapusoperator" data-id="{{$item->id}}" data-nama="{{$item->nama}}"><i class="fas fa-trash" class="fas fa-key" data-toggle="tooltip" title="Hapus Operator {{ $item->nama }}"></i></button>
@endif
