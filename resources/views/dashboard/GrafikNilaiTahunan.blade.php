@php
 $unit_nama = Generate::ChartNilaiTahunan(\Carbon\Carbon::now()->format('Y'))['unit_nama'];
 $unit_nama = json_encode($unit_nama);
 $nilai = Generate::ChartNilaiTahunan(\Carbon\Carbon::now()->format('Y'))['point_rata'];
 $nilai = json_encode($nilai);
 $nilai = str_replace('"', '', $nilai);
@endphp
<script>
    
    Highcharts.chart('nilai_tahunan', {
       chart: {
        type: 'bar'
    },
        title: {
            text: 'Nilai Tahun {{\Carbon\Carbon::now()->isoFormat('YYYY')}} (Max 5)',
            x: -20 //center
        },
        subtitle: {
            text: 'Keadaan : {{Tanggal::LengkapHariPanjang(\Carbon\Carbon::now())}}',
            x: -20
        },
        xAxis: {
            categories: {!! $unit_nama !!},
             title: {
            text: null
        	}
        },
        yAxis: {
        min: 0,
        title: {
            text: '',
            align: 'high'
        },
        labels: {
            overflow: 'justify'
        }
    },
    tooltip: {
        valueSuffix: ''
    },
     plotOptions: {
        bar: {
            dataLabels: {
                enabled: true
            }
        }
    },
        legend: {
             enabled: false
        },
        series: [{
            name: 'Point',
            data: {!! $nilai !!}
        }]
    });

</script>