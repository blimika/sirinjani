<h3 class="m-t-20"><b class="text-danger">{{$dataKegiatan->keg_nama}}</b></h3>
<hr>
<table class="table table-striped">
    <tr>
        <td>Tim Kerja</td>
        <td>{{$dataKegiatan->TimKerja->unit_nama}}</td>
    </tr>
    <tr>
        <td>Jenis Kegiatan</td>
        <td>{{$dataKegiatan->JenisKeg->jkeg_nama}}</td>
    </tr>
    <tr>
        <td>Total Target</td>
        <td>{{$dataKegiatan->keg_total_target}} {{$dataKegiatan->keg_target_satuan}}</td>
    </tr>
    <tr>
        <td>Progres Kegiatan</td>
        <td>
            <div>Konfirmasi Pengiriman <span class="float-right">{{number_format(($dataKegiatan->RealisasiKirim->sum('keg_r_jumlah')/$dataKegiatan->keg_total_target)*100,2,",",".")}}%</span></div>
            <div class="progress ">
                <div class="progress-bar
                @if (($dataKegiatan->RealisasiKirim->sum('keg_r_jumlah')/$dataKegiatan->keg_total_target)*100 > 99)
                bg-info
                @elseif (($dataKegiatan->RealisasiKirim->sum('keg_r_jumlah')/$dataKegiatan->keg_total_target)*100 > 80)
                bg-success
                @elseif (($dataKegiatan->RealisasiKirim->sum('keg_r_jumlah')/$dataKegiatan->keg_total_target)*100 > 50)
                bg-warning
                @else
                bg-danger
                @endif
                wow animated progress-animated" style="width: {{number_format(($dataKegiatan->RealisasiKirim->sum('keg_r_jumlah')/$dataKegiatan->keg_total_target)*100,2,".",",")}}%; height:20px;" role="progressbar">
                <span class="sr-only">{{number_format(($dataKegiatan->RealisasiKirim->sum('keg_r_jumlah')/$dataKegiatan->keg_total_target)*100,2,",",".")}}% Terkirim</span>
                </div>
            </div>
            <div class="m-t-30">Konfirmasi Penerimaan
                <span class="float-right">{{number_format(($dataKegiatan->RealisasiTerima->sum('keg_r_jumlah')/$dataKegiatan->keg_total_target)*100,2,",",".")}}%</span>
            </div>
            <div class="progress">
                <div class="progress-bar
                @if (($dataKegiatan->RealisasiTerima->sum('keg_r_jumlah')/$dataKegiatan->keg_total_target)*100 > 99)
                bg-info
                @elseif (($dataKegiatan->RealisasiTerima->sum('keg_r_jumlah')/$dataKegiatan->keg_total_target)*100 > 80)
                bg-success
                @elseif (($dataKegiatan->RealisasiTerima->sum('keg_r_jumlah')/$dataKegiatan->keg_total_target)*100 > 50)
                bg-warning
                @else
                bg-danger
                @endif
                wow animated progress-animated" style="width: {{number_format(($dataKegiatan->RealisasiTerima->sum('keg_r_jumlah')/$dataKegiatan->keg_total_target)*100,2,".",",")}}%; height:20px;" role="progressbar"> <span class="sr-only">{{number_format(($dataKegiatan->RealisasiTerima->sum('keg_r_jumlah')/$dataKegiatan->keg_total_target)*100,2,",",".")}}% Complete</span> </div>
            </div>
        </td>
    </tr>
    @if ($dataKegiatan->keg_spj==1)
    <tr>
        <td>Progres SPJ</td>
        <td>
            <div>Konfirmasi Pengiriman SPJ
                <span class="float-right">{{number_format(($dataKegiatan->RealisasiKirimSpj->sum('spj_r_jumlah')/$dataKegiatan->TargetSpj->sum('spj_t_target'))*100,2,",",".")}}%</span></div>
            <div class="progress ">
                <div class="progress-bar
                @if (($dataKegiatan->RealisasiKirimSpj->sum('spj_r_jumlah')/$dataKegiatan->TargetSpj->sum('spj_t_target'))*100 > 85)
                bg-success
                @elseif (($dataKegiatan->RealisasiKirimSpj->sum('spj_r_jumlah')/$dataKegiatan->TargetSpj->sum('spj_t_target'))*100 > 68)
                bg-warning
                @else
                bg-danger
                @endif
                wow animated progress-animated" style="width: {{number_format(($dataKegiatan->RealisasiKirimSpj->sum('spj_r_jumlah')/$dataKegiatan->TargetSpj->sum('spj_t_target'))*100,2,".",",")}}%; height:20px;" role="progressbar">
                <span class="sr-only">{{number_format(($dataKegiatan->RealisasiKirimSpj->sum('spj_r_jumlah')/$dataKegiatan->TargetSpj->sum('spj_t_target'))*100,2,",",".")}}% Terkirim</span>
                </div>
            </div>
            <div class="m-t-30">Konfirmasi Penerimaan SPJ
                <span class="float-right">{{number_format(($dataKegiatan->RealisasiTerimaSpj->sum('spj_r_jumlah')/$dataKegiatan->TargetSpj->sum('spj_t_target'))*100,2,",",".")}}%</span>
            </div>
            <div class="progress">
                <div class="progress-bar
                @if (($dataKegiatan->RealisasiTerimaSpj->sum('spj_r_jumlah')/$dataKegiatan->TargetSpj->sum('spj_t_target'))*100 > 85)
                bg-success
                @elseif (($dataKegiatan->RealisasiTerimaSpj->sum('spj_r_jumlah')/$dataKegiatan->TargetSpj->sum('spj_t_target'))*100 > 68)
                bg-warning
                @else
                bg-danger
                @endif
                wow animated progress-animated" style="width: {{number_format(($dataKegiatan->RealisasiTerimaSpj->sum('spj_r_jumlah')/$dataKegiatan->TargetSpj->sum('spj_t_target'))*100,2,".",",")}}%; height:20px;" role="progressbar"> <span class="sr-only">{{number_format(($dataKegiatan->RealisasiTerimaSpj->sum('spj_r_jumlah')/$dataKegiatan->TargetSpj->sum('spj_t_target'))*100,2,",",".")}}% Complete</span> </div>
            </div>
        </td>
    </tr>
    @endif
    <tr>
        <td>Tanggal mulai</td>
        <td>{{Tanggal::HariPanjang($dataKegiatan->keg_start)}}</td>
    </tr>
    <tr>
        <td>Tanggal berakhir</td>
        <td>{{Tanggal::HariPanjang($dataKegiatan->keg_end)}}</td>
    </tr>
    <tr>
        <td>Laporan SPJ</td>
        <td>
            @if ($dataKegiatan->keg_spj==1)
                <span class="label label-success label-rounded">Ada</span>
            @else
                <span class="label label-danger label-rounded">Tidak</span>
            @endif
        </td>
    </tr>
    <tr>
        <td>Info Lanjutan</td>
        <td>{!! $dataKegiatan->keg_info !!}</td>
    </tr>
    <tr>
        <td>Flag</td>
        <td>
            @if($dataKegiatan->keg_flag == 1)
                <span class="label label-success label-rounded">{{$dataKegiatan->FlagKegiatan->nama}}</span>
            @else
                <span class="label label-danger label-rounded">{{$dataKegiatan->FlagKegiatan->nama}}</span>
            @endif
        </td>
    </tr>
    <tr>
        <td>Dibuat oleh</td>
        <td>{{$dataKegiatan->keg_dibuat_oleh}}</td>
    </tr>
    <tr>
        <td>Diupdate oleh</td>
        <td>{{$dataKegiatan->keg_diupdate_oleh}}</td>
    </tr>
    <tr>
        <td>Dibuat tanggal</td>
        <td>{{Tanggal::LengkapPanjang($dataKegiatan->created_at)}}</td>
    </tr>
    <tr>
        <td>Diupdate tanggal</td>
        <td>{{Tanggal::LengkapPanjang($dataKegiatan->updated_at)}}</td>
    </tr>
    @if (Auth::user()->role > 3)
        @if (Auth::user()->role > 4 or $dataKegiatan->keg_timkerja == Auth::user()->kodeunit or Auth::User()->HakAkses->where('hak_kodeunit',$dataKegiatan->keg_timkerja)->count() > 0)
        <tr>
            <td colspan="2">
                <div class="text-right">
                    <button class="btn btn-warning" data-toggle="modal" data-target="#EditInfoLanjutanModal" data-kegid="{{$dataKegiatan->keg_id}}" data-kegnama="{{$dataKegiatan->keg_nama}}"><span data-toggle="tooltip" title="Edit info lanjutan"><i class="fas fa-pencil-alt"></i> Info Lanjutan</span></button>
                    <a href="{{route('kegiatan.copy',$dataKegiatan->keg_id)}}" class="btn btn-info" data-toggle="tooltip" title="Copy kegiatan ini"><i class="fas fa-copy"></i></a>
                    <a href="{{route('kegiatan.edit',$dataKegiatan->keg_id)}}" class="btn btn-success" data-toggle="tooltip" title="Edit kegiatan ini"><i class="fas fa-pencil-alt"></i></a>
                    <button class="btn btn-danger hapuskegiatan" data-kegid="{{$dataKegiatan->keg_id}}" data-kegnama="{{$dataKegiatan->keg_nama}}" data-toggle="tooltip" title="Hapus kegiatan ini"><i class="fas fa-trash"></i></button>
                </div>
            </td>
        </tr>
        @endif
    @endif

</table>
