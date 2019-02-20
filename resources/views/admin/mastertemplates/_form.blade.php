<div class="row">
      <div class="col-sm-4">
        <div class="form-group">
          {!! Form::text('name', null , ['class' =>"form-control",'placeholder'=>"Master Template Name *",'required'=>'required']) !!} 
        </div>
      </div>
      <div class="col-sm-4">
        <div class="row">
          <div class="col-sm-6">
            <div class="form-group">
              {!! Form::text('royalty', null , ['class' =>"form-control",'placeholder'=>"Royalty *",'required'=>'required']) !!} 
            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group having-info">
              {!! Form::text('value', null , ['class' =>"form-control",'placeholder'=>"Value",'required'=>'required']) !!} 
              <span>1 Point = 10 $</span>
            </div>
          </div>
        </div>

      </div>
      <div class="col-sm-4">
        <div class="form-group">

        @if(!isset($template->category))
            {{ Form::select('template_category', $tempCatCollection, Request::old('tempCatCollection') ? Request::old('tempCatCollection') : null, array('multiple' => true,'class'=>'selectpicker show-tick form-control','data-live-search'=>'true',"data-max-options"=>'1')) }}
        @else
            <select name="template_category" multiple data-max-options="1" class="selectpicker show-tick form-control" data-live-search="true">
              @foreach($tempCatCollection as $id => $option)
                <option value="{{ $id }}" {{ ($id== $template->category)? 'selected':'' }} > {{ $option }}</option>
              @endforeach
            </select>
        @endif
        </div>
      </div>

    </div>
    <div class="row">
      <div class="col-sm-3">
        <div class="form-group">
        @if(!isset($associates))
            {{ Form::select('associated[]', $targets, Request::old('targets') ? Request::old('targets') : null, array('multiple' => true,'class'=>'selectpicker show-tick form-control','data-live-search'=>'true')) }}
        @else
            <select id="basic" name="associated[]" multiple class="selectpicker show-tick form-control" data-live-search="true">
              @foreach($targets as $id => $option)
                <option value="{{ $id }}" {{ (in_array($id,$associates)) ? 'selected':'' }}> {{ $option }}</option>
              @endforeach
            </select>
        @endif
        </div>
      </div>
        <div class="col-sm-3">
            <div class="form-group" style="margin-top: 8px;">
                <label style=" text-transform: capitalize">Invoice to Events</label>

              @if(isset($template) && $template->invoice_to_event==1)
                {!! Form::checkbox('invoice_to_event', 1 ,true, ['class' =>"form-control"]) !!}
              @else
                    {!! Form::checkbox('invoice_to_event', 1 , ['class' =>"form-control"]) !!}
              @endif
            </div>
            </div>
        <div class="col-sm-3">

        <div class="form-group" style="margin-top: 8px;">
                <label style=" text-transform: capitalize">Invoice to Assets</label>
            @if(isset($template) && $template->invoice_to_asset==1)
                {!! Form::checkbox('invoice_to_asset', 1 ,true, ['class' =>"form-control"]) !!}
            @else
                {!! Form::checkbox('invoice_to_asset', 1 , ['class' =>"form-control"]) !!}
            @endif
         </div>

        </div>
        <div class="col-sm-3">

        <div class="form-group" style="margin-top: 8px;">
                <label style=" text-transform: capitalize">Cumulative ranking</label>
            @if(isset($template) && $template->cumulative_ranking ==1)
            
                {!! Form::checkbox('cumulative_ranking', 1 ,true, ['class' =>"form-control"]) !!}
            @else
                {!! Form::checkbox('cumulative_ranking', 1 ,false, ['class' =>"form-control"]) !!}
            @endif
         </div>

        </div>

        <div class="col-sm-3">

        <div class="form-group" style="margin-top: 8px;">
                <label style=" text-transform: capitalize">Blog App Wise</label>
            @if(isset($template) && $template->blog_type ==1)
                {!! Form::checkbox('blog_type', 1 ,true, ['class' =>"form-control"]) !!}
            @else
                {!! Form::checkbox('blog_type', 1 ,false, ['class' =>"form-control"]) !!}
            @endif
         </div>

        </div>

    </div>