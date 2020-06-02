@php
 
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
        @for ($i = 1; $i < 11; $i++)
            {
            name: 'Nama {{$i}}',
            data: [7.0, 6.9, 9.5, {{$i}}, {{$i+1}}, {{$i+1.5}}, {{$i+2}}, 26.5, 23.3, 18.3, 13.9, 9.6]
            },
        @endfor
         
    ]
});
</script>

