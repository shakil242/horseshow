@extends('layouts.equetica2')
@php  $currentTab = \Session('currentTab');  @endphp
@section('custom-htmlheader')
  <!-- Search populate select multiple-->
 <script src="{{ asset('/js/vender/bootstrap3-typeahead.min.js') }}"></script>
  <!-- END:Search populate select multiple-->
@endsection

@section('blue-header')
<li  class="{{ request()->is('shows/dashboard/Upcoming') ? 'active' : '' }}"><a href="{{URL('/shows/dashboard/Upcoming')}}">Upcoming</a></li>
<li class="{{ request()->is('shows/dashboard') ? 'active' : '' }}"><a href="{{URL('/shows/dashboard')}}">Current Month</a></li>
<li class="{{ request()->is('shows/dashboard/previous') ? 'active' : '' }}"><a href="{{URL('/shows/dashboard/previous')}}">Previous</a></li>
@endsection

@section('main-content')

    <div class="page-menu">

        <div class="row">
            <div class="col left-panel">
                <div class="d-flex flex-nowrap">
                    <span class="menu-icon mr-15 align-self-start" >
                        <img src="{{asset('img/icons/icon-page-common.svg')}}" />
                    </span>
                    <h1 class="title flex-shrink-1">Shows
                        <small>{{request()->is('shows/dashboard/current') ? 'Current Month' :(request()->is('shows/dashboard/previous')?'Previous' : 'Upcoming') }}
                            </small>
                    </h1>
                </div>
            </div>
            <div class="right-panel">
                <div class="desktop-view">
                    <form class="form-inline justify-content-end">
                          <div class="search-field mr-10">
                            <div class="input-group">
                                <input id="shows-search" type="text" class="form-control" placeholder="" aria-label="Username" aria-describedby="basic-addon1">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1"><img src="{{asset('img/icons/icon-search.svg')}}" /></span>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="mobile-view">
                        <span class="menu-icon mr-15" data-toggle="collapse" href="#collapseMoreAction" role="button" aria-expanded="false" aria-controls="collapseMoreAction">
