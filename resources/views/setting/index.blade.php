@extends('layouts.equetica2')

@section('custom-htmlheader')
    <!-- Search populate select multiple-->
    <script src="{{ asset('/js/vender/bootstrap3-typeahead.min.js') }}"></script>
    <!-- END:Search populate select multiple-->
@endsection

@section('main-content')
    <div class="row">
        <div class="col-sm-8">
            <h1>Settings</h1>
        </div>

    </div>
    <div class="row">
        <div class="info">
            @if(Session::has('message'))
                <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
            @endif
        </div>
    </div>
    <div class="row">
        <div class="col-sm-8">
            {!! Breadcrumbs::render('settings') !!}
        </div>
    </div>
    <!--- App listing -->
    <ul class="nav nav-tabs">
        <li class="active"><a data-toggle="tab" href="#appss">Your Apps</a></li>
        <li><a data-toggle="tab" href="#invited_assets">Invited Assets</a></li>
    </ul>
    <div class="tab-content">
        <div id="appss" class="tab-pane fade in active">
            <div class="row">
                <div class="col-sm-4 action-holder pull-right">
                    <form action="#">
                        <div class="search-form">
                            <input type="text" class="typeahead-apps" placeholder="Search By Name"/>
                            <button type="submit"><i class="fa fa-search" aria-hidden="true"></i></button>
                        </div>
                    </form>
                </div>
            </div>
            <ul class="app-listing">
                @if((!$collection->isEmpty()))
                    @foreach($collection as $app)
                        <li class="Smodule">
                            <div class="app-info">
                                <h4 class="app-name">
                                    <span>App Name</span><em>{{GetTemplateName($app->template_id,$user_id)}}</em></h4>
                                <h4 class="app-status"><span>Status</span><em>{{EmailStatus($app->status)}}</em></h4>
                                <h4 class="app-created">
                                    <span>Invited on App</span><em>{{getDates($app->created_at)}}</em></h4>
                            </div>

                            <div class="app-actions">
                                @if($app->status == 1)

                                    @if($app->block == 1)
                                        <div class="row">
                                            <div class="col-lg-8 col-md-8 col-sm-8">You have been blocked by admin.
                                                Contact admin to proceed with this applicaton!
                                            </div>
                                        </div>
                                    @else
                                         <div class="row">
                                            <?php $Profiler_Forms = getFormsForProfile($app->template_id,PROFILE_APP_OWNER) ?>
                                            @if($Profiler_Forms->count())
                                                @foreach($Profiler_Forms as $form)
                                                <div class="col-lg-3 col-md-4 col-sm-6">
                                                    <a class="app-action-link"
                                                       href="{{URL::to('settings') }}/{{nxb_encode($form->id)}}/1/profile/view">{{$form->name}}</a>
                                                </div>
                                                @endforeach
                                            @else
                                                <div class="col-md-8"> No Form attached for this application.</div>
                                            @endif
                                        </div>
                                    @endif
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
                        <div class="col-lg-5 col-md-5 col-sm-6">You have not been invited to use any application yet!
                        </div>
                    </li>
                @endif
            </ul>
        </div>

        <div id="invited_assets" class="tab-pane fade">
            <div class="row">
                <div class="col-sm-4 action-holder pull-right">
                    <form action="#">
                        <div class="search-form">
                            <input type="text" class="typeahead-apps" placeholder="Search By Name"/>
                            <button type="submit"><i class="fa fa-search" aria-hidden="true"></i></button>
                        </div>
                    </form>
                </div>
            </div>
            <ul class="app-listing">
                @if((!$participant_collection->isEmpty()))
                    @foreach($participant_collection as $app)
                        <li class="Smodule">
                            <div class="app-info">
                                <h4 class="app-name">
                                    <span>App Name</span><em><?php echo getAllTemplatesNames($app->template_id) ?> </span> </em>
                                </h4>  
                                <h4 class="app-status"><span>Status</span><em>{{EmailStatus($app->status)}}</em></h4>
                                <h4 class="app-created">
                                    <span>Invited on App</span><em>{{getDates($app->created_at)}}</em></h4>
                            </div>

                            <div class="app-actions">
                                @if($app->status == 1)
                                    <div class="row"> 
                                    <?php $Profiler_Forms = getFormsForProfile($app->template_id,PROFILE_APP_NORMAL_USER,json_decode($app->invited_profiles)) ?>
                                            @if($Profiler_Forms->count())
                                                @foreach($Profiler_Forms as $form)
                                                <div class="col-lg-3 col-md-4 col-sm-6">
                                                    <a class="app-action-link"
                                                       href="{{URL::to('settings') }}/{{nxb_encode($form->id)}}/2/profile/view">{{$form->name}}</a>
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
                        </li>
                    @endforeach
                @else
                    <li>
                        <div class="col-lg-5 col-md-5 col-sm-6">{{MASTER_TEMPLATE_NO_ASSET_INVITE_TEXT}}</div>
                    </li>
                @endif
            </ul>
        </div>

    </div>

@endsection
@section('footer-scripts')
    <script src="{{ asset('/js/custom-tabs-cookies.js') }}"></script>
    <script src="{{ asset('/js/nxb-search-rapidly.js') }}"></script>
@endsection