@extends('layouts.equetica2')
@section('custom-htmlheader')
    @include('layouts.partials.form-header')
@endsection
@section('main-content')
    <!-- ================= CONTENT AREA ================== -->
    <div class="container-fluid">
    @php
        $title = "Payment Receiving Methods";
        $added_subtitle =Breadcrumbs::render('participant-payment-methods');
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

            <div class="col-md-12">

                @if(!$account)
                    <div class="row">
                            <div class="col-md-2 pt-10">
                                <h3>Stripe Account</h3>
                            </div>
                            <div class="col-md-10">
                            {{--<div class="col-md-12 btnContainer">--}}
                                {{--<div class="row">--}}
                                {{--<div class="col-md-3 pl-0">--}}
                                    {{--<a  onclick="UserForm('alreadyUser',this)" class="app-action-link btn btn-primary " href="javascript:">Already Stripe User</a>--}}
                                {{--</div>--}}
                           {{----}}
                                    {{--</div>--}}
                            {{--</div>--}}

                                <form method="post" action="/participant/submit/stripe" name="stripeEmail">
                                <div class="form-group alreadyUser">
                                        {{csrf_field()}}
                                    <div class="row">
                                    <div class="col-md-4">
                                    <div class="form-group-lg">
                                        <input class="form-control" placeholder="enter email to register in stripe" required name="email" style="border: 1px solid rgb(205, 205, 205); border-bottom-left-radius: 5px; border-top-left-radius: 5px; padding-left: 10px;" type="text">
                                    </div>
                                    </div>
                                    <div class="col-md-4">
                                    <div class="form-group-lg">
                                        <input class="form-control" placeholder="enteryour stripe account id" required name="stripeAccount" style="border: 1px solid rgb(205, 205, 205); border-bottom-left-radius: 5px; border-top-left-radius: 5px; padding-left: 10px;" type="text">
                                    </div>
                                    </div>

                                    <div class="col-md-4">
                                        <input name="submit" class="btn   btn-success"  value="Submit" type="submit">
                                        <input name="cancel" class="btn  btn-primary cancelBtn"  value="Cancel" type="button">
                                    </div>
                                    </div>

                                </div>
                                </form>

                            </div>
                    </div>
                @else
                          <div class="stripDetailCon row">

                                <input type="hidden" value="{{$account->stripe_account_email}}" id="stripe_account_email">
                               <input type="hidden" value="{{$account->stripe_account_id}}" id="stripe_account_id">

                            <div class="col-md-2 pt-10">
                                <h3>Stripe Account</h3>
                            </div>
                            <div class="col-md-4">
                                <div class="col-sm-4" style="padding-left: 0px;"><strong>Stripe Email</strong></div>
                                <div class="col-sm-8"
                                     style="padding-left: 0px;"> {{$account->stripe_account_email}}</div>
                            </div>
                            <div class="col-md-4">
                                <div class="col-sm-4"><strong>Stripe Account</strong></div>
                                <div class="col-sm-8"> {{$account->stripe_account_id}}</div>
                            </div>
                            <div class="col-md-2">
                    <a href="javascript:" class="btn btn-success editButton">Edit Information</a>
                    </div>
                           </div>
                        <form method="post" action="/participant/edit/stripe" name="editStripeEmail">
                            <div class="form-group alreadyUserEdit" style="display: none">

                            {{csrf_field()}}
                            <div class="row">
                                <div class="col-md-2 pt-10 pl-0">
                                    <h3>Stripe Account</h3>
                                </div>
                            <div class="col-md-3">
                                <div class="form-group-lg">
                                    <input class="form-control emailAddress" placeholder="enter email to register in stripe" required name="email" style="border: 1px solid rgb(205, 205, 205); border-bottom-left-radius: 5px; border-top-left-radius: 5px; padding-left: 10px;" type="text">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group-lg">
                                    <input class="form-control accountId" placeholder="enteryour stripe account id" required name="stripeAccount" style="border: 1px solid rgb(205, 205, 205); border-bottom-left-radius: 5px; border-top-left-radius: 5px; padding-left: 10px;" type="text">
                                </div>
                            </div>

                            <div class="col-md-4" >
                                <input name="submit" class=" btn  btn-success"  style="width: 100px; margin-right: 10px;" value="Submit" type="submit">
                                <input name="cancel" class=" btn btn-primary editCancelBtn" style="width: 100px; margin-right: 10px; font-size: 14px;" value="Cancel" type="button">
                                {{--<a  onclick="UserForm('newUser',this)" style="color: #ffffff!important;" class="btn btn-success" href="javascript:" >New User</a>--}}
                            </div>
                            </div>
                            </div>
                        </form>


                @endif

            </div>

            <div class="col-md-12">
                <hr>
                <div class="row">

                    <div class="col-md-2 pt-10">  <h3>Paypal Account</h3></div>
                @if($paypalAccountDetail)
                        <div class="col-md-4" >
                                <div class="col-sm-4 p-0"><strong>Paypal Email</strong></div>
                                <div class="col-sm-4 p-0"> {{$paypalAccountDetail->paypalEmail}}</div>
                            </div>
                         <div class="col-md-5">
                                <div class="col-sm-4"><strong>Paypal Account </strong></div>
                                <div class="col-sm-4"> {{$paypalAccountDetail->accountId}}</div>
                            </div>
                @else
                    {{--<div class="col-md-2">--}}
                    {{--<a data-toggle="modal" data-target=".paypalAccount" class="app-action-link btn btn-primary " href="javascript:">Sign Up Paypal</a>--}}
{{--</div>--}}
                    <div class="col-md-5">
                    <a data-toggle="modal" data-target=".paypalAccountDetails" class="app-action-link btn btn-primary " href="javascript:" >Add Paypal Account Details</a>
