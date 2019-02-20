<script type="text/javascript">
$(function () { 
  //Default option to set the chart
  var xArrays = <?php echo $nameDataObj ?>;
               Highcharts.chart("<?php echo $question->unique_id; ?>", {
                      chart: {
                          type: 'column'
                      },
                      title: {
                          text: '{{$question->form_name}}'
                      },
                      xAxis: {
                          categories: xArrays,
                          labels: {
                              rotation: -45,
                              style: {
                                  fontSize: '13px',
                                  fontFamily: 'Verdana, sans-serif'
                              }
                          }
                      },
                      yAxis: {
                          min: 0,
                          title: {
                              text: 'Rating'
                          }
                      },
                      legend: {
                          enabled: false
                      },
                      tooltip: {
                           @if($question->form_field_type == OPTION_MONETERY)
                          pointFormat: 'Total : <b>{point.y:.1f}</b> Cost given.'
                        @else
                          pointFormat: 'Rated : <b>{point.y:.1f}</b> out of 5'
                        @endif
                      },
                      series: [{
                          name: 'Ratings',
                          data: [<?php echo $graphDataObj;?>],
                          dataLabels: {
                              enabled: true,
                              rotation: -90,
                              color: '#FFFFFF',
                              align: 'right',
                              format: '{point.y:.1f}', // one decimal
                              y: 10, // 10 pixels down from the top
                              style: {
                                  fontSize: '13px',
                                  fontFamily: 'Verdana, sans-serif'
                              }
                          }
                      }]
                  });
  //On click of the chartfunc. Call to sVal = type, renderName = ID of the question
    $('input[type=radio][name={{$question->unique_id}}]').change(function() {
      var sVal = $(this).attr('id');
      var renderName = $(this).attr('name');
    if(sVal == "column")
          {
              var xArrays = <?php echo $nameDataObj ?>;
               Highcharts.chart("<?php echo $question->unique_id; ?>", {
                      chart: {
                          type: 'column'
                      },
                      title: {
                          text: '{{$question->form_name}}'
                      },
                      xAxis: {
                          categories: xArrays,
                          labels: {
                              rotation: -45,
                              style: {
                                  fontSize: '13px',
                                  fontFamily: 'Verdana, sans-serif'
                              }
                          }
                      },
                      yAxis: {
                          min: 0,
                          title: {
                              text: 'Rating'
                          }
                      },
                      legend: {
                          enabled: false
                      },
                      tooltip: {
                        @if($question->form_field_type == OPTION_MONETERY)
                          pointFormat: 'Total : <b>{point.y:.1f}</b> Cost given.'
                        @else
                          pointFormat: 'Rated : <b>{point.y:.1f}</b> out of 5'
                        @endif
                      },
                      series: [{
                          name: 'Ratings',
                          data: [<?php echo $graphDataObj;?>],
                          dataLabels: {
                              enabled: true,
                              rotation: -90,
                              color: '#FFFFFF',
                              align: 'right',
                              format: '{point.y:.1f}', // one decimal
                              y: 10, // 10 pixels down from the top
                              style: {
                                  fontSize: '13px',
                                  fontFamily: 'Verdana, sans-serif'
                              }
                          }
                      }]
                  });
          }
      else if(sVal == "pie")
          {
             var xArrays = <?php echo $nameDataObj ?>;
               Highcharts.chart("<?php echo $question->unique_id; ?>", {
                      chart: {
                          plotBackgroundColor: null,
                          plotBorderWidth: null,
                          plotShadow: false,
                          type: 'pie'
                      },
                      title: {
                          text: '{{$question->form_name}}'
                      },
                      tooltip: {
                        @if($question->form_field_type == OPTION_MONETERY)
                          pointFormat: 'Total : <b>{point.y:.1f}</b> Cost given.'
                        @else
                          pointFormat: 'Rated : <b>{point.y:.1f}</b> out of 5'
                        @endif
                      },
                       plotOptions: {
                          pie: {
                              allowPointSelect: true,
                              cursor: 'pointer',
                              dataLabels: {
                                  enabled: false
                              },
                              showInLegend: true
                          }
                      },
                      series: [{
                          name: 'Ratings',
                          colorByPoint: true,
                          data: [<?php echo $graphDataObj;?>],
                      }]
                  });
          }
      else if(sVal == "line")
          {
             var xArrays = <?php echo $nameDataObj ?>;
               Highcharts.chart("<?php echo $question->unique_id; ?>", {
                      chart: {
                          type: 'line'
                      },
                      title: {
                          text: '{{$question->form_name}}'
                      },
                      tooltip: {
                        @if($question->form_field_type == OPTION_MONETERY)
                          pointFormat: 'Total : <b>{point.y:.1f}</b> Cost given.'
                        @else
                          pointFormat: 'Rated : <b>{point.y:.1f}</b> out of 5'
                        @endif
                      },
                       plotOptions: {
                          pie: {
                              allowPointSelect: true,
                              cursor: 'pointer',
                              dataLabels: {
                                  enabled: false
                              },
                              showInLegend: true
                          }
                      },
                      series: [{
                          name: 'Ratings',
                          colorByPoint: true,
                          data: [<?php echo $graphDataObj;?>],
                      }]
                  });
          }
         else
          {
              var xArrays = <?php echo $nameDataObj ?>;
               Highcharts.chart("<?php echo $question->unique_id; ?>", {
                      chart: {
                          plotBackgroundColor: null,
                          plotBorderWidth: null,
                          plotShadow: false,
                          type: 'pie',
                          options3d: {
                              enabled: true,
                              alpha: 45,
                              beta: 0
                          }
                      },
                      title: {
                          text: '{{$question->form_name}}'
                      },
                      tooltip: {
                        @if($question->form_field_type == OPTION_MONETERY)
                          pointFormat: 'Total : <b>{point.y:.1f}</b> Cost given.'
                        @else
                          pointFormat: 'Rated : <b>{point.y:.1f}</b> out of 5'
                        @endif
                      },
                       plotOptions: {
                          pie: {
                              allowPointSelect: true,
                              cursor: 'pointer',
                              depth: 45,
                              dataLabels: {
                                  enabled: false
                              },
                              showInLegend: true
                          }
                      },
                      series: [{
                          name: 'Ratings',
                          colorByPoint: true,
                          data: [<?php echo $graphDataObj;?>],
                      }]
                  });
          }
    });
});
</script>