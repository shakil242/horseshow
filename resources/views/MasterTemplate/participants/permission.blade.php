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
<?php $templateType = GetTemplateType($data['template_id']); ?>
          <!-- ================= CONTENT AREA ================== -->
<div class="main-contents">
    <div class="container-fluid">

        @php 
        $i_p_fields = getButtonLabelFromTemplateId($data['template_id'],'i_p_fields');
          $title = "Set Permission";
          $remove_search = 1;
          $added_subtitle =  Breadcrumbs::render('master-template-participants',$data['template_id']);
        @endphp
        @include('layouts.partials.pagemenu',['added_title'=>$title,'added_subtitle'=>$added_subtitle,'remove_search'=>1])

        <!-- Content Panel -->
        <div class="white-board permissions">            
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
                            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="division-tab">
                              @include('admin.layouts.errors')

                                <div class="box-shadow bg-white p-4 mt-30 mb-30">
                                                      <!--- Invite participants -->
                                 <div class="row">
                                    <div class="col-sm-12 row">
                                    <div class="col-sm-4">

                                      <h2>Asset:</h2>
                                      <div class="permission-labels row">
                                        @foreach($data['asset'] as $asset)
                                          <div class="col-sm-12">{{GetAssetNamefromId($asset)}} </div>
                                        @endforeach
                                      </div>
                                    </div>
                                    <div class="col-sm-4">
                                      <h2>Location:</h2>
                                      <div class="permission-labels row">
                                        @foreach($data['asset'] as $asset)
                                          <div class="col-sm-12">{{GetAssetLocationfromId($asset)}} </div>
                                        @endforeach
                                      </div>
                                    </div>
                                    <div class="col-sm-4">
                                      <label class="col-sm-8 permission-labels"><h2>Invited Participant:</h2>

                                         <div class="emailsForInvite">
                                         <!-- Excel Formate -->
                                           @if(!empty($excelData))
                                            @foreach($excelData as $excelEntry)
                                              <p>{{$excelEntry['name']}} --- {{$excelEntry['email']}}</p>
                                            @endforeach
                                          @endif
                                          <!-- New Invited Users -->
                                          @if(isset($data['emailName']))
                                            @foreach($data['emailName'] as $emailName)
                                              @if($emailName['email'] !='')
                                              <p>{{$emailName['name']}} --- {{$emailName['email']}}</p>
                                              @endif
                                            @endforeach
                                          @endif
                                          <!-- Past participants -->
                                          @if(isset($data['pastParticipats']))
                                            @foreach($data['pastParticipats'] as $pastParticipats)
                                              <p>{{$pastParticipats}}</p>
                                            @endforeach
                                          @endif
                                        </div>
                                      </label>
                                    </div>
                                    </div>
                                  </div>
                                    {!! Form::open(['url'=>'master-template/invite/participant/send/','method'=>'post','class'=>'form-horizontal dropzone targetvalue']) !!}
                                        <input type="hidden" name="template_id" value="{{$data['template_id']}}">
                                        <!-- <input type="hidden" name="location" value="$data['location']"> -->
                                         <br>
                                         <div class="row">
                                            @if(count($project_overview)>0)
                                            <div class="row col-sm-12 form-group">
                                                     <div class="col-sm-2"> <label style="padding-top:5px">Select Project Overview:</label> </div>
                                                     <div class="col-sm-4">
                                                       <select multiple name="project_overview[]" class="selectpicker show-tick form-control"
                                                               multiple data-size="8" data-selected-text-format="count>6" title="Please Select Class"  id="allAssets" data-live-search="true">
                                                             <option data-hidden="true"></option>
                                                                 @foreach($project_overview as $option)
                                                                     <option value="{{$option->id}}" @if(old("project_overview") != null) {{ (in_array($option->id, old("project_overview")) ? "selected":"") }} @endif> {{ getAssetName($option) }}</option>
                                                                 @endforeach
                                                         </select>
                                                     </div>
                                                 </div>
                                            @endif
                                          </div>

                                          @foreach($data['asset'] as $asset)
                                            <input type="hidden" name="asset[]" class="asset" value="{{$asset}}">
                                          @endforeach
                                          @if(isset($data['pastParticipats']))
                                            @foreach($data['pastParticipats'] as $pastParticipats)
                                              <input type="hidden" name="pastParticipats[]" value="{{$pastParticipats}}">
                                            @endforeach
                                          @endif
                                          <?php $indexer = 0; ?>
                                          @if(isset($data['emailName']))
                                            @foreach($data['emailName'] as $emailName)
                                              <input type="hidden" name="emailName[{{$indexer}}][email]" value="{{$emailName['email']}}">
                                              <input type="hidden" name="emailName[{{$indexer}}][name]" value="{{$emailName['name']}}">
                                            <?php $indexer = $indexer+1; ?>
                                            @endforeach
                                          @endif
                                          <!-- Excel Formate -->
                                           @if(!empty($excelData))
                                            @foreach($excelData as $excelEntry)
                                              <input type="hidden" name="emailName[{{$indexer}}][email]" value="{{$excelEntry['email']}}">
                                              <input type="hidden" name="emailName[{{$indexer}}][name]" value="{{$excelEntry['name']}}">
                                              <?php $indexer = $indexer+1; ?>
                                            @endforeach
                                          @endif
                                        <!-- <input type="hidden" name="search_location" value="$data['search_location']">
                                        <input type="hidden" name="latitude" value="$data['latitude']">
                                        <input type="hidden" name="longitude" value="$data['longitude']">
                                        <input type="hidden" name="place_id" value="$data['place_id']"> -->
                                        <input type="hidden" name="description" value="{{$data['description']}}">

                                        @if(isset($data['show_id']))
                                            <input type="hidden" name="show_id" value="{{$data['show_id']}}">
                                        @endif

                                        <div class="invite-participants-table">
                                          <div class="row">
                                            <div class="col-sm-5">
                                              <h2>Modules Access</h2>
                                            </div>
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
                                          </div>
                                       
                                        @if($modules)
                                                 <table class="primary-table DTB_nopagination table table-line-braker">
                                                    <thead>
                                                    <tr>
                                                        <th style="width:5%">#</th>
                                                        <th>Asset</th>
                                                        <th>Module Name</th>
                                                        <th>Invoice</th>
                                                        <th>Access Rights</th>
                                                        <th>
                                                          <label>
                                                            <input type="checkbox" id="checkall"> 
                                                           <span>
                                                            Select All
                                                          </span>
                                                          </label> 
                                                        </th>

                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                            @foreach($modules as $mod)
                                                @if(getModuleNameModuleId($mod['module']))
                                                <?php $serial = $loop->index + 1; ?>
                                                <tr>
                                                    <td>{{ $serial }}</td>
                                                    <td><strong class="visible-xs">Asset</strong>{{ GetAssetNamefromId($mod['asset']) }}</td>
                                                    <td><strong class="visible-xs">Module Name</strong>{{ getModuleNameModuleId($mod['module']) }}
                                                        {{--<input type="hidden" name="module[{{$mod['module']}}]" value="{{$mod['module']}}" >--}}
                                                    </td>
                                                    <td><strong class="visible-xs">Invoice</strong>
                                                        @if($mod['invoice'] > 0)
                                                        <a target="_blank" style="float: left;margin-left: 0px; color: rgb(101, 30, 28);" href="{{URL::to('master-template') }}/{{nxb_encode($mod['module'])}}/{{$mod['asset']}}/associatedInvoice/viewInvoice">View Invoice</a>
                                                    @else
                                                        N/A
                                                    @endif
                                                    </td>@if($mod['invoice'] > 0)<input type="hidden" name="invoiceAttach[{{$mod['module']}}]" value="{{$mod['module']}}" >@endif
                                                    <td><strong class="visible-xs">Access Rights</strong>{{ $mod['access'] }}</td>


                                                    @if(childModule($mod['module']) >0)
                                                    <td><strong>Parent</strong></td>
                                                    @else
                                                        <?php $modIds= parentModule($mod['module']); ?>
                                                        <td>
                                                         <label>
                                                            <input type="checkbox" value="2" name="module[{{$modIds}}]" class="check ch-one-select">
                                                            <span>Select</span> 
                                                         </label>
                                                        </td>
                                                    @endif

                                                </tr>
                                                @endif
                                            @endforeach
                                            </tbody>
                                           </table>
                                        @else
                                        <div class="row">
                                          <div class="col-lg-5 col-md-5 col-sm-6">{{MASTER_TEMPLATE_NO_MODULES_TEXT}}</div>
                                        </div>
                                        @endif
                                      </div>
                                      <!-- Historry of associated -->

                                @if($templateType==FACILTY)
                                <br>
                                <div class="invite-participant-history">
                                        <h2>Associate History</h2>
                                        <br />
                                         <table class="primary-table DTB_nopagination table table-line-braker">
                                          <thead>
                                            <tr>
                                              <th style="width:5%">#</th>
                                              <th>Submited By</th>
                                              <th>Asset Name</th>
                                              <th>Form Name</th>
                                              <th>Location</th>
                                              <th>Responsed On</th>
                                             <th>
                                                <label>
                                                  <input type="checkbox" id="checkAllHistory"> 
                                                  <span>Select All </span>
                                                </label>
                                             </th>

                                                {{--<th style="width:22%">Actions</th>--}}
                                            </tr>
                                          </thead>
                                          <tbody>
                                            @if(!$participantResponse->isEmpty())
                                              @foreach($participantResponse as $pResponse)
                                              <?php $serial = $loop->index + 1; ?>
                                              <tr>
                                                <td>{{ $serial }}</td>
                                                <td><strong class="visible-xs">Submited By</strong>{{ getUserNamefromid($pResponse->user_id) }}</td>
                                                <td><strong class="visible-xs">Asset Name</strong>{{ GetAssetNamefromId($pResponse->participant->asset_id) }}</td>
                                                <td><strong class="visible-xs">Form Name</strong>{{ getFormNamefromid($pResponse->form_id) }}</td>
                                                <td><strong class="visible-xs">Location</strong>{{ $pResponse->participant->location }}</td>
                                                <td><strong class="visible-xs">Created On</strong>{{  getDates($pResponse->created_at) }}</td>
                                                <td>
                                                  <storng class="visible-xs">Actions</storng>
                                                  <a target="_blank" href="{{URL::to('participant') }}/{{nxb_encode($pResponse->id)}}/all/response/readonly" class="btn-invite-parti">View Details</a>
                                                  <label>
                                                  <input name="attachmentHistory[]" value="{{$pResponse->id}}" class="checkHistory" type="checkbox" />
                                                  <span>Attach</span>
                                                  </label>
                                                </td>
                                              </tr>
                                              @endforeach
                                            @else
                                            <td>
                                              <label>No History Associated yet!</label>
                                            </td>
                                            @endif
                                          </tbody>
                                        </table>
                                      </div>
                                      <br />
                                    @endif
                                      <div class="new-participants">

                                        <div class="row">
                                          <div class="col-sm-12">
                                            <h2>Permissions</h2>
                                              <div class="row">
                                                <!-- <div class="col-sm-6">
                                                  <br />
                                                  <label class="col-xs-6">
                                                    <input type="radio" name="permission"  value="1" checked="checked" />
                                                    <span>One Time</span>
                                                    </label>
                                                  <label class="col-xs-6">
                                                    <input type="radio" name="permission" value="unlimited" />
                                                    <span>Unlimited</span>
                                                    </label>
                                                </div> -->
                                                <div class="col-sm-6">
                                                  <div class="row">
                                                  
                                                   <div class="col-sm-12"><?php echo CreatePermissionsDrp("drp_permission","",1); ?><!-- <input type="text" name="access" class="form-control" placeholder="Set Permission Time" onkeypress="return isNumber(event)"/> --></div>
                                                  </div>
                                                </div>
                                            </div>
                                          </div>

                                            <div class="col-sm-12 row">

                                                    @if($penaltyInvoice > 0)

                                                <br />
                                                    <div class="col-md-3"><h2>Penalty Invoice</h2></div>
                                                    <div class="form-group col-md-3">
                                                        <input name="penaltyDate" placeholder="Deadline Date"  id="datepicker" class="form-control datepicker" value="" type="text">
                                                    </div>
                                                        <a href="{{URL::to('master-template') }}/{{nxb_encode($data['template_id'])}}/{{$invoiceFormKey}}/PenaltyAssociatedInvoice/viewInvoice">View Invoice</a>
                                                    @else
                                                          @if($penaltyTemplate > 0)
                                                            <div class="col-md-3"><h2>Penalty Invoice</h2></div>
                                                            <div class="form-group col-md-3">
                                                                <input name="penaltyDate" placeholder="Deadline Date"  id="datepicker" class="form-control datepicker" value="" type="text">
                                                            </div>
                                                        <div class="setPenaltyInvoice">
                                                            <a href="{{URL::to('master-template') }}/penaltyInvoice/preview/{{nxb_encode($data['template_id'])}}">Set Penalty Invoice</a>
                                                            <a style="margin-left: 20px;" class="cancelPenalty" href="javascript:">Cancel</a>
                                                      </div>
                                                          @endif
                                                    @endif




                                            </div>
                                            <div class="row col-sm-12" style="padding-left: 20px;">


                                            <div class="col-sm-4">
                                              <br />
                                              <h2>Invite to Master Templates</h2>
                                              <p> (Optional)</p>
                                              <br />
                                              <div class="row col-sm-12">
                                              <div class="form-group">
                                                @if($associated)
                                                <select name="invited_master_template" class="selectpicker show-tick" data-live-search="true">
                                                <option value="">Please Select</option>
                                                <option value="{{$data['template_id']}}">{{GetTemplateName($data['template_id'])}}</option>
                                                @foreach($associated as $templat)
                                                  <option value="{{$templat->id}}">{{$templat->name}}</option>
                                                @endforeach
                                                </select>
                                                @endif
                                              </div>
                                              </div>
                                            </div>
                                            <div class="col-sm-1"></div>
                                            <div class="col-sm-4">
                                              <br />
                                              <h2>Allowed Profiles</h2>
                                              <p> (Optional)</p>
                                              <br />
                                              <div class="row col-sm-12">
                                              <div class="form-group">
                                                @if($profile_forms)
                                                <select name="allowed_invite_profiles[]" multiple name="asset[]" class="selectpicker show-tick form-control" multiple id="allowed_invite_profiles" data-live-search="true">
                                                  @foreach($profile_forms as $id => $pform)
                                                    <option value="{{$id}}">{{$pform}}</option>
                                                  @endforeach
                                                </select>
                                                @else
                                                  <p>No Invited Users profiles added for this template.</p>
                                                @endif
                                              </div>
                                              </div>
                                            </div>
                                            </div>

                                            @if($templateType==CONST_SHOW)

                                            <div class="row" style="padding-left: 20px;">

                                            <div class="col-sm-3" style="margin-top: 20px; margin-bottom: 20px; padding-left: 0px;">

                                                <label>{{post_value_or($i_p_fields,'selectShow','Select Show')}}</label>

                                                <select class="form-group form-control" name="show_id">

                                                    @foreach($manageShows as $show)
                                                        <option value="{{$show->id}}">  {{$show->title}}</option>
                                                    @endforeach

                                                </select>

                                            </div>
                                        </div>
                                            @endif

                                          <div class="col-sm-12">
                                            <br />

                                            <div class="row">
                                              <div class="col-sm-1">
                                                <input type="submit" class="btn btn-primary submitVals" value="INVITE" />
                                              </div>
                                              <div class="col-sm-4">
                                                <input type="submit" class="btn btn-defualt" value="CLOSE" />
                                              </div>
                                            </div>
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

      	 <!-- <div class="row">
      		<div class="col-lg-5 col-md-5 col-sm-6">You have not added any asset for this template yet!</div>
      	 </div> -->
