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
          $title = GetAssetNamefromId($asset_id)." Feedback";
          $added_subtitle = Breadcrumbs::render('participant-asset-getFeedBack',$asset_id);
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
                            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="division-tab">
                              @include('admin.layouts.errors')
                                <div class="row">
                                    <div class="col-sm-12">
                                       @if(!$feedBack->count())
                                            <div class="">
                                                <div class="col-lg-5 col-md-5 col-sm-6">{{NO_PARTICIPANT_RESPONSE}}</div>
                                            </div>
                                        @else
                                                <div class="tab-content">
                                            
                                                    <div class="module-holer rr-datatable">
                                                        <table id="" class="table primary-table dataViews">
                                                            <thead class="hidden-xs">
                                                            <tr>
                                                                <th style="width:5%">#</th>
                                                                <th>Submitted By</th>
                                                                <th>Horse</th>
                                                                <th>Feedback Type</th>
                                                                <th>Start Ride Time</th>
                                                                {{--<th>Appointment To</th>--}}
                                                                <th class="action">Actions</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>

                                                            @if(sizeof($feedBack)>0)
                                                                @foreach($feedBack as $pResponse)
                                                                    <?php
                                                                    $serial = $loop->index + 1;
                                                                    $roleName = '';
                                                                    ?>
                                                                    <tr>
                                                                        <td>{{ $serial }}</td>
                                                                        <td><strong class="visible-xs">Submitted By</strong>{{ $pResponse->invitee->name }}</td>
                                                                        <td><strong class="visible-xs">Horse </strong>@if($pResponse->horse) {{ GetAssetName($pResponse->horse) }} @else No Horse Selected @endif</td>
                                                                        <td><strong class="visible-xs">Template Name</strong>{{ getFormNameFromId($pResponse->form_id) }}</td>
                                                                        <td><strong class="visible-xs">Start Ride Time</strong>@if(!is_null($pResponse->schedualNotes->timeFrom)){{  \Carbon\Carbon::parse($pResponse->schedualNotes->timeFrom)->format('m-d-Y g:i A') }}@endif</td>
                                                                        {{--<td><strong class="visible-xs">Appointment To</strong>@if(!is_null($pResponse->schedualNotes->timeTo)){{  \Carbon\Carbon::parse($pResponse->schedualNotes->timeTo)->format('m-d-Y H:i a') }}@endif</td>--}}

                                                                        <td class="pull-left">
                                                                            <strong class="visible-xs">Actions</strong>
                                                                            <a href="{{URL::to('participant') }}/{{nxb_encode($pResponse->id)}}/FeeBack/viewFeedBack" data-toggle="tooltip" data-placement="top" title="View Feedback Template"><i class="fa fa-eye" aria-hidden="true"></i></a>
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
