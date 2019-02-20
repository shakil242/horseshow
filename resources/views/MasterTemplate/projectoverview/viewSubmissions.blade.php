@extends('layouts.equetica2')

@section('custom-htmlheader')
  <!-- Search populate select multiple-->
 <script src="{{ asset('/js/vender/bootstrap3-typeahead.min.js') }}"></script>
  <!-- END:Search populate select multiple-->
@endsection

@section('main-content')
<!-- ================= CONTENT AREA ================== -->
<div class="row">
    <div class="col-sm-12">
         <div class="info">
             @if(Session::has('message'))
                 <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
             @endif
         </div>
     </div>
</div>
<div class="main-contents">
    <div class="container-fluid">
        @php 
          $title = "Project History - ".getAssetNameFromId($projectoverview_id);
          $added_subtitle = Breadcrumbs::render('master-template-projectoverview-listresponse', ['projectoverview_id'=>$projectoverview_id,'template_id'=>$template_id]);
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
              <input type="hidden" class="owner-name" value="{{$username}}">
              <input type="hidden" class="owner-email" value="{{$useremail}}">
              <div class="invite-participant-history">
              <table class="table table-line-braker mt-10 custom-responsive-md dataTableView">
              <thead class="hidden-xs">
                <tr>
                  <th style="width:5%">#</th>
                  <th>User Name</th>
                  <th>User Email</th>
                  <th>Asset Name</th>
                  <th>Form Name</th>
                  <th>Submited On </th>
                  <th style="width:22%">Actions</th>
                </tr>
              </thead>
              <tbody>
                @if($participantResponse != null)
                  @foreach($participantResponse as $pResponse)
                  <?php $serial = $loop->index + 1; ?>
                  <tr>
                    <td>{{ $serial }}</td>
                    <td><strong class="visible-xs">User Name</strong>{{ $pResponse->user->name }}</td>
                    <td><strong class="visible-xs">User email</strong>{{ $pResponse->user->email }}</td>
                    <td><strong class="visible-xs">Asset Name</strong>{{ $pResponse->assets->name }}</td>
                    <td><strong class="visible-xs">Form Name</strong>{{ $pResponse->form->name }}</td>
                    <td><strong class="visible-xs">Project Overview Name</strong>{{ getDates($pResponse->created_at) }}</td>
                    <td>
                      <storng class="visible-xs">Actions</storng>
                      <a href='#' class='more' type='button' id='dropdownMenuButton' data-toggle='dropdown'><i data-toggle='tooltip' title='More Action' class='fa fa-list-ul'></i></a>

                      <div class='dropdown-menu dropdown-menu-custom' aria-labelledby='dropdownMenuButton'> 
                        <a target="_blank" href="{{URL::to('participant') }}/{{nxb_encode($pResponse->id)}}/response/readonly" class="dropdown-item">View Details</a>
                        <a href="#myModal" data-id="{{$pResponse->id}}" data-projectovid="{{$projectoverview_id}}"  data-assetname="{{$pResponse->assets->name}}" data-formname="{{$pResponse->form->name}}" data-email="{{$pResponse->user->email}}" data-toggle="modal" class="dropdown-item proposal-conclusion">Proposal Conclusion</a>
                        <a target="_blank" href="{{URL::to('master-template') }}/{{nxb_encode($pResponse->id)}}/project-overview/email/sended" class="dropdown-item">View Emails</a>
                      </div>
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

        <!-- Tab containing all the data tables -->
    
        <!-- Modal -->
                                 <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="myModal" class="modal fade" style="display: none;">
                                     <div class="modal-dialog">
                                         <div class="modal-content">
                                             <div class="modal-header">
                                                 <h4 class="modal-title">Compose</h4>
                                                 <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                                             </div>
                                             <div class="modal-body">
                                                   {!! Form::open(['route' => 'send-mail-for-Project', 'files' => true,'class'=>"form-horizontal"]) !!}

                                                     <div class="form-group">
                                                         <!-- <label class="col-lg-2 control-label">To</label> -->
                                                         <input type="hidden" name="reponse_id" class="responseid" value="">
                                                         <input type="hidden" name="projectov_id" class="projectovid" value="">
                                                         <input type="hidden" name="asset_id" class="assetid" value="">
                                                         <input type="hidden" name="form_id" class="formid" value="">
                                                         <div class="col-lg-12">
                                                             <input name="model_to" type="text" placeholder="To" id="inputEmail1" class="form-control model-to" readonly>
                                                         </div>
                                                     </div>
                                                     <div class="form-group adding-extras ">
                                                         <!-- <label class="col-lg-2 control-label">Cc / Bcc</label> -->
                                                         <div class="container-extra row col-md-12">
                                                           <div class="col-md-11">
                                                               <input name="model_cc_bcc[]" type="email" placeholder="CC" class="form-control">
                                                           </div>
                                                           <div class="col-md-1">
                                                             <button type="button" class="btn btn-xs add-cc-bcc"><span class="fa fa-plus"></span></button>
                                                          </div>
                                                        </div>
                                                     </div>
                                                     <div class="form-group">
                                                         <!-- <label class="col-lg-2 control-label">Subject</label> -->
                                                         <div class="col-lg-12">
                                                             <input name="model_subject" required type="text" placeholder="Subject"  class="form-control">
                                                         </div>
                                                     </div>
                                                     <div class="form-group">
                                                         <!-- <label class="col-lg-2 control-label">Message</label> -->
                                                         <div class="col-lg-12">
                                                             <textarea name="model_body" rows="10" cols="60" class="form-control" class="bodymodal" id="texteditor" placeholder="Enter Message"></textarea>
                                                         </div>
                                                     </div>

                                                     <div class="form-group">
                                                         <div class="col-lg-10">
                                                             <span class="btn btn-small btn-success green fileinput-button" >
                                                               <i class="fa fa-plus fa fa-white"></i>
                                                               <span>Attachment</span>
                                                               <input name="uplaod_attachment[]" class="uploadedfiles" type="file" multiple="multiple" style="opacity: 0;margin-left: -119px;width: 120px;">
                                                             </span>

                                                             <button class="btn btn-send" type="submit">Send</button>
                                                         </div>
                                                         <div class="col-lg-10">
                                                            <div class="upload_prev"></div>
                                                         </div>
                                                     </div>
                                                {!! Form::close() !!}
                                             </div>
                                         </div><!-- /.modal-content -->
                                     </div><!-- /.modal-dialog -->
                                 </div><!-- /.modal -->

@endsection
@section('footer-bootstrap-Overridescripts')
   <script></script>
@endsection
@section('footer-scripts')
    <script src="{{ asset('/js/vender/tinymce/tinymce.min.js') }}"></script>
    <script>tinymce.init({ selector:'textarea',height : "310" });</script>
    <script src="{{ asset('/js/vender/plugins/dropzone.js') }}"></script>
    <script src="{{ asset('/js/proposal-conclusion.js') }}"></script>
    <script src="{{ asset('/js/ajax-dynamic-assets-table.js') }}"></script>
    @include('layouts.partials.datatable')
@endsection
