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
        @php 
          $title = "Form";
          $added_subtitle = Breadcrumbs::render('master-template-form-view', $dataBreadcrum);
          $remove_search = 1;
        @endphp
        @include('layouts.partials.pagemenu',['added_title'=>$title,'added_subtitle'=>$added_subtitle,'remove_search'=>1])

        <!-- Content Panel -->
        <div class="white-board">  
          <div class="row">
                <!-- <div class="col-md-12">
                    <div class="back"><a onclick="history.go(-1);" class="back-to-all-modules" href="#"><span class="glyphicon glyphicon-circle-arrow-left"></span> Back</a></div>
                </div> -->
            </div>
            
            <div class="row">
                    <div class="col-md-12">
                            
                        <!-- TAB CONTENT -->
                        <div class="tab-content" id="myTabContent">
                            <!-- Tab Data Divisions -->
                            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="division-tab">
                              @include('admin.layouts.errors')
                                            <div class="participants-responses">
                                                {!! Form::open(['url'=>'participant/submit/response/','method'=>'post','files'=>true,'class'=>'form-horizontal dropzone targetvalue']) !!}
                                                  <input type="hidden" name="template_id" value="{{$template_id}}">
                                                  <input type="hidden" name="form_id" value="{{$formid}}">
                                                  <input type="hidden" name="participant_id" value="">
                                                  <input type="hidden" name="draft_id" value="{{$draft_id}}">
                                                  <input type="hidden" name="app_owner" value="1">
                                                  @if(isset($assets))
                                                  <div class="row">
                                                    <div class="col-sm-6">
                                                      <select multiple name="asset[]" class="selectpicker show-tick form-control" multiple data-size="8" data-selected-text-format="count>6"  id="allAssets" data-live-search="true" required="required">
                                                          @if($assets->count())
                                                              @foreach($assets as $id => $option)
                                                              <option value="{{$option->id}}" @if(old("asset") != null) {{ (in_array($option->id, old("asset")) ? "selected":"") }} @endif> {{ GetAssetName($option) }}</option>
                                                              @endforeach
                                                          @endif
                                                      </select>
                                                    </div>
                                                  </div>
                                                  <br>
                                                  @endif
                                                  @include('MasterTemplate.form.template')
                                                 <div class="row col-sm-6">
                                                  <div class="col-sm-3">{!! Form::submit("Save" , ['class' =>"btn btn-primary btn-close clicked-submit"]) !!}</div>
                                                  <div class="col-sm-3"><input type="submit" formnovalidate="formnovalidate" name="Draft" class="btn btn-success btn-close" value="Draft Form"></div>    
                                                  <div class="col-sm-3"><input type="button" class="btn btn-success btn-duplicate-form" value="Duplicate"></div>
                                                  </div>
                                                  {!! Form::close() !!}
                                            </div>
                            </div>
                            
                        </div>
                        <!-- ./ TAB CONTENT -->
                    </div>
                </div>
        <!-- ./ Content Panel -->  
        </div>
    </div>
</div>

<!-- ================= ./ CONTENT AREA ================ -->

@endsection

@section('footer-scripts')
    <script src="{{ asset('/js/duplicate-form.js') }}"></script>

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
