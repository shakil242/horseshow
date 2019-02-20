<?php

namespace App\Http\Controllers;

use App\AppownerBankAccountInformation;
use App\Billing;
use App\Division;
use App\HorseInvoices;
use App\HorseRiderStall;
use App\InvitedUser;
use App\Invoice;
use App\ManageShowTrainerSplit;
use App\Participant;
use App\ParticipantAccountInformation;
use App\PaypalAccountDetail;
use App\paypalDetail;
use App\ShowStallUtility;
use App\SponsorCategoryBilling;
use App\Template;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Srmklive\PayPal\Facades\PayPal;
use Stripe\Charge;
use Stripe\Customer;
use Stripe\Stripe;
use Stripe\Error\Card;
use App\ManageShowsRegister;
use App\ManageShows;
use App\ClassHorse;
use App\ShowPrizingListing;
use App\ShowPayInOffice;
use Carbon\Carbon;

class BillingController extends Controller
{
    
    public function paymentMethods()
    {
    
        Stripe::setApiKey(config('services.stripe.secret'));
    
        $user_id = \Auth::user()->id;
        
        $billingDetail = [];
        
        $account = ParticipantAccountInformation::where('participant_id',$user_id)->first();
        
        $bankDetails = AppownerBankAccountInformation::where('owner_id',$user_id)->first();
        
        $paypalAccountDetail = PaypalAccountDetail::where('userId',$user_id)->first();
         
        return view('invoice.billing.paymentMethod')->with(compact('account','bankDetails','paypalAccountDetail'));
        
    }
    
    public function billingDetail($user_type)
    {
        $user_id = \Auth::user()->id;
        Stripe::setApiKey(config('services.stripe.secret'));
        $customers = \Stripe\Customer::all();
        
        $billingReceiveDetails = Billing::where('participant_id',$user_id)->orderBy('id','DESC')->get();
        $billingTransferDetails = Billing::where('sender_id',$user_id)->orderBy('id','DESC')->get();
        
        return view('invoice.billing.index')->with(compact('billingReceiveDetails','billingTransferDetails','user_type'));
    }
    
    
    
    public function getAccountDetail(Request $request)
    {
    
    
        try {
            
            $email = $request->get('email');
            $stripeAccount = $request->get('stripeAccount');
    
            Stripe::setApiKey(config('services.stripe.secret'));
            
            $customersResults = \Stripe\Account::all();

            $arr = [];
    
            foreach ($customersResults->data as $row) {
                $arr[] = $row['email'];
            }
            
            $user_id = \Auth::user()->id;

                if($stripeAccount!='')
                {

                    $account = \Stripe\Account::retrieve($stripeAccount);
                    if($account) {
                        $model = new ParticipantAccountInformation();

                        $model->participant_id = $user_id;
                        $model->stripe_account_id = $stripeAccount;
                        $model->stripe_account_email = $email;

                        $model->save();
                        \Session::flash('message', 'Stripe information has been saved successfully');
                    }
                }else {
                    \Session::flash('message', 'Please add proper stripe email and account id for proper configuration.');
                }

        }

        catch (\Stripe\Error\ApiConnection $e) {
            \Session::flash('message',$e->getMessage());
        } catch (\Stripe\Error\InvalidRequest $e) {
            \Session::flash('message',$e->getMessage());
        } catch (\Stripe\Error\Api $e) {
            \Session::flash('message',$e->getMessage());
        } catch (\Stripe\Error\Card $e) {
            \Session::flash('message',$e->getMessage());
        }catch (\Stripe\Error\Permission $e) {
            \Session::flash('message',$e->getMessage());
        }
        
        return redirect()->action('BillingController@paymentMethods');
    
    }
    
    /**
     * @return string
     */
    public function payoutProcess()
    {


        \Stripe\Transfer::create(
            array(
                "amount" => 1000,
                "currency" => "usd",
                "destination" => "{PLATFORM_STRIPE_ACCOUNT_ID}"
            ),
            array("stripe_account" => "{CONNECTED_STRIPE_ACCOUNT_ID}")
        );


    }


