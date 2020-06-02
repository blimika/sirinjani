<!-- Row -->
<div class="row">
    <!-- Column -->
    <div class="col-lg-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Jumlah Kegiatan</h4>
                <div class="text-right"> <span class="text-muted">Tahun {{Carbon\Carbon::now()->format('Y')}}</span>
                    <h1 class="font-light">
                        @if (Generate::TotalKegiatan(Carbon\Carbon::now()->format('Y')) < Generate::TotalKegiatan(Carbon\Carbon::now()->format('Y') - 1))
                            <sup><i class="ti-arrow-down text-danger"></i></sup>
                        @else
                            <sup><i class="ti-arrow-up text-success"></i></sup> 
                        @endif
                        {{Generate::TotalKegiatan(Carbon\Carbon::now()->format('Y'))}} <sup><span class="h4">({{Generate::TotalTargetKegiatan(Carbon\Carbon::now()->format('Y'))}})</span></sup></h1>
                </div>
                @if (Generate::TotalKegiatan(Carbon\Carbon::now()->format('Y')) < Generate::TotalKegiatan(Carbon\Carbon::now()->format('Y') - 1))
                <span class="text-danger">
                @else
                <span class="text-success">
                @endif
                    {{number_format(((abs(Generate::TotalKegiatan(Carbon\Carbon::now()->format('Y'))- Generate::TotalKegiatan(Carbon\Carbon::now()->format('Y') - 1))/Generate::TotalKegiatan(Carbon\Carbon::now()->format('Y')))*100),2,",",".")}}
                %
                </span>
                <div class="progress">
                    <div 
                    @if (Generate::TotalKegiatan(Carbon\Carbon::now()->format('Y')) < Generate::TotalKegiatan(Carbon\Carbon::now()->format('Y') - 1))
                        class="progress-bar bg-danger" 
                    @else
                        class="progress-bar bg-success"  
                    @endif role="progressbar" style="width: {{number_format(((abs(Generate::TotalKegiatan(Carbon\Carbon::now()->format('Y'))- Generate::TotalKegiatan(Carbon\Carbon::now()->format('Y') - 1))/Generate::TotalKegiatan(Carbon\Carbon::now()->format('Y')))*100),2,".",",")}}%; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </div>
    </div>
    <!-- Column -->
    <!-- Column -->
    <div class="col-lg-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Jumlah Pengiriman</h4>
                <div class="text-right"> <span class="text-muted">Tahun {{Carbon\Carbon::now()->format('Y')}}</span>
                    <h1 class="font-light">{{Generate::TotalPengiriman(Carbon\Carbon::now()->format('Y'))}}</h1>
                </div>
                <span class="text-info">
                    {{number_format((Generate::TotalPengiriman(Carbon\Carbon::now()->format('Y'))/Generate::TotalTargetKegiatan(Carbon\Carbon::now()->format('Y')))*100,2,",",".")}}
                    %</span>
                <div class="progress">
                    <div class="progress-bar bg-info" role="progressbar" style="width: {{number_format((Generate::TotalPengiriman(Carbon\Carbon::now()->format('Y'))/Generate::TotalTargetKegiatan(Carbon\Carbon::now()->format('Y')))*100,2,".",",")}}%; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </div>
    </div>
    <!-- Column -->
    <!-- Column -->
    <div class="col-lg-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Jumlah Penerimaan</h4>
                <div class="text-right"> <span class="text-muted">Tahun {{Carbon\Carbon::now()->format('Y')}}</span>
                    <h1 class="font-light">{{Generate::TotalPenerimaan(Carbon\Carbon::now()->format('Y'))}}</h1>
                </div>
                <span class="text-danger"> {{number_format((Generate::TotalPenerimaan(Carbon\Carbon::now()->format('Y'))/Generate::TotalTargetKegiatan(Carbon\Carbon::now()->format('Y')))*100,2,",",".")}}
                    %</span>
                <div class="progress">
                    <div class="progress-bar bg-danger" role="progressbar" style="width: {{number_format((Generate::TotalPenerimaan(Carbon\Carbon::now()->format('Y'))/Generate::TotalTargetKegiatan(Carbon\Carbon::now()->format('Y')))*100,2,".",",")}}%; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </div>
    </div>
    <!-- Column -->
    <!-- Column -->
    <div class="col-lg-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Unit Kerja terbanyak kegiatan</h4>
                <div class="text-right"> 
                    <div class="text-muted">Tahun {{Carbon\Carbon::now()->format('Y')}}</div>
                    
                    <h4 class="font-light"><span class="pull-left"><i class="fa fa-trophy"></i></span> 
                        {{Generate::KegiatanTerbanyak(Carbon\Carbon::now()->format('Y'))['nama_unit']}}
                    </h4>
                </div>
                <span class="text-inverse">{{Generate::KegiatanTerbanyak(Carbon\Carbon::now()->format('Y'))['total_keg']}} Kegiatan ({{number_format((Generate::KegiatanTerbanyak(Carbon\Carbon::now()->format('Y'))['total_keg']/Generate::TotalKegiatan(Carbon\Carbon::now()->format('Y')))*100,2,",",".")}}%)</span>
                <div class="progress">
                    <div class="progress-bar bg-inverse" role="progressbar" style="width: {{number_format((Generate::KegiatanTerbanyak(Carbon\Carbon::now()->format('Y'))['total_keg']/Generate::TotalKegiatan(Carbon\Carbon::now()->format('Y')))*100,2,".",",")}}%; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </div>
    </div>
    <!-- Column -->
</div>
<!-- Row -->