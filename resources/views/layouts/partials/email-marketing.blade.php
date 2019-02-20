    <!-- Modal -->
    @if(isset($idx))
    <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="myModal{{$idx}}" class="modal fade" style="display: none;">
    @else
    <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="myModal" class="modal fade" style="display: none;">

    <script src="{{ asset('/js/vender/tinymce/tinymce.min.js') }}"></script>
    <script>tinymce.init({ selector:'textarea' });</script>
    <script src="{{ asset('/js/marketing-email.js') }}"></script>
    @endif
    <div class="modal-dialog">

    <div class="modal-content">
     <div class="modal-header">
         <h4 class="modal-title">Compose</h4>
         <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
     </div>
     <div class="modal-body">
           {!! Form::open(['route' => 'send-marketing-email', 'files' => true,'class'=>"form-horizontal"]) !!}

             <div class="form-group adding-extras">
                 <!-- <label class="col-lg-2 control-label">Cc / Bcc</label> -->
                 @if(isset($emailUsers))
                @php $emailResult = array_unique($emailUsers); @endphp
                @foreach($emailResult as $key => $emails)
                 <div class="row col-lg-12 container-extra">
                   <div class="col-lg-11">
                       <input name="model_to[]" required="required" type="email" value="{{$emails}}" placeholder="To" class="form-control">
                   </div>
                   <div class="col-lg-1">
                    @if($key == 0)
                        <button type="button" class="btn btn-xs add-cc-bcc"><span class="glyphicon glyphicon-plus"></span></button>
                    @else
                        <button type="button" class="btn btn-xs remove-cc-bcc"><span class="fa fa-minus"></span></button>
                    @endif
                  </div>
                </div>
                @endforeach
                @endif

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
