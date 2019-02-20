/****
* Created on : 22-08-2017.
* @Author: Faran Ahmed Khan.
* Vteams
****/
$(function () {

//Using High charts, To display the data
Highcharts.chart('container', {
    data: {
        table: 'datatable',
        startColumn:1,
        endColumn:2
    },
    chart: {
        type: 'line'
    },
    title: {
        text: 'Data extracted from table of scoring'
    },
    yAxis: {
        allowDecimals: false,
        title: {
            text: 'Total Score'
        }
    },
    tooltip: {
        formatter: function () {
            return '<b>' + this.series.name + '</b><br/>' +
                this.point.y + ' ' + this.point.name.toLowerCase();
        }
    }
});
//Sort table



});

