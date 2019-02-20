@extends('layouts.equetica2')

@section('custom-htmlheader')
    <!-- Search populate select multiple-->
    <script src="{{ asset('/js/vender/bootstrap3-typeahead.min.js') }}"></script>
    <!-- END:Search populate select multiple-->
@endsection

@section('main-content')
    <div class="row">
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
            {!! Breadcrumbs::render('payment-detail-paypal',$dataBreadCrumb) !!}

        </div>
    </div>
<form method="post" action="{{URL::to('master-template') }}/payment/add-funds/paypal">
    <div class="row">
        <div class="col-sm-12">
            <div class="row" style="line-height: 30px;">

                <div class="col-sm-3"><strong>Invoice Form</strong></div>
                <div class="col-sm-3">{{$invoice->form->name}}</div>
            </div>
            <div class="row" style="line-height: 30px; background: rgb(205, 205, 205) none repeat scroll 0% 0%;">
                <div class="col-sm-3"><strong>Participant Name</strong></div>
                <div class="col-sm-3">{{$invoice->sendToInvitee->name}}</div>
            </div>

            <div class="row" style="line-height: 30px;">
                <div class="col-sm-3"><strong>Participant Email</strong></div>
                <div class="col-sm-3">{{$invoice->sendToInvitee->email}}</div>
            </div>
            <div class="row" style="line-height: 30px; background: rgb(205, 205, 205) none repeat scroll 0% 0%;">
                <div class="col-sm-3"><strong>Payment Amount</strong></div>
                <div class="col-sm-3">$ {{$invoice->amount}}</div>
            </div>
            <div class="row" style="line-height: 30px;">

                <div class="col-sm-3"><strong>From</strong></div>
                <div class="col-sm-3">Paypal</div>
            </div>
            <div class="row" style="line-height: 30px; background: rgb(205, 205, 205) none repeat scroll 0% 0%;">

                <div class="col-sm-3"><strong>Payment Date</strong></div>
                <div class="col-sm-3">{{ date('M-d-Y') }}</div>
            </div>

        </div>
    </div>
    {{csrf_field()}}
    <input type="hidden" name="amount" value="{{$invoice->amount}}">
    <input type="hidden" name="invoiceId" value="{{nxb_encode($invoice->id)}}">

    <div class="row" style="margin-top: 20px;">
        <div class="col-md-offset-5 col-sm-4">
            <button type="submit" value="Submit" name="Submit"  style="width: 40%; color: #FFF" class="btn btn-success">Submit</button>
        </div>
    </div>
</form>


    <a href="{{URL::to('master-template') }}/payment/paypal/{{nxb_encode($invoice->id)}}">Store Paypal</a>

    <!-- Tab containing all the data tables -->


@endsection

@section('footer-scripts')
    <script src="{{ asset('/js/ajax-dynamic-assets-table.js') }}"></script>
    <script src="{{ asset('/js/paypalForm.js') }}"></script>

    @include('layouts.partials.datatable')



@endsection
