<?php

namespace App\Http\Controllers;

use App\AdditionalCharges;
use App\HorseInvoices;
use App\Invoice;
use App\ParticipantAccountInformation;
use App\PaypalAccountDetail;
use App\paypalDetail;
use App\PrizeClaimForm;
use App\User;
use App\ManageShowsRegister;
use App\ManageShows;
use App\ClassHorse;
use App\ShowPrizingListing;
use App\InvitedUser;
use Illuminate\Http\Request;
use Srmklive\PayPal\Facades\PayPal;
use App\ManageShowTrainerSplit;
use Carbon\Carbon;
use App\HorseInvoiceComment;
use App\Division;


class HorseBillingController extends Controller
{
    
      /**
     * Show invoices dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function showInvoicing()
    {
        $user_id = \Auth::user()->id;
        $types = [1,2];
        $collection = ManageShowsRegister::with("show")
            ->where('user_id',$user_id)
            ->where("status",1)
            ->whereIn("type",$types)
            ->where("manage_show_id","!=",NULL)
            ->orderBy('id','Desc')->groupBy("manage_show_id")->get();
        return view('shows.billing.index')->with(compact('collection','user_id'));

    }
      /**
     * Show invoices detail.
     *
     * @return \Illuminate\Http\Response
     */
    public function invoicingDetail($show_id)
    {

        $show_id = nxb_decode($show_id);
        $user_id = \Auth::user()->id;
        $collection = ClassHorse::select("id","horse_id","horse_quantity","horse_reg","user_id",'paid_on','invoice_no','show_id')->with("horse")
                ->where("show_id",$show_id)
                ->where("user_id",$user_id)
                ->where("status",0)
                ->groupBy("horse_id")
                ->orderBy("id",'desc')
                ->get();

        $prize = ShowPrizingListing::with("shows")->where("show_id",$show_id)->get();

        $MS = ManageShows::with('template')->find($show_id);

        $appOwner = User::find($MS->user_id);

        $sponsers = getSponsorsCollection($show_id);
        if($appOwner) {

            $inviteUser = InvitedUser::where('email', '=', $appOwner->email)->where('template_id', $MS->template_id)->first();
            $royalty = $inviteUser->royalty;
        }
        $paidCollection = ClassHorse::select("id","horse_id","horse_quantity","horse_reg","user_id",'paid_on','invoice_no','show_id')->with("horse")
                ->where("show_id",$show_id)
                ->where("user_id",$user_id)
                ->where("status",1)
                ->groupBy("horse_id","paid_on")
                ->orderBy("id",'desc')
                ->get();
        $m_s_fields = getButtonLabelFromTemplateId($MS->template_id,'m_s_fields');
        $show_type = ManageShows::where('id',$show_id)->pluck('show_type')->first();
        return view('shows.billing.horseDetail')->with(compact('collection','m_s_fields','show_type',"MS",'sponsers',"user_id","show_id","prize","royalty","paidCollection"));

    }

    /**
     * Show billing.
     *
     * @return \Illuminate\Http\Response
     */
    public function billingHorse(Request $request)
    {

        $updates = $request->all();
        $user_id = \Auth::user()->id;

        $show_id =  $request->show_id;
        $horse_id =  $request->horse_id;

        $show_owner_id = ManageShows::where('id',$show_id)->pluck('user_id')->first();

        foreach ($updates['Invoices'] as $k=>$v)
        {

            $result = HorseInvoices::where('show_id',$request->show_id)->where('horse_id',$v['horse_id'])->where('invoice_status',0)->first();

            if(isset($result) && $result->id!='')
            {
               $model = $result;
            }
            else {
               $model = new HorseInvoices();
            }

            $model->show_id = $request->show_id;
            $model->horse_id = $v['horse_id'];
            $model->payer_id = $user_id;
            $model->payment_receiver_id = $show_owner_id;
            $model->class_price = $v['assets_price'];
            if(isset($v['additional_price']))
            $model->additional_price = $v['additional_price'];
            $model->royalty = $v['royalty'];
            if(isset($v['prize_won']))
            $model->prize_won = $v['prize_won'];
            if(isset($v['split_charges']))
            $model->split_charges = $v['split_charges'];
            $model->horse_total_price = $v['total_price'];
            $model->total_bill_price = $v['total_price'];
            if(isset($v['division_price']))
            $model->division_price = $v['division_price'];
            if(isset($v['stall_price']))
            $model->stall_price = $v['stall_price'];
            if(isset($v['total_taxis']))
            $model->total_taxis = $v['total_taxis'];


            $model->save();

        }


       $horseInvoices = HorseInvoices::where('show_id',$show_id)->where('invoice_status',0)->whereIn('horse_id',$horse_id)->get();

       // dd($horseInvoices->toArray());

       $paypalAccountDetail = PaypalAccountDetail::where('userId',$show_owner_id)->count();


       $stripeDetails = ParticipantAccountInformation::where('participant_id',$show_owner_id)->count();

        return view('invoice.billing.invoiceDetail')->with(compact('horseInvoices','show_id','paypalAccountDetail','stripeDetails'));

    }
 
