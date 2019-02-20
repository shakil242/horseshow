@if(Auth::check())
<header id="header">
      <div class="container">
        <a class="logo" href="{{url('/')}}"><img src="{{asset('adminstyle/images/logo.png') }}" alt="logo" /></a>
        <div class="header-right">
          <div class="dropdown">
            <strong href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
              {{Auth::user()->name}}
            <span class="caret"></span>
            </strong>
            <ul class="dropdown-menu">
              <li><a href="{{url('/settings/user')}}"><i class="fa fa-info-circle" aria-hidden="true"></i>User Profile</a></li>
                <!-- <li><a href="#"><i class="fa fa-cog" aria-hidden="true"></i> Setting</a></li> -->
                <li><a href="#"  data-toggle="modal" data-target="#feedbackModal"><i class="fa fa-flag" aria-hidden="true"></i> Feedback </a></li>
                <li><a href="{{URL::to('participant') }}/billing"><i class="fa fa-credit-card" aria-hidden="true"></i> Billing</a></li>
                <li><a href="#"><form id="logout-form" action="{{ url('/logout') }}" method="POST" >
                                        {{ csrf_field() }}
                                        <i class="fa fa-power-off" aria-hidden="true"></i>
                                        <input type="submit" value="Logout" style="background: rgba(0, 0, 0, 0) none repeat scroll 0 0;border: 0 none !important;">
                                </form></a></li>
            </ul>
          </div>

        </div>
        <span class="bnt-menu"><i class="fa fa-bars" aria-hidden="true"></i></span>

        <nav class="main-menu">
          @if(Auth::user()->user_type == 1)
          <ul>
            <li><a href="{{url('/admin')}}">Dashboard</a></li>
            <li><a href="{{url('/admin/user/listings')}}">Users</a></li>
            <li class="active"><a href="#">Master Templates</a></li>
            <li><a href="#">Billing</a></li>
            <li><a href="#">Reports</a></li>
          </ul>
          @else
          <ul>
            <li class="active"><a href="{{url('/user/dashboard')}}">Dashboard</a></li>
            <li><a href="{{url('/shows/dashboard')}}">Shows</a></li>
            <li><a href="{{url('/trainer/dashboard')}}">Trainers</a></li>
            <li><a href="{{url('/timeline/index')}}">Blog</a></li>
            <li><a href="{{url('/billing')}}">Billing</a></li>
            <li><a href="{{url('/overall/horse/rankings')}}">Horse Ranking</a></li>
          </ul>
          @endif

        </nav>
      </div>
    </header>
@else
    <div class="guestHeader">
    @include('layouts.partials.home-header')
    </div>
@endif