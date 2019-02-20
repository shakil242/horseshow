@extends('layouts.equetica-auth')

@section('main-content')
<div class="vertical-center">
        <div class="vertical-content login-panel">
        

                <a class="badge-logo user-logo" href="#"><img src="{{asset('adminstyle/images/logo.svg') }}" alt="logo badge image" /></a>
                <h4>Reset Password</h4>
                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/password/email') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                <input id="email" placeholder="Enter Email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                        </div>

                        <div class="form-group"> 
                            <button type="submit" class="btn btn-primary btn-links">
                                Send Password Reset Link
                            </button>
                        </div>
                    </form>
                </div>
        
    </div>
</div>
@endsection
