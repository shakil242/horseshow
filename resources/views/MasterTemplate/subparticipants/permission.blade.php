@extends('layouts.equetica2')
@section('custom-htmlheader')
    @include('layouts.partials.form-header')
@endsection

@section('main-content')

    <!-- ================= CONTENT AREA ================== -->
    <div class="container-fluid">
    @php
        $title = "Set Permission";
        $added_subtitle ='';
    @endphp
    @include('layouts.partials.pagemenu',['added_title'=>$title,'added_subtitle'=>$added_subtitle,'remove_search'=>1])

    <!-- Content Panel -->
        <div class="white-board">
        @include('admin.layouts.errors')

                  <!--- Invite participants -->
           {{--<div class="row">--}}
              {{--<div class="col-sm-12">--}}
              {{--<div class="col-sm-8">--}}

                {{--<label class="permission-labels"><div style="color:#651E1C">Asset & location: </div>--}}

                    {{--<div class="col-sm-6">{{GetAssetNamefromId($data['asset'])}} </div>--}}
                    {{--<div class="col-sm-6">{{GetAssetLocationfromId($data['asset'])}} </div>--}}

                {{--</label>--}}
              {{--</div>--}}
              {{--<div class="col-sm-4">  --}}
                {{--<label class="col-sm-8 permission-labels"><div style="color:#651E1C">Invited Participant:</div>--}}
                  {{----}}
                   {{--<div class="emailsForInvite">--}}
                   {{--<!-- Excel Formate -->--}}
                     {{--@if(!empty($excelData))--}}
                      {{--@foreach($excelData as $excelEntry)--}}
                        {{--<p>{{$excelEntry['name']}} --- {{$excelEntry['email']}}</p>--}}
                      {{--@endforeach--}}
                    {{--@endif--}}
                    {{--<!-- New Invited Users -->--}}
                    {{--@if(isset($data['emailName']))--}}
                      {{--@foreach($data['emailName'] as $emailName)--}}
                        {{--@if($emailName['email'] !='')--}}
                        {{--<p>{{$emailName['name']}} --- {{$emailName['email']}}</p>--}}
                        {{--@endif--}}
                      {{--@endforeach--}}
                    {{--@endif--}}
                    {{--<!-- Past participants -->--}}
                    {{--@if(isset($data['pastParticipats']))--}}
                      {{--@foreach($data['pastParticipats'] as $pastParticipats)--}}
                        {{--<p>{{$pastParticipats}}</p>--}}
                      {{--@endforeach--}}
                    {{--@endif--}}
                  {{--</div>--}}
                {{--</label>--}}
              {{--</div>--}}
              {{--</div>--}}
            {{--</div>--}}
            {!! Form::open(['url'=>'master-template/invite/sub-participant/send/','method'=>'post','class'=>'form-horizontal dropzone targetvalue']) !!}
            <input type="hidden" name="template_id" value="{{$data['template_id']}}">
              <input type="hidden" name="asset" class="asset" value="{{implode(',',$data['asset'])}}">
              <input type="hidden" name="participant_id" value="{{implode(',',$data['participant_id'])}}">

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

            <div class="invite-participants-table">
              <div class="participants-filters">
                <div class="row">

                    <div class="participants-filters col-sm-9">
                        <h2>Modules Access</h2>
                    </div>



                    <div class="col-sm-3">
                        <div class="search-field mr-10">
                            <div class="input-group">
                                <input class="form-control" placeholder="Search By Name" id="myInputTextField" aria-label="Username" aria-describedby="basic-addon1" type="text">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1"><img src="{{asset('/img/icons/icon-search.svg')}}"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
              </div>

                @if($modules)
                    <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-line-braker mt-10 custom-responsive-md dataTableView display">
                       <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Asset</th>
                            <th scope="col">Module Name</th>
                            <th scope="col">Invoice</th>
                            <th scope="col">Access Rights</th>
                            <th scope="col">
                                <div class="form-group">
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input checked class="form-check-input select-past-participant" name="legendRadio" id="checkall" type="checkbox">
                                            <span>Select All</span>
                                        </label>
                                    </div>
                                </div></th>

                        </tr>
                        </thead>
                        <tbody>
                        @foreach($modules as $mod)
                            <?php $serial = $loop->index + 1; ?>
                            <tr>
                                <td>{{ $serial }}</td>
                                <td><span class="table-title">Asset</span>{{ GetAssetNamefromId($mod['asset']) }}</td>
                                <td><span class="table-title">Module Name</span>{{ getModuleNameModuleId($mod['module']) }}
                                </td>
                                <td><span class="table-title">Invoice</span>
                                    @if($mod['invoice'] > 0)
                                        <a target="_blank" style="float: left;margin-left: 0px; color: rgb(101, 30, 28);" href="{{URL::to('master-template') }}/{{nxb_encode($mod['module'])}}/{{$mod['asset']}}/associatedInvoice/viewInvoice">View Invoice</a>
                                    @else
                                        N/A
                                    @endif
                                </td>@if($mod['invoice'] > 0)<input type="hidden" name="invoiceAttach[{{$mod['module']}}]" value="{{$mod['module']}}" >@endif
                                <td><span class="table-title">Access Rights</span>{{ $mod['access'] }}</td>


                                @if(childModule($mod['module']) >0)
                                    <td><strong>Parent</strong></td>
                                @else
                                    <?php $modIds= parentModule($mod['module']); ?>
                                    <td>
                                        <div class="form-group">
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                    <input checked  value="2" class="form-check-input select-past-participant check" name="module[{{$modIds}}]" id="checkall" type="checkbox">
                                                    <span>&nbsp</span>
                                                </label>
                                            </div>
                                        </div>
                                @endif

                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    </div>
                    </div>
                @else
                    <div class="row">
                        <div class="col-sm-12">{{MASTER_TEMPLATE_NO_MODULES_TEXT}}</div>
                    </div>
                @endif

                @if($templates->category != CONST_SHOW)
                <div class="invite-participant-history">
                    <h2>Associate History</h2>
                    <div class="col-md-12">

                    <div class="table-responsive">
                        <table class="table table-line-braker mt-10 custom-responsive-md dataTableView display">
                        <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Submited By</th>
                            <th scope="col">Asset Name</th>
                            <th scope="col">Form Name</th>
                            <th scope="col">Location</th>
                            <th scope="col">Responsed On</th>
                            <th class="action">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(count($participantResponse)>0)
                            @foreach($participantResponse as $pResponse)
                                <?php $serial = $loop->index + 1; ?>
                                <tr>
                                    <td>{{ $serial }}</td>
                                    <td><span class="table-title">Submited By</span>{{ getUserNamefromid($pResponse->user_id) }}</td>
                                    <td><span class="table-title">Asset Name</span>{{ GetAssetNamefromId($pResponse->participant->asset_id) }}</td>
                                    <td><span class="table-title">Form Name</span>{{ getFormNamefromid($pResponse->form_id) }}</td>
                                    <td><span class="table-title">Location</span>{{ $pResponse->participant->location }}</td>
                                    <td><span class="table-title">Created On</span>{{  getDates($pResponse->created_at) }}</td>
                                    <td>
                                        <span class="table-title">Actions</span>
                                        <a target="_blank" href="{{URL::to('participant') }}/{{nxb_encode($pResponse->id)}}/all/response/readonly" class="btn-invite-parti"><i class="fa fa-eye" data-toggle="tooltip" title="View Details"></i> </a>
                                        <div class="form-group">
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                    <input  value="{{$pResponse->id}}" class="form-check-input select-past-participant check" name="attachmentHistory[]" id="checkall" type="checkbox">
                                                    <span>Attach</span>
                                                </label>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <td colspan="7" class="text-center">
                                <label>No History Associated yet!</label>
                            </td>
                        @endif
                        </tbody>
                    </table>
                </div>
                    </div>
                </div>
              @endif


              <div class="new-participants">
                <div class="row">


                  <div class="col-sm-12">
                      @if($templates->category == SHOW)
                          <input type="hidden" value="1" name="drp_permission">
                      @else
                    <h2>Permissions</h2>
                    <div class="form-group">
                      <div class="row">
                        <?php $Number_Permission = getParticipantPermissions($data['participant_id']); ?>
                        @if($Number_Permission == "unlimited")
                        <div class="col-sm-6">
                            <div class="row">
                            <div class="form-group col-md-4">
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input class="form-check-input" value="unlimited" name="permission" id="legendRadio1" type="radio">
                                        <span>Unlimited</span>
                                    </label>
                                </div>

                            </div>
                            <div class="col-md-4">
                                <span><?php echo CreatePermissionsDrp("drp_permission","",1,20); ?></span>
                            </div>
                            </div>
                        </div>
                        @else
                        <div class="col-sm-6">
                          <div class="col-sm-10">
                           <div class="col-sm-10">

                               <?php echo CreatePermissionsDrp("drp_permission","",1,$Number_Permission); ?>
                           </div>
                          </div>
                        </div>
                        @endif
                      </div>
                    </div>
                      @endif
                  </div>

        <hr>
                  <div class="col-sm-6">
                    <h2>Invite to Master Templates</h2>
                    <p> (Optional)</p>
                    <div class="form-group">
                      @if($associated)
                      <select name="invited_master_template">
                      <option value="">Please Select</option>
                      <option value="{{$data['template_id']}}">{{GetTemplateName($data['template_id'])}}</option>
                      @foreach($associated as $templat)
                        <option value="{{$templat->id}}">{{$templat->name}}</option>
                      @endforeach
                      </select>
                      @endif
                    </div>
                  </div>
                  <div class="col-sm-12 mt-20 mb-30">

                    <div class="row">
                      <div class="col-sm-1">
                        <input type="submit" class="btn btn-primary submitVals" value="INVITE" />
                      </div>
                      <div class="col-sm-2">
                        <input type="submit" class="btn btn-defualt" value="CLOSE" />
                      </div>
                    </div>
                  </div>
                </div>
              </div>


            {!! Form::close() !!}
      	 <!-- <div class="row">
      		<div class="col-lg-5 col-md-5 col-sm-6">You have not added any asset for this template yet!</div>
      	 </div> -->
            </div>
        </div>
@endsection

@section('footer-scripts')
    <script src="{{ asset('/js/google-map-script.js') }}"></script>
    <script src="{{ asset('js/cookie.js') }}"></script>

    <script src="{{ asset('/js/vender/jquery-ui.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('/css/vender/jquery-ui.css') }}" />

    <script src="{{ asset('js/permission.js') }}"></script>
    @include('layouts.partials.datatable')

@endsection
