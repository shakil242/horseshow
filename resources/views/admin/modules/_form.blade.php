  <div class="row">
    <div class="col-sm-9">
    <div class="row">    
      <div class="col-sm-6">
        <div class="form-group">
          {!! Form::text('name', null , ['class' =>"form-control",'placeholder'=>"Module Name *",'required'=>'required']) !!} 
        </div>
      </div>
      <div class="col-sm-6">
        <div class="form-group">
          <lable>{!! Form::checkbox('general', '1') !!} General</lable>
        </div>
      </div>
  </div>
    <div class="row">
      <div class="col-sm-6">
        <div class="form-group">
            {{ Form::select('linkto',  [0 => 'Please Select']+$targets, Request::old('targets') ? Request::old('targets') : null) }}
        </div>
        </div>

     <?php $templateType = GetTemplateType($masterid); ?>

      @if($templateType!=FACILTY)
      <div class="col-sm-6">
        <div class="form-group">
        @if(!isset($selectedFeedback))
            {{ Form::select('feedback_form_ids[]', $modules, Request::old('modules') ? Request::old('modules') : null, array('multiple' => true,'class'=>'selectpicker show-tick form-control','data-live-search'=>'true')) }}
        @else
            <select id="basic" name="feedback_form_ids[]" multiple class="selectpicker show-tick form-control" data-live-search="true">
              @foreach($modules as $id => $option)
                <option value="{{ $id }}" {{ (in_array($id,$selectedFeedback)) ? 'selected':'' }}> {{ $option }}</option>
              @endforeach
            </select>
        @endif
        </div>
      </div>
      @endif

         <input type="hidden" name="template_id" value="{{ $masterid }}">
      </div>

      <div class="row">

        <div class="col-sm-12">
          <div class="form-group logo-upload">
            {!! Form::file('logo') !!}
          </div>
          <div class="form-group">
            <?php
            if (isset($template->logo)) { ?>
            <div style="    border: 1px solid;float: left;margin: 3px;padding: 5px;">
              <img src="{{ getImageS3($template->logo) }}" height="50" width="50" />
            </div>
            <?php } ?>
          </div>
        </div>


      </div>


</div>
  <div class="col-sm-3 modules-actions">
        {!! Form::submit($btn_title , ['class' =>"btn btn-lg btn-primary",'id'=>'storeonly']) !!} 
        <a  href="{{URL::to('admin/template/edit') }}/{{ $masterid }}" class="btn btn-lg btn-default" value="Cancel">Cancel</a>
    </div>
  </div>

