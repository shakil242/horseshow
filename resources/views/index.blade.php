@extends('layouts.app')
@section('home-banner')
<div id="hero">
      <div class="slideshow wow fadeInLeft">
        <div class="slideset">
            <div class="slide slide1">
            <!-- <img src="images/banner-1.jpg" alt="banner image" /> -->
          </div>
            <div class="slide slide2">
            <!-- <img src="images/banner-2.jpg" alt="banner image" /> -->
          </div>
            <div class="slide slide3">
            <!-- <img src="images/banner-3.jpg" alt="banner image" /> -->
          </div>
            <div class="slide slide4">
                <!-- <img src="images/banner-4.jpg" alt="banner image" /> -->
            </div>
            <div class="slide slide5">
                <!-- <img src="images/banner-5.jpg" alt="banner image" /> -->
            </div>
        </div>
        {{--<div class="request-form wow bounceInUp">--}}
          {{--<h2><span>in</span>Revo<sup>2</sup></h2>--}}
          {{--<p>--}}
            {{--The established and continual knowledge base (intelligence) predicts events before they occur.  This provides the opportunity to implement corrective actions.--}}
          {{--</p>--}}
          {{--<a class="btn-register-home" href="{{ url('/register') }}"><i class="fa fa-user" aria-hidden="true"></i>Get Register</a>--}}
          {{--<a data-toggle="modal" data-target="#myModal1" class="btn-how-works" href="#"><i class="fa fa-video-camera" aria-hidden="true"></i>How It Works</a>--}}
        {{--</div>--}}
        <a class="see-more-page hidden-xs" href="#why-panel"><i class="fa fa-angle-down" aria-hidden="true"></i></a>
      </div>
    </div>
