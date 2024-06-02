<div class="card bg-white">
    <div class="card-header bg-info text-white">
        <h4 class="font-bold">{{$Ranking1Bulan->unit_nama}}</h4>
        <h6 class="text-muted">Peringkat 1 Bulan {{\Carbon\Carbon::now()->isoFormat('MMMM YYYY')}}</h6>
    </div>
    <div class="card-body">
        <div class="carousel slide" data-ride="carousel">
            <!-- Carousel items -->
            <div class="carousel-inner">
                <div class="carousel-item active flex-column">
                    <div class="row">
                        <div class="col-2"><i class="text-warning fas fa-trophy fa-2x"></i></div>
                        <div class="col-10"><h4>Peringkat 1 Bulan {{\Carbon\Carbon::now()->isoFormat('MMMM YYYY')}}</h4></div>
                    </div>
                </div>
                <div class="carousel-item flex-column">
                        <div class="row text-center m-b-0">
                            <div class="col-4">
                                <h4>{{$Ranking1Bulan->keg_jml}}</h4>
                                <h5 class="text-muted">Kegiatan</h5>
                            </div>
                            <div class="col-4">
                                <h4>{{$Ranking1Bulan->keg_jml_target}}</h4>
                                <h5 class="text-muted">Target</h5>
                            </div>
                            <div class="col-4">
                                    <h4>{{number_format($Ranking1Bulan->point_total,3,".",",")}}</h4>
                                    <h5 class="text-muted">Nilai</h5>
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div>
</div>
