<div id="ProfilesModal{{$pResponse->id}}" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title" id="exampleModalLabel">Profiles</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>

      <div class="modal-body">
            <div class="app-actions" style="width:100%;display:block">
                @if($pResponse->template->status == 1)
                    <div class="row"> 
                    <?php $Profiler_Forms = getFormsForProfile($pResponse->template_id,1) ?>
                            @if($Profiler_Forms->count())
                                @foreach($Profiler_Forms as $form)
                                <div class="col-lg-3 col-md-4 col-sm-6">
                                    <a class="app-action-link" target="_blank"
                                       href="{{URL::to('settings') }}/{{nxb_encode($form->id)}}/2/{{$pResponse->user_id}}/view">{{$form->name}}</a>
                                </div>
                                @endforeach
                            @else
                                <div class="col-md-8"> No Form attached for this application</div>
                            @endif
                    </div>
                @else
                    <div class="row">
                        <div class="col-lg-5 col-md-5 col-sm-6">You have to accept the request to
                            proceed with this form!
                        </div>
                    </div>
                @endif
            </div>
    </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>


    </div>

  </div>
</div>