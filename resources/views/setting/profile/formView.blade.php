@extends('layouts.equetica2')

@section('custom-htmlheader')
    @include('layouts.partials.form-header')
@endsection

@section('main-content')
    <!-- ================= CONTENT AREA ================== -->
        <div class="container-fluid">
        @php
            $title = "Form Preview";
            $added_subtitle = Breadcrumbs::render('setting-form-view');
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

          @include('admin.layouts.errors')

          <div class="participants-responses">
                {!! Form::open(['url'=>'','method'=>'post','files'=>true,'class'=>'form-horizontal dropzone targetvalue']) !!}
                  @include('MasterTemplate.form.template')
                {!! Form::close() !!}
              
          </div>
            </div></div>

@endsection

@section('footer-scripts')
    <script src="{{ asset('/js/preview-form-jquery.js') }}"></script>
    <script src="{{ asset('/js/disable-form.js') }}"></script>
@endsection
@section('footer-bootstrap-Overridescripts')
    <script src="{{ asset('/adminstyle/js/vender/bootstrap-custom-form2.js') }}"></script>
@endsection
