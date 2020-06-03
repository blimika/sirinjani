@php
 $unit_nama = Generate::ChartNilaiTahunan(\Carbon\Carbon::now()->format('Y'))['unit_nama'];
 $point_rata = Generate::ChartNilaiTahunan(\Carbon\Carbon::now()->format('Y'))['point_rata']; 
@endphp
<script>
   Highcharts.chart('nilai_tahunan', {
    chart: {
        type: 'line'
    },
    title: {
        text: 'Nilai Rata-Rata Perbulan'
    },
    subtitle: {
        text: 'Keadaan : {{Tanggal::LengkapHariPanjang(\Carbon\Carbon::now())}} '
    },
    xAxis: {
        categories: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des']
    },
    yAxis: {
        title: {
            text: 'Nilai (max 5)'
        }
    },
    plotOptions: {
        line: {
            dataLabels: {
                enabled: true
            },
            enableMouseTracking: false
        }
    },
    series: [
        @foreach ($unit_nama as $kode => $nama)
        {
            name: '{{$nama}}',
            @php
                $nilai = json_encode($point_rata[$kode]);
                $nilai = str_replace('"', '', $nilai);
            @endphp
            data: {!! $nilai !!}
        },
        @endforeach
        ]
});
</script>

