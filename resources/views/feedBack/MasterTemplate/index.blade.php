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

            $ya_fields = getButtonLabelFromTemplateId($template_id,'ya_fields');

            if($feedBackType==JUDGES_FEEDBACK)
            $title = post_value_or($ya_fields,'judges_feed_back','Judges Feedback');
            else
            $title = post_value_or($ya_fields,'feedback','Feedback');

            $added_subtitle = "";
        @endphp
        @include('layouts.partials.pagemenu',['added_title'=>$title,'added_subtitle'=>$added_subtitle])

        <!-- Content Panel -->

        <div class="white-board">

            @if(Session::has('message'))
                <p class="alert {{ Session::get('alert-class', 'alert-success') }} text-center">{{ Session::get('message') }}</p>
            @endif
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
                                            <a class="btn btn-success pull-right" id="SubmitFeedBackRequest" style="color: #FFF; display: none">Display Selected Feedback to Riders</a>
                                    </div>
                                    <div class="col-sm-12">

                                    <div class="module-holer rr-datatable">
                                                    <table id="" class="table table-line-braker mt-10 custom-responsive-md dataViews">
                                                        <thead class="hidden-xs">
                                                        <tr>
                                                            <th style="width:5%">#</th>
                                                            <th>Participant</th>
                                                            <th>Horse</th>
                                                            <th>Class</th>
                                                            <th>Feedback type</th>
                                                            <th>Show Name</th>
                                                            <th>Start Ride Time</th>
                                                            @if($feedBackType==JUDGES_FEEDBACK)
                                                            <th>Rider Allowed to View</th>
                                                            <th style="width: 112px;">Actions
                                                                <label class="pull-left">
                                                                    <input type="checkbox"  id="checkallFeedBacks">
                                                                    <span>&nbsp</span>
                                                                </label>
                                                            </th>
                                                             @else
                                                                <th style="width: 112px;">Actions</th>
                                                             @endif

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
                                                                    <td><strong class="visible-xs">Participant</strong>{{ $pResponse->user->name }}</td>
                                                                    <td><strong class="visible-xs">Horse </strong>@if($pResponse->horse) {{ GetAssetName($pResponse->horse) }} @else No Horse Selected @endif</td>
                                                                    <td><strong class="visible-xs">Class</strong>{{ GetAssetNamefromId($pResponse->asset_id) }}</td>
                                                                    <td><strong class="visible-xs">Class Type</strong>{{ getFormNamefromid($pResponse->form_id) }}</td>
                                                                    <td><strong class="visible-xs">Show Name</strong>{{ $pResponse->show->title }}</td>
                                                                    <td><strong class="visible-xs">Start Ride Time</strong>@if(!is_null($pResponse->schedualNotes->timeFrom)){{  \Carbon\Carbon::parse($pResponse->schedualNotes->timeFrom)->format('m-d-Y g:i A') }}@endif</td>
                                                                    @if($pResponse->feed_back_type==JUDGES_FEEDBACK)
                                                                    <td><strong class="visible-xs">Rider Allowed to View</strong>{{($pResponse->rider_allowed_to_view==1)?'Shown':'No Access' }}</td>
                                                                   @endif
                                                                    <td class="action">
                                                                        <strong class="visible-xs">Actions</strong>
                                                                       @if($pResponse->rider_allowed_to_view==0 && $pResponse->feed_back_type==JUDGES_FEEDBACK)
                                                                        <label class="pull-left">
                                                                            <input type="checkbox" value="{{$pResponse->id}}" name="riderAllowed[]" class="checkFeedBack selectedRows">
                                                                            <span>&nbsp</span>
                                                                        </label>
                                                                        @endif
                                                                        <a class="{{($pResponse->rider_allowed_to_view==1)?'customPaddingClass':'' }}" href="{{URL::to('participant') }}/{{nxb_encode($pResponse->id)}}/FeeBack/viewFeedBack" data-toggle="tooltip" data-placement="top" title="View Feedback Template"><i class="fa fa-eye" aria-hidden="true"></i></a>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        @endif
                                                        </tbody>
                                                    </table>
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
