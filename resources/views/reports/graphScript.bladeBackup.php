<script type="text/javascript">
$(function () { 

  var xArrays = new Array(<?php echo getXOptions($question) ?>);
  var xySeries = [<?php echo $graphDataObj ?>];
  
  var myChart = Highcharts.chart("<?php echo $question->unique_id; ?>", {
                  chart: {
                      type: "{{$type}}"
                  },
                  title: {
                      text: "{{$question->form_name}}"
                  },
                  xAxis: {
                      categories: xArrays
                  },
                  yAxis: {
                      min: 0,
                      title: {
                          text: 'Selected Total'
                      }
                  },
                  tooltip: {
                      pointFormat: '<span style="color:{series.color}">{series.name}</span>: <b>{point.y}</b> ({point.percentage:.0f}%)<br/>',
                      shared: true
                  },
                  plotOptions: {
                        series: {
                            stacking: 'normal'
                        }
                  },
                  series: xySeries
              });
});
</script>