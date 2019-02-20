@extends('layouts.equetica2')

@section('custom-htmlheader')
    @include('layouts.partials.form-header')
@endsection

@section('main-content')

    <!-- ================= CONTENT AREA ================== -->
        <div class="container-fluid">

        @php
            $title = "Asset History";
            $added_subtitle = Breadcrumbs::render('participant-asset-history',$asset_id);
        @endphp
        @include('layouts.partials.pagemenu',['added_title'=>$title,'added_subtitle'=>$added_subtitle])

        <!-- Content Panel -->
            <div class="white-board">



        <div class="row">
          <div class="col-sm-12">
            @if(!$participantResponse->count())
              <div class="">
                <div class="col-lg-5 col-md-5 col-sm-6">{{NO_PARTICIPANT_RESPONSE}}</div>
              </div>
            @else
                  <div class="col-md-12">
                      
                      <table id="" class="table table-line-braker mt-10 custom-responsive-md dataViews">
                        <thead class="hidden-xs">
                           <tr>
                              <th scope="col">#</th>
                              <th scope="col">Invited By</th>
                              <th scope="col">Form Name</th>
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
                                        <td><span class="table-title">Invited By</span>{{ getUserNamefromid($pResponse->participant->invitee_id) }}</td>
                                         <td><span class="table-title">Form Name</span>{{ $pResponse->form->name }}</td>
                                         <td><span class="table-title">Created On</span>{{  getDates($pResponse->created_at) }}</td>
                                         <td>
                                              <span class="table-title">Actions</span>

                                             <a  class="viewInvoiceBtn"

                                                 href="{{URL::to('master-template') }}/exportResponsePdf/{{nxb_encode($pResponse->id)}}" class="ic_bd_export"><i class="fa fa-file-pdf-o" data-toggle="tooltip" title="Export PDF"></i> </a>

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
        <!-- Tab containing all the data tables -->
   
		
@endsection

@section('footer-scripts')
    <script src="{{ asset('/js/ajax-dynamic-assets-table.js') }}"></script>
    @include('admin.layouts.partials.datatable')
@endsection
