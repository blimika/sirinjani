<h3 class="m-t-20"><b>Rekap Target BPS Kabupaten/Kota</b></h3>
<hr>
<div class="table-responsive">
    <table class="table table-striped table-hover" >
        <thead>
        <tr>
            <th class="text-center" rowspan="2" style="background-color:#cecece;vertical-align:middle;">No</th>
            <th class="text-center" rowspan="2" style="background-color:#cecece;vertical-align:middle;">Unit Kerja</th>
            <th class="text-center" rowspan="2" style="background-color:#cecece;vertical-align:middle;">Target</th>
            <th class="text-center" colspan="3" style="background-color:#ccffcc;vertical-align:bottom;border-bottom:1px solid #cecece;">Pengiriman</th>
            <th class="text-center" colspan="3" style="background-color:#99ffcc;vertical-align:bottom;border-bottom:1px solid #cecece;">Penerimaan</th>
            <th class="text-center" rowspan="2" style="background-color:#66ff99;vertical-align:middle;">Nilai</th>
        </tr>
        <tr>
            <th class="text-center" style="background-color:#ccffcc;vertical-align:bottom;">Rincian</th>
            <th class="text-center" style="background-color:#ccffcc;vertical-align:bottom;">RR (%)</th>
            <th class="text-center" style="background-color:#ccffcc;vertical-align:bottom;">&nbsp;</th>
            <th class="text-center" style="background-color:#99ffcc;vertical-align:bottom;">Rincian</th>
            <th class="text-center" style="background-color:#99ffcc;vertical-align:bottom;">RR (%)</th>
            <th class="text-center" style="background-color:#99ffcc;vertical-align:bottom;">&nbsp;</th>
        </tr>
        </thead>
        <tbody>
            @foreach ($dataKegiatan->Target->where('keg_t_target','>','0') as $item)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$item->Unitkerja->unit_nama}}</td>
                    <td class="text-center">{{$item->keg_t_target}}</td>
                    <td>
                        <!--Rincian Pengiriman-->
                        @if (count($dataKegiatan->RealisasiKirim->where('keg_r_unitkerja','=',$item->keg_t_unitkerja)) > 0)
                            @foreach ($dataKegiatan->RealisasiKirim->where('keg_r_unitkerja','=',$item->keg_t_unitkerja) as $r)
                                <div class="m-b-10">
                                    <!--edit realiasi kirim-->
                                    @if (Auth::user()->level > 4 or (((Auth::user()->level == 2 or Auth::user()->level == 4) and Auth::user()->kodeunit == $item->keg_t_unitkerja)) and Carbon\Carbon::parse($dataKegiatan->keg_start)->format('Y-m-d') <= Carbon\Carbon::now()->format('Y-m-d'))
                                     <button class="btn btn-success btn-xs" data-toggle="modal" data-target="#EditPengirimanModal" data-kegrid="{{$r->keg_r_id}}" data-targetkabkota="{{$item->keg_t_target}}" data-tglkirim="{{$r->keg_r_tgl}}" data-tglstart="{{$dataKegiatan->keg_start}}">
                                         <i class="fas fa-pencil-alt" data-toggle="tooltip" title="Edit konfirmasi pengiriman tanggal {{Tanggal::Pendek($r->keg_r_tgl)}}"></i>
                                     </button>
                                     <!--batas edit realisasi kirim-->
                                     <!--hapus realiasi-->
                                     <button class="btn btn-danger btn-xs hapuskirim" data-kegrid="{{$r->keg_r_id}}" data-nama="konfirmasi pengiriman oleh {{$item->Unitkerja->unit_nama}} tanggal {{Tanggal::Pendek($r->keg_r_tgl)}}">
                                         <i class="fas fa-trash" data-toggle="tooltip" title="Hapus konfirmasi pengiriman tanggal {{Tanggal::Pendek($r->keg_r_tgl)}}"></i>
                                     </button>
                                     <!--batas hapus realisasi-->
                                     |
                                    @endif
                                     <span class="badge badge-pill badge-primary" data-toggle="tooltip" title="Tanggal konfirmasi pengiriman">{{Tanggal::Pendek($r->keg_r_tgl)}}</span>
                                     | <span class="badge badge-pill badge-success" data-toggle="tooltip" title="Jumlah dikirim">{{$r->keg_r_jumlah}}</span>
                                     | <span class="badge badge-pill badge-warning" data-toggle="tooltip" title="Keterangan konfirmasi pengiriman">{{$r->keg_r_ket}}</span>
                                     @if ($r->keg_r_link != null)
                                        | <a href="{{$r->keg_r_link}}" class="btn btn-xs btn-dark" target="_blank" data-toggle="tooltip" title="Url download pengiriman"><i class="fas fa-link"></i></a>
                                     @endif
                                </div>
                            @endforeach
                        @endif
                        <!--Batas Rincian Pengiriman-->
                    </td>
                    <td>
                        <!---RR pengiriman--->
                        @if (($dataKegiatan->RealisasiKirim->where('keg_r_unitkerja','=',$item->keg_t_unitkerja)->sum('keg_r_jumlah')/$item->keg_t_target)*100 > 85)
                        <div class="badge badge-pill badge-success float-right">
                            {{number_format(($dataKegiatan->RealisasiKirim->where('keg_r_unitkerja','=',$item->keg_t_unitkerja)->sum('keg_r_jumlah')/$item->keg_t_target)*100,2,",",".")}}%
                        </div>
                        @elseif(($dataKegiatan->RealisasiKirim->where('keg_r_unitkerja','=',$item->keg_t_unitkerja)->sum('keg_r_jumlah')/$item->keg_t_target)*100 > 70)
                        <div class="badge badge-pill badge-warning float-right">
                            {{number_format(($dataKegiatan->RealisasiKirim->where('keg_r_unitkerja','=',$item->keg_t_unitkerja)->sum('keg_r_jumlah')/$item->keg_t_target)*100,2,",",".")}}%
                        </div>
                        @else
                        <div class="badge badge-pill badge-danger float-right">
                            {{number_format(($dataKegiatan->RealisasiKirim->where('keg_r_unitkerja','=',$item->keg_t_unitkerja)->sum('keg_r_jumlah')/$item->keg_t_target)*100,2,",",".")}}%
                        </div>
                        @endif
                    </td>
                    <td>
                        @if (Auth::user()->level > 4 or (((Auth::user()->level == 2 or Auth::user()->level == 4) and Auth::user()->kodeunit == $item->keg_t_unitkerja)) and Carbon\Carbon::parse($dataKegiatan->keg_start)->format('Y-m-d') <= Carbon\Carbon::now()->format('Y-m-d'))
                        <button class="btn btn-primary btn-xs" data-toggle="modal" data-target="#PengirimanModal" data-kegid="{{$item->keg_id}}" data-kabkota="{{$item->keg_t_unitkerja}}" data-kabkotanama="{{$item->Unitkerja->unit_nama}}" data-targetkabkota="{{$item->keg_t_target}}" data-tglstart="{{$dataKegiatan->keg_start}}">
                            <i class="fas fa-plus"data-toggle="tooltip" data-placement="top" title="Tambah Pengiriman {{$item->Unitkerja->unit_nama}}"></i>
                        </button>
                        @endif
                    </td>
                    <td>
                        <!--Rincian Penerimaan-->
                        @if (count($dataKegiatan->RealisasiTerima->where('keg_r_unitkerja','=',$item->keg_t_unitkerja)) > 0)
                            @foreach ($dataKegiatan->RealisasiTerima->where('keg_r_unitkerja','=',$item->keg_t_unitkerja) as $r)
                                <div class="m-b-10">
                                    <!--edit realiasi-->
                                    @if (Auth::user()->level > 4 or (Auth::user()->level == 3 and Auth::user()->kodeunit == $dataKegiatan->Unitkerja->unit_parent))
                                     <button class="btn btn-success btn-xs" data-toggle="modal" data-target="#EditPenerimaanModal" data-kegrid="{{$r->keg_r_id}}" data-targetkabkota="{{$item->keg_t_target}}" data-tglkirim="{{$r->keg_r_tgl}}" data-tglstart="{{$dataKegiatan->keg_start}}">
                                         <i class="fas fa-pencil-alt" data-toggle="tooltip" title="Edit penerimaan tanggal {{Tanggal::Pendek($r->keg_r_tgl)}}"></i>
                                     </button>
                                     <!--batas edit realisasi-->
                                     <!--hapus realiasi-->
                                     <button class="btn btn-danger btn-xs hapusterima" data-kegrid="{{$r->keg_r_id}}" data-nama="konfirmasi penerimaan oleh {{$item->Unitkerja->unit_nama}} tanggal {{Tanggal::Pendek($r->keg_r_tgl)}}">
                                         <i class="fas fa-trash" data-toggle="tooltip" title="Hapus konfirmasi penerimaan tanggal {{Tanggal::Pendek($r->keg_r_tgl)}}"></i>
                                     </button>
                                     <!--batas hapus realisasi-->
                                     |
                                     @endif
                                     <span class="badge badge-pill badge-info" data-toggle="tooltip" title="Tanggal konfirmasi penerimaan">{{Tanggal::Pendek($r->keg_r_tgl)}}</span>
                                     | <span class="badge badge-pill badge-success" data-toggle="tooltip" title="Jumlah diterima">{{$r->keg_r_jumlah}}</span>
                                     | <span class="badge badge-pill badge-warning" data-toggle="tooltip" title="Keterangan konfirmasi penerimaan">{{$r->keg_r_ket}}</span>
                                </div>
                            @endforeach
                        @endif
                        <!--Batas Rincian Penerimaan-->
                    </td>
                    <td>
                        <!--RR Penerimaan-->
                        @if (($dataKegiatan->RealisasiTerima->where('keg_r_unitkerja','=',$item->keg_t_unitkerja)->sum('keg_r_jumlah')/$item->keg_t_target)*100 > 85)
                        <div class="badge badge-pill badge-success float-right">
                            {{number_format(($dataKegiatan->RealisasiTerima->where('keg_r_unitkerja','=',$item->keg_t_unitkerja)->sum('keg_r_jumlah')/$item->keg_t_target)*100,2,",",".")}}%
                        </div>
                        @elseif(($dataKegiatan->RealisasiTerima->where('keg_r_unitkerja','=',$item->keg_t_unitkerja)->sum('keg_r_jumlah')/$item->keg_t_target)*100 > 70)
                        <div class="badge badge-pill badge-warning float-right">
                            {{number_format(($dataKegiatan->RealisasiTerima->where('keg_r_unitkerja','=',$item->keg_t_unitkerja)->sum('keg_r_jumlah')/$item->keg_t_target)*100,2,",",".")}}%
                        </div>
                        @else
                        <div class="badge badge-pill badge-danger float-right">
                            {{number_format(($dataKegiatan->RealisasiTerima->where('keg_r_unitkerja','=',$item->keg_t_unitkerja)->sum('keg_r_jumlah')/$item->keg_t_target)*100,2,",",".")}}%
                        </div>
                        @endif
                        <!--Batas RR Penerimaan-->
                    </td>
                    <td>
                        @if (Auth::user()->level > 4 or (Auth::user()->level == 3 and $dataKegiatan->RealisasiKirim->where('keg_r_unitkerja','=',$item->keg_t_unitkerja)->sum('keg_r_jumlah') > 0 and Auth::user()->kodeunit == $dataKegiatan->Unitkerja->unit_parent))
                        <button class="btn btn-info btn-xs" data-toggle="modal" data-target="#PenerimaanModal" data-kegid="{{$item->keg_id}}" data-kabkota="{{$item->keg_t_unitkerja}}" data-kabkotanama="{{$item->Unitkerja->unit_nama}}" data-targetkabkota="{{$item->keg_t_target}}" data-totalkirim="{{$dataKegiatan->RealisasiKirim->where('keg_r_unitkerja','=',$item->keg_t_unitkerja)->sum('keg_r_jumlah')}}" data-tglstart="{{$dataKegiatan->keg_start}}">
                            <i class="fa fa-plus" data-toggle="tooltip" data-placement="top" title="Tambah Penerimaan {{$item->Unitkerja->unit_nama}}"></i>
                        </button>
                        @endif
                    </td>
                    <td>{{$item->keg_t_point}}</td>
                </tr>
            @endforeach
            <tr>
                <td style="background-color:#cecece;vertical-align:middle;">&nbsp;</td>
                <td style="background-color:#cecece;vertical-align:middle;"><strong>Total</strong></td>
                <td class="text-center" style="background-color:#cecece;vertical-align:middle;"><strong>{{$dataKegiatan->Target->sum('keg_t_target')}}</strong></td>
                <td class="text-center" style="background-color:#ccffcc;vertical-align:bottom;"><strong>{{$dataKegiatan->RealisasiKirim->sum('keg_r_jumlah')}}</strong></td>
                <td style="background-color:#ccffcc;vertical-align:bottom;"><strong>
                    <!--RR Total pengiriman-->
                    @if (($dataKegiatan->RealisasiKirim->sum('keg_r_jumlah')/$dataKegiatan->Target->sum('keg_t_target'))*100 > 85)
                        <div class="badge badge-pill badge-success float-right">
                            {{number_format(($dataKegiatan->RealisasiKirim->sum('keg_r_jumlah')/$dataKegiatan->Target->sum('keg_t_target'))*100,2,",",".")}}%
                        </div>
                    @elseif (($dataKegiatan->RealisasiKirim->sum('keg_r_jumlah')/$dataKegiatan->Target->sum('keg_t_target'))*100 > 70)
                        <div class="badge badge-pill badge-warning float-right">
                            {{number_format(($dataKegiatan->RealisasiKirim->sum('keg_r_jumlah')/$dataKegiatan->Target->sum('keg_t_target'))*100,2,",",".")}}%
                        </div>
                    @else
                        <div class="badge badge-pill badge-danger float-right">
                            {{number_format(($dataKegiatan->RealisasiKirim->sum('keg_r_jumlah')/$dataKegiatan->Target->sum('keg_t_target'))*100,2,",",".")}}%
                        </div>
                    @endif
                    <!--batas RR Total pengiriman-->
                    </strong>
                </td>
                <td style="background-color:#ccffcc;vertical-align:bottom;">&nbsp;</td>
                <td class="text-center" style="background-color:#99ffcc;vertical-align:bottom;">
                    <b>{{$dataKegiatan->RealisasiTerima->sum('keg_r_jumlah')}}</b>
                </td>
                <td style="background-color:#99ffcc;vertical-align:bottom;">
                    <b>
                    <!--RR Total penerimaan-->
                    @if (($dataKegiatan->RealisasiTerima->sum('keg_r_jumlah')/$dataKegiatan->Target->sum('keg_t_target'))*100 > 85)
                    <div class="badge badge-pill badge-success float-right">
                        {{number_format(($dataKegiatan->RealisasiTerima->sum('keg_r_jumlah')/$dataKegiatan->Target->sum('keg_t_target'))*100,2,",",".")}}%
                    </div>
                @elseif (($dataKegiatan->RealisasiTerima->sum('keg_r_jumlah')/$dataKegiatan->Target->sum('keg_t_target'))*100 > 70)
                    <div class="badge badge-pill badge-warning float-right">
                        {{number_format(($dataKegiatan->RealisasiTerima->sum('keg_r_jumlah')/$dataKegiatan->Target->sum('keg_t_target'))*100,2,",",".")}}%
                    </div>
                @else
                    <div class="badge badge-pill badge-danger float-right">
                        {{number_format(($dataKegiatan->RealisasiTerima->sum('keg_r_jumlah')/$dataKegiatan->Target->sum('keg_t_target'))*100,2,",",".")}}%
                    </div>
                @endif
                    <!--batas RR Total penerimaan-->
                    </b>
                </td>
                <td style="background-color:#99ffcc;vertical-align:bottom;">&nbsp;</td>
                <td style="background-color:#66ff99;vertical-align:middle;">&nbsp;</td>
            </tr>
        </tbody>
    </table>
</div>
