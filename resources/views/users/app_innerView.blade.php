<div class="page-menu">

    <div class="row">
        <div class="col left-panel">
            <div class="d-flex flex-nowrap">
                <span class="menu-icon" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
                    <img src="{{ asset('/img/icons/icon-menu.svg') }}" />
                </span>
                @if($app)
                    <h1 class="pl-15">{{GetTemplateName($app->template_id,$user_id)}}</h1>
                @endif

            </div>
        </div>
        <div class="right-panel">
            <div class="desktop-view">
                <form class="form-inline justify-content-end">
                    <div class="search-field mr-10">
                        <div class="input-group">
                            <input id="typeahead-App" class="form-control" placeholder="" aria-label="Username" aria-describedby="basic-addon1" type="text">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1"><img src="{{ asset('img/icons/icon-search.svg')}}"></span>
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
                        <div class="search-field">
                            <div class="input-group">
                                <input  id="typeahead-App" class="form-control" placeholder="" aria-label="Username" aria-describedby="basic-addon1" type="text">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1"><img src="{{asset('img/icons/icon-search.svg')}}"></span>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>


        </div>
    </div>

    <div class="collapse-box menu-holder">


        <div class="collapse menu-box MobileViewRightPanel" id="collapseMoreAction">
                    <span class="close-menu" data-toggle="collapse" href="#collapseMoreAction" role="button" aria-expanded="false" aria-controls="collapseMoreAction">
                        <img src="{{asset('img/icons/icon-close.svg')}}" />
                    </span>
            <div class="menu-links">
                <div class="row">
                    <!-- col-md-6  -->
                    <div class="col-md-6 mb-10">
                        <form class="form-inline justify-content-end">

                            <div class="search-field">
                                <div class="input-group">
                                    <input  id="typeahead-App" type="text" class="form-control" placeholder="" aria-label="Username" aria-describedby="basic-addon1">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1"><img src="{{asset('img/icons/icon-search.svg')}}" /></span>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- /.col-md-6  -->
                    <!-- col-md-6  -->

                    <!-- /.col-md-6  -->

                </div>
            </div>
        </div>

        <div class="collapse menu-box MobileViewRightPanel" id="collapseExample">
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
                                    <a class="nav-link {{($app->template->id==$row->template->id)?'active':''}}"   href="javascript:" onclick="loadTemplateApps('{{$row->template->id}}')">{{GetTemplateName($row->template_id,$user_id)}}</a>
                                    <a class="setting" href="{{URL::to('master-template') }}/{{nxb_encode($app->id)}}/template/setting"><i class="fa fa-gear"></i></a>
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
    </div>
</div>

@php

    if(isset($app->template) && $app->template->category == SHOW)
      $templateTitle =  'Show';
    else
      $templateTitle =  '';

@endphp

