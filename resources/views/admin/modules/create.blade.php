@extends('admin.layouts.app')

@section('htmlheader_title')
    Log in
@endsection

@section('main-content')
          <div class="row">
            <div class="col-sm-6">
              <h1>Create A Module</h1>
            </div>
          </div>
           <div class="row">
            <div class="col-sm-8">
                {!! Breadcrumbs::render('admin-create-module',$masterid) !!}
            </div>
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
                    <!--- create template panel -->
          <div class="gray-box">
            <div class="create-template create-module">
            {!! Form::open(['url'=>'admin/modules-managment/store','enctype'=>'multipart/form-data','method'=>'post']) !!}
                  @include('admin.modules._form', ['targets'=>$collection,'modules'=>$feedBack,'btn_title'=>'Create'])
            </div>
          </div>
           {!! Form::close() !!}


@endsection
@section('footer-scripts')
<!-- <script src="{{ asset('/adminstyle/js/vender/bootstrap-custom-form.js') }}"></script>
  -->  
    @include('admin.layouts.partials.datatable')
@endsection