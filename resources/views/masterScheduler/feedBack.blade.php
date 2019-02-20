@extends('layouts.equetica2')

@section('custom-htmlheader')
    <!-- Auto populate select multiple-->
@endsection

@section('main-content')

    <!-- ================= CONTENT AREA ================== -->
        <div class="container-fluid">
        @php
            $title = "Feedback Review Form";
            $added_subtitle ='';
        @endphp
        @include('layouts.partials.pagemenu',['added_title'=>$title,'added_subtitle'=>$added_subtitle,'remove_search'=>1])

            <div class="row">
                <div class="info text-center col-md-12 mt-10">
                    @if(Session::has('message'))
                        <div class="alert {{ Session::get('alert-class', 'alert-success') }}" role="alert">
                            {{ Session::get('message') }}
                        </div>
                    @endif
                </div>
            </div>

        <!-- Content Panel -->
            <div class="white-board">

          <div class="row">
              <div class="col-md-12">
                  <div class="row">
                    <div class="col-md-2"><h4><strong>Class Name:</strong></h4></div>
                    <div class="col-md-10"><h4><strong>{{getAssetNameFromId($scheduals->asset_id)}}</strong></h4></div>
                  </div>
                  @if(!empty($horse_id))
                  <div class="row">
                    <div class="col-md-2"><h4><strong>Horse Name:</strong></h4></div>
                    <div class="col-md-10"><h4><strong>{{getAssetNameFromId($horse_id)}}</strong></h4></div>
                  </div>
                  @endif

                  @if(!empty($horse_rider))
                  <div class="row">
                    <div class="col-md-2"><h4><strong>Rider Name:</strong></h4></div>
                    <div class="col-md-10"><h4><strong>{{getAssetNameFromId($horse_rider)}}</strong></h4></div>
                  </div>
                  @endif

              </div>
          </div>


          <div class="participants-responses">
             @if(!empty($answer_fields) && count($answer_fields)>0)
             @if($FormTemplate->form_type==JUDGES_FEEDBACK && $show_type=='Dressage')
                      {!! Form::open(['url'=>'master-template/schedular/submit/feedBack/','method'=>'post','files'=>true,'class'=>'form-horizontal dropzone targetvalue']) !!}
                      <input type="hidden" name="template_id" value="{{$scheduals->template_id}}">
                      <input type="hidden" name="form_id" value="{{$FormTemplate->id}}">
                      <input type="hidden" name="asset_id" value="{{$scheduals->asset_id}}">
                      <input type="hidden" name="invitee_id" value="{{$invitee_id}}">
                      <input type="hidden" name="user_id" value="{{$scheduals->user_id}}">
                      <input type="hidden" name="schedule_id" value="{{$scheduals->id}}">
                      @if(!empty($show_id))
                          <input type="hidden" name="show_id" value="{{$show_id}}">
                      @endif
                      <input type="hidden" name="spectator_id" value="{{$spectatorId}}">
                      @if(!empty($horse_id))
                          <input type="hidden" name="horse_id" value="{{$horse_id}}">
                      @endif
                     @php
                         $answer_fields=array();
                     @endphp
                      @include('MasterTemplate.form.template')
                      {!! Form::submit("Save" , ['class' =>"btn btn-success btn-close"]) !!}
                      {!! Form::close() !!}
                @else
              @include('MasterTemplate.form.template')
              <script src="{{ asset('/js/disable-form.js') }}"></script>
                @endif
            @else
              {!! Form::open(['url'=>'master-template/schedular/submit/feedBack/','method'=>'post','files'=>true,'class'=>'form-horizontal dropzone targetvalue']) !!}

                <input type="hidden" name="template_id" value="{{$scheduals->template_id}}">
                <input type="hidden" name="form_id" value="{{$FormTemplate->id}}">
                <input type="hidden" name="asset_id" value="{{$scheduals->asset_id}}">
                <input type="hidden" name="invitee_id" value="{{$invitee_id}}">
                <input type="hidden" name="user_id" value="{{$scheduals->user_id}}">
                <input type="hidden" name="schedule_id" value="{{$scheduals->id}}">
                  @if(!empty($show_id))
                  <input type="hidden" name="show_id" value="{{$show_id}}">
              @endif
                <input type="hidden" name="spectator_id" value="{{$spectatorId}}">
                  @if(!empty($horse_id))
                  <input type="hidden" name="horse_id" value="{{$horse_id}}">
                  @endif

              @include('MasterTemplate.form.template')
                {!! Form::submit("Save" , ['class' =>"btn btn-success btn-close"]) !!}
              {!! Form::close() !!}
              @endif
          </div>
            </div>
        </div>
@endsection

@section('footer-scripts')
    <script src="{{ asset('/js/preview-form-jquery.js') }}"></script>
@if(isset($permission))
  @if(getModulePermission($moduleid,$permission) == READ_ONLY || count($answer_fields)>0)
  <script src="{{ asset('/js/disable-form.js') }}"></script>
  @endif
@endif
@endsection
@section('footer-bootstrap-Overridescripts')
    <script src="{{ asset('/adminstyle/js/vender/bootstrap-custom-form2.js') }}"></script>
@endsection
