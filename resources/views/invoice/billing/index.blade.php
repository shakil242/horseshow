@extends('layouts.equetica2')
@section('custom-htmlheader')
    @include('layouts.partials.form-header')
@endsection
@section('main-content')
    <!-- ================= CONTENT AREA ================== -->
    @php
        $title = "Billing Details";
        $added_subtitle =Breadcrumbs::render('participant-payment-methods');
    @endphp
    @include('layouts.partials.pagemenu',['added_title'=>$title,'added_subtitle'=>$added_subtitle])
    <!-- Content Panel -->
        <div class="white-board">

            <div class="row">
                <div class="info">
                    @if(Session::has('message'))
                        <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                    @endif
                </div>
            </div>

            <div class="row">
                <div class="col">


                    <ul class="nav nav-tabs" id="myTab" role="tablist">


                        @if($user_type==0)
                        <li class="nav-item">
                            <a class="nav-link  active show" id="division-tab" data-toggle="tab" href="#Transffered" role="tab" aria-controls="Transffered" aria-selected="false">Payment Sent
                            </a>
                        </li>
                        @else
                        <li class="nav-item">
                            <a class="nav-link active" id="showclasses-tab" data-toggle="tab" href="#Payments" role="tab" aria-controls="Payments" aria-selected="true">Payment Received
                            </a>
                        </li>
                            @endif
                    </ul>
                </div>

            </div>

        <div class="row">
        <div class="col-sm-12">
            <div class="tab-content" id="myTabContent">
                <div class="box-shadow bg-white p-4 mt-30 mb-30 tab-pane fade {{($user_type==0)?'show active':''}}" id="Transffered" role="tabpanel" aria-labelledby="division-tab">
        <div class="table-responsive module-holer rr-datatable">
        <table id="crudTable2" class="table table-line-braker mt-10 custom-responsive-md">
        <thead class="hidden-xs">
        <tr>
            <th scope="col">#</th>
            <th scope="col">Receive By</th>
            <th scope="col">Receiver Email</th>
            <th scope="col">Amount</th>
            <th scope="col">Transfered date</th>
            <th class="action">Transfered Type</th>
        </tr>
        </thead>
                    <tbody>
                        @if(sizeof($billingTransferDetails)>0)
                            @foreach($billingTransferDetails as $pResponse)
                                <?php
                              $serial = $loop->index + 1;
                                $roleName = '';
                                ?>
                                <tr>
                                    <td>{{ $serial }}</td>
                                    <td><span class="table-title">Receive By</span>{{ $pResponse->receiver->name }}</td>
                                    <td><span class="table-title">Receiver Email</span>{{ $pResponse->receiver->email }}</td>
                                    <td>
                                        <span class="table-title">Amount</span>{{ getpriceFormate($pResponse->amount_transfer)}}
                                    </td>
                                    <td><span class="table-title">Invoice Created</span>{{ $pResponse->created_at->format('m-d-Y g:i A') }}
                                    </td>
                                    <td><span class="table-title">Transfered Type</span>@if($pResponse->type == "pay in office") Paid In Office @else {{ $pResponse->type }} @endif</td>

                                </tr>
                            @endforeach

                        @endif

                    </tbody>
                </table>
            </div>
        </div>
        <div class="box-shadow bg-white p-4 mt-30 mb-30 tab-pane fade  {{($user_type==1)?'show active':''}}" id="Payments" role="tabpanel" aria-labelledby="showclasses-tab">
                <div class="table-responsive module-holer rr-datatable">
                    <table id="crudTable2" class="table table-line-braker mt-10 custom-responsive-md">
                    <thead class="hidden-xs">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Transfered By</th>
                        <th scope="col">Transfered Email</th>
                        <th scope="col">Amount</th>
                        <th scope="col">Transfered date</th>
                        <th class="action">Transfered Type</th>
                    </tr>
                    </thead>
                    <tbody>
                        @if(sizeof($billingReceiveDetails)>0)
                            @foreach($billingReceiveDetails as $pResponse)
                                <?php
                                $serial = $loop->index + 1;
                                $roleName = '';
                                ?>
                                <tr>
                                    <td>{{ $serial }}</td>
                                    <td><span class="table-title">Transfered By</span>{{ $pResponse->user->name }}
                                    </td>
                                    <td><span class="table-title">Transfered Email</span>{{ $pResponse->user->email }}</td>
                                    <td>
                                        <span class="table-title">Amount</span> {{ getpriceFormate($pResponse->amount_transfer)}}
                                    </td>
                                    <td><span class="table-title">Invoice Created</span>{{ $pResponse->created_at->format('m-d-Y g:i A') }}
                                    </td>
                                    <td><span class="table-title">Transfered Type</span>@if($pResponse->type == "pay in office") Paid In Office @else {{ $pResponse->type }} @endif</td>

                                </tr>
                            @endforeach
                        @else

                    <tr><td colspan="6">There is no payment exist</td></tr>


                        @endif

                    </tbody>
                </table>
            </div>
        </div>
    </div>

        </div>
        </div>
        </div>

    <!-- Tab containing all the data tables -->
@endsection

@section('footer-scripts')
    <script src="{{ asset('/js/ajax-dynamic-assets-table.js') }}"></script>

    @include('layouts.partials.datatable')
@endsection
