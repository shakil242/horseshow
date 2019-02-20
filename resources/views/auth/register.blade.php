@extends('layouts.equetica-auth')
@section('main-content')
    <div class="vertical-panel"  style="margin-top: 70px; margin-bottom: 50px;">

      <div class="vertical-center registration-panel">
        <div class="vertical-content login-panel">
          <a class="badge-logo user-logo" href="#"><img   style="margin-top: 10px;" src="{{asset('adminstyle/images/logo.svg') }}" alt="logo badge image" /></a>
           @include('admin.layouts.errors')
          <h4>User Registration</h4>
            <form role="form" method="POST"   action="{{ url('/register') }}">
            {{ csrf_field() }}
            @if(isset($register_via))
            <input type="hidden" name="register_via" value="{{$register_via}}">
            @endif
            <input type="hidden" name="invite_id" value="{{(isset($data['id']) ? $data['id'] : '' )}}">
            <div class="form-group">
                <input id="name" type="text" class="form-control" name="name" value="{{ (isset($data['name']) ? $data['name'] : old('name') ) }}" {{ (isset($data['name']) ? "readonly='readonly'" : "" ) }} placeholder="Full Name *" required >
            </div>
            <div class="form-group">
                <input id="business_name" type="text" class="form-control" name="business_name" value="{{ (isset($data['business_name']) ? $data['business_name'] : old('business_name') ) }}" {{ (isset($data['business_name']) ? "readonly='readonly'" : "" ) }} placeholder="Business Name" >
            </div>
            <div class="form-group">
              <input type="text" class="form-control" placeholder="Email Address *" name="email" value="{{ (isset($data['email']) ? $data['email'] : old('email') ) }}" {{ (isset($data['email']) ? "readonly='readonly'" : "" ) }} required>
            </div>

                <div class="form-group map-location"  initialize="false">
              {{ Form::text('location', isset($model->detail)?$model->detail['location']:'', ['id' => 'search_input_1',
                            'placeholder'=>"City / State *", 'required'=>'required' ,'class' => 'form-control location allow-copy','autocomplete'=>'off']) }}
            </div>
            <div class="form-group">
              <input name="username" value="{{old('username')}}" type="text" class="form-control" placeholder="Username *" required>
            </div>
            <div class="form-group">
             <input id="password" type="password" class="form-control" name="password" placeholder="Password *"  required>
                <small class="text-muted">Password must be six characters</small>
            </div>
            <div class="form-group">
             <input id="password" type="password" class="form-control" name="password_confirmation" placeholder="Confirm Password *"  required>
            </div>

                @if(isset($regisCollection))
                <div class="form-group">
                 <select  name="application" class="form-control" required>
                   <option value="">Select Application</option>
                   @foreach($regisCollection as $row)
                    <option @if(old('application') == $row->id) {{ 'selected' }} @endif value="{{$row->id}}">{{$row->name}}</option>
                    @endforeach
                    </select>
                </div>
                @endif




                <div class="form-group" style="position: relative">
                    <label style="font-weight: normal; font-size: 12px; margin-left:15px;">
                    <input style="opacity: 0;" id="terms" type="checkbox" title="Please agree to our terms and conditions!" class="form-control required" name="termsCondition"   >
                      <span>Agree to accept <a target="_blank" href="{{url('/terms')}}">terms & Conditions</a></span>
                    </label>
                </div>

            <div class="row reg-buttons" style="margin-top: 25px;">
              <div class="col-sm-6">
                <input type="submit" class="btn btn-lg btn-primary" value="Sign Up">
              </div>
              <div class="col-sm-6">
                <input type="button" class="btn btn-lg btn-defualt" value="Cancel">
              </div>
            </div>

            <div class="forgot-holder">
                <a class="btn btn-link" href="{{ url('/login') }}">
                    Already have an account with us?
                </a>
            </div>
          </form>
        </div>
      </div>
    </div>




<style>
label#termsCondition-error {
    position: absolute;
    left: 1px;
    top: 28px;
    font-weight: normal;
    font-size: 12px;
    text-transform: capitalize;
    color: red;
}
</style>
    <script type="text/javascript">
        $(".register-header").hide();

    </script>

    <script src="{{ asset('/js/google-map-script-form.js') }}"></script>

    <div class="col-sm-12 map-location" initialize="false">
        <div id="map_wrapper">
            <div id="map_canvas_1" class="map-canvas mapping"></div>
        </div>
    </div>

@endsection
