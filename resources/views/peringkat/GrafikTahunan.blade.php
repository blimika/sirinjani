@php
 //$unit_nama = Generate::ChartNilaiTahunan($tahun)['unit_nama'];
 foreach ($dataPeringkat as $item ) {
     $unit_nama[] = $item->unit_nama;
     $nilai[]=number_format($item->point_total,3,".",",");
 }
 $unit_nama = json_encode($unit_nama);
 $nilai = json_encode($nilai);
 $nilai = str_replace('"', '', $nilai);
@endphp
<script>

    Highcharts.chart('nilai_tahunan', {
       chart: {
        type: 'bar'
    },
        title: {
            text: 'Nilai Tahun {{$tahun}} (Max 5)',
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
