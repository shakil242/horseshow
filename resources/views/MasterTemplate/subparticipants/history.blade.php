@extends('layouts.equetica2')

@section('custom-htmlheader')
    @include('layouts.partials.form-header')
@endsection

@section('main-content')

    <!-- ================= CONTENT AREA ================== -->
    <div class="main-contents">
        <div class="container-fluid">

        @php
            $title = "Sub Participant Response";
            $added_subtitle = Breadcrumbs::render('subparticipant-asset-history',$subParticipant_id);
        @endphp
        @include('layouts.partials.pagemenu',['added_title'=>$title,'added_subtitle'=>$added_subtitle])

        <!-- Content Panel -->
            <div class="white-board">

        <div class="row">
          <div class="col-sm-12">
            @if(!$participantResponse->count())
              <div class="row">
                <div class="col-sm-12">{{NO_PARTICIPANT_RESPONSE}}</div>
              </div>
            @else

                  <div class="col-md-12">
                  <div class="table-responsive">
                      <table class="table table-line-braker mt-10 custom-responsive-md " id="crudTable2">
                        <thead class="hidden-xs">
                           <tr>
                              <th scope="col">#</th>
                              <th scope="col">Responded By</th>
                              <th scope="col">Asset Name</th>
                              <th scope="col">Location</th>
                              <th scope="col">Responsed On</th>
                              <th class="action">Actions</th>
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
                                        <td><span class="table-title">Responded By</span>{{ getUserNamefromid($pResponse->subparticipant) }}</td>
                                        <td><span class="table-title">Asset Name</span>{{ GetAssetNamefromId($pResponse->participant->asset_id) }}</td>
                                        <td><span class="table-title">Location</span>{{ $pResponse->participant->location }}</td>
                                        <td><span class="table-title">Created On</span>{{  getDates($pResponse->created_at) }}</td>
                                         <td class="action">
                                              <span class="table-title">Actions</span>
                                              <a href="{{URL::to('participant') }}/{{nxb_encode($pResponse['id'])}}/response/readonly"><i data-toggle="tooltip" data-placement="top" title="View Master Template" class="fa fa-eye" aria-hidden="true"></i></a>
                                          </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
              </div>
            @endif
          </div>
        </div>
        <!-- Tab containing all the data tables -->
   
		
@endsection

@section('footer-scripts')
    <script src="{{ asset('/js/ajax-dynamic-assets-table.js') }}"></script>
    @include('admin.layouts.partials.datatable')
@endsection
