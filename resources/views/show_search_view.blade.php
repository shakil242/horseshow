
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
                @endphp
                @if($pResponse->appowner)
                    <div class="item item-three">
                        <div class="card-widget">
                            <!-- Card Action -->
                            <div class="filter" id="dropdownMenuButton">
                                <a href="#" class="filter-icon"  data-toggle="dropdown" type="button" >
                                    <i data-toggle="tooltip" title="Actions" class="fa fa-list-ul pl-10 pt-10 pr-10 pb-10"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-custom" aria-labelledby="dropdownMenuButton">
                                    <a class="dropdown-item" href="{{URL::to('shows') }}/{{nxb_encode($pResponse->id)}}/trainers">Trainers</a>
                                    <a class="dropdown-item"  data-toggle="modal" data-target="#ProfilesModal{{$pResponse->id}}" href="#">Profile</a>
                                    <a class="dropdown-item" href="{{URL::to('shows') }}/{{nxb_encode($pResponse->id)}}/participate">Participate</a>
                                    @if($pResponse->checkForm()->count()>0)
                                        @if($pResponse->checkRequest()->count()>0)
                                            <a class="dropdown-item" href="{{URL::to('master-template') }}/{{nxb_encode($pResponse->template_id)}}/{{nxb_encode($pResponse->id)}}/masterSchedular/{{nxb_encode($pResponse->app_id)}}" >View Scheduler
                                            </a>
                                        @else
                                            <a class="dropdown-item" href="{{URL::to('shows') }}/{{nxb_encode($pResponse->template_id)}}/{{nxb_encode($pResponse->app_id)}}/{{nxb_encode($pResponse->id)}}/viewSchedulerForm">View Scheduler
                                            </a>
                                        @endif
                                    @endif

                                    @if($pResponse->checkSponsor()->count()>0)
                                        <a class="dropdown-item" href="{{URL::to('shows') }}/{{nxb_encode($pResponse->id)}}/sponsor/0">Sponsor Registration
                                        </a>
                                    @endif


                                    @if($pResponse->sponsorsBilling()->count()>0)
                                        <a class="dropdown-item" href="{{URL::to('shows') }}/{{nxb_encode($pResponse->id)}}/sponsorHistory" >Sponsor History
                                        </a>
                                    @endif
                                    @if($pResponse->sponsorsBilling()->count()>0)
                                        <a class="dropdown-item" href="{{URL::to('shows') }}/{{nxb_encode($pResponse->id)}}/showSponsorsDetails">Show Sponsors
                                        </a>
                                    @endif
                                    <a class="dropdown-item" href="{{URL::to('shows') }}/{{nxb_encode($pResponse->id)}}/stallRequest">Stall Request
                                    </a>

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
