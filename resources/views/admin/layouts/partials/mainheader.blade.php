<header id="header">
      <div class="container">
        <a class="logo" href="{{url('/admin')}}"><img src="{{asset('adminstyle/images/logo.png') }}" alt="logo" /></a>
        <div class="header-right">
          <div class="dropdown">
            <strong href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                <img src="{{userImage(Auth::user()->id)}}" alt="user image" class="user-image" />
              <span class="caret"></span>
            </strong>
            <ul class="dropdown-menu">
              <li><a href="{{url('/admin/user')}}"><i class="fa fa-info-circle" aria-hidden="true"></i> Profile</a></li>
              <li><a href="#"><i class="fa fa-cog" aria-hidden="true"></i> Setting</a></li>
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
          <ul>
            <li><a href="{{url('/admin')}}">Dashboard</a></li>
            <li><a href="{{url('/admin/user/listings')}}">Users</a></li>
            <li class="active"><a href="#">Master Templates</a></li>
            <li><a href="{{url('/admin/points-system')}}">Points System</a></li>
            <li><a href="{{url('/admin/billing')}}">Billing</a></li>
          </ul>
        </nav>

      </div>
    </header>