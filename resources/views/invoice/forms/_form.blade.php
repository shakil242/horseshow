<div class="row">
<div class="col-sm-6">
  <div class="form-group">
    <label>Form Name *</label>
     {!! Form::text('name', null , ['class' =>"form-control",'required'=>"required",'placeholder'=>"Form Name"]) !!}
  </div>
  <div class="form-group">
    <label>Form Type *</label>
      {{ Form::select('form_type', [0 => 'Please Select']+$FormTypes, Request::old('FormTypes') ? Request::old('FormTypes') : null,['class'=>'formtype' , 'required' =>"required"]) }}
  </div>
</div>
<div class="col-sm-6">
  <div class="form-group linkto">
    <label>Link To *</label>
      {{ Form::select('linkto', [0 => 'Please Select']+$targets, Request::old('targets') ? Request::old('targets') : null) }}
  </div>
  <div class="form-group invoice">
    <label>Invoice</label>
      {{ Form::select('invoice', [0 => 'Please Select']+$invoice, Request::old('invoice') ? Request::old('invoice') : null) }}
  </div>
</div>
<div class="col-sm-6 accessable">
  @if(isset($form->accessable_to))
    <div class="form-group">
    <label>
      <input type="radio" name="accessable_to" value="1" {{ $form->accessable_to == 1 ? "checked='checked'" : "" }} required>
      For App owner
    </label>
    <label>
      <input type="radio" name="accessable_to" value="2" {{ $form->accessable_to == 2 ? "checked='checked'" : "" }} >
      For Invited Pariticipants
    </label>
  </div>
  @endif
</div>
<div class="col-sm-12">
  <div class="row">
    <div class="col-sm-6">
      <label class="label-scheduler">{!! Form::checkbox('scheduler', '1') !!} Scheduler</label>
    </div>
  </div>
</div>
</div>
<script src="{{ asset('/adminstyle/js/profile.js') }}"></script>
