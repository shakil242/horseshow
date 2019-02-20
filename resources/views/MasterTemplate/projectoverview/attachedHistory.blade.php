@extends('layouts.equetica2')

@section('custom-htmlheader')
  <!-- Search populate select multiple-->
 <script src="{{ asset('/js/vender/bootstrap3-typeahead.min.js') }}"></script>
  <!-- END:Search populate select multiple-->
@endsection

@section('main-content')

<!-- ================= CONTENT AREA ================== -->
<div class="main-contents">
    <div class="container-fluid">
        @php 
          $title = "Project History";
          $added_subtitle =Breadcrumbs::render('master-template-participants-viewProjectOverview', $participant_id);
        @endphp
        @include('layouts.partials.pagemenu',['added_title'=>$title,'added_subtitle'=>$added_subtitle])

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
                            <div class="row">
                              <div class="col-sm-12">
                                @if($participantResponse == null)
                                  <div class="">
                                    <div class="col-lg-5 col-md-5 col-sm-6">{{NO_PARTICIPANT_RESPONSE}}</div>
                                  </div>
                                @else
                                  <div class="invite-participant-history">
                                <table class="table table-line-braker mt-10 custom-responsive-md dataViews">
                                  <thead class="hidden-xs">
                                    <tr>
                                      <th style="width:5%">#</th>
                                      <th>Project Overview Name</th>
                                      <th style="width:22%">Actions</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    @if($participantResponse != null)
                                      @foreach($participantResponse as $pResponse)
                                      <?php $serial = $loop->index + 1; ?>
                                      <tr>
                                        <td>{{ $serial }}</td>
                                        <td><strong class="visible-xs">Project Overview Name</strong>{{ getAssetName($pResponse->projectOverview) }}</td>
                                        <td>
                                          <storng class="visible-xs">Actions</storng>
                                          <a target="_blank" href="{{URL::to('master-template') }}/{{nxb_encode($pResponse->projectOverview->id)}}/asset/readonly" class="btn-invite-parti pull-left">View Details</a>
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
    <script src="{{ asset('/js/ajax-dynamic-assets-table.js') }}"></script>
    @include('layouts.partials.datatable')
@endsection
