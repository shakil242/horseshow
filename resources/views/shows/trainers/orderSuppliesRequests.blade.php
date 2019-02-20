@extends('layouts.equetica2')

@section('custom-htmlheader')
    <!-- Search populate select multiple-->
    <script src="{{ asset('/js/vender/bootstrap3-typeahead.min.js') }}"></script>
    <!-- END:Search populate select multiple-->
@endsection

@section('blue-header')

@endsection


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
                    <h1 class="title flex-shrink-1">Order Supplies Requests
                        <small>{!! Breadcrumbs::render('show-supplies-order-requests',["template_id"=>$template_id,"type"=>2]) !!}</small>
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
                    <div class="col-md-12">
                            
                        <!-- TAB CONTENT -->
                        <div class="tab-content" id="myTabContent">
                            <!-- Tab Data Divisions -->
                            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="division-tab">
                                <div class="table-responsive">
                                    <table class="table table-line-braker mt-10 custom-responsive-md dataView" id="">
                                        <thead>
                                            <tr>
                                               <th scope="col" style="width:5%">#</th>
                                                <th scope="col">Show Title</th>
                                                <th scope="col">Trainer Name</th>
                                                <th scope="col">Total Amount</th>
                                                <th scope="col">Ordered On</th>
                                                <th scope="col">Status</th>
                                                <th scope="col" class="action">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $ya_fields = getButtonLabelFromTemplateId($template_id,'ya_fields');?>

                                                @if(sizeof($suppliesOrders)>0)
                                                        {{-- $serial =(($suppliesOrders->currentpage()-1)*$suppliesOrders->perpage()); --}}
                                                    @php
                                                        $serial = 0;
                                                    @endphp 
                                                    @foreach($suppliesOrders as $key => $row)
                                                        <?php
                                                        $serial = $serial + 1;
                                                        // exit;
                                                        ?>
                                                        <tr>
                                                            <td>{{ $serial }}</td>
                                                            <td>{{ $row->show->title }}</td>
                                                            <td>{{  $row->trainer->name }}</td>
                                                            <td>${{ ($row->total_amount>0)?$row->total_amount:0 }}</td>
                                                            <td>{{  getDates($row->created_at) }}</td>
                                                            <td>{{ ($row->status==1?'Closed':'Open')  }}</td>

                                                             <td class="action">
                                                                <!-- <a href="#" class="more" type="button" id="dropdownMenuButton" data-toggle="dropdown" >
                                                                    <i data-toggle="tooltip" title="More Action" class="fa fa-list-ul"></i>
                                                                </a>
                                                                <div class="dropdown-menu dropdown-menu-custom" aria-labelledby="dropdownMenuButton">
                                                                    <a class="dropdown-item" href="{{url('shows')}}/trainer/viewOrderDetail/{{nxb_encode($row->id)}}/2">View Order Detail</a>
                                                                </div> -->
                                                                <a href="{{url('shows')}}/trainer/viewOrderDetail/{{nxb_encode($row->id)}}/2"><i data-toggle="tooltip" title="View" class="fa fa-eye"></i></a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    <tr><td colspan="7" style="text-align: center">No Order Exist</td></tr>
                                                @endif

                                        </tbody>
                                    </table>
                                </div>
                                <!-- PAGINATION -->
                                <div class="">

                                    {{-- {{$suppliesOrders->links('layouts.pagination')}} --}}
                                </div>
                                <!-- ./ PAGINATION -->
                                
                            </div>
                            
                            <!-- Tab Data Show Classes -->
                            <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="showclasses-tab">
                                showclasses
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
    <script src="{{ asset('/js/orderSuppliesTable.js') }}"></script>
    
@endsection
