<?php 
if ($highest_score == 0) {
  $highest_score=1;
}
for($ix=100;$ix >=10; $ix-=10){
  ${"yaxis" . $ix}=0;
  ${"data_y_array" . $ix} = array();
}
$loopindex = 1;
$currentbar = "null";
foreach ($usersResponseArray as $UserRes) {
  $userpercent = $UserRes["score"]/$highest_score*100;
  //$user_scorep = $UserRes["score"]/$highest_score;

  for($ix=100;$ix >10; $ix-=10){
    $ix_minus = $ix-10;

    if ($userpercent <= $ix && $userpercent > $ix_minus) {

      ${"yaxis" . $ix}= ${"yaxis" . $ix}+1;
      ${"data_y_array" . $ix}[] = [$UserRes['u_name'],$UserRes["score"]];

        if($UserRes['user_id'] == \Auth::user()->id){
            $currentbar = ceil(9-($ix_minus/10));
        }

    }
  }
  if ($userpercent < 10) {
   $yaxis10 = $yaxis10 +1;
   ${"data_y_array" . $ix}[] = [$UserRes['u_name'],$UserRes["score"]];
   if($UserRes['user_id'] == \Auth::user()->id){
             $currentbar = 9;
        }
  }
  $loopindex = $loopindex+1;
}
?>
<script type="text/javascript">
$(function () { 
var leaderboardChart = Highcharts.chart('container2', {
    colors:['#000055'],
    chart: {
        type: 'column',
        events: {
                load: function(){
                    if (<?php echo $currentbar ?> != null) {
                        var p = this.series[0].points[{{$currentbar}}];
                        this.tooltip.refresh(p);
                    };
                    //this.series[0].data[{{$currentbar}}].graphic.attr("fill","Green");

                },
            }
    },
    title: {
        text: 'Leadership Board for app users'
    },
    xAxis: {
        type: 'category'
    },
    yAxis: {
        title: {
            text: 'User Rankings'
        }

    },
    legend: {
        enabled: false
    },
    plotOptions: {
        series: {
            borderWidth: 0,
        }
    },

    tooltip: {
      pointFormat: '<span style="color:{series.color}">{series.name}</span>: <b>{point.z}</b> out of {{$total_users_responses}} Users<br/>',
      //shared: true,
    },

    series: [{
        name: 'Users',
        colorByPoint: true,
        data: [
        {
            name: 'Top 10%',
            y: 100,
            z: {{$yaxis100}},
            drilldown: 'top100'
        }, {
            name: 'Top 20%',
            y: 90,
            z: {{$yaxis90}},
            drilldown: 'top90'
        }, {
            name: 'Top 30%',
            y: 80,
            z: {{$yaxis80}}, 
            drilldown: 'top80'
        }, {
            name: 'Top 40%',
            y: 70,
            z: {{$yaxis70}}, 
            drilldown: 'top70'
        }, {
            name: 'Top 50%',
            y: 60,
            z: {{$yaxis60}}, 
            drilldown: 'top60'
        }, {
            name: 'In 60%',
            y: 50,
            z: {{$yaxis50}}, 
            drilldown: 'top50'
        }, {
            name: 'In 70%',
            y: 40,
            z: {{$yaxis40}}, 
            drilldown: 'top40'
        } , {
            name: 'In 80%',
            y: 30,
            z: {{$yaxis30}}, 
            drilldown: 'top30'
        }, {
            name: 'In 90%',
            y: 20,
            z: {{$yaxis20}}, 
            drilldown: 'top20'
        }, {
            name: '10% and Below',
            y: 10,
            z: {{$yaxis10}}, 
            drilldown: 'top10'
        },]
    }],
    drilldown: {
        series: [
        {
            name: 'In Top 10%',
            id: 'top100',
            tooltip: { pointFormat: '<span style="color:{series.color}">Total Score : {point.y}</span><br/>' },
            data: <?php echo json_encode($data_y_array100); ?>
        }, {
            name: 'In Top 20%',
            id: 'top90',
            tooltip: { pointFormat: '<span style="color:{series.color}">Total Score : {point.y}</span><br/>' },
            data: <?php echo json_encode($data_y_array90); ?>
        },{
            name: 'In Top 30%',
            id: 'top80',
            tooltip: { pointFormat: '<span style="color:{series.color}">Total Score : {point.y}</span><br/>' },
            data: <?php echo json_encode($data_y_array80); ?>
        },{
            name: 'In Top 40%',
            id: 'top70',
            tooltip: { pointFormat: '<span style="color:{series.color}">Total Score : {point.y}</span><br/>' },
            data: <?php echo json_encode($data_y_array70); ?>
        }, {
            name: 'In Top 50%',
            id: 'top60',
            tooltip: { pointFormat: '<span style="color:{series.color}">Total Score : {point.y}</span><br/>' },
            data: <?php echo json_encode($data_y_array60); ?>
        },
        {
            name: 'In Average 60%',
            id: 'top50',
            tooltip: { pointFormat: '<span style="color:{series.color}">Total Score : {point.y}</span><br/>' },
            data: <?php echo json_encode($data_y_array50); ?>
        },
        {
            name: 'In Average 70%',
            id: 'top40',
            tooltip: { pointFormat: '<span style="color:{series.color}">Total Score : {point.y}</span><br/>' },
            data: <?php echo json_encode($data_y_array40); ?>
        },
        {
            name: 'In below 80%',
            id: 'top30',
            tooltip: { pointFormat: '<span style="color:{series.color}">Total Score : {point.y}</span><br/>' },
            data: <?php echo json_encode($data_y_array30); ?>
        },
        {
            name: 'In below 90%',
            id: 'top20',
            tooltip: { pointFormat: '<span style="color:{series.color}">Total Score : {point.y}</span><br/>' },
            data: <?php echo json_encode($data_y_array20); ?>
        },
        {
            name: '100% and below Average',
            id: 'top10',
            tooltip: { pointFormat: '<span style="color:{series.color}">Total Score : {point.y}</span><br/>' },
            data: <?php echo json_encode($data_y_array10); ?>,
            color: '#FF0000',
        }
    ]
  }

},function(chart){
            if (<?php echo $currentbar ?> != null) {
                      chart.series[0].data[{{$currentbar}}].update({
                        //color:'green',
                    });
            };
            
        }
);

//Class data table for sorting default
  var defaultsort2 = $('table.defaultsort_second').DataTable({
    "pageLength": 8,
    "language": {
       "paginate": {
                    "first":       "First",
                    "last":       "Last",
                    "next":       "<i class='fa fa-angle-right' aria-hidden='true'></i>",
                    "previous":   "<i class='fa fa-angle-left' aria-hidden='true'></i>"
                },
    },
    aaSorting: [[2, 'desc']],
  });
  $('#mySearchTerm2').keyup(function(){
        defaultsort2.search($(this).val()).draw() ;
  })
    defaultsort2.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
        cell.innerHTML +="  ";
        cell.innerHTML +=i+1;
    }).draw();

});




</script>