    /**
    * Pay in office action.
     * @return string
     */
    public function appownerPayInOffice(Request $request)
    {
        $user_id = \Auth::user()->id;

        $show_id =  $request->show_id;
        $horse_id =  $request->horse_id;
        $amount = $request->total_price;
        $royality = $request->royalty;
        $appOwnerAmount = ($amount / 100)  * $royality;
        $participantAmount = $amount - $appOwnerAmount;
        $payofficeAll = $request->all();

        $invoiceOwner = $request->invoice_owner_id;

        $result = HorseInvoices::where('show_id',$show_id)->where('horse_id',$horse_id)->where('invoice_status',0)->first();
        

            if(isset($result) && $result->id!='')
            {
               $model = $result;
            }
            else {
               $model = new HorseInvoices();
            }

            $model->show_id = $show_id;
            $model->horse_id = $horse_id;
            $model->payer_id = $invoiceOwner;
            $model->payment_receiver_id = $user_id;
            $model->class_price = $payofficeAll['assets_price'];
            if(isset($payofficeAll['additional_price']))
            $model->additional_price = $payofficeAll['additional_price'];
            $model->royalty = $payofficeAll['royalty'];
            if(isset($payofficeAll['prize_won']))
            $model->prize_won = $payofficeAll['prize_won'];
            if(isset($payofficeAll['split_charges']))
            $model->split_charges = $payofficeAll['split_charges'];
            $model->horse_total_price = $payofficeAll['total_price'];
            $model->total_bill_price = $payofficeAll['total_price'];
            if(isset($payofficeAll['division_price']))
            $model->division_price = $payofficeAll['division_price'];
            if(isset($payofficeAll['stall_price']))
            $model->stall_price = $payofficeAll['stall_price'];
            if(isset($payofficeAll['total_taxis']))
            $model->total_taxis = $payofficeAll['total_taxis'];
            $model->save();



            if ($model) {
                $billing = new Billing();

                $billing->invoice_id = $model->id;

                $billing->sender_id = $invoiceOwner;

                //$billing->stripe_receiver_account_id = $model->stripe_account_id;

                $billing->charge_id = $request->payinoffice_details;

                $billing->amount_transfer = $participantAmount;

                $billing->amount_sent = $amount;

                $billing->participant_id = $model->payment_receiver_id;

                $billing->application_fee = $appOwnerAmount;

                $billing->type = 'pay in office';

                $billing->save();
            }

            // for paid invoices


            

            $time_now = updatePaidInvoices($horse_id,$show_id,$model->id,$invoiceOwner);

            $SPIO = ShowPayInOffice::where('show_id',$show_id)->where("horse_id",$horse_id)->where("invoice_status",0)->get();
            foreach($SPIO as $sts)
            {
                $stl = ShowPayInOffice::findOrFail($sts->id);
                $stl->invoice_status=1;
                $stl->paid_on=$time_now;

                $stl->update();
            }

            \Session::flash('message',"The invoice for horse: ".GetAssetNamefromId($horse_id)." has been moved to paid invoice");
            return redirect()->back();
    
    }



