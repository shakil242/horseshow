@extends('layouts.equetica2')

@section('main-content')
@if(Session::has('message'))
<p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
@endif
        <!-- ================= CONTENT AREA ================== -->
<div class="main-contents">
    <div class="container-fluid">

        @php 
          $title = GetTemplateName($template_id,$user_id);
          $added_subtitle = Breadcrumbs::render($breadcrumbsRoute, $dataBreadcrum );
        @endphp
        @include('layouts.partials.pagemenu',['added_title'=>$title,'added_subtitle'=>$added_subtitle])

          <!-- <div class="col-sm-4">
              {!! Form::open(['url'=>'master-template/search/','method'=>'post','class'=>'']) !!}
                <div class="search-form">
                  <input name="keywords" class="typeahead form-control" type="text" placeholder="Search By Name" />
                  <button type="submit"><i class="fa fa-search" aria-hidden="true"></i></button>
                  <input type="hidden" name="template_id" value="{{nxb_encode($template_id)}}">
                </div>
              {!! Form::close() !!}
            </div> -->
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
                            
                        <!-- TAB CONTENT -->
                        <div class="tab-content" id="myTabContent">
                            <!-- Tab Data Divisions -->
                            <div id="all-modules" class="box-shadow bg-white p-4 mt-30 mb-30">
                            <div class="tab-pane fade show active row" id="home" role="tabpanel" aria-labelledby="division-tab">
                              @include('admin.layouts.errors')

                                @if(sizeof($collection)>0)
              
                                  <div class="col-sm-8">
                                    <div class="row searchables">


                                      @foreach($collection as $module)

                                          {{--this below code is for image and title from app owner moduels--}}

                                        <?php
                                            if(!is_null($module['module_logo']))
                                            {
                                            $moduleName = $module['module_logo']['name'];

                                            if(!is_null($module['module_logo']['logo']))
                                            {
                                               // $imageUrl = getImageS3($module['module_logo']['logo']);
                                                $actualImageUrl = getImageS3($module['module_logo']['orignal_logo']);
                                            ?>
                                        <input type="hidden" name="actualImage" id="actual_{{$module['id']}}" value="{{$actualImageUrl}}">
                                        <?php
                                            } else
                                            {


                                            if(getImageS3($module['logo'])!='')
                                            {
                                            $moduleName = $module['name'];
                                            $actualImageUrl = getImageS3($module['module_logo']['orignal_logo']);

                                            ?>
                                            <input type="hidden" name="actualImage" id="actual_{{$module['id']}}" value="{{$actualImageUrl}}">
                                            <?php
                                            }else
                                            {
                                           $actualImageUrl =  asset('adminstyle/images/logo-badge.png');
                                            ?>

                                            <input type="hidden" name="actualImage" id="actual_{{$module['id']}}" value="{{$actualImageUrl}}">


                                            <?php
                                            }
                                            }
                                            }
                                            else
                                             {

                                             $moduleName = $module['name'];

                                            if(!is_null($module['module_logo']['logo']))
                                                {
                                             $actualImageUrl = getImageS3($module['module_logo']['orignal_logo']);
                                            ?>
                                            <input type="hidden" name="actualImage" id="actual_{{$module['id']}}" value="{{$actualImageUrl}}">
                                             <?php
                                            }else
                                                    {
                                             $actualImageUrl =  'http://equetica.vteamslabs.com/adminstyle/images/logo-badge.png';
                                                    ?>

                                            <input type="hidden" name="actualImage" id="actual_{{$module['id']}}" value="{{$actualImageUrl}}">


                                        <?php

                                                    }
                                             }

                                            ?>

                                            {{--this above code is for image and title from app owner moduels--}}
                                        @if(isset($permission))
                                            @if(getModulePermission($module['id'],$permission) != 0)
                                            <div class="col-md-3 col-sm-6 Smodule">
                                              @if(!isset($participant))
                                                <a onclick="getLogomodel({{$module['id']}},'{{$moduleName}}')"  href="javascript:void(0);" style="position: absolute; top: 5px;  right: 25px;">Edit</a>
                                               @endif
                                                <!-- if the view modules are for sub participants-->
                                              @if(isset($subparticipants))
                                              <a href="{{URL::to('master-template') }}/{{nxb_encode($module['template_id'])}}/{{nxb_encode($participant->id)}}/{{nxb_encode($module['id'])}}/subparticipant-sub-modules/{{$asset_id}}/{{nxb_encode($app_id)}}/{{$invite_asociated_key}}" class="module-holder">
                                              @else
                                              <a href="{{URL::to('master-template') }}/{{nxb_encode($module['template_id'])}}/{{nxb_encode($participant->id)}}/{{nxb_encode($module['id'])}}/sub-modules/{{$asset_id}}/{{nxb_encode($app_id)}}/{{$invite_asociated_key}}" class="module-holder">
                                              @endif
                                                      <img src="{{ $actualImageUrl }}"  alt="Logo Image" />
                                                      <p class="moduleName">{{$moduleName}}<span class="hidden">{{ strtolower($moduleName)}}</span></p>
                                              </a>
                                            </div>
                                             @endif
                                        @else
                                              @if(!isset($participant))
                                                <div class="col-md-3 col-sm-6 Smodule" style="position: relative;">
                                                @if(!isset($participant->id))
                                                <a onclick="getLogomodel({{$module['id']}},'{{$moduleName}}')"   href="javascript:void(0);" style="position: absolute; top: 5px;  right: 25px;">Edit</a>
                                                @endif
                                                  <a href="{{URL::to('master-template') }}/{{nxb_encode($module['template_id'])}}/{{nxb_encode($module['id'])}}/sub-modules/{{nxb_encode($app_id)}}" class="module-holder">
                                                      <img src="{{ $actualImageUrl }}"  alt="Logo Image "  />
                                                      <p class="moduleName">{{$moduleName}}<span class="hidden">{{ strtolower($moduleName)}}</span></p>
                                                  </a>
                                              </div>
                                            @endif
                                        @endif

                                      @endforeach
                                    
                                    </div>
                                  </div>
                                  @else
                                   <div class="col-sm-8">
                                      <div>
                                        <p> No modules added to this master template yet</p>
                                      </div>
                                    </div>
                                  @endif
                                  <!-- General Modules -->
                                  @if(count($generalCollection) && !isset($permission))
                                  <aside class="col-sm-4" id="modules-sidebar">
                                    <h4>General Modules</h4>
                                    <nav class="general-modules">
                                      <ul>
                                      @foreach($generalCollection as $module)
                                        <li>
                                          <a href="{{URL::to('master-template') }}/{{nxb_encode($module['template_id'])}}/{{nxb_encode($module['id'])}}/sub-modules/{{nxb_encode($app_id)}}">

                                              @if(getImageS3($module['logo'])!='')
                                                  <img src="{{ getImageS3($module['logo']) }}" width="120" height="70" alt="Logo Image" />
                                              @else
                                                  <img src="{{asset('adminstyle/images/logo-badge.png') }}" width="" height="" alt="Logo Image" />
                                              @endif


                                            <div>
                                              {{$module['name']}}
                                            </div>
                                          </a>
                                        </li>
                                      @endforeach
                                      </ul>
                                    </nav>
                                  </aside>
                                  @endif

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



              <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                  <div class="modal-dialog" role="document">
                      {!! Form::open(['url'=>'master-template/updateModuleLogo','method'=>'post', 'id'=>'updateModuleLogo','enctype'=>"multipart/form-data"]) !!}
                        <input type="hidden" name="template_id" value="{{$template_id}}">
                      <input type="hidden" name="app_id" value="{{$app_id}}">

                      <input type="hidden" id="module_id" name="module_id" value="">
                      <input type="hidden" name="userCroppedImage" id="userCroppedImage">

                      <div class="modal-content">
                          <div class="modal-header">
                              <h2 class="modal-title" id="myModalLabel">Update Module Information</h2>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                          </div>
                          <div class="modal-body">


                              <div class="row">
                                      <div class="col-sm-12">
                                        <label>Update Logo</label>
                                          <input type="file"  id="upload" name="userOrignalImage" class="">
                                        
                                        <div id="upload-demo" style="width:350px; display: none">
                                            <div id="ajax-loading" class="loading-ajax"></div>
                                        </div>
                                      </div>
                                      <div class="col-sm-12">
                                          <label>Title</label>
                                          <input type="text"  name="name" id="title"  class="" placeholder="Title*">
                                      </div>

                                  </div>
                              </div>

                          <div class="modal-footer" style="border: none; ">
                                  <div class="form-group">
                                  <button type="submit" class="btn btn-primary">Update</button>
                              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                              </div>
                          </div>
                          </div>

                      </div>
                      {!! Form::close() !!}

                  </div>



@endsection
@section('footer-scripts')


    <script src="{{ asset('/js/croppie.js') }}"></script>
    <link href="{{ asset('/css/croppie.css') }}" rel="stylesheet">
  <script src="{{ asset('/js/nxb-search-rapidly.js') }}"></script>
    <script src="{{ asset('/js/modules.js') }}"></script>
<style>

    .croppie-container {
        padding: 0 0 30px;
    }

</style>


@endsection