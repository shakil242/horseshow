@if (Auth::check())
    @php  $currentTab = \Session('currentTab');  @endphp
    <div class="header-responsive">
        <nav class="navbar navbar-expand-lg">
            <a class="navbar-brand " href="{{url('/')}}"><img src="{{ asset('/img/icons/Logo-white.svg') }}" /></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExample09" aria-controls="navbarsExample09" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"><img src="{{ asset('/img/icons/icon-menu-white.svg') }}" /></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarsExample09">

                <ul class="navbar-nav mr-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="{{url('/dashboard')}}" id="dropdown09"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Dashboard</a>
                        <div class="dropdown-menu" aria-labelledby="dropdown09">
                            <a class="{{($currentTab=='Myapp' || $currentTab=='')?'active':''}} myAPP dropdown-item"  href="{{url('/user/dashboard')}}">My App</a>
                            <a class="{{($currentTab=='activity' || $currentTab=='')?'active':''}} activity dropdown-item"  onclick="getActivityView(null,'1')"  href="javascript:">Activity Zone</a>
                            <a class="{{($currentTab=='subParticipants' || $currentTab=='')?'active':''}} sub dropdown-item"  onclick="loadSubParticipantView(null)" href="javascript:">Sub Participants</a>
                            <a class="{{($currentTab=='employee' || $currentTab=='')?'active':''}} employee dropdown-item"  onclick="loadEmployeeView()" href="javascript:">Manage Application</a>
                        </div>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="{{url('/shows/dashboard')}}" id="dropdown090"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" >Shows</a>
                        <div class="dropdown-menu" aria-labelledby="dropdown090">
                        <a class="{{ request()->is('shows/dashboard') ? 'active' : '' }} dropdown-item" href="{{URL('/shows/dashboard')}}">Upcoming</a>
                        <a class="{{ request()->is('shows/dashboard/current') ? 'active' : '' }} dropdown-item" href="{{URL('/shows/dashboard/current')}}">Current Month</a>
                        <a class="{{ request()->is('shows/dashboard/previous') ? 'active' : '' }} dropdown-item" href="{{URL('/shows/dashboard/previous')}}">Previous</a>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link disabled" href="{{url('/trainer/dashboard')}}">Trainers</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="https://example.com" id="dropdown09" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Blog</a>
                        <div class="dropdown-menu" aria-labelledby="dropdown09">
                            <a class="dropdown-item" href="#">App Based</a>
                            <a class="dropdown-item" href="#">Public</a>
                        </div>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="https://example.com" id="dropdown09" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Billing</a>
                        <div class="dropdown-menu" aria-labelledby="dropdown09">
                            <a class="dropdown-item" href="#">Transffered</a>
                            <a class="dropdown-item" href="#">Payments</a>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link disabled" href="#">Horse Ranking</a>
                    </li>
                </ul>

                <hr />


                <ul class="navbar-nav mr-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="https://example.com" id="dropdown09" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{ str_limit(Auth::user()->name, $limit = 15, $end = ' ') }}</a>
                        <div class="dropdown-menu pb-10" aria-labelledby="dropdown09">
                            <a class="dropdown-item" href="{{url('/settings/user')}}">User Profile</a>
                            <a class="dropdown-item" href="{{url('/paymentMethods')}}">Payment Method</a>
                            <a class="dropdown-item" href="#"><form id="logout-form" action="{{ url('/logout') }}" method="POST" >
                                    {{ csrf_field() }}
                                    <input type="submit" value="Logout" style="background: rgba(0, 0, 0, 0) none repeat scroll 0 0;border: 0 none !important;">
                                </form></a>
                        </div>
                    </li>
                </ul>


            </div>
        </nav>
    </div>

    <header class="main-header sticky-top">
        <div class="top">
            <a class="logo-holder" href="{{url('/')}}"><img src="{{ asset('/img/icons/Logo.svg')}}" /></a>

            <ul class="inline-list nav-list">
                <li class="nav-item {{ request()->is('user/dashboard') ? 'active' : '' }}">
                    <a class="nav-link" href="{{url('/user/dashboard')}}">Dashboard</a>
                </li>
                <li class="nav-item {{ request()->is('shows/dashboard') || request()->is('shows/dashboard/current') || request()->is('shows/dashboard/previous') ? 'active' : '' }}">
                    <a class="nav-link" href="{{url('/shows/dashboard')}}">Shows</a>
                </li>
                <li class="nav-item  {{ request()->is('trainer/dashboard') ? 'active' : '' }}">
                    <a class="nav-link" href="{{url('/trainer/dashboard')}}">Trainers</a>
                </li>
                <li class="nav-item  {{ request()->is('timeline/index') ? 'active' : '' }}">
                    <a class="nav-link" href="{{url('/timeline/index')}}">Blog</a>
                </li>
                {{--<li class="nav-item {{ request()->is('billing') ? 'active' : '' }}">--}}
                    {{--<a class="nav-link" href="{{url('/billing')}}">Billing</a>--}}
                {{--</li>--}}
                <li class="nav-item {{ request()->is('overall/horse/rankings') ? 'active' : '' }}">
                    <a class="nav-link" href="{{url('/overall/horse/rankings')}}">Horse Ranking</a>
                </li>
            </ul>
            <div class="inline-list right-area">
                <div class="nav-item dropdown UserProfile"  title="{{ str_limit(Auth::user()->name, $limit = 15, $end = ' ') }}">
                    <p style="padding: 0px;line-height: 0px;margin: 0px;">{{ str_limit(Auth::user()->name, $limit = 15, $end = ' ') }}</p>
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true"  aria-expanded="false">
                        <img height="40" width="40" class="user-picture" src="{{userImage(Auth::user()->id)}}">
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="{{url('/settings/user')}}"><i class="fa fa-user"></i> User Profile</a>
                        <a class="dropdown-item" href="{{url('/paymentMethods')}}"><i class="fa fa-money"></i>Payment Method</a>
                        <a class="dropdown-item" href="javascript:">
                            <form id="logout-form" action="{{ url('/logout') }}" method="POST" >
                                {{ csrf_field() }}
                                <i class="fa fa-power-off" aria-hidden="true"></i>
                                <input type="submit" value="Logout">
                            </form></a>
                    </div>
                </div>
            </div>

        </div>
        <div class="bottom">
            <ul class="inline-list secondaryMenu">
                @yield('blue-header')
            </ul>
        </div>
    </header>

@else
    <div class="guestHeader">
        @include('layouts.partials.home-header')
    </div>
@endif
