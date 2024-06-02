<div class="card bg-white">
    <div class="card-header bg-success text-white">
        <h4 class="font-bold">{{$Ranking1Tahun->unit_nama}}</h4>
        <h6 class="text-muted">Peringkat 1 Tahun 2024</h6>
    </div>
    <div class="card-body">
        <div class="carousel vert slide" data-ride="carousel">
            <!-- Carousel items -->
            <div class="carousel-inner">
                <div class="carousel-item active flex-column">
                    <div class="row">
                        <div class="col-2"><i class="text-warning fas fa-trophy fa-2x"></i></div>
                        <div class="col-10"><h4>Peringkat 1 Tahun 2024</h4></div>
                    </div>
                </div>
                <div class="carousel-item flex-column">
                    <div class="row text-center m-b-0">
                        <div class="col-4">
                            <h4>{{$Ranking1Tahun->keg_jml}}</h4>
                            <h5 class="text-muted">Kegiatan</h5>
                        </div>
                        <div class="col-4">
                            <h4>{{$Ranking1Tahun->keg_jml_target}}</h4>
                            <h5 class="text-muted">Target</h5>
                        </div>
                        <div class="col-4">
                                <h4>{{number_format($Ranking1Tahun->point_total,3,".",",")}}</h4>
                                <h5 class="text-muted">Nilai</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
