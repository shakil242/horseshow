@extends('layouts.equetica2')

@section('custom-htmlheader')
  <!-- Search populate select multiple-->
 <script src="{{ asset('/js/vender/bootstrap3-typeahead.min.js') }}"></script>
  <!-- END:Search populate select multiple-->
@endsection

@section('main-content')
        <div class="row">
          <div class="col-sm-8">
            <?php $ya_fields = getButtonLabelFromTemplateId($template_id,'ya_fields'); ?>
            <h1>{{post_value_or($ya_fields,'invite_participants','Invite Sub Participants')}}</h1>
          </div>
        </div>
      	<div class="row">
	        <div class="col-sm-12">
		          {!! Breadcrumbs::render('master-template-participants',$template_id) !!}
	    	  </div>
      	</div>
          @include('admin.layouts.errors')

                  <!--- Invite participants -->
          {!! Form::open(['url'=>'master-template/invite/participant/select/','method'=>'post','files'=> true,'class'=>'form-horizontal dropzone targetvalue']) !!}
            <input type="hidden" name="template_id" value="{{$template_id}}">
            <div class="row">
              <div class="col-sm-7">
              <div class="form-group">
                 <div class="col-sm-3"> <label style="padding-top:5px">Select Asset:</label> </div>
                  <div class="col-sm-9">
                      <select multiple name="asset[]" class="selectpicker show-tick form-control" multiple data-size="8" data-selected-text-format="count>6"  id="allAssets" data-live-search="true">
                      @if($assets->count())
                          <option value="All">Select All</option>
                          @foreach($assets as $id => $option)
                          <option value="{{$option->id}}" @if(old("asset") != null) {{ (in_array($option->id, old("asset")) ? "selected":"") }} @endif> {{ GetAssetName($option) }}</option>
                          @endforeach
                      @endif
                  </select>

                      {{--<select id="divRatings" class="selectpicker" multiple data-size="5" data-selected-text-format="count>2">--}}
                          {{--<option value="All" selected="selected">All Ratings</option>--}}
                          {{--<option value="EC">EC (Early Childhood)</option>--}}
                          {{--<option value="E">E (Everyone)</option>--}}
                          {{--<option value="E10+">E10+ (Everyone 10+)</option>--}}
                          {{--<option value="T">T (Teen)</option>--}}
                          {{--<option value="M">M (Mature)</option>--}}
                          {{--<option value="AO">AO (Adults Only)</option>--}}
                      {{--</select>--}}

                </div>
              </div>
                
              </div>
              <div class="col-sm-5">
                <textarea name="description" class="invite-textarea" placeholder="Description">{{old("description")}}</textarea>
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
            <div class="row">
              <div class="col-sm-8">
              <div class="col-sm-8">
                {{--<div class="action-holder">--}}
                    {{--<div class="search-form">--}}
                      {{--<input type="text" id="mySearchTerm" placeholder="Search By Name,Location or Average Cost" />--}}
                      {{--<button type="submit"><i class="fa fa-search" aria-hidden="true"></i></button>--}}
                    {{--</div>--}}
                {{--</div>--}}
              </div>
              </div>
              <div class="col-sm-4">
                <!-- <a href="#" class="btn btn-lg btn-primary btn-show-part">Show All Participants</a>
               --></div>
            </div>
            <div class="invite-participants-table">
              <div class="participants-filters row">
                <label class="col-sm-5"><input type="checkbox" /> Participants already worked </label>
               <!--  <label class="col-sm-4"><input type="checkbox" /> Sort by average cost </label> -->
              </div>
              <table class="primary-table dataTableView">
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
            <div class="">
              <div class="col-sm-7 add-more-participants-fields">
              @if(old('emailName'))
                <?php $indexer = 0 ?>
                @foreach(old('emailName') as $emailNameVal)
                  <div class="row number-emails">
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
              <div class="row number-emails">
              <div class="col-sm-5">
                <div class="">
                  <input name="emailName[0][name]" type="text" placeholder="Name" class="form-control" value="{{old('name')}}"/>
                </div>
              </div>
              <div class="col-sm-5">
                <div class="">
                  <input name="emailName[0][email]" type="email" placeholder="Email" class="form-control" value="{{old('email')}}"/>
                </div>
              </div>
              </div>
              @endif
              </div>
              <div class="col-sm-1"> <h1> OR </h1></div>
              <div class="col-sm-4">
                <div class="excel-participants">
                  <div style="padding-left:5px"><h3>Import via Excel</h3></div>
                  <div class="col-sm-12">
                      <div class="row">
                        <input name="import_file" type="file" placeholder="Upload excel" class="form-control" value="{{old('import_file')}}" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"/>
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
            <div class="padding-25"></div>
            <div class="row">
              <div class="col-sm-12 text-center">
                <button type="button" class="btn btn-success btn-add-new-user" data-type="plus">
                    Add More
                </button>
              </div>
            </div> 
          </div>
          
          <div class="row">
                <div class="col-sm-12">
                  <div class="">
                    <input type="submit" value="Next" class="btn btn-lg btn-primary" />
                  </div>
                </div>
            </div>
          
          {!! Form::close() !!}
@endsection

@section('footer-scripts')
    @include('layouts.partials.datatable')
    <script src="{{ asset('/js/google-map-script.js') }}"></script>
    <script src="{{ asset('/js/custome/select-deselect-script.js') }}"></script>
    <script src="{{ asset('/js/invite-participant.js') }}"></script>
    <script src="{{ asset('js/cookie.js') }}"></script>

    <script>
        Cookies.set("radioButtonValues", null, { path: '/' });
    </script>

@endsection
