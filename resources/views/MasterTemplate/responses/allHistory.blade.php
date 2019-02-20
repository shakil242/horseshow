@extends('layouts.equetica2')

@section('custom-htmlheader')
  <!-- Search populate select multiple-->
 <script src="{{ asset('/js/vender/bootstrap3-typeahead.min.js') }}"></script>
  <!-- END:Search populate select multiple-->
@endsection

@section('main-content')
	 	<div class="row">
		 	<div class="col-sm-8">
              <h1>Your Responses</h1>
            </div>
            <div class="col-sm-4 action-holder">
              <form action="#">
                <div class="search-form">
                  <input class="form-control input-sm" placeholder="Search By Name" id="myInputTextField" type="search">
                  <button type="submit"><i class="fa fa-search" aria-hidden="true"></i></button>
                </div>
              </form>
            </div>
      	</div>
         <div class="row">
        <div class="info">
            @if(Session::has('message'))
                <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
            @endif
        </div>
    </div>
      	<div class="row">
	        <div class="col-sm-12">
          {!! Breadcrumbs::render('template-overall-allHistory',["template_id"=>$template_id,"invitee_id"=>$invitee_id]) !!}
		         
	    	  </div>
      	</div>
        <div class="row">
          <div class="col-sm-12">
            @if(!$participantResponse->count())
              <div class="">
                <div class="col-lg-5 col-md-5 col-sm-6">{{NO_PARTICIPANT_RESPONSE}}</div>
              </div>
            @else
              <ul class="nav nav-tabs">
                <li class="active"><a data-toggle="tab" href="#indivisual">Indivisual Response</a></li>
                <li><a data-toggle="tab" href="#response">Responses</a></li>
              </ul>
                <div class="tab-content">
                  <div id="indivisual" class="tab-pane fade in active">
                            <div class="module-holer rr-datatable">
                                <table id="crudTable2" class="table primary-table">
                                <thead class="hidden-xs">
                                   <tr>
                                      <th style="width:5%">#</th>
                                      <th>Submited By</th>
                                      <th>Asset Name</th>
                                      <th>Form Name</th>
                                      <th>Location</th>
                                      <th>Responsed On</th>
                                      <th style="width:22%">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @if(sizeof($participantResponse)>0)
                                        @foreach($participantResponse as $pResponse)
                                            <?php 
                                              $serial = $loop->index + 1; 
                                              $roleName = '';
                                            ?>
                                            <tr>
                                                <td>{{ $serial }}</td>
                                                <td><strong class="visible-xs">Submited By</strong><a href="#" data-toggle="modal" data-target="#ProfilesModal{{$pResponse->id}}" >{{ getUserNamefromid($pResponse->user_id) }}</a></td>
                                                <td><strong class="visible-xs">Asset Name</strong>{{ GetAssetNamefromId($pResponse->participant->asset_id) }}</td>
                                                <td><strong class="visible-xs">Form Name</strong>{{ getFormNamefromid($pResponse->form_id) }}</td>
                                                <td><strong class="visible-xs">Location</strong>{{ $pResponse->participant->location }}</td>
                                                <td><strong class="visible-xs">Created On</strong>{{  getDates($pResponse->created_at) }}</td>
                                                 <td class="pull-left">
                                                      <strong class="visible-xs">Actions</strong>
                                                      <a href="{{URL::to('participant') }}/{{nxb_encode($pResponse->id)}}/all/response/readonly" data-toggle="tooltip" data-placement="top" title="View Master Template"><i class="fa fa-eye" aria-hidden="true"></i></a>
                                                  </td>
                                            </tr>
                                             @include('setting.profile.modal')
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                       </div>
                  </div>
                  <div id="response" class="tab-pane fade in">
                      <div class="module-holer rr-datatable">
                                <table id="crudTable3" class="table primary-table">
                                <thead class="hidden-xs">
                                   <tr>
                                      <th style="width:5%">#</th>
                                      <th>Form Name</th>
                                      <th>Module Attached To</th>
                                      <th>No Of Responses</th>
                                      <th style="width:22%">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @if(sizeof($forms)>0)
                                        @foreach($forms as $pResponse)
                                            <?php 
                                              $serial = $loop->index + 1; 
                                              $roleName = '';
                                            ?>
                                            <tr>
                                                <td>{{ $serial }}</td>
                                                <td><strong class="visible-xs">Form Name</strong>{{ getFormNamefromid($pResponse->form_id) }}</td>
                                                <td><strong class="visible-xs">Module Attached To</strong>{{ getFormsModuleFromId($pResponse->form_id) }}</td>
                                                <td><strong class="visible-xs">No Of Responses</strong>{{ getFormsResponsesfromId($pResponse->form_id,$user_id) }}</td>
                                                <td class="pull-left">
                                                      <strong class="visible-xs">Actions</strong>
                                                      <a href="{{URL::to('master-template') }}/{{nxb_encode($pResponse->form_id)}}/{{nxb_encode($invitee_id)}}/graphics/response" data-toggle="tooltip" data-placement="top" title="View Master Template"><i class="fa fa-eye" aria-hidden="true"></i></a>
                                                  </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                       </div>
                  </div>
                </div>
            @endif
          </div>
        </div>
        <!-- Tab containing all the data tables -->
   
		
@endsection

@section('footer-scripts')
    <script src="{{ asset('/js/ajax-dynamic-assets-table.js') }}"></script>
    @include('layouts.partials.datatable')
@endsection