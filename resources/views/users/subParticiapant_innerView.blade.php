<script>
    $('#typeahead-sub').typeahead( {
        source: function( query, process ) {
            $.get("/ajax-request/getSubParticipantData", {
                    query: query
                },
                function( data ) {
//                console.log(data);
                    process(data);
                } );
        },
        afterSelect: function (item) {
            //console.log(item);
            loadSubParticipantView(item.id)
        }
    } );


</script>
<div class="page-menu">
    <div class="row justify-content-between collapse-box menu-holder">
        <div class="d-flex flex-nowrap left-panel">
                <span class="menu-icon" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
                    <img src="{{ asset('/img/icons/icon-menu.svg') }}" />
                </span>

            @if($app)
                <h1 class="title flex-shrink-1">
                    <span @if(checkAllScratch($app->asset_id,$app->participant_id)) class="scratched-horses-class" @endif>{{GetAssetNamefromId($app->asset_id)}}</span>
                    @if(isset($app->participant->hastemplate))
                        @if(isset($app->participant->hastemplate->category))
                            @if($app->participant->hastemplate->category==SHOW)
                                 <small>{{(isset($app->participant->show->title))?$app->participant->show->title:''}}</small>
                                <small>{{(isset($app->participant->showRegistration->created_at))?$app->participant->showRegistration->created_at->format('m-d-Y'):''}}
                                   </small>
                            @else
                                @if(isset($app->participant->hastemplate))
                                <small>{{$app->participant->hastemplate->name}}</small>
                                @endif
                            @endif
                        @endif
                    @endif
                </h1>
            @endif
        </div>

        <div class="right-panel">
            <div class="desktop-view">

                    <div class="search-field mr-10">
                        <div class="input-group">
                            <input  id="typeahead-sub" class="form-control" placeholder="" aria-label="Username" aria-describedby="basic-addon1" type="text">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1"><img src="{{asset('/img/icons/icon-search.svg')}}"></span>
                            </div>
                        </div>
                    </div>
            </div>
            <div class="mobile-view">
                        <span class="menu-icon mr-15" data-toggle="collapse" href="#collapseMoreAction" role="button" aria-expanded="false" aria-controls="collapseMoreAction">
