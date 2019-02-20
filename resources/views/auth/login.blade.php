@extends('layouts.equetica-auth')
@section('main-content')
<div class="vertical-center">
        <div class="vertical-content login-panel">
          <a class="badge-logo user-logo" href="#"><img src="{{asset('img/icons/Logo.svg') }}" alt="logo badge image" /></a>
          <h4>User Login</h4>
           @include('admin.layouts.errors')
          <form role="form" method="POST" action="{{ url('/login') }}">
                        {{ csrf_field() }}
            <div class="form-group">
                <input id="identity" type="identity" class="form-control" name="identity" value="{{ old('identity') }}" placeholder="Enter Email" required autofocus>
            </div>
            <div class="form-group">
                <input id="password" placeholder="Enter Password" type="password" class="form-control" name="password" required>            
            </div>
            <div class="form-group">
                 <label>
                    <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : ''}} style="opacity: 0;">
                    <span style=" margin-left: -25px !important">Remember Me</span> 
                 </label>        
            </div>
            <input type="submit" class="btn btn-primary" value="Login">
            <div class="forgot-holder">
               <a class="btn btn-link" href="{{ url('/password/reset') }}">
                Forgot Your Password?
                </a>
            </div>
          </form>
        </div>
      </div>
<script type="text/javascript">
    $(".login-header").hide();
</script>
@endsection
