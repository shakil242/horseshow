@extends('layouts.equetica2')

@section('custom-htmlheader')
    @include('layouts.partials.form-header')
@endsection

@section('main-content')
    <!-- ================= CONTENT AREA ================== -->
        <div class="container-fluid">

        @php
            $title = "Trainer Registration";
            $added_subtitle = Breadcrumbs::render('shows-register',nxb_encode($show_id));
        @endphp
        @include('layouts.partials.pagemenu',['added_title'=>$title,'added_subtitle'=>$added_subtitle,'remove_search'=>1])

        <!-- Content Panel -->
            <div class="white-board">
                <div class="row">
                    <div class="col">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <!--<li class="nav-item">
                                <a class="nav-link active" id="division-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Orders</a>
                            </li>
                             <li class="nav-item">
                                <a class="nav-link" id="showclasses-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Show Classes</a>
                            </li> -->
                        </ul>
                    </div>

                </div>
                <div class="row">
                    <div class="col-md-12">

                  @include('admin.layouts.errors')

          <div class="participants-responses">
                @if(count($FormTemplate)>0)
                {!! Form::open(['url'=>'trainer/store','method'=>'post','files'=>true,'class'=>'form-horizontal dropzone targetvalue']) !!}
                <input type="hidden" name="template_id" value="{{$FormTemplate->template_id}}"> 
                <input type="hidden" name="form_id" value="{{$FormTemplate->id}}">
                <input type="hidden" name="show_id" value="{{$show_id}}">
                  @include('MasterTemplate.form.template')
                <div class="row">
                    <div class="col-md-12">
                  <div class="col-sm-1 mr-20 pull-right">{!! Form::submit("Next Step" , ['class' =>"btn btn-primary btn-close"]) !!}</div>
                  <div class="col-sm-1 pull-right"><a class="btn btn-primary" href="{{URL::to('shows') }}/dashboard">Cancel</a></div>
                </div>
                </div>
                {!! Form::close() !!}
                @else
                  <div class="row">
                      <div class="col-sm-12"> No registration form added for this show uptill now!</div>  
                  </div>
                  
                @endif
          </div>
                    </div>
                </div>
            </div>
        </div>
@endsection

@section('footer-scripts')
    <script src="{{ asset('/js/preview-form-jquery.js') }}"></script>
    <script src="{{ asset('/js/duplicate-form.js') }}"></script>
@endsection
@section('footer-bootstrap-Overridescripts')
    <script src="{{ asset('/adminstyle/js/vender/bootstrap-custom-form2.js') }}"></script>
@endsection
