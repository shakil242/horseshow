@if(sizeof($collection)>0)
    @foreach($collection as $pResponse)
    @php
        $serial = $loop->index + 1;
        $roleName = '';
        $approvedStalls = getApprovedStalls($pResponse->id);
    @endphp
    @if($pResponse->appowner)
        <div class="item item-four">
            <div href="#" class="card-widget">
                <!-- Card Action -->
                <div class="filter" id="dropdownMenuButton" data-toggle="dropdown">
                    <a href="#" class="filter-icon" type="button" >
                        <i data-toggle="tooltip" title="Actions" class="fa fa-list-ul pl-10 pt-10 pr-10 pb-10"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-custom" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item" href="{{URL::to('shows') }}/{{nxb_encode($pResponse->id)}}/trainers">Trainers</a>
                        <a class="dropdown-item"  data-toggle="modal" data-target="#ProfilesModal{{$pResponse->id}}" href="#">Profile</a>
                        @if(count($horseCollection)==0)
                            <a class="dropdown-item" href="javascript:" onclick="alertBox('Please join Riding Enhancement App, add a Horse, Rider and Owner in your application.','TYPE_WARNING')">Participate</a>
                        @elseif($horseContains==0)
                            <a class="dropdown-item" href="javascript:" onclick="alertBox('Please add a Horse, Rider and Owner in your Riding Enhancement App to participate in this show.','TYPE_WARNING')">Participate</a>
                        @elseif($riderContains==0)
                            <a class="dropdown-item" href="javascript:" onclick="alertBox('Please add a Rider in your Riding Enhancement App to participate in this show.','TYPE_WARNING')">Participate</a>
                        @else
                            @if(in_array('Rider',$riderOwner) && in_array('Owner',$riderOwner))
                                <a class="dropdown-item" href="{{URL::to('shows') }}/{{nxb_encode($pResponse->id)}}/participate">Participate</a>
                            @else
                                <a class="dropdown-item" href="javascript:" onclick="alertBox('Please add {{implode(',',$notRiderOwner)}} in your Riding Enhancement App to participate in this show.','TYPE_WARNING')">Participate</a>
                            @endif
                        @endif
                        @if($approvedStalls > 0)
                            <a class="dropdown-item" href="{{URL::to('shows') }}/{{nxb_encode($pResponse->template_id)}}/{{nxb_encode($pResponse->app_id)}}/{{nxb_encode($pResponse->id)}}/trainer/order-supplies">Order Supplies</a>
                        @else
                            <a class="dropdown-item" href="javascript:" onclick="alertBox('Please Wait untill your Stall Request is approved!','TYPE_WARNING')">Order Supplies</a>
                        @endif
                        @if(getCurrentUserAsTrainer($pResponse->id))
                            <a class="dropdown-item" href="{{URL::to('shows') }}/{{nxb_encode($pResponse->template_id)}}/{{nxb_encode($pResponse->app_id)}}/{{nxb_encode($pResponse->id)}}/trainer/splite-invoice">Split Invoice</a>
                            <a class="dropdown-item" href="{{URL::to('shows') }}/{{nxb_encode($pResponse->id)}}/trainer/splite-invoice-history">Split History</a>
                            <a class="dropdown-item" href="{{URL::to('shows') }}/{{nxb_encode($pResponse->id)}}/trainer/riders-index">Register For Riders</a>
                            <a class="dropdown-item" href="{{URL::to('shows') }}/{{nxb_encode($pResponse->template_id)}}/{{nxb_encode($pResponse->app_id)}}/{{nxb_encode($pResponse->id)}}/trainer/viewOrderHistory">View Order History</a>
                        @endif
                        @if($pResponse->checkForm()->count()>0)
                            @if($pResponse->checkRequest()->count()>0)
                                <a class="dropdown-item" href="{{URL::to('master-template') }}/{{nxb_encode($pResponse->template_id)}}/{{nxb_encode($pResponse->id)}}/masterSchedular/{{nxb_encode($pResponse->app_id)}}">View Scheduler</a>
                            @else
                                <a class="dropdown-item" href="{{URL::to('shows') }}/{{nxb_encode($pResponse->template_id)}}/{{nxb_encode($pResponse->app_id)}}/{{nxb_encode($pResponse->id)}}/viewSchedulerForm">View Scheduler</a>
                            @endif
                        @endif
                        @if($pResponse->checkSponsor()->count()>0)
                            <a class="dropdown-item" href="{{URL::to('shows') }}/{{nxb_encode($pResponse->id)}}/sponsor/0">Sponsor Registration</a>
                        @endif
                        @if($pResponse->sponsorsBilling()->count()>0)
                            <a class="dropdown-item" href="{{URL::to('shows') }}/{{nxb_encode($pResponse->id)}}/sponsorHistory">Sponsor History</a>
                        @endif
                        @if($pResponse->sponsorsBilling()->count()>0)
                            <a class="dropdown-item" href="{{URL::to('shows') }}/{{nxb_encode($pResponse->id)}}/showSponsorsDetails">Show Sponsors</a>
                        @endif
                        <a class="dropdown-item" href="{{URL::to('shows') }}/{{nxb_encode($pResponse->id)}}/stallRequest">Stall Request</a>

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
                                <img class="mr-2 align-self-start" src="{{asset('img/icons/icon-start-date.svg')}}" alt="Start Tade">
                                <div class="media-body">{{$pResponse->date_from->toDateString()}}</div>
                            </div>
                        </div>
                        <div class="col mb-10 text-nowrap">
                            <div class="media">
                                <img class="mr-2" src="{{asset('img/icons/icon-end-date.svg')}}" alt="Start Tade">
                                <div class="media-body">{{$pResponse->date_to->toDateString()}}</div>
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
    @endforeach
@endif