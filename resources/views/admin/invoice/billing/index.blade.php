@extends('admin.layouts.app')

@section('custom-htmlheader')
    <!-- Search populate select multiple-->
    <script src="{{ asset('/js/vender/bootstrap3-typeahead.min.js') }}"></script>
    <!-- END:Search populate select multiple-->
@endsection

@section('main-content')
    <div class="row">
        <div class="col-sm-8">
            <h1>  Billing Detail </h1>
        </div>
    </div>

    <div class="row">
        <div class="info">
            @if(Session::has('message'))
                <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
            @endif
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            {{--{!! Breadcrumbs::render('template-overall-allHistory',["template_id"=>$feedBack->template_id,"invitee_id"=>$feedBack->invitee_id]) !!}--}}
        </div>
    </div>

    <ul class="nav nav-tabs" style="margin-top: 30px;">
        <li class="active"><a data-toggle="tab" href="#yourApps">Roylaty</a></li>
        <li><a data-toggle="tab" href="#invitedAsset">Stripe Trasactions</a></li>
        <li><a data-toggle="tab" href="#paypalTransactions">Paypal Trasactions</a></li>
        <li><a data-toggle="tab" href="#payInOffice">Pay In Office</a></li>

    </ul>

    <div class="tab-content">
        <div id="yourApps" class="tab-pane fade in active">

<div class="row">
            <div class="col-sm-4 action-holder" style="float: right">
                <form action="#">
                    <div class="search-form">
                        <input class="form-control input-sm" placeholder="Search By Name" id="searchMultiField" type="search">
                        <button type="submit"><i class="fa fa-search" aria-hidden="true"></i></button>
                    </div>
                </form>
            </div>
</div>

            <div class="module-holer rr-datatable">



                <table id="crudTable4" class="table primary-table">
                    <thead class="hidden-xs">
                    <tr>
                        <th style="width: 5%">#</th>
                        <th>Transfered By</th>
                        <th>Transfered To</th>
                        <th>Event name</th>
                        <th style="width: 8%">Royalty</th>
                        <th>Transfered date</th>
                        <th>Transfered Type</th>

                    </tr>
                    </thead>
                    <tbody>

                    @if(sizeof($billingDetails)>0)
                        <?php  $totalBalance = 0; ?>
                        @foreach($billingDetails as $pResponse)
                            <?php
                            $serial = $loop->index + 1;
                            $roleName = '';

                            $totalBalance = $totalBalance + number_format( $pResponse->application_fee/100, 2, '.', ' ');
                            ?>
                            <tr>
                                <td>{{ $serial }}</td>

                                <td><strong class="visible-xs">Transfered By</strong>{{ $pResponse->user->name }}</td>
                                <td><strong class="visible-xs">Transfered To</strong>{{ $pResponse->user->email }}</td>
                                <td><strong class="visible-xs">Event name</strong>{{ $pResponse->getEventTitle->form->name }}</td>
                                <td><strong class="visible-xs">Royalty</strong>${{ number_format( $pResponse->application_fee/100, 2, '.', ' ')}}</td>
                                <td><strong class="visible-xs">Transfered date</strong>{{ $pResponse->created_at }}</td>
                                <td><strong class="visible-xs">Transfered Type</strong>{{ $pResponse->type }}</td>

                            </tr>
                        @endforeach
                        {{--<tr><td colspan="4"><strong>Total Balance</strong></td><td colspan="2"><strong>$  {{$totalBalance}}</strong></td></tr>--}}

                    @endif
                    </tbody>
                </table>
            </div>

        </div>
        <div id="invitedAsset" class="tab-pane">

            <div class="row">
                <div class="col-sm-4 action-holder" style="float: right">
                    <form action="#">
                        <div class="search-form">
                            <input class="form-control input-sm" placeholder="Search By Name" id="myInputTextField" type="search">
                            <button type="submit"><i class="fa fa-search" aria-hidden="true"></i></button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="module-holer rr-datatable">


                <table id="crudTable3" class="table primary-table">
                    <thead class="hidden-xs">
                    <tr>
                        <th>#</th>
                        <th>Sent From</th>
                        <th>Sent To</th>
                        <th>Amount</th>
                        <th>Royalty</th>
                        <th>Stripe Fee</th>
                        <th>Sent Amount</th>
                        <th>Avalaible On</th>
                        <th>Type</th>

                    </tr>
                    </thead>
                    <tbody>

                    <?php
