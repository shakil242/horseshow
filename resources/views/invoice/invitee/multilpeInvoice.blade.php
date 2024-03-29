@extends('layouts.equetica2')

@section('custom-htmlheader')
    <!-- Search populate select multiple-->
    <script src="{{ asset('/js/vender/bootstrap3-typeahead.min.js') }}"></script>
    <!-- END:Search populate select multiple-->
@endsection

@section('main-content')
    <div class="row" style="margin-bottom: 15px;">
        <div class="col-sm-12">
            <h1 style="text-align: center">  Invoice Detail </h1>
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
            {{--{!! Breadcrumbs::render('master-confirmation-billing',$dataBreadCrumb) !!}--}}
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">

            <table class="table primary-table">
                <thead class="hidden-xs">
                <tr>
                    <th>Invoice Form</th>
                    <th>Send To</th>
                    <th>Payment</th>
                    <th>Account#</th>
                    <th>Payment Date</th>

                </tr>
                </thead>
                <tbody>

                <?php
                $amount = 0;
                ?>

                @foreach($invoice as $row)

                    <?php
                    $amount = $amount + $row->amount;
                    $invoiceIdArr[] = $row->id;
                    $invoiceFormIdArr[] = $row->form_id;
                    $invoiceUserIdArr[] = $row->user_id;
                    $invoiceTitleArr[] = $row->invoiceTitle->name;
                    $template_id = $row->template_id;
                     ?>

                    <tr>
                        <td>{{$row->form->name}}</td>
                        <td>@if($row->invitee_id!=0){{$row->inviteeUser->name}}@else {{"N/A"}}@endif</td>
                        <td> $ {{$row->amount}}</td>
                        <td>{{$OwnerInfo->account_number}}</td>
                        <td>{{ date('M-d-Y') }}</td>
                    </tr>

                @endforeach


                <tr><td colspan="2"><strong>Total Amount</strong></td><td colspan="3">$ {{$amount}}</td></tr>
                </tbody>
            </table>

            <div class="row">
                @if($amount > 0 && $paypalAccountDetail)
                    <div class="col-sm-3">
                        <a class="btn-default btn-sm payButton"
                           data-content="{{ $amount }}"
                           data-id="{{"test"}}"
                           href="{{URL::to('master-template') }}/multipleInvoice/paypal/{{nxb_encode(implode(',',$invoiceIdArr))}}">Pay By Paypal</a>
                    </div>
                @endif
                @if($amount > 0)
                    <div class="col-sm-3">

                        <form action="{{URL::to('participant') }}/submit/checkout" method="POST">
                            {{csrf_field()}}
                            <script
                                    src="https://checkout.stripe.com/checkout.js" class="stripe-button payButton"
                                    data-key="{{config('services.stripe.key')}}"
                                    data-amount="{{$amount * 100}}"
                                    data-name="{{implode(',',$invoiceTitleArr)}}"
                                    data-description="Widget"
                                    data-image="https://stripe.com/img/documentation/checkout/marketplace.png"
                                    data-locale="auto">
                            </script>
                            <input type="hidden" name="fromId" value="{{nxb_encode(implode(',',$invoiceFormIdArr))}}">
                            <input type="hidden" name="invoiceId" value="{{nxb_encode(implode(',',$invoiceIdArr))}}">
                            <input type="hidden" name="participant_id" value="{{nxb_encode(implode(',',$invoiceUserIdArr))}}">
                            <input type="hidden" name="template_id" value="{{$template_id}}">
                            <input type="hidden" name="amount" value="{{$amount * 100}}">
                        </form>
                    </div>

                @endif

                <div class="col-sm-3">
                    <a href="{{URL::to('master-template') }}/{{nxb_encode($template_id)}}/invoice/listing" style="text-align: center; border-radius: 10px; margin-top: 9px; line-height: 33px;"
                       class="btn-block btn-primary">Cancel</a>

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