    /**
     * Show billing.
     *
     * @return \Illuminate\Http\Response
     */
    public function appOwnerHorseListing($template_id)
    {
               $isEmail = \Session('isEmployee');
                $userEmail = \Auth::user()->email;

                $template_id = nxb_decode($template_id);

                if($isEmail==1) {
                    $user_id = getAppOwnerId($userEmail,$template_id);
                }
                else {
                    $user_id = \Auth::user()->id;
                    $employee_id = 0;
                }


                $manageShows = ManageShows::with(["participants"=>function($q){
                        $q->orderBy('id','Desc');
                        $q->groupBy('email');
                    }])->where('template_id',$template_id)
                    ->where('user_id',$user_id)
                    ->orderBy('id','Desc')->get();
                return view('shows.billing.appowner.userListing')->with(compact("template_id","manageShows"));
    }
    
     /**
     * Update Invoice.
     *
     * @return \Illuminate\Http\Response
     */
    public function updateInvoice(Request $request)
    {           
        $CH = ClassHorse::find($request->ch_id);
        //dd($CH->toArray());
        $CH->price=$request->new_val;
        if(isset($request->horse_quantity))
        $CH->horse_quantity=$request->horse_quantity;
        $CH->update();
         \Session::flash('message', 'The invoice has been updated');
        return redirect()->back();

    }
    
     /**
     * Update Invoice for additional charges.
     *
     * @return \Illuminate\Http\Response
     */
    public function updateAdditional(Request $request)
    {  
        $input = $request->all();
        $CH = ClassHorse::find($input['ch_id']);
        if ($CH) {
            $additon = json_decode($CH->additional_charges);
            foreach ($additon as $charge) {
                if(isset($charge->id) && $charge->id == $input['line_id']){
                    $charge->price = $input['price'];
                    $charge->qty = $input['qty'];
                }
            }
        }
        
         $CH->additional_charges= json_encode($additon);
         $CH->update();
         \Session::flash('message', 'The invoice has been updated');
        return redirect()->back();

    }
    /**
     * Update Invoice for Split charges.
     *
     * @return \Illuminate\Http\Response
     */
    public function updateSplit(Request $request)
    {  
        $input = $request->all();    
        $MSTS = ManageShowTrainerSplit::find($input['MSTS_id']);
        if ($MSTS) {
            $additon = json_decode($MSTS->additional_fields);
            foreach ($additon as $charge) {
                if(isset($charge->id) && $charge->id == $input['line_id']){
                    $charge->price = $input['price'];
                    $charge->qty = $input['qty'];
                }
            }
        }
        
         $MSTS->additional_fields= json_encode($additon);
         $MSTS->update();
         \Session::flash('message', 'The invoice has been updated');
        return redirect()->back();

    }
    /**
     * Update Invoice for Split charges.
     *
     * @return \Illuminate\Http\Response
     */
    public function updateDivision(Request $request)
    {  
        $CH = Division::find($request->ch_id);
        $CH->price=$request->new_val;
        $CH->update();
        \Session::flash('message', 'The invoice has been updated');
        return redirect()->back();
    }


