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
  @if(isset($form->accessable_to) && ($form->form_type == FEEDBACK ||$form->form_type == PROFILE_ASSETS ))
    <div class="form-group">
    <label>
      <input type="radio" name="accessable_to" class="app-owner-radio" value="1" {{ $form->accessable_to == 1 ? "checked='checked'" : "" }}>
      For App owner
    </label>
    <label>
      <input type="radio" name="accessable_to" class="app-owner-radio" value="2" {{ $form->accessable_to == 2 ? "checked='checked'" : "" }} >
      @if($form->form_type == PROFILE_ASSETS)
       For Invited Pariticipants
      @elseif($form->form_type == FEEDBACK)
        For Spectators
      @else
        For Invited Pariticipant
      @endif
    </label>
  </div>
  @endif
</div>
<div class="col-sm-6 form-type-feedback-t">
  @if((isset($form->feedback_type) && $form->accessable_to==1 ) && ($form->form_type == FEEDBACK ||$form->form_type == PROFILE_ASSETS))
    <div class="form-group">
    <label>
      <input type="radio" name="feedback_type" value="1" {{ $form->feedback_type == 1 ? "checked='checked'" : "" }}>
      Required
    </label>
    {{--<label>--}}
      {{--<input type="radio" name="feedback_type" value="2" {{ $form->feedback_type == 2 ? "checked='checked'" : "" }}>--}}
      {{--Dressage feedback--}}
    {{--</label>--}}
    {{--<label>--}}
      {{--<input type="radio" name="feedback_type" value="3" {{ $form->feedback_type == 3 ? "checked='checked'" : "" }}>--}}
      {{--Champions feedback--}}
    {{--</label>--}}
  </div>
  @endif
</div>

<div class="col-sm-12">
  <div class="row">
    <div class="col-sm-6 schedulers_checkbox">
      <label class="label-scheduler">{!! Form::checkbox('scheduler', '1') !!} Scheduler</label>
    </div>
  </div>
</div>
</div>
<script src="{{ asset('/adminstyle/js/profile.js') }}"></script>
