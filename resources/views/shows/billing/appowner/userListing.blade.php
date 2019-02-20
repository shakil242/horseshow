    @extends('layouts.equetica2')


    @section('main-content')


<!-- ================= CONTENT AREA ================== -->
<div class="main-contents">
    <div class="container-fluid">

        <div class="page-menu">
            
            <div class="row">
                <div class="col left-panel">
                    <div class="d-flex flex-nowrap">
                    <span class="menu-icon mr-15 align-self-start" >
                        <img src="{{asset('img/icons/icon-page-common.svg') }}" />
                    </span>
                    <h1 class="title flex-shrink-1"><?php $ya_fields = getButtonLabelFromTemplateId($template_id,'ya_fields'); ?>
                        {{post_value_or($ya_fields,'show_invoice','Shows Invoices')}}
                        <small> {!! Breadcrumbs::render('shows-appowner-invoices-listing', nxb_encode($template_id)) !!} </small>
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
                <div class="col">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <!--<li class="nav-item">
                                <a class="nav-link active" id="division-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Orders</a>
                            </li>
                             <li class="nav-item">
                                <a class="nav-link" id="showclasses-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Show Classes</a>
                            </li> -->
                        </ul>
                    </div>

            </div>
            
            <div class="row">
                <div class="col-sm-10 mb-20">&nbsp</div>
                <div class="col-sm-2">
                    <a class="nav-link btn btn-primary" href="{{url('/billing/1')}}">Billing</a>
                </div>

                    <div class="col-md-12">
                        <!-- TAB CONTENT -->
                        <div class="tab-content" id="myTabContent">
                            <!-- Tab Data Divisions -->
                            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="division-tab">
                                <div class="table-responsive">
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
                                                  <?php $collection = $show->participants; ?>
                                                    @if(!$collection->count())
                                                        <div class="">
                                                          <div class="col-lg-5 col-md-5 col-sm-12">No Invoice added yet.</div>
                                                        </div>
                                                      @else
                                                           <table class="table table-line-braker mt-10 custom-responsive-md dataViews" id="" data-id="{{$show->id}}">
                                                            <thead class="hidden-xs">
                                                               <tr>
                                                                  <th style="width:1%;">#</th>
                                                                  <th style="width:25%;">Name</th>
                                                                  <th style="width:35%;">Email</th>
                                                                  <th style="width:15%">Register On</th>
                                                                  <th style="width:15%">Status</th>
                                                                  <th class="action" style="width:15%">Action</th>
                                                                </tr>
                                                            </thead>
                                                                <!-- <tfoot>
                                                                <tr>
                                                                    <td colspan="2">Name</td>
                                                                    <td colspan="2">Email</td>

                                                                </tr>
                                                                </tfoot> -->

                                                                <tbody>
                                                              @foreach($collection as $pResponse)
                                                                  <?php $serial = $loop->index + 1; ?>
                                                                  <tr>
                                                                      <td style="width:1px;padding: 0px 2px">{{ $serial }}</td>
                                                                      <td>{{getUserNamefromEmail($pResponse->email)}}</td>
                                                                      <td>{{$pResponse->email}}</td>
                                                                      <td> {{getDates($pResponse->created_at)}}</td>
                                                                      @php
                                                                        $user_id = getIdFromEmail($pResponse->email);
                                                                      @endphp
                                                                      <td> {{appInvoiceStatus($pResponse->show_id,$user_id)}}</td>
                                                                      <td class="action">
                                                                         <a href="{{URL::to('shows') }}/{{nxb_encode($pResponse->show_id)}}/{{nxb_encode($user_id)}}/horse/invoice/view" class="module-holder">
                                                                            <i class="fa fa-eye"></i>
                                                                         </a>
                                                                      </td>
                                                                  </tr>
                                                              @endforeach
                                                            </tbody>
                                                        </table>
                                                    @endif
                                                  </div>
                                              </div>
                                          </div>
                                      
                                        @endforeach
                                        @else
                                         <div class="panel panel-default">
                                            <label>Please add a show first by clicking on "Manage Shows" from dashboard.</label>
                                          </div>
                                        @endif 
                                        </div> 
                                </div>
                                
                            </div>
                            
                        </div>
                        <!-- ./ TAB CONTENT -->
                    </div>
                </div>
        <!-- ./ Content Panel -->  
        </div>
    </div>
</div>

<!-- ================= ./ CONTENT AREA ================ -->

@endsection

@section('footer-scripts')


   @include('layouts.partials.datatable')
   <link href="{{ asset('/css/addMoreCollapse.css') }}" rel="stylesheet" />
@endsection