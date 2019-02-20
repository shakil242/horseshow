@extends('admin.layouts.app')

@section('htmlheader_title')
    Log in
@endsection

@section('main-content')
          <div class="row">
            <div class="col-sm-6">
              <h1>Create Master Templates</h1>
            </div>
            <div class="col-sm-6 action-holder">
             <!--  <a href="#" class="btn-action">Upload Like Icon</a>
              <a href="#" class="btn-action">Design App</a> -->
            </div>
          </div>
          <div class="row">
            <div class="col-sm-8">
                {!! Breadcrumbs::render('admin-create-m-template') !!}
            </div>
          </div>
                    <!--- create template panel -->
          <div class="gray-box">
            <div class="create-template">
            {!! Form::open(['url'=>'admin/template/store','method'=>'post']) !!}
                  @include('admin.mastertemplates._form', ['targets'=>$collection])
            </div>
          </div>
          <input type="hidden" name="actionafterstore" id="afterstore" value="">
          <div class="buttons-holder">
           {!! Form::submit("Save And Close" , ['class' =>"btn btn-lg btn-primary",'id'=>'storeandlist']) !!} 
           {!! Form::submit("Save" , ['class' =>"btn btn-lg btn-success",'id'=>'storeonly']) !!} 
            <a  href="{{URL::to('/admin')}}" class="btn btn-lg btn-default" value="Cancel">Cancel</a>
          </div>
           {!! Form::close() !!}
@endsection
@section('footer-scripts')
    @include('admin.layouts.partials.datatable')
@endsection