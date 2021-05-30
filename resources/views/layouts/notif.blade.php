<ul>
    <li>
        <div class="drop-title">Notifikasi</div>
    </li>
    <li>
        <div class="message-center">
           @foreach (Generate::Notifikasi5Terakhir(Auth::user()->username) as $item)
                <!-- Message -->
                <a href="javascript:void(0)" data-toggle="modal" data-target="#ViewNotifikasi" data-idnotif="{{$item->id}}">
                    @if ($item->notif_jenis == 1)
                        <div class="btn btn-info btn-circle"><i class="fa fa-link"></i></div>
                    @elseif ($item->notif_jenis == 2)
                        <div class="btn btn-success btn-circle"><i class="fa fa-link"></i></div>
                    @elseif ($item->notif_jenis == 3)
                        <div class="btn btn-danger btn-circle"><i class="fa fa-link"></i></div>
                    @else
                    <div class="btn btn-primary btn-circle"><i class="fa fa-link"></i></div>
                    @endif

                    <div class="mail-contnet">
                        <h5>{{$item->notif_dari}} <br />{{$item->JenisNotif->jnotif_nama}}</h5>
                        <span class="mail-desc">{!! $item->notif_isi !!}</span>
                        <span class="time">{{Tanggal::LengkapHariPanjang($item->created_at)}}</span>
                    </div>
                </a>
           @endforeach
        </div>
    </li>
    <li>
        <a class="nav-link text-center link" href="{{route('notif.list')}}"> <strong>Cek Semua Notifikasi</strong> <i class="fa fa-angle-right"></i> </a>
    </li>
</ul>
