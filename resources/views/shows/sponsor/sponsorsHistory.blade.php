@extends('layouts.equetica2')
@section('main-content')

    <div class="container-fluid">
        <div class="page-menu">
            <div class="row">
                <div class="col left-panel">
                    <div class="d-flex flex-nowrap">
                    <span class="menu-icon mr-15 align-self-start" >
                        <img src="{{asset('img/icons/icon-page-common.svg') }}" />
                    </span>
                        <h1 class="title flex-shrink-1">{{getShowName($show_id)}}
                            <small>{!! Breadcrumbs::render('shows-sponsor-history') !!}</small>
                        </h1>
                    </div>
                </div>
                <div class="right-panel">
                    <div class="desktop-view">
                        <form class="form-inline justify-content-end">
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
                            <i class="fa fa-navicon"></i>
                        </span>

                        <div class="collapse navbar-collapse-responsive navbar-collapse justify-content-end" id="navbarSupportedContent">
                            <form class="form-inline justify-content-end">
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

                @if(count($collection)<=0)
                    <div class="col-md-12">
                        <div class="text-center">No show added yet.</div>
                    </div>
                @else
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-line-braker mt-10 custom-responsive-md " id="crudTable9">
                                <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Category</th>
                                    <th  scope="col">Amount</th>
                                    <th width="50%"  scope="col">Description</th>
                                    <th  scope="col">Sponsored On</th>
                                    <th  scope="col">Action</th>

                                </tr>
                                </thead>
                                <tbody>

                                <?php
                              //  dd($collection);
                                $serial=0; ?>
                                @foreach($collection as $pResponse)
                                    <?php
                                    $catCollection = $pResponse['has_category'];

                                    ?>
                                    @if(count($catCollection)>0)
                                        @foreach($catCollection as $cat)
                                            <?php

                                            $serial = $serial + 1; ?>
                                            <tr>
                                                <td>{{ $serial }}</td>
                                                <td>{{$cat['category_title']}}</td>
                                                <td>${{$cat['category_price']}}</td>
                                                <td>{{ $cat['category_description'] }}</td>

                                                <td>{{getDates($pResponse['created_at'])}}</td>
                                                <td>
                                                    <a href="{{URL::to('shows') }}/{{nxb_encode($pResponse['show_id'])}}/{{nxb_encode($pResponse['sponsor_form_id'])}}/sponsor/1"><i class="fa fa-eye" data-toggle="tooltip" title="View Registration"></i></a>
                                                    <a  class="viewInvoiceBtn"

                                                        href="{{URL::to('master-template') }}/ExportSponsorsView/{{nxb_encode($pResponse['show_id'])}}/{{nxb_encode($pResponse['sponsor_form_id'])}}" class="ic_bd_export"><i class="fa fa-file-pdf-o" data-toggle="tooltip" title="Export PDF"></i></a>
                                               <a href="{{URL::to('shows') }}/viewSponsorInvoice/{{nxb_encode($pResponse['id'])}}">View Invoice</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            </div>
            <!-- ./ Content Panel -->
        </div>
    </div>



@endsection

@section('footer-scripts')
    @include('layouts.partials.datatable')
    <link href="{{ asset('/css/addMoreCollapse.css') }}" rel="stylesheet" />
@endsection