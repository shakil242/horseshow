<div class="row">
    <?php $feedBack_Forms = getFeedBackForFacility($template_id) ?>
    @if($feedBack_Forms->count())
        @foreach($feedBack_Forms as $form)
            <div class="col-lg-3 col-md-4 col-sm-6">
                <a style="background: #28a0e5;color: #FFF;" target="_blank" href="{{URL::to('master-template')}}/schedular/faciltyFeedBack/{{nxb_encode($template_id)}}/{{nxb_encode($form->id)}}/{{$id}}" class="app-action-link feeds">{{$form->name}}</a>
            </div>
        @endforeach
    @endif
</div>
