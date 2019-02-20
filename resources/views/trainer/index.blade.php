@extends('layouts.equetica2')
@section('custom-htmlheader')
    @include('layouts.partials.form-header')
@endsection
@section('main-content')


    <div class="page-menu">

        <div class="row">
            <div class="col left-panel">
                <div class="d-flex flex-nowrap">
                    <span class="menu-icon mr-15 align-self-start" >
                        <img src="{{asset('img/icons/icon-page-common.svg')}}" />
                    </span>
                    <h1 class="title flex-shrink-1">Trainers
                    </h1>
                </div>
            </div>
            <div class="right-panel">
                <div class="desktop-view">
                    <form class="form-inline justify-content-end">
                        <div class="search-field mr-10">
                            <div class="input-group">
                                <input id="trainer-search" type="text" class="form-control" placeholder="" aria-label="Username" aria-describedby="basic-addon1">
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
                                    <input id="trainer-search" type="text" class="form-control" placeholder="" aria-label="Username" aria-describedby="basic-addon1">
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
    </div>
    <!-- ================= CONTENT AREA ================== -->

    <!-- Content Panel -->
    <div class="white-board">

        <div class="row">
            <div class="info">
                @if(Session::has('message'))
                    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
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
                        <?php
                          $serial = $loop->index + 1;
                          $roleName = '';
                        ?>
                        @if($pResponse->appowner)
                        <div class="item item-four">
                            <div class="card-widget">
                                <!-- Card Action -->
                            <div class="filter" id="dropdownMenuButton">
                            <a href="#" class="filter-icon"  data-toggle="dropdown" type="button" >
                            <i data-toggle="tooltip" title="Actions" class="fa fa-list-ul pl-10 pt-10 pr-10 pb-10"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-custom" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#ProfilesModal{{$pResponse->id}}"> Profile </a>
                            @if(count($horseCollection)==0)
                                <a class="dropdown-item" href="javascript:" onclick="alertBox('Please join a Horse app first and add a Horse!','TYPE_WARNING')">Participate</a>
                            @elseif($horseContains==0)
                                <a class="dropdown-item" href="javascript:" onclick="alertBox('Please add a Horse in your Horse application to participate in the show!','TYPE_WARNING')">Participate</a>
                            @else
                                <a class="dropdown-item" href="{{URL::to('trainer') }}/{{nxb_encode($pResponse->id)}}/participate">Participate</a>
                            @endif
                            @if($pResponse->checkForm()->count()>0)
                                @if($pResponse->checkRequest()->count()>0)
                                    <a class="dropdown-item" href="{{URL::to('master-template') }}/{{nxb_encode($pResponse->template_id)}}/{{nxb_encode($pResponse->id)}}/masterSchedular/{{nxb_encode($pResponse->app_id)}}">View Scheduler
                                </a>
                            @else
                                <a class="dropdown-item" href="{{URL::to('shows') }}/{{nxb_encode($pResponse->template_id)}}/{{nxb_encode($pResponse->app_id)}}/{{nxb_encode($pResponse->id)}}/viewSchedulerForm">View Scheduler
                            </a>
                            @endif
                            @endif
                            @if(getCurrentUserAsTrainer($pResponse->id))
                            <a class="dropdown-item" href="{{URL::to('trainer') }}/{{nxb_encode($pResponse->template_id)}}/{{nxb_encode($pResponse->app_id)}}/{{nxb_encode($pResponse->id)}}/trainer/splite-invoice">Split Invoice</a>
                            <a class="dropdown-item" href="{{URL::to('trainer') }}/{{nxb_encode($pResponse->id)}}/trainer/splite-invoice-history">Split History</a>
                            <a class="dropdown-item" href="{{URL::to('trainer') }}/{{nxb_encode($pResponse->template_id)}}/{{nxb_encode($pResponse->app_id)}}/{{nxb_encode($pResponse->id)}}/trainer/order-supplies">Order Supplies
                            </a>
                            <a class="dropdown-item" href="{{URL::to('trainer') }}/{{nxb_encode($pResponse->template_id)}}/{{nxb_encode($pResponse->app_id)}}/{{nxb_encode($pResponse->id)}}/trainer/viewOrderHistory">View Order History
                            </a>
                            @endif
                            </div>
                            </div>
                            <!-- Caed Data -->
                            <figure class="icons-holder"><img src="{{asset('img/icons/icon-show.svg')}}" /></figure>
                            <div class="desc">
                                <h5>{{$pResponse->title}}<small><span class="text-green">{{$pResponse->show_type}}</span></small></h5>
                            <hr />
                            <div class="row">
                            <div class="col mb-10 text-nowrap">
                            <div class="media no-wrap">
                            <img class="mr-2 align-self-start" src="{{asset('img/icons/icon-start-date.svg')}}" alt="Start Tade">
                            <div class="media-body">{{$pResponse->date_from->format('m-d-Y')}}</div>
                            </div>
                            </div>
                            <div class="col mb-10 text-nowrap">
                            <div class="media">
                            <img class="mr-2" src="{{asset('img/icons/icon-end-date.svg')}}" alt="Start Tade">
                            <div class="media-body">{{$pResponse->date_to->format('m-d-Y')}}</div>
                            </div>
                            </div>
                            </div>
                            <div class="row">
                            <div class="col">
                            <div class="media">
                            <img class="mr-2" src="{{asset('img/icons/icon-location.svg')}}" alt="Start Tade">
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
                        @endif
                        @include('setting.show-modal')

                    @endforeach
                @endif
            @endif
          </div>
        </div>
        <!-- Tab containing all the data tables -->
   
		
@endsection

@section('footer-scripts')
    <script src="{{ asset('/js/ajax-dynamic-assets-table.js') }}"></script>
    @include('layouts.partials.datatable')

    <style>
            #show_search_view .card-widget
            { min-height: 115px!important;}

        .primary-table tr td:last-child a {
            background:#9B9B9B;
            float:none;
            margin:0 0 10px!important ;
            color: #FFFFFF;

        }

      .app-action-link{  display: block;
        padding: 5px 0px;
        text-align: center;
        /*border: 1px solid #efefef;*/
        margin: 0 0 10px!important;
        }
        .col-md-4,.col-md-6 {
            padding: 0px 3px;
        }

    </style>
@endsection