@endsection

@section('footer-scripts')
    <script src="{{ asset('/js/google-map-script.js') }}"></script>
    <script src="{{ asset('js/cookie.js') }}"></script>

    <script src="{{ asset('/js/vender/jquery-ui.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('/css/vender/jquery-ui.css') }}" />

    <script src="{{ asset('js/permission.js') }}"></script>
    @include('layouts.partials.datatable')
<script>
    $(document).ready(function() {

    $(document).on('submit','.targetvalue',function(e){
        if($('.primary-table input.ch-one-select:checked').length == 0){
          alert('Please select atleast one Module');
          e.preventDefault();
        }
    });


    $('.display').DataTable({
        "scrollY":        "400px",
        "scrollCollapse": true,
        "search": false,
        "ordering": false,
        "paging":         false,
        "bLengthChange": false,
        "bInfo": false,
        "bAutoWidth": false,
////        "language": {
////
////
////        }
////
////
//    "pageLength":30,
//    "info":     false,
//    "bLengthChange": false,
//    "bFilter": true,
//    "bInfo": false,
//    "bAutoWidth": false,
//    "language": {
//    "paginate": {
//    "first":      "First",
//    "last":       "Last",
//    "next":       "<i class='fa fa-angle-right' aria-hidden='true'></i>",
//    "previous":   "<i class='fa fa-angle-left' aria-hidden='true'></i>"
//    },


    });
    } );
</script>
<style>

    .invite-participants-table #DataTables_Table_0_filter {
        display: none!important;
    }

    .invite-participant-history #DataTables_Table_0_filter {
        display: none!important;
    }

    .dataTables_filter {
        float: right !important;
    }


</style>
@endsection
