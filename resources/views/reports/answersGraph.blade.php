<?php $qno = 1;?>
@foreach ($formfields as $question)
@if($question->form_field_type != OPTION_LABEL && $question->form_field_type != OPTION_HYPERLINK) 
<br>
<h2>{{$qno}}) Question <strong>({{$question->form_name}} )</strong></h2>
<div class="summary-holder">
  <div class="row">
    <!-- For Text, Email and Describtion -->
    @if($question->form_field_type == OPTION_TEXT || $question->form_field_type == OPTION_EMAIL || $question->form_field_type == OPTION_LABEL || $question->form_field_type == OPTION_TEXTAREA)
      <div class="summary-text-responses col">
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
                  <?php $Answer = GetSimpleAnswersArray($answerfields,$question); $Indx = $Indx+1; ?>
                  <tr>
                    <td>{{$Indx}})</td>
                    <td class="warning">{{getUserNamefromid($answerfields->user_id)}}</td>
                    <td class="success"><?php echo $Answer ?></td>
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
      <div class="summary-text-responses col">
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
                  <?php $Answer = GetSimpleAnswersArray($answerfields,$question); $Indx = $Indx+1; ?>
                  <tr>
                    <td>{{$Indx}})</td>
                    <td class="warning">{{getUserNamefromid($answerfields->user_id)}}</td>
                    <td><span class="label"><?php echo $Answer ?></span></td>
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
      <div class="summary-text-responses col">
        <div class="ans-header"><h4 class="text-success">Answers</h4></div>
        <div class="ans-body">
          <?php $Indx = 0; $graphDataObj = "";?>
          @foreach ($participantResponse as $answerfields)
            <?php   
                $graphDataObj .="{name:'".getUserNamefromid($answerfields->user_id)."',"."data: [".getXYSeries($answerfields,$question)."]},"; 
                $graphDataObj .= getDuplicatedGraphicalVals($answerfields,$question);
                $Indx = $Indx+1; 
            ?>
          @endforeach 
          <div class="row">
            <div class="col-sm-8"> 
              <label>
                <input type="radio" name="{{$question->unique_id}}" id= "column">
                <span>Column</span>
              </label>
              <label>
                <input type="radio" name="{{$question->unique_id}}" id= "bar" checked>
                <span>Bar</span>
              </label>
              <label>
                <input type="radio" name="{{$question->unique_id}}" id= "area">
                <span>Area</span>
              </label>
              <label>
                <input type="radio" name="{{$question->unique_id}}" id= "line">
                <span>Line</span>
              </label>
              <label>
                <input type="radio" name="{{$question->unique_id}}" id= "3dcolumn">
                <span>3dcolumn</span>
              </label>
            
            </div>
          </div>
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
      <div class="summary-text-responses col">
        <div class="ans-header"><h4 class="text-success">Answers</h4></div>
        <div class="ans-body">
          <?php $Indx = 0; $graphDataObj = "";?>
          @foreach ($participantResponse as $answerfields)
            <?php   
                $graphDataObj .="{name:'".getUserNamefromid($answerfields->user_id)."',"."data: [".getXYSeries($answerfields,$question)."]},"; 
                //if (checkDuplicateArray($answerfields,$question)) {
                    $graphDataObj .= getDuplicatedGraphicalVals($answerfields,$question);
                //}
                $Indx = $Indx+1; 
            ?>
          @endforeach 
          <div class="row">
            <div class="col-sm-8"> 
              <label>
                <input type="radio" name="{{$question->unique_id}}" checked id= "column">
                <span>Column</span>
              </label>
              <label>
                <input type="radio" name="{{$question->unique_id}}" id= "bar" >
                <span>Bar</span>
              </label>
              <label>
                <input type="radio" name="{{$question->unique_id}}" id= "area">
                <span>Area</span>
              </label>
              <label>
                <input type="radio" name="{{$question->unique_id}}" id= "line">
                <span>Line</span>
              </label>
              <label>
                <input type="radio" name="{{$question->unique_id}}" id= "3dcolumn">
                <span>3dcolumn</span>
              </label>
            
            </div>
          </div>
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
      <div class="summary-text-responses col">
        <div class="ans-header"><h4 class="text-success">Answers</h4></div>
        <div class="ans-body">
          <?php $Indx = 0; $graphDataObj = ""; $nameDataObj = "[";?>
          @foreach ($participantResponse as $answerfields)
            <?php   
                $nameDataObj .= GetUserNameArray($answerfields->user_id,$answerfields,$question);
                $graphDataObj .= GetAnswersArray($answerfields,$question, $answerfields->user_id); 
                //$graphDataObj .="['".getUserNamefromid($answerfields->user_id)."', ".GetAnswersArray($answerfields,$question)."],"; 
                $Indx = $Indx+1; 
            ?>
          @endforeach
          <?php $nameDataObj .= "]";  ?> 
           <div class="row">
            <div class="col-sm-8"> 
              <label>
                <input type="radio" name="{{$question->unique_id}}" checked id= "column">
                <span>Column</span>
              </label>
              <label>
                <input type="radio" name="{{$question->unique_id}}" id="3dpie">
                <span>3d Pie</span>
              </label>
              <label>
                <input type="radio" name="{{$question->unique_id}}" id= "area">
                <span>Area</span>
              </label>
              <label>
                <input type="radio" name="{{$question->unique_id}}" id= "line">
                <span>Line</span>
              </label>
              <label>
                <input type="radio" name="{{$question->unique_id}}" id= "pie">
                <span>Pie</span>
              </label>
            </div>
          </div>
           <div id="{{$question->unique_id}}" style="width:100%; height:400px;"></div>
            @include('reports.graphScriptPie',['type'=>'column']) 
            
        </div>
        <div class="ans-footer">
          ( <span> {{$Indx}} Responses</span> )
        </div>
      </div>      
    @endif

     <!-- For Ratings   -->
    @if($question->form_field_type == OPTION_MONETERY || $question->form_field_type == OPTION_NUMARIC)
      <div class="summary-text-responses col">
        <div class="ans-header"><h4 class="text-success">Answers</h4></div>
        <div class="ans-body">
          <?php $Indx = 0; $graphDataObj = ""; $nameDataObj = "[";?>
          @foreach ($participantResponse as $answerfields)
            <?php   
                $nameDataObj .= GetUserNameWithDuplicate($answerfields,$question, $answerfields->user_id);
                $graphDataObj .= GetAnswersArray($answerfields,$question, $answerfields->user_id);
                $Indx = $Indx+1;
            ?>

          @endforeach
          <?php $nameDataObj .= "]"; ?> 
           <div class="row">
            <div class="col-sm-8"> 
              <label>
                <input type="radio" name="{{$question->unique_id}}" checked id="column">
                <span>Column</span>
              </label>
              <label>
                <input type="radio" name="{{$question->unique_id}}" id="3dpie">
                <span>3d Pie</span>
              </label>
              <label>
                <input type="radio" name="{{$question->unique_id}}" id= "area">
                <span>Area</span>
              </label>
              <label>
                <input type="radio" name="{{$question->unique_id}}" id= "line">
                <span>Line</span>
              </label>
              <label>
                <input type="radio" name="{{$question->unique_id}}" id= "pie">
                <span>Pie</span>
              </label>
            </div>
          </div>
           <div id="{{$question->unique_id}}" style="width:100%; height:400px;"></div>
           @include('reports.graphScriptPie',['type'=>'pie'])
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