       /**
    * Pay in office action.
     * @return string
     */
    public function appownerPayInOfficeEdit(Request $request)
    {
        $billingid = $request->billing_id;
        $result = Billing::findOrfail($billingid);
        $result->charge_id = $request->payinoffice_details;
        $result->save();

            \Session::flash('message',"The invoice detials has been Updated");
            return redirect()->back();
    }


    
    public function submitCheckout(Request $request)
    {

        $user_id = \Auth::user()->id;

        $invoiceIdArr =  json_decode($request->get("invoiceId"));

       // dd($invoiceIdArr);

        $invoice = HorseInvoices::whereIn('id',$invoiceIdArr)->get();

        //dd($invoice->toArray());
        $appOwnerAmount = 0;

        $arrDestination = [];
        $arrMetaDeta = [];
        $amountToPay =  $request->get("amount");
        foreach ($invoice as $inv) {

            $amount = $amountToPay;

            $appownerEmail= User::where('id',$inv->payment_receiver_id)->pluck('email')->first();

            $template_id = ManageShows::where('id',$inv->show_id)->pluck('template_id')->first();

            $invitedUser = InvitedUser::where('email','=',$appownerEmail)->where('template_id',$template_id)->orderBy('id','desc')->first();

            $royality = $invitedUser->royalty;

            $appOwnerAmount = ($amount / 100)  * $royality;

            $participantAmount = $amount - $appOwnerAmount;

            $model = ParticipantAccountInformation::where('participant_id',$inv->payment_receiver_id)->first();

            //dd($model->toArray());

            if($model) {

                if($participantAmount>0) {
                    $arrDestination = [
                        "amount" => round($participantAmount),
                        "account" => $model->stripe_account_id,
                    ];

                    $arrMetaDeta = [
                        "user_account_id" => $model->stripe_account_id,
                        "royality" => $appOwnerAmount,
                        "sender_id" => $user_id,
                        "receiver_id" => $model->participant_id,
                        "receiverAmount" => $participantAmount,
                        "showId" => $inv->show_id,
                        "type" => 'Credit Card'
                    ];
                }

            }
            else
            {
            setAlert("warning",getUserNamefromid($inv->invitee_id)," has not yet configured the stripe account");
            }

        }
        Stripe::setApiKey(config('services.stripe.secret'));
        
        $customer = Customer::create([
            'email' => request('stripeEmail'),
            'source' => request('stripeToken')
        ]);


        if(isset($arrDestination) && count($arrDestination) > 0) {
            $charge = Charge::create([
                'customer' => $customer->id,
                'amount' => "$amountToPay",
                'currency' => 'usd',
                "destination" => $arrDestination,
                "metadata" => $arrMetaDeta
            ]);


            if ($charge) {

                $classID = [];

                foreach ($invoice as $inv) {

                    $amount = $inv->total_bill_price * 100;

                    $appownerEmail = User::where('id', $inv->payment_receiver_id)->pluck('email')->first();

                    $template_id = ManageShows::where('id', $inv->show_id)->pluck('template_id')->first();

                    $show_id = $inv->show_id;

                    $invitedUser = InvitedUser::where('email', '=', $appownerEmail)->where('template_id', $template_id)->orderBy('id', 'desc')->first();

                    $royality = $invitedUser->royalty;

                    $appOwnerAmount = ($royality / 100) * $amount;

                    $stripeAmount = (config('services.stripe.stripeFee') / 100) * $amount + config('services.stripe.stripeFeeCent');

                    $participantAmount = floatval($amount - $appOwnerAmount);

                    $model = ParticipantAccountInformation::where('participant_id', $inv->payment_receiver_id)->first();

                    if ($model) {
                        $billing = new Billing();

                        $billing->invoice_id = $inv->id;

                        $billing->sender_id = $user_id;

                        $billing->stripe_receiver_account_id = $model->stripe_account_id;

                        $billing->charge_id = $charge->id;

                        $billing->amount_transfer = $participantAmount;

                        $billing->amount_sent = $amount;

                        $billing->participant_id = $inv->payment_receiver_id;

                        $billing->application_fee = $appOwnerAmount;

                        $billing->type = 'Credit Card';

                        $billing->save();
                    }

                    // for paid invoices
                    $horse_id = $inv->horse_id;

                    updatePaidInvoices($horse_id,$show_id,$inv->id);


                }

            }


            \Session::flash('message', 'Invoice has been paid successfully');

        }
        return Redirect::to('shows/'.nxb_encode($invoice[0]->show_id).'/horse/invoices');
    }
    
    
    public function saveBankAccountInfo(Request $request)
    {
        
//    "account_holder_name" => "Ella Jackson",
//    "account_holder_type" => "individual",
//    "routing_number" => "110000000",
//    "account_number" => "000123456789"
        try {
            
      $user_id = \Auth::user()->id;
    
      $account_holder_name = trim($request->get('account_holder_name'));
      $account_holder_type = trim($request->get('account_holder_type'));
      $routing_number = trim($request->get('routing_number'));
      $account_number = trim($request->get('account_number'));
      $detailId = trim($request->get('detailId'));
            
       Stripe::setApiKey(config('services.stripe.secret'));
        $token = \Stripe\Token::create(array(
            "bank_account" => array(
                "country" => "US",
                "currency" => "usd",
                "account_holder_name" =>$account_holder_name,
                "account_holder_type" =>$account_holder_type,
                "routing_number" =>$routing_number,
                "account_number" =>$account_number
            )
        ));
            
        $customer =  \Stripe\Customer::create(array(
            "source" => $token['id'],
            "description" => "equetica Applicant"
        ));
    
      $bank_account = $customer->sources->retrieve($customer['default_source']);

            
            
       $bank_account->verify(array('amounts' => array(32, 45)));
            
            
        if($customer['id']!='') {

            if(empty($detailId))
            {
                $model = new AppownerBankAccountInformation();

                $model->owner_id = $user_id;
                $model->account_holder_name = $account_holder_name;
                $model->account_holder_type = $account_holder_type;
                $model->routing_number = $routing_number;
                $model->account_number = $account_number;
                $model->stripe_customer_id = $customer['id'];

                $model->save();
                
            }
            else {
                
                $model = AppownerBankAccountInformation::findOrfail($detailId);

                $model->owner_id = $user_id;
                $model->account_holder_name = $account_holder_name;
                $model->account_holder_type = $account_holder_type;
                $model->routing_number = $routing_number;
                $model->account_number = $account_number;
                $model->stripe_customer_id = $customer['id'];

                $model->update();
            }
            \Session::flash('message', 'Account information has been saved successfully');

        }else
        {
            \Session::flash('message', 'Account information is not correct');
        }
        
        }
        
    catch (\Stripe\Error\ApiConnection $e) {
    \Session::flash('message',$e->getMessage());
    } catch (\Stripe\Error\InvalidRequest $e) {
      \Session::flash('message',$e->getMessage());
    } catch (\Stripe\Error\Api $e) {
            \Session::flash('message',$e->getMessage());
    } catch (\Stripe\Error\Card $e) {
    \Session::flash('message',$e->getMessage());
    }
        return redirect()->back();


//
//
//      STRIPE_KEY =pk_test_Ydeg2p5DGWncK60dVUOTFC2r
 //       STRIPE_SECRET = sk_test_MIazWbwHvib7RENJf2p1z0Z1
//        $customer = \Stripe\Customer::retrieve($customer['id']);
//
//        $bank_account = $customer->sources->retrieve($customer['default_source']);
//
//// verify the account
//        $bank_account->verify(array('amounts' => array(32, 45)));
//
//
//        \Stripe\Stripe::setApiKey("sk_test_MIazWbwHvib7RENJf2p1z0Z1");
//
//        $cahrge =  \Stripe\Charge::create(array(
//            "amount" => 150000,
//            "currency" => "usd",
//            "customer" => $customer->id // Previously stored, then retrieved
//        ));
    
    
    
    
    }
        /**
     * @return string
     */