<!--                            <i class="fa fa-expand"></i>-->
                            <i class="fa fa-navicon"></i>
                        </span>

                <div class="collapse navbar-collapse-responsive navbar-collapse justify-content-end" id="navbarSupportedContent">
                        <div class="search-field">
                            <div class="input-group">
                                <input  id="typeahead-sub" class="form-control" placeholder="" aria-label="Username" aria-describedby="basic-addon1" type="text">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1"><img src="{{asset('/img/icons/icon-search.svg')}}"></span>
                                </div>
                            </div>
                        </div>

                </div>
            </div>
        </div>


        @if($appCollection->count()>0)

            <div class="collapse menu-box" id="collapseExample">
                        <span class="close-menu" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
                            <img src="{{ asset('/img/icons/icon-close.svg')}}" />
                        </span>
                <div class="menu-links">
                    <div class="row">

                        <!-- col-md-3  -->
                        <div class="col-md-3">
                            <ul class="nav flex-column">
                                @foreach($appCollection as $row)
                                    <li class="nav-item">
                                        <a class="nav-link {{($app->id==$row->id)?'active':''}}" onclick="loadSubParticipantView('{{$row->id}}')" href="javascript:">

                                            <span @if(checkAllScratch($row->asset_id,$row->participant_id)) class="scratched-horses-class" @endif>{{GetAssetNamefromId($row->asset_id)}}</span>
                                            @if(isset($row->participant->hastemplate))
                                                        <small>{{$row->participant->name}}</small>
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

            <div class="col-md-3 contetn-aside Smodule">
                <div class="row">
                    @if($app->participant->hastemplate->category!=SHOW)
                    <div class="col-lg-12 detail-area">
                        <span class="label">App Name</span>  <a class="text-secondary underline" href="#"  data-toggle="modal" data-target="#ProfilesModal{{$app->id}}">{{GetTemplateName($app->template_id,$app->invitee_id)}}</a>
                    </div>
                    @endif
                    <div class="col-lg-12 detail-area">
                        @if($app->participant->hastemplate->category==SHOW)
                            <span class="label">Class</span>
                        @else
                            <span class="label">Asset</span>
                        @endif
                        {{GetAssetNamefromId($app->asset_id)}}
                    </div>
                    @if($app->participant->hastemplate->category==SHOW)
                    @if(getHorseNames($app->participant_id ,$app->asset_id) != "No Horse Added")
                        <div class="col-lg-12 detail-area">
                            <span class="label">Horses</span>
                            <span class="block">
                         <label for="val1"><a class="text-secondary underline text-disabled" href="#">{!! getHorseNames($app->participant_id ,$app->asset_id,0) !!}</a></label></span>
                        </div>
                    @endif
                    @endif

                            <div class="col-lg-12 detail-area">
                                <span class="label">Invited By</span>
                                <a class="text-secondary underline" href="#">{{getUserNamefromid($app->user_id)}}</a>
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


                </div>
            </div>

        <!-- ./ Left Panel -->

        <!-- Right Panel -->
        <div class="col-md-9 card-small-area">
        @php $ia_fields = getButtonLabelFromTemplateId($app->template_id,'ia_fields'); @endphp

        @if($app->status == 1)

            <!-- Feedback & Class Info -->
            <div class="cards-holder pb-25">
                <div class="item">
                    <a href="{{URL::to('master-template') }}/{{nxb_encode($app->id)}}/sub-participant/assets/details" class="card-widget">
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
                        <a href="{{URL::to('master-template') }}/{{nxb_encode($app->template_id)}}/{{nxb_encode($app->id)}}/subparticipant-modules/launch/{{nxb_encode($app->asset_id)}}/{{$app->participant->invite_asociated_key}}" class="card-widget">
                            <figure class="icons-holder"><i class="fa fa-calendar"></i></figure>
                            <div class="desc">
                                <h5>{{post_value_or($ia_fields,'view_app','View App')}}</h5>
                                <p>{{str_limit(post_description_or($ia_fields ,'view_app_description'), $limit = 55, $end = '...')}}</p>
                            </div>
                            <div class="info">
                                <!-- <span herf="#" class="more">click to view more details</span> -->
                                {!!  str_limit(post_description_or($ia_fields,'view_app_description'), $limit = 90, $end = '<div class="tooltipTitle">&nbspView More ..&nbsp <span class="tooltiptext">'.post_description_or($ia_fields,'view_app_description').'</span></div>') !!}<b><u>GO</u></b>

                            </div>
                        </a>
                    </div>

                <div class="item">
                    <a href="{{URL::to('master-template') }}/{{nxb_encode($app->asset_id)}}/asset/readonly" class="card-widget">
                        <figure class="icons-holder"><i class="fa fa-eye"></i></figure>
                        <div class="desc">
                            <h5> {{post_value_or($ia_fields,'view_assets','View Assets')}}</h5>
                            <p>{{str_limit(post_description_or($ia_fields ,'view_assets_description'), $limit = 55, $end = '...')}}</p>
                        </div>
                        <div class="info">
                            {!!  str_limit(post_description_or($ia_fields,'view_assets_description'), $limit = 90, $end = '<div class="tooltipTitle">&nbspView More ..&nbsp <span class="tooltiptext">'.post_description_or($ia_fields,'view_assets_description').'</span></div>') !!}<b><u>GO</u></b>

                        </div>
                    </a>
                </div>
                <div class="item">
                    <a href="{{URL::to('master-template') }}/{{nxb_encode($app->id)}}/sub-participants/own-response" class="card-widget">
                        <figure class="icons-holder"><i class="fa fa-eye"></i></figure>
                        <div class="desc">
                            <h5>{{post_value_or($ia_fields,'my_response_history','My Responses')}}</h5>
                            <p>{{str_limit(post_description_or($ia_fields ,'my_response_history_description'), $limit = 55, $end = '...')}}</p>
                        </div>
                        <div class="info">
                            {!!  str_limit(post_description_or($ia_fields,'my_response_history_description'), $limit = 90, $end = '<div class="tooltipTitle">&nbspView More ..&nbsp <span class="tooltiptext">'.post_description_or($ia_fields,'my_response_history_description').'</span></div>') !!}<b><u>GO</u></b>

                        </div>
                    </a>
                </div>
                <div class="item">
                    <a href="{{URL::to('ranking') }}/{{nxb_encode($app->id)}}/sub-participant/ranking" class="card-widget">
                        <figure class="icons-holder"><i class="fa fa-download"></i></figure>
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

                @if($app->participant->hastemplate->category!=SHOW)
                <div class="item">
                    <a href="{{URL::to('sub-participant') }}/{{nxb_encode($app->id)}}/attached/history/assets" class="card-widget">
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
            @if($app->participant->hastemplate->category!=FACILTY)
                <div class="item">
                    <a href="{{URL::to('master-template') }}/participant/scheduler/{{nxb_encode($app->template_id)}}/{{ nxb_encode($app->asset_id) }}/{{$app->participant->invite_asociated_key}}/{{nxb_encode($app->participant->show_id)}}/1/{{$app->id}}" class="card-widget">
                        <figure class="icons-holder"><i class="fa fa-calendar"></i></figure>
                        <div class="desc">
                            <h5>{{post_value_or($ia_fields,'manage_scheduler','Manage Scheduler')}}</h5>
                            <p>{{str_limit(post_description_or($ia_fields ,'manage_scheduler_description'), $limit = 55, $end = '...')}}</p>
                        </div>
                        <div class="info">
                            {!!  str_limit(post_description_or($ia_fields,'manage_scheduler_description'), $limit = 90, $end = '<div class="tooltipTitle">&nbspView More ..&nbsp <span class="tooltiptext">'.post_description_or($ia_fields,'manage_scheduler_description').'</span></div>') !!}<b><u>GO</u></b>

                        </div>
                    </a>
                </div>
                @endif
            </div>
            <?php exit; ?>

        @elseif($app->status == 0)
            <!-- Invite Sub-Participants -->
            <div class="cards-holder pb-25">
                <div class="item">
                    <a href="{{URL::to('master-template') }}/{{nxb_encode($app->id)}}/sub-participant/assets/details" class="card-widget">
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


                <div class="item">
                    <a href="{{URL::to('sub-participants/sendInvite/response') }}/{{ nxb_encode($app->id) }}/1/{{ nxb_encode($app->asset_id) }}"
                        class="card-widget">
                        <figure class="icons-holder"><i class="fa fa-edit"></i></figure>
                        <div class="desc">
                            <h5>Accept</h5>
                            <p> Rider request of ability to post rider/filling out class feedback</p>
                        </div>
                        <div class="info">
                            Rider request of ability to post rider/filling out class feedback
                        </div>
                    </a>
                </div>

                <div class="item">
                    <a href="{{URL::to('sub-participants/sendInvite/response') }}/{{ nxb_encode($app->id) }}/2/{{ nxb_encode($app->asset_id) }}"
                      class="card-widget">
                        <figure class="icons-holder"><i class="fa fa-edit"></i></figure>
                        <div class="desc">
                            <h5>Decline</h5>
                            <p> Rider request of ability to post rider/filling out class feedback</p>

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

