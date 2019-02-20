@extends('layouts.equetica2')
@section('custom-htmlheader')
    @include('layouts.partials.form-header')
@endsection
@section('main-content')
    <!-- ================= CONTENT AREA ================== -->
    <div class="container-fluid">
    @php
        $title = "User Details";
        $added_subtitle ='';
    @endphp
    @include('layouts.partials.pagemenu',['added_title'=>$title,'added_subtitle'=>$added_subtitle,'remove_search'=>1])
    <!-- Content Panel -->
        <div class="white-board">

            <div class="row">
                <div class="info">
                    @if(Session::has('message'))
                        <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                    @endif
                </div>
            </div>



            @if($user->cropped_profile_picture!='')
            <div class="panel-body">
                <div class="row" style="position: relative">
                    <div class="col-md-12 text-center">
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
                                    <a href="javascript:" style="margin-top: 30px; margin-left: 5px;" class="btn btn-success rotate">Rotate Image</a>
                                    <a href="javascript:" style="margin-top: 30px; margin-left: 5px;"  class="btn btn-default" onclick="cancelEdit()">Cancel</a>

                                </div>
                            </div>
                            <div class="row">
                            </div>
                        </form>
                    @endif
                    </div>
                </div>
                <div class="row mb-20" style="position: relative">
                    <div class="col-md-12 text-center">
                    <img src="{{getImageS3($user->cropped_profile_picture)}}">
                    </div>
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
                            <a href="javascript:" style="margin-top: 30px; margin-left: 5px;" class="btn btn-success rotate">Rotate Image</a>

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
           <div class="row">
               <div class="col-md-12">

                <div class="row">
                <div class="col-md-4">
                <div class="form-group">
                <input id="name" type="text" class="form-control" name="name" value="{{ (isset($user->name) ? $user->name : old('name') ) }}"  placeholder="Full Name *" required >
                </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <input id="business_name" type="text" class="form-control" name="business_name" value="{{ (isset($user->business_name) ? $user->business_name : old('business_name') ) }}"  placeholder="Business Name">
                    </div>
                </div>
                <div class="col-md-4">
                <div class="form-group">
                <input id="email" type="text" class="form-control" disabled="disabled" name="email" value="{{ (isset($user->email) ? $user->email : old('email') ) }}"  placeholder="Email *" readonly  >
                </div>
                </div>
                </div>

                <div class="row">
                <div class="col-md-6">
                <div class="form-group">
                <input id="password" type="password" class="form-control" name="password" placeholder="Password *" >
                </div>
                </div>
                <div class="col-md-6">
                <div class="form-group">
                <input id="password" type="password" class="form-control" name="password_confirmation" placeholder="Confirm Password *" >
                </div>
                </div>
                </div>


                <div class="row">
                <div class="col-md-6  map-location"  initialize="false">
                <div class="form-group input-group">
                    {{ Form::text('location', isset($user->location)? $user->location :'', ['id' => 'search_input_1',
                               'placeholder'=>"Location *",'required'=>'required' ,'class' => 'form-control location allow-copy','autocomplete'=>'off']) }}
                </div>
                </div>
                <div class="col-md-6">
                <div class="form-group">
                    <input id="username" type="text" class="form-control" name="username" value="{{ (isset($user->username) ? $user->username : old('username') ) }}"   placeholder="User Name" >
                </div>
                </div>
                </div>


                <div class="forgot-holder row">
                    <div class="col-md-12 text-center">
                 <input type="submit" class="btn btn-success" value="Update">
                </div>
                </div>
               </div>
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
<script src="{{ asset('/js/google-map-script-form.js') }}"></script>

<div class="col-sm-12 map-location" initialize="false">
    <div id="map_wrapper">
        <div id="map_canvas_1" class="map-canvas mapping"></div>
    </div>
</div>

@endsection