</div>
                @endif
                    </div>
            </div>
            </div>
    </div>







    <div class="modal fade bs-example-modal-sm  paypalAccount" tabindex="-1" role="dialog"
         aria-labelledby="myLargeModalLabel">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h2 class="modal-title" id="exampleModalLabel">Enter Account Detail</h2>
                </div>
                <div class="modal-body">

                    <form method="post" action="{{URL::to('master-template') }}/createPaypalAccount"
                          name="Bank_account">
                        <div class="row" style="margin-top: 40px;">
                            {{csrf_field()}}
                            <div class="col-sm-12">
                                <div class="col-md-12">

                                    <input type="hidden" name="detailId"  value="">


                                    Personal or Premier
                                    <div class="form-group">
                                        <div class="state-wrap form-group">
                                            <label for="state">State</label>
                                            <select required class="form-control" id="accountType" name="accountType">
                                                <option value="Personal">Individual</option>
                                                <option value="Premier">Company</option>
                                            </select>
                                        </div>
                                        </div>
                                    <div class="form-group">
                                        <label for="account_number">First Name:</label>
                                        <input type="text" class="form-control" id="FirstName" required name="FirstName">
                                    </div>
                                    <div class="form-group">
                                        <label for="account_number">Last Name:</label>
                                        <input type="text" class="form-control" id="LastName" required name="LastName">
                                    </div>
                                    <div class="form-group">
                                        <label for="account_number">Email Address:</label>
                                        <input type="email" class="form-control" id="emailAddress" required name="emailAddress">
                                    </div>
                                    <div class="form-group">
                                        <label for="account_number">Date Of Birth:</label>

                                        <div class='input-group date' >
                                            <input type='text' class="form-control datetimepickerDate"  id='datepicker' required name="dateOfBirth" />
                                        </div>

                                    </div>
                                    <div class="form-group">
                                        <h2>Address</h2>
                                        <div style="margin-top: 20px; margin-left: 30px;">
                                        <div>
                                            <div class=" form-group">
                                                <label for="address-line-1">Street #1</label>
                                                <input class="form-control" type="text" name="address-line-1" id="address-line-1">
                                                <p class="text-lowercase" style="float: right; text-transform: none; font-size: 12px; margin-top: 5px;">like , 1503 Main St</p>
                                            </div>
                                        </div>
                                        <div>
                                            <div class=" form-group">
                                                <label  for="address-line-2">Street #2</label>
                                                <input class="form-control" type="text" name="address-line-2" id="address-line-2">
                                            </div>
                                        </div>
                                        <div>
                                            <div class="city-wrap form-group">
                                                <label for="city">City</label>

                                                <input class="form-control" type="text" name="city" id="city">
                                            </div>
                                            <?php  $state = getStates();  ?>
                                            <div class="state-wrap form-group">
                                                <label for="state">State</label>
                                                    <select required class="form-control" id="state" name="state">
                                                    @foreach($state as $key=>$value)
                                                        <option value="{{$key}}">{{$value}}</option>
                                                    @endforeach
                                                    </select>
                                            </div>
                                            <div class="zip-wrap form-group">
                                                <label for="zip">Zip Code</label>
                                                <input class="form-control" type="text" pattern="[0-9]*" name="zip" id="zip">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                    <div class="form-group">
                                        <label for="routing_number">Country Code:</label>
                                        <input type="text" class="form-control" id="citizenshipCountryCode" required name="citizenshipCountryCode">
                                        <p class="text-lowercase" style="float: right; text-transform: none; font-size: 12px; margin-top: 5px;">Currency code like , US, AU, CA</p>

                                    </div>

                                    <div class="form-group">
                                        <label for="account_number">Contact Phone Number:</label>
                                        <input type="text" class="form-control phone-format" id="contactPhoneNumber" required name="contactPhoneNumber">
                                    </div>

                                    <div class="form-group">
                                        <label for="account_number">Currency Code:</label>
                                        <input type="text" class="form-control" id="currencyCode" required name="currencyCode">
                                        <p class="text-lowercase" style="float: right; text-transform: none; font-size: 12px; margin-top: 5px;">Currency code like , USD, AUD, CAD</p>

                                    </div>
                                </div>
                            </div>


                            <div class="col-md-4">
                                <input type="submit" name="submit" class="btn btn-lg  btn-success btn-close"
                                       value="Submit">
                            </div>


                        </div>
                </div>
                </form>


            </div>
        </div>
    </div>

    <div class="modal fade bs-example-modal-sm  paypalAccountDetails" tabindex="-1" role="dialog"
         aria-labelledby="myLargeModalLabel">


        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Enter Paypal Account Details</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">

                    <form method="post" action="{{URL::to('master-template') }}/EmailVerificationPaypal"
                          name="PaypalDetails">
                        <div class="row" style="margin-top: 40px;">
                            {{csrf_field()}}
                            <div class="col-sm-12">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="account_number">First Name:</label>
                                        <input type="text" class="form-control" id="FirstName" required name="FirstName">
                                    </div>
                                    <div class="form-group">
                                        <label for="account_number">Last Name:</label>
                                        <input type="text" class="form-control" id="LastName" required name="LastName">
                                    </div>
                                    <div class="form-group">
                                        <label for="account_number">Email Address:</label>
                                        <input type="text" class="form-control" id="emailAddress" required name="emailAddress">
                                    </div>

                                </div>
                            </div>


                            <div class="col-md-4">
                                <input type="submit" name="submit" class="btn btn-success btn-close"
                                       value="Submit">
                            </div>


                        </div>
                </div>
                </form>


            </div>
        </div>
    </div>




    </div>


    <!-- Tab containing all the data tables -->


