@extends('layouts.equetica2')

@section('custom-htmlheader')
    <!-- Search populate select multiple-->
    <script src="{{ asset('/js/vender/bootstrap3-typeahead.min.js') }}"></script>
    <!-- END:Search populate select multiple-->
@endsection

@section('main-content')

 @if(Session::has('message'))
    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
@endif
 <!-- ================= CONTENT AREA ================== -->
    <div class="container-fluid">
    @php
        $title = "Invoice Detail";
        $added_subtitle =Breadcrumbs::render('master-template-invoice-listing',$dataBreadCrumb);
    @endphp
    @include('layouts.partials.pagemenu',['added_title'=>$title,'added_subtitle'=>$added_subtitle])
    <!-- Content Panel -->
        <div class="white-board">

    <div class="row">
        {{ getAlert() }}
    </div>
            <div class="row">
                <div class="col-12">
                    <div class="tabs-header">
                        <ul id="display-scheduler-assets" class="nav nav-tabs">
                            <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#Transffered">Transffered</a></li>
                            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#Payments">Payments</a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="tab-content">
                        <div class="box-shadow bg-white p-4 mt-30 mb-30 tab-pane fade show active" id="Transffered" role="tabpanel" aria-labelledby="division-tab">

                            <div class="table-responsive module-holer rr-datatable">
                            @if(sizeof($invoiceForms)>0)
                                <div class="col-sm-2" style="float: right; text-align: right">
                                    <a  class="btn-sm btn-primary viewInvoiceBtn"  href="{{URL::to('master-template') }}/exportOwnerCsv/{{$dataBreadCrumb['id']}}/transfered" class="ic_bd_export">Export Transfered CSV</a>
                                </div>
                            @endif
                                <div class="col-sm-2" style="margin-left: 0px; padding-left: 0px;">
                                    <div class="commulativeInvoice" style="display: none; margin-top: -16px;">
                                        <input type="button" id="commulativeInvoice" class="btn btn-success" value="Pay Commulative Invoic">
                                        {{--<a style="color: #FFF" class="btn btn-success" href="">Pay Commulative Invoice</a>--}}
                                    </div>
                                </div>

                                <table id="crudTable2" class="table table-line-braker mt-10 custom-responsive-md">
                                    

                                <thead class="hidden-xs">
                                <tr>
                                    <th style="width:8%"># <input data-target="commulativeInvoice" type="checkbox" class="allCheck" > </th>
                                    <th>Asset</th>
                                    <th>Submitted</th>
                                    <th>Title</th>
                                    <th>Event</th>
                                    <th>Amount</th>
                                    <th>Created</th>
                                    <th>Status</th>
                                    <th class="action">Actions</th>
                                </tr>
                                </thead>
                                <tbody>

                                @if(sizeof($invoiceForms)>0)


                                    @foreach($invoiceForms as $pResponse)
                                        <?php
                                        $serial = $loop->index + 1;
                                        $roleName = '';

                                        if($pResponse->billing($pResponse->id)->count() > 0)
                                            $status = 'Paid';
                                        else
                                            $status = 'Pending';
                                        ?>
                                        <tr>
                                            <td>
                                                @if($pResponse->billing($pResponse->id)->count() == 0 && $pResponse->amount > 0)
                                                    <input  value="{{$pResponse->id}}" data-target="commulativeInvoice"  type="checkbox"
                                                           class="singleCheck" name="commulativeInvoice[]">

                                                @endif

                                                {{ $serial }}


                                            </td>
                                            <td><strong class="visible-xs">Asset</strong>{{ GetAssetNamefromId($pResponse->asset_id) }}</td>

                                            <td><strong class="visible-xs">Submitted</strong>{{ $pResponse->submittedInvitee($pResponse->user_id) }}</td>

                                            <td><strong class="visible-xs">Title</strong>{{ $pResponse->invoiceTitle->name }}</td>
                                            <td><strong class="visible-xs">Event</strong>

                                                @if($pResponse->response_id > 0)
                                                <a href="{{URL::to('master-template') }}/{{nxb_encode($pResponse->response_id)}}/{{nxb_encode($pResponse->template_id)}}/viewEvent"> {{ $pResponse->form->name }}</a>
                                                @else
                                                {{ $pResponse->form->name }}
                                                @endif
                                            </td>
                                            <td><strong class="visible-xs">Amount</strong> $ {{ $pResponse->amount }}</td>

                                            <td><strong class="visible-xs">Created</strong>{{ $pResponse->created_at }}</td>

                                            <td><strong class="visible-xs">Status</strong>
                                              <span>{{$status}}</span>
                                            </td>

                                            <td class="action">
                                                <strong class="visible-xs">Actions</strong>
                                                <a href="#" class="more" type="button" id="dropdownMenuButton" data-toggle="dropdown"><i data-toggle="tooltip" title="" class="fa fa-list-ul" data-original-title="More Action"></i></a>
                                                <div class="dropdown-menu dropdown-menu-custom" aria-labelledby="dropdownMenuButton">
                                                    <a class="btn-sm viewInvoiceBtn dropdown-item" href="{{URL::to('master-template') }}/{{nxb_encode($pResponse->id)}}/{{nxb_encode($pResponse->template_id)}}/Invoice/viewInvoice" data-toggle="tooltip" data-placement="top" title="View Invoice">View Invoice</a>
                                                    <a class="btn-sm viewInvoiceBtn dropdown-item" href="{{URL::to('master-template') }}/exportinvoiceCsv/{{nxb_encode($pResponse->id)}}" class="ic_bd_export">Export CSV</a>

                                                    @if($pResponse->invoiceStatus($pResponse->id,'is_discard',1) > 0)
                                                            <span>No Action</span>
                                                            @elseif($status=='Pending')
                                                            <a class="btn-sm payButton dropdown-item"
                                                                   data-content="{{ $pResponse->amount }}"
                                                                   data-id="{{nxb_encode($pResponse->id)}}"
                                                                   href="{{URL::to('master-template') }}/{{nxb_encode($pResponse->id)}}/billing/singleInvoice">Pay Invoice</a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>

                                    @endforeach

                                @endif
                                </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="box-shadow bg-white p-4 mt-30 mb-30 tab-pane fade" id="Payments" role="tabpanel" aria-labelledby="division-tab">

                            <div class="table-responsive module-holer rr-datatable">
                                
                                 @if(sizeof($inviteInvoices)>0)
                                <div class="col-sm-2" style="float: right; text-align: right">
                                    <a class="btn-sm btn-default viewInvoiceBtn" style="background: green none repeat scroll 0% 0%; margin-top: -16px; margin-bottom: 16px;" href="{{URL::to('master-template') }}/exportOwnerCsv/{{$dataBreadCrumb['id']}}/payment" class="ic_bd_export">Export Payment CSV</a>
                                </div>
                                @endif

                                <table id="crudTable2" class="table table-line-braker mt-10 custom-responsive-md">
                                    
                                    <thead class="hidden-xs">
                                    <tr>
                                        <th style="width:5%">#</th>
                                        <th>Submitted By</th>
                                        <th>Invoice Title</th>
                                        <th>Event</th>
                                        <th>Invoice Created</th>
                                        <th>Status</th>
                                        <th class="action">Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @if(sizeof($inviteInvoices)>0)
                                        @foreach($inviteInvoices as $pResponse)
                                            <?php

                                            $serial = $loop->index + 1;
                                            $roleName = '';
                                            ?>
                                            <tr>
                                                <td>{{ $serial }}</td>

                                                <td><strong class="visible-xs">Submitted By</strong>{{ $pResponse->submittedInvitee($pResponse->user_id) }}</td>

                                                <td><strong class="visible-xs">Invoice Title</strong>{{ $pResponse->invoiceTitle->name }}</td>

                                                <td><strong class="visible-xs">Event</strong>

                                                    @if($pResponse->response_id!=0)
                                                        <a href="{{URL::to('master-template') }}/{{nxb_encode($pResponse->response_id)}}/{{nxb_encode($pResponse->template_id)}}/viewEvent">{{ $pResponse->form->name }}</a>
                                                    @else
                                                        {{ $pResponse->form->name }}
                                                    @endif

                                                </td>

                                                <td><strong class="visible-xs">Invoice Created</strong>{{ $pResponse->created_at }}</td>
                                                <td><strong class="visible-xs">Status</strong>
                                                    @if($pResponse->invoiceStatus($pResponse->id,'is_discard',1) > 0)
                                                        <span>Discarded</span>
                                                    @elseif($pResponse->invoiceStatus($pResponse->id,'is_draft',2) > 0)
                                                        <span>Pending</span>
                                                    @elseif($pResponse->invoiceStatus($pResponse->id,'is_draft',1) > 0)
                                                        <span>Drafted</span>
                                                    @endif
                                                </td>

                                                <td class="pull-left">
                                                    <strong class="visible-xs">Actions</strong>

                                                    <a href="#" class="more" type="button" id="dropdownMenuButton" data-toggle="dropdown"><i data-toggle="tooltip" title="" class="fa fa-list-ul" data-original-title="More Action"></i></a>
                                                        <div class="dropdown-menu dropdown-menu-custom" aria-labelledby="dropdownMenuButton">
                                                        @if($pResponse->invoiceStatus($pResponse->id,'is_discard',1) > 0)
                                                            <span>No Action</span>
                                                        @elseif($pResponse->invoiceStatus($pResponse->id,'is_draft',2) > 0)
                                                            <a class="dropdown-item" href="{{URL::to('participant') }}/{{nxb_encode($pResponse->form_id)}}/{{nxb_encode($pResponse->template_id)}}/{{nxb_encode($pResponse->invoice_form_id)}}/{{nxb_encode($pResponse->asset_id)}}/Invoice/viewInvoice" data-toggle="tooltip" data-placement="top" title="View Invoice">View Invoice</a>
                                                            <a class="viewInvoiceBtn dropdown-item ic_bd_export" href="{{URL::to('master-template') }}/exportinvoiceCsv/{{nxb_encode($pResponse->id)}}">Export CSV</a>
                                                        @else
                                                            <a class="dropdown-item" href="{{URL::to('master-template') }}/{{nxb_encode($pResponse->invoice_form_id)}}/{{nxb_encode($pResponse->form_id)}}/{{nxb_encode($pResponse->template_id)}}/{{nxb_encode($pResponse->asset_id)}}/{{nxb_encode($id)}}/billing/invoice/1/{{nxb_encode($pResponse->response_id)}}">Submit Invoice</a>
                                                        @endif
                                                        </div>


                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
    <!-- Tab containing all the data tables -->
        </div>
    </div>


@endsection

@section('footer-scripts')

    <style>
        .chk-area {
            cursor: pointer;
        }

    </style>

    <script src="{{ asset('/js/custom-tabs-cookies.js') }}"></script>

    <script src="{{ asset('/js/ajax-dynamic-assets-table.js') }}"></script>
    <script src="{{ asset('/js/stripe-custom.js') }}"></script>

    @include('layouts.partials.datatable')
@endsection
