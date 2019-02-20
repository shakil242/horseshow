@extends('admin.layouts.app')

@section('main-content')

    <div class="vertical-center">
        <p class="vertical-content login-panel">
            <h1 style="text-align: center; margin-bottom: 30px;">User Details</h1>

        <div class="info">
            @if(Session::has('message'))
                <p class="alert {{ Session::get('alert-class', 'alert-success') }}">{{ Session::get('message') }}</p>
            @endif
        </div>
            @if($user->cropped_profile_picture!='')
            <div class="panel-body">
                <div class="row" style="text-align: center; position: relative">
                    @if($user->orignal_profile_picture!='')
                    <a href="javascript:" class="edit_image" onclick="editImageUrl('{{getImageS3($user->orignal_profile_picture)}}')">Edit Image</a>
                   @else
                        <form method="post" action="{{url('settings/imageUpload')}}"  enctype="multipart/form-data" name="imageUplaod" id="imageUplaod">
                            {{csrf_field()}}

                            <input type="hidden" name="userCroppedImage" id="userCroppedImage">

                            <div class="row">
                                <div class="col-md-4 text-center">
                                    <div id="upload-demo" style="width:350px">
                                    </div>
                                </div>
                                <div class="col-md-4" style="padding-top:30px;">
                                    <input type="file" style="width: 100%" placeholder="select image" id="upload" name="userOrignalImage" required>
                                    <button style="margin-top: 30px; margin-left: 5px;" class="btn btn-success upload-result">Upload Image</button>
                                    <a href="javascript:" style="margin-top: 30px; margin-left: 5px;"  class="btn btn-default" onclick="cancelEdit()">Cancel</a>

                                </div>
                            </div>
                            <div class="row">
                            </div>
                        </form>
                    @endif
                </div>
                <div class="row" style="text-align: center; position: relative">
                    <img src="{{getImageS3($user->cropped_profile_picture)}}">
                </div>

            </div>
                <div class="panel-body userEditImage" style="display: none">

                    <div id="ajax-loading" class="loading-ajax" style="display: none;"></div>

                <form method="post" action="{{url('settings/imageUpload')}}"  enctype="multipart/form-data" name="imageUplaod" id="imageUplaod">
                    {{csrf_field()}}

                    <input type="hidden" name="userCroppedImage" id="userCroppedImage">

                    <div class="row">
                        <div class="col-md-4 text-center">
                            <div id="upload-demo" style="width:350px">
                            </div>
                        </div>
                        <div class="col-md-4" style="padding-top:30px;">
                            <input type="file" style="width: 100%" placeholder="select image" id="upload" name="userOrignalImage" required>
                            <button style="margin-top: 30px; margin-left: 5px;" class="btn btn-success upload-result">Upload Image</button>
                            <a href="{{url('settings/removeProfileImage')}}"  style="margin-top: 30px; margin-left: 5px; font-size: 14px; text-transform: capitalize" class="btn btn-primary remove-image">Remove Image</a>
                            <a href="javascript:" style="margin-top: 30px; margin-left: 5px;"  class="btn btn-default" onclick="cancelEdit()">Cancel</a>

                        </div>
                    </div>
                    <div class="row">
                    </div>
                </form>
                </div>

@else

            <div class="panel-body">

                    <span id="addNotesMessage"></span>

                    <form method="post" action="{{url('settings/imageUpload')}}"  enctype="multipart/form-data" name="imageUplaod" id="imageUplaod">
                        {{csrf_field()}}

                        <input type="hidden" name="userCroppedImage" id="userCroppedImage">

                        <div class="row">
                            <div class="col-md-4 text-center">

                                <div id="upload-demo" style="width:350px"></div>
                            </div>
                            <div class="col-md-4" style="padding-top:30px;">
                                <input type="file" placeholder="select image" id="upload" name="userOrignalImage" required>

                                <button style="margin-top: 30px; margin-left: 5px;" class="btn btn-success upload-result">Upload Image</button>
                            </div>

                        </div>
                        <div class="row">
            </div>
                    </form>
                </div>
@endif

<div class="userDetails">

            @if($errors->any())
                @foreach ($errors->all() as $error)
                    <p class="alert alert-class alert-danger">{{ $error }}</p>
                @endforeach
            @endif

        <div class="info">
            @if(Session::has('messageUser'))
                <p class="alert {{ Session::get('alert-class', 'alert-success') }}">{{ Session::get('messageUser') }}</p>
            @endif
        </div>
            <form role="form" method="POST" action="{{ url('settings/updateUser') }}">
                {{ csrf_field() }}
                <div class="form-group">
                    <input id="name" type="text" class="form-control" name="name" value="{{ (isset($user->name) ? $user->name : old('name') ) }}"  placeholder="Full Name *" required >
                </div>
                <div class="form-group">
                    <input id="email" type="text" class="form-control" disabled="disabled" name="email" value="{{ (isset($user->email) ? $user->email : old('email') ) }}"  placeholder="Email *" readonly  >
                </div>
                <div class="form-group">
                    {{ Form::text('location', isset($user->location)? $user->location :'', ['id' => 'search-input',
                            'placeholder'=>"Location *",'required'=>'required' ,'class' => 'form-control']) }}
                </div>
                <div class="form-group">
                    <input id="password" type="password" class="form-control" name="password" placeholder="Password *" >
                </div>
                <div class="form-group">
                    <input id="password" type="password" class="form-control" name="password_confirmation" placeholder="Confirm Password *" >
                </div>
                <div class="form-group">
                </div>

                <div class="forgot-holder">
                 <input type="submit" class="btn-lg btn-success" value="Update">
                </div>
            </form>

    </div>

        </div>
    </div>


    <script type="text/javascript">
        $(".register-header").hide();
    </script>

@endsection
@section('footer-scripts')


<script src="{{ asset('/js/croppie.js') }}"></script>
<link href="{{ asset('/css/croppie.css') }}" rel="stylesheet">
<script src="{{ asset('/js/userProfile.js') }}"></script>
<script src="{{ asset('/js/google-map-script-search.js') }}"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCWp7OvMOkqzMjDTNHDstANUQatmbuWyWo&libraries=places&callback=initialize"
         async defer></script>


@endsection