@endsection
@section('main-content')



    <!--- section shows -->
    <div id="shows">

        <div class=" container">
           <div class="col-md-12">
            <div class="row">
                <div class="col-md-6">
            <h2 class="wow slideInRight">Shows</h2>
                </div>
                <div class="col-md-6">
            <div class="right-panel">
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
            </div>
            </div>
           </div>
            <hr>

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
            </div>

   </div>
    </div>

    <!--- section Trainers -->
    <div id="trainers">
        <div class=" container">

            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-6">
                        <h2 class="wow slideInRight">Trainers</h2>
                    </div>
                    <div class="col-md-6">
                        <div class="right-panel">
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
                    </div>
                </div>
            </div>
            <hr>
            <div id="trainer_search_view" class="cards-holder pb-25">

                    @if(!$trainerCollection->count())
                            <div class="col-sm-12">{{NO_PARTICIPANT_RESPONSE}}</div>
                    @else
                                        @if(sizeof($trainerCollection)>0)
                                            @foreach($trainerCollection as $pResponse)
                                                <?php
                                                $serial = $loop->index + 1;
                                                $roleName = '';
                                                ?>
                                                @if($pResponse->appowner)
                                                    <div class="item item-three">
                                                        <div class="card-widget">
                                                            <!-- Card Action -->
                                                            <div class="filter" id="dropdownMenuButton">
                                                                <a href="#" class="filter-icon"  data-toggle="dropdown" type="button" >
                                                                    <i data-toggle="tooltip" title="Actions" class="fa fa-list-ul pl-10 pt-10 pr-10 pb-10"></i>
                                                                </a>
                                                                <div class="dropdown-menu dropdown-menu-custom" aria-labelledby="dropdownMenuButton">
                                                                        <a class="dropdown-item" href="#" data-toggle="modal" data-target="#ProfilesModal{{$pResponse->id}}" > Profile </a>
                                                                        <a class="dropdown-item" href="{{URL::to('trainer') }}/{{nxb_encode($pResponse->id)}}/participate" >Participate</a>
                                                                    @if($pResponse->checkForm()->count()>0)
                                                                            @if($pResponse->checkRequest()->count()>0)
                                                                                <a class="dropdown-item" href="{{URL::to('master-template') }}/{{nxb_encode($pResponse->template_id)}}/{{nxb_encode($pResponse->id)}}/masterSchedular/{{nxb_encode($pResponse->app_id)}}" >View Scheduler
                                                                                </a>
                                                                            @else
                                                                                <a class="dropdown-item" href="{{URL::to('shows') }}/{{nxb_encode($pResponse->template_id)}}/{{nxb_encode($pResponse->app_id)}}/{{nxb_encode($pResponse->id)}}/viewSchedulerForm" >View Scheduler
                                                                                </a>
                                                                            @endif
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
                                                @endif
                                            @endforeach
                                        @endif

                    @endif
                </div>
            </div>
    </div>


 <!--- section why panel -->
    <div id="why-panel">
      <div class=" container">
        <h2 class="wow slideInRight">Why Equetica ?</h2>
        <p class="wow slideInLeft">
            The equestrian industry is broken into many silos (barns, veterinarians, trainers, riders, shows and farriers).  Each silo offers the rider’s and industry essential services for success.
            Currently, these individual silos all operate independently of each other; even though they affect each other’s outcome. This disconnect creates monetary pitfalls and large consumption of time and resources to navigate. Not only are these different business silos not communicating with each other, they are not collecting data or information in their own silos.
            Equetica is the first to holistically combine each of these silos in order to glean insight and statistical intelligence resulting in enhanced value for all. Equetica brings to the market a new and proven method of data aggregation, intelligence, and predictive modeling.  This proprietary platform will provide all participants in the industry the ability to substantially reduce the money, time, and resources wasted by not having access to the intelligence we produce.
        </p>
        {{--<img src="{{asset('adminstyle/images/img-view.jpg') }}" alt="images" class="img-responsive wow bounceInUp" />--}}
      </div>
    </div>
    <!--- section features -->
    <div id="features">
      <div class=" container">
        <div class="row">
          <div class="col-sm-4 wow bounceInLeft">
            <div class="feature-holder">
              <h3>Real Time Scheduler</h3>
              <div class="feat-img">
                <img src="{{asset('adminstyle/images/scheduler.png') }}" alt="images" class="img-responsive" />
              </div>
                <div class="home-points">
                    <ul>
                        <li>Self posting on-line</li>
                        <li>Show can send reminders to riders (days, hours, and minutes) before class</li>
                        <li>Show sends results to riders through app</li>
                        <li>Riders can view results on scheduler for individual classes</li>
                        <li>Show can send ring delays to riders</li>
              </ul>
                </div>
            </div>
          </div>
          <div class="col-sm-4 wow bounceInUp">
            <div class="feature-holder">
              <h3>Show Blog</h3>
              <div class="feat-img">
                <img src="{{asset('adminstyle/images/blog.png') }}" alt="images" class="img-responsive" />
              </div>
                <div class="home-points">
                    <ul>

                        <li>Blog specific for only exhibitors and trainers participating in the show</li>
                        <li>Show updates (weather delays, Grand Prix/Derby reminders, exhibitor party)</li>
                        <li>Advertising (horses for lease/sale, braiders available, shippers, sponsors information, and vendors)</li>
                        <li>Videos, pictures, and comments</li>
                        <li>Ask questions</li>
                        <li>Post memories</li>
              </ul>
                </div>
            </div>
          </div>
          <div class="col-sm-4 wow bounceInRight">
            <div class="feature-holder">
              <h3>Billing</h3>
              <div class="feat-img">
                <img src="{{asset('adminstyle/images/billing.png') }}" alt="images" class="img-responsive" />
              </div>
                <div class="home-points">
                    <ul>
                    <li>
                    Pay on-online, so don't have to wait in long office lines</li>
                    <li>Trainers can split charges on-line</li>
                    <li>1099 form filled out on-line if won over $600 in prize money</li>
                    <li>Invoice broken down by individual horse</li>
                    <li>Can pay horses invoices individually or as a group</li>
                    <li>Historical record of show invoices in riding enhancement app in activity zone tab under invoices</li>
              </ul>
                </div>
            </div>
          </div>


            <div class="col-sm-4 wow bounceInLeft">
                <div class="feature-holder">
                    <h3>Spectator Participation</h3>
                    <div class="feat-img">
                        <img src="{{asset('adminstyle/images/spectator.png') }}" alt="images" class="img-responsive" />
                    </div>
                    <div class="home-points">
                        <ul>
                            <li>
                        Register as a spectator and view individual rings schedules and order of go
                        View class results</li>
                            <li>Look up individual riders and horses profiles</li>
                            <li>Helps create more involvement with spectators and exhibitors</li>
                        </ul>
                    </div>

                </div>
            </div>
                    <div class="col-sm-4 wow bounceInUp">
                <div class="feature-holder">
                    <h3>Stabling</h3>
                    <div class="feat-img">
                        <img src="{{asset('adminstyle/images/stable.png') }}" alt="images" class="img-responsive" />
                    </div>
                    <div class="home-points">
                        <ul>
                            <li>
                        Order stall requests</li>
                            <li>Once stall numbers have been assigned by show, exhibitor must update on-line what horse they put in the individual stalls assigned
                    </li>
                            <li>Show can offer multiple stall types with different costs assigned</li>
                            <li>Show can individually email reminders of unpaid stalls</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-sm-4 wow bounceInRight">
                <div class="feature-holder">
                    <h3>Order Supplies</h3>
                    <div class="feat-img">
                        <img src="{{asset('adminstyle/images/OrderSupply.png') }}" alt="images" class="img-responsive" />
                    </div>
                    <div class="home-points">
                    <ul>
                        <li>
                        Trainers and riders can send order requests (hay, bedding, feed, etc.)</li>
                        <li>Order populates to invoice automatically</li>
                        <li>Trainer's able to split costs with customers (itemized or lump sum)</li>
                        <li>View status updates on orders (open, closed)</li>
                        <li>Show or customer can attach specialized comments in regard to the order</li>
                    </ul>
                </div>
            </div>
            </div>
        </div>
      </div>
    </div>
    <!--Testimonials starting-->
           <div id="testimonials" class="wow wow bounceInRight">
               <h2 id="dev-snippet-title" class="text-center slideInRight">Testimonial</h2>
               <div class=" container">
                   <div class="row">
                       <div class="col-md-12">
                           <div class="testimonials-list">

                                <!-- Single Testimonial -->
                               <div class="single-testimonial">
                                   <div class="testimonial-holder">
                                       <div class="testimonial-content">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Adipisci animi consequatur cupiditate delectus dicta dolore dolorem ex harum ipsum laborum nobis nulla odit officia. Adipisci animi consequatur cupiditate delectus dicta dolore dolorem ex harum ipsum laborum nobis nulla odit officia
                                           <div class="testimonial-caret"><i class="fa fa-caret-down"></i></div>
                                       </div>
                                       <div class="row">
                                           <div class="testimonial-user clearfix">
                                               <div class="testimonial-user-image"><img src="{{asset('adminstyle/images/img-admin.png') }}" alt=""></div>
                                               <div class="testimonial-user-name">Dev Krishna <br><a href="#">View Profile</a></div>
                                           </div>
                                       </div>
                                   </div>
                               </div>
                               <!-- End of Single Testimonial -->

                               <!-- Single Testimonial -->
                               <div class="single-testimonial">
                                   <div class="testimonial-holder">
                                       <div class="testimonial-content">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Adipisci animi consequatur cupiditate delectus dicta dolore dolorem ex harum ipsum laborum nobis nulla odit officia. Adipisci animi consequatur cupiditate delectus dicta dolore dolorem ex harum ipsum laborum nobis nulla odit officia
                                           <div class="testimonial-caret"><i class="fa fa-caret-down"></i></div>
                                       </div>
                                       <div class="row">
                                           <div class="testimonial-user clearfix">
                                               <div class="testimonial-user-image"><img src="{{asset('adminstyle/images/img-admin.png') }}" alt=""></div>
                                               <div class="testimonial-user-name">Tom Hiddleston <br><a href="#">View Profile</a></div>
                                           </div>
                                       </div>
                                   </div>
                               </div>
                                <!-- End of Single Testimonial -->

                               <!-- Single Testimonial -->
                               <div class="single-testimonial">
                                   <div class="testimonial-holder">
                                       <div class="testimonial-content">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Adipisci animi consequatur cupiditate delectus dicta dolore dolorem ex harum ipsum laborum nobis nulla odit officia. Adipisci animi consequatur cupiditate delectus dicta dolore dolorem ex harum ipsum laborum nobis nulla odit officia
                                           <div class="testimonial-caret"><i class="fa fa-caret-down"></i></div>
                                       </div>
                                       <div class="row">
                                           <div class="testimonial-user clearfix">
                                               <div class="testimonial-user-image"><img src="{{asset('adminstyle/images/img-admin.png') }}" alt=""></div>
                                               <div class="testimonial-user-name">Robert Downey jr <br><a href="#">View Profile</a></div>
                                           </div>
                                       </div>
                                   </div>
                               </div>
                               <!-- End of Single Testimonial -->

                               <!-- Single Testimonial -->
                               <div class="single-testimonial">
                                   <div class="testimonial-holder">
                                       <div class="testimonial-content">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Adipisci animi consequatur cupiditate delectus dicta dolore dolorem ex harum ipsum laborum nobis nulla odit officia. Adipisci animi consequatur cupiditate delectus dicta dolore dolorem ex harum ipsum laborum nobis nulla odit officia
                                           <div class="testimonial-caret"><i class="fa fa-caret-down"></i></div>
                                       </div>
                                       <div class="row">
                                           <div class="testimonial-user clearfix">
                                               <div class="testimonial-user-image"><img src="{{asset('adminstyle/images/img-admin.png') }}" alt=""></div>
                                               <div class="testimonial-user-name">Bruce Wayne<br><a href="#">View Profile</a></div>
                                           </div>
                                       </div>
                                   </div>
                               </div>
                               <!-- End of Single Testimonial -->

                           </div>
                       </div>
                   </div>
               </div>
           </div>
    <!--- section about -->
    <!--- section about -->
    <div id="faqs" class="wow bounceInUp">
      <div class="container ">
        <div class="col-sm-12 wow bounceInUp">
          <h2>FAQs</h2>
          <h3><strong>Q:</strong> Question title one</h3>
          <p>
            <strong>A:</strong>Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod.
          </p>
          <h3><strong>Q:</strong> Question title two</h3>
          <p>
            <strong>A:</strong>Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod.
          </p>
          <h3><strong>Q:</strong> Question title three</h3>
          <p>
            <strong>A:</strong>Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod.
          </p>
          <h3><strong>Q:</strong> Question title four</h3>
          <p>
            <strong>A:</strong>Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod.
          </p>
        </div>
    </div>
    <!--- section about -->

 <div style="margin-top: 50px;" id="terms" class="wow wow bounceInUp">
     <div class=" container">
         <div class="col-sm-12 wow bounceInUp">
             <h2 class="slideInRight" style="margin-bottom: 20px; font-size: 40px; color: #651e1c">Terms And Conditions</h2>
    <p> Terms and Conditions are a set of rules and guidelines that a user must agree to in order to use your website or mobile app. It acts as a legal contract between you (the company) who has the website or mobile app and the user who access your website and mobile app.

     It’s up to you to set the rules and guidelines that the user must agree to. You can think of your Terms and Conditions agreement as the legal agreement where you maintain your rights to exclude users from your app in the event that they abuse your app, and where you maintain your legal rights against potential app abusers, and so on.

     Terms and Conditions are also known as Terms of Service or Terms of Use.

     This type of legal agreement can be used for both your website and your mobile app. It’s not required (it’s not recommended actually) to have separate Terms and Conditions agreements: one for your website and one for your mobile app.
    </p>

             <p> Terms and Conditions are a set of rules and guidelines that a user must agree to in order to use your website or mobile app. It acts as a legal contract between you (the company) who has the website or mobile app and the user who access your website and mobile app.

                 It’s up to you to set the rules and guidelines that the user must agree to. You can think of your Terms and Conditions agreement as the legal agreement where you maintain your rights to exclude users from your app in the event that they abuse your app, and where you maintain your legal rights against potential app abusers, and so on.

                 Terms and Conditions are also known as Terms of Service or Terms of Use.

                 This type of legal agreement can be used for both your website and your mobile app. It’s not required (it’s not recommended actually) to have separate Terms and Conditions agreements: one for your website and one for your mobile app.
             </p>


             <p> Terms and Conditions are a set of rules and guidelines that a user must agree to in order to use your website or mobile app. It acts as a legal contract between you (the company) who has the website or mobile app and the user who access your website and mobile app.

                 It’s up to you to set the rules and guidelines that the user must agree to. You can think of your Terms and Conditions agreement as the legal agreement where you maintain your rights to exclude users from your app in the event that they abuse your app, and where you maintain your legal rights against potential app abusers, and so on.

                 Terms and Conditions are also known as Terms of Service or Terms of Use.

                 This type of legal agreement can be used for both your website and your mobile app. It’s not required (it’s not recommended actually) to have separate Terms and Conditions agreements: one for your website and one for your mobile app.
             </p>
             <p> Terms and Conditions are a set of rules and guidelines that a user must agree to in order to use your website or mobile app. It acts as a legal contract between you (the company) who has the website or mobile app and the user who access your website and mobile app.

                 It’s up to you to set the rules and guidelines that the user must agree to. You can think of your Terms and Conditions agreement as the legal agreement where you maintain your rights to exclude users from your app in the event that they abuse your app, and where you maintain your legal rights against potential app abusers, and so on.

                 Terms and Conditions are also known as Terms of Service or Terms of Use.

                 This type of legal agreement can be used for both your website and your mobile app. It’s not required (it’s not recommended actually) to have separate Terms and Conditions agreements: one for your website and one for your mobile app.
             </p>
         </div>
     </div>
 </div>


    <div id="contact">
      <div class="container">
        <h2 class="slideInRight">Contact US</h2>
          <div class="row">
              <div class="col-sm-12">
                  @if(Session::has('message'))
                      <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                  @endif
              </div>
          </div>
          {!! Form::open(['url'=>'/contactUs/save','method'=>'post']) !!}

        <div class="row">

          <div class="col-sm-6 wow bounceInLeft">

              <div class="form-group">
              <input type="text" class="form-control" name="first_name" placeholder="First Name*" required>
            </div>
            <div class="form-group">
              <input type="text" class="form-control" name="last_name" placeholder="Last Name*" required>
            </div>
            <div class="form-group">
              <input type="email"  class="form-control" name="email" placeholder="Email Address*" required>
            </div>
            <div class="form-group">
              <textarea  class="form-control" name="message" style="height: 100px;" placeholder="Message"  required></textarea>
            </div>
              <input id="csrf-token" type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="form-group">
                <input type="submit" value="Say Hi !" class="btn btn-lg btn-primary" />
            </div>
          </div>


            <div class="col-sm-6 wow bounceInRight">
            <div id="googleMap">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2821.8310133152263!2d-93.58437044892806!3d44.98774387899562!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x52b34d86852c160b%3A0xe45dda8e0f5cb94b!2s525+Tamarack+Ave+Suite+B%2C+Long+Lake%2C+MN+55356!5e0!3m2!1sen!2s!4v1545043042223" frameborder="0" style="border:0" allowfullscreen></iframe>
              {{--<iframe src="https://goo.gl/maps/VP8JiG7CocE2" frameborder="0" style="border:0" allowfullscreen></iframe>--}}
            </div>
          </div>
            {!! Form::close() !!}

        </div>


      </div>
    </div>



