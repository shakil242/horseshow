@extends('admin.layouts.app')

@section('main-content')
         <div class="row">
            <div class="col-sm-8">
              <h1>Create a Form</h1>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-8">
                {!! Breadcrumbs::render('admin-create-form',$masterid) !!}
            </div>
          </div>
          @include('admin.layouts.errors')
              

            {!! Form::open(['url'=>'admin/forms-managment/store','class'=>'formfields-submit','enctype'=>'multipart/form-data','method'=>'post']) !!}
            <div class="create-form">
                  @include('admin.mastertemplates.forms._form',['targets'=>$modules])
            </div>
            <input type="hidden" name="template_id" value="{{ $masterid }}">
            <div class="create-form-actions">
              <div class="row">
                <div class="col-sm-4">
                  <input type="submit" value="Save" class="btn btn-lg btn-primary" />
                </div>
                <div class="col-sm-4">
                  <a href="{{URL::to('admin/template/edit') }}/{{ $masterid }}" class="btn btn-lg btn-defualt">Cancel</a>
                </div>
              </div>
            </div>
            {!! Form::close() !!}

@endsection

@section('footer-scripts')
    @include('admin.layouts.partials.datatable')
@endsection