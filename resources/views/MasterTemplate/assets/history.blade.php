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
          $title = "Participants Response";
          $added_subtitle = Breadcrumbs::render('template-asset-history',['asset_id'=>$asset_id,'template_id'=>$template_id]);
        @endphp
        @include('layouts.partials.pagemenu',['added_title'=>$title,'added_subtitle'=>$added_subtitle,'remove_search'=>1])

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
                                        @if(!$participantResponse->count())
                                          <div class="row">
                                            <div class="col-lg-5 col-md-5 col-sm-6">{{NO_PARTICIPANT_RESPONSE}}</div>
                                          </div>
                                        @else
                                          <div class="">
                                                
                                                    <table class="table primary-table table-line-braker mt-10 custom-responsive-md dataViews">
                                                    <thead class="hidden-xs">
                                                       <tr>
                                                          <th style="width:5%">#</th>
                                                          <th>Participant Name</th>
                                                          <th>Participant Email</th>
                                                          <th>Responsed On</th>
                                                          <th>Actions</th>
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
                                                                    <td><strong class="visible-xs">Participant Name</strong>{{ getUserNamefromid($pResponse->user_id) }}</td>
                                                                    <td><strong class="visible-xs">Participant Email</strong>{{ $pResponse->participant->email }}</td>
                                                                    <td><strong class="visible-xs">Created On</strong>{{  getDates($pResponse->created_at) }}</td>
                                                                     <td>
                                                                          <strong class="visible-xs">Actions</strong>
                                                                          <!-- <a href="#" data-toggle="tooltip" data-placement="top" title="Template Linked"><i class="fa fa-users" aria-hidden="true"></i></a> -->
                                                                          <a href="{{URL::to('participant') }}/{{nxb_encode($pResponse['id'])}}/response/readonly" data-toggle="tooltip" data-placement="top" title="View Master Template"><i class="fa fa-eye" aria-hidden="true"></i></a>
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