    public function setMultiInvoice(Request $request)
    {
        $commulativeInvoice = $request->get("commulativeInvoice");

        $invoice = Invoice::whereIn('id',$commulativeInvoice)->get();

        \Session::put('multipleInvoice', $invoice);
        \Session::put('templateIdInvoice', $invoice[0]->template_id);

    }


    //calculate the billing ndetail for invoice

    public function singleInvoice($id,$amount)
    {

        $user_id = \Auth::user()->id;
        $id = nxb_decode($id);
        $amount = nxb_decode($amount);


        $invoice = Invoice::where('id', $id)->first();
        $template_id = $invoice->template_id;
        $invoice->amount = $amount;

        $OwnerInfo = AppownerBankAccountInformation::where('owner_id',$user_id)->first();

        $paypalAccountDetail = PaypalAccountDetail::where('userId',$invoice->show_owner_id)->first();

        $dataBreadCrumb = [
            'id' => nxb_encode($id),
            'templateId' => nxb_encode($template_id)
        ];
        return view('invoice.invitee.viewConfirmation')->with(compact("invoice","OwnerInfo","paypalAccountDetail","user_id","dataBreadCrumb"));
        
    }


    public function multipleInvocie()
    {

        $user_id = \Auth::user()->id;

        $multipleInvoice = \Session('multipleInvoice');

        if(isset($multipleInvoice)) {
            $invoice = $multipleInvoice;
            $template_id = $multipleInvoice = \Session('templateIdInvoice');
//            \Session::put('multipleInvoice', '');
//            \Session::put('templateIdInvoice', '');


            $OwnerInfo = AppownerBankAccountInformation::where('owner_id', $user_id)->first();

            $paypalAccountDetail = PaypalAccountDetail::where('userId', $user_id)->first();

            $dataBreadCrumb = [
               // 'id' => nxb_encode($invoice[0]['id']),
                'templateId' => nxb_encode($template_id)
            ];

            //dd($invoice);

            return view('invoice.invitee.multilpeInvoice')->with(compact("invoice", "OwnerInfo", "paypalAccountDetail", "user_id", "dataBreadCrumb"));
        }
    }



