@php
 //$unit_nama = Generate::ChartNilaiTahunan($tahun)['unit_nama'];
 foreach ($dataPeringkat as $item ) {
     $unit_nama[] = $item->unit_nama;
     $nilai[]=number_format($item->point_rata,2,".",",");
 }
 $unit_nama = json_encode($unit_nama);
 $nilai = json_encode($nilai);
 $nilai = str_replace('"', '', $nilai);
 if ($unit > 0)
 {
    $data_unit = $dataUnitkerja->where('unit_kode','=',$unit)->first();
    $nama_unit = $data_unit->unit_nama;
 }
 else
 {
     $nama_unit ='';
 }
@endphp
<script>

    Highcharts.chart('nilai_bulanan', {
       chart: {
        type: 'bar'
    },
        title: {
            text: 'Grafik Nilai Bulan {{$dataBulan[(int)($bulan)]}} {{$tahun}} (Max 5)',
            x: -20 //center
        },
        subtitle: {
            text: '@if ($unit>0)Nilai Berdasarkan {{$nama_unit}} <br />@endif Keadaan : {{Tanggal::LengkapHariPanjang(\Carbon\Carbon::now())}}',
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
