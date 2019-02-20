@extends('layouts.equetica2')
@section('main-content')


    <!-- ================= CONTENT AREA ================== -->
        <div class="container-fluid">

        @php
            $title = "Invoice Detail";
            $added_subtitle = "";
        @endphp
        @include('layouts.partials.pagemenu',['added_title'=>$title,'added_subtitle'=>$added_subtitle,'remove_search'=>1])

        <!-- Content Panel -->
            <div class="white-board">

                <div class="row">
                    <div class="info text-center col-md-12 mt-10">
                        @if(Session::has('message'))
                            <div class="alert {{ Session::get('alert-class', 'alert-success') }}" role="alert">
                                {{ Session::get('message') }}
                            </div>
                        @endif
                    </div>
                </div>

    <div class="row">
        <div class="col-sm-12">
            {{--{!! Breadcrumbs::render('master-singleInvoice-billing',$dataBreadCrumb) !!}--}}
        </div>
    </div>
<div class="" style="position: relative">
    {!! Form::open(['url'=>'Billing/checkout','method'=>'post','class'=>'form-horizontal']) !!}
    <div class="row">
        <div class="col-sm-12">

            <table class="table primary-table">
                <thead class="hidden-xs">
                <tr>
                    <th>
                        <label>
                            <input type="checkbox" checked  id="checkall" class="select-past-participant">
                            <span>&nbsp</span>
                        </label>
                    </th>
                    <th>Horse</th>
                    <th style="width: 73px;padding: 8px 0px;">Class</th>
                    <th>Additional</th>
                    <th>Misc.</th>
                    <th>Split</th>
                    <th style="width: 80px;padding: 8px 0px;">Division</th>
                    <th style="width: 73px;padding: 8px 0px;">Stall</th>
                    <th>Taxes</th>
                    <th>Prize</th>
                    <th>Total</th>
                </tr>
                </thead>
                <tbody>
                <?php  $totalSum=0;
                $totalRoyalty=0;
                $invoices = [];

             // dd($horseInvoices->toArray());

                ?>
                  @foreach($horseInvoices as $row)
                <tr>
                <td>
                    <label>
                        <input type="checkbox" checked value="{{$row->id}}" name="horseSelected[]" class="check select-past-participant">
                        <span>&nbsp</span>
                    </label>
                </td>

                <td>{{GetAssetNamefromId($row->horse_id)}} </td>
                <td style="padding: 8px 0px">$ {{$row->class_price or '0'}}</td>
                <td>$ {{$row->additional_price or '0'}}</td>
                <td> $ {{$row->royalty or '0'}}</td>
                <td> $ {{$row->split_chargesor or '0'}}</td>
                <td style="padding: 8px 0px"> $ {{$row->division_price or '0'}}</td>
                <td style="padding: 8px 0px"> $ {{$row->stall_price or '0'}}</td>
                <td> $ {{$row->prize_won or '0'}}</td>
                 <td> $ {{$row->total_taxis or '0'}}</td>
                <td>$ <span class="totalPrice">{{ $row->horse_total_price or '0'}}</span></td>
                </tr>
                      <?php $totalSum = $row->horse_total_price+ $totalSum;
                      $totalRoyalty = $row->royalty+ $totalRoyalty;
                      $invoices[]=$row->id;
                      $invoiceId = json_encode($invoices);
                      ?>
                  @endforeach
                <?php
                $showOwner = $totalSum - $totalRoyalty;
                ?>
                </tbody>
            </table>
            <hr>

            <div class="row">
                <div class="col-md-10 text-right">
                    <strong>TOTAL</strong>
                </div>
                <div class="col-md-2 pl-50">
                    <strong>$ <span class="totalSum">{{$totalSum}}</span></strong>
                </div>
            </div>

            <input type="hidden"  class="totalSum" name="totalSum" value="{{$totalSum}}">
            <input type="hidden" class="T_sum"  value="{{$totalSum}}">
            <input type="hidden" name="show_id" value="{{$show_id}}">


            <div class="row">
                @if($totalSum > 0 && $paypalAccountDetail > 0)

                <div class="col-sm-2">

                    <?php
                    $paypalCharges = twodecimalformate($stripeAmount = (config('services.paypal.paypalFee') +config('services.paypal.paypalFeeCent')/100)/100*$totalSum,2);
                    $paypalAmount = $totalSum + $paypalCharges;
                    ?>
                <button type="submit" class="btn-lg stripeBtn" name="type" value="paypal">Pay By Paypal</button>
                <span class="paypalCharges" style="text-align: center; font-size: 11px;">Paypal Charges( ${{$paypalCharges}})</span>

                </div>
                @endif

                @if($totalSum > 0 && $stripeDetails > 0)
                <div class="col-sm-1">
                &nbsp
                </div>
                @endif

                <div class="col-sm-2 ml-25" >
                  <a href="javascript:" onclick="history.go(-1);"  @if($stripeDetails > 0 && $paypalAccountDetail <= 0) style="margin-bottom: 21px;color:#FFF" @endif class="btn btn-primary strp stripeBtn">Cancel</a>
                </div>
            </div>

        </div>
    </div>
    {!! Form::close() !!}
    @if($totalSum > 0 && $stripeDetails > 0)

    <div class="col-sm-2" id="stripeee" @if($totalSum > 0 && $paypalAccountDetail > 0)  style="visibility: visible;position: absolute;bottom: 0px;left: 209px;" @else style="visibility: visible;position: absolute;bottom: 2px;left: 21px;" @endif >
        <?php
        $stripeCharges = twodecimalformate($stripeAmount = (config('services.stripe.stripeFee') +config('services.stripe.stripeFeeCent')/100)/100*$totalSum,2);
        $stripeAmount = ($totalSum * 100) + $stripeCharges*100;
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
            <input type="hidden" name="amount" value="{{$totalSum * 100}}">
        </form>
            <input type="hidden" id="stripeCharges" value="{{$stripeCharges}}">

            <input type="hidden" id="paypalCharges" value="{{$paypalCharges}}">



            <span id="stripeFees" style="text-align: center; font-size: 11px;">Stripe charges( ${{$stripeCharges}})</span>

    </div>
        @endif
</div>
            </div>
        </div>
@endsection

@section('footer-scripts')
    <script src="{{ asset('/js/ajax-dynamic-assets-table.js') }}"></script>
    @include('layouts.partials.datatable')
    <style>

        .stripeBtn {
            background-image: linear-gradient(#28a0e5,#015e94);
            border: none;
            border-radius: 5px;
            color: #FFFFFF;
            padding: 5px;
            width: 90%;
            font-weight: bold;
            font-size: 14px;
            line-height: 1.6;
            margin-top: 9px;
        }
        .strp{
            background:#001e46;
        }

    </style>

@endsection