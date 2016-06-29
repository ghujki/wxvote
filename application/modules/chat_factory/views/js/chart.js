/**
 * Created by ghujk on 2016/6/15.
 */
$(function () {
    Highcharts.setOptions({
        colors: ['#3EB642', '#237C30', '#74C4F1','#468EE5', '#E7EC93'],
        lang:{months:["1","2","3","4","5","6","7","8","9","10","11","12"]}
    });

    $('#container').highcharts({
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
            height:325,
            width:400,
            marginBottom:50,
            backgroundColor:'#F4F5F9'
        },
        title: {
            text: '人数来源分析'
        },
        legend:{
            itemWidth:100
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    format: '{point.percentage:.2f} %'
                },
                showInLegend: true,
                size:140
            }
        },
        series: [{
            type: 'pie',
            name: 'chat members',
            innerSize:"70%",
            data: chat_data
        }]
    });

    $('#container-1').highcharts({
        chart: {
            type: 'spline'
        },
        title: {
            text: '匹配时间分析'
        },
        xAxis: {
            type: 'datetime',
            dateTimeLabelFormats: { // don't display the dummy year
                day: '%B月%e日',
                hour: '%H:%M',
                month: '%B月',
                year:'%Y'
            }
        },
        yAxis: {
            title: {
                text: '匹配人数 (人)'
            },
            min: 0
        },
        tooltip: {
            formatter: function () {
                return '<b>' + this.series.name + '</b><br/>' +
                    Highcharts.dateFormat('%B月%e日 %H时', this.x) + ': ' + this.y + ' 人';
            }
        },

        series: [{
            name: '2016',
            // Define the data points. All series have a dummy year
            // of 1970/71 in order to be compared on the same x axis. Note
            // that in JavaScript, months start at 0 for January, 1 for February etc.
            data: chat_data2
        }]
    });
});