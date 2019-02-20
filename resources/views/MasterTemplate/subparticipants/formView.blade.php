@extends('layouts.equetica2')

@section('custom-htmlheader')
    @include('layouts.partials.form-header')
@endsection

@section('main-content')
    @if(Session::has('message'))
        <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
    @endif

    <!-- ================= CONTENT AREA ================== -->
    <div class="main-contents">
        <div class="container-fluid">
            <?php $bread = 0 ?>
            @if(!empty($participant))
                <?php $bread = $participant->id ?>
            @endif
            @php
                $title = GetAssetNamefromId(nxb_decode($asset_id));
                $added_subtitle = Breadcrumbs::render('master-template-form-view', $data = ['template_id' => $template_id,'form_id' => $formid,'asset_id' => $asset_id],$bread,1) ;
            @endphp
            @include('layouts.partials.pagemenu',['added_title'=>$title,'added_subtitle'=>$added_subtitle,'remove_search'=>1])


          @include('admin.layouts.errors')


          <div class="participants-responses mb-20">
              @if(allowedToSubmitForm($participant->participant_id,$formid))
                @if(allowedToSubmitSubPartForm($participant->id,$formid))
                    {!! Form::open(['url'=>'master-template/sub-participants/submit/response/','method'=>'post','files'=>true,'class'=>'form-horizontal dropzone targetvalue']) !!}
                      <input type="hidden" name="template_id" value="{{$template_id}}"> 
                      <input type="hidden" name="form_id" value="{{$formid}}">
                      <input type="hidden" name="participant_id" value="{{$participant->participant_id}}">
                      <input type="hidden" name="subparticipant_id" value="{{$participant->id}}">
                      <input type="hidden" name="draft_id" value="{{$draft_id}}">
                      <input type="hidden" name="asset_id" value="{{$asset_id}}">
                      <input type="hidden" name="invite_asociated_key" value="{{$invite_asociated_key}}">
                      <input type="hidden" name="owner_id" value="{{$participant->user_id}}">

                      @include('MasterTemplate.form.template')
                    <div class="row">
                      <div class="col-sm-2"><input type="submit" formnovalidate="formnovalidate" name="Draft" class="btn btn-success btn-close" value="Draft Form"></div>
                      <div class="col-sm-2">{!! Form::submit("Submit" , ['class' =>"btn btn-primary btn-close"]) !!}</div>
                      @if($participant->allowed_time != null)<div class="col-sm-5">Allowed To submit: <?php if($participant->allowed_time == "unlimited"){ echo $participant->allowed_time; }else{ echo ($participant->allowed_time-$participantRCount); } ?> </div> @endif
                      @if($draft_id != 0)<div class="col-sm-5">Drafted On: {{$draft->updated_at}} </div> @endif
                    </div>
                    {!! Form::close() !!}
                  @else
                  <div class="col-sm-8">
                    <p> You have already Submited the response {{$participant->allowed_time}} time(s). You cannot submit more then this.</p>
                  </div>
                  <div class="row"></div>
                @endif
              @else
                <div class="col-sm-8">
                  <p> You have already Submited the response {{$participant->participant->allowed_time}} time(s). You cannot submit more then this.</p>
                </div>
                <div class="row"></div>
              @endif
              
          </div>

@endsection

@section('footer-scripts')
@if(isset($permission))
  @if(getModulePermission($moduleid,$permission) == READ_ONLY)
  <script src="{{ asset('/js/disable-form.js') }}"></script>
  @endif
@endif
    <script src="{{ asset('/js/preview-form-jquery.js') }}"></script>
@endsection
@section('footer-bootstrap-Overridescripts')
    <script src="{{ asset('/adminstyle/js/vender/bootstrap-custom-form2.js') }}"></script>
@endsection
