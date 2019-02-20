@extends('layouts.equetica2')

@section('custom-htmlheader')
    <!-- Search populate select multiple-->
    <script src="{{ asset('/js/vender/bootstrap3-typeahead.min.js') }}"></script>
    <!-- END:Search populate select multiple-->
@endsection

@section('main-content')
    
<!-- ================= CONTENT AREA ================== -->


@php

    $templateType = GetTemplateType($template_id);

@endphp




<div class="main-contents">
    <div class="container-fluid">

        <div class="page-menu">
            
            <div class="row">
                <div class="col left-panel">
                    <div class="d-flex flex-nowrap">
                    <span class="menu-icon mr-15 align-self-start" >
                        <img src="{{asset('img/icons/icon-page-common.svg') }}" />
                    </span>
                    <h1 class="title flex-shrink-1">Additional Charges
                        <small> {!! Breadcrumbs::render('master-template-additional-charges',0) !!}</small>
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
                @if(Session::has('message'))
                    <div class="alert alert-info" role="alert">{{ Session::get('message') }}</div>
                @endif
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
                    <div class="col-sm-12">
                        <div class="col-sm-6 pull-right"><input type="button" name="" class="btn btn-secondary addNewCharges pull-right" value="Add Charges"> </div>
                    </div>
                </div>
                <div class="charges-hidden-form ">
                    {!! Form::open(['url'=>'shows/additional-charges/store','method'=>'post',"class"=>'additiona-charge-form']) !!}
                    <div id="input-hidden-for-edit"></div>
                    <div class="row row box-shadow bg-white p-4 mb-30">
                        <div class="col-sm-4">
                            {!! Form::text('title', null , ['class' =>"form-control mb-4",'placeholder'=>"Add Title",'required'=>"required"]) !!}
                            {!! Form::number('amount', null , ['class' =>"form-control ",'placeholder'=>"Add Amount in $",'required'=>"required",'step'=>'any']) !!}
                        </div>
                        <div class="col-sm-4">
                            {!! Form::textarea('description', null , ['class' =>"form-control",'placeholder'=>"Add the description",'rows'=>"3"]) !!}
                        </div>
                        @if($templateType!=TRAINER)
                        <div class="col-sm-2">
                          <label> {!! Form::checkbox('required', '1', false); !!} <span> Required to pay</span></label>
                        </div>
                        @endif

                        <div class="col-sm-2">{!! Form::submit("Save" , ['class' =>"btn btn-secondary",'id'=>'storeonly']) !!} </div>
                        <input type="hidden" name="app_id" value="{{$app_id}}">
                        <input type="hidden" name="template_id" value="{{$template_id}}">
                    </div>
                    {!! Form::close() !!}
                </div>
            
            <div class="row">
                    <div class="col-md-12">
                            
                        <!-- TAB CONTENT -->
                        <div class="tab-content" id="myTabContent">
                            <!-- Tab Data Divisions -->
                            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="division-tab">
                                <div class="table-responsive">
                                    <table class="table table-line-braker mt-10 custom-responsive-md " id="crudTable9">
                                        <thead>
                                            <tr>
                                                <th scope="col" style="width:5%">#</th>
                                                <th scope="col">Title</th>
                                                <th scope="col">Description</th>
                                                <th scope="col">Amount</th>

                                                @if($templateType!=TRAINER)
                                                <th scope="col">Required</th>
                                                @endif

                                                <th scope="col" class="action">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                                @if(sizeof($additional_charges)>0)
                                                    @foreach($additional_charges as $pResponse)
                                                        <?php $serial = $loop->index + 1; ?>
                                                        <tr>
                                                            <td>
                                                                {{ $serial }}
                                                            </td>
                                                            <td>{{$pResponse->title}} <input class="list-title" type="hidden" value="{{$pResponse->title}}"></td>

                                                            <td>{{$pResponse->description}}<input class="list-description" type="hidden" value="{{$pResponse->description}}"></td>

                                                            <td>{{getpriceFormate($pResponse->amount)}}<input class="list-amount" type="hidden" value="{{$pResponse->amount}}"></td>

                                                            @if($templateType!=TRAINER)
                                                            <td>@if($pResponse->required == 1) Required @else Not Required @endif<input class="list-required" type="hidden" value="{{$pResponse->required}}"></td>
                                                            @endif

                                                            <td class="action">
                                                                <a href="{{URL::to('shows/additional-charges/delete') }}/{{ nxb_encode($pResponse->id) }}" onclick="return confirm('Are you sure you want to delete?')"><i data-toggle="tooltip" title="Delete" class="fa fa-trash"></i></a>
                                                                <a href="#" class="edit-additional-charges" data-attr="{{$pResponse->id}}"><i data-toggle="tooltip" title="Edit" class="fa fa-edit"></i></a>   
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


    <script src="{{ asset('/js/ajax-dynamic-assets-table.js') }}"></script>
    <script src="{{ asset('/js/shows/restriction.js') }}"></script>

    @include('layouts.partials.datatable')
@endsection