<!--                            <i class="fa fa-expand"></i>-->
                            <i class="fa fa-navicon"></i>
                        </span>

                    <div class="collapse navbar-collapse-responsive navbar-collapse justify-content-end" id="navbarSupportedContent">
                        <form class="form-inline justify-content-end">
                        <div class="search-field">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="" aria-label="Username" aria-describedby="basic-addon1">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1"><img src="{asset('img/icons/icon-search.svg')}}" /></span>
                                    </div>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>

        <div class="collapse-box menu-holder">

            <div class="collapse menu-box MobileViewRightPanel" id="collapseMoreAction">
                    <span class="close-menu" data-toggle="collapse" href="#collapseMoreAction" role="button" aria-expanded="false" aria-controls="collapseMoreAction">
                        <img src="{{asset('img/icons/icon-close.svg')}}" />
                    </span>
                <div class="menu-links">
                    <div class="row">
                        <!-- col-md-6  -->
                        <div class="col-md-6 mb-10">
                            <form class="form-inline justify-content-end">
                                <div class="search-field">
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="" aria-label="Username" aria-describedby="basic-addon1">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1"><img src="{{asset('img/icons/icon-search.svg')}}" /></span>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- /.col-md-6  -->
                        <!-- col-md-6  -->

                        <!-- /.col-md-6  -->

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="white-board">
        <div class="row">
            <div class="info text-center col-md-12 mt-10">
                @if(Session::has('message'))
                    <div class="alert {{ Session::get('alert-class', 'alert-success') }}" role="alert">
                        {{ Session::get('message') }}
                    </div>
                @endif
            </div>
        </div>

        <div id="show_search_view" class="cards-holder pb-25">

            @if(!$collection->count())
                <div class="col-lg-12">
                    <div class="text-center">No Shows Exist</div>
                </div>
            @else
                @if(sizeof($collection)>0)
                    @foreach($collection as $pResponse)
                        @php
                        $serial = $loop->index + 1;
                        $roleName = '';
                        $approvedStalls = getApprovedStalls($pResponse->id);
                        @endphp
                        @if($pResponse->appowner)
                          <div class="item item-four">
                <div class="card-widget">
                    <!-- Card Action -->
                    <div class="filter" id="dropdownMenuButton">
                        <a href="#" class="filter-icon"  data-toggle="dropdown" type="button" >
                            <i data-toggle="tooltip" title="Actions" class="fa fa-list-ul pl-10 pt-10 pr-10 pb-10"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-custom" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item"  data-toggle="modal" data-target="#ProfilesModal{{$pResponse->id}}" href="#">Profile</a>
                            @if(count($horseCollection)==0)
                            <a class="dropdown-item" href="javascript:" onclick="alertBox('Please join Riding Enhancement App, add a Horse, Rider and Owner in your application.','TYPE_WARNING')">Participate</a>
                            @elseif($horseContains==0)
                                <a class="dropdown-item" href="javascript:" onclick="alertBox('Please add a Horse, Rider and Owner in your Riding Enhancement App to participate in this show.','TYPE_WARNING')">Participate</a>
                            @elseif($riderContains==0)
                                <a class="dropdown-item" href="javascript:" onclick="alertBox('Please add a Rider and Owner in your Riding Enhancement App to participate in this show.','TYPE_WARNING')">Participate</a>
                            @else
                                @if(in_array('Rider',$riderOwner) && in_array('Owner',$riderOwner))
                                <a class="dropdown-item" href="{{URL::to('shows') }}/{{nxb_encode($pResponse->id)}}/participate">Participate</a>
                                @else
                                <a class="dropdown-item" href="javascript:" onclick="alertBox('Please add {{implode(',',$notRiderOwner)}} in your Riding Enhancement App to participate in this show.','TYPE_WARNING')">Participate</a>
                                @endif
                            @endif

                            <a class="dropdown-item" href="{{URL::to('shows') }}/{{nxb_encode($pResponse->id)}}/trainers">Trainers</a>

                             @if($pResponse->checkForm()->count()>0)
                                @if($pResponse->checkRequest()->count()>0)
                                <a class="dropdown-item" href="{{URL::to('master-template') }}/{{nxb_encode($pResponse->template_id)}}/{{nxb_encode($pResponse->id)}}/masterSchedular/{{nxb_encode($pResponse->app_id)}}">View Scheduler</a>
                                @else
                                <a class="dropdown-item" href="{{URL::to('shows') }}/{{nxb_encode($pResponse->template_id)}}/{{nxb_encode($pResponse->app_id)}}/{{nxb_encode($pResponse->id)}}/viewSchedulerForm">View Scheduler</a>
                                @endif
                            @endif
                            <a class="dropdown-item" href="{{URL::to('shows') }}/{{nxb_encode($pResponse->id)}}/stallRequest">Stall Request</a>
                            {{--@if($approvedStalls > 0)--}}
                                <a class="dropdown-item" href="{{URL::to('shows') }}/{{nxb_encode($pResponse->template_id)}}/{{nxb_encode($pResponse->app_id)}}/{{nxb_encode($pResponse->id)}}/trainer/order-supplies">Order Supplies</a>
                           {{--@else--}}
                                {{--<a class="dropdown-item" href="javascript:" onclick="alertBox('Please Wait untill your Stall Request is approved!','TYPE_WARNING')">Order Supplies</a>--}}
                           {{--@endif--}}
                           
                            <a class="dropdown-item" href="{{URL::to('shows') }}/{{nxb_encode($pResponse->template_id)}}/{{nxb_encode($pResponse->app_id)}}/{{nxb_encode($pResponse->id)}}/trainer/viewOrderHistory">View Order History</a>
                            @if(getCurrentUserAsTrainer($pResponse->id))
                            <a class="dropdown-item" href="{{URL::to('shows') }}/{{nxb_encode($pResponse->template_id)}}/{{nxb_encode($pResponse->app_id)}}/{{nxb_encode($pResponse->id)}}/trainer/splite-invoice">Split Invoice</a>
                            <a class="dropdown-item" href="{{URL::to('shows') }}/{{nxb_encode($pResponse->id)}}/trainer/splite-invoice-history">Split History</a>
                            <a class="dropdown-item" href="{{URL::to('shows') }}/{{nxb_encode($pResponse->id)}}/trainer/riders-index">Register For Riders</a>
                            @endif
                           
                            @if($pResponse->sponsorsBilling()->count()>0)
                            <a class="dropdown-item" href="{{URL::to('shows') }}/{{nxb_encode($pResponse->id)}}/showSponsorsDetails">Show Sponsors</a>
                            @endif
                            @if($pResponse->checkSponsor()->count()>0)
                            <a class="dropdown-item" href="{{URL::to('shows') }}/{{nxb_encode($pResponse->id)}}/sponsor/0">Sponsor Registration</a>
                            @endif
                            @if($pResponse->sponsorsBilling()->count()>0)
                            <a class="dropdown-item" href="{{URL::to('shows') }}/{{nxb_encode($pResponse->id)}}/sponsorHistory">Sponsor History</a>
                            @endif
                            
                            

                        </div>
                    </div>

                    <!-- Caed Data -->
                    <figure class="icons-holder"><img src="{{asset('img/icons/icon-show.svg')}}" /></figure>
                    <div class="desc">
                        <h5>{{$pResponse->title}} <small><span class="text-green">{{$pResponse->show_type}}</span>  -  {{$pResponse->governing_body}} </small></h5>
                        <hr />
                        <div class="row">
                            <div class="col mb-10 text-nowrap">
                                <div class="media no-wrap">
                                    <img class="mr-2 align-self-start" src="{{asset('img/icons/icon-start-date.svg')}}" alt="Start Date">
                                    <div class="media-body">{{((!empty($pResponse->date_from))?$pResponse->date_from->format('m-d-Y'):'No Start Date')}}</div>
                                </div>
                            </div>
                            <div class="col mb-10 text-nowrap">
                                <div class="media">
                                    <img class="mr-2" src="{{asset('img/icons/icon-end-date.svg')}}" alt="To Date">
                                    <div class="media-body">{{((!empty($pResponse->date_to))?$pResponse->date_to->format('m-d-Y'):'No To Date')}}</div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="media">
                                    <img class="mr-2" src="{{asset('img/icons/icon-location.svg')}}" alt="Location">
                                    <div class="media-body">{{ $pResponse->location }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Card Back -->
                    <div class="info">
                        <div class="justify-content-center">{{$pResponse->show_description}}
                        </div>
                    </div>

                </div>
            </div>
                            @include('setting.show-modal')

                        @endif
                    @endforeach
                        @if(sizeof($collection)>50)
                            <div id="loadMore" class="cards-holder pb-25 col-md-12">
                                <div class="col-md-12 d-flex justify-content-center"  id="remove-row">
                                    <button id="btn-more"  class="btn btn-rounded btn-secondary btn-sm"> Load More </button>
                                </div>

                            </div>
                        @endif
                    @endif
            @endif
        </div>
    </div>

@endsection

@section('footer-scripts')
    <script src="{{ asset('/js/ajax-dynamic-assets-table.js') }}"></script>
    @include('layouts.partials.datatable')
@endsection