    /**
     * @return string
     */
    public function submitByAccount($id)
    {

        try {
            $arrId = [];

            Stripe::setApiKey(config('services.stripe.secret'));

            $user_id = \Auth::user()->id;

            $id = nxb_decode($id);

            $arrId = explode(',', $id);

            $invoice = Invoice::whereIn('id', $arrId)->get();

            $OwnerInfo = AppownerBankAccountInformation::where('owner_id', $user_id)->first();

            $appOwnerAmount = 0;

            $amount = 0;

            foreach ($invoice as $inv) {

                $template = Template::where('id', $inv->template_id)->first();

                $amount = $amount + $inv->amount * 100;

                $royality = $template->royalty;

                $appOwnerSingle = ($royality / 100) * $amount;

                $appOwnerAmount = $appOwnerAmount + ($royality / 100) * $amount;

                $stripeAmount = (config('services.stripe.stripeFee') / 100) * $amount + config('services.stripe.stripeFeeCent');

                $participantAmount = round(($amount - $appOwnerSingle - $stripeAmount));

                $model = ParticipantAccountInformation::where('participant_id', $inv->invitee_id)->first();

                if ($model) {
                    $arrDestination = [
                        "amount" => "$participantAmount",
                        "account" => $model->stripe_account_id,
                    ];
                    $arrMetaDeta = [
                        "user_account_id" => $model->stripe_account_id,
                        "royality" => $appOwnerSingle,
                        "stripeCharges" => $stripeAmount,
                        "sender_id" => $user_id,
                        "receiver_id" => $inv->invitee_id,
                        "receiverAmount" => $participantAmount,
                        "type" => 'Bank Account'
                    ];

                } else {
                    setAlert("warning", getUserNamefromid($inv->invitee_id), " has not given his bank account details");
                }

            }


            $customer = \Stripe\Customer::retrieve($OwnerInfo->stripe_customer_id);

            $bank_account = $customer->sources->retrieve($customer['default_source']);

            // verify the account
            // $bank_account->verify(array('amounts' => array(32, 45)));

            if (isset($arrDestination)) {
                $charge = Charge::create([
                    'customer' => $customer->id,
                    'amount' => "$amount",
                    'currency' => 'usd',
                    "destination" => $arrDestination,
                    "metadata" => $arrMetaDeta

                ]);
            }


            if (isset($charge)) {


                foreach ($invoice as $inv) {

                    $template = Template::where('id', $inv->template_id)->first();

                    $amount = $inv->amount * 100;

                    $royality = $template->royalty;

                    $appOwnerAmount = ($royality / 100) * $amount;

                    $stripeAmount = (config('services.stripe.stripeFee') / 100) * $amount + config('services.stripe.stripeFeeCent');

                    $participantAmount = floatval($amount - $appOwnerAmount - $stripeAmount);

                    $model = ParticipantAccountInformation::where('participant_id', $inv->invitee_id)->first();

                    if ($model) {
                        $billing = new Billing();

                        $billing->invoice_id = $inv->id;

                        $billing->sender_id = $user_id;

                        $billing->stripe_receiver_account_id = $model->stripe_account_id;

                        $billing->charge_id = $charge->id;

                        $billing->amount_transfer = $participantAmount;

                        $billing->amount_sent = $amount;

                        $billing->participant_id = $inv->user_id;

                        $billing->application_fee = $appOwnerAmount;

                        $billing->save();
                    }
                }

                setAlert("success", '', " Payment has been transferd successfully");

                return Redirect::to(\Session('urlInvoice'));

            } else {

                setAlert("error", '', " There is an error to send transfer");

                return Redirect::to(\Session('urlInvoice'));
            }
        }

        catch (\Stripe\Error\ApiConnection $e) {
            setAlert("error", '', $e->getMessage());
        } catch (\Stripe\Error\InvalidRequest $e) {
            setAlert("error", '', $e->getMessage());
        } catch (\Stripe\Error\Api $e) {
            setAlert("error", '', $e->getMessage());
        } catch (\Stripe\Error\Card $e) {
            setAlert("error", '', $e->getMessage());
        }
        return Redirect::to(\Session('urlInvoice'));

    
    }
    
    
    public function editStripeDetail(Request $request)
    {
        
        try {

            Stripe::setApiKey(config('services.stripe.secret'));

            $user_id = \Auth::user()->id;
            $email = $request->get('email');
            $stripeAccount = $request->get('stripeAccount');
            $account = \Stripe\Account::retrieve($stripeAccount);
            if($account) {
                $model = ParticipantAccountInformation::where('participant_id', $user_id)->first();
                $model->participant_id = $user_id;
                $model->stripe_account_id = $stripeAccount;
                $model->stripe_account_email = $email;

                $model->update();
                \Session::flash('message', 'Stripe Detail has been updated successfully');
            }
            
        }
        
        catch (\Stripe\Error\ApiConnection $e) {
            \Session::flash('message',$e->getMessage());
        } catch (\Stripe\Error\InvalidRequest $e) {
            \Session::flash('message',$e->getMessage());
        } catch (\Stripe\Error\Api $e) {
            \Session::flash('message',$e->getMessage());
        } catch (\Stripe\Error\Card $e) {
            \Session::flash('message',$e->getMessage());
        } catch (\Stripe\Error\Permission $e) {
            \Session::flash('message',$e->getMessage());
        }

        
        return redirect()->action('BillingController@paymentMethods');
    }
      /**
     * Show invoices dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function showInvoicing()
    {
        $user_id = \Auth::user()->id;
        $collection = ManageShowsRegister::with("show")
            ->where('user_id',$user_id)
            ->where("status",1)
            ->where("type",1)
            ->where("manage_show_id","!=",NULL)
            ->orderBy('id','Desc')->groupBy("manage_show_id")->get();
        return view('billing.index')->with(compact('collection'));

    }
      /**
     * Show invoices detail.
     *
     * @return \Illuminate\Http\Response
     */


