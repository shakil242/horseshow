@extends('layouts.equetica2')

@section('custom-htmlheader')
  <!-- Search populate select multiple-->
 <script src="{{ asset('/js/vender/bootstrap3-typeahead.min.js') }}"></script>
  <!-- END:Search populate select multiple-->
@endsection

@section('main-content')
	 	<div class="row">
		 	<div class="col-sm-8">
              <h1>Asset History</h1>
            </div>
            <div class="col-sm-4 action-holder">
               <div class="search-form">
                <input type="text" placeholder="Search By Name, Date, Location etc ..." id="mySearchTerm">
                <button type="submit"><i class="fa fa-search" aria-hidden="true"></i></button>
              </div>
            </div>
      	</div>
      	<div class="row">
	        <div class="col-sm-12">
		          {!! Breadcrumbs::render('participant-asset-history',$asset_id) !!}
	    	  </div>
      	</div>
        <div class="row">
          <div class="col-sm-12">
            @if($participantResponse == null)
              <div class="">
                <div class="col-lg-5 col-md-5 col-sm-6">{{NO_PARTICIPANT_RESPONSE}}</div>
              </div>
            @else
              <div class="invite-participant-history">
                  <table class="primary-table dataTableView">
              <thead class="hidden-xs">
                <tr>
                  <th style="width:5%">#</th>
                  <th>Asset Name</th>
                  <th>Form Name</th>
                  <th>Location</th>
                  <th>Responsed On</th>
                  <th style="width:22%">Actions</th>
                </tr>
              </thead>
              <tbody>
                @if($participantResponse != null) 
                  @foreach($participantResponse as $pResponse)
                  <?php $serial = $loop->index + 1; ?>
                  <tr>
                    <td>{{ $serial }}</td>
                    <td><strong class="visible-xs">Asset Name</strong>{{ GetAssetNamefromId($pResponse->participant->asset_id) }}</td>
                    <td><strong class="visible-xs">Form Name</strong>{{ getFormNamefromid($pResponse->form_id) }}</td>
                    <td><strong class="visible-xs">Location</strong>{{ $pResponse->participant->location }}</td>
                    <td><strong class="visible-xs">Created On</strong>{{  getDates($pResponse->created_at) }}</td>               
                    <td>
                      <storng class="visible-xs">Actions</storng>
                      <a target="_blank" href="{{URL::to('participant') }}/{{nxb_encode($pResponse->id)}}/all/response/readonly" class="btn-invite-parti pull-left">View Details</a>
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
            @endif
          </div>
        </div>
        <!-- Tab containing all the data tables -->
   
		
@endsection

@section('footer-scripts')
    <script src="{{ asset('/js/ajax-dynamic-assets-table.js') }}"></script>
    @include('layouts.partials.datatable')
@endsection
