<script type="text/javascript">
    $(function () {
        "use strict";
        Morris.Area({
            element: 'provinsi-target-tahunan',
            data: {!! $data_grafik_target !!},
                    lineColors: ['#55ce63', '#6726B6', '#009efb'],
                    xkey: 'bulan',
                    ykeys: ['target','kirim','terima'],
                    labels: ['Target','Dikirim','Diterima'],
                    pointSize: 0,
                    lineWidth: 0,
                    resize:true,
                    parseTime: false,
                    fillOpacity: 0.8,
                    behaveLikeLine: true,
                    gridLineColor: '#e0e0e0',
                    hideHover: 'auto'

        });


     });
    </script>