//                    echo '<pre>';
//                    print_r($transaction);
//                    echo '</pre>';
//                    exit;
                    ?>

                    @if(sizeof($transaction)>0)
                        @foreach($transaction->data as $pResponse)
                            <?php

                            $serial = $loop->index + 1;
                            $roleName = '';
                            ?>
                            <tr>
                                <td>{{ $serial }} </td>
                                <td><strong class="visible-xs">Sent From</strong>
                                    @if(isset($pResponse['metadata']['sender_id']) && $pResponse['metadata']['sender_id']!='')
                                    {{ getUserNamefromid($pResponse['metadata']['sender_id']) }}
                                    @endif

                                </td>
                                <td><strong class="visible-xs">Sent To</strong>
                                    @if(isset($pResponse['metadata']['receiver_id']) && $pResponse['metadata']['receiver_id']!='')
                                    {{ getUserNamefromid($pResponse['metadata']['receiver_id']) }}
                                @endif
                                </td>

                                <td><strong class="visible-xs">Amount</strong>$ {{ number_format( $pResponse['amount']/100, 2, '.', ' ')}}</td>
                                <td><strong class="visible-xs">Royalty</strong>$ {{ number_format( $pResponse['metadata']['royality']/100, 2, '.', ' ')}}</td>
                                <td><strong class="visible-xs">Stripe Fee</strong>$ {{ number_format( $pResponse['metadata']['stripeCharges']/100, 2, '.', ' ')}}</td>
                                <td><strong class="visible-xs">Sent Amount</strong>$ {{ number_format( $pResponse['metadata']['receiverAmount']/100, 2, '.', ' ')}}</td>
                                <td><strong class="visible-xs">Avalaible On</strong>{{ date('Y-m-d',$pResponse['created'])}}</td>
                                <td><strong class="visible-xs">Type</strong>{{$pResponse['metadata']['type']}}</td>

                   </tr>
                        @endforeach

                   @endif

                    </tbody>
                </table>
            </div>
        </div>
        <div id="paypalTransactions" class="tab-pane">

            <div class="row">
                <div class="col-sm-4 action-holder" style="float: right">
                    <form action="#">
                        <div class="search-form">
                            <input class="form-control input-sm" placeholder="Search By Name" id="myInputTextField" type="search">
                            <button type="submit"><i class="fa fa-search" aria-hidden="true"></i></button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="module-holer rr-datatable">


                <table id="crudTable4" class="table primary-table">
                    <thead class="hidden-xs">
                    <tr>
                        <th style="width: 3%">#</th>
                        <th style="width: 20%">Sent From</th>
                        <th style="width: 20%">Sent To</th>
                        <th style="width: 10%">Amount</th>
                        <th style="width: 10%">Royalty</th>
                        <th style="width: 20%">Sent Amount</th>

                        <th>Date</th>

                    </tr>
                    </thead>
                    <tbody>

                    @if(sizeof($payPalRes)>0)
                        @foreach($payPalRes as $pResponse)
                            <?php


                            $serial = $loop->index + 1;
                            $roleName = '';
                            ?>
                            <tr>
                                <td>{{ $serial }} </td>
                                <td><strong class="visible-xs">Sent From</strong>
                                    @if(isset($pResponse['participantEmail']) && $pResponse['participantEmail']!='')
                                        {{ $pResponse['participantEmail'] }}
                                    @endif

                                </td>
                                <td><strong class="visible-xs">Sent To</strong>
                                    @if(isset($pResponse['participantEmail']) && $pResponse['participantEmail']!='')
                                        {{ $pResponse['participantEmail'] }}
                                    @endif
                                </td>

                                <td><strong class="visible-xs">Amount</strong>$ {{ number_format( $pResponse['participantAmount'], 2, '.', ' ')}}</td>
                                <td><strong class="visible-xs">Royalty</strong>$ {{ number_format( $pResponse['royalty'], 2, '.', ' ')}}</td>
                                <td><strong class="visible-xs">Sent Amount</strong>$ {{ number_format( $pResponse['amount'], 2, '.', ' ')}}</td>
                                <td><strong class="visible-xs">Date</strong> {{date('Y-m-d H:i',strtotime($pResponse['transferedDate']))}}</td>

                            </tr>
                        @endforeach

                    @endif

                    </tbody>
                </table>
            </div>
        </div>
        <div id="payInOffice" class="tab-pane">

            <div class="row">
                <div class="col-sm-4 action-holder" style="float: right">
                    <form action="#">
                        <div class="search-form">
                            <input class="form-control input-sm" placeholder="Search By Name" id="myInputTextField" type="search">
                            <button type="submit"><i class="fa fa-search" aria-hidden="true"></i></button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="module-holer rr-datatable">


                <table id="crudTable4" class="table primary-table">
                    <thead class="hidden-xs">
                    <tr>
                        <th style="width: 3%">#</th>
                        <th style="width: 20%">Sent From</th>
                        <th style="width: 20%">Sent To</th>
                        <th style="width: 10%">Amount</th>
                        <th style="width: 20%">Royalty</th>

                        <th>Date</th>

                    </tr>
                    </thead>
                    <tbody>

                    @if(sizeof($payinoffice)>0)
                        @foreach($payinoffice as $pResponse)
                            <?php


                            $serial = $loop->index + 1;
                            $roleName = '';
                            ?>
                            <tr>
                                <td>{{ $serial }} </td>
                                <td><strong class="visible-xs">Sent From</strong>
                                    {{getUserNamefromid($pResponse->sender_id)}}
                                </td>
                                <td><strong class="visible-xs">Sent To</strong>
                                    {{getUserNamefromid($pResponse->horseinvoice->payment_receiver_id)}}
                                </td>
                                <td><strong class="visible-xs">Amount</strong>$ {{ number_format( $pResponse->amount_sent, 2, '.', ' ')}}</td>
                                <td><strong class="visible-xs">Royalty</strong>$ {{ number_format( $pResponse->horseinvoice->royalty, 2, '.', ' ')}}</td>
                                <td><strong class="visible-xs">Date</strong> {{date('Y-m-d H:i',strtotime($pResponse->updated_at))}}</td>

                            </tr>
                        @endforeach

                    @endif

                    </tbody>
                </table>
            </div>
        </div>




    </div>


    <!-- Tab containing all the data tables -->


@endsection

@section('footer-scripts')
    <script src="{{ asset('/js/ajax-dynamic-assets-table.js') }}"></script>
    @include('layouts.partials.datatable')
@endsection
