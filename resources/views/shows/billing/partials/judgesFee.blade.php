@if($show_type == 'Western')
@if(isset($class->ShowClassPrice))                                       
  <div class="judges-break-down">
    <p><small>Class Price: <b>{{twodecimalformate($class->ShowClassPrice->price)}}</b></small></p>
    <p><small>Judges Fee: <b>{{twodecimalformate($class->ShowClassPrice->price_judges)}}</b></small></p>
  </div>
@endif
@endif