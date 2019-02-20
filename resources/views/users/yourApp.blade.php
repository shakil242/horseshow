@extends('layouts.equetica2')
@php  $currentTab = \Session('currentTab');  @endphp

@section('blue-header')
    <li class="myApp{{($currentTab=='myApp' || $currentTab=='')?' active':''}} active"><a href="{{url('/user/dashboard')}}">My App</a></li>
    <li class="activity{{($currentTab=='activity')?' active':''}}"><a onclick="getActivityView(null,'1')" href="#activity">Activity Zone</a></li>
    <li class="subParticipants{{($currentTab=='subParticipants')?' active':''}}"><a onclick="loadSubParticipantView()" href="#subParticipants">Sub Participants</a></li>
    <li class="employee{{($currentTab=='employee')?' active':''}}"><a onclick="loadEmployeeView()" href="#employee">Manage Application</a></li>

@endsection


@section('main-content')
    <div class="container-fluid"  id="innerViewCon">
     @include('users.app_innerView', ['app' => $collection,'appCollection',$appCollection])
    </div>

@endsection

@section('footer-scripts')

    <script src="{{ asset('/js/user/home.js') }}"></script>
    <script src="{{ asset('/js/vertical-tab-position.js') }}"></script>
    <script src="{{ asset('/js/scroll-position.js') }}"></script>
    {{--<script src="{{ asset('/js/custom-tabs-cookies.js') }}"></script>--}}
    <script src="{{ asset('/js/shows/changetrainer.js') }}"></script>
    <script src="{{ asset('/js/nxb-search-rapidly.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/js/scheduler-modal.js') }}"></script>
@endsection