    /**
     * Show billing.
     *
     * @return \Illuminate\Http\Response
     */

    public function sponsorStripeCheckout(Request $request)
    {
        try {
        $user_id = \Auth::user()->id;

        $categoryIdArr = $request->get("category_id");

        $amountToPay = $request->get("amount");

        $appOwnerAmount = 0;

        $arrDestination = [];
        $arrMetaDeta = [];

        $showOwnerId = ManageShows::where('id', $request->show_id)->pluck('user_id')->first();
        $appownerEmail = User::where('id', $showOwnerId)->pluck('email')->first();
        $template_id = ManageShows::where('id', $request->show_id)->pluck('template_id')->first();
        $invitedUser = InvitedUser::where('email', '=', $appownerEmail)->where('template_id', $template_id)->orderBy('id', 'desc')->first();

        $amount = $amountToPay;

        $royality = $invitedUser->royalty;

        $appOwnerAmount = $request->royaltyFinal;

        $participantAmount = $amount - $appOwnerAmount;


        $model = ParticipantAccountInformation::where('participant_id', $showOwnerId)->first();


        if ($model) {

            if ($participantAmount > 0) {
                $arrDestination = [
                    "amount" => round($participantAmount),
                    "account" => $model->stripe_account_id,
                ];

                $arrMetaDeta = [
                    "user_account_id" => $model->stripe_account_id,
                    "royality" => $appOwnerAmount,
                    "sender_id" => $user_id,
                    "receiver_id" => $model->participant_id,
                    "receiverAmount" => $participantAmount,
                    "showId" => $request->show_id,
                    "type" => 'Credit Card'
                ];
            }

        } else {
            setAlert("warning", getUserNamefromid($showOwnerId), " has not yet configured the stripe account");
        }

        Stripe::setApiKey(config('services.stripe.secret'));

        $customer = Customer::create([
            'email' => request('stripeEmail'),
            'source' => request('stripeToken')
        ]);


        if (isset($arrDestination) && count($arrDestination) > 0) {
            $charge = Charge::create([
                'customer' => $customer->id,
                'amount' => "$amountToPay",
                'currency' => 'usd',
                "destination" => $arrDestination,
                "metadata" => $arrMetaDeta
            ]);

            if ($charge) {
                $billing = new SponsorCategoryBilling();
                $billing->show_id = $request->show_id;
                $billing->sender_id = $user_id;
                $billing->show_owner_id = $showOwnerId;
//                $billing->category_id = $categoryIdArr;
                $billing->stripe_receiver_account_id = $model->stripe_account_id;
                $billing->charge_id = $charge->id;
                $billing->amount_transfer = $amountToPay / 100;
                $billing->royalty_charges = $appOwnerAmount / 100;
                $billing->billing_method_type = "Credit Card";
                $billing->payment_status = 1;
                $billing->sponsor_form_id = $request->sponsor_form_id;

                $billing->save();

                $categoryIdArr = explode(',', $categoryIdArr);
                $billing->hasCategory()->attach($categoryIdArr);

            }

            \Session::flash('message', 'Invoice has been paid successfully');

//            \Session::flash('message', 'Invoice has been paid successfully');
//            return Redirect::to(\Session('urlInvoice'));
        }

    }


        catch (\Stripe\Error\ApiConnection $e) {
            \Session::flash('message',$e->getMessage());
        } catch (\Stripe\Error\InvalidRequest $e) {
            \Session::flash('message',$e->getMessage());
        } catch (\Stripe\Error\Api $e) {
            \Session::flash('message',$e->getMessage());
        } catch (\Stripe\Error\Card $e) {
            \Session::flash('message',$e->getMessage());
        }


        return Redirect::to('shows/dashboard');

        //return Redirect::to(\Session('urlInvoice'));


    }


