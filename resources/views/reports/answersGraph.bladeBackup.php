<?php $qno = 1;?>
@foreach ($formfields as $question)
@if($question->form_field_type != OPTION_LABEL && $question->form_field_type != OPTION_HYPERLINK) 
<h2>{{$qno}}) Question <strong>({{$question->form_name}} )</strong></h2>
<div class="summary-holder">
  <div class="row">
    <!-- For Text, Email and Describtion -->
    @if($question->form_field_type == OPTION_TEXT || $question->form_field_type == OPTION_EMAIL || $question->form_field_type == OPTION_LABEL || $question->form_field_type == OPTION_TEXTAREA)
      <div class="summary-text-responses">
       <div class="row">
         <div class="col-sm-8">
            <div class="ans-header"><h4 class="text-success">Responses</h4></div>
            <div class="ans-body">
              <?php $Indx = 0; ?>
              <table class="table table-striped dataTableView">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Participants</th>
                  <th>Answer</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($participantResponse as $answerfields)
                  <?php $Answer = GetAnswersArray($answerfields,$question); $Indx = $Indx+1; ?>
                  <tr>
                    <td>{{$Indx}})</td>
                    <td class="warning">{{getUserNamefromid($answerfields->user_id)}}</td>
                    <td class="success">{{$Answer}}</td>
                  </tr>
                @endforeach
                </tbody>
               </table> 
            </div>
            <div class="ans-footer">
              ( <span> {{$Indx}} Responses</span> )
            </div>
         </div>
       </div>
      </div>   
    @endif

    <!-- For date and time  -->
    @if($question->form_field_type == OPTION_DATE_PICKER || $question->form_field_type == OPTION_TIME_PICKER)
      <div class="summary-text-responses">
       <div class="row">
         <div class="col-sm-8">
            <div class="ans-header"><h4 class="text-success">Responses</h4></div>
            <div class="ans-body">
              <?php $Indx = 0; ?>
              <table class="table table-striped dataTableView">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Participants</th>
                  <th>Answer</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($participantResponse as $answerfields)
                  <?php $Answer = GetAnswersArray($answerfields,$question); $Indx = $Indx+1; ?>
                  <tr>
                    <td>{{$Indx}})</td>
                    <td class="warning">{{getUserNamefromid($answerfields->user_id)}}</td>
                    <td><span class="label label-success">{{$Answer}}</span></td>
                  </tr>
                @endforeach
                </tbody>
               </table> 
            </div>
            <div class="ans-footer">
              ( <span> {{$Indx}} Responses</span> )
            </div>
         </div>
       </div>
      </div>      
    @endif

    <!-- For date and time  -->
    @if($question->form_field_type == OPTION_CHECKBOX || $question->form_field_type == OPTION_AUTO_POPULATE)
      <div class="summary-text-responses">
        <div class="ans-header"><h4 class="text-success">Answers</h4></div>
        <div class="ans-body">
          <?php $Indx = 0; $graphDataObj = "";?>
          @foreach ($participantResponse as $answerfields)
            <?php   
                $graphDataObj .="{name:'".getUserNamefromid($answerfields->user_id)."',"."data: [".getXYSeries($answerfields,$question)."]},"; 
                $Indx = $Indx+1; 
            ?>
          @endforeach 
           <div id="{{$question->unique_id}}" style="width:95%; height:400px;"></div>
            @include('reports.graphScript',['type'=>'bar']) 

        </div>
        <div class="ans-footer">
          ( <span> {{$Indx}} Responses</span> )
        </div>
      </div>      
    @endif
    <!-- End of fields -->
    <!-- For Radio Buttons   -->
    @if($question->form_field_type == OPTION_RADIOBUTTON || $question->form_field_type == OPTION_DROPDOWN)
      <div class="summary-text-responses">
        <div class="ans-header"><h4 class="text-success">Answers</h4></div>
        <div class="ans-body">
          <?php $Indx = 0; $graphDataObj = "";?>
          @foreach ($participantResponse as $answerfields)
            <?php   
                $graphDataObj .="{name:'".getUserNamefromid($answerfields->user_id)."',"."data: [".getXYSeries($answerfields,$question)."]},"; 
                $Indx = $Indx+1; 
            ?>
          @endforeach 

           <div id="{{$question->unique_id}}" style="width:100%; height:400px;"></div>
            @include('reports.graphScript',['type'=>'column']) 


        </div>
        <div class="ans-footer">
          ( <span> {{$Indx}} Responses</span> )
        </div>
      </div>      
    @endif

     <!-- For Ratings   -->
    @if($question->form_field_type == OPTION_RATINGS )
      <div class="summary-text-responses">
        <div class="ans-header"><h4 class="text-success">Answers</h4></div>
        <div class="ans-body">
          <?php $Indx = 0; $graphDataObj = ""; $nameDataObj = "[";?>
          @foreach ($participantResponse as $answerfields)
            <?php   
                $nameDataObj .="'".getUserNamefromid($answerfields->user_id)."',";
                $graphDataObj .="['".getUserNamefromid($answerfields->user_id)."', ".GetAnswersArray($answerfields,$question)."],"; 
                $Indx = $Indx+1; 
            ?>
          @endforeach
          <?php $nameDataObj .= "]"; ?> 
           <div id="{{$question->unique_id}}" style="width:100%; height:400px;"></div>
            <script type="text/javascript">
            $(function () { 
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
                          pointFormat: 'Rated : <b>{point.y:.1f}</b> out of 5'
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
            });
            </script>
        </div>
        <div class="ans-footer">
          ( <span> {{$Indx}} Responses</span> )
        </div>
      </div>      
    @endif

     <!-- For Ratings   -->
    @if($question->form_field_type == OPTION_MONETERY )
      <div class="summary-text-responses">
        <div class="ans-header"><h4 class="text-success">Answers</h4></div>
        <div class="ans-body">
          <?php $Indx = 0; $graphDataObj = ""; $nameDataObj = "[";?>
          @foreach ($participantResponse as $answerfields)
            <?php   
                $nameDataObj .="'".getUserNamefromid($answerfields->user_id)."',";
                $graphDataObj .="['".getUserNamefromid($answerfields->user_id)."', ".GetAnswersArray($answerfields,$question)."],"; 
                $Indx = $Indx+1; 
            ?>
          @endforeach
          <?php $nameDataObj .= "]"; ?> 
           <div id="{{$question->unique_id}}" style="width:100%; height:400px;"></div>
            <script type="text/javascript">
            $(function () { 
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
            });
            </script>
        </div>
        <div class="ans-footer">
          ( <span> {{$Indx}} Responses</span> )
        </div>
      </div>      
    @endif
    <!-- End of fields -->
  </div>
</div>
<?php $qno= $qno+1; ?>
@endif
@endforeach