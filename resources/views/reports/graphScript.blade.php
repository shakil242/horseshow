<script type="text/javascript">
$(function () { 

  var xArrays = new Array(<?php echo getXOptions($question) ?>);
  var xySeries = [<?php echo $graphDataObj ?>];
  
  var options = {
                  chart: {
                     events: {
                          drilldown: function (e) {
                              if (!e.seriesOptions) {

                                  var chart = this;
                                  // Show the loading label
                                  chart.showLoading('Loading ...');

                                  setTimeout(function () {
                                      chart.hideLoading();
                                      chart.addSeriesAsDrilldown(e.point, series);
                                  }, 1000); 
                              }

                          }
                      },
                      plotBorderWidth: 0
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
                            stacking: 'normal',
                            borderWidth: 0,
                              dataLabels: {
                                  enabled: false
                              }
                        },                        
                        pie: {
                            plotBackgroundColor: null,
                            plotBorderWidth: null,
                            plotShadow: false,
                            cursor: 'pointer',
                            pointFormat: 'Total : <b>{point.y:.1f}</b> Cost given.',
                            dataLabels: {
                                  enabled: false
                              }
                        }
                  },
                  series: xySeries
              };

  //Default option to set the chart
  options.chart.renderTo = "<?php echo $question->unique_id; ?>";
  options.chart.type = "{{$type}}";
  var chart1 = new Highcharts.Chart(options);
  //On click of the chartfunc. Call to sVal = type, renderName = ID of the question
    $('input[type=radio][name={{$question->unique_id}}]').change(function() {
      var sVal = $(this).attr('id');
      var renderName = $(this).attr('name');
    if(sVal == "column")
          {
              options.chart.renderTo = renderName;
              options.chart.type = 'column';
              options.chart.options3d ={enabled: false};
              var chart1 = new Highcharts.Chart(options);
          }
      else if(sVal == "bar")
          {
              options.chart.renderTo = renderName;
              options.chart.type = 'bar';
              options.chart.options3d ={enabled: false};
              var chart1 = new Highcharts.Chart(options);
          }
      else if(sVal == "area")
          {
              options.chart.renderTo = renderName;
              options.chart.type = 'areaspline';
              options.chart.options3d ={enabled: false};
              var chart1 = new Highcharts.Chart(options);
          }
         else if(sVal == "3dcolumn")
          {
              options.chart.renderTo = renderName;
              options.chart.type = 'column';
              options.chart.options3d ={
                                            enabled: true,
                                            alpha: 10,
                                            beta: 25,
                                            depth: 70
                                        };
              var chart1 = new Highcharts.Chart(options);
          }
          
      else
          {
              options.chart.renderTo = renderName;
              options.chart.options3d ={enabled: false};
              options.chart.type = 'line';
              var chart1 = new Highcharts.Chart(options);
          }
    });
});
</script>