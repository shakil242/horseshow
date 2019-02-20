<?php
    
    namespace App\Http\Controllers;
    use angelleye\PayPal\Adaptive;
    use App\AppownerBankAccountInformation;
    use App\Billing;
    use App\ClassHorse;
    use App\HorseInvoices;
    use App\InvitedUser;
    use App\Invoice;
    use App\Mail\PaypalAccountCreationEmail;
    use App\ManageShows;
    use App\ParticipantAccountInformation;
    use App\PaypalAccountDetail;
    use App\paypalDetail;
    use App\Template;
    use App\User;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Redirect;
    use Srmklive\PayPal\Facades\PayPal;
    use Srmklive\PayPal\Services\ExpressCheckout;
    use Paypalpayment;
// Through facade. No need to import namespaces
     class PaypalController extends Controller
    {
        
        private $_api_context;
        
        public function __construct()
        {
        $provider = PayPal::setProvider('express_checkout');      // To use express checkout(used by default).
        $provider = PayPal::setProvider('adaptive_payments');     // To use adaptive payments.
        
        $this->_apiContext = Paypalpayment::apiContext(config('services.paypal.client_id'), config('services.paypal.secret'));
    
        }
        
        
        public function testPaypalAccountCreate()
        {
    
            $PayPalConfig = array(
                'Sandbox' => 'sandbox',
                'DeveloperAccountEmail' => config('services.paypal.developerAccountEmail'),
                'ApplicationID' => config('services.paypal.ApplicationID'),
                'APIUsername' => config('services.paypal.APIUsername'),
                'APIPassword' =>config('services.paypal.APIPassword'),
                'APISignature' =>config('services.paypal.APISignature'),
            );
            
            $CreateAccountFields = array(
                'AccountType' => 'Premier',  										// Required.  The type of account to be created.  Personal or Premier
                'CitizenshipCountryCode' => 'US',  							// Required.  The code of the country to be associated with the business account.  This field does not apply to personal or premier accounts.
                'ContactPhoneNumber' => '555-555-5555', 								// Required.  The phone number associated with the new account.
                'HomePhoneNumber' => '555-555-5555', 									// Home phone number associated with the account.
                'MobilePhoneNumber' => '555-555-4444', 								// Mobile phone number associated with the account.
                'ReturnURL' => 'http://equetica.vteamslabs.com/paymentMethods', 										// Required.  URL to redirect the user to after leaving PayPal pages.
                'ShowAddCreditCard' => 'true', 								// Whether or not to show the Add Credit Card option.  Values:  true/false
                'ShowMobileConfirm' => '', 								// Whether or not to show the mobile confirmation option.  Values:  true/false
                'ReturnURLDescription' => 'Home Page', 								// A description of the Return URL.
                'UseMiniBrowser' => 'false', 									// Whether or not to use the minibrowser flow.  Values:  true/false  Note:  If you specify true here, do not specify values for ReturnURL or ReturnURLDescription
                'CurrencyCode' => 'USD', 										// Required.  Currency code associated with the new account.
                'DateOfBirth' => '1982-04-09Z', 										// Date of birth of the account holder.  YYYY-MM-DDZ format.  For example, 1970-01-01Z
                'EmailAddress' => 'sandbox2@angelleye.com', 										// Required.  Email address.
                'Salutation' => '', 										// A salutation for the account holder.
                'FirstName' => 'Tester', 										// Required.  First name of the account holder.
                'MiddleName' => '', 										// Middle name of the account holder.
                'LastName' => 'Testerson', 											// Required.  Last name of the account holder.
                'Suffix' => '',  											// Suffix name for the account holder.
                'NotificationURL' => 'http://equetica.vteamslabs.com/paymentMethods', 									// URL for IPN
                'PreferredLanguageCode' => '', 							// Required.  The code indicating the language to be associated with the new account.
                'RegistrationType' => 'Web', 									// Required.  Whether the PayPal user will use a mobile device or the web to complete registration.  This determins whether a key or a URL is returned for the redirect URL.  Allowable values are:  Web
                'SuppressWelcomeEmail' => '', 								// Whether or not to suppress the PayPal welcome email.  Values:  true/false
                'PerformExtraVettingOnThisAccount' => '', 					// Whether to subject the account to extra vetting by PayPal before the account can be used.  Values:  true/false
                'TaxID' => ''												// Tax ID equivalent to US SSN number.   Note:  Currently only supported in Brazil, which uses tax ID numbers such as CPF and CNPJ.
            );
    
            $Address = array(
                'Line1' => '1503 Main St.', 													// Required.  Street address.
                'Line2' => '376', 													// Street address 2.
                'City' => 'Kansas City', 													// Required.  City
                'State' => 'MO', 													// State or Province
                'PostalCode' => '64111', 												// Postal code
                'CountryCode' => 'US'												// Required.  The country code.
            );
    
            $BusinessAddress = array(
                'Line1' => '123 Test Ave.', 													// Required.  Street address.
                'Line2' => '', 													// Street address 2.
                'City' => 'Grandview', 													// Required.  City
                'State' => 'MO', 													// State or Province
                'PostalCode' => '64030', 												// Postal code
                'CountryCode' => 'US'												// Required.  The country code.
            );
    
            $PrinciplePlaceOfBusinessAddress = array(
                'Line1' => '1503 Main St.', 													// Required.  Street address.
                'Line2' => '376', 													// Street address 2.
                'City' => 'Kansas City', 													// Required.  City
                'State' => 'MO', 													// State or Province
                'PostalCode' => '64111', 												// Postal code
                'CountryCode' => 'US'												// Required.  The country code.
            );
    
            $BusinessStakeHolder = array(
                'DateOfBirth' => '1982-04-09Z', 										// The date of birth of the stakeholder in the business.  Format:  YYYY-MM-DDZ  (ie. 1970-01-01Z)
                'FullLegalName' => 'Tester Testerson', 										// The legal name of the stakeholder in the business for which the account is being created.
                'Salutation' => '', 											// A salutation for the account holder.
                'FirstName' => 'Tester', 											// Required.  First name of the account holder.
                'MiddleName' => '', 										// Middle name of the account holder.
                'LastName' => 'Testerson', 											// Required.  Last name of the account holder.
                'Suffix' => '',  											// Suffix name for the account holder.
                'Role' => 'CHAIRMAN', 												// The role of the stakeholder in the business.  Values are:  CHAIRMAN, SECRETARY, TREASURER, BENEFICIAL_OWNER, PRIMARY_CONTACT, INDIVIDUAL_PARTNER, NON_INDIVIDUAL_PARTNER, PRIMARY_INDIVIDUAL_PARTNER, DIRECTOR, NO_BENEFICIAL_OWNER
                'CountryCode' => 'US'											// The country code of the stakeholder's address.
            );
    
            $BusinessStakeHolderAddress = array(
                'Line1' => '1503 Main St.', 													// Required.  Street address.
                'Line2' => '376', 													// Street address 2.
                'City' => 'Kansas City', 													// Required.  City
                'State' => 'MO', 													// State or Province
                'PostalCode' => '64111', 												// Postal code
                'CountryCode' => 'US'												// Required.  The country code.
            );
    
            $PayPalRequestData = array(
                'CreateAccountFields' => $CreateAccountFields,
                'Address' => $Address,
                'BusinessAddress' => $BusinessAddress,
                'PrinciplePlaceOfBusinessAddress' => $PrinciplePlaceOfBusinessAddress,
                'BusinessStakeHolder' => $BusinessStakeHolder,
                'BusinessStakeHolderAddress' => $BusinessStakeHolderAddress
            );
    
    
            $payPal =  new Adaptive($PayPalConfig);
    
            
// Pass data into class for processing with PayPal and load the response array into $PayPalResult
            $PayPalResult = $payPal->CreateAccount($PayPalRequestData);

// Write the contents of the response array to the screen for demo purposes.
            echo '<pre />';
            print_r($PayPalResult);
            
            
        }
        // show paypal form view

         public function getPaypalRequest($id,Request $request)
         {

             $user_id = \Auth::user()->id;
             $id = nxb_decode($id);
             $invoice = HorseInvoices::where('id',$id)->first();
             $totalRoyalty = 0;

             if($request['action']=='success') {

                 $provider = PayPal::setProvider('adaptive_payments');

                 $paypalDetail = paypalDetail::where('invoice_id', $id)->orderBy('id')->limit(1)->first();

                 $invoicesss = paypalDetail::where('pay_id','=', $paypalDetail->pay_id)->pluck('invoice_id');

                 $paypalSelectedHorses = \Session('paypalSelectedHorses');
                 // dd($paypalSelectedHorses);

                 $invoiceHorses = HorseInvoices::whereIn('id', $paypalSelectedHorses)->get();

                 foreach($invoiceHorses as $row)
                 {
                     $totalRoyalty = $row->royalty + $totalRoyalty;
                 }

                 //dd($invoiceHorses->toArray());

                 $detail = $provider->getPaymentDetails($paypalDetail->pay_id);


                 $appOwnerRes['amount'] = 0;
                 if (count($detail['paymentInfoList']) > 1) {
                     $appOwnerRes = $detail['paymentInfoList']['paymentInfo'][0]['receiver'];
                     $participantRes = $detail['paymentInfoList']['paymentInfo'][1]['receiver'];
                 } else {
                     $participantRes = $detail['paymentInfoList']['paymentInfo'][0]['receiver'];
                 }
//
//                foreach ($invoicesss as $inv) {
//
//                    $invo = HorseInvoices::where('id', $inv)->first();
//                    $invo->invoice_status = 1;
//                    $invo->update();
//                    updatePaidInvoices($invo->horse_id,$invoice->show_id);
//
//                }
//                $paypalSelectedHorses = \Session('paypalSelectedHorses');

                 foreach ($invoiceHorses as $inv) {


                     $amount = $inv->total_bill_price * 100;

                     $paypalCharges = (config('services.paypal.paypalFee') + config('services.paypal.paypalFeeCent') / 100) / 100 * $amount;
                     $OwnerPaypalCharges = (config('services.paypal.paypalFee') + config('services.paypal.paypalFeeCent') / 100) / 100 * $totalRoyalty;


                     $totalAmountToPay=$paypalCharges+$amount;

                     $appOwnerAmount = $totalRoyalty + $OwnerPaypalCharges;

                     $ShowAmount = $totalAmountToPay - $appOwnerAmount;


                     $billing = new Billing();

                     $billing->invoice_id =$inv->id;

                     $billing->sender_id = $inv->payer_id;

                     $billing->paypal_pay_id = $paypalDetail->pay_id;

                     $billing->amount_transfer = $ShowAmount;

                     $billing->amount_sent = $totalAmountToPay;

                     $billing->participant_id = $inv->payment_receiver_id;

                     $billing->application_fee = $appOwnerAmount;

                     $billing->type = 'Paypal';

                     $billing->save();


                     $horse_id = $inv->horse_id;

                     updatePaidInvoices($horse_id,$inv->show_id);

                     $HorseInvoices = HorseInvoices::findOrFail($inv->id);
                     $HorseInvoices->invoice_status = 1;
                     $HorseInvoices->update();


                 }
                 //  $paypalDetail =  paypalDetail::where('invoice_id',$id)->delete();
                 setAlert("success", '', " Invoice has been paid successfully");

                 // \Session::flash('message', 'Invoice has been paid successfully');

                 // echo  $invoice->horse_id;exit;



//                $model = ClassHorse::where('show_id', $invoice->show_id)
//                    ->where('horse_id', $invoice->horse_id)
//                    ->first();
//
//                $model->status = 1;
//                $model->update();
             }

             return Redirect::to('shows/'.nxb_encode($invoice->show_id).'/horse/invoices');

         }


         public function store($id)
        {
    
            $user_id = \Auth::user()->id;

            $provider =   PayPal::setProvider('adaptive_payments');
    
            $invoiceId = nxb_decode($id);

            $invoice = Invoice::where('id',$invoiceId)->first();

            $template = Template::where('id',$invoice->template_id)->first();

            $paypalCharges = number_format( (config('services.paypal.paypalFee') +config('services.paypal.paypalFeeCent')/100)/100*$invoice->amount,2);

            $amount = $invoice->amount + $paypalCharges;

            $royality = $template->royalty;
    
            $appOwnerAmount = ($amount / 100)  * $royality;

            $participantAmount = $amount - $appOwnerAmount;

            $model = ParticipantAccountInformation::where('participant_id',$invoice->user_id)->first();
            $PaypalAccountDetail = PaypalAccountDetail::where('userId',$invoice->invitee_id)->first();
            
            if(count($PaypalAccountDetail)==0)
            {
                setAlert("warning",getUserNamefromid($invoice->invitee_id)," has not given paypal details");
            }
            $data = [
                'receivers'  => [
                    [
                        'email' => 'shakilahmedshaki@gmail.com',
                        'amount' => round($appOwnerAmount),
                    ],
                    [
                        'email' =>$PaypalAccountDetail->paypalEmail ,
                        'amount' => round($participantAmount),
                    ]
                ],
                'payer' => 'EACHRECEIVER', // (Optional) Describes who pays PayPal fees. Allowed values are: 'SENDER', 'PRIMARYRECEIVER', 'EACHRECEIVER' (Default), 'SECONDARYONLY'
                'return_url' => url('master-template/'.$id.'/payment/add-funds/paypal?action=success'),
                'cancel_url' => url('master-template/'.$id.'/payment/add-funds/paypal?action=cancel'),
            ];


            $response = $provider->createPayRequest($data);

            $redirect_url = $provider->getRedirectUrl('approved', $response['payKey']);

            $model =  new paypalDetail();
    
            $model->invoice_id=nxb_decode($id);
            $model->pay_id=$response['payKey'];
            $model->save();

            setAlert("success",''," Invoice has been paid successfully");


            return redirect($redirect_url);

              }
    
         /**
          * @return mixed
          */
         public function createPaypalAccount(Request $request)
         {
             
                 $user_id = \Auth::user()->id;
                 $email = \Auth::user()->email;
             
                 $dateOfBirth =  date('Y-m-d',strtotime($request->get('dateOfBirth'))).'Z';
             
             
            // echo '>>>'.config('services.paypal.developerAccountEmail');
             
            // exit;
             
             
                 $PayPalConfig = array(
                 'Sandbox' => 'sandbox',
                 'DeveloperAccountEmail' => config('services.paypal.developerAccountEmail'),
                 'ApplicationID' => config('services.paypal.ApplicationID'),
                 'APIUsername' => config('services.paypal.APIUsername'),
                 'APIPassword' =>config('services.paypal.APIPassword'),
                 'APISignature' =>config('services.paypal.APISignature'),
             );
 
                 $CreateAccountFields = array(
                 'AccountType' => $request->get('accountType'), // Required. The type of account to be created. Personal or Premier
                 'CitizenshipCountryCode' => $request->get('citizenshipCountryCode'), // Required. The code of the country to be associated with the business account. This field does not apply to personal or premier accounts.
                'ContactPhoneNumber' => $request->get('contactPhoneNumber'), // Required. The phone number associated with the new account.
                'ReturnURL' => 'http://equetica.vteamslabs.com/paymentMethods', // Required. URL to redirect the user to after leaving PayPal pages.
                 'ShowAddCreditCard' => 'true', // Whether or not to show the Add Credit Card option. Values: true/false
                 'ShowMobileConfirm' => '', // Whether or not to show the mobile confirmation option. Values: true/false
                 'ReturnURLDescription' => 'Home Page', // A description of the Return URL.
                 'UseMiniBrowser' => 'false', // Whether or not to use the minibrowser flow. Values: true/false Note: If you specify true here, do not specify values for ReturnURL or ReturnURLDescription
                 'CurrencyCode' => $request->get('currencyCode'), // Required. Currency code associated with the new account.
                 'DateOfBirth' =>$dateOfBirth, // Date of birth of the account holder. YYYY-MM-DDZ format. For example, 1970-01-01Z
                 'EmailAddress' => $request->get('emailAddress'), // Required. Email address.
                 'Saluation' => '', // A saluation for the account holder.
                 'FirstName' => $request->get('FirstName'), // Required. First name of the account holder.
                 'MiddleName' => '', // Middle name of the account holder.
                 'LastName' => $request->get('LastName'), // Required. Last name of the account holder.
                 'Suffix' => '', // Suffix name for the account holder.
                'NotificationURL' => 'http://equetica.vteamslabs.com/paymentMethods', // URL for IPN
                 'PreferredLanguageCode' => '', // Required. The code indicating the language to be associated with the new account.
                 'RegistrationType' => 'Web', // Required. Whether the PayPal user will use a mobile device or the web to complete registration. This determins whether a key or a URL is returned for the redirect URL. Allowable values are: Web
                 'SuppressWelcomeEmail' => '', // Whether or not to suppress the PayPal welcome email. Values: true/false
                 'PerformExtraVettingOnThisAccount' => '', // Whether to subject the account to extra vetting by PayPal before the account can be used. Values: true/false
                 'TaxID' => ''    // Tax ID equivalent to US SSN number. Note: Currently only supported in Brazil, which uses tax ID numbers such as CPF and CNPJ.
             );
    
             $Address = array(
                 'Line1' => $request->get('address-line-1'), // Required. Street address.
                 'Line2' => $request->get('address-line-2'), // Street address 2.
                 'City' => $request->get('city'), // Required. City
                 'State' => $request->get('state'), // State or Province
                 'PostalCode' => $request->get('zip'), // Postal code
                 'CountryCode' => $request->get('citizenshipCountryCode')    // Required. The country code.
             );

             $PayPalRequestData = array(
                 'CreateAccountFields' => $CreateAccountFields,
                 'Address' => $Address
//                 'BusinessAddress' => $BusinessAddress,
//                 'PrinciplePlaceOfBusinessAddress' => $PrinciplePlaceOfBusinessAddress,
//                 'BusinessStakeHolder' => $BusinessStakeHolder,
//                 'BusinessStakeHolderAddress' => $BusinessStakeHolderAddress
             );
    
             $payPal =  new Adaptive($PayPalConfig);
    
             $PayPalResult = $payPal->CreateAccount($PayPalRequestData);
    
              if($PayPalResult['Ack']=='Success')
                 {
                    $model = new PaypalAccountDetail();
                    
                    $model->userId = $user_id;
                    $model->paypalEmail = $request->get('emailAddress');
                    $model->accountID = $PayPalResult['AccountID'];
                    $model->createAccountKey = $PayPalResult['CreateAccountKey'];
                    $model->correlationID = $PayPalResult['CorrelationID'];
                    $model->redirectURL = $PayPalResult['RedirectURL'];
                    
                    $model->save();
                     \Session::flash('message','Paypal account has been created successfully, please check email and activate The Paypal account');
    
                     // \Mail::to($request->get('emailAddress'))->send(new PaypalAccountCreationEmail($model));
                 }

              else
              {
  
                      \Session::flash('message', $PayPalResult['Errors']['0']['Message']);
                  
              }
                 
                 
                return redirect()->back();
                 
         }
    
    
         /**
          * @return mixed
          */
         public function EmailVerificationPaypal(Request $request)
         {
             $user_id = \Auth::user()->id;
             
             $PayPalConfig = array(
                 'Sandbox' => 'sandbox',
                 'DeveloperAccountEmail' => config('services.paypal.DeveloperAccountEmail'),
                 'ApplicationID' => config('services.paypal.ApplicationID'),
                 'APIUsername' => config('services.paypal.APIUsername'),
                 'APIPassword' =>config('services.paypal.APIPassword'),
                 'APISignature' =>config('services.paypal.APISignature'),
                 );
             
    
             $GetVerifiedStatusFields = array(
                 'EmailAddress' => $request->get('emailAddress'), 					// Required.  The email address of the PayPal account holder.
                 'FirstName' => $request->get('FirstName'), 						// The first name of the PayPal account holder.  Required if MatchCriteria is NAME
                 'LastName' => $request->get('LastName'), 						// The last name of the PayPal account holder.  Required if MatchCriteria is NAME
                 'MatchCriteria' => 'NAME'					// Required.  The criteria must be matched in addition to EmailAddress.  Currently, only NAME is supported.  Values:  NAME, NONE   To use NONE you have to be granted advanced permissions
             );
    
             $PayPal =  new Adaptive($PayPalConfig);
    
    
             $PayPalRequestData = array('GetVerifiedStatusFields' => $GetVerifiedStatusFields);

// Pass data into class for processing with PayPal and load the response array into $PayPalResult
             $PayPalResult = $PayPal->GetVerifiedStatus($PayPalRequestData);

// Write the contents of the response array to the screen for demo purposes.
             if($PayPalResult['Ack']=='Success')
             {
                 $model = new PaypalAccountDetail();
        
                 $model->userId = $user_id;
                 $model->paypalEmail = $request->get('emailAddress');
                 $model->accountID = $PayPalResult['AccountID'];
                 $model->correlationID = $PayPalResult['CorrelationID'];
        
                 $model->save();
        
             }else
             {
               
                 \Session::flash('message',$PayPalResult['Errors']['0']['Message']);
    
             }
    
             return redirect()->back();
         }
    
    
         /**
          * @return string
          */


         public function multipleInvoiceRequest($id)
         {

             $user_id = \Auth::user()->id;

             $provider =   PayPal::setProvider('adaptive_payments');

             $invoiceId = nxb_decode($id);

            $invoiceIdArr=explode(',',$invoiceId);

             $invoice = Invoice::select(DB::raw("SUM(amount) as amount,user_id,invitee_id,template_id"))->whereIn('id',$invoiceIdArr)
                 ->groupBy("invitee_id")
                 ->get();

             $template = Template::where('id',$invoice[0]->template_id)->first();

             $appOwnerAmount = 0;

             $arr = [];

             foreach ($invoice as $inv) {

                 $amount = $inv->amount;

                 $royality = $template->royalty;

                 $appOwnerSingle = ($royality / 100) * $amount;

                 $appOwnerAmount = $appOwnerAmount + ($royality / 100) * $amount;

                 $participantAmount = $amount - $appOwnerSingle;

                 $model = ParticipantAccountInformation::where('participant_id', $inv->user_id)->first();
                 $PaypalAccountDetail = PaypalAccountDetail::where('userId', $inv->invitee_id)->first();

                 if (count($PaypalAccountDetail) == 0) {

                     setAlert("warning",getUserNamefromid($inv->invitee_id)," has not given paypal details");
                 }
                else {
                    $arr[] = ['email' => $PaypalAccountDetail->paypalEmail, 'amount' => $participantAmount];
                }
             }

             $arr[]=[
                     'email' => 'shakilahmedshaki@gmail.com',
                     'amount' => round($appOwnerAmount),
                 ];


             $data = ['receivers'  =>$arr
                 ,
                 'payer' => 'EACHRECEIVER', // (Optional) Describes who pays PayPal fees. Allowed values are: 'SENDER', 'PRIMARYRECEIVER', 'EACHRECEIVER' (Default), 'SECONDARYONLY'
                 'return_url' => url('master-template/'.$id.'/payment/add-funds/paypal?action=success'),
                 'cancel_url' => url('master-template/'.$id.'/payment/add-funds/paypal?action=cancel'),
             ];


             $response = $provider->createPayRequest($data);

             $redirect_url = $provider->getRedirectUrl('approved', $response['payKey']);

             $model =  new paypalDetail();

             $model->invoice_id=nxb_decode($id);
             $model->pay_id=$response['payKey'];
             $model->save();

             setAlert("success",''," Invoice has been paid successfully");


             return redirect($redirect_url);

         }




     }