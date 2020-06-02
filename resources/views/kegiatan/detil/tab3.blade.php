<h3 class="m-t-20"><b>Rekap SPJ BPS Kabupaten/Kota</b></h3>
<hr>
<div class="table-responsive">
    <table class="table table-striped table-hover" >
        <thead>
        <tr>
            <th class="text-center" rowspan="2">No</th>
            <th class="text-center" rowspan="2">Unit Kerja</th>
            <th class="text-center" rowspan="2">Target</th>
            <th class="text-center" colspan="3">Pengiriman</th>
            <th class="text-center" colspan="3">Penerimaan</th>
            <th class="text-center" rowspan="2">Nilai</th>
        </tr>
        <tr>
            <th class="text-center">Rincian</th>
            <th class="text-center">RR (%)</th>
            <th class="text-center">&nbsp;</th>
            <th class="text-center">Rincian</th>
            <th class="text-center">RR (%)</th>
            <th class="text-center">&nbsp;</th>
        </tr>
        </thead>
        <tbody>
            @foreach ($dataKegiatan->TargetSpj->where('spj_t_target','>','0') as $item)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$item->Unitkerja->unit_nama}}</td>
                    <td class="text-center">{{$item->spj_t_target}}</td>
                    <td>
                        <!--Rincian Pengiriman-->
                        @if (count($dataKegiatan->RealisasiKirimSpj->where('spj_r_unitkerja','=',$item->spj_t_unitkerja)) > 0)
                            @foreach ($dataKegiatan->RealisasiKirimSpj->where('spj_r_unitkerja','=',$item->spj_t_unitkerja) as $r)
                                <div class="m-b-10">
                                    <!--edit realisasi-->
                                    @if (Auth::user()->level > 4 or (((Auth::user()->level == 2 or Auth::user()->level == 4) and Auth::user()->kodeunit == $item->spj_t_unitkerja)) and Carbon\Carbon::parse($dataKegiatan->keg_start)->format('Y-m-d') <= Carbon\Carbon::now()->format('Y-m-d'))
                                     <button class="btn btn-success btn-xs" data-toggle="modal" data-target="#EditPengirimanSpjModal" data-spjrid="{{$r->spj_r_id}}" data-targetkabkota="{{$item->spj_t_target}}" data-tglkirim="{{$r->spj_r_tgl}}" data-tglstart="{{$dataKegiatan->keg_start}}">
                                         <i class="fas fa-pencil-alt" data-toggle="tooltip" title="Edit konfirmasi pengiriman tanggal {{Tanggal::Pendek($r->spj_r_tgl)}}"></i>
                                     </button>
                                     <!--batas edit realisasi-->
                                     <!--hapus realiasi-->
                                     <button class="btn btn-danger btn-xs hapuskirimspj" data-kegrid="{{$r->spj_r_id}}" data-nama="konfirmasi pengiriman oleh {{$item->Unitkerja->unit_nama}} tanggal {{Tanggal::Pendek($r->spj_r_tgl)}}">
                                         <i class="fas fa-trash" data-toggle="tooltip" title="Hapus konfirmasi pengiriman tanggal {{Tanggal::Pendek($r->spj_r_tgl)}}"></i>
                                     </button> 
                                     <!--batas hapus realisasi-->
                                     | 
                                     @endif
                                     <span class="badge badge-pill badge-info" data-toggle="tooltip" title="Tanggal konfirmasi pengiriman">{{Tanggal::Pendek($r->spj_r_tgl)}}</span> 
                                     | <span class="badge badge-pill badge-success" data-toggle="tooltip" title="Jumlah dikirim">{{$r->spj_r_jumlah}}</span>
                                     | <span class="badge badge-pill badge-warning" data-toggle="tooltip" title="Keterangan konfirmasi pengiriman">{{$r->spj_r_ket}}</span>
                                     @if ($r->spj_r_link != null)
                                        | <a href="{{$r->spj_r_link}}" class="btn btn-xs btn-dark" target="_blank" data-toggle="tooltip" title="Url download pengiriman"><i class="fas fa-link"></i></a>
                                     @endif
                                </div>
                            @endforeach
                        @endif
                        <!--Batas Rincian Pengiriman-->
                    </td>
                    <td>
                        <!---RR pengiriman--->
                        @if (($dataKegiatan->RealisasiKirimSpj->where('spj_r_unitkerja','=',$item->spj_t_unitkerja)->sum('spj_r_jumlah')/$item->spj_t_target)*100 > 85)
                        <div class="badge badge-pill badge-success float-right">
                            {{number_format(($dataKegiatan->RealisasiKirimSpj->where('spj_r_unitkerja','=',$item->spj_t_unitkerja)->sum('spj_r_jumlah')/$item->spj_t_target)*100,2,",",".")}}%    
                        </div>
                        @elseif(($dataKegiatan->RealisasiKirimSpj->where('keg_r_unitkerja','=',$item->spj_t_unitkerja)->sum('spj_r_jumlah')/$item->spj_t_target)*100 > 70)
                        <div class="badge badge-pill badge-warning float-right">
                            {{number_format(($dataKegiatan->RealisasiKirimSpj->where('spj_r_unitkerja','=',$item->spj_t_unitkerja)->sum('spj_r_jumlah')/$item->spj_t_target)*100,2,",",".")}}%    
                        </div>
                        @else 
                        <div class="badge badge-pill badge-danger float-right">
                            {{number_format(($dataKegiatan->RealisasiKirimSpj->where('spj_r_unitkerja','=',$item->spj_t_unitkerja)->sum('spj_r_jumlah')/$item->spj_t_target)*100,2,",",".")}}%    
                        </div> 
                        @endif
                    </td>
                    <td>
                        @if (Auth::user()->level > 4 or (((Auth::user()->level == 2 or Auth::user()->level == 4) and Auth::user()->kodeunit == $item->spj_t_unitkerja)) and Carbon\Carbon::parse($dataKegiatan->keg_start)->format('Y-m-d') <= Carbon\Carbon::now()->format('Y-m-d'))
                        <button class="btn btn-primary btn-xs" data-toggle="modal" data-target="#PengirimanSpjModal" data-kegid="{{$item->keg_id}}" data-kabkota="{{$item->spj_t_unitkerja}}" data-kabkotanama="{{$item->Unitkerja->unit_nama}}" data-targetkabkota="{{$item->spj_t_target}}" data-tglstart="{{$dataKegiatan->keg_start}}">
                            <i class="fas fa-plus"data-toggle="tooltip" data-placement="top" title="Tambah Pengiriman {{$item->Unitkerja->unit_nama}}"></i>
                        </button> 
                        @endif
                    </td>
                    <td>
                        <!--Rincian Penerimaan-->
                        @if (count($dataKegiatan->RealisasiTerimaSpj->where('spj_r_unitkerja','=',$item->spj_t_unitkerja)) > 0)
                            @foreach ($dataKegiatan->RealisasiTerimaSpj->where('spj_r_unitkerja','=',$item->spj_t_unitkerja) as $r)
                                <div class="m-b-10">
                                    <!--edit realiasi-->
                                    @if (Auth::user()->level > 4 or (Auth::user()->level == 3 and Auth::user()->kodeunit == $dataKegiatan->Unitkerja->unit_parent))
                                     <button class="btn btn-success btn-xs" data-toggle="modal" data-target="#EditPenerimaanSpjModal" data-spjrid="{{$r->spj_r_id}}" data-targetkabkota="{{$item->spj_t_target}}" data-tglkirim="{{$r->spj_r_tgl}}" data-tglstart="{{$dataKegiatan->keg_start}}">
                                         <i class="fas fa-pencil-alt" data-toggle="tooltip" title="Edit penerimaan tanggal {{Tanggal::Pendek($r->spj_r_tgl)}}"></i>
                                     </button>
                                     <!--batas edit realisasi-->
                                     <!--hapus realiasi-->
                                     <button class="btn btn-danger btn-xs hapusterimaspj" data-kegrid="{{$r->spj_r_id}}" data-nama="konfirmasi penerimaan oleh {{$item->Unitkerja->unit_nama}} tanggal {{Tanggal::Pendek($r->spj_r_tgl)}}">
                                         <i class="fas fa-trash" data-toggle="tooltip" title="Hapus konfirmasi penerimaan tanggal {{Tanggal::Pendek($r->spj_r_tgl)}}"></i>
                                     </button> 
                                     <!--batas hapus realisasi-->
                                     |
                                     @endif
                                     <span class="badge badge-pill badge-info" data-toggle="tooltip" title="Tanggal konfirmasi penerimaan">{{Tanggal::Pendek($r->spj_r_tgl)}}</span> 
                                     | <span class="badge badge-pill badge-success" data-toggle="tooltip" title="Jumlah diterima">{{$r->spj_r_jumlah}}</span>
                                     | <span class="badge badge-pill badge-warning" data-toggle="tooltip" title="Keterangan konfirmasi penerimaan">{{$r->spj_r_ket}}</span>
                                </div>
                            @endforeach
                        @endif
                        <!--Batas Rincian Penerimaan-->
                    </td>
                    <td>
                        <!--RR Penerimaan-->
                        @if (($dataKegiatan->RealisasiTerimaSpj->where('spj_r_unitkerja','=',$item->spj_t_unitkerja)->sum('spj_r_jumlah')/$item->spj_t_target)*100 > 85)
                        <div class="badge badge-pill badge-success float-right">
                            {{number_format(($dataKegiatan->RealisasiTerimaSpj->where('spj_r_unitkerja','=',$item->spj_t_unitkerja)->sum('spj_r_jumlah')/$item->spj_t_target)*100,2,",",".")}}%    
                        </div>
                        @elseif(($dataKegiatan->RealisasiTerimaSpj->where('spj_r_unitkerja','=',$item->spj_t_unitkerja)->sum('spj_r_jumlah')/$item->spj_t_target)*100 > 70)
                        <div class="badge badge-pill badge-warning float-right">
                            {{number_format(($dataKegiatan->RealisasiTerimaSpj->where('spj_r_unitkerja','=',$item->spj_t_unitkerja)->sum('spj_r_jumlah')/$item->spj_t_target)*100,2,",",".")}}%    
                        </div>
                        @else 
                        <div class="badge badge-pill badge-danger float-right">
                            {{number_format(($dataKegiatan->RealisasiTerimaSpj->where('spj_r_unitkerja','=',$item->spj_t_unitkerja)->sum('spj_r_jumlah')/$item->spj_t_target)*100,2,",",".")}}%    
                        </div> 
                        @endif
                        <!--Batas RR Penerimaan-->
                    </td>
                    <td>
                        @if (Auth::user()->level > 4 or (Auth::user()->level == 3 and $dataKegiatan->RealisasiKirimSpj->where('spj_r_unitkerja','=',$item->spj_t_unitkerja)->sum('spj_r_jumlah') > 0 and Auth::user()->kodeunit == $dataKegiatan->Unitkerja->unit_parent))
                        <button class="btn btn-info btn-xs" data-toggle="modal" data-target="#PenerimaanSpjModal" data-kegid="{{$item->keg_id}}" data-kabkota="{{$item->spj_t_unitkerja}}" data-kabkotanama="{{$item->Unitkerja->unit_nama}}" data-targetkabkota="{{$item->spj_t_target}}" data-totalkirim="{{$dataKegiatan->RealisasiKirimSpj->where('spj_r_unitkerja','=',$item->spj_t_unitkerja)->sum('spj_r_jumlah')}}" data-tglstart="{{$dataKegiatan->keg_start}}">
                            <i class="fa fa-plus" data-toggle="tooltip" data-placement="top" title="Tambah Penerimaan SPJ dari {{$item->Unitkerja->unit_nama}}"></i>
                        </button>
                        @endif
                    </td>
                    <td>{{$item->spj_t_point}}</td>
                </tr>
            @endforeach
            <tr>
                <td></td>
                <td>Total</td>
                <td class="text-center">{{$dataKegiatan->TargetSpj->sum('spj_t_target')}}</td>
                <td class="text-center">{{$dataKegiatan->RealisasiKirimSpj->sum('spj_r_jumlah')}}</td>
                <td>
                    <!--RR Total pengiriman-->
                    @if (($dataKegiatan->RealisasiKirimSpj->sum('spj_r_jumlah')/$dataKegiatan->TargetSpj->sum('spj_t_target'))*100 > 85)
                        <div class="badge badge-pill badge-success float-right">
                            {{number_format(($dataKegiatan->RealisasiKirimSpj->sum('spj_r_jumlah')/$dataKegiatan->TargetSpj->sum('spj_t_target'))*100,2,",",".")}}%
                        </div>
                    @elseif (($dataKegiatan->RealisasiKirimSpj->sum('spj_r_jumlah')/$dataKegiatan->TargetSpj->sum('spj_t_target'))*100 > 70)
                        <div class="badge badge-pill badge-warning float-right">
                            {{number_format(($dataKegiatan->RealisasiKirimSpj->sum('spj_r_jumlah')/$dataKegiatan->TargetSpj->sum('spj_t_target'))*100,2,",",".")}}%
                        </div>
                    @else 
                        <div class="badge badge-pill badge-danger float-right">
                            {{number_format(($dataKegiatan->RealisasiKirimSpj->sum('spj_r_jumlah')/$dataKegiatan->TargetSpj->sum('spj_t_target'))*100,2,",",".")}}%
                        </div>
                    @endif
                    <!--batas RR Total pengiriman-->
                </td>
                <td></td>
                <td class="text-center">{{$dataKegiatan->RealisasiTerimaSpj->sum('spj_r_jumlah')}}</td>
                <td>
                    <!--RR Total penerimaan-->
                    @if (($dataKegiatan->RealisasiTerimaSpj->sum('spj_r_jumlah')/$dataKegiatan->TargetSpj->sum('spj_t_target'))*100 > 85)
                    <div class="badge badge-pill badge-success float-right">
                        {{number_format(($dataKegiatan->RealisasiTerimaSpj->sum('spj_r_jumlah')/$dataKegiatan->TargetSpj->sum('spj_t_target'))*100,2,",",".")}}%
                    </div>
                @elseif (($dataKegiatan->RealisasiTerimaSpj->sum('spj_r_jumlah')/$dataKegiatan->TargetSpj->sum('spj_t_target'))*100 > 70)
                    <div class="badge badge-pill badge-warning float-right">
                        {{number_format(($dataKegiatan->RealisasiTerimaSpj->sum('spj_r_jumlah')/$dataKegiatan->TargetSpj->sum('spj_t_target'))*100,2,",",".")}}%
                    </div>
                @else 
                    <div class="badge badge-pill badge-danger float-right">
                        {{number_format(($dataKegiatan->RealisasiTerimaSpj->sum('spj_r_jumlah')/$dataKegiatan->TargetSpj->sum('spj_t_target'))*100,2,",",".")}}%
                    </div>
                @endif
                    <!--batas RR Total penerimaan-->
                </td>
                <td></td>
                <td></td>
            </tr>
        </tbody>
    </table>
</div>