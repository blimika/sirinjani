<div class="col-lg-12 col-xlg-12 col-md-12">
    <div class="card">
        <div class="card-body">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs customtab" role="tablist">
                <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#detil" role="tab"><span class="hidden-sm-up"><i class="ti-home"></i></span> <span class="hidden-xs-down">Detil</span></a> </li>
                <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#rekapkeg" role="tab"><span class="hidden-sm-up"><i class="ti-user"></i></span> <span class="hidden-xs-down">Progress Kegiatan</span></a> </li>
                @if ($dataKegiatan->keg_spj==1)
                <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#rekapspj" role="tab"><span class="hidden-sm-up"><i class="ti-user"></i></span> <span class="hidden-xs-down">Progress SPJ</span></a> </li>
                <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#nilaitotal" role="tab"><span class="hidden-sm-up"><i class="ti-user"></i></span> <span class="hidden-xs-down">Nilai Total</span></a> </li>
                @endif
            </ul>
            <!-- Tab panes -->
            <div class="tab-content">
                <div class="tab-pane active" id="detil" role="tabpanel">
                    <div class="p-10">
                       @include('kegiatan.detil.tab1')
                    </div>
                </div>
                <div class="tab-pane" id="rekapkeg" role="tabpanel">
                    <div class="p-10">
                        @include('kegiatan.detil.tab2')
                     </div>
                </div>
                @if ($dataKegiatan->keg_spj==1)
                <div class="tab-pane" id="rekapspj" role="tabpanel">
                    <div class="p-10">
                        @include('kegiatan.detil.tab3')
                     </div>
                </div>
                <div class="tab-pane" id="nilaitotal" role="tabpanel">
                    <div class="p-10">
                        @include('kegiatan.detil.tab4')
                     </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
