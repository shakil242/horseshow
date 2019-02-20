@extends('admin.layouts.app')

@section('main-content')
          <div class="row">
            <div class="col-12">
              <h1>Design Master Template</h1>
            </div>
              @if (count($errors) > 0)
                <div class="box box-solid box-danger">
                  <ul>
                    @foreach ($errors->all() as $error)
                      <li style="color:#DD4B39">{{ $error }}</li>
                    @endforeach
                  </ul>
                </div>
              @endif
          </div>
           <div class="row">
            <div class="col-sm-8">
                {!! Breadcrumbs::render('admin-design-form',$masterid) !!}
            </div>
          </div>
          @if (isset($design_template))
            {!! Form::model($design_template , ['method'=>'post','enctype'=>'multipart/form-data','action'=>['admin\TemplateDesignController@store', $design_template->id]]) !!}
            <input type="hidden" name="design_template_id" value="{{$design_template->id}}"> 
            @else
            {!! Form::open(['url'=>'admin/master-template-design/store','enctype'=>'multipart/form-data','method'=>'post']) !!}
            @endif
            <div class="col-8 customizable-label">
               <label for="cust">
              {{ Form::checkbox('customizable_app_user', '1') }}
              Customizable by APP users</label>
            </div>
                    <!--- create template panel -->
            <div class="row">
                  @include('admin.mastertemplates.design._form')

                  <input type="hidden" name="template_id" value="{{ $masterid }}">
            <div class="desgin-buttons">
                {!! Form::submit('Save' , ['class' =>"btn btn-lg btn-primary",'name'=>'saveOnly','id'=>'storeonly']) !!} 
                {!! Form::submit('Save and Close' , ['class' =>"btn btn-lg btn-primary",'name'=>'saveClose','id'=>'storeonly']) !!} 
                <a  href="{{URL::to('admin/template/edit') }}/{{ $masterid }}" class="btn btn-lg btn-default" value="Cancel">Cancel</a>
            </div>
          </div>
           {!! Form::close() !!}
@endsection

@section('footer-scripts')
    @include('admin.layouts.partials.datatable')
@endsection