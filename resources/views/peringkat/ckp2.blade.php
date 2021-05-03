<div class="table-responsive">
    <h4 class="card-title text-center">Nilai Poin Kabupaten/Kota</h4>

    <table class="table table-bordered table-hover table-striped" cellspacing="0" width="100%">
        <thead>
        <tr>
            <th rowspan="2" class="text-center">Nama Kabkota</th>
            <th colspan="12" class="text-center">Tahun {{$tahun}} </th>
        </tr>
        <tr>
            @for ($i = 1; $i <= 12; $i++)
               <td>{{$dataBulan[$i]}}</td>
            @endfor
        </tr>
        </thead>
        <tbody>
            @foreach ($dataKabkota as $item)
                <tr>
                    <td>{{$item->unit_nama}}</td>
                    @for ($i = 1; $i <= 12; $i++)
                        <td>
                            @if ((int) Generate::NilaiCkpBulan($item->unit_kode,$i,$tahun)['nilai_point'] == 0)
                                -
                            @else
                                @if (date('Y')==$tahun && $i >= date('m'))
                                -
                                @else
                                {{Generate::NilaiCkpBulan($item->unit_kode,$i,$tahun)['nilai_point']}}
                                @endif
                            @endif
                        </td>
                    @endfor
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
