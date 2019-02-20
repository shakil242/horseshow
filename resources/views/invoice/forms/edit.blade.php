@extends('admin.layouts.app')

@section('htmlheader_title')
    Log in
@endsection

@section('main-content')
          <div class="row">
            <div class="col-sm-8">
              <h1>Create a Form</h1>
            </div>
          </div>
         @include('admin.layouts.errors')
                    <!--- create template panel -->
           <div class="row">
            <div class="info">
                @if(Session::has('message'))
                <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                @endif
            </div>
          </div>
          <div class="row">
            <div class="col-sm-8">
                {!! Breadcrumbs::render('admin-edit-form',$masterid) !!}
            </div>
          </div>

             {!! Form::model($form , ['method'=>'PATCH','enctype'=>'multipart/form-data', 'class'=>'formfields-submit', 'action'=>['admin\FormController@update', $form->id]]) !!}
                <div class="create-form">
                  @include('admin.mastertemplates.forms._form',['targets'=>$modules])
                <div class="create-form-fields-dynamic">
                  <!-- Form creator Dynamic Area-->
                  @if(is_array($pre_fields))
                    @foreach ($pre_fields as $key => $field)
                      @include('admin.mastertemplates.forms._fields')
                    @endforeach
                  @endif

                  <!-- End -->
                </div>
                <p class="add-field">
                  <input type="button" value="Add Field" onclick="" class="btn btn-lg btn-defualt btn-add-filed addfields-form" />
                </p>

                </div>
            <input type="hidden" name="template_id" value="{{ $masterid }}">

            <div class="create-form-actions">
              <div class="row">
                <div class="col-sm-3">
                  <input type="submit" value="Update" name="update" class="btn btn-lg btn-primary" />
                </div>
                <div class="col-sm-4">
                 <input type="submit" value="Update & Preview" name="updatepreview" class="btn btn-lg btn-success" />
                 <!--  <a href="{{URL::to('admin/template') }}/{{ nxb_encode($form->id) }}/preview" class="btn btn-lg btn-success">Preview</a> -->
                </div>
                 <div class="col-sm-3">
                  <a href="{{URL::to('admin/template') }}/{{ nxb_encode($form->id) }}/make-a-copy" class="btn btn-lg btn-info">Make a Copy</a>
                </div>
                <div class="col-sm-2">
                  <a href="{{URL::to('admin/template/edit') }}/{{ $masterid }}" class="btn btn-lg btn-defualt">Cancel</a>
                </div>
              </div>
            </div>
            {!! Form::close() !!}


@endsection
@section('footer-scripts')
<script src="{{ asset('/js/jquery-ui-1.9.2.custom.min.js') }}"></script>
<script src="{{ asset('/adminstyle/js/forms.creation.equetica.js') }}"></script>
<script src="{{ asset('/js/custom-form-validation.js') }}"></script>
    @include('admin.layouts.partials.datatable')
@endsection