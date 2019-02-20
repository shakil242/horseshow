@extends('admin.layouts.auth')

@section('htmlheader_title')
    Log in
@endsection

@section('content')
<body class="hold-transition login-page">
   <div class="vertical-panel">
      <div class="vertical-center">
      
        <div class="vertical-content login-panel">
          <a class="badge-logo" href="{{ url('/home') }}"><img src="{{asset('adminstyle/images/logo-badge.png')}}" alt="logo badge image" /></a>
          <h4>Admin Login</h4>
          <form action="{{ url('/login') }}" method="post">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="form-group">
                <input type="email" class="form-control" placeholder="{{ trans('email') }}" name="email"/>
              
            </div>
            <div class="form-group">
                <input type="password" class="form-control" placeholder="{{ trans('password') }}" name="password"/>
              
            </div>
            <div class="row">
                
                    <button type="submit" class="btn btn-lg btn-primary">Login</button>
                </div><!-- /.col -->
            <div class="row">
            <br>
                @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <strong>Whoops!</strong> {{ trans('message.someproblems') }}<br><br>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
             </div>
            </div>
        </form>

           @include('admin.layouts.partials.scripts_auth')
        </div>
      </div>
    </div>
</body>

@endsection
