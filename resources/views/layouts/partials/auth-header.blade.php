
    <header id="home-header">
      <div class="container">
        <nav class="navbar navbar-default">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="row">
              <div class="col-sm-2">
                <div class="navbar-header">
                  <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                  </button>
                 <a class="logo-holder" href="{{url('/')}}"><img src="{{ asset('/img/icons/Logo.svg')}}" /></a>

                  </div>
              </div>
              <div class="col-sm-10">
                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                  <div class="row">
                    <div class="col-sm-10 menus">
                      <ul class="nav navbar-nav">
                        <li><a href="{{url('/')}}">Home <span class="sr-only">(current)</span></a></li>
                        <li><a href="{{url('/')}}/#why-panel">Why Us</a></li>
                        <li><a href="{{url('/')}}/#shows">Shows</a></li>
                        <li><a href="{{url('/')}}/#trainers">Trainers</a></li>
                        <li><a href="{{url('/')}}/#features">Features</a></li>
                        <li><a href="{{url('/')}}/#contact">Contact</a></li>
                        <li><a href="{{url('/')}}/#faqs">FAQs</a></li>
                      </ul>
                    </div>
                    <div class="col-sm-2">
                      <ul class="nav navbar-nav navbar-right">
                        <li><a class="register-header" href="{{url('/register')}}">Register</a></li>
                        <li><a class="login-header" href="{{url('/login')}}">Login</a></li>
                      </ul>
                    </div>
                  </div>
                </div><!-- /.navbar-collapse -->
              </div>
            </div>
        </nav>
      </div>
    </header>