@endsection

@section('footer-scripts')

    <link href="{{ asset('/css/vender/bootstrap-datetimepicker.min.css') }}" rel="stylesheet" />
    <script type="text/javascript" src="{{ asset('/js/vender/moment.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/js/vender/transition.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/js/vender/bootstrap-datetimepicker.min.js') }}"></script>

<link href="{{ asset('/css/vender/bootstrap-datetimepicker.min.css') }}" rel="stylesheet" />
<link href="{{ asset('/css/vender/daterangepicker.css') }}" rel="stylesheet" />
    <script src="<?php echo e(asset('/js/custom-tabs-cookies.js')); ?>"></script>
    <script src="<?php echo e(asset('/js/vender/jquery-ui.min.js')); ?>"></script>
    <link rel="stylesheet" href="<?php echo e(asset('/css/vender/jquery-ui.css')); ?>" />
    <script type="text/javascript" src="<?php echo e(asset('/js/vender/bootstrap-tooltip.js')); ?>"></script>
    <link href="<?php echo e(asset('/css/vender/bootstrap-datepicker.css')); ?>" rel="stylesheet" />
    <link href="<?php echo e(asset('/css/vender/daterangepicker.css')); ?>" rel="stylesheet" />
    <script type="text/javascript" src="<?php echo e(asset('/js/vender/bootstrap-datepicker.min.js')); ?>"></script>
<script type="text/javascript" src="{{ asset('/js/vender/bootstrap-datetimepicker.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('/js/vender/daterangepicker.js') }}"></script>

    <script src="{{ asset('/js/ajax-dynamic-assets-table.js') }}"></script>
    <script src="{{ asset('/js/custom-function.js') }}"></script>

    @include('layouts.partials.datatable')

<script>

$(document).ready(function () {
    $('#datepicker').datepicker({
inline: true
});
});
</script>
@endsection
