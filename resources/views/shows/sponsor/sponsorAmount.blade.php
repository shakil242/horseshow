    <div class="row">
        <div class="col-sm-7">&nbsp</div>
        <div class="col-sm-5 pull-right border-bottom">
            <div class="row">
            <div class="col-sm-6"><b>Miscellaneous Charges: </b></div>
            <div class="col-sm-6 addAdditionalPrice">
                @php
                    $totalMiscCharges = (config('services.paypal.paypalFee')/100*$arr['royaltyFinal'])+config('services.paypal.paypalFeeCent')+$arr['royaltyFinal'];
                    $paypalCharges = $stripeAmount = (config('services.paypal.paypalFee') +config('services.paypal.paypalFeeCent')/100)/100*$arr['grandTotal'];


                @endphp
                ($) {{$arr['royaltyFinal']}}
            </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-7">&nbsp</div>

        <div class="col-sm-5 pull-right border-bottom">
            <div class="row">
            <div class="col-sm-6"><b>(Federal + State Tax): </b></div>
            <div class="col-sm-6 taxPrice">($)
                ( {{twodecimalformate($arr['taxFederal'])}} + {{twodecimalformate($arr['taxState'])}} ) = {{twodecimalformate($total = $arr['taxState']+$arr['taxFederal'])}}
</div>
        </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-7">&nbsp</div>
        <div class="col-sm-5 pull-right border-bottom" >
            <div class="row">
            <div class="col-sm-6"><b>Amount: </b></div>
            <div class="col-sm-6 addAssetPrice">($) {{$arr['grandTotal']}}</div>
        </div>
    </div>
    </div>
    <div class="row mt-40 mb-20">

        <div class="col-sm-3">&nbsp</div>
        @if($paypalAccountDetail > 0)

        {{--@if($totalSum > 0 && $paypalAccountDetail > 0)--}}
        {!! Form::open(['url'=>'shows/sponsor/payPalCheckout','method'=>'post','class'=>'form-horizontal']) !!}

        <div class="col-sm-2">

            <?php

            $paypalAmount = $arr['grandTotal'] + $paypalCharges;

            ?>
            <button type="submit" class="btn-primary btn stripeBtn" name="type" value="paypal">Pay By Paypal</button>
            <label class="paypalCharges" style="text-align: center; width: 11em; font-size: 11px; padding-top:5px;">Paypal Charges( ${{$paypalCharges}})</label>
                @if(isset($arr['id']))
                <input type="hidden" id="category_id" name="category_id" value="{{implode(',',$arr['id'])}}">
              @endif
                <input type="hidden" id="show_id" name="show_id" value="{{$arr['show_id']}}">
                <input type="hidden" id="sponsor_form_id" name="sponsor_form_id" value="{{$arr['sponsor_form_id']}}">
                <input type="hidden" name="royaltyFinal" value="{{$totalMiscCharges}}">
               <input type="hidden" name="amount" value="{{$paypalAmount}}">

        </div>
        {!! Form::close() !!}
        @endif
        @if($stripeDetails > 0)
        <div class="col-sm-2" id="stripeee" >
            <?php
            $stripeCharges = number_format($stripeAmount = (config('services.stripe.stripeFee') +config('services.stripe.stripeFeeCent')/100)/100*$arr['grandTotal'],2);
            $stripeAmount = ($arr['grandTotal'] * 100) + $stripeCharges*100;
            ?>

            <form action="{{URL::to('shows') }}/sponsor/stripeCheckout" method="POST">
                {{csrf_field()}}
                <script
                        src="https://checkout.stripe.com/checkout.js" class="stripe-button  payButton"
                        data-key="{{config('services.stripe.key')}}"
                        data-amount="{{$stripeAmount}}"
                        data-name="Sponsor Billing"
                        data-description="Widget"
                        data-image="https://stripe.com/img/documentation/checkout/marketplace.png"
                        data-locale="auto">
                </script>
                @if(isset($arr['id']))
                <input type="hidden" id="category_id" name="category_id" value="{{implode(',',$arr['id'])}}">
                @endif
                <input type="hidden" id="show_id" name="show_id" value="{{$arr['show_id']}}">
                <input type="hidden" id="sponsor_form_id" name="sponsor_form_id" value="{{$arr['sponsor_form_id']}}">

                <input type="hidden" name="amount" value="{{$stripeAmount}}">
            </form>
            <label style="text-align: center; font-size: 11px; width: 11em; padding-top:5px;">Stripe charges( ${{$stripeCharges}})</label>

        </div>
@endif
        <div class="col-sm-2" >
            <button class="btn-primary btn strp stripeBtn">Cancel</button>
        </div>

    </div>
