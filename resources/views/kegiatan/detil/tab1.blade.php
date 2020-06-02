<h3 class="m-t-20"><b class="text-danger">{{$dataKegiatan->keg_nama}}</b></h3>
<hr>
<table class="table table-striped">
    <tr>
        <td>Unitkerja</td>
        <td>{{$dataKegiatan->Unitkerja->unit_nama}}</td>
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
                @if (($dataKegiatan->RealisasiKirim->sum('keg_r_jumlah')/$dataKegiatan->keg_total_target)*100 > 85)
                bg-success 
                @elseif (($dataKegiatan->RealisasiKirim->sum('keg_r_jumlah')/$dataKegiatan->keg_total_target)*100 > 68)
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
                @if (($dataKegiatan->RealisasiTerima->sum('keg_r_jumlah')/$dataKegiatan->keg_total_target)*100 > 85)
                bg-success
                @elseif (($dataKegiatan->RealisasiTerima->sum('keg_r_jumlah')/$dataKegiatan->keg_total_target)*100 > 68)
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
        <td>{{$dataKegiatan->keg_info}}</td>
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
    @if (Auth::user()->level > 4 or Auth::user()->level==3)
        @if (Auth::user()->level > 4 or $dataKegiatan->Unitkerja->unit_parent == Auth::user()->kodeunit)
        <tr>
            <td colspan="2">
                <div class="text-right">
                    <button class="btn btn-warning" data-toggle="modal" data-target="#EditInfoLanjutanModal" data-kegid="{{$dataKegiatan->keg_id}}" data-kegnama="{{$dataKegiatan->keg_nama}}"><span data-toggle="tooltip" title="Edit info lanjutan untuk kegiatan {{$dataKegiatan->keg_nama}}"><i class="fas fa-pencil-alt"></i> Info Lanjutan</span></button>
                    <a href="{{route('kegiatan.edit',$dataKegiatan->keg_id)}}" class="btn btn-success"><i class="fas fa-pencil-alt"></i></a>
                    <button class="btn btn-danger hapuskegiatan" data-kegid="{{$dataKegiatan->keg_id}}" data-kegnama="{{$dataKegiatan->keg_nama}}"><i class="fas fa-trash"></i></button>
                </div>
            </td>
        </tr>    
        @endif
    @endif
    
</table>