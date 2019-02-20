<script src="{{ asset('/js/vender/bootstrap3-typeahead.min.js') }}"></script>
<script>
    $('#typeahead-activity').typeahead( {
        source: function( query, process ) {
            $.get("/ajax-request/getActivityData", {
                    query: query
                },
                function( data ) {
//                console.log(data);
                    process(data);
                } );
        },
        afterSelect: function (item) {
            //console.log(item);
            getActivityView(item.participant_id,1,item.id)
        }
    } );


</script>

@if($app)
    @if($app->hastemplate->category ==TRAINER)
        <style>
            .HorseScratch{
                display:none;
            }

        </style>
@endif
@endif
<div class="page-menu">
    <div class="row justify-content-between collapse-box menu-holder">
        <div class="d-flex flex-nowrap left-panel">
                <span  class="menu-icon" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
                    <img src="{{ asset('/img/icons/icon-menu.svg') }}" />
                </span>
            @if($app)
                <h1 class="title flex-shrink-1">
                        <span @if(checkAllScratch($app->asset_id,$app->id)) class="scratched-horses-class" @endif>
                            {{GetAssetNamefromId($app->asset_id)}}
                        </span>

                    @if($app->hastemplate->category!=SHOW)
                        <small>{{$app->hastemplate->name}}</small>
                    @else
                        <small>{{$app->show->title}}</small>
                        <small>{{$app->showRegistration->created_at->format('m-d-Y')}}</small>
                    @endif

                </h1>
            @endif

        </div>



        <div class="right-panel">
            <div class="desktop-view">

                <form class="form-inline justify-content-end">

            <a href="{{URL::to('shows') }}/home/invoicing" class="btn btn-sm btn-primary btn-rounded mr-10">Invoice</a>
            <div class="search-field mr-10">
                <div class="input-group">
                    <input  id="typeahead-activity" class="form-control" placeholder="" aria-label="Username" aria-describedby="basic-addon1" type="text">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1"><img src="{{asset('img/icons/icon-search.svg')}}"></span>
                    </div>
                </div>
            </div>
                </form>
        </div>
            <div class="mobile-view">
                        <span class="menu-icon mr-15" data-toggle="collapse" href="#collapseMoreAction" role="button" aria-expanded="false" aria-controls="collapseMoreAction">
<!--                            <i class="fa fa-expand"></i>-->
                            <i class="fa fa-navicon"></i>
                        </span>

                <div class="collapse navbar-collapse-responsive navbar-collapse justify-content-end" id="navbarSupportedContent">
                    <form class="form-inline justify-content-end">
                        <a href="{{URL::to('shows') }}/home/invoicing" class="btn btn-sm btn-primary btn-rounded mr-10" type="button">Invoice</a>

                        <div class="search-field">
                            <div class="input-group">
                                <input  id="typeahead-activity" class="form-control" placeholder="" aria-label="Username" aria-describedby="basic-addon1" type="text">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1"><img src="{{asset('img/icons/icon-search.svg')}}"></span>
                                </div>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
        @if($appCollection->count()>0)
        <div class="collapse menu-box" id="collapseExample">
                        <span class="close-menu" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
                            <img src="{{ asset('/img/icons/icon-close.svg')}}" />
                        </span>
                <div class="menu-links">
                    <div class="row"  id="load-data">
                        <!-- col-md-3  -->
                        <div class="col-md-3" >
                            <ul class="nav flex-column" >

                                @foreach($appCollection as $row)
                                    <li class="nav-item">
                                            <a  class="nav-link {{($app->id==$row->id)?'active':''}}" onclick="getActivityView('{{$row->id}}','1','{{$row->asset_id}}')" href="javascript:">
                                                <span @if(checkAllScratch($row->asset_id,$row->id)) class="scratched-horses-class" @endif>{{GetAssetNamefromId($row->asset_id)}}</span>
                                            @if($row->hastemplate->category==SHOW)
                                                <small>{{$row->show->title}}</small>
                                                <small>{{$row->showRegistration->created_at->format('m-d-Y')}}</small>
                                            @else
                                                <small>{{$row->hastemplate->name}}</small>
                                            @endif
                                        </a>
                                        <a class="setting" href="#"><i class="fa fa-gear"></i></a>
                                    </li>

                                    @if($loop->iteration % 5 ==0)
                            </ul>
                        </div>
                        <div class="col-md-3">
                            <ul class="nav flex-column">
                                @endif
                                @endforeach
                            </ul>

                        </div>
                    <div class="col-md-12">
                    <div class="d-flex justify-content-center"  id="remove-row">
                        <button id="btn-more" data-id="{{$row->id}}"  class="btn btn-rounded btn-secondary btn-sm"> Load More </button>
                    </div>
                    </div>
                </div>
                </div>
            </div>
        @endif

    </div>
