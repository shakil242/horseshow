<?php

namespace App\Http\Controllers\admin;

use App\Billing;
use App\Invoice;
use App\paypalDetail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Stripe\Stripe;
use Srmklive\PayPal\Facades\PayPal;
use Srmklive\PayPal\Services\ExpressCheckout;
class BillingController extends Controller
{
    /**
     * @return string
     */
    public function show()
    {
    
        Stripe::setApiKey(config('services.stripe.secret'));
    
        $user_id = \Auth::user()->id;
        $billingDetail = [];
    
        $transaction = \Stripe\Charge::all(array("limit" => '2000'));
    
    
        //$transaction = \Stripe\BalanceTransaction::all(array("limit" => '2000'));
    
//        echo '<pre>';
//        print_r($transaction);
//        echo '</pre>';
//        exit;
//        echo '<pre>';
//        print_r($transaction);
//        echo '</pre>';
        
//        foreach ($transaction->data as $row) {
//            echo '<pre>';
//            print_r($transaction);
//            echo '</pre>';
//        }
//        /exit;
        $provider =   PayPal::setProvider('adaptive_payments');

        $billingDetails = Billing::where('application_fee','!=',0)->orderBy('id')->get();
    
       $paypalDetail = paypalDetail::groupBy('invoice_id')->orderBy('id')->get();

        $payinoffice = Billing::with('horseinvoice')->where('type',"pay in office")->orderBy('id')->get();

        $payPalRes=[];
        
//        foreach ($paypalDetail as $paypal) {
//            $detail = $provider->getPaymentDetails($paypal->pay_id);
//
//            if ($detail) {
//                $invoice = Invoice::where('id', $paypal->invoice_id)->first();
//                $appOwnerRes = $detail['paymentInfoList']['paymentInfo'][0]['receiver'];
//                $participantRes = $detail['paymentInfoList']['paymentInfo'][1]['receiver'];
//                $totalAmount = $appOwnerRes['amount'] + $participantRes['amount'];
//                if ($invoice) {
//                    $payPalRes[] = [
//                        'participantEmail' => $participantRes['email'],
//                        'participantAmount' => $participantRes['amount'],
//                        'transferedDate' => $detail['responseEnvelope']['timestamp'],
//                        'senderEmail' => $invoice->invoice_email,
//                        'amount' => $totalAmount,
//                        'royalty' => $appOwnerRes['amount']
//                    ];
//                }
//            }
//        }
//        echo '<pre>';
//        print_r($payPalRes);
//        echo '</pre>';
//        exit;
        return view('admin.invoice.billing.index')->with(compact('billingDetails','transaction','payPalRes','payinoffice'));
    
    
    }
}