@endsection
    </div></div>
@section('modals')
<div class="modal wow bounceIn" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="exampleModalLabel">Send Request</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-sm-12">
                <div class="form-group">
                  <input type="text" class="form-control" placeholder="First Name*">
                </div>
                <div class="form-group">
                  <input type="text" class="form-control" placeholder="Last Name*">
                </div>
                <div class="form-group">
                  <input type="text" class="form-control" placeholder="Email Address*">
                </div>
              </div>
              <div class="col-sm-12">
                <div class="form-group">
                  <select>
                    <option value="">Application Type</option>
                    <option value="">Spectator</option>
                    <option value="">Trainer</option>
                    <option value="">Horse Rider</option>
                    <option value="">Vet</option>
                  </select>
                </div>
                <textarea placeholder="Reason To Join*"></textarea>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary">Send Request</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

          </div>
        </div>
      </div>
    </div>
    <div class="modal wow bounceIn" id="myModal1" tabindex="-2" role="dialog" aria-labelledby="myModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h2 class="modal-title" id="myModalLabel1">How Its Works</h2>
          </div>
          <div class="modal-body">
            <div class="how-video-holder">
              <iframe src="https://player.vimeo.com/video/166930720" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                <p>
                  <a href="https://vimeo.com/166930720">SURF IN SIBERIA ARCTIC OCEAN 5</a>
                  from
                  <a href="https://vimeo.com/kokorev">Kokorev Konstantin</a>
                  on <a href="https://vimeo.com">Vimeo</a>.
                </p>
          </div>

        </div>
      </div>
    </div>
    </div>
