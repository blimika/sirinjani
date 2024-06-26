<script type="text/javascript">
$(function () {
    "use strict";
    Morris.Area({
        element: 'kabkota-poin-tahunan',
        data: {!! $data_grafik_poin !!},
                lineColors: ['#55ce63'],
                xkey: 'bulan',
                ykeys: ['poin'],
                labels: ['Poin'],
                pointSize: 0,
                lineWidth: 0,
                resize:true,
                parseTime: false,
                fillOpacity: 0.8,
                behaveLikeLine: true,
                gridLineColor: '#e0e0e0',
                hideHover: 'auto'

    });
    /*
    Morris.Area({
        element: 'kabkota-kegiatan-tahunan',
        data: {!! $data_grafik_keg !!},
                lineColors: ['#0C3CCB'],
                xkey: 'bulan',
                ykeys: ['kegiatan'],
                labels: ['Kegiatan'],
                pointSize: 0,
                lineWidth: 0,
                resize:true,
                parseTime: false,
                fillOpacity: 0.8,
                behaveLikeLine: true,
                gridLineColor: '#e0e0e0',
                hideHover: 'auto'

    });
    */
    Morris.Area({
        element: 'kabkota-target-tahunan',
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
    //plugins
    //nilai tim
    /*
    new Chart(document.getElementById("tim1-nilai-kabkota"),
    {
        "type":"doughnut",
        "data":{"labels":["Poin",""],
        "datasets":[{
            "label":"",
            "data":[3.222,(5-3.222)],
            "backgroundColor":["rgb(255, 99, 132)"]}
        ]},
        "options":{
            "legend": {
                "display": false,
            }
        },
    });
    */
    Chart.pluginService.register({
      beforeDraw: function(chart) {
        if (chart.config.options.elements.center) {
          // Get ctx from string
          var ctx = chart.chart.ctx;

          // Get options from the center object in options
          var centerConfig = chart.config.options.elements.center;
          var fontStyle = centerConfig.fontStyle || 'Arial';
          var txt = centerConfig.text;
          var color = centerConfig.color || '#000';
          var maxFontSize = centerConfig.maxFontSize || 75;
          var sidePadding = centerConfig.sidePadding || 20;
          var sidePaddingCalculated = (sidePadding / 100) * (chart.innerRadius * 2)
          // Start with a base font of 30px
          ctx.font = "30px " + fontStyle;

          // Get the width of the string and also the width of the element minus 10 to give it 5px side padding
          var stringWidth = ctx.measureText(txt).width;
          var elementWidth = (chart.innerRadius * 2) - sidePaddingCalculated;

          // Find out how much the font can grow in width.
          var widthRatio = elementWidth / stringWidth;
          var newFontSize = Math.floor(30 * widthRatio);
          var elementHeight = (chart.innerRadius * 2);

          // Pick a new font size so it will not be larger than the height of label.
          var fontSizeToUse = Math.min(newFontSize, elementHeight, maxFontSize);
          var minFontSize = centerConfig.minFontSize;
          var lineHeight = centerConfig.lineHeight || 25;
          var wrapText = false;

          if (minFontSize === undefined) {
            minFontSize = 20;
          }

          if (minFontSize && fontSizeToUse < minFontSize) {
            fontSizeToUse = minFontSize;
            wrapText = true;
          }

          // Set font settings to draw it correctly.
          ctx.textAlign = 'center';
          ctx.textBaseline = 'middle';
          var centerX = ((chart.chartArea.left + chart.chartArea.right) / 2);
          var centerY = ((chart.chartArea.top + chart.chartArea.bottom) / 2);
          ctx.font = fontSizeToUse + "px " + fontStyle;
          ctx.fillStyle = color;

          if (!wrapText) {
            ctx.fillText(txt, centerX, centerY);
            return;
          }

          var words = txt.split(' ');
          var line = '';
          var lines = [];

          // Break words up into multiple lines if necessary
          for (var n = 0; n < words.length; n++) {
            var testLine = line + words[n] + ' ';
            var metrics = ctx.measureText(testLine);
            var testWidth = metrics.width;
            if (testWidth > elementWidth && n > 0) {
              lines.push(line);
              line = words[n] + ' ';
            } else {
              line = testLine;
            }
          }

          // Move the center up depending on line height and number of lines
          centerY -= (lines.length / 2) * lineHeight;

          for (var n = 0; n < lines.length; n++) {
            ctx.fillText(lines[n], centerX, centerY);
            centerY += lineHeight;
          }
          //Draw text in center
          ctx.fillText(line, centerX, centerY);
        }
      }
    });

@php
$j=1;
$nilai = 0;
@endphp
@foreach ($data_grafik_baris1 as $item)
    @php
    $nilai = number_format($item->point_total,3,".",",");
    if ($nilai < 3)
    {
        $warna_tulisan = '#E60C3A';
    }
    else
    {
        $warna_tulisan = '#22C31D';
    }
    @endphp
    var tim{{$j}} = {
    type: 'doughnut',
    data: {
        labels: [
        "Poin: ",
        ""
        ],
        datasets: [{
        data: [{{$nilai}},{{(5-$nilai)}}],
        backgroundColor: [
            "#22C31D","#E60C3A"
        ],
        hoverBackgroundColor: [
            "#22C31D","#E60C3A"
        ]
        }]
    },
    options: {
        responsive: true,
        legend: {
                    "display": false,
        },
        elements: {
        center: {
            text: '{{$nilai}}',
            color: '{{$warna_tulisan}}', // Default is #000000
            fontStyle: 'Arial', // Default is Arial
            sidePadding: 10, // Default is 20 (as a percentage)
            minFontSize: 15, // Default is 20 (in px), set to false and text will not wrap.
            lineHeight: 20 // Default is 25 (in px), used for when text wraps
        }
        }
    }
    };

    var ctx = document.getElementById("tim{{$j}}-nilai-kabkota").getContext("2d");
    var tim{{$j}}chart = new Chart(ctx, tim{{$j}});
    @php $j++ @endphp
@endforeach

@if ($data_grafik_baris2)
@php
$j=5;
$nilai = 0;
@endphp
@foreach ($data_grafik_baris2 as $item)
    @php
    $nilai = number_format($item->point_total,3,".",",");
    if ($nilai < 3)
    {
        $warna_tulisan = '#E60C3A';
    }
    else
    {
        $warna_tulisan = '#22C31D';
    }
    @endphp
    var tim{{$j}} = {
    type: 'doughnut',
    data: {
        labels: [
        "Poin: ",
        ""
        ],
        datasets: [{
        data: [{{$nilai}},{{(5-$nilai)}}],
        backgroundColor: [
            "#22C31D","#E60C3A"
        ],
        hoverBackgroundColor: [
            "#22C31D","#E60C3A"
        ]
        }]
    },
    options: {
        responsive: true,
        legend: {
                    "display": false,
        },
        elements: {
        center: {
            text: '{{$nilai}}',
            color: '{{$warna_tulisan}}', // Default is #000000
            fontStyle: 'Arial', // Default is Arial
            sidePadding: 10, // Default is 20 (as a percentage)
            minFontSize: 15, // Default is 20 (in px), set to false and text will not wrap.
            lineHeight: 20 // Default is 25 (in px), used for when text wraps
        }
        }
    }
    };

    var ctx = document.getElementById("tim{{$j}}-nilai-kabkota").getContext("2d");
    var tim{{$j}}chart = new Chart(ctx, tim{{$j}});
    @php $j++ @endphp
@endforeach

@endif

 });
</script>
