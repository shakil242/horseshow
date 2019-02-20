<div id="ProfilesModal{{$pResponse->id}}" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="model-titles">Profiles</h4>
      </div>
      <div class="modal-body">
            <div class="app-actions" style="width:100%;display:block">
                
                    <div class="row"> 
                    <?php $Profiler_Forms = getFormsForProfile($template_id,2) ?>
                            @if($Profiler_Forms->count())
                                @foreach($Profiler_Forms as $form)
                                <div class="col-lg-3 col-md-4 col-sm-6">
                                    <a class="app-action-link"
                                       href="{{URL::to('settings') }}/{{nxb_encode($form->id)}}/2/{{$pResponse->user_id}}/view">{{$form->name}}</a>
                                </div>
                                @endforeach
                            @else
                                <div class="col-md-8"> No Form attached for this application</div>
                            @endif
                    </div>
               
            </div>
    </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>