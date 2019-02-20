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
          $added_subtitle = "";
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
                                 @if($FormTemplate !== null)
                                    <div class="participants-responses">
                                        {{--{!! Form::model($Asset , ['method'=>'PATCH','enctype'=>'multipart/form-data', 'class'=>'formfields-submit', 'action'=>['AssetController@update', $Asset->id]]) !!}--}}

                                        @if(count($assets) > 0)
                                            <div class="row form-group" style="line-height: 30px;">
                                                <div class="col-sm-2" style="padding-right: 0px"> <label>Select Primary Asset</label></div>
                                                <div class="col-sm-6">
                                                    <select multiple name="asset[]" class="selectpicker form-control" multiple data-size="8" autocomplete="off" data-selected-text-format="count>6"  id="allAssets" data-live-search="true">
                                                        @foreach($assets as $option)
                                                            <option value="{{$option->id}}" {{ (collect(old('asset',$parentAsset))->contains($option->id)) ? 'selected':'' }}
                                                            > {{ GetAssetName($option) }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        @endif


                                        <input type="hidden" name="template_id" value="{{$template_id}}">
                                        <input type="hidden" name="form_id" value="{{$formid}}">
                                        @include('MasterTemplate.form.template')
                                    </div>
                                @else
                                    <div class="col-xs-12">
                                        <div class="row">
                                            {{NO_FORM_MESSAGES}}
                                        </div>
                                    </div>
                                @endif
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

    @if(isset($secret) && $secret==1)
    <script src="{{ asset('/js/form-private-fields.js') }}"></script>
    @endif
    <script src="{{ asset('/js/disable-form.js') }}"></script>
    <script src="{{ asset('/js/preview-form-jquery.js') }}"></script>
@endsection
@section('footer-bootstrap-Overridescripts')
    <script src="{{ asset('/adminstyle/js/vender/bootstrap-custom-form2.js') }}"></script>
@endsection
