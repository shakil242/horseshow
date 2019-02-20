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
          $title = "Email History";
          $added_subtitle = "";
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
                  @if(isset($displayAll))
                  <th>Email To</th>
                  <th>Project</th>
                  @endif
                  <th>Sent On </th>
                  <th>Subject</th>
                  <th>CC </th>
                  <th style="width:35%">Email Body</th>
                  <th>Attachment</th>
                </tr>
              </thead>
              <tbody>
                @if($participantResponse != null)
                  @foreach($participantResponse as $pResponse)
                  <?php $serial = $loop->index + 1; ?>
                  <tr>
                    <td>{{ $serial }}</td>
                    @if(isset($displayAll))
                    <td><strong class="visible-xs">Email To</strong>{{$pResponse->email_to}}</td>
                    <td><strong class="visible-xs">Project</strong>{{GetAssetNamefromId($pResponse->projectovs_id)}}</td>
                    @endif
                    <td><strong class="visible-xs">Sent On</strong>{{ getDates($pResponse->created_at) }}</td>
                    <td><strong class="visible-xs">Subject</strong>{{ $pResponse->email_subject }}</td>
                    <td><strong class="visible-xs">CC </strong>@php $cc = json_decode($pResponse->email_cc) @endphp
                      @if (!empty(array_non_empty_items($cc)))
                        @foreach($cc as $email)
                          <p>{{$email}}</p>
                        @endforeach
                      @else
                        Dont Have CC.
                      @endif
                    </td>
                    <td><strong class="visible-xs">Form Name</strong>{!! json_decode($pResponse->email_body) !!}</td>
                    <td><strong class="visible-xs">Attachment</strong>
                      @php
                        $emailattache = json_decode($pResponse->email_attachment);
                        $disk = getStorageDisk();
                      @endphp
                      @if(count($emailattache)>0)
                        @foreach($emailattache as $attachment)
                          @php $answer =$attachment->path;  @endphp
                            <p><a href="{{$disk->url($answer)}}"> {{$attachment->name}} </a></p>
                        @endforeach
                      @endif
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
        <!-- ./ Content Panel -->  
        </div>
    </div>
</div>

<!-- ================= ./ CONTENT AREA ================ -->
        
        <!-- Tab containing all the data tables -->
        <script src="{{ asset('/js/vender/tinymce/tinymce.min.js') }}"></script>
        <script>tinymce.init({ selector:'textarea' });</script>
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
                                 <div class="col-lg-12">
                                     <input name="model_to" type="text" placeholder="To" id="inputEmail1" class="form-control model-to" readonly>
                                 </div>
                             </div>
                             <div class="form-group adding-extras">
                                 <!-- <label class="col-lg-2 control-label">Cc / Bcc</label> -->
                                 <div class="container-extra">
                                   <div class="col-lg-11">
                                       <input name="model_cc_bcc[]" type="email" placeholder="CC" class="form-control">
                                   </div>
                                   <div class="col-lg-1">
                                     <button type="button" class="btn btn-xs add-cc-bcc"><span class="glyphicon glyphicon-plus"></span></button>
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
                                     <textarea name="model_body" rows="10" cols="60" class="form-control" placeholder="Enter Message"></textarea>
                                 </div>
                             </div>

                             <div class="form-group">
                                 <div class="col-lg-10">
                                     <span class="btn btn-small btn-success green fileinput-button">
                                       <i class="fa fa-plus fa fa-white"></i>
                                       <span>Attachment</span>
                                       <input name="uplaod_attachment[]" class="uploadedfiles" type="file" multiple="multiple">
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

<script src="{{ asset('/js/vender/plugins/dropzone.js') }}"></script>
    <script src="{{ asset('/js/proposal-conclusion.js') }}"></script>
    <script src="{{ asset('/js/ajax-dynamic-assets-table.js') }}"></script>
    @include('layouts.partials.datatable')
@endsection