@if($app)
<div class="white-board">
<h4>{{$templateTitle}} Configurations</h4>
@if($app->status != 2)
@php $templateType = $app->template->category @endphp
@if($app->status == 1)
    @if($app->block == 1)
        <div class="row">
            <div class="col-lg-8 col-md-8 col-sm-8">You have been blocked by admin.
                Contact admin to proceed with this applicaton!
            </div>
        </div>
    @else
        @php $ya_fields = getButtonLabelFromTemplateId($app->template_id,'ya_fields'); @endphp

        <div class="cards-holder pb-25">

            <div class="item">
                <a href="{{URL::to('master-template') }}/{{nxb_encode($app->template_id)}}/manage/assets" class="card-widget">
                    <figure class="icons-holder"><i class="fa fa-desktop"></i></figure>
                    <div class="desc">
                        <h5>{{post_value_or($ya_fields,'assets','Assets')}}</h5>
                        <p>{{str_limit(post_description_or($ya_fields,'assets_description'), $limit = 55, $end = '...')}}</p>
                    </div>
                    <div class="info">
                        <!-- <span herf="#" class="more">click to view more details</span> -->
                        {!!  str_limit(post_description_or($ya_fields,'assets_description'), $limit = 100, $end = '<div class="tooltipTitle">&nbspView More ..&nbsp <span class="tooltiptext">'.post_description_or($ya_fields,'assets_description').'</span></div>') !!}<b><u>GO</u></b>
                    </div>
                </a>
            </div>

            @if($app->template->category == FACILTY)
                <div class="item">
                    <a href="{{URL::to('master-template') }}/{{nxb_encode($app->template_id)}}/manage/project-overview" class="card-widget">
                        <figure class="icons-holder"><i class="fa fa-braille"></i></figure>
                        <div class="desc">
                            <h5>{{post_value_or($ya_fields,'project_overview','Project Overview')}}</h5>
                            <p>{{str_limit(post_description_or($ya_fields,'project_overview'), $limit = 55, $end = '...')}}</p>
                        </div>
                        <div class="info">
                            <!-- <span herf="#" class="more">click to view more details</span> -->
                            {!!  str_limit(post_description_or($ya_fields,'project_overview'), $limit = 100, $end = '<div class="tooltipTitle">&nbspView More ..&nbsp <span class="tooltiptext">'.post_description_or($ya_fields,'project_overview').'</span></div>') !!}<b><u>GO</u></b>

                        </div>
                    </a>
                </div>
            @endif
            @if($app->template->category != FACILTY)
            <div class="item">
                <a href="{{URL::to('master-template') }}/{{nxb_encode($app->template_id)}}/{{nxb_encode($app->id)}}/list/schedular" class="card-widget">
                    <figure class="icons-holder"><i class="fa fa-braille"></i></figure>
                    <div class="desc">
                        <h5>{{post_value_or($ya_fields,'manage_scheduler','Manage Scheduler(s)')}}</h5>
                        <p>{{str_limit(post_description_or($ya_fields,'manage_scheduler_description'), $limit = 55, $end = '...')}}</p>
                    </div>
                    <div class="info">
                        <!-- <span herf="#" class="more">click to view more details</span> -->
                        {!!  str_limit(post_description_or($ya_fields,'manage_scheduler_description'), $limit = 100, $end = '<div class="tooltipTitle">&nbspView More ..&nbsp <span class="tooltiptext">'.post_description_or($ya_fields,'manage_scheduler_description').'</span></div>') !!}<b><u>GO</u></b>

                    </div>
                </a>
            </div>
            @endif
            @if($app->template->category == SHOW || $app->template->category == TRAINER)
                <div class="item">
                    <a href="{{URL::to('shows') }}/{{nxb_encode($app->id)}}/additional-charges" class="card-widget">
                        <figure class="icons-holder"><i class="fa fa-star"></i></figure>
                        <div class="desc">
                            <h5>{{post_value_or($ya_fields,'shows_additional_charges','Shows Additional Charges')}}</h5>
                            <p>{{str_limit(post_description_or($ya_fields,'shows_additional_charges_description'), $limit = 55, $end = '...')}}</p>
                        </div>
                        <div class="info">
                            <!-- <span herf="#" class="more">click to view more details</span> -->
                            {!!  str_limit(post_description_or($ya_fields,'shows_additional_charges_description'), $limit = 100, $end = '<div class="tooltipTitle">&nbspView More ..&nbsp <span class="tooltiptext">'.post_description_or($ya_fields,'shows_additional_charges_description').'</span></div>') !!}<b><u>GO</u></b>

                        </div>
                    </a>
                </div>
            @endif
            @if($app->template->category == SHOW )
                

                <div class="item">
                    <a href="{{URL::to('shows') }}/{{nxb_encode($app->template_id)}}/manageSponsorRequest" class="card-widget">
                        <figure class="icons-holder"><i class="fa fa-vcard-o"></i></figure>
                        <div class="desc">
                            <h5> {{post_value_or($ya_fields,'manage_sponsor_categories','Manage Sponsor Categories')}}</h5>
                            <p>{{str_limit(post_description_or($ya_fields,'manage_sponsor_categories_description'), $limit = 55, $end = '...')}}</p>
                        </div>
                        <div class="info">
                            <!-- <span herf="#" class="more">click to view more details</span> -->
                            {!!  str_limit(post_description_or($ya_fields,'manage_sponsor_categories_description'), $limit = 100, $end = '<div class="tooltipTitle">&nbspView More ..&nbsp <span class="tooltiptext">'.post_description_or($ya_fields,'manage_sponsor_categories_description').'</span></div>') !!}<b><u>GO</u></b>

                        </div>
                    </a>
                </div>
                <div class="item">
                    <a href="{{URL::to('shows') }}/{{nxb_encode($app->template_id)}}/add/scratch" class="card-widget">
                        <figure class="icons-holder"><i class="fa fa-envelope"></i></figure>
                        <div class="desc">
                            <h5>{{post_value_or($ya_fields,'show_scratch_option','Scratch Option')}}</h5>
                            <p>{{str_limit(post_description_or($ya_fields,'show_scratch_option_description'), $limit = 55, $end = '...')}}</p>
                        </div>
                        <div class="info">
                            <!-- <span herf="#" class="more">click to view more details</span> -->
                            {!!  str_limit(post_description_or($ya_fields,'show_scratch_option_description'), $limit = 100, $end = '<div class="tooltipTitle">&nbspView More ..&nbsp <span class="tooltiptext">'.post_description_or($ya_fields,'show_scratch_option_description').'</span></div>') !!}<b><u>GO</u></b>

                        </div>
                    </a>
                </div>

                <div class="item">
                    <a href="{{URL::to('shows') }}/{{nxb_encode($app->template_id)}}/showStables" class="card-widget">
                        <figure class="icons-holder"><i class="fa fa-vcard-o"></i></figure>
                        <div class="desc">
                            <h5>{{post_value_or($ya_fields,'show_stables','Show Stables')}}</h5>
                            <p>{{str_limit(post_description_or($ya_fields,'show_stables_description'), $limit = 55, $end = '...')}}</p>
                        </div>
                        <div class="info">
                            <!-- <span herf="#" class="more">click to view more details</span> -->
                            {!!  str_limit(post_description_or($ya_fields,'show_stables_description'), $limit = 100, $end = '<div class="tooltipTitle">&nbspView More ..&nbsp <span class="tooltiptext">'.post_description_or($ya_fields,'show_stables_description').'</span></div>') !!}<b><u>GO</u></b>

                        </div>
                    </a>
                </div>

            @endif
            @if($app->template->category != SHOW and $app->template->category != TRAINER )

        <div class="item">
            <a href="{{URL::to('master-template') }}/{{nxb_encode($app->template_id)}}/invite/participants" class="card-widget">
                <figure class="icons-holder"><i class="fa fa-plus"></i></figure>
                <div class="desc">
                    <h5> {{post_value_or($ya_fields,'invite_participants','Invite Participants')}}</h5>
                    <p>{{str_limit(post_description_or($ya_fields,'invite_participants_description'), $limit = 55, $end = '...')}}</p>
                </div>
                <div class="info">
                    {{--<a href="javascript:"><small>View More</small></a>--}}
                    <!-- <span herf="#" class="more">click to view more details</span> -->
                        {!!  str_limit(post_description_or($ya_fields,'invite_participants_description'), $limit = 100, $end = '<div class="tooltipTitle">&nbspView More ..&nbsp <span class="tooltiptext">'.post_description_or($ya_fields,'invite_participants_description').'</span></div>') !!}<b><u>GO</u></b>
                </div>
            </a>
        </div>
            <div class="item">

            <a href="{{URL::to('master-template') }}/{{nxb_encode($app->template_id)}}/modules/launch/{{nxb_encode($app->id)}}" class="card-widget">
                <figure class="icons-holder"><i class="fa fa-bar-chart"></i></figure>
                <div class="desc">
                    <h5> {{post_value_or($ya_fields,'view_app','View App')}}</h5>
                    <p>{{str_limit(post_description_or($ya_fields,'view_app_description'), $limit = 55, $end = '...')}}</p>
                </div>
                <div class="info">
                    <!-- <span herf="#" class="more">click to view more details</span> -->
                    {!!  str_limit(post_description_or($ya_fields,'view_app_description'), $limit = 100, $end = '<div class="tooltipTitle">&nbspView More ..&nbsp <span class="tooltiptext">'.post_description_or($ya_fields,'view_app_description').'</span></div>') !!}<b><u>GO</u></b>
                </div>
            </a>
        </div>

            @endif


    </div>

    <!-- Manage Participants -->
    <h4>Manage {{$templateTitle}}</h4>
    <div class="cards-holder pb-25">


        @php($getShows = getShows($app->template_id))

        @if($getShows)
            @if($getShows->show_id > 0)

                @if($app->template->category == SHOW)
                <div class="item">
                    <a href="{{URL::to('master-template') }}/{{nxb_encode($app->template_id)}}/{{nxb_encode($getShows->show_id)}}/masterSchedular" class="card-widget">
                        <figure class="icons-holder"><i class="fa fa-file-text-o"></i></figure>
                        <div class="desc">
                            <h5>{{post_value_or($ya_fields,'master_scheduler','Master Scheduler')}}</h5>
                            <p>{{str_limit(post_description_or($ya_fields,'master_scheduler_description'), $limit = 55, $end = '...')}}</p>
                        </div>
                        <div class="info">
                            <!-- <span herf="#" class="more">click to view more details</span> -->
                            {!!  str_limit(post_description_or($ya_fields,'master_scheduler_description'), $limit = 100, $end = '<div class="tooltipTitle">&nbspView More ..&nbsp <span class="tooltiptext">'.post_description_or($ya_fields,'master_scheduler_description').'</span></div>') !!}<b><u>GO</u></b>
                        </div>
                    </a>
                </div>
                @endif

                @if($app->template->category == SHOW)
                <div class="item">
                    <a href="{{URL::to('master-template') }}/{{nxb_encode($app->template_id)}}/view/orderSupplies" class="card-widget">
                        <figure class="icons-holder"><i class="fa fa-file-text-o"></i></figure>
                        <div class="desc">
                            <h5>{{post_value_or($ya_fields,'order_supplies','Order Supplies Requests')}}</h5>
                            <p>{{str_limit(post_description_or($ya_fields,'order_supplies_description'), $limit = 55, $end = '...')}}</p>
                        </div>
                        <div class="info">
                            <!-- <span herf="#" class="more">click to view more details</span> -->
                            {!!  str_limit(post_description_or($ya_fields,'order_supplies_description'), $limit = 100, $end = '<div class="tooltipTitle">&nbspView More ..&nbsp <span class="tooltiptext">'.post_description_or($ya_fields,'order_supplies_description').'</span></div>') !!}<b><u>GO</u></b>
                        </div>
                    </a>
                </div>
                @endif

            @endif
        @endif
        @if($app->template->category == SHOW)

        <div class="item">
            <a href="{{URL::to('shows') }}/low-participant-view/{{nxb_encode($app->template_id)}}" class="card-widget">
                <figure class="icons-holder"><i class="fa fa-barcode"></i></figure>
                <div class="desc">
                    <h5>{{post_value_or($ya_fields,'low_participants','Low Participants')}}</h5>
                    <p>{{str_limit(post_description_or($ya_fields,'low_participants_description'), $limit = 55, $end = '...')}}</p>
                </div>
                <div class="info">
                    <!-- <span herf="#" class="more">click to view more details</span> -->
                    {!!  str_limit(post_description_or($ya_fields,'low_participants_description'), $limit = 100, $end = '<div class="tooltipTitle">&nbspView More ..&nbsp <span class="tooltiptext">'.post_description_or($ya_fields,'low_participants_description').'</span></div>') !!}<b><u>GO</u></b>

                </div>
            </a>
        </div>
        @endif

    @if($app->template->category == SHOW || $app->template->category == TRAINER)
            <div class="item">
                <a href="{{URL::to('master-template') }}/{{nxb_encode($app->template_id)}}/addScratchEntries" class="card-widget">
                    <figure class="icons-holder"><i class="fa fa-desktop"></i></figure>
                    <div class="desc">
                        <h5> {{post_value_or($ya_fields,'add_scratch_entries','Add/Scratch Entries')}}</h5>
                        <p>{{str_limit(post_description_or($ya_fields,'add_scratch_entries_description'), $limit = 55, $end = '...')}}</p>
                    </div>
                    <div class="info">
                        <!-- <span herf="#" class="more">click to view more details</span> -->
                        {!!  str_limit(post_description_or($ya_fields,'add_scratch_entries_description'), $limit = 100, $end = '<div class="tooltipTitle">&nbspView More ..&nbsp <span class="tooltiptext">'.post_description_or($ya_fields,'add_scratch_entries_description').'</span></div>') !!}<b><u>GO</u></b>
                    </div>
                </a>
            </div>
           
     @endif
        @if($app->template->category == SHOW)
         <div class="item">
                <a href="{{URL::to('shows') }}/champion/{{nxb_encode($app->id)}}/index" class="card-widget">
                    <figure class="icons-holder"><i class="fa fa-plus-circle"></i></figure>
                    <div class="desc">
                        <h5> {{post_value_or($ya_fields,'champion_calculator','Champion Calculator')}}</h5>
                        <p>{{str_limit(post_description_or($ya_fields,'champion_calculator_description'), $limit = 55, $end = '...')}}</p>
                    </div>
                    <div class="info">
                        <!-- <span herf="#" class="more">click to view more details</span> -->
                        {!!  str_limit(post_description_or($ya_fields,'champion_calculator_description'), $limit = 100, $end = '<div class="tooltipTitle">&nbspView More ..&nbsp <span class="tooltiptext">'.post_description_or($ya_fields,'champion_calculator_description').'</span></div>') !!}<b><u>GO</u></b>
                    </div>
                </a>
            </div>

        @endif

            <div class="item">
                <a href="{{URL::to('employee') }}/{{nxb_encode($app->template_id)}}/{{nxb_encode($app->id)}}/index" class="card-widget">
                    <figure class="icons-holder"><i class="fa fa-desktop"></i></figure>
                    <div class="desc">
                        <h5> {{post_value_or($ya_fields,'manage_employee','Manage Employee')}}</h5>
                        <p>{{str_limit(post_description_or($ya_fields,'manage_employee_description'), $limit = 55, $end = '...')}}</p>
                    </div>
                    <div class="info">
                        <!-- <span herf="#" class="more">click to view more details</span> -->
                        {!!  str_limit(post_description_or($ya_fields,'manage_employee_description'), $limit = 100, $end = '<div class="tooltipTitle">&nbspView More ..&nbsp <span class="tooltiptext">'.post_description_or($ya_fields,'manage_employee_description').'</span></div>') !!}<b><u>GO</u></b>
                    </div>
                </a>
            </div>
        <div class="item">
            <a href="{{URL::to('master-template') }}/{{nxb_encode($app->template_id)}}/invite/users" class="card-widget">
                <figure class="icons-holder"><i class="fa fa-desktop"></i></figure>
                <div class="desc">
                    <h5>{{post_value_or($ya_fields,'invite_users','Invite User(s)')}}</h5>
                    <p>{{str_limit(post_description_or($ya_fields,'invite_users_description'), $limit = 55, $end = '...')}}</p>
                </div>
                <div class="info">
                    <!-- <span herf="#" class="more">click to view more details</span> -->
                    {!!  str_limit(post_description_or($ya_fields,'invite_users_description'), $limit = 100, $end = '<div class="tooltipTitle">&nbspView More ..&nbsp <span class="tooltiptext">'.post_description_or($ya_fields,'invite_users_description').'</span></div>') !!}<b><u>GO</u></b>
                </div>
            </a>
        </div>

    </div>
    <h4>Info Hub</h4>
    <div class="cards-holder pb-25">

        <div class="item">
            <a href="{{URL::to('master-template') }}/{{nxb_encode($app->template_id)}}/all/history/assets" class="card-widget">
                <figure class="icons-holder"><i class="fa fa-star-o"></i></figure>
                <div class="desc">
                    <h5>{{post_value_or($ya_fields,'participants_response','Participants Response')}}</h5>
                    <p>{{str_limit(post_description_or($ya_fields,'participants_response_description'), $limit = 55, $end = '...')}}</p>
                </div>
                <div class="info">
                    <!-- <span herf="#" class="more">click to view more details</span> -->
                    {!!  str_limit(post_description_or($ya_fields,'participants_response_description'), $limit = 100, $end = '<div class="tooltipTitle">&nbspView More ..&nbsp <span class="tooltiptext">'.post_description_or($ya_fields,'participants_response_description').'</span></div>') !!}<b><u>GO</u></b>
                </div>
            </a>
        </div>

        <div class="item">
            <a href="{{URL::to('ranking') }}/{{nxb_encode($app->template_id)}}/index" class="card-widget">
                <figure class="icons-holder"><i class="fa fa-dashboard"></i></figure>
                <div class="desc">
                    <h5>{{post_value_or($ya_fields,'ranking','Ranking')}}</h5>
                    <p>{{str_limit(post_description_or($ya_fields,'ranking_description'), $limit = 55, $end = '...')}}</p>
                </div>
                <div class="info">
                    <!-- <span herf="#" class="more">click to view more details</span> -->
                    {!!  str_limit(post_description_or($ya_fields,'ranking_description'), $limit = 100, $end = '<div class="tooltipTitle">&nbspView More ..&nbsp <span class="tooltiptext">'.post_description_or($ya_fields,'ranking_description').'</span></div>') !!}<b><u>GO</u></b>
                </div>
            </a>
        </div>

        @if($app->masterFeedBackExist($app->template_id) > 0)

            <div class="item">
                <a href="{{URL::to('master-template') }}/{{nxb_encode($app->template_id)}}/invitee/feedBack" class="card-widget">
                    <figure class="icons-holder"><i class="fa fa-desktop"></i></figure>
                    <div class="desc">
                        <h5>{{post_value_or($ya_fields,'feedback','Feedback')}}</h5>
                        <p>{{str_limit(post_description_or($ya_fields,'feedback_description'), $limit = 55, $end = '...')}}</p>
                    </div>
                    <div class="info">
                        <!-- <span herf="#" class="more">click to view more details</span> -->
                        {!!  str_limit(post_description_or($ya_fields,'feedback_description'), $limit = 100, $end = '<div class="tooltipTitle">&nbspView More ..&nbsp <span class="tooltiptext">'.post_description_or($ya_fields,'feedback_description').'</span></div>') !!}<b><u>GO</u></b>

                    </div>
                </a>
            </div>
        @endif

        @if($app->masterJudgesFeedBackExist($app->template_id) > 0)

            <div class="item">
                <a href="{{URL::to('master-template') }}/{{nxb_encode($app->template_id)}}/judges/feedBack" class="card-widget">
                    <figure class="icons-holder"><i class="fa fa-desktop"></i></figure>
                    <div class="desc">
                        <h5>{{post_value_or($ya_fields,'judges_feed_back','Judges Feedback')}}</h5>
                        <p>{{str_limit(post_description_or($ya_fields,'judges_feed_back_description'), $limit = 55, $end = '...')}}</p>
                    </div>
                    <div class="info">
                        <!-- <span herf="#" class="more">click to view more details</span> -->
                        {!!  str_limit(post_description_or($ya_fields,'judges_feed_back_description'), $limit = 100, $end = '<div class="tooltipTitle">&nbspView More ..&nbsp <span class="tooltiptext">'.post_description_or($ya_fields,'judges_feed_back_description').'</span></div>') !!}<b><u>GO</u></b>

                    </div>
                </a>
            </div>
        @endif
        @if($app->template->category == SHOW)
        <div class="item">
            <a href="{{URL::to('shows') }}/{{nxb_encode($app->template_id)}}/spectators" class="card-widget">
                <figure class="icons-holder"><i class="fa fa-thumbs-o-down"></i></figure>
                <div class="desc">
                    <h5>{{post_value_or($ya_fields,'show_spectators','Shows Spectators')}}</h5>
                    <p>{{str_limit(post_description_or($ya_fields,'show_spectators_description'), $limit = 55, $end = '...')}}</p>
                </div>
                <div class="info">
                    <!-- <span herf="#" class="more">click to view more details</span> -->
                    {!!  str_limit(post_description_or($ya_fields,'show_spectators_description'), $limit = 100, $end = '<div class="tooltipTitle">&nbspView More ..&nbsp <span class="tooltiptext">'.post_description_or($ya_fields,'show_spectators_description').'</span></div>') !!}<b><u>GO</u></b>
                </div>
            </a>
        </div>
        <div class="item">
            <a href="{{URL::to('shows') }}/{{nxb_encode($app->template_id)}}/showSponsors" class="card-widget">
                <figure class="icons-holder"><i class="fa fa-user"></i></figure>
                <div class="desc">
                    <h5>{{post_value_or($ya_fields,'show_sponsors','Sponsors')}}</h5>
                    <p>{{str_limit(post_description_or($ya_fields,'show_sponsors_description'), $limit = 55, $end = '...')}}</p>
                </div>
                <div class="info">
                    <!-- <span herf="#" class="more">click to view more details</span> -->
                    {!!  str_limit(post_description_or($ya_fields,'show_sponsors_description'), $limit = 100, $end = '<div class="tooltipTitle">&nbspView More ..&nbsp <span class="tooltiptext">'.post_description_or($ya_fields,'show_sponsors_description').'</span></div>') !!}<b><u>GO</u></b>
                </div>
            </a>
        </div>
        <div class="item">
            <a href="{{URL::to('shows') }}/{{nxb_encode($app->template_id)}}/horse/prizClaimForms" class="card-widget">
                <figure class="icons-holder"><i class="fa fa-th-list"></i></figure>
                <div class="desc">
                    <h5>{{post_value_or($ya_fields,'1099Forms','1099 Forms')}}</h5>
                    <p>{{str_limit(post_description_or($ya_fields,'1099Forms_description'), $limit = 55, $end = '...')}}</p>
                </div>
                <div class="info">
                    <!-- <span herf="#" class="more">click to view more details</span> -->
                    {!!  str_limit(post_description_or($ya_fields,'1099Forms_description'), $limit = 100, $end = '<div class="tooltipTitle">&nbspView More ..&nbsp <span class="tooltiptext">'.post_description_or($ya_fields,'1099Forms_description').'</span></div>') !!}<b><u>GO</u></b>

                </div>
            </a>
        </div>
        @endif
        @if($app->template->category == SHOW || $app->template->category == TRAINER)
        <div class="item">
            <a href="{{URL::to('shows') }}/{{nxb_encode($app->template_id)}}/participants" class="card-widget">
                <figure class="icons-holder"><i class="fa fa-check-square-o"></i></figure>
                <div class="desc">
                    <h5>{{post_value_or($ya_fields,'show_participants','Shows Participants')}}</h5>
                    <p>{{str_limit(post_description_or($ya_fields,'show_participants_description'), $limit = 55, $end = '...')}}</p>
                </div>
                <div class="info">
                    <!-- <span herf="#" class="more">click to view more details</span> -->
                    {!!  str_limit(post_description_or($ya_fields,'show_participants_description'), $limit = 100, $end = '<div class="tooltipTitle">&nbspView More ..&nbsp <span class="tooltiptext">'.post_description_or($ya_fields,'show_participants_description').'</span></div>') !!}<b><u>GO</u></b>

                </div>
            </a>
        </div>

            <div class="item">
                <a href="{{URL::to('shows') }}/{{nxb_encode($app->template_id)}}/exportShows" class="card-widget">
                    <figure class="icons-holder"><i class="fa fa-check-square-o"></i></figure>
                    <div class="desc">
                        <h5>{{post_value_or($ya_fields,'export_shows','Export Shows')}}</h5>
                        <p>{{str_limit(post_description_or($ya_fields,'export_shows_description'), $limit = 55, $end = '...')}}</p>
                    </div>
                    <div class="info">
                        <!-- <span herf="#" class="more">click to view more details</span> -->
                        {!!  str_limit(post_description_or($ya_fields,'export_shows_description'), $limit = 100, $end = '<div class="tooltipTitle">&nbspView More ..&nbsp <span class="tooltiptext">'.post_description_or($ya_fields,'export_shows_description').'</span></div>') !!}<b><u>GO</u></b>

                    </div>
                </a>
            </div>


        
        @endif

    </div>

    <!-- Billing -->
    <h4>Billing</h4>

                <div class="cards-holder pb-25">
                    @if($app->template->category == SHOW || $app->template->category == TRAINER)

                    <div class="item">
            <a href="{{URL::to('shows') }}/{{nxb_encode($app->template_id)}}/horse/invoice/listing" class="card-widget">
                <figure class="icons-holder"><i class="fa fa-file-text-o"></i></figure>
                <div class="desc">
                    <h5>{{post_value_or($ya_fields,'show_invoice','Show Invoices')}}</h5>
                    <p>{{str_limit(post_description_or($ya_fields,'show_invoice_description'), $limit = 55, $end = '...')}}</p>
                </div>
                <div class="info">
                    <!-- <span herf="#" class="more">click to view more details</span> -->
                    {!!  str_limit(post_description_or($ya_fields,'show_invoice_description'), $limit = 100, $end = '<div class="tooltipTitle">&nbspView More ..&nbsp <span class="tooltiptext">'.post_description_or($ya_fields,'show_invoice_description').'</span></div>') !!}<b><u>GO</u></b>

                </div>
            </a>
        </div>

                    @endif

                        @if($app->template->category!=SHOW  and $app->template->category != TRAINER)
                            <div class="item">
                                <a href="{{URL::to('master-template') }}/{{nxb_encode($app->template_id)}}/invoice/listing" class="card-widget">
                                    <figure class="icons-holder"><i class="fa fa-desktop"></i></figure>
                                    <div class="desc">
                                        <h5>{{post_value_or($ya_fields,'invoice','Invoices')}}</h5>
                                        <p>{{str_limit(post_description_or($ya_fields,'invoice_description'), $limit = 55, $end = '...')}}</p>
                                    </div>
                                    <div class="info">
                                        <!-- <span herf="#" class="more">click to view more details</span> -->
                                        {!!  str_limit(post_description_or($ya_fields,'invoice_description'), $limit = 100, $end = '<div class="tooltipTitle">&nbspView More ..&nbsp <span class="tooltiptext">'.post_description_or($ya_fields,'invoice_description').'</span></div>') !!}<b><u>GO</u></b>

                                    </div>
                                </a>
                            </div>
                        @endif


                </div>

    <!-- Profile & Course Content -->
    <h4>Profiles/Forms</h4>
    <div class="cards-holder pb-25">


        <?php $course_form = getCourseOutlineForm($app->template_id); ?>
            @if($course_form)
            <div class="item">
                <a href="{{URL::to('master-template') }}/{{nxb_encode($course_form->id)}}/course-outline" class="card-widget">
                    <figure class="icons-holder"><i class="fa fa-wpforms"></i></figure>
                    <div class="desc">
                        <h5>{{$course_form->name}}</h5>
                        <p>{{str_limit(post_description_or($ya_fields,'course_content_description'), $limit = 55, $end = '...')}}</p>
                    </div>
                    <div class="info">
                        <!-- <span herf="#" class="more">click to view more details</span> -->
                        {!!  str_limit(post_description_or($ya_fields,'course_content_description'), $limit = 100, $end = '<div class="tooltipTitle">&nbspView More ..&nbsp <span class="tooltiptext">'.post_description_or($ya_fields,'course_content_description').'</span></div>') !!}<b><u>GO</u></b>
                    </div>
                </a>
            </div>
        @endif


        @php($Profiler_Forms = getFormsForProfile($app->template_id,PROFILE_APP_OWNER))
        @if($Profiler_Forms->count())
            @foreach($Profiler_Forms as $form)
        <div class="item">
            <a href="{{URL::to('settings') }}/{{nxb_encode($form->id)}}/1/profile/view" class="card-widget">
                <figure class="icons-holder"><i class="fa fa-desktop"></i></figure>
                <div class="desc">
                    <h5>{{$form->name}}</h5>
                    <p>{{str_limit(post_description_or($ya_fields,'Profiler_Forms_description'), $limit = 55, $end = '...')}}</p>
                </div>
                <div class="info">
                    <!-- <span herf="#" class="more">click to view more details</span> -->
                    {!!  str_limit(post_description_or($ya_fields,'Profiler_Forms_description'), $limit = 100, $end = '<div class="tooltipTitle">&nbspView More ..&nbsp <span class="tooltiptext">'.post_description_or($ya_fields,'Profiler_Forms_description').'</span></div>') !!}<b><u>GO</u></b>

                </div>
            </a>
        </div>
            @endforeach
        @endif
      @if($app->template->category != SHOW and $app->template->category != TRAINER )
        <div class="item">
            <a href="{{URL::to('master-template') }}/{{nxb_encode($app->template_id)}}/participants/list" class="card-widget">
                <figure class="icons-holder"><i class="fa fa-desktop"></i></figure>
                <div class="desc">
                    <h5>{{post_value_or($ya_fields,'manage_participants','Manage Participants')}}</h5>
                    <p>{{str_limit(post_description_or($ya_fields,'manage_participants_description'), $limit = 55, $end = '...')}}</p>
                </div>
                <div class="info">
                    <!-- <span herf="#" class="more">click to view more details</span> -->
                    {!!  str_limit(post_description_or($ya_fields,'manage_participants_description'), $limit = 100, $end = '<div class="tooltipTitle">&nbspView More ..&nbsp <span class="tooltiptext">'.post_description_or($ya_fields,'manage_participants_description').'</span></div>') !!}<b><u>GO</u></b>

                </div>
            </a>
        </div>
      @endif




    </div>
    @endif

  @elseif($app->status == 0)
        <div class="cards-holder pb-25">

        <div class="item">
            <a href="{{URL::to('template/sendInvite/response') }}/{{ nxb_encode($app->id) }}/1" class="card-widget">
                <figure class="icons-holder"><i class="fa fa-desktop"></i></figure>
                <div class="desc">
                    <h5>Accept</h5>
                    <p>Accept the request in order to participate in the app activity</p>
                </div>
                <div class="info">
                    <!-- <span herf="#" class="more">click to view more details</span> -->
                    Accept the request in order to participate in the app activity
                </div>
            </a>
        </div>
        <div class="item">
            <a href="{{URL::to('template/sendInvite/response') }}/{{ nxb_encode($app->id) }}/2" class="card-widget">
                <figure class="icons-holder"><i class="fa fa-desktop"></i></figure>
                <div class="desc">
                    <h5>Decline</h5>
                    <p>Decline the request, if you are not interested</p>
                </div>
                <div class="info">
                    <!-- <span herf="#" class="more">click to view more details</span> -->
                    Decline the request, if you are not interested
                </div>
            </a>
        </div>
        </div>
  @endif

@endif
</div>
@else
    <div class="white-board text-center">No Application Exist</div>
@endif