@endsection
@section('footer-scripts')
    @include('layouts.partials.datatable')
<script>

    var searchRequest = null;
    var url = window.location.href;
    var minlength = 3;
    $('#shows-search').keyup(function () {
        query = $(this).val();
            if (searchRequest != null)
                searchRequest.abort();
            searchRequest = $.ajax({
                type: "GET",
                url: url,
                data: {
                    query: query,
                    type:'show'
                },
                success: function (data) {
                    $("#show_search_view").html(data);
                }
            })
    });


    $('#trainer-search').keyup(function () {
        query = $(this).val();
            if (searchRequest != null)
                searchRequest.abort();
            searchRequest = $.ajax({
                type: "GET",
                url: url,
                data: {
                    query: query,
                    type:'trainer'
                },
                success: function (data) {
                    $("#trainer_search_view").html(data);
                }
            })
    });


    var allTables = $('table.dataTableView2').DataTable({
     
        "pageLength": 10,
        "search": false,

        "language": {
            "paginate": {
                "first":      "First",
                "last":       "Last",
                "next":       "<i class='fa fa-angle-right' aria-hidden='true'></i>",
                "previous":   "<i class='fa fa-angle-left' aria-hidden='true'></i>"
            }
        },"fnPreDrawCallback": function( oSettings ) {

        },"fnDrawCallback": function() {
        },
        "columnDefs": [ { type: 'natural', targets: [ 0, 1 ] } ]

    });


</script>
<style>

    .primary-table tr td:last-child a {
        background:#9B9B9B;
        float:none;
        margin:0 0 10px!important ;
        color: #FFFFFF;

    }
    /*.primary-table tr td:last-child a {*/
    /*color:#9B9B9B;*/
    /*float:right;*/
    /*margin:0 10px;*/
    /*}*/
    .app-action-link{  display: block;
        padding: 5px 0px;
        text-align: center;
        /*border: 1px solid #efefef;*/
        margin: 0 0 10px!important;
    }
    .col-md-4,.col-md-6 {
        padding: 0px 3px;
    }
    .dataTables_filter{
        float: right;}
    .dataTables_length{ display: none}
</style>
@endsection
