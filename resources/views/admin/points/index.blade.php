@extends('admin.layouts.app')

@section('htmlheader_title')
    Log in
@endsection

@section('main-content')
        
        <div class="row">
            <div class="col-sm-8">
              <h1>Points System</h1>
            </div>
            <div class="col-sm-4 action-holder">
              <form action="#">
                <div class="search-form">
                  <input type="text" placeholder="Search By Name" id="myInputTextField"/>
                  <button type="submit"><i class="fa fa-search" aria-hidden="true"></i></button>
                </div>
              </form>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-8">
                {!! Breadcrumbs::render('points-dashboard') !!}
            </div>
          </div>
          <div class="row">
            <div class="info">
                @if(Session::has('message'))
                  <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                @endif
            </div>
          </div>

             <div class="row">
              <div class="col-sm-8">
                     <div class="col-md-4 col-sm-6 Smodule">
                            <a href="{{url('/admin/points-system/show')}}">
                              <img src="{{asset('adminstyle/images/logo-badge.png') }}" width="" height="" alt="Logo Image" />
                              <p class="moduleName">
                                Add Show Type
                              </p>
                            </a>
                     </div>

                      <div class="col-md-4 col-sm-6 Smodule">
                            <a href="{{url('/admin/points-system/class')}}">
                              <img src="{{asset('adminstyle/images/logo-badge.png') }}" width="" height="" alt="Logo Image" />
                              <p class="moduleName">
                                Add Class Type
                              </p>
                            </a>
                     </div>
                </div>
            </div>


        
@endsection
@section('footer-scripts')
    @include('admin.layouts.partials.datatable')
@endsection