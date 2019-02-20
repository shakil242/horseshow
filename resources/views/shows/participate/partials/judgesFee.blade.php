@if($show->show_type == 'Western')                                                
  <div class="judges-break-down">
    <p><small>Class Price: <b>{{twodecimalformate($pResponse->ShowClassPrice->price)}}</b></small></p>
    <p><small>Judges Fee: <b>{{twodecimalformate($pResponse->ShowClassPrice->price_judges)}}</b></small></p>
  </div>
@endif