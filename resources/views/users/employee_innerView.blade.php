<script>
$('#typeahead-emp').typeahead( {
source: function( query, process ) {
$.get("/ajax-request/getEmployeeData", {
query: query
},
function( data ) {
process(data);
} );
},
afterSelect: function (item) {
loadEmployeeView(item.id)
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
<h1 class="pl-15">{{GetTemplateName($app->template_id,$user_id)}}</h1>
@endif
</div>

<div class="right-panel">
<div class="desktop-view">
<form class="form-inline justify-content-end">
<div class="search-field mr-10">
<div class="input-group">
    <input id="typeahead-emp" class="form-control" placeholder="" aria-label="Username" aria-describedby="basic-addon1" type="text">
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
        <input  id="typeahead-emp" class="form-control" placeholder="" aria-label="Username" aria-describedby="basic-addon1" type="text">
        <div class="input-group-prepend">
            <span class="input-group-text" id="basic-addon1"><img src="{{ asset('img/icons/icon-search.svg')}}"></span>
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
<div class="row">
<!-- col-md-3  -->
<div class="col-md-3">
    <ul class="nav flex-column">
        @foreach($appCollection as $row)
            <li class="nav-item">
                <a class="nav-link {{($app->template->id==$row->template->id)?'active':''}}"   href="javascript:" onclick="loadEmployeeView('{{$row->template->id}}')">{{GetTemplateName($row->template_id,$user_id)}}</a>
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
@endif
</div>
</div>

<div class="white-board">
@if($app)
<div class="row">
<div class="col-md-3 contetn-aside Smodule">
<div class="row">
<div class="col-lg-12 detail-area">
<span class="label">App Name</span>  <a class="text-secondary underline" href="#"  data-toggle="modal" data-target="#ProfilesModal{{$app->id}}">{{GetTemplateName($app->template_id,$app->invitee_id)}}</a>
</div>
<div class="col-lg-12 detail-area">
<span class="label">Status</span>  {{EmailStatus($app->status)}}
</div>
<div class="col-lg-12 detail-area">
<span class="label">Invited on App</span>
{{getDates($app->created_at)}}
</div>
</div>
</div>
<div class="col-md-9 card-small-area">
@php $ya_fields = getButtonLabelFromTemplateId($app->template_id,'ya_fields'); @endphp

@if($app->status == 1)

<!-- Feedback & Class Info -->
<div class="cards-holder pb-25">

@if(in_array('1',$app->permission($app->email,$app->template_id)))
    <div class="item">
        <a href="{{URL::to('master-template') }}/{{nxb_encode($app->template_id)}}/invite/participants" class="card-widget">
            <figure class="icons-holder"><i class="fa fa-eye"></i></figure>
            <div class="desc">
                <h5> {{post_value_or($ya_fields,'invite_participants','Invite Participants')}}</h5>
                <p>{{str_limit(post_description_or($ya_fields ,'invite_participants_description'), $limit = 55, $end = '...')}}</p>
            </div>
            <div class="info">
                {!!  str_limit(post_description_or($ya_fields,'invite_participants_description'), $limit = 90, $end = '<div class="tooltipTitle">&nbspView More ..&nbsp <span class="tooltiptext">'.post_description_or($ya_fields,'invite_participants_description').'</span></div>') !!}<b><u>GO</u></b>

            </div>
        </a>
    </div>
@endif

@if(in_array('4',$app->permission($app->email,$app->template_id)))
    <div class="item">
        <a href="{{URL::to('master-template') }}/{{nxb_encode($app->template_id)}}/modules/launch" class="card-widget">
            <figure class="icons-holder"><i class="fa fa-calendar"></i></figure>
            <div class="desc">
                <h5>{{post_value_or($ya_fields,'view_app','View App')}}</h5>
                <p>{{str_limit(post_description_or($ya_fields ,'view_app_description'), $limit = 55, $end = '...')}}</p>
            </div>
            <div class="info">
                <!-- <span herf="#" class="more">click to view more details</span> -->
                {!!  str_limit(post_description_or($ya_fields,'view_app_description'), $limit = 90, $end = '<div class="tooltipTitle">&nbspView More ..&nbsp <span class="tooltiptext">'.post_description_or($ya_fields,'view_app_description').'</span></div>') !!}<b><u>GO</u></b>

            </div>
        </a>
    </div>
@endif

@if(in_array('5',$app->permission($app->email,$app->template_id)))
    <div class="item">
        <a href="{{URL::to('master-template') }}/{{nxb_encode($app->template_id)}}/manage/assets" class="card-widget">
            <figure class="icons-holder"><i class="fa fa-eye"></i></figure>
            <div class="desc">
                <h5> {{post_value_or($ya_fields,'assets','Assets')}}</h5>
                <p>{{str_limit(post_description_or($ya_fields ,'assets_description'), $limit = 55, $end = '...')}}</p>
            </div>
            <div class="info">
                {!!  str_limit(post_description_or($ya_fields,'assets_description'), $limit = 90, $end = '<div class="tooltipTitle">&nbspView More ..&nbsp <span class="tooltiptext">'.post_description_or($ya_fields,'assets_description').'</span></div>') !!}<b><u>GO</u></b>

            </div>
        </a>
    </div>
@endif

@if(in_array('6',$app->permission($app->email,$app->template_id)))
    <div class="item">
        <a href="{{URL::to('master-template') }}/{{nxb_encode($app->template_id)}}/all/history/assets" class="card-widget">
            <figure class="icons-holder"><i class="fa fa-eye"></i></figure>
            <div class="desc">
                <h5>{{post_value_or($ya_fields,'participants_response','Participants Response')}}</h5>
                <p>{{str_limit(post_description_or($ya_fields ,'participants_response_response'), $limit = 55, $end = '...')}}</p>
            </div>
            <div class="info">
                {!!  str_limit(post_description_or($ya_fields,'participants_response_response'), $limit = 90, $end = '<div class="tooltipTitle">&nbspView More ..&nbsp <span class="tooltiptext">'.post_description_or($ya_fields,'participants_response_response').'</span></div>') !!}<b><u>GO</u></b>

            </div>
        </a>
    </div>
@endif

@if(in_array('2',$app->permission($app->email,$app->template_id)))
    <div class="item">
        <a href="{{URL::to('master-template') }}/{{nxb_encode($app->template_id)}}/invite/spectators" class="card-widget">
            <figure class="icons-holder"><i class="fa fa-download"></i></figure>
            <div class="desc">
                <h5>{{post_value_or($ya_fields,'invite_spectator','Invite Spectator')}}</h5>
                <p>{{str_limit(post_description_or($ya_fields ,'invite_spectator_ranking'), $limit = 55, $end = '...')}}</p>
            </div>
            <div class="info">
                <!-- <span herf="#" class="more">click to view more details</span> -->
                {!!  str_limit(post_description_or($ya_fields,'invite_spectator_ranking'), $limit = 90, $end = '<div class="tooltipTitle">&nbspView More ..&nbsp <span class="tooltiptext">'.post_description_or($ya_fields,'invite_spectator_ranking').'</span></div>') !!}<b><u>GO</u></b>

            </div>
        </a>
    </div>
@endif

@if(in_array('7',$app->permission($app->email,$app->template_id)))
    <div class="item">
        <a href="{{URL::to('ranking') }}/{{nxb_encode($app->template_id)}}/index" class="card-widget">
            <figure class="icons-holder"><i class="fa fa-calendar"></i></figure>
            <div class="desc">
                <h5>{{post_value_or($ya_fields,'ranking','Ranking')}}</h5>
                <p>{{str_limit(post_description_or($ya_fields ,'ranking_description'), $limit = 55, $end = '...')}}</p>
            </div>
            <div class="info">
                <!-- <span herf="#" class="more">click to view more details</span> -->
                {!!  str_limit(post_description_or($ya_fields,'ranking_description'), $limit = 90, $end = '<div class="tooltipTitle">&nbspView More ..&nbsp <span class="tooltiptext">'.post_description_or($ya_fields,'ranking_description').'</span></div>') !!}<b><u>GO</u></b>

            </div>
        </a>
    </div>
@endif

@if(in_array('8',$app->permission($app->email,$app->template_id)))
    <div class="item">
        <a href="{{URL::to('master-template') }}/{{nxb_encode($app->template_id)}}/{{nxb_encode($app->app_id)}}/list/schedular" class="card-widget">
            <figure class="icons-holder"><i class="fa fa-calendar"></i></figure>
            <div class="desc">
                <h5>{{post_value_or($ya_fields,'manage_scheduler','Manage Scheduler(s)')}}</h5>
                <p>{{str_limit(post_description_or($ya_fields ,'manage_scheduler_description'), $limit = 55, $end = '...')}}</p>
            </div>
            <div class="info">
                {!!  str_limit(post_description_or($ya_fields,'manage_scheduler_description'), $limit = 90, $end = '<div class="tooltipTitle">&nbspView More ..&nbsp <span class="tooltiptext">'.post_description_or($ya_fields,'manage_scheduler_description').'</span></div>') !!}<b><u>GO</u></b>

            </div>
        </a>
    </div>
@endif

@if($app->template->category == CONST_SHOW || $app->template->category ==TRAINER)

    @if(in_array('14',$app->permission($app->email,$app->template_id)))
        <div class="item">
            <a href="{{URL::to('shows') }}/{{nxb_encode($app->app_id)}}/additional-charges" class="card-widget">
                <figure class="icons-holder"><i class="fa fa-calendar"></i></figure>
                <div class="desc">
                    <h5>{{post_value_or($ya_fields,'shows_additional_charges',' Shows Additional Charges')}}</h5>
                    <p>{{str_limit(post_description_or($ya_fields ,'shows_additional_charges'), $limit = 55, $end = '...')}}</p>
                </div>
                <div class="info">
                    {!!  str_limit(post_description_or($ya_fields,'shows_additional_charges_description'), $limit = 90, $end = '<div class="tooltipTitle">&nbspView More ..&nbsp <span class="tooltiptext">'.post_description_or($ya_fields,'shows_additional_charges_description').'</span></div>') !!}<b><u>GO</u></b>

                </div>
            </a>
        </div>
    @endif

    @if(in_array('12',$app->permission($app->email,$app->template_id)))
        <div class="item">
            <a href="{{URL::to('shows') }}/{{nxb_encode($app->template_id)}}/participants" class="card-widget">
                <figure class="icons-holder"><i class="fa fa-calendar"></i></figure>
                <div class="desc">
                    <h5>{{post_value_or($ya_fields,'show_participants','Shows Participants')}}</h5>
                    <p>{{str_limit(post_description_or($ya_fields ,'show_participants_description'), $limit = 55, $end = '...')}}</p>
                </div>
                <div class="info">
                    {!!  str_limit(post_description_or($ya_fields,'shows_additional_charges_description'), $limit = 90, $end = '<div class="tooltipTitle">&nbspView More ..&nbsp <span class="tooltiptext">'.post_description_or($ya_fields,'shows_additional_charges_description').'</span></div>') !!}<b><u>GO</u></b>

                </div>
            </a>
        </div>
    @endif

    @if(in_array('11',$app->permission($app->email,$app->template_id)))
        <div class="item">
            <a href="{{URL::to('shows') }}/{{nxb_encode($app->template_id)}}/add/scratch" class="card-widget">
                <figure class="icons-holder"><i class="fa fa-calendar"></i></figure>
                <div class="desc">
                    <h5>{{post_value_or($ya_fields,'show_scratch_option','Scratch Option')}}</h5>
                    <p>{{str_limit(post_description_or($ya_fields ,'show_scratch_option_description'), $limit = 55, $end = '...')}}</p>
                </div>
                <div class="info">
                    {!!  str_limit(post_description_or($ya_fields,'show_scratch_option_description'), $limit = 90, $end = '<div class="tooltipTitle">&nbspView More ..&nbsp <span class="tooltiptext">'.post_description_or($ya_fields,'show_scratch_option_description').'</span></div>') !!}<b><u>GO</u></b>

                </div>
            </a>
        </div>
    @endif

@endif

<?php $getShows = getShows($app->template_id); ?>

@if($getShows)
    @if($getShows->show_id > 0)

        @if(in_array('9',$app->permission($app->email,$app->template_id)))
            <div class="item">
                <a href="{{URL::to('master-template') }}/{{nxb_encode($app->template_id)}}/{{nxb_encode($getShows->show_id)}}/masterSchedular" class="card-widget">
                    <figure class="icons-holder"><i class="fa fa-calendar"></i></figure>
                    <div class="desc">
                        <h5>{{post_value_or($ya_fields,'master_scheduler','Master Scheduler')}}</h5>
                        <p>{{str_limit(post_description_or($ya_fields ,'master_scheduler_description'), $limit = 55, $end = '...')}}</p>
                    </div>
                    <div class="info">

                        {!!  str_limit(post_description_or($ya_fields,'master_scheduler_description'), $limit = 90, $end = '<div class="tooltipTitle">&nbspView More ..&nbsp <span class="tooltiptext">'.post_description_or($ya_fields,'master_scheduler_description').'</span></div>') !!}<b><u>GO</u></b>

                    </div>
                </a>
            </div>
        @endif
    @endif
@endif

@if(in_array('3',$app->permission($app->email,$app->template_id)))
    <div class="item">
        <a href="{{URL::to('master-template') }}/{{nxb_encode($app->template_id)}}/invite/users" class="card-widget">
            <figure class="icons-holder"><i class="fa fa-calendar"></i></figure>
            <div class="desc">
                <h5>{{post_value_or($ya_fields,'invite_users','Invite User(s)')}}</h5>
                <p>{{str_limit(post_description_or($ya_fields ,'invite_users_description'), $limit = 55, $end = '...')}}</p>
            </div>
            <div class="info">
                {!!  str_limit(post_description_or($ya_fields,'invite_users_description'), $limit = 90, $end = '<div class="tooltipTitle">&nbspView More ..&nbsp <span class="tooltiptext">'.post_description_or($ya_fields,'invite_users_description').'</span></div>') !!}<b><u>GO</u></b>

            </div>
        </a>
    </div>
@endif

@if($app->masterFeedBackExist($app->template_id) > 0)

    @if(in_array('13',$app->permission($app->email,$app->template_id)))
        <div class="item">
            <a href="{{URL::to('master-template') }}/{{nxb_encode($app->template_id)}}/invitee/feedBack" class="card-widget">
                <figure class="icons-holder"><i class="fa fa-calendar"></i></figure>
                <div class="desc">
                    <h5>{{post_value_or($ya_fields,'feedback','Feedback')}}</h5>
                    <p>{{str_limit(post_description_or($ya_fields ,'feedback_description'), $limit = 55, $end = '...')}}</p>
                </div>
                <div class="info">
                    {!!  str_limit(post_description_or($ya_fields,'feedback_description'), $limit = 90, $end = '<div class="tooltipTitle">&nbspView More ..&nbsp <span class="tooltiptext">'.post_description_or($ya_fields,'feedback_description').'</span></div>') !!}<b><u>GO</u></b>

                </div>
            </a>
        </div>
    @endif
@endif

<?php $templateType = GetTemplateType($app->template_id); ?>

@if($templateType!=SHOW || $templateType!=TRAINER)
    @if(in_array('10',$app->permission($app->email,$app->template_id)))
        <div class="item">
            <a href="{{URL::to('master-template') }}/{{nxb_encode($app->template_id)}}/invoice/listing" class="card-widget">
                <figure class="icons-holder"><i class="fa fa-calendar"></i></figure>
                <div class="desc">
                    <h5>{{post_value_or($ya_fields,'invoice','Invoices')}}</h5>
                    <p>{{str_limit(post_description_or($ya_fields ,'invoice_description'), $limit = 55, $end = '...')}}</p>
                </div>
                <div class="info">
                    {!!  str_limit(post_description_or($ya_fields,'invoice_description'), $limit = 90, $end = '<div class="tooltipTitle">&nbspView More ..&nbsp <span class="tooltiptext">'.post_description_or($ya_fields,'invoice_description').'</span></div>') !!}<b><u>GO</u></b>

                </div>
            </a>
        </div>
    @endif
@endif

@if(in_array('15',$app->permission($app->email,$app->template_id)))
    @if($app->template->category != SHOW)
        <div class="item">
            <a href="{{URL::to('master-template') }}/{{nxb_encode($app->template_id)}}/participants/list" class="card-widget">
                <figure class="icons-holder"><i class="fa fa-desktop"></i></figure>
                <div class="desc">
                    <h5>{{post_value_or($ya_fields,'manage_participants','Manage Participants')}}</h5>
                    <p>{{str_limit(post_description_or($ya_fields,'manage_participants_description'), $limit = 55, $end = '...')}}</p>
                </div>
                <div class="info">
                    <!-- <span herf="#" class="more">click to view more details</span> -->
                    {!!  str_limit(post_description_or($ya_fields,'manage_participants_description'), $limit = 90, $end = '<div class="tooltipTitle">&nbspView More ..&nbsp <span class="tooltiptext">'.post_description_or($ya_fields,'manage_participants_description').'</span></div>') !!}<b><u>GO</u></b>

                </div>
            </a>
        </div>
    @endif
@endif
@if(in_array('16',$app->permission($app->email,$app->template_id)))
    @php($getShows = getShows($app->template_id))
    @if($getShows)
        @if($getShows->show_id > 0)
            <div class="item">
                <a href="{{URL::to('master-template') }}/{{nxb_encode($app->template_id)}}/view/orderSupplies" class="card-widget">
                    <figure class="icons-holder"><i class="fa fa-file-text-o"></i></figure>
                    <div class="desc">
                        <h5>{{post_value_or($ya_fields,'order_supplies','Order Supplies Requests')}}</h5>
                        <p>{{str_limit(post_description_or($ya_fields,'order_supplies_description'), $limit = 55, $end = '...')}}</p>
                    </div>
                    <div class="info">
                        <!-- <span herf="#" class="more">click to view more details</span> -->
                        {!!  str_limit(post_description_or($ya_fields,'order_supplies_description'), $limit = 90, $end = '<div class="tooltipTitle">&nbspView More ..&nbsp <span class="tooltiptext">'.post_description_or($ya_fields,'order_supplies_description').'</span></div>') !!}<b><u>GO</u></b>

                    </div>
                </a>
            </div>
        @endif
    @endif
@endif
    @if($app->template->category == SHOW || $app->template->category == TRAINER)
        @if(in_array('17',$app->permission($app->email,$app->template_id)))
        <div class="item">
            <a href="{{URL::to('shows') }}/{{nxb_encode($app->template_id)}}/spectators" class="card-widget">
                <figure class="icons-holder"><i class="fa fa-thumbs-o-down"></i></figure>
                <div class="desc">
                    <h5>{{post_value_or($ya_fields,'show_spectators','Shows Spectators')}}</h5>
                    <p>{{str_limit(post_description_or($ya_fields,'show_spectators_description'), $limit = 55, $end = '...')}}</p>
                </div>
                <div class="info">
                    <!-- <span herf="#" class="more">click to view more details</span> -->
                    {!!  str_limit(post_description_or($ya_fields,'show_spectators_description'), $limit = 90, $end = '<div class="tooltipTitle">&nbspView More ..&nbsp <span class="tooltiptext">'.post_description_or($ya_fields,'show_spectators_description').'</span></div>') !!}<b><u>GO</u></b>

                </div>
            </a>
        </div>
    @endif
            @if(in_array('18',$app->permission($app->email,$app->template_id)))
                <div class="item">
            <a href="{{URL::to('shows') }}/{{nxb_encode($app->template_id)}}/manageSponsorRequest" class="card-widget">
                <figure class="icons-holder"><i class="fa fa-vcard-o"></i></figure>
                <div class="desc">
                    <h5> {{post_value_or($ya_fields,'manage_sponsor_categories','Manage Sponsor Categories')}}</h5>
                    <p>{{str_limit(post_description_or($ya_fields,'manage_sponsor_categories_description'), $limit = 55, $end = '...')}}</p>
                </div>
                <div class="info">
                    <!-- <span herf="#" class="more">click to view more details</span> -->
                    {!!  str_limit(post_description_or($ya_fields,'manage_sponsor_categories_description'), $limit = 90, $end = '<div class="tooltipTitle">&nbspView More ..&nbsp <span class="tooltiptext">'.post_description_or($ya_fields,'manage_sponsor_categories_description').'</span></div>') !!}<b><u>GO</u></b>

                </div>
            </a>
        </div>
            @endif


            @if(in_array('19',$app->permission($app->email,$app->template_id)))

            <div class="item">
                <a href="{{URL::to('shows') }}/{{nxb_encode($app->template_id)}}/horse/prizClaimForms" class="card-widget">
                    <figure class="icons-holder"><i class="fa fa-th-list"></i></figure>
                    <div class="desc">
                        <h5>{{post_value_or($ya_fields,'1099Forms','1099 Forms')}}</h5>
                        <p>{{str_limit(post_description_or($ya_fields,'1099Forms_description'), $limit = 55, $end = '...')}}</p>
                    </div>
                    <div class="info">
                        <!-- <span herf="#" class="more">click to view more details</span> -->
                        {!!  str_limit(post_description_or($ya_fields,'1099Forms_description'), $limit = 90, $end = '<div class="tooltipTitle">&nbspView More ..&nbsp <span class="tooltiptext">'.post_description_or($ya_fields,'1099Forms_description').'</span></div>') !!}<b><u>GO</u></b>

                    </div>
                </a>
            </div>
            @endif
            @if(in_array('20',$app->permission($app->email,$app->template_id)))
            <div class="item">
                <a href="{{URL::to('shows') }}/{{nxb_encode($app->template_id)}}/showStables" class="card-widget">
                    <figure class="icons-holder"><i class="fa fa-vcard-o"></i></figure>
                    <div class="desc">
                        <h5>{{post_value_or($ya_fields,'show_stables','Show Stables')}}</h5>
                        <p>{{str_limit(post_description_or($ya_fields,'show_stables_description'), $limit = 55, $end = '...')}}</p>
                    </div>
                    <div class="info">
                        <!-- <span herf="#" class="more">click to view more details</span> -->
                        {!!  str_limit(post_description_or($ya_fields,'show_stables_description'), $limit = 90, $end = '<div class="tooltipTitle">&nbspView More ..&nbsp <span class="tooltiptext">'.post_description_or($ya_fields,'show_stables_description').'</span></div>') !!}<b><u>GO</u></b>

                    </div>
                </a>
            </div>
            @endif

            @if(in_array('21',$app->permission($app->email,$app->template_id)))
            <div class="item">
                <a href="{{URL::to('shows') }}/{{nxb_encode($app->template_id)}}/showSponsors" class="card-widget">
                    <figure class="icons-holder"><i class="fa fa-user"></i></figure>
                    <div class="desc">
                        <h5>{{post_value_or($ya_fields,'show_sponsors','Sponsors')}}</h5>
                        <p>{{str_limit(post_description_or($ya_fields,'show_sponsors_description'), $limit = 55, $end = '...')}}</p>
                    </div>
                    <div class="info">
                        <!-- <span herf="#" class="more">click to view more details</span> -->
                        {!!  str_limit(post_description_or($ya_fields,'show_sponsors_description'), $limit = 90, $end = '<div class="tooltipTitle">&nbspView More ..&nbsp <span class="tooltiptext">'.post_description_or($ya_fields,'show_sponsors_description').'</span></div>') !!}<b><u>GO</u></b>

                    </div>
                </a>
            </div>
            @endif

    @endif

    @if($app->template->category != HORSE)
        @if(in_array('22',$app->permission($app->email,$app->template_id)))
        <div class="item">
            <a href="{{URL::to('shows') }}/low-participant-view/{{nxb_encode($app->template_id)}}" class="card-widget">
                <figure class="icons-holder"><i class="fa fa-barcode"></i></figure>
                <div class="desc">
                    <h5>{{post_value_or($ya_fields,'low_participants','Low Participants')}}</h5>
                    <p>{{str_limit(post_description_or($ya_fields,'low_participants_description'), $limit = 55, $end = '...')}}</p>
                </div>
                <div class="info">
                    <!-- <span herf="#" class="more">click to view more details</span> -->
                    {!!  str_limit(post_description_or($ya_fields,'low_participants_description'), $limit = 90, $end = '<div class="tooltipTitle">&nbspView More ..&nbsp <span class="tooltiptext">'.post_description_or($ya_fields,'low_participants_description').'</span></div>') !!}<b><u>GO</u></b>

                </div>
            </a>
        </div>
        @endif

            @if(in_array('23',$app->permission($app->email,$app->template_id)))

            <div class="item">
                <a href="{{URL::to('master-template') }}/{{nxb_encode($app->template_id)}}/addScratchEntries" class="card-widget">
                    <figure class="icons-holder"><i class="fa fa-desktop"></i></figure>
                    <div class="desc">
                        <h5> {{post_value_or($ya_fields,'add_scratch_entries','Add/Scratch Entries')}}</h5>
                        <p>{{str_limit(post_description_or($ya_fields,'add_scratch_entries_description'), $limit = 55, $end = '...')}}</p>
                    </div>
                    <div class="info">
                        <!-- <span herf="#" class="more">click to view more details</span> -->
                        {!!  str_limit(post_description_or($ya_fields,'add_scratch_entries_description'), $limit = 90, $end = '<div class="tooltipTitle">&nbspView More ..&nbsp <span class="tooltiptext">'.post_description_or($ya_fields,'add_scratch_entries_description').'</span></div>') !!}<b><u>GO</u></b>

                    </div>
                </a>
            </div>
            @endif
    @endif

    @if(in_array('24',$app->permission($app->email,$app->template_id)))
    <div class="item">
        <a href="{{URL::to('master-template') }}/{{nxb_encode($app->template_id)}}/judges/feedBack" class="card-widget">
            <figure class="icons-holder"><i class="fa fa-desktop"></i></figure>
            <div class="desc">
                <h5>{{post_value_or($ya_fields,'judges_feed_back','Judges Feedback')}}</h5>
                <p>{{str_limit(post_description_or($ya_fields,'judges_feed_back_description'), $limit = 55, $end = '...')}}</p>
            </div>
            <div class="info">
                <!-- <span herf="#" class="more">click to view more details</span> -->
                {!!  str_limit(post_description_or($ya_fields,'judges_feed_back_description'), $limit = 90, $end = '<div class="tooltipTitle">&nbspView More ..&nbsp <span class="tooltiptext">'.post_description_or($ya_fields,'judges_feed_back_description').'</span></div>') !!}<b><u>GO</u></b>

            </div>
        </a>
    </div>
    @endif

@elseif($app->status == 0)
<!-- Invite Sub-Participants -->
    <div class="cards-holder pb-25">
        <div class="row">
            <div class="col-lg-8 col-md-8 col-sm-8">You have been blocked by App Owner.
                Contact App Owner to proceed with this applicaton!
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

<!-- ./ Right Panel -->
</div>
</div>
</div>
@else
<div class="row text-center">No Application Exist</div>
@endif
</div>