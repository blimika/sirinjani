<div class="table-responsive">
    <table class="table table-striped table-hover" >
        <thead>
        <tr>
            <th class="text-center">No</th>
            <th class="text-center">Unit Kerja</th>
            <th class="text-center">Nilai Kegiatan</th>
            <th class="text-center">Nilai SPJ</th>
            <th class="text-center">Total Nilai</th>
        </tr>
        </thead>
        <tbody>
            @foreach ($dataKegiatan->Target->where('keg_t_target','>','0') as $item)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$item->Unitkerja->unit_nama}}</td>
                    <td class="text-right">{{number_format($item->keg_t_point,2,",",".")}}</td>
                    <td class="text-right">{{number_format($item->spj_t_point,2,",",".")}}</td>
                    <td class="text-right">{{number_format($item->keg_t_point_total,2,",",".")}}</td>
                </tr>
            @endforeach
            
        </tbody>
    </table>
</div>
