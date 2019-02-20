@extends('layouts.equetica2')

@section('custom-htmlheader')
    <!-- Search populate select multiple-->
    <script src="{{ asset('/js/vender/bootstrap3-typeahead.min.js') }}"></script>
    <!-- END:Search populate select multiple-->
@endsection

@section('main-content')

   <!-- ================= CONTENT AREA ================== -->
        <div class="container-fluid">
        @php
            $title = GetAssetNamefromId($asset_id)." Feedback";
            $added_subtitle = Breadcrumbs::render('Asset-FeedBack-list',['asset_id' => $asset_id,'templateID'=>$template_id]);
        @endphp
        @include('layouts.partials.pagemenu',['added_title'=>$title,'added_subtitle'=>$added_subtitle])

        <!-- Content Panel -->
            <div class="white-board">

                <div class="row">
                    <div class="col-md-12">
                        @include('admin.layouts.errors')

                        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="division-tab">

                           <div class="col-sm-12">
                                @if(!$feedBack->count())
                                    <div class="">
                                        <div class="col-lg-5 col-md-5 col-sm-6">{{NO_PARTICIPANT_RESPONSE}}</div>
                                    </div>
                                @else
                                       
                                            <div class="module-holer rr-datatable">
                                                <table class="table primary-table table-line-braker mt-10 custom-responsive-md dataViews dataTable no-footer">
                                                    <thead class="hidden-xs">
                                                    <tr>
                                                        <th style="width:5%">#</th>
                                                        <th>Given By</th>
                                                        <th>Class</th>
                                                        <th>Class type</th>
                                                        <th>Appointment From</th>
                                                        <th>Appointment To</th>
                                                        <th style="width:22%">Actions</th>
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
                                                                <td><strong class="visible-xs">Given By</strong>{{ $pResponse->invitee->name }}</td>
                                                                <td><strong class="visible-xs">Class</strong>{{ GetAssetNamefromId($pResponse->asset_id) }}</td>
                                                                <td><strong class="visible-xs">Class Type</strong>{{ getFormNamefromid($pResponse->form_id) }}</td>
                                                                <td><strong class="visible-xs">Appointment From</strong>{{  $pResponse->schedualNotes->timeFrom }}</td>
                                                                <td><strong class="visible-xs">Appointment To</strong>{{  $pResponse->schedualNotes->timeTo }}</td>
                                                                <td class="pull-left">
                                                                    <strong class="visible-xs">Actions</strong>
                                                                    <a href="{{URL::to('participant') }}/{{nxb_encode($pResponse->id)}}/FeeBack/viewFeedBack/1" data-toggle="tooltip" data-placement="top" title="View Feedback Template"><i class="fa fa-eye" aria-hidden="true"></i></a>
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
            </div>
        </div>



@endsection

@section('footer-scripts')
    <script src="{{ asset('/js/ajax-dynamic-assets-table.js') }}"></script>
    @include('layouts.partials.datatable')
@endsection
