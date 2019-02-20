@extends('layouts.equetica2')

@section('custom-htmlheader')
  <!-- Search populate select multiple-->
 <script src="{{ asset('/js/vender/bootstrap3-typeahead.min.js') }}"></script>
  <!-- END:Search populate select multiple-->
@endsection
@section('blue-header')
   @endsection
@section('main-content')
    <?php $ya_fields = getButtonLabelFromTemplateId($template_id,'ya_fields'); ?>

    <div class="page-menu">

        <div class="row">
            <div class="col left-panel">
                <div class="d-flex flex-nowrap">
                    <span class="menu-icon mr-15 align-self-start">
                        <img src="{{asset('img/icons/icon-page-common.svg')}}">
                    </span>
                    <h1 class="title flex-shrink-1">{{post_value_or($ya_fields,'participants_response','Participants Responses')}}
                    </h1>
                </div>
            </div>
            <div class="right-panel">
                <div class="desktop-view">
                    <form class="form-inline justify-content-end">

                        <div class="search-field mr-10">
                            <div class="input-group">
                                <input id="searchField" class="form-control" placeholder="" aria-label="Username" aria-describedby="basic-addon1" type="text">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1"><img src="{{asset('img/icons/icon-search.svg')}}"></span>
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
                                    <input class="form-control" placeholder="" aria-label="Username" aria-describedby="basic-addon1" type="text">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1"><img src="{{asset('img/icons/icon-search.svg')}}"></span>
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
                        <img src="{{asset('img/icons/icon-close.svg')}}">
                    </span>
                <div class="menu-links">
                    <div class="row">
                        <!-- col-md-6  -->
                        <div class="col-md-6 mb-10">
                            <form class="form-inline justify-content-end">
                                <div class="search-field">
                                    <div class="input-group">
                                        <input class="form-control" placeholder="" aria-label="Username" aria-describedby="basic-addon1" type="text">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1"><img src="{{asset('img/icons/icon-search.svg')}}"></span>
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

    {{--<h1>{{post_value_or($ya_fields,'participants_response','Participants Responses')}}</h1>--}}
    {{--<div class="col-sm-4 action-holder">--}}
        {{--<form action="#">--}}
            {{--<div class="search-form">--}}
                {{--<input class="form-control input-sm" placeholder="Search By Name" id="myInputTextField" type="search">--}}
                {{--<button type="submit"><i class="fa fa-search" aria-hidden="true"></i></button>--}}
            {{--</div>--}}
        {{--</form>--}}
    {{--</div>--}}

    {{--<div class="row">--}}
        {{--<div class="info">--}}
            {{--@if(Session::has('message'))--}}
                {{--<p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>--}}
            {{--@endif--}}
        {{--</div>--}}
    {{--</div>--}}
    {{--<div class="row">--}}
        {{--<div class="col-sm-12">--}}
            {{--{!! Breadcrumbs::render('template-asset-all-history',$template_id) !!}--}}

        {{--</div>--}}
    {{--</div>--}}

    {{--<div class="row">--}}
        {{--<div class="col-md-12">--}}
            {{--<div class="back"><a onclick="history.go(-1);" class="back-to-all-modules" href="#"><span class="glyphicon glyphicon-circle-arrow-left"></span> Back</a></div>--}}
        {{--</div>--}}
    {{--</div>--}}





    <div class="white-board">

        <div class="row">
            <div class="col">

                @if(!$participantResponse->count())
                    <div class="">
                        <div class="col-lg-5 col-md-5 col-sm-6">{{NO_PARTICIPANT_RESPONSE}}</div>
                    </div>
                @else

                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="division-tab" data-toggle="tab" href="#indivisual" role="tab" aria-controls="indivisual" aria-selected="true">Individual Response</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="showclasses-tab" data-toggle="tab" href="#response" role="tab" aria-controls="response" aria-selected="false">Responses</a>
                    </li>
                </ul>
                    @endif
            </div>

        </div>
        <div class="row">
            <div class="col-md-12">

                <!-- TAB CONTENT -->
                <div class="tab-content" id="myTabContent">
                    <!-- Tab Data Divisions -->

                    <div class="tab-pane fade show active" id="indivisual" role="tabpanel" aria-labelledby="indivisual-tab">
                        <div class="table-responsive">
                            <table id="response_history"  class="table table-line-braker mt-10 custom-responsive-md">
                                <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Submitted By</th>
                                    <th scope="col">Asset Name</th>
                                    <th scope="col">Form Name</th>
                                    @if($templateType==SHOW || $templateType==TRAINER)
                                    <th scope="col">Show Name</th>
                                    @else
                                    <th scope="col">Location</th>
                                    @endif
                                    <th scope="col">Responded On</th>
                                    <th scope="col"  class="action">
                                        Action</th>
                                </tr>
                                </thead>
                                <tbody>


                                </tbody>
                            </table>
                        </div>
                    </div>


                    <!-- Tab Data Show Classes -->
                        <div class="tab-pane fade" id="response" role="tabpanel" aria-labelledby="showclasses-tab">
                            <div class="table-responsive">
                                <table id="crudTable3" class="table table-line-braker mt-10 custom-responsive-md">

                                    <thead class="hidden-xs">
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Form Name</th>
                                        <th scope="col">Module Attached To</th>
                                        <th scope="col">No Of Responses</th>
                                        <th scope="col"  class="action">Actions</th>
                                    </tr>
                                    </thead>

                                    <tbody>

                                    @if(sizeof($forms)>0)
                                        @foreach($forms as $pResponse)
                                            <?php
                                            $serial = $loop->index + 1;
                                            $roleName = '';
                                            ?>
                                            <tr>
                                                <td scope="row">{{ $serial }}</td>
                                                <td><span class="table-title">Form Name</span>{{ getFormNamefromid($pResponse->form_id) }}</td>
                                                <td><span class="table-title">Module Attached To</span>{{ getFormsModuleFromId($pResponse->form_id) }}</td>
                                                <td><span class="table-title">No Of Responses</span>{{ getFormsResponsesfromNId($pResponse->form_id) }}</td>
                                                <td class="action">
                                                    <span class="table-title">Actions</span>
                                                    <a  href="{{URL::to('report') }}/{{nxb_encode($pResponse->form_id)}}/graphics/response" ><i data-toggle="tooltip" title="View Response" class="fa fa-eye"></i></a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                    </tbody>

                                </table>
                            </div>
                        </div>
                    </div>
                <!-- ./ TAB CONTENT -->

                <!-- PAGINATION -->
                {{--<ul class="pagination center">--}}
                    {{--<li class="page-item first"><a class="page-link" href="#"><i class="fa fa-angle-left"></i></a></li>--}}
                    {{--<li class="page-item"><a class="page-link" href="#">1</a></li>--}}
                    {{--<li class="page-item active"><a class="page-link" href="#">2</a></li>--}}
                    {{--<li class="page-item"><a class="page-link" href="#">3</a></li>--}}
                    {{--<li class="page-item"><a class="page-link" href="#">...</a></li>--}}
                    {{--<li class="page-item last"><a class="page-link" href="#"><i class="fa fa-angle-right"></i></a></li>--}}
                {{--</ul>--}}
                <!-- ./ PAGINATION -->
            </div>
        </div>
    </div>
    </div>

