@extends('layouts.equetica2')
@section('custom-htmlheader')
    @include('layouts.partials.form-header')
@endsection
@section('main-content')
    <!-- ================= CONTENT AREA ================== -->
    <div class="container-fluid">
    @php
        $title = "Asset History";
        $added_subtitle =Breadcrumbs::render('subparticipant-asset-history',$subParticipant_id);
    @endphp
    @include('layouts.partials.pagemenu',['added_title'=>$title,'added_subtitle'=>$added_subtitle])
    <!-- Content Panel -->
        <div class="white-board">

            <div class="row">
                <div class="info">
                    @if(Session::has('message'))
                        <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                    @endif
                </div>
            </div>

        <div class="row">
          <div class="col-sm-12">
              <div class="row">
            @if(!$participantResponse->count())
                <div class="col-sm-12">{{NO_PARTICIPANT_RESPONSE}}</div>
             @else

                      <div class="table-responsive module-holer rr-datatable">
                          <table id="crudTable2" class="table table-line-braker mt-10 custom-responsive-md">

                        <thead class="hidden-xs">
                           <tr>
                              <th scope="col">#</th>
                              <th scope="col">Invited By</th>
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
                                        <td><span class="table-title">Invited By</span> @if(isset($pResponse->user_id)) {{getUserNamefromid($pResponse->user_id)}} @endif</td>
                                        <td><span class="table-title">Asset Name</span>@if(isset($pResponse->participant)) {{ GetAssetNamefromId($pResponse->participant->asset_id) }}@endif</td>
                                        <td><span class="table-title">Location</span>@if(isset($pResponse->location)){{ $pResponse->participant->location }}@endif</td>
                                        <td><span class="table-title">Created On</span>{{  getDates($pResponse->created_at) }}</td>
                                         <td>
                                              <span class="table-title">Actions</span>
                                              <a href="{{URL::to('participant') }}/{{nxb_encode($pResponse['id'])}}/response/readonly"><i data-toggle="tooltip" data-placement="top" title="View Master Template" class="fa fa-eye" aria-hidden="true"></i></a>
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
        <!-- Tab containing all the data tables -->
   
		
@endsection

@section('footer-scripts')
    <script src="{{ asset('/js/ajax-dynamic-assets-table.js') }}"></script>
    @include('admin.layouts.partials.datatable')
@endsection
