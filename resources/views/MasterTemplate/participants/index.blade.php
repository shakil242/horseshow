@extends('layouts.equetica2')

@section('custom-htmlheader')
  <!-- Search populate select multiple-->
 <script src="{{ asset('/js/vender/bootstrap3-typeahead.min.js') }}"></script>
  <!-- END:Search populate select multiple-->
@endsection

@section('main-content')
@if(Session::has('message'))
<p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
@endif
     <!-- ================= CONTENT AREA ================== -->
<div class="main-contents">
    <div class="container-fluid">
        @php 
          $ya_fields = getButtonLabelFromTemplateId($template_id,'ya_fields'); 
          $i_p_fields = getButtonLabelFromTemplateId($template_id,'i_p_fields');
          $title = post_value_or($ya_fields,'invite_participants','Invite Participants');
          $added_subtitle = Breadcrumbs::render('master-template-participants',$template_id);
          $remove_search = 1;
        @endphp
        @include('layouts.partials.pagemenu',['added_title'=>$title,'added_subtitle'=>$added_subtitle])

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
                        <div class="tab-content invite-ppl" id="myTabContent">
                            <!-- Tab Data Divisions -->
                            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="division-tab">
                              @include('admin.layouts.errors')
                               <div class="box-shadow bg-white p-4 mt-10 mb-30">
                                 
                              
                               {!! Form::open(['url'=>'master-template/invite/participant/select/','method'=>'post','files'=> true,'class'=>'form-horizontal dropzone targetvalue']) !!}
                                <input type="hidden" name="template_id" value="{{$template_id}}">
                                <div class="row">
                                <?php $templateType = GetTemplateType($template_id); ?>
                                  @if($templateType==FACILTY)
                                  
                                    <div class="col-sm-12">
                                      <h2>Select Assets</h2>
                                      <br>
                                    </div>
                                 
                                  @endif
                                  <div class="col-sm-7">

                                      @if($templateType==FACILTY)

                                         <div class="row">
                                                <div class="col-sm-4"> <label style="padding-top:5px">{{post_value_or($i_p_fields,'selectAsset','Select Primary Asset')}}:</label> </div>
                                                <div class="col-sm-8">
                                                    <select  name="show_id" required title="Please Select Primary Asset ..." class="form-control" onchange="getSecondarAssets($(this).val())"  id="primaryAsset" data-live-search="true">
                                                        <option data-hidden="true"></option>
                                                        @if($assetPrimary->count())
                                                            @foreach($assetPrimary as $option)
                                                                <option value="{{$option->id}}" @if(old("asset") != null) {{ (in_array($option->id, old("asset")) ? "selected":"") }} @endif> {{ GetAssetNameWithType($option) }}</option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                            </div>
                                              <br>
                                              <div class="row secondaryContainer" style="display: none">
                                                  <div class="col-sm-4"> <label style="padding-top:5px">{{post_value_or($i_p_fields,'selectAsset','Select Secondary Assets')}}:</label> </div>
                                                  <div class="col-sm-8 secondaryAssets"></div>
                                              </div>

                                       @else
                                         <div class="row">
                                     <div class="col-sm-3"> <label style="padding-top:5px">{{post_value_or($i_p_fields,'selectAsset','Select Asset')}}:</label> </div>
                                      <div class="col-sm-9">
                                          <select multiple name="asset[]" class="selectpicker show-tick form-control"
                                                  multiple data-size="8" data-selected-text-format="count>6" title="Please Select Class"  id="allAssets" data-live-search="true">
                                          @if($assets->count())
                                              <option value="All">Select All</option>
                                              @foreach($assets as $option)
                                              <option value="{{$option->id}}" @if(old("asset") != null) {{ (in_array($option->id, old("asset")) ? "selected":"") }} @endif> {{ GetAssetNameWithType($option) }}</option>
                                              @endforeach
                                          @endif
                                      </select>
                                    </div>
                                  </div>
                                       @endif

                                  </div>
                                  <div class="col-sm-5">
                                    <textarea name="description" class="invite-textarea form-control" placeholder="Description">{{old("description")}}</textarea>
                                  </div>
                                </div>
                             <!--    <div class="row mapers">
                                <div class="col-sm-8">
                                  <p class="clearfix">
                                      <strong>Assets Location</strong>
                                    </p>
                                      <div class="input-group">
                                            <span class="input-group-addon">Location:</span>
                                            {{ Form::text('location', isset($model->detail)?$model->detail['location']:'', ['id' => 'search-input',
                                                'class' => 'form-control']) }}
                                            {{ Form::hidden('search_location', isset($model->detail)?$model->detail['location']:'', ['id' => 'search-location',
                                                'class' => 'form-control']) }}

                                            <span class="input-group-addon text-red" onclick="js:initialize();" data-placement="left" data-toggle="tooltip" data-title="Load Map">
                                                <i class="fa fa-fw fa-lg fa-map-marker"></i>
                                            </span>

                                            <span class="input-group-addon hidden">Latitude: </span>
                                            {{ Form::hidden('latitude', isset($model->detail)?$model->detail['latitude']:'', ['maxlength' => 12,
                                                'class' => 'form-control']) }}

                                            <span class="input-group-addon hidden">Longitude: </span>
                                            {{ Form::hidden('longitude', isset($model->detail)?$model->detail['longitude']:'', ['maxlength' => 12,
                                                'class' => 'form-control']) }}
                                            {{ Form::hidden('address', isset($model->detail)?$model->detail['address']:'', ['class'=>'form-control']) }}
                                            {{ Form::hidden('place_id', isset($model->detail)?$model->detail['place_id']:'', ['class'=>'form-control']) }}
                                    </div>
                                      <div id="map_wrapper">

                                        <div id="map_canvas" class="mapping"></div>
                                      </div>
                                </div>

                                </div> -->

                              <div class="participants-holder">
                                <h2>Participants</h2>
                                <br />
                                <div class="invite-participants-table">
                                  <div class="participants-filters row">
                                    <label class="row col-sm-5">
                                      <input type="checkbox" class="select-past-participant" /> 
                                      <span>Select all past participants</span>
                                    </label>
                                    <div class="offset-4 col-sm-3">
                                      <div class="search-field">
                                          <div class="input-group">
                                          <input type="text" class="form-control" placeholder="" id="myInputTextField" aria-label="Username" aria-describedby="basic-addon1">
                                          <div class="input-group-prepend">
                                          <span class="input-group-text" id="basic-addon1"><img src="{{asset('img/icons/icon-search.svg') }}" /></span>
                                          </div>
                                          </div>
                                      </div>
                                    </div>
                                   <!--  <label class="col-sm-4"><input type="checkbox" /> Sort by average cost </label> -->
                                  </div>
                                  <table class="primary-table dataViews table table-line-braker">
                                    <thead class="hidden-xs">
                                      <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Location</th>
                                        <th>Invite</th>
                                      </tr>
                                    </thead>
                                    <tbody>
                                      @if($pastParticipants->count())
                                      @foreach($pastParticipants as $PP)
                                      <tr>
                                        <td>
                                          <storng class="visible-xs">Name</storng>
                                          {{getUserNamefromEmail($PP->email)}}
                                        </td>

                                        <td>
                                          <storng class="visible-xs">email</storng>
                                          {{$PP->email}}
                                        </td>
                                         <td>
                                          <storng class="visible-xs">Location</storng>
                                          {{$PP->location}}

                                        </td>
                                        <td>
                                          <storng class="visible-xs">Invite</storng>
                                          <a href="javascript:void(0)" data-email="{{$PP->email}}" class="btn-invite-parti">Select for Invite</a>
                                        </td>
                                      </tr>
                                      @endforeach
                                      @endif
                                    </tbody>
                                  </table>
                                </div>
                              </div>
                              <div class="new-participants">
                                <h2>Invite New Participants</h2>
                                <div class="row">
                                  <div class="col-sm-7 add-more-participants-fields">
                                  @if(old('emailName'))
                                    <?php $indexer = 0 ?>
                                    @foreach(old('emailName') as $emailNameVal)
                                      <div class="row number-emails mb-20">
                                        <div class="col-sm-5">
                                          <div class="">
                                            <input name="emailName[{{$indexer}}][name]" type="text" placeholder="Name" class="form-control" value="{{$emailNameVal['name']}}"/>
                                          </div>
                                        </div>
                                        <div class="col-sm-5">
                                          <div class="">
                                            <input name="emailName[{{$indexer}}][email]" type="email" placeholder="Email" class="form-control" value="{{$emailNameVal['email']}}"/>
                                          </div>
                                        </div>
                                        <div class='col-sm-2'>
                                          <div class='pull-left'>
                                          <button type='button' class='close remove-this-entry' aria-label='Close'>
                                            <span aria-hidden='true'>&times;</span>
                                          </button>
                                          </div>
                                        </div>
                                      </div>
                                      <?php $indexer = $indexer+1; ?>
                                    @endforeach
                                  @else
                                  <div class="row number-emails mb-20">
                                  <div class="col-sm-5">
                                    <div class="">
                                      <input name="emailName[0][name]" type="text" placeholder="Name" class="form-control" value="{{old('name')}}"/>
                                    </div>
                                  </div>
                                  <div class="col-sm-5">
                                    <div class="">
                                      <input name="emailName[0][email]"  pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,63}$" type="email" placeholder="Email" class="form-control" value="{{old('email')}}"/>
                                    </div>
                                  </div>
                                  </div>
                                  @endif
                                  </div>
                                 <!--  <div class="col-sm-1"> <h1> OR </h1></div> -->
                                  <div class="offset-1 col-sm-4">
                                    <div class="excel-participants">
                                      <div style="padding-left:5px"><h3>Import via Excel</h3></div>
                                      <div class="col-sm-12">
                                          <div class="row">
                                            <input name="import_file" type="file" placeholder="Upload excel" class="" value="{{old('import_file')}}" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"/>
                                          </div>
                                          <div class="row">
                                            <p><small><a href="{{ asset('uploads/excel/sample.xlsx') }}">View</a> sample file for excel file formate. </small></p>
                                          </div>
                                      </div>
                                    </div>
                                  </div>


                                </div>
                              </div>
                              <div class="previous-participants">
                                <div class="row">
                                  <div class="col-sm-12 text-center">
                                    <button type="button" class="btn btn-secondary btn-add-new-user" data-type="plus">
                                        Add More
                                    </button>
                                  </div>
                                </div>
                              </div>

                              <div class="row">
                                    <div class="col-sm-12">
                                      <div class="">
                                        <input type="submit" value="Next" class="btn btn-primary" />
                                      </div>
                                    </div>
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
    @include('layouts.partials.datatable')
    <script src="{{ asset('/js/google-map-script.js') }}"></script>
    <script src="{{ asset('/js/custome/select-deselect-script.js') }}"></script>
    <script src="{{ asset('/js/invite-participant.js') }}"></script>
    <script src="{{ asset('js/cookie.js') }}"></script>

    <script>
        Cookies.set("radioButtonValues", null, { path: '/' });

        function getSecondarAssets(parent_id) {

                var url = '/master-template/participant/secondarAssets/' + parent_id;
                $.ajax({
                    url: url,
                    type: "Get",
                    beforeSend: function (xhr) {

                    },
                    success: function (data) {
                        $(".secondaryContainer").show();
                        $(".secondaryAssets").html(data);
                        $("#allAssets").selectpicker("refresh");
                    }
                });


        }


        
        
        
    </script>
<style>
    .btn-group.bootstrap-select.form-control > .jcf-unselectable.select-selectpicker.select-form-control.select-area
    {
        display: none;
    }
    .btn.dropdown-toggle.btn-default, .btn.dropdown-toggle.bs-placeholder.btn-default {
        padding-left: 10px!important;
    }
</style>
@endsection
