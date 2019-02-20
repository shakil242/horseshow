@extends('layouts.equetica2')
@section('custom-htmlheader')
    @include('layouts.partials.form-header')
@endsection
@section('main-content')
    <!-- ================= CONTENT AREA ================== -->
    <div class="container-fluid">
    @php
        $title = "Invoice Detail";
        $added_subtitle =Breadcrumbs::render('participant-invoice-listing',$dataBreadCrumb);
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
                            <li class="nav-item"><a class="nav-link active"  data-toggle="tab" href="#Payments">Submit Invoice</a></li>
                            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#Transffered">Transffered</a></li>
                            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#viewSubmitted">Submitted Invoice</a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="tab-content">
                        <div class="box-shadow bg-white p-4 mt-30 mb-30 tab-pane fade show active" id="Payments" role="tabpanel" aria-labelledby="division-tab">

                            <div class="table-responsive module-holer rr-datatable">
                                <table id="crudTable2" class="table table-line-braker mt-10 custom-responsive-md">
                                <thead class="hidden-xs">
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Invoice Title</th>
                                    <th scope="col">Event</th>
                                    <th scope="col">Invoice Created</th>
                                    <th scope="col">Status</th>
                                    <th class="action">Actions</th>
                                </tr>
                                </thead>
                                <tbody>

                                @if(sizeof($invoiceForms)>0)
                                    @foreach($invoiceForms as $pResponse)
                                        <?php

                                        $res = $pResponse->responseForm($pResponse->id,$pResponse->template_id)->first();

                                        if (in_array($pResponse->id, $arr))
                                            {
                                               continue;
                                            }

                                        $serial = $loop->index + 1;
                                        $roleName = '';
                                        ?>

                                        <tr>
                                            <td>{{ $serial }}</td>
                                            <td><span class="table-title">Invoice Title</span>{{ $pResponse->invoiceTitle->name }}</td>

                                            <td><span class="table-title"> Form Name</span>
                                                @if($res)
                                                <a href="{{URL::to('master-template') }}/{{nxb_encode($res->response_id)}}/{{nxb_encode($pResponse->template_id)}}/viewEvent">{{ $pResponse->name }}</a>
                                                @else
                                                   {{ $pResponse->name }}
                                                @endif
                                            </td>

                                            <td><span class="table-title">Invoice Created</span>{{ $pResponse->created_at->format('m-d-Y') }}</td>
                                            <td><span class="table-title">Status</span>
                                                @if($pResponse->invoiceStatus($pResponse->id,$assetId,'is_discard',1) > 0)
                                                    <span>Discarded</span>
                                                @elseif($pResponse->invoiceStatus($pResponse->id,$assetId,'is_draft',2) > 0)
                                                    <span>Pending</span>
                                                @elseif($pResponse->invoiceStatus($pResponse->id,$assetId,'is_draft',1) > 0)
                                                    <span>Drafted</span>
                                                @endif
                                            </td>

                                            <td class="action">
                                                <span class="table-title">Actions</span>
                                                @if($pResponse->invoiceStatus($pResponse->id,$assetId,'is_discard',1) > 0)
                                                <span>No Action</span>
                                                @elseif($pResponse->invoiceStatus($pResponse->id,$assetId,'is_draft',2) > 0)
                                                <a href="{{URL::to('participant') }}/{{nxb_encode($pResponse->id)}}/{{nxb_encode($pResponse->template_id)}}/{{nxb_encode($pResponse->invoice)}}/{{nxb_encode($assetId)}}/Invoice/viewInvoice"><i class="fa fa-eye" title="View Invoice" data-toggle="tooltip"></i> </a>
                                                 {{--<a href="{{URL::to('participant') }}/{{nxb_encode($pResponse->invoiceUser($pResponse->id,$assetId)->first()->id)}}/checkout">Pay invoice</a>--}}
                                                @else
                                                <a href="{{URL::to('master-template') }}/{{nxb_encode($pResponse->invoice)}}/{{nxb_encode($pResponse->id)}}/{{nxb_encode($pResponse->template_id)}}/{{nxb_encode($assetId)}}/{{nxb_encode($id)}}/billing/invoice/0/{{nxb_encode($pResponse->response_id)}}">Submit Invoice</a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                        <div class="box-shadow bg-white p-4 mt-30 mb-30 tab-pane fade" id="Transffered" role="tabpanel" aria-labelledby="division-tab">

                          @if(sizeof($inviteInvoices)>0)
                              <div class="row mt-10">
                                  <div class="col-sm-10">
                                  &nbsp
                                  </div>
                                    <div class="col-sm-2">
                                        <a  class="btn btn-primary viewInvoiceBtn" style="background: green none repeat scroll 0% 0%; margin-top: -16px; margin-bottom: 16px;"  href="{{URL::to('participant') }}/exportParticipantCsv/{{nxb_encode($assetId)}}/transfered" class="ic_bd_export">Export Transfered CSV</a>
                                    </div>
                              </div>
                                @endif
                                <div class="col-sm-2">
                                    <div class="commulativeInvoice" style="display: none; margin-top: -16px;">
                                        <input type="button" id="commulativeInvoice" class="btn btn-success" value="Pay Commulative Invoic">
                                    </div>
                                </div>


                                    <div class="table-responsive module-holer rr-datatable">
                                        <table id="crudTable2" class="table table-line-braker mt-10 custom-responsive-md">

                                    <thead class="hidden-xs">
                                    <tr>
                                        <th scope="col">
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                    <input class="form-check-input allCheck"  data-target="commulativeInvoice"  id="legendCheck1" type="checkbox">
                                                    <span>#</span>
                                                </label>
                                            </div>
                                        </th>
                                        <th scope="col">Asset</th>
                                        <th scope="col">Submitted</th>
                                        <th scope="col">Title</th>
                                        <th scope="col">Event</th>
                                        <th scope="col">Amount</th>
                                        <th scope="col">Created</th>
                                        <th scope="col">Status</th>
                                        <th class="action" >Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if(sizeof($inviteInvoices)>0)
                                        @foreach($inviteInvoices as $pResponse)
                                            <?php
                                            $serial = $loop->index + 1;
                                            $roleName = '';

                                            if($pResponse->billing($pResponse->id)->count() > 0)
                                                $status = 'Paid';
                                            else
                                                $status = 'Pending';
                                            ?>
                                            <tr>
                                                <td> <div class="form-check">
                                                        <label class="form-check-label">
                                                            <input class="form-check-input singleCheck" value="{{$pResponse->id}}"  name="commulativeInvoice[]" data-target="commulativeInvoice"  type="checkbox">
                                                            <span>{{ $serial }}</span>
                                                        </label>
                                                    </div>
                                                    </td>

                                                <td><span class="table-title">Asset</span>{{ GetAssetNamefromId($pResponse->asset_id) }}</td>
                                                <td><span class="table-title">Submitted</span>{{ $pResponse->submittedByOWner->name }}</td>
                                                <td><span class="table-title">Title</span>{{ $pResponse->invoiceTitle->name }}</td>
                                                <td><span class="table-title">Event</span>

                                                    @if($pResponse->response_id > 0)
                                                        <a href="{{URL::to('master-template') }}/{{nxb_encode($pResponse->response_id)}}/{{nxb_encode($pResponse->template_id)}}/viewEvent"> {{ $pResponse->form->name }}</a>
                                                    @else
                                                        {{ $pResponse->form->name }}
                                                    @endif
                                                </td>
                                                <td><span class="table-title">Amount</span> $ {{ $pResponse->amount }}</td>

                                                <td><span class="table-title">Created</span>{{ $pResponse->created_at->format('m-d-Y') }}</td>

                                                <td><span class="table-title">Status</span><span>{{$status}}</span></td>

                                                <td class="action">
                                                    <span class="table-title">Actions</span>
                                                    <a class="viewInvoiceBtn" href="{{URL::to('master-template') }}/exportinvoiceCsv/{{nxb_encode($pResponse->id)}}"><i class="fa fa-file-excel-o" data-toggle="tooltip" title="Export CSV"></i></a>
                                                    <a class="viewInvoiceBtn" href="{{URL::to('master-template') }}/{{nxb_encode($pResponse->id)}}/{{nxb_encode($pResponse->template_id)}}/Invoice/viewInvoice" ><i class="fa fa-eye" data-toggle="tooltip" title="View Invoice"></i></a>

                                                    @if($pResponse->invoiceStatus($pResponse->id,'is_discard',1) > 0)
                                                        <span>No Action</span>
                                                    @elseif($status=='Pending')
                                                        <a class="payButton"
                                                           data-content="{{ $pResponse->amount }}"
                                                           data-id="{{nxb_encode($pResponse->id)}}"
                                                           href="{{URL::to('master-template') }}/{{nxb_encode($pResponse->id)}}/billing/singleInvoice"><i class="fa fa-money" Pay Invoice</a>
                                                    @endif

                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                    </tbody>

                                </table>
                            </div>
                        </div>
                        <div class="box-shadow bg-white p-4 mt-30 mb-30 tab-pane" id="viewSubmitted" role="tabpanel" aria-labelledby="division-tab">

                                {{--@if(sizeof($inviteInvoices)>0)--}}
                                    {{--<div class="col-sm-2" style="float: right; text-align: right">--}}
                                        {{--<a  class="btn-sm btn-default viewInvoiceBtn" style="background: green none repeat scroll 0% 0%; margin-top: -16px; margin-bottom: 16px;"  href="{{URL::to('participant') }}/exportParticipantCsv/{{nxb_encode($assetId)}}/transfered" class="ic_bd_export">Export Transfered CSV</a>--}}
                                    {{--</div>--}}
                                {{--@endif--}}
                                <div class="col-sm-2" style="margin-left: 0px; padding-left: 0px;">
                                    <div class="commulativeInvoice" style="display: none; margin-top: -16px;">
                                        <input type="button" id="commulativeInvoice" class="btn btn-success" value="Pay Commulative Invoic">
                                    </div>
                                </div>
                            <div class="table-responsive module-holer rr-datatable">
                                <table id="crudTable2" class="table table-line-braker mt-10 custom-responsive-md">

                                    <thead class="hidden-xs">
                                    <tr>
                                        <th scope="col"><div class="form-check">
                                                <label class="form-check-label">
                                                    <input class="form-check-input allCheck"  data-target="commulativeInvoice"  type="checkbox">
                                                    <span>#</span>
                                                </label>
                                            </div> </th>
                                        <th scope="col">Asset</th>
                                        <th scope="col">Submitted</th>
                                        <th scope="col">Title</th>
                                        <th scope="col">Event</th>
                                        <th scope="col">Amount</th>
                                        <th scope="col">Created</th>
                                        <th scope="col">Status</th>
                                        <th class="action">Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @if(sizeof($viewInvoices)>0)
                                        @foreach($viewInvoices as $pResponse)
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
                                                    <div class="form-check">
                                                        <label class="form-check-label">
                                                            <input  value="{{$pResponse->id}}" class="form-check-input singleCheck" name="commulativeInvoice[]"  data-target="commulativeInvoice"  type="checkbox">
                                                            <span> {{ $serial }}</span>
                                                        </label>
                                                    </div>

                                                </td>

                                                <td><span class="table-title">Asset</span>{{ GetAssetNamefromId($pResponse->asset_id) }}</td>
                                                <td><span class="table-title">Submitted</span>{{ $pResponse->submittedByOWner->name }}</td>
                                                <td><span class="table-title">Title</span>{{ $pResponse->invoiceTitle->name }}</td>
                                                <td><span class="table-title">Event</span>

                                                    @if($pResponse->response_id > 0)
                                                        <a href="{{URL::to('master-template') }}/{{nxb_encode($pResponse->response_id)}}/{{nxb_encode($pResponse->template_id)}}/viewEvent"> {{ $pResponse->form->name }}</a>
                                                    @else
                                                        {{ $pResponse->form->name }}
                                                    @endif
                                                </td>
                                                <td><span class="table-title">Amount</span> $ {{ $pResponse->amount }}</td>

                                                <td><span class="table-title">Created</span>{{ $pResponse->created_at->format('m-d-Y') }}</td>

                                                <td><span class="table-title">Status</span><span>{{$status}}</span></td>

                                                <td class="action">
                                                    <span class="table-title">Actions</span>
                                                    <a class="viewInvoiceBtn" href="{{URL::to('master-template') }}/exportinvoiceCsv/{{nxb_encode($pResponse->id)}}" class="ic_bd_export"><i class="fa fa-file-excel-o" title="Export CSV" data-toggle="tooltip"></i> </a>
                                                    <a class="viewInvoiceBtn" href="{{URL::to('master-template') }}/{{nxb_encode($pResponse->id)}}/{{nxb_encode($pResponse->template_id)}}/Invoice/viewInvoice"><i class="fa fa-eye" title="View Invoice" data-toggle="tooltip"></i></a>
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
    {{--<script src="{{ asset('/js/custom-tabs-cookies.js') }}"></script>--}}
    <script src="{{ asset('/js/ajax-dynamic-assets-table.js') }}"></script>
    <script src="{{ asset('/js/stripe-custom.js') }}"></script>

    @include('layouts.partials.datatable')
@endsection
