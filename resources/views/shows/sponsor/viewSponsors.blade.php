@extends('layouts.equetica2')


@section('main-content')


<!-- ================= CONTENT AREA ================== -->
    <div class="container-fluid">

        <div class="page-menu">
            
            <div class="row">
                <div class="col left-panel">
                    <div class="d-flex flex-nowrap">
                    <span class="menu-icon mr-15 align-self-start" >
                        <img src="{{asset('img/icons/icon-page-common.svg') }}" />
                    </span>
                    <h1 class="title flex-shrink-1">
                    <?php $ya_fields = getButtonLabelFromTemplateId($template_id,'ya_fields'); ?>
                        {{post_value_or($ya_fields,'','Shows Sponsors')}}
                        <small> {!! Breadcrumbs::render('shows-sponsor-listing', $template_id) !!} </small>
                    </h1>
                    </div>
                </div>
                <div class="right-panel">
                    <div class="desktop-view">
                        <form class="form-inline justify-content-end">
                         <!--    <button class="btn btn-sm btn-primary btn-rounded mr-10" type="button">Trake Price</button>
                            <button class="btn btn-sm btn-primary btn-rounded mr-10" type="button">Export All Assets</button>
                             -->
                            <div class="search-field mr-10">
                                <div class="input-group">
                                <input type="text" class="form-control" placeholder="" id="myInputTextField" aria-label="Username" aria-describedby="basic-addon1">
                                <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1"><img src="{{asset('img/icons/icon-search.svg') }}" /></span>
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
                            <!-- <button class="btn btn-sm btn-primary btn-rounded mr-10" type="button">Trake Price</button>
                            <button class="btn btn-sm btn-primary btn-rounded mr-10" type="button">Export All Assets</button>
                             -->
                            <div class="search-field">
                                <div class="input-group">
                                    <input type="text" class="form-control" id="myInputTextField" placeholder="" aria-label="Username" aria-describedby="basic-addon1">
                                <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1"><img src="{{asset('img/icons/icon-search.svg') }}" /></span>
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
                        <img src="{{asset('img/icons/icon-close.svg') }}" />
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
                                    <span class="input-group-text" id="basic-addon1"><img src="{{asset('img/icons/icon-search.svg') }}" /></span>
                                    </div>
                                    </div>
                                </div>
                                </form>  
                            </div>
                            <!-- /.col-md-6  -->
                            <!-- col-md-6  -->
                           <!--  <div class="col-md-6 text-center-sm">
                                <button class="btn btn-sm btn-primary btn-rounded mr-10 mb-10" type="button">Trake Price</button>
                                <button class="btn btn-sm btn-primary btn-rounded mr-10 mb-10" type="button">Export All Assets</button>
                            </div> -->
                            <!-- /.col-md-6  -->

                        </div>
                    </div>
                </div>
            </div>
        </div>


        
        <!-- Content Panel -->
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

            <div class="row">
                <div class="col-md-12">

                <div class="accordion-light" role="tablist" aria-multiselectable="true">
                   @if($manageShows->count()>0)
                    @foreach($manageShows as $idx => $show)
                    <div class="slide-holder">
                        <h5 class="card-header">
                            <a class="d-block title collapsed" data-toggle="collapse" href="#collapse{{$idx}}" aria-expanded="true" aria-controls="collapse-example" id="heading-example">
                                @if($show->title != null) {{$show->title}} @else Show Title @endif
                            </a>
                        </h5>
                        <div id="collapse{{$idx}}" class="collapse" aria-labelledby="heading-example">
                            <div class="card-body">
                              <?php $collection = $show->sponsorsBilling; ?>
                              @if(!$collection->count())
                                    <div class="">
                                      <div class="col-lg-5 col-md-5 col-sm-12">No Participants yet.</div>
                                    </div>
                                  @else
                                    <?php
                                    $data = getScratchHorseCount($show->id);
                                    ?>
                                        <div class="col-md-12">
                                            <div class="table-responsive">
                                                <table class="table table-line-braker mt-10 custom-responsive-md dataViewSponsor" id="" data-id="{{$show->id}}">
                                     <thead class="hidden-xs">
                                         <tr>
                                            <th style="width:4%">#</th>
                                            <th style="width:10%">Name</th>
                                             <th style="width:10%">User name</th>
                                             <th style="width:15%">Categories</th>
                                            <th style="width:15%">Amount</th>
                                             <th style="width:15%">Sponsored On</th>
                                            <th class="action" style="width:25%">Action</th>
                                          </tr>
                                      </thead>
                                      <tbody>
                                        @foreach($collection as $pResponse)
                                            <?php $serial = $loop->index + 1;
                                               $userId =  $pResponse->user_id;
                                               $show_id = $pResponse->show_id;

                                              // dd($pResponse->toArray());
                                            ?>
                                            <tr>
                                                <td>{{ $serial }}</td>
                                                <td>{{GetSponsorName($pResponse->sponsor_form_id,$pResponse->user->id)}}</td>
                                              <td>{{getUserNamefromid($pResponse->sender_id)}}</td>
                                                <td>
                                                    @if(!is_null($pResponse->hasCategory))
                                                    <?php echo getCategories($pResponse->hasCategory) ?>
                                                @endif
                                                </td>
                                                <td>${{getCategoriesAmount($pResponse->hasCategory)}}</td>
                                                <td>{{getDates($pResponse->created_at)}}</td>
                                                <td class="action">
                                                  <a href="{{URL::to('shows') }}/{{nxb_encode($pResponse->show_id)}}/{{nxb_encode($pResponse->sponsor_form_id)}}/sponsor/1">
                                                    <i data-toggle="tooltip" data-placement="top" title="View This" class="fa fa-eye"></i>
                                                  </a>
                                                    <a class="viewInvoiceBtn" href="{{URL::to('master-template') }}/ExportSponsorsView/{{nxb_encode($pResponse->show_id)}}/{{nxb_encode($pResponse->sponsor_form_id)}}" class="ic_bd_export">
                                                      <i class="fa fa-file-pdf-o" data-toggle="tooltip" title="Download pdf"></i>

                                                    </a>
                                                    <a href="{{URL::to('shows') }}/viewSponsorInvoice/{{nxb_encode($pResponse['id'])}}">View Invoice</a>

                                                </td>
                                            </tr>
                                        @endforeach
                                      </tbody>
                                </table>
                                            </div></div>
                                @endif
                              </div>
                          </div>
                      </div>
                    @endforeach
                    @else
                    <div class="panel panel-default">
                      <label>No show added yet!</label>
                    </div>
                    @endif
                    </div>
                    </div>

            </div>
        </div>

                        <!-- ./ TAB CONTENT -->

        <!-- ./ Content Panel -->  
        </div>


<!-- ================= ./ CONTENT AREA ================ -->

@endsection

@section('footer-scripts')
   @include('layouts.partials.datatable')
   <link href="{{ asset('/css/addMoreCollapse.css') }}" rel="stylesheet" />
   <script src="{{ asset('/js/showParticipants.js') }}"></script>
    <style>
        .dataTables_paginate
        {
            text-align: center;
            float: none!important;

        }

    </style>
@endsection