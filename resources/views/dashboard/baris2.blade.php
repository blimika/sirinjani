<!-- Row -->
<div class="row">
    <!-- Column -->
    <div class="col-lg-6 col-md-6">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Kegiatan mendekati batas waktu</h4>
                @if (Generate::KegiatanDeadline())
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kegiatan</th>
                                <th width="19%">Batas Waktu</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach (Generate::KegiatanDeadline() as $item)
                                <tr>
                                    <td rowspan="2">{{$loop->iteration}}</td>
                                    <td><a href="{{route('kegiatan.detil',$item->keg_id)}}" class="text-info">{{$item->keg_nama}}</a></td>
                                    <td align="right" class="text-cyan">{{Tanggal::Pendek($item->keg_end)}}</td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <h6>Penerimaan<span class="float-right">{{number_format(($item->RealisasiKirim->sum('keg_r_jumlah')/$item->keg_total_target)*100,2,",",".")}}%</span></h6>
                                                <div class="progress">
                                                    <div class="progress-bar 
                                                        @if (($item->RealisasiKirim->sum('keg_r_jumlah')/$item->keg_total_target)*100 > 85)
                                                        bg-success 
                                                        @elseif (($item->RealisasiKirim->sum('keg_r_jumlah')/$item->keg_total_target)*100 > 68)
                                                        bg-warning
                                                        @else 
                                                        bg-danger
                                                        @endif
                                                        wow animated progress-animated" role="progressbar" style="width: {{number_format(($item->RealisasiKirim->sum('keg_r_jumlah')/$item->keg_total_target)*100,2,".",",")}}%; height: 15px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <h6>Pengiriman<span class="float-right">{{number_format(($item->RealisasiTerima->sum('keg_r_jumlah')/$item->keg_total_target)*100,2,",",".")}}%</span></h6>
                                                <div class="progress">
                                                    
                                                    <div class="progress-bar 
                                                        @if (($item->RealisasiTerima->sum('keg_r_jumlah')/$item->keg_total_target)*100 > 85)
                                                        bg-success
                                                        @elseif (($item->RealisasiTerima->sum('keg_r_jumlah')/$item->keg_total_target)*100 > 68)
                                                        bg-warning
                                                        @else 
                                                        bg-danger
                                                        @endif
                                                        wow animated progress-animated" role="progressbar" style="width: {{number_format(($item->RealisasiTerima->sum('keg_r_jumlah')/$item->keg_total_target)*100,2,".",",")}}%; height: 15px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                        </div>
                                       
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else 
                    Belum ada kegiatan mendekati batas waktu
                @endif
            </div>
        </div>
    </div>
    <!-- Column -->
    <!-- Column -->
    <div class="col-lg-6 col-md-6">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Grafik Nilai</h4>
                 <!-- Nav tabs -->
                 <ul class="nav nav-tabs customtab2" role="tablist">
                    <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#bulanan" role="tab"><span class="hidden-sm-up"><i class="ti-home"></i></span> <span class="hidden-xs-down">BULANAN</span></a> </li>
                    <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#tahunan" role="tab"><span class="hidden-sm-up"><i class="ti-user"></i></span> <span class="hidden-xs-down">TAHUNAN</span></a> </li>
                </ul>
                <!-- Tab panes -->
                <div class="tab-content">
                    <div class="tab-pane active" id="bulanan" role="tabpanel">
                        <div class="p-20">
                            <div id="nilai_bulanan"></div>
                        </div>
                    </div>
                    <div class="tab-pane p-20" id="tahunan" role="tabpanel">
                        <div id="nilai_tahunan"></div>
                    </div>
                    
                </div>
                
                
            </div>
        </div>
    </div>
</div>
<!-- Row -->