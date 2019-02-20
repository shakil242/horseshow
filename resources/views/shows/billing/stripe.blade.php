<?php
$stripeCharges = number_format($stripeAmount = (config('services.stripe.stripeFee') +config('services.stripe.stripeFeeCent')/100)/100*$amount,2);
$stripeAmount = ($amount * 100) + $stripeCharges*100;
?>
<form action="{{URL::to('participant') }}/submit/checkout" method="POST">
    {{csrf_field()}}
    <script
            src="https://checkout.stripe.com/checkout.js" class="stripe-button payButton"
            data-key="{{config('services.stripe.key')}}"
            data-amount="{{$stripeAmount}}"
            data-name="Horse Billing"
            data-description="Widget"
            data-image="https://stripe.com/img/documentation/checkout/marketplace.png"
            data-locale="auto">
    </script>
    <input type="hidden" id="invoiceId" name="invoiceId" value="{{$invoiceId}}">
    <input type="hidden" name="amount" value="{{$amount * 100}}">
</form>
<span style="text-align: center; font-size: 11px;">Stripe charges( ${{$stripeCharges}})</span>
