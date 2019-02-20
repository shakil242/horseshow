


    <div class="header-responsive">
        <nav class="navbar navbar-expand-lg">
            <a class="navbar-brand " href="{{url('/')}}"><img src="{{ asset('/img/icons/Logo-white.svg') }}" /></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExample09" aria-controls="navbarsExample09" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"><img src="{{ asset('/img/icons/icon-menu-white.svg') }}" /></span>
            </button>
        </nav>
    </div>

    <header id="home-header" class="main-header top sticky-top">
        <div class="container top">
            <a class="logo-holder" href="{{url('/')}}"><img src="{{ asset('/img/icons/Logo.svg')}}" /></a>

            <ul class="inline-list nav-list">
                <li class="nav-item active"><a href="{{url('/')}}/#hero">Home <span class="sr-only">(current)</span></a></li>
                        <li class="nav-item"><a href="{{url('/')}}/#shows">Shows</a></li>
                        <li class="nav-item"><a href="{{url('/')}}/#trainers">Trainers</a></li>
                        <li class="nav-item"><a href="{{url('/')}}/#why-panel">Why us</a></li>
                        <li class="nav-item"><a href="{{url('/')}}/#features">Features</a></li>
                        <li class="nav-item"><a href="{{url('/')}}/#faqs">FAQs</a></li>
                        <li class="nav-item"><a href="{{url('/')}}/#contact">Contact</a></li>
            </ul>
            <div class="inline-list right-area">
                <div class="nav-item dropdown UserProfile">
                   
                        @if (Auth::guest())
                          <div class="row">
                            <div class="logreg"><a class="login-headers" href="{{url('/login')}}">Login</a></div>
                            <div class="logreg"><a class="login-headers" href="{{url('/register')}}">Register</a></div>
                              
                          </div>
                        @else
                        <ul class="nav navbar-nav navbar-right">
                        <li>
                          <a class="login-toggle" href="javascript:void(0)">{{Auth::user()->name}}</a>
                          <div class="login-home-panel login-home2">
                          <h2 class="hidden-xs">Account</h2>
                            <div class="form-group">
                            <a href="{{ url('/user/dashboard') }}"><i class="fa fa-dashboard" aria-hidden="true"></i> Dashboard</a>
                            <a href="#"><form id="logout-form" action="{{ url('/logout') }}" method="POST" >
                                        {{ csrf_field() }}
                                        <i class="fa fa-power-off" aria-hidden="true"></i>
                                        <input type="submit" value="Logout" style="background: rgba(0, 0, 0, 0) none repeat scroll 0 0;border: 0 none !important;">
                                </form></a>
                            </div>
                          </div>
                        </li>
                        </ul>
                        @endif

                      
                </div>
            </div>

        </div>
    </header>