</div>
<div class="white-board">
    @if($app)
    <div class="row">
        <!-- Left Panel -->

        @if($app->hastemplate->category ==SHOW || $app->hastemplate->category ==TRAINER)
        <div class="col-md-3 contetn-aside Smodule">
            <div class="row">
                <div class="col-lg-12 detail-area">
                    <span class="label">Date Registered</span> {{getDates($app->created_at)}}
                </div>
                <div class="col-lg-12 detail-area">
                    <span class="label">Class</span> {{GetAssetNamefromId($app->asset_id)}}
                </div>


                @if(isset($app->show->title))
                    @if($app->show->type != SHOW_TYPE_TRAINER)
                <div class="col-lg-12 detail-area">
                    <span class="label">Show</span>
                    <a class="text-secondary underline" href="#">{{$app->show->title}}</a>
                </div>
                    @else
                <div class="col-lg-12 detail-area">
                    <span class="label">Trainer</span>
                    <a class="text-secondary underline" href="#">{{$app->show->title}}</a>
                </div>
                   @endif
                @endif

                @if($app->hastemplate->category ==SHOW)
                    @if($app->showRegistration != null)

                <div class="col-lg-12 detail-area">
                    <span class="label">Trainer <a class="text-secondary hover-display"  onclick="getShowTrainers('{{nxb_encode($app->show_id)}}','{{nxb_encode($app->showRegistration->id)}}',this)" href="javascript:"><i class="fa fa-edit"></i></a></span>
                    @if($app->showRegistration->trainer_id != 0)  {{getTrainerFromId($app->showRegistration->trainer_id)}} @endif
                <div class="trainer-div"></div>
                </div>
                    @endif
                @endif
             @if(getHorseNames($app->id ,$app->asset_id) != "No Horse Added")
                <div class="col-lg-12 detail-area">
                    <span class="label">Horses</span>
                    <span class="block">
                         <label for="val1"><a class="text-secondary underline text-disabled" href="#">{!! getHorseNames($app->id ,$app->asset_id,1) !!}</a></label></span>
                </div>
              @endif
            @if(getRiderWithHorse($app->id ,$app->asset_id,1) != "No Rider Added")
                <div class="col-lg-12 detail-area">
                <span class="label">Rider</span>
                <span class="block"><a class="text-secondary underline" href="#">{!! getRiderWithHorse($app->id ,$app->asset_id,1) !!}</a></span>
                </div>
            @endif

            </div>
        </div>
        @else
            <div class="col-md-3 contetn-aside Smodule">
                <div class="row">
                    <div class="col-lg-12 detail-area">
                        <span class="label">App Name</span>  <a class="text-secondary underline" href="#"  data-toggle="modal" data-target="#ProfilesModal{{$app->id}}">{{GetTemplateName($app->template_id,$app->invitee_id)}}</a>
                    </div>
                    <div class="col-lg-12 detail-area">
                        <span class="label">Asset</span>  {{GetAssetNamefromId($app->asset_id)}}
                    </div>

                            <div class="col-lg-12 detail-area">
                                <span class="label">Invited By</span>
                                <a class="text-secondary underline" href="#">{{getUserNamefromid($app->invitee_id)}}</a>
                            </div>
                    <div class="col-lg-12 detail-area">
                        <span class="label">Status</span>
                        {{EmailStatus($app->status)}}
                    </div>
                    <div class="col-lg-12 detail-area">
                        <span class="label">Invited on App</span>
                        {{getDates($app->created_at)}}
                    </div>

                    @if(!is_null($app->accepted_request_time))
                        <div class="col-lg-12 detail-area">
                            <span class="label">Accepted on</span>
                            {{getDates($app->accepted_request_time->toDateTimeString())}}
                        </div>
                    @endif
                    @if($app->status==2)
                        <div class="col-lg-12 detail-area">
                            <span class="label">Decline on</span>
                            {{getDates($app->updated_at->toDateTimeString())}}
                        </div>
                    @endif
                    @if($app->allowed_time =='unlimited')
                        <div class="col-lg-12 detail-area">
                            <span class="label">Allowed Submittion</span>
                            Unlimited
                        </div>
                    @else
                        <div class="col-lg-12 detail-area">
                            <span class="label">Allowed Submittion</span>
                            {{FormPermissionCheck($app->allowed_time,$app->id,$app->forms_attached)}}
                        </div>

                    @endif

                </div>
            </div>
        @endif
                @include('setting.modal')

        <!-- ./ Left Panel -->

        <!-- Right Panel -->
        <div class="col-md-9 card-small-area">
            @php $ia_fields = getButtonLabelFromTemplateId($app->template_id,'ia_fields'); @endphp

        @if($app->status == 1)
            @if($app->block == 1)
                    <div class="row">
                        <div class="col-lg-8 col-md-8 col-sm-8">You have been blocked by App Owner.
                            Contact App owner to proceed with this applicaton!
                        </div>
                    </div>
            @else
            <!-- Feedback & Class Info -->
            <h4>Feedback & Class Info</h4>
            <div class="cards-holder pb-25">
                @if($app->hastemplate->category ==FACILTY)
                <div class="item">
                    <a href="{{URL::to('master-template') }}/{{nxb_encode($app->id)}}/assets/details" class="card-widget">
                        <figure class="icons-holder"><i class="fa fa-eye"></i></figure>
                        <div class="desc">
                            <h5> {{post_value_or($ia_fields,'view_details','View Details')}}</h5>
                            <p>{{str_limit(post_description_or($ia_fields ,'view_details_description'), $limit = 55, $end = '...')}}</p>
                        </div>
                        <div class="info">
                            {!!  str_limit(post_description_or($ia_fields,'view_details_description'), $limit = 90, $end = '<div class="tooltipTitle">&nbspView More ..&nbsp <span class="tooltiptext">'.post_description_or($ia_fields,'view_details_description').'</span></div>') !!}<b><u>GO</u></b>

                        </div>
                    </a>
                </div>

                 <div class="item">
                    <a href="{{URL::to('master-template') }}/{{nxb_encode($app->id)}}/manage/project-overview/history" class="card-widget">
                        <figure class="icons-holder"><i class="fa fa-eye"></i></figure>
                        <div class="desc">
                            <h5> {{post_value_or($ia_fields,'project_overview_history','Project Overview')}}</h5>
                            <p>{{str_limit(post_description_or($ia_fields ,'project_overview_history'), $limit = 55, $end = '...')}}</p>
                        </div>
                        <div class="info">
                            {!!  str_limit(post_description_or($ia_fields,'project_overview_history'), $limit = 90, $end = '<div class="tooltipTitle">&nbspView More ..&nbsp <span class="tooltiptext">'.post_description_or($ia_fields,'project_overview_history').'</span></div>') !!}<b><u>GO</u></b>

                        </div>
                    </a>
                </div>

                    <div class="item">
                        <a href="{{URL::to('participant') }}/{{nxb_encode($app->id)}}/attached/history/assets" class="card-widget">
                            <figure class="icons-holder"><i class="fa fa-calendar"></i></figure>
                            <div class="desc">
                                <h5>{{post_value_or($ia_fields,'asset_history','Asset History')}}</h5>
                                <p>{{str_limit(post_description_or($ia_fields ,'asset_history_description'), $limit = 55, $end = '...')}}</p>
                            </div>
                            <div class="info">
                                <!-- <span herf="#" class="more">click to view more details</span> -->
                                {!!  str_limit(post_description_or($ia_fields,'asset_history_description'), $limit = 90, $end = '<div class="tooltipTitle">&nbspView More ..&nbsp <span class="tooltiptext">'.post_description_or($ia_fields,'asset_history_description').'</span></div>') !!}<b><u>GO</u></b>

                            </div>
                        </a>
                    </div>

                @endif
                <div class="item">
                    <a href="{{URL::to('master-template') }}/{{nxb_encode($app->template_id)}}/{{nxb_encode($app->id)}}/modules/launch/{{nxb_encode($app->asset_id)}}/{{$app->invite_asociated_key}}" class="card-widget">
                        <figure class="icons-holder"><i class="fa fa-eye"></i></figure>
                        <div class="desc">
                            <h5> {{post_value_or($ia_fields,'view_app','View App')}}</h5>
                            <p>{{str_limit(post_description_or($ia_fields ,'view_app_description'), $limit = 55, $end = '...')}}</p>
                        </div>
                        <div class="info">
                            {!!  str_limit(post_description_or($ia_fields,'view_app_description'), $limit = 90, $end = '<div class="tooltipTitle">&nbspView More ..&nbsp <span class="tooltiptext">'.post_description_or($ia_fields,'view_app_description').'</span></div>') !!}<b><u>GO</u></b>

                        </div>
                    </a>
                </div>
                <div class="item">
                    <a href="{{URL::to('master-template') }}/{{nxb_encode($app->asset_id)}}/asset/readonly" class="card-widget">
                        <figure class="icons-holder"><i class="fa fa-eye"></i></figure>
                        <div class="desc">
                            <h5>{{post_value_or($ia_fields,'view_assets','View Assets')}}</h5>
                            <p>{{str_limit(post_description_or($ia_fields ,'view_assets_description'), $limit = 55, $end = '...')}}</p>
                        </div>
                        <div class="info">
                            {!!  str_limit(post_description_or($ia_fields,'view_assets_description'), $limit = 90, $end = '<div class="tooltipTitle">&nbspView More ..&nbsp <span class="tooltiptext">'.post_description_or($ia_fields,'view_assets_description').'</span></div>') !!}<b><u>GO</u></b>

                        </div>
                    </a>
                </div>
                <div class="item">
                    <a href="{{URL::to('participant') }}/{{nxb_encode($app->asset_id)}}/history/assets" class="card-widget">
                        <figure class="icons-holder"><i class="fa fa-download"></i></figure>
                        <div class="desc">
                            <h5>{{post_value_or($ia_fields,'my_response_history','My Response History')}}</h5>
                            <p>{{str_limit(post_description_or($ia_fields ,'my_response_history_description'), $limit = 55, $end = '...')}}</p>
                        </div>
                        <div class="info">
                            <!-- <span herf="#" class="more">click to view more details</span> -->
                            {!!  str_limit(post_description_or($ia_fields,'my_response_history_description'), $limit = 90, $end = '<div class="tooltipTitle">&nbspView More ..&nbsp <span class="tooltiptext">'.post_description_or($ia_fields,'my_response_history_description').'</span></div>') !!}<b><u>GO</u></b>

                        </div>
                    </a>
                </div>



                <div class="item">
                    <a href="{{URL::to('ranking') }}/{{nxb_encode($app->id)}}/participant/ranking" class="card-widget">
                        <figure class="icons-holder"><i class="fa fa-calendar"></i></figure>
                        <div class="desc">
                            <h5>{{post_value_or($ia_fields,'ranking','Ranking')}}</h5>
                            <p>{{str_limit(post_description_or($ia_fields ,'ranking_description'), $limit = 55, $end = '...')}}</p>
                        </div>
                        <div class="info">
                            <!-- <span herf="#" class="more">click to view more details</span> -->
                            {!!  str_limit(post_description_or($ia_fields,'ranking_description'), $limit = 90, $end = '<div class="tooltipTitle">&nbspView More ..&nbsp <span class="tooltiptext">'.post_description_or($ia_fields,'ranking_description').'</span></div>') !!}<b><u>GO</u></b>

                        </div>
                    </a>
                </div>
                @php $course_form = getCourseOutlineForm($app->template_id); @endphp
                @if($course_form)
                <div class="item">
                    <a href="{{URL::to('master-template') }}/{{nxb_encode($course_form->id)}}/course-outline/{{nxb_encode($app->invitee_id)}}" class="card-widget">
                        <figure class="icons-holder"><i class="fa fa-calendar"></i></figure>
                        <div class="desc">
                            <h5>{{$course_form->name}}</h5>
                        </div>
                        <div class="info">
                            <!-- <span herf="#" class="more">click to view more details</span> -->
                            <b><u>GO</u></b>
                        </div>
                    </a>
                </div>
                @endif



            </div>
            <!-- Scheduler & Rating -->
            <h4>Scheduler & Rating</h4>
            <div class="cards-holder pb-25">

                @if($app->hastemplate->category ==FACILTY || $app->hastemplate->category ==TRAINER)
                <div class="item">

                    <a href="{{URL::to('master-template') }}/participant/secondaryScheduler/{{nxb_encode($app->template_id)}}/{{nxb_encode($app->show_id)}}/{{$app->invite_asociated_key}}/0" class="card-widget">
                        <figure class="icons-holder"><i class="fa fa-calendar"></i></figure>
                        <div class="desc">
                            <h5>{{post_value_or($ia_fields,'manage_scheduler','Manage Scheduler')}}</h5>
                            <p>{{str_limit(post_description_or($ia_fields ,'manage_scheduler_description'), $limit = 55, $end = '...')}}</p>
                        </div>
                        <div class="info">
                            <!-- <span herf="#" class="more">click to view more details</span> -->
                            {!!  str_limit(post_description_or($ia_fields,'manage_scheduler_description'), $limit = 90, $end = '<div class="tooltipTitle">&nbspView More ..&nbsp <span class="tooltiptext">'.post_description_or($ia_fields,'manage_scheduler_description').'</span></div>') !!}<b><u>GO</u></b>

                        </div>
                    </a>
                </div>
            @else
                    @if(!empty($app->show_id))
                        <div class="item">
                    <a href="{{URL::to('master-template') }}/participant/scheduler/{{nxb_encode($app->template_id)}}/{{ nxb_encode($app->asset_id) }}/{{$app->invite_asociated_key}}/{{nxb_encode($app->show_id)}}/0" class="card-widget">
                        <figure class="icons-holder"><i class="fa fa-calendar"></i></figure>
                        <div class="desc">
                            <h5>{{post_value_or($ia_fields,'manage_scheduler','Manage Scheduler')}}</h5>
                            <p>{{str_limit(post_description_or($ia_fields ,'manage_scheduler_description'), $limit = 55, $end = '...')}}</p>
                        </div>
                        <div class="info">
                            <!-- <span herf="#" class="more">click to view more details</span> -->
                            {!!  str_limit(post_description_or($ia_fields,'manage_scheduler_description'), $limit = 90, $end = '<div class="tooltipTitle">&nbspView More ..&nbsp <span class="tooltiptext">'.post_description_or($ia_fields,'manage_scheduler_description').'</span></div>') !!}<b><u>GO</u></b>

                        </div>
                    </a>
                </div>
                        @endif
            @endif

                    @if($app->judgesFeedBackExist($app->asset_id) > 0)

                    <div class="item">
                        <a href="{{URL::to('participant') }}/{{nxb_encode($app->asset_id)}}/rider/judges/feedBack" class="card-widget">
                            <figure class="icons-holder"><i class="fa fa-desktop"></i></figure>
                            <div class="desc">
                                <h5>{{post_value_or($ia_fields,'judges_feed_back','Judges Feedback')}}</h5>
                                <p>{{str_limit(post_description_or($ia_fields,'judges_feed_back_description'), $limit = 55, $end = '...')}}</p>
                            </div>
                            <div class="info">
                                <!-- <span herf="#" class="more">click to view more details</span> -->
                                {!!  str_limit(post_description_or($ia_fields,'judges_feed_back_description'), $limit = 100, $end = '<div class="tooltipTitle">&nbspView More ..&nbsp <span class="tooltiptext">'.post_description_or($ia_fields,'judges_feed_back_description').'</span></div>') !!}<b><u>GO</u></b>

                            </div>
                        </a>
                    </div>
                    @endif


                @if($app->participantFeedBackExist($app->asset_id) > 0)
                <div class="item">
                    <a href="{{URL::to('participant') }}/{{nxb_encode($app->asset_id)}}/participant/getFeedBack" class="card-widget">
                        <figure class="icons-holder"><i class="fa fa-bar-chart"></i></figure>
                        <div class="desc">
                            <h5>{{post_value_or($ia_fields,'feedback','Feedback')}}</h5>
                            <p>{{str_limit(post_description_or($ia_fields ,'feedback_description'), $limit = 55, $end = '...')}}</p>
                        </div>
                        <div class="info">
                            <!-- <span herf="#" class="more">click to view more details</span> -->
                            {!!  str_limit(post_description_or($ia_fields,'feedback_description'), $limit = 90, $end = '<div class="tooltipTitle">&nbspView More ..&nbsp <span class="tooltiptext">'.post_description_or($ia_fields,'feedback_description').'</span></div>') !!}<b><u>GO</u></b>

                        </div>
                    </a>
                </div>
                @endif

                {{--@if($app->invoiceExist($app->invite_asociated_key,$app->asset_id) > 0)--}}
                    @if($app->hastemplate->category !=SHOW && $app->hastemplate->category !=TRAINER)
                <div class="item">
                <a href="{{URL::to('participant') }}/{{nxb_encode($app->id)}}/{{nxb_encode($app->asset_id)}}/invoice/listing/{{$app->invite_asociated_key}}" class="card-widget">
                    <figure class="icons-holder"><i class="fa fa-list-ul "></i></figure>
                    <div class="desc">
                        <h5>{{post_value_or($ia_fields,'invoice','Invoices')}}</h5>
                        <p>{{str_limit(post_description_or($ia_fields ,'invoice_description'), $limit = 55, $end = '...')}}</p>
                    </div>
                    <div class="info">
                        <!-- <span herf="#" class="more">click to view more details</span> -->
                        {!!  str_limit(post_description_or($ia_fields,'invoice_description'), $limit = 90, $end = '<div class="tooltipTitle">&nbspView More ..&nbsp <span class="tooltiptext">'.post_description_or($ia_fields,'invoice_description').'</span></div>') !!}<b><u>GO</u></b>

                    </div>
                </a>
            </div>
                    @endif
                {{--@endif--}}
                {{--@if($app->isFormSubmitted($app->id) == 0)--}}
                {{--<div class="item">--}}
                    {{--<a href="{{URL::to('participant/response/decline/') }}/{{ nxb_encode($app->id) }}/{{ nxb_encode($app->asset_id) }}" class="card-widget">--}}
                        {{--<figure class="icons-holder"><i class="fa fa-list-ul "></i></figure>--}}
                        {{--<div class="desc">--}}
                            {{--<h5>{{post_value_or($ia_fields,'decline','Decline')}}</h5>--}}
                        {{--</div>--}}

                    {{--</a>--}}
                {{--</div>--}}
                {{--@endif--}}
             @if($app->hastemplate->category !=TRAINER)
                <div class="item">
                    <a href="{{URL::to('master-template') }}/{{nxb_encode($app->id)}}/{{$app->invite_asociated_key}}/sub-participants/invite" class="card-widget">
                        <figure class="icons-holder"><i class="fa fa-list-ul "></i></figure>
                        <div class="desc">
                            <h5>{{post_value_or($ia_fields,'sub_participants','Invite Sub-Participant')}}</h5>
                            <p>{{str_limit(post_description_or($ia_fields ,'sub_participants_description'), $limit = 55, $end = '...')}}</p>
                        </div>
                        <div class="info">
                            <!-- <span herf="#" class="more">click to view more details</span> -->
                            {!!  str_limit(post_description_or($ia_fields,'sub_participants_description'), $limit = 90, $end = '<div class="tooltipTitle">&nbspView More ..&nbsp <span class="tooltiptext">'.post_description_or($ia_fields,'sub_participants_description').'</span></div>') !!}<b><u>GO</u></b>

                        </div>
                    </a>
                </div>
                <div class="item">
                    <a href="{{URL::to('master-template') }}/{{nxb_encode($app->id)}}/sub-participants/response" class="card-widget">
                        <figure class="icons-holder"><i class="fa fa-list-ul "></i></figure>
                        <div class="desc">
                            <h5>{{post_value_or($ia_fields,'view_sub_participants_response','View Sub-Participants Response')}}</h5>
                            <p>{{str_limit(post_description_or($ia_fields ,'view_sub_participants_response_description'), $limit = 55, $end = '...')}}</p>
                        </div>
                        <div class="info">
                            <!-- <span herf="#" class="more">click to view more details</span> -->
                            {!!  str_limit(post_description_or($ia_fields,'view_sub_participants_response_description'), $limit = 90, $end = '<div class="tooltipTitle">&nbspView More ..&nbsp <span class="tooltiptext">'.post_description_or($ia_fields,'view_sub_participants_response_description').'</span></div>') !!}<b><u>GO</u></b>

                        </div>
                    </a>
                </div>
            @endif
            </div>
            <!-- Profile & Course -->
            <h4>Profile</h4>
            <div class="cards-holder pb-25">

                @php $Profiler_Forms = getFormsForProfile($app->template_id,PROFILE_APP_NORMAL_USER,json_decode($app->invited_profiles)) @endphp
                @if($Profiler_Forms->count())
                    @foreach($Profiler_Forms as $form)
                <div class="item">
                    <a href="{{URL::to('settings') }}/{{nxb_encode($form->id)}}/2/profile/view" class="card-widget">
                        <figure class="icons-holder"><i class="fa fa-list-ul "></i></figure>
                        <div class="desc">
                            <h5>{{$form->name}}</h5>
                        </div>

                    </a>
                </div>
                    @endforeach
                @endif

            </div>
          @endif
            @elseif($app->status == 0)
            <!-- Invite Sub-Participants -->
            <div class="cards-holder pb-25">
                <div class="item">
                    <a href="{{URL::to('master-template') }}/{{nxb_encode($app->id)}}/assets/details" class="card-widget">
                        <figure class="icons-holder"><i class="fa fa-envelope"></i></figure>
                        <div class="desc">
                            <h5> {{post_value_or($ia_fields,'view_details','View Details')}}</h5>
                            <p>{{str_limit(post_description_or($ia_fields ,'view_details_description'), $limit = 55, $end = '...')}}</p>
                        </div>
                        <div class="info">
                            <!-- <span herf="#" class="more">click to view more details</span> -->
                            {!!  str_limit(post_description_or($ia_fields,'view_details_description'), $limit = 90, $end = '<div class="tooltipTitle">&nbspView More ..&nbsp <span class="tooltiptext">'.post_description_or($ia_fields,'view_details_description').'</span></div>') !!}<b><u>GO</u></b>

                        </div>
                    </a>
                </div>

                @php
                $penalty = 0;

                if($app->is_penalty==1)
                {
                    if($app->checkPenaltyDate($app->penalty_date) == 1)
                        $penalty = 1;
                }
                @endphp

                <div class="item">
                    <a href="javascript:"
                       onclick="acceptRequest('{{nxb_encode($app->template_id)}}','{{$app->invite_asociated_key}}','{{nxb_encode($app->id)}}','{{nxb_encode($app->asset_id)}}','1','{{$penalty}}')" class="card-widget">
                        <figure class="icons-holder"><i class="fa fa-edit"></i></figure>
                        <div class="desc">
                            <h5>Accept</h5>
                        </div>
                        <div class="info">
                            <!-- <span herf="#" class="more">click to view more details</span> -->
                        </div>
                    </a>
                </div>

                <div class="item">
                    <a href="javascript:"
                       onclick="acceptRequest('{{nxb_encode($app->template_id)}}','{{$app->invite_asociated_key}}','{{nxb_encode($app->id)}}','{{nxb_encode($app->asset_id)}}','2','{{$penalty}}')" class="card-widget">
                        <figure class="icons-holder"><i class="fa fa-edit"></i></figure>
                        <div class="desc">
                            <h5>Decline</h5>
                            {{--<p>Morbi sodales magna quis</p>--}}
                        </div>
                        <div class="info">
                            <!-- <span herf="#" class="more">click to view more details</span> -->
                            {!!  str_limit(post_description_or($ia_fields,'view_app_description'), $limit = 90, $end = '<div class="tooltipTitle">&nbspView More ..&nbsp <span class="tooltiptext">'.post_description_or($ia_fields,'view_app_description').'</span></div>') !!}<b><u>GO</u></b>

                        </div>
                    </a>
                </div>


            </div>
            @else
                <div class="row">
                    <div class="col-lg-5 col-md-5 col-sm-6">You have to accept the request to
                        proceed with this form!
                    </div>
                </div>
            @endif

                        <!-- ./ Right Panel -->
    </div>

</div>
    @else
    <div class="row text-center">No Application Exist</div>
    @endif
</div>


