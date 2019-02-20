@extends('layouts.equetica2')

@section('custom-htmlheader')
    @include('layouts.partials.form-header')
@endsection

@section('main-content')
    <!-- ================= CONTENT AREA ================== -->
        <div class="container-fluid">
        @php
            $title = "Register As Trainer";
            $added_subtitle = Breadcrumbs::render('shows-register-trainer',nxb_encode($show_id));
        @endphp
        @include('layouts.partials.pagemenu',['added_title'=>$title,'added_subtitle'=>$added_subtitle,'remove_search'=>1])

        <!-- Content Panel -->
            <div class="white-board">

                <div class="row">
                    <div class="col-md-12">
                        @include('admin.layouts.errors')

                        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="division-tab">

                    <div class="participants-responses">
                @if($FormTemplate!= null)
                  @include('MasterTemplate.form.template')
                  @if (Auth::check())
                    <div class="row">
                    <div class="col-sm-3"><a style="text-align: center" class="btn btn-primary" href="{{URL::to('shows') }}/dashboard">Cancel</a></div>
                    </div>
                  @endif
                {!! Form::close() !!}
                @else
                <div class="row">
                  <div class="col-sm-12">
                      No form added
                  </div>
                </div>
                @endif
          </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection

@section('footer-scripts')
  <script src="{{ asset('/js/disable-form.js') }}"></script>
  <script src="{{ asset('/js/preview-form-jquery.js') }}"></script>
@endsection
@section('footer-bootstrap-Overridescripts')
    <script src="{{ asset('/adminstyle/js/vender/bootstrap-custom-form2.js') }}"></script>
@endsection