    /**
     * Show billing for app owner.
     *
     * @return \Illuminate\Http\Response
     */
    public function appOwnerHorseInvoice($show_id,$user_id)
    {           
        $show_id = nxb_decode($show_id);
        $user_id = nxb_decode($user_id);
        $collection = ClassHorse::select("id","horse_id","horse_reg","horse_quantity","user_id",'paid_on','invoice_no','show_id')->with("horse")
                ->where("show_id",$show_id)
                ->where("user_id",$user_id)
                ->where("status",0)
                ->groupBy("horse_id")
                ->orderBy("id",'desc')
                ->get();

        $paidCollection = ClassHorse::select("id","horse_id","horse_reg","horse_quantity","user_id",'paid_on','invoice_no','show_id')->with("horse")
                ->where("show_id",$show_id)
                ->where("user_id",$user_id)
                ->where("status",1)
                ->groupBy("horse_id","paid_on")
                ->orderBy("id",'desc')
                ->get();

        $prize = ShowPrizingListing::with("shows")->where("show_id",$show_id)->get();
        $MS = ManageShows::with('template')->find($show_id);
        $appOwner = User::find($MS->user_id);
        $sponsers = getSponsorsCollection($show_id);

        $template_id =  $MS->template_id;
        if($appOwner) {

            $inviteUser = InvitedUser::where('email', '=', $appOwner->email)->where('template_id', $MS->template_id)->first();
            $royalty = $inviteUser->royalty;
        }

        $invited = InvitedUser::where('id', $inviteUser->id)->first();
        $additional_charges = AdditionalCharges::where('app_id', $inviteUser->id)->get();

        $show_type = ManageShows::where('id',$show_id)->pluck('show_type')->first();
        $m_s_fields = getButtonLabelFromTemplateId($template_id,'m_s_fields');


        return view('shows.billing.appowner.horseDetail')->with(compact('collection','m_s_fields','show_type',"sponsers","MS","show_id","prize","royalty",'template_id',"user_id",'paidCollection',"additional_charges"));

    }
    /**
     * Show billing.
     *
     * @return \Illuminate\Http\Response
     */
    public function invoiceAlreadyPaid($horse_id,$show_id)
    {           
        $horse_id = nxb_decode($horse_id);
        $show_id = nxb_decode($show_id);
        $user_id = \Auth::user()->id;

        $MS = ManageShows::find($show_id);
        $now = Carbon::now()->format('Y-m-d H:i:s');
        if($MS->user_id == $user_id){
            $CH = ClassHorse::where('horse_id',$horse_id)->where("show_id",$show_id)->where('status',0)
                    ->update(['status' => 1, 'paid_on'=>$now]);

            HorseInvoiceComment::where('horse_id',$horse_id)->where("show_id",$show_id)
                          ->where("paid_on",NULL)
                          ->update(['paid_on'=>$now]);      
            \Session::flash('message', 'Invoice has been moved to paid invoice!');
            return redirect()->back();
        }else{
            \Session::flash('message', 'You donot have permissions to perform changes at that page!');
            return redirect()->back();
        }
    }
    /**
     * Show billing.
     *
     * @return \Illuminate\Http\Response
     */
   public function addInvoiceComment(Request $request){
     $input = $request->all();
     if (isset($input['HIC_id'])) {
        $HIC = HorseInvoiceComment::find($input['HIC_id']);
     }else{
        $HIC = new HorseInvoiceComment();
     }
     $HIC->horse_id=$input['horse_id'];
     $HIC->show_id=$input['show_id'];
     $HIC->comment = $input['invoice_comments'];
     $HIC->save();
     \Session::flash('message', 'Comment has been added to Invoice!');
      return redirect()->back();
   }

public function checkout(Request $request)
{
   //dd($request->all());
    $updates = $request->all();
    $totalRoyalty = 0;


    if(count($updates['horseSelected'])>0)
    {

        \Session::put('paypalSelectedHorses',$updates['horseSelected']);


        $show_owner_id = ManageShows::where('id',$request->show_id)->pluck('user_id')->first();

       $show_owner_email = User::where('id',$show_owner_id)->pluck('email')->first();

        $classHorse = HorseInvoices::whereIn('id',$updates['horseSelected'])->orderBy('id','DESC')->get();

        foreach($classHorse as $row)
        {
             $totalRoyalty = $row->royalty + $totalRoyalty;
        }


        $totalSum = $request->get('totalSum');

    if($request->get('type')=='paypal') {

        $user_id = \Auth::user()->id;

        $provider = PayPal::setProvider('adaptive_payments');

        $paypalCharges = (config('services.paypal.paypalFee') + config('services.paypal.paypalFeeCent') / 100) / 100 * $totalSum;
        $OwnerPaypalCharges = (config('services.paypal.paypalFee') + config('services.paypal.paypalFeeCent') / 100) / 100 * $totalRoyalty;

        $PaypalAccountDetail = PaypalAccountDetail::where('userId', $show_owner_id)->first();

     $totalAmountToPay=$paypalCharges+$totalSum;

     $appOwnerAmount = $totalRoyalty + $OwnerPaypalCharges;

     $ShowAmount = $totalAmountToPay - $appOwnerAmount;


        if($appOwnerAmount!=0) {
            $data = [
                'receivers' => [
                    [
                        'email' => 'shakilahmedshaki@gmail.com',
                        'amount' => round($appOwnerAmount),
                        'setItemList' => $updates['horseSelected'],

                    ],
                    [
                        'email' => 'shakilmultan@gmail.com',
                        'amount' => round($ShowAmount),
                        'setItemList' => $updates['horseSelected'],

                    ]
                ],
                'payer' => 'EACHRECEIVER', // (Optional) Describes who pays PayPal fees. Allowed values are: 'SENDER', 'PRIMARYRECEIVER', 'EACHRECEIVER' (Default), 'SECONDARYONLY'
                'setItemList' => $updates['horseSelected'],
                'items'=>$updates['horseSelected'],
                'return_url' => url('master-template/' . nxb_encode($updates['horseSelected'][0]) . '/payment/add-funds/paypal?action=success'),
                'cancel_url' => url('master-template/' . nxb_encode($updates['horseSelected'][0]) . '/payment/add-funds/paypal?action=cancel'),
            ];
        }else
        {
            $data = [
                'receivers' => [
                    [
                        'email' => 'shakilmultan@gmail.com',
                        'amount' => round($ShowAmount),
                    ]
                ],
                'payer' => 'EACHRECEIVER', // (Optional) Describes who pays PayPal fees. Allowed values are: 'SENDER', 'PRIMARYRECEIVER', 'EACHRECEIVER' (Default), 'SECONDARYONLY'

                'return_url' => url('master-template/' . nxb_encode($updates['horseSelected'][0]) . '/payment/add-funds/paypal?action=success'),
                'cancel_url' => url('master-template/' . nxb_encode($updates['horseSelected'][0]) . '/payment/add-funds/paypal?action=cancel'),
            ];
        }


        $response = $provider->createPayRequest($data);
//        dd($response);

        if(isset($response['payKey'])) {

            $response = $provider->createPayRequest($data);

            $redirect_url = $provider->getRedirectUrl('approved', $response['payKey']);

            for ($i = 0; $i < count($request->horseSelected); $i++) {
                $model = new paypalDetail();

                $model->invoice_id = $request->horseSelected[$i];
                $model->pay_id = $response['payKey'];
                $model->save();

           //     $m = HorseInvoices::findOrFail($request->horseSelected[$i]);
           //     $m->invoice_status = 1;
           //     $m->update();
            }
            \Session::flash('message', 'Invoice has been paid successfully');

            return redirect($redirect_url);
        }else{

            \Session::flash('message', 'there is some issues in payment process');
            return redirect()->back();
        }
    }
    else
    {
        return view('shows.billing.stripe');
    }

    }



}



public function stripAjax($amount,$invoiceId)
{

    $invoiceId =  json_encode(explode(',',$invoiceId));

    return view('shows.billing.stripe')->with(compact('amount','invoiceId'));
}


