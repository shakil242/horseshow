

@if (count($errors) > 0)
<div class="alert alert-danger">
<div class="box box-solid box-danger">
  <ul>
    @foreach ($errors->all() as $error)
      <li style="color:#DD4B39">{{ $error }}</li>
    @endforeach
  </ul>
</div>
</div>
@endif