@endsection

@section('footer-scripts')
    <script src="{{ asset('/js/ajax-dynamic-assets-table.js') }}"></script>
    @include('layouts.partials.datatable')

    <script>
        $(document).ready(function() {
            var oTableDetails =$('#response_history').DataTable({
                processing: true,
                serverSide: true,
                dataType: "json",
                "order": [[ 5, "desc" ]],

//                dom: "t",
                ajax: '{{ route('master-template-all-history-assets',nxb_encode($template_id)) }}',
                columns: [
                    {data: 'response_number'},
                    {data: 'user_name'},
                    {data: 'asset_name'},
                    {data: 'form_name'},
                    {data: 'miscColumn'},
                    {data: 'created_at'},
                    {mRender:function (data, type, row) {
                        return '<a href="/participant/'+row['response_id']+'/all/response/readonly/" class="table-edit" ><i  data-toggle="tooltip" title="View Response" class="fa fa-eye" aria-hidden="true"></i></a>' +
                            '<a  href="/master-template/exportResponsePdf/'+row['response_id']+'"class="viewInvoiceBtn"  class="ic_bd_export"><i  data-toggle="tooltip" title="Download pdf" class="fa fa-file-pdf-o"></i></a>';
                    }
                    },
                ],
                columnDefs: [
                    {
                        "targets": 0,

                        "render": function (data, type, full, meta) {
                            return meta.settings._iDisplayStart + meta.row + 1;
                        }
                    },
                    { className: "action", "targets": [ 6 ] },

                    // {
                    //     "targets": [0,2,5,-1],
                    //     "orderable":false,
                    //     "searchable":false
                    // }
                ],
                "bLengthChange": false,
                "pageLength": 10,
                "language": {
                    "paginate": {
                        "first":      "First",
                        "last":       "Last",
                        "next":       "<i class='fa fa-angle-right' aria-hidden='true'></i>",
                        "previous":   "<i class='fa fa-angle-left' aria-hidden='true'></i>"
                    },

                },
                search: {
                    "regex": true
                },
                "drawCallback": function () {
                    $('.dataTables_paginate > .pagination').addClass('pager');
                },"fnDrawCallback": function() {
                    $(".selectpicker").selectpicker();
                }
            });
            $( oTableDetails.table().container() ).removeClass( 'form-inline' );

            $('#searchField').keyup(function(){
                oTableDetails.search($(this).val()).draw() ;
            })

        });
    </script>

<style>

    .dataTables_filter{
        float: right;}
    #response_history_filter{
        display: none;
    }
    #crudTable3_filter
    {
        display: none;

    }

</style>
@endsection
