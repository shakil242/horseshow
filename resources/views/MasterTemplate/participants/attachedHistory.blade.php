@extends('layouts.equetica2')

@section('custom-htmlheader')
    @include('layouts.partials.form-header')
@endsection

@section('main-content')
    <!-- ================= CONTENT AREA ================== -->
    <div class="container-fluid">

    @php
        $title = "Asset History";
        $added_subtitle =Breadcrumbs::render('participant-asset-history',$asset_id);
    @endphp
    @include('layouts.partials.pagemenu',['added_title'=>$title,'added_subtitle'=>$added_subtitle])

    <!-- Content Panel -->
        <div class="white-board">

        <div class="row">
            <div class="col-md-12">
            @if($participantResponse == null)
                  <div class="row">
                <div class="col-md-12">{{NO_PARTICIPANT_RESPONSE}}</div>
              </div>
            @else

                      <div class="table-responsive module-holer rr-datatable">
                          <table class="table table-line-braker mt-10 custom-responsive-md dataTableView">


                          {{--<div class="invite-participant-history">--}}
                  {{--<table class="primary-table dataTableView">--}}
              <thead class="hidden-xs">
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">Asset Name</th>
                  <th scope="col">Form Name</th>
                  <th scope="col">Location</th>
                  <th scope="col">Responsed On</th>
                  <th class="action">Actions</th>
                </tr>
              </thead>
              <tbody>
                @if($participantResponse != null) 
                  @foreach($participantResponse as $pResponse)
                  <?php $serial = $loop->index + 1; ?>
                  <tr>
                    <td>{{ $serial }}</td>
                    <td><span class="table-title">Asset Name</span>{{ GetAssetNamefromId($pResponse->participant->asset_id) }}</td>
                    <td><span class="table-title">Form Name</span>{{ getFormNamefromid($pResponse->form_id) }}</td>
                    <td><span class="table-title">Location</span>{{ $pResponse->participant->location }}</td>
                    <td><span class="table-title">Created On</span>{{  getDates($pResponse->created_at) }}</td>
                    <td class="action">
                      <span class="table-title">Actions</span>
                        <a target="_blank" href="{{URL::to('participant') }}/{{nxb_encode($pResponse->id)}}/all/response/readonly" ><i class="fa fa-eye" data-toggle="tooltip" title="View Details" > </i></a>
                    </td>
                  </tr>
                  @endforeach
                @else
                <td colspan="6">
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
