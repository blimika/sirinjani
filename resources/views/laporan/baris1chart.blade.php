<div class="row">
    @php
    $j=1;
    $nilai = 0;
    @endphp
    @foreach ($data_grafik_baris1 as $item)
        <div class="col-lg-3 col-sm-3 col-xs-12 text-center">
            <div>
                <canvas id="tim{{$j}}-nilai-kabkota" height="100"> </canvas>
            </div>
            <h5>{{$item->nama_tim}}</h5>
        </div>
        @php $j++; @endphp
    @endforeach
</div>
