

@extends('layouts.equetica2')

@section('custom-htmlheader')
    <!-- Search populate select multiple-->
    <script src="{{ asset('/js/vender/bootstrap3-typeahead.min.js') }}"></script>
    <!-- END:Search populate select multiple-->
@endsection

@section('main-content')
    <div class="row">
        <div class="col-sm-8">
            <h1>  {{$invoice->form->name}} Invoice </h1>
        </div>

    </div>

    <div class="row" style="margin-top: 50px;">
        <div class="col-sm-12">

            <form action="{{URL::to('participant') }}/submit/checkout" method="POST">

                {{csrf_field()}}
                <script
                        src="https://checkout.stripe.com/checkout.js" class="stripe-button"
                        data-key="{{config('services.stripe.key')}}"
                        data-amount="2500"
                        data-name="{{$invoice->invoiceTitle->name}}"
                        data-description="Widget"
                        data-image="https://stripe.com/img/documentation/checkout/marketplace.png"
                        data-locale="auto">
                </script>
            </form>

        </div>
    </div>
    <!-- Tab containing all the data tables -->


@endsection

@section('footer-scripts')
    <script src="{{ asset('/js/ajax-dynamic-assets-table.js') }}"></script>
    @include('layouts.partials.datatable')
@endsection