    public function sponsorPaypalcheckout(Request $request)
    {
       // dd($request->all());

        $user_id = \Auth::user()->id;

        $categoryIdArr = $request->get("category_id");

        $amountToPay =  $request->get("amount");

        $appOwnerAmount = 0;

        $arrDestination = [];
        $arrMetaDeta = [];

        $showOwnerId= ManageShows::where('id',$request->show_id)->pluck('user_id')->first();
        $appownerEmail= User::where('id',$showOwnerId)->pluck('email')->first();
        $template_id = ManageShows::where('id',$request->show_id)->pluck('template_id')->first();
        $invitedUser = InvitedUser::where('email','=',$appownerEmail)->where('template_id',$template_id)->orderBy('id','desc')->first();

        $amount = $amountToPay;

        $royality = $invitedUser->royalty;
        $appOwnerAmount = $request->royaltyFinal;
        //$appOwnerAmount = number_format(($amount / 100)  * $royality,2);

         $participantAmount = $amount - $appOwnerAmount;

        $provider = PayPal::setProvider('adaptive_payments');

        $billing =  new SponsorCategoryBilling();
        $billing->show_id = $request->show_id;
        $billing->sender_id = $user_id;
        $billing->show_owner_id = $showOwnerId;
        $billing->category_id = $categoryIdArr;
        $billing->amount_transfer = $amountToPay;
        $billing->royalty_charges = $appOwnerAmount;
        $billing->billing_method_type = "Paypal";
        $billing->sponsor_form_id = $request->sponsor_form_id;

        $billing->save();

        $categoryIdArr = explode(',',$categoryIdArr);

        $billing->hasCategory()->attach($categoryIdArr);

        if($appOwnerAmount!=0) {

            $data = [
                'receivers' => [
                    [
                        'email' => 'shakilahmedshaki@gmail.com',
                        'amount' => round($appOwnerAmount),
                    ],
                    [
                        'email' => 'shakilmultan@gmail.com',
                        'amount' => round($participantAmount),
                    ]
                ],
                'payer' => 'EACHRECEIVER', // (Optional) Describes who pays PayPal fees. Allowed values are: 'SENDER', 'PRIMARYRECEIVER', 'EACHRECEIVER' (Default), 'SECONDARYONLY'

                'return_url' => url('shows/submitPaypal/' . nxb_encode($billing->id) . '?action=success'),
                'cancel_url' => url('shows/submitPaypal/' . nxb_encode($billing->id) . '?action=cancel'),
            ];
        }else
        {
            $data = [
                'receivers' => [
                    [
                        'email' => 'shakilmultan@gmail.com',
                        'amount' => round($participantAmount),

                    ]
                ],
                'payer' => 'EACHRECEIVER', // (Optional) Describes who pays PayPal fees. Allowed values are: 'SENDER', 'PRIMARYRECEIVER', 'EACHRECEIVER' (Default), 'SECONDARYONLY'

                'return_url' => url('shows/submitPaypal/' . nxb_encode($billing->id) . '?action=success'),
                'cancel_url' => url('shows/submitPaypal/' . nxb_encode($billing->id) . '?action=cancel'),
            ];
        }

        $response = $provider->createPayRequest($data);

        if(isset($response['payKey'])) {
            $redirect_url = $provider->getRedirectUrl('approved', $response['payKey']);

            $m = SponsorCategoryBilling::findorFail($billing->id);
            $m->pay_id = $response['payKey'];
            $m->update();
        }

        return redirect($redirect_url);

    }


    public function getPaypalDetails($id,Request $request)
    {

        $id = nxb_decode($id);
        if($request['action']=='success')
        {

            $model = SponsorCategoryBilling::findorFail($id);
            $model->payment_status =1;
            $model->update();

            \Session::flash('message', 'Invoice has been paid successfully');

            return redirect()->route('ShowController-index');
        }
        else
        {
            \Session::flash('message', 'Invoice has been Cancelled');
          $model = SponsorCategoryBilling::findorFail($id);
          $model->delete();
            return redirect()->route('ShowController-index');

        }


    }



}