    public function payPalCharges($amount)
    {
          $paypalCharges = number_format($stripeAmount = (config('services.paypal.paypalFee') +config('services.paypal.paypalFeeCent')/100)/100*$amount,2);

          return 'Paypal Charges($ '.$paypalCharges.')';

    }

    public function getPrizeClaimForm(Request $request)
    {

        $prizeClaim = PrizeClaimForm::where('show_id',$request->show_id)->where('horse_id',$request->horse_id)->first();
        $prizeAutoFill = PrizeClaimForm::where('show_id',$request->show_id)->orderBy('id','desc')->first();


      if($prizeClaim)
          return $prizeClaim;
      elseIf($prizeAutoFill)
          return $prizeAutoFill;
      else
          return '';


    }
    public function prizeClaimSubmit(Request $request)
    {

        $user_id = \Auth::user()->id;


        $prizeClaim = PrizeClaimForm::where('show_id',$request->show_id)->where('horse_id',$request->horse_id)->first();
        if($prizeClaim)
            $model = $prizeClaim;
        else
            $model =  New PrizeClaimForm();

        $model->user_id = $user_id;
        $model->show_id = $request->show_id;
        $model->horse_id = $request->horse_id;
        $model->prize_amount = $request->prize_amount;
        $model->social_security_number = $request->social_security_number;
        $model->federal_id_number = $request->federal_id_number;

        $model->save();

        \Session::flash('message', 'Prize Claim Form has been submitted Successfully');

        return redirect()->back();

    }


    public function prizClaimForms($template_id)
    {
        $isEmail = \Session('isEmployee');
        $userEmail = \Auth::user()->email;

        $template_id = nxb_decode($template_id);

        if($isEmail==1) {
            $user_id = getAppOwnerId($userEmail,$template_id);
        }
        else {
            $user_id = \Auth::user()->id;
            $employee_id = 0;
        }


        $manageShows = ManageShows::with(["prizeWon"=>function($q){
            $q->orderBy('id','Desc');
        }])->where('template_id',$template_id)
            ->where('user_id',$user_id)
            ->orderBy('id','Desc')->get();


     //   dd($manageShows->toArray());

        return view('shows.billing.appowner.prizeClaimListing')->with(compact("template_id","manageShows"));
    }


}
