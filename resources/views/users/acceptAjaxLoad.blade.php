    @if((!$participant_collection->isEmpty()))
        @foreach($participant_collection as $app)
            <li class="Smodule">
                <div class="app-info">
                    <h4 class="app-name">
                        <span class="span-one-listing">App Name</span><em class="em-one-listing"><a href="#" data-toggle="modal" data-target="#ProfilesModal{{$app->id}}" >{{GetTemplateName($app->template_id,$app->invitee_id)}} </a></em>
                    </h4>
                    @if($app->hastemplate->category ==SHOW)
                        <h4 class="app-name"><span class="span-one-listing">Class </span><em class="em-one-listing">{{GetAssetNamefromId($app->asset_id)}}</em>
                        </h4>
                    @else
                        <h4 class="app-name"><span class="span-one-listing">Asset </span><em class="em-one-listing">{{GetAssetNamefromId($app->asset_id)}}</em>
                        </h4>
                    @endif

                    <h4 class="app-name">
                        <span class="span-one-listing"> Invited By</span><em class="em-one-listing">{{getUserNamefromid($app->invitee_id)}}</em></h4>
                    <h4 class="app-status"><span>Status</span><em>{{EmailStatus($app->status)}}</em></h4>
                    <h4 class="app-created">
                        @if($app->hastemplate->category ==SHOW)
                            <span class="span-one-listing">Date Registered</span><em class="em-one-listing">{{getDates($app->created_at)}}</em></h4>
                    @else
                        <span class="span-one-listing">Invited on App</span><em class="em-one-listing">{{getDates($app->created_at)}}</em></h4>
                    @endif
                    @if(isset($app->show->title))
                        <h4 class="app-status"><span class="span-one-listing">Show</span><em class="em-one-listing">{{$app->show->title}}</em></h4>
                    @endif

                    @if(!is_null($app->accepted_request_time))
                        <h4 class="app-created">
                            <span class="span-one-listing">Accepted on</span><em class="em-one-listing">{{getDates($app->accepted_request_time->toDateTimeString())}}</em></h4>
                    @endif
                    @if($app->status==2)
                        <h4 class="app-created">
                            <span>Decline on</span><em>{{getDates($app->updated_at->toDateTimeString())}}</em></h4>
                    @endif
                <!-- condition is to check if is show -->


                    @if($app->hastemplate->category ==SHOW)
                        @if($app->showRegistration != null)
                            <h4 class="app-status trainer-div"><span class="span-horse">Trainer</span><em class="em-horse">@if($app->showRegistration->trainer_id != 0) {{getTrainerFromId($app->showRegistration->trainer_id)}} <a href="javascript:" class="change-trainer" onclick="getShowTrainers('{{nxb_encode($app->show_id)}}','{{nxb_encode($app->showRegistration->id)}}',this)"> (Change)</a>@else <a href="javascript:" class="change-trainer" onclick="getShowTrainers('{{nxb_encode($app->show_id)}}','{{nxb_encode($app->showRegistration->id)}}',this)"> (Add)</a> @endif</em> </h4>
                            <br>
                        @endif
                    @endif

                    @if(getHorseNames($app->id ,$app->asset_id) != "No Horse Added")
                        <h4 class="app-status"><span class="span-horse">Horses</span><em class="em-horse">{!! getHorseNames($app->id ,$app->asset_id,1) !!}</em></h4>
                        <br>
                    @endif

                    @if($app->allowed_time =='unlimited')
                        <h4 class="app-name"><span class="span-one-listing">Allowed Submittion</span><em class="em-one-listing">Unlimited</em></h4>
                        <br>
                    @else
                        <h4 class="app-name"><span class="span-one-listing">Allowed Submittion</span><em class="em-one-listing">{{FormPermissionCheck($app->allowed_time,$app->id,$app->forms_attached)}}</em></h4>
                        <br>
                    @endif

                </div>
                @include('setting.modal')
                <div class="app-actions">
                    @if($app->status == 1)
                        @if($app->block == 1)
                            <div class="row">
                                <div class="col-lg-8 col-md-8 col-sm-8">You have been blocked by App Owner.
                                    Contact App owner to proceed with this applicaton!
                                </div>
                            </div>
                        @else
                            <?php $ia_fields = getButtonLabelFromTemplateId($app->template_id,'ia_fields'); ?>
                            <div class="row">
                                <div class="col-lg-3 col-md-4 col-sm-6">
                                    <a class="app-action-link"
                                       href="{{URL::to('master-template') }}/{{nxb_encode($app->id)}}/assets/details">{{post_value_or($ia_fields,'view_details','View Details')}}</a>
                                </div>
                                <div class="col-lg-3 col-md-4 col-sm-6">
                                    <a class="app-action-link"
                                       href="{{URL::to('master-template') }}/{{nxb_encode($app->template_id)}}/{{nxb_encode($app->id)}}/modules/launch/{{nxb_encode($app->asset_id)}}/{{$app->invite_asociated_key}}">{{post_value_or($ia_fields,'view_app','View App')}}</a>
                                </div>
                                <div class="col-lg-3 col-md-4 col-sm-6">
                                    <a class="app-action-link"
                                       href="{{URL::to('master-template') }}/{{nxb_encode($app->asset_id)}}/asset/readonly">{{post_value_or($ia_fields,'view_assets','View Assets')}}</a>
                                </div>
                                <div class="col-lg-3 col-md-4 col-sm-6">
                                    <a class="app-action-link"
                                       href="{{URL::to('participant') }}/{{nxb_encode($app->asset_id)}}/history/assets">{{post_value_or($ia_fields,'my_response_history','My Response History')}}</a>
                                </div>
                                <div class="col-lg-3 col-md-4 col-sm-6">
                                    <a class="app-action-link"
                                       href="{{URL::to('participant') }}/{{nxb_encode($app->id)}}/attached/history/assets">{{post_value_or($ia_fields,'asset_history','Asset History')}}</a>
                                </div>
                                <div class="col-lg-3 col-md-4 col-sm-6">
                                    <a class="app-action-link"
                                       href="{{URL::to('ranking') }}/{{nxb_encode($app->id)}}/participant/ranking">{{post_value_or($ia_fields,'ranking','Ranking')}}</a>
                                </div>

                                <div class="col-lg-3 col-md-4 col-sm-6">

                                    <?php $templateType = GetTemplateType($app->template_id); ?>
                                    @if($templateType==FACILTY)
                                        <a class="app-action-link invite-users" href="{{URL::to('master-template') }}/participant/secondaryScheduler/{{nxb_encode($app->template_id)}}/{{nxb_encode($app->show_id)}}/{{$app->invite_asociated_key}}/0">{{post_value_or($ia_fields,'manage_scheduler','Manage Scheduler')}}</a>
                                    @else
                                        @if($app->participantAsset($app->template_id,$app->invite_asociated_key) > 0)
                                            <a class="app-action-link invite-users" href="{{URL::to('master-template') }}/participant/scheduler/{{nxb_encode($app->template_id)}}/{{ nxb_encode($app->asset_id) }}/{{$app->invite_asociated_key}}/{{nxb_encode($app->show_id)}}/0">{{post_value_or($ia_fields,'manage_scheduler','Manage Scheduler')}}</a>
                                        @endif
                                    @endif
                                </div>
                                @if($app->participantFeedBackExist($app->asset_id) > 0)
                                    <div class="col-lg-3 col-md-4 col-sm-6">
                                        <a class="app-action-link"
                                           href="{{URL::to('participant') }}/{{nxb_encode($app->asset_id)}}/participant/getFeedBack">{{post_value_or($ia_fields,'feedback','Feedback')}}</a>
                                    </div>
                                @endif

                                @if($app->invoiceExist($app->invite_asociated_key,$app->asset_id) > 0)

                                    <?php //$templateType = GetTemplateType($app->template_id); ?>
                                    @if($app->hastemplate->category !=SHOW)
                                    <div class="col-lg-3 col-md-4 col-sm-6">
                                        <a class="app-action-link"
                                           href="{{URL::to('participant') }}/{{nxb_encode($app->id)}}/{{nxb_encode($app->asset_id)}}/invoice/listing/{{$app->invite_asociated_key}}">{{post_value_or($ia_fields,'invoice','Invoices')}}</a>
                                    </div>
                                    @endif
                                    @endif

                                    @if($app->isFormSubmitted($app->id) == 0)
                                    <div class="col-lg-3 col-md-4 col-sm-6">
                                        <a class="app-action-link"
                                           href="{{URL::to('participant/response/decline/') }}/{{ nxb_encode($app->id) }}/{{ nxb_encode($app->asset_id) }}">{{post_value_or($ia_fields,'decline','Decline')}}</a>
                                    </div>
                                    @endif
                                    <div class="col-lg-3 col-md-4 col-sm-6">
                                        <a class="app-action-link"
                                           href="{{URL::to('master-template') }}/{{nxb_encode($app->id)}}/sub-participants/invite">{{post_value_or($ia_fields,'sub_participants','Invite Sub-Participant')}}</a>
                                    </div>
                                    <div class="col-lg-3 col-md-4 col-sm-6">
                                        <a class="app-action-link"
                                           href="{{URL::to('master-template') }}/{{nxb_encode($app->id)}}/sub-participants/response">{{post_value_or($ia_fields,'sub_participants','View Sub-Participants Response')}}</a>
                                    </div>
                                    @if($app->manage_show_reg_id != 0)
                                    <?php //$templateType = GetTemplateType($app->template_id); ?>
                                    @if($app->hastemplate->category ==SHOW)
                                    <div class="col-lg-3 col-md-4 col-sm-6">
                                        <a class="app-action-link"
                                           href="{{URL::to('shows') }}/{{nxb_encode($app->manage_show_reg_id)}}/{{nxb_encode($app->id)}}/pay/invoice">{{post_value_or($ia_fields,'show_invoice','Show Invoice')}}</a>
                                    </div>
                                    @endif
                                    @endif
                                    <!-- Show Profile settings -->
                                    <?php $Profiler_Forms = getFormsForProfile($app->template_id,PROFILE_APP_NORMAL_USER,json_decode($app->invited_profiles)) ?>
                                    @if($Profiler_Forms->count())
                                        @foreach($Profiler_Forms as $form)
                                            <div class="col-lg-3 col-md-4 col-sm-6">
                                                <a class="app-action-link"
                                                   href="{{URL::to('settings') }}/{{nxb_encode($form->id)}}/2/profile/view">{{$form->name}}</a>
                                            </div>
                                        @endforeach
                                    @endif
                            </div>
                        @endif
                    @elseif($app->status == 0)
                        <div class="row">
                            <div class="col-lg-8 col-md-8 col-sm-8">
                                <div class="col-lg-3 col-md-4 col-sm-6">
                                    <a class="app-action-link"
                                       href="{{URL::to('master-template') }}/{{nxb_encode($app->id)}}/assets/details">View
                                        Details</a>
                                </div>
                                <div class="col-lg-3 col-md-4 col-sm-6">
                                    <?php
                                    $penalty = 0;

                                    if($app->is_penalty==1)
                                    {
                                    if($app->checkPenaltyDate($app->penalty_date) == 1)
                                    $penalty = 1;
                                    }
                                    ?>

                                    <a href="javascript:"
                                       onclick="acceptRequest('{{nxb_encode($app->template_id)}}','{{$app->invite_asociated_key}}','{{nxb_encode($app->id)}}','{{nxb_encode($app->asset_id)}}','1','{{$penalty}}')" class="app-action-link">Accept</a>
                                </div>
                                <div class="col-lg-3 col-md-4 col-sm-6">
                                    <a href="javascript:" onclick="acceptRequest('{{nxb_encode($app->template_id)}}','{{$app->invite_asociated_key}}','{{nxb_encode($app->id)}}','{{nxb_encode($app->asset_id)}}','2','{{$penalty}}')" class="app-action-link">Decline</a>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="row">
                            <div class="col-lg-5 col-md-5 col-sm-6">You have to accept the request to
                                proceed with this form!
                            </div>
                        </div>
                    @endif
                </div>
            </li>
        @endforeach
    @else
        <li>
            <div class="col-lg-5 col-md-5 col-sm-6">{{MASTER_TEMPLATE_NO_ASSET_INVITE_TEXT}}</div>
        </li>
    @endif