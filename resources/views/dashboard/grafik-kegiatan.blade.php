<script type="text/javascript">
    $(function () {
        "use strict";
        Morris.Area({
            element: 'kegiatan-depan',
            data: {!! $data_grafik_keg !!},
                    lineColors: ['#6726B6', '#009efb','#55ce63'],
                    xkey: 'bulan',
                    ykeys: ['tahun0','tahun1','tahun2'],
                    labels: {!! $data_grafik_label !!},
                    pointSize: 0,
                    lineWidth: 0,
                    resize:true,
                    parseTime: false,
                    fillOpacity: 0.6,
                    behaveLikeLine: true,
                    gridLineColor: '#e0e0e0',
                    hideHover: 'auto'
        });


     });
    </script>
