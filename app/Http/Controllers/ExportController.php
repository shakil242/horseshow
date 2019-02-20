<?php

namespace App\Http\Controllers;

use App\Form;
use App\Invoice;
use App\ManageShows;
use App\ManageShowSpectator;
use App\ParticipantResponse;
use App\PrizeClaimForm;
use App\ShowSponsors;
use App\TemplateDesign;
use Illuminate\Http\Request;
use App\Participant;
use App\ManageShowsRegister;
use App\ShowPrizingListing;
use App\ManageShowTrainerSplit;
use App\ClassHorse;
use App\User;
use App\InvitedUser;
use App\Asset;
use App\ManageShowOrderSupplies;
use Illuminate\Support\Facades\DB;
use PDF;
use Excel;

class ExportController extends Controller
{

public function exportResponsePdf($id)
{

    $user_id = \Auth::user()->id;
    $response_id = nxb_decode($id);

    $Asset = ParticipantResponse::with('participant')->where('id',$response_id)->first();
    $show_id = $Asset->participant->show_id;
    $template_id = $Asset->template_id;
    $form_id = $Asset->form_id;
    $FormTemplate = Form::where('id',$form_id)->first();
    $TemplateDesign = TemplateDesign::where('template_id', $template_id)->first();

    $templateName = GetTemplateName($template_id);

    $TD_variables = null;
    $pre_fields = null;
    $formid = null;
    if ($FormTemplate) {
        $TD_variables = getTemplateDesign($TemplateDesign);
        $pre_fields = json_decode($FormTemplate->fields, true);
        $answer_fields = json_decode($Asset->fields, true);
        $formid = $FormTemplate->id;
    }

    $arr = [];

//ArrayPrint($answer_fields);
    if(isset($answer_fields)) {
        foreach ($answer_fields as $k => $val) {
            if (isset($val['answer'])) {
                $arr[$val['form_name']] = $val['answer'];
            }
        }
    }
    $html = view('export.responses',compact('Asset','answer_fields','FormTemplate','TD_variables','template_id','pre_fields','formid','show_id'))->render();

    $pdf = PDF::loadHTML($html);
    $pdf->setPaper('a4', 'landscape');
    return $pdf->download($FormTemplate->name.'-'.getUserNamefromid($Asset->user_id).'-'.getDates($Asset->created_at).'.pdf');

}
        /**
     * Show PDF invoice export for participants.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

public function viewInvoice($manage_show_r_id,$participant_id)
{

        $user_id = \Auth::user()->id;
        $Uname = \Auth::user()->name;
        $manage_show_r_id = nxb_decode($manage_show_r_id);
        $participant_id = nxb_decode($participant_id);
        //Queries
        $participant = Participant::find($participant_id);
        $MSR = ManageShowsRegister::find($manage_show_r_id);
        $invoice = Invoice::where('show_id',$MSR->manage_show_id)->where('payer_id',$user_id)->first();
        $prize = ShowPrizingListing::with("shows")->where("show_id",$participant->show_id)->get();

        //Assigning variables
        $assets = json_decode($MSR->assets_fields);
        $additional_price = json_decode($MSR->additional_fields);
        
        $html =view('shows.pdf.participantInvoice',compact("MSR","invoice","assets","collection",'additional_price','prize','user_id'))->render();
        $pdf = PDF::loadHTML($html);
        $pdf->setPaper('A4', 'landscape');
        return $pdf->download($Uname.'-invoice.pdf');

}
        /**
     * Trainer View invoice.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function viewtrainerInvoice($split_id)
    {
            $user_id = \Auth::user()->id;
            $Uname = \Auth::user()->name;
            $split_id = nxb_decode($split_id);
            $split = ManageShowTrainerSplit::find($split_id);
            //return view('shows.trainers.splitHistorydetail')->with(compact("split"));

            $html =view('shows.pdf.trainerInvoice',compact("split"))->render();
            $pdf = PDF::loadHTML($html);
            $pdf->setPaper('A4', 'landscape');
            return $pdf->download($Uname.'-invoice.pdf');

    }

    /**
     * Print Horse invoice.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function printPDFInvoice(Request $request)
    {
            //dd($request->all());
            $show_id = $request->show_id;
            $invoice_status = $request->status;
            
            $collection = ClassHorse::select("id","horse_id","horse_reg","horse_quantity","user_id",'paid_on','invoice_no','show_id')->with("horse")
                ->where("show_id",$show_id)
                ->whereIn("horse_id",$request->printHorseInvoice)
                ->where("status",$invoice_status)
                ->groupBy("horse_id")
                ->get();
            
            $user_id = $request->user_id;

            $prize = ShowPrizingListing::with("shows")->where("show_id",$show_id)->get();
            $MS = ManageShows::with('template')->find($show_id);
            $appOwner = User::find($MS->user_id);
            $sponsers = getSponsorsCollection($show_id);

            $template_id =  $MS->template_id;
            if($appOwner) {

                $inviteUser = InvitedUser::where('email', '=', $appOwner->email)->where('template_id', $MS->template_id)->first();
                $royalty = $inviteUser->royalty;
            }
            $show_type = ManageShows::where('id',$show_id)->pluck('show_type')->first();
            $m_s_fields = getButtonLabelFromTemplateId($template_id,'m_s_fields');
            //return view('shows.pdf.HorseInvoice',compact('collection','sponsers',"MS","InvoiceStatus","show_id","prize","royalty",'template_id',"user_id"))->render();
            $html=view('shows.pdf.HorseInvoice',compact('collection','m_s_fields','show_type','sponsers',"MS","invoice_status","show_id","prize","royalty",'template_id',"user_id"))->render();
            $pdf = PDF::loadHTML($html);
            $pdf->setPaper('a4', 'landscape');
            $pdf->setOptions(['dpi' => 150, 'defaultFont' => 'sans-serif']);
            $showName = getShowName($show_id);
            $userName = getUserNamefromid($user_id);
            $inName = $showName." ".$userName;
            return $pdf->download($inName.'-invoice.pdf');

    }
        

        /**
     * Show PDF invoice export for app owner.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function viewParticipantInvoice($manage_show_r_id,$participant_id)
    {
        $user_id = \Auth::user()->id;
        $manage_show_r_id = nxb_decode($manage_show_r_id);
        $participant_id = nxb_decode($participant_id);
        //Queries
        $participant = Participant::find($participant_id);
        $payer_id = getIdFromEmail($participant->email);
        $payer_name = getUserNamefromEmail($participant->email);

        $MSR = ManageShowsRegister::find($manage_show_r_id);
        $invoice = Invoice::where('show_id',$MSR->manage_show_id)->where('payer_id',$payer_id)->first();
        $prize = ShowPrizingListing::with("shows")->where("show_id",$participant->show_id)->get();

        //Assigning variables
        $assets = json_decode($MSR->assets_fields);
        $additional_price = json_decode($MSR->additional_fields);
    
        $html =view('shows.pdf.ownerviewInvoice',compact("payer_id","MSR","invoice","assets","collection",'additional_price','prize','user_id'))->render();
        $pdf = PDF::loadHTML($html);
        $pdf->setPaper('A4', 'landscape');
        return $pdf->download($payer_name.'-invoice.pdf');
    }

    /**
     * Show PDF invoice export for app owner.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function exportAssetPositionCsv($form_id)
    {
        $isEmail = \Session('isEmployee');
        $userEmail = \Auth::user()->email;

        $form = Form::where('id',$form_id)->first();
        $template_id = $form->template_id;

        if($isEmail==1) {
            $user_id = getAppOwnerId($userEmail,$template_id);
        }
        else {
            $user_id = \Auth::user()->id;
            $employee_id = 0;
        }



        $assets = Asset::select('id','fields')->with('showPrizing')->where('user_id',$user_id)->where('form_id',$form_id)->get();
        $shows = ManageShows::select('id','title')->where('template_id',$template_id)->where('user_id',$user_id)->get();
        //$show = ManageShows::where('id',21)->first();
        $columns = array();
        // if (count($shows)>0) {
        //    foreach ($shows as $ind=>$show) {
        //        $columns[$ind]['name'] = $show->title;
        //        $columns[$ind]['id'] = $show->id;
        //    }
        // }
        //dd($assets->toArray());
        //return view('export.showPrizeList', compact('showAssetList','show'));
        Excel::create('Position Listing', function ($excel) use ($assets,$shows,$form_id) {
            $excel->sheet('Classes', function ($sheet) use ($assets,$shows) {
                $sheet->loadView('export.assetPrizeList', compact('assets'));
            });
            foreach ($shows as $show) {
                $show_id = $show->id;
                $showtitl = substr($show->title, 0, 30);

                $showAssetList = ShowPrizingListing::with("assets")->where('show_id',$show_id)
                ->whereHas('assets',function($q) use ($form_id){
                    $q->where('form_id',$form_id);
                })->get();
                $excel->sheet($showtitl, function ($sheet) use ($showAssetList,$show) {
                    $sheet->loadView('export.showPrizeList', compact('showAssetList','show'));
                });
            }
        })->download('xls');


    }


    public function exportOwnerCsv($template_id,$type)
    {


        $user_id = \Auth::user()->id;
        $email = \Auth::user()->email;

        $template_id = nxb_decode($template_id);

        if($type=='transfered') {
            $invoiceForms = Invoice::where('template_id', $template_id)->where('is_draft', 2)->where(function ($qry) use ($user_id, $email) {
                return $qry->where('payer_id', $user_id)->orWhere('invoice_email', '=', $email);
            })->orderBy('id', 'desc')->get();
        }
        else {
            $invoiceForms = Invoice::where('template_id', $template_id)->where('invitee_id', $user_id)->orderBy('id', 'desc')->get();
        }
        $excelArr = [];
        $data = [];
        if(($invoiceForms))
        {
            foreach($invoiceForms as $pResponse)
            {

                $type = '';
               $types = $pResponse->billing($pResponse->id)->first();

               if($types)
                 $type = $types ->type;


             if($pResponse->billing($pResponse->id)->count() > 0)
                $status = 'Paid';
             else
                 $status = 'Pending';

            $data[]=[$pResponse->submittedInvitee($pResponse->user_id),$pResponse->invoiceTitle->name,$pResponse->form->name,
                $pResponse->amount,$pResponse->created_at,$status,$type];
            }
        }
        $columns=['Submitted By','Invoice Title','Event','Amount','Invoice Created','Status','Paid Source'];


        $excelArr['Invocie Transffered']=['Column'=>$columns,'data'=>$data];

        Excel::create('invoice-Transffered', function($excel) use($columns,$data) {

                $excel->sheet('Invocie Transffered', function ($sheet) use ($columns,$data) {

                    $sheet->row(1, $columns); // etc etc
                    if (isset($data) && count($data)>0)
                        $sheet->rows($data);
                });

        })->download('xls');

    }


    public function exportParticipantCsv($asset_id,$type)
    {


        $user_id = \Auth::user()->id;
        $email = \Auth::user()->email;

        $asset_id = nxb_decode($asset_id);

        if($type=='transfered') {
            $invoiceForms = Invoice::where('asset_id', $asset_id)->where('is_draft', 2)->where('payer_id', $user_id)->orderBy('id', 'desc')->get();
        }
        else {
            $invoiceForms = Invoice::where('asset_id', $asset_id)->where('invitee_id', $user_id)->orderBy('id', 'desc')->get();
        }
        $excelArr = [];
        $data = [];
        if(($invoiceForms))
        {
            foreach($invoiceForms as $pResponse)
            {

                $type = '';
                $types = $pResponse->billing($pResponse->id)->first();

                if($types)
                    $type = $types ->type;


                if($pResponse->billing($pResponse->id)->count() > 0)
                    $status = 'Paid';
                else
                    $status = 'Pending';

                $data[]=[$pResponse->submittedInvitee($pResponse->user_id),$pResponse->invoiceTitle->name,$pResponse->form->name,
                    $pResponse->amount,$pResponse->created_at,$status,$type];
            }
        }
        $columns=['Submitted By','Invoice Title','Event','Amount','Invoice Created','Status','Paid Source'];


        $excelArr['Invocie Transffered']=['Column'=>$columns,'data'=>$data];

        Excel::create('invoice-Transffered', function($excel) use($columns,$data) {

            $excel->sheet('Invocie Transffered', function ($sheet) use ($columns,$data) {

                $sheet->row(1, $columns); // etc etc
                if (isset($data) && count($data)>0)
                    $sheet->rows($data);
            });

        })->download('xls');

    }


    public function exportinvoiceCsv($invoice_id)
    {


        $user_id = \Auth::user()->id;

        $invoice_id = nxb_decode($invoice_id);

        $invoice = Invoice::where('id',$invoice_id)->first();

        $template_id = $invoice->template_id;

        $answer_fields=[];

        $model = Form::where('id',$invoice->invoice_form_id)->first();

        $columns = array();

            $formType = $model->form_type;
            $header = json_decode($model->fields);

            if ($header != null) {
                //Header Columns
                foreach ($header as $key => $field) {
                    //Donot need images , Videos, uploads ,label
                    if (exclueded_fields_datatable($field->form_field_type)) {
                        $columns[$model->name]['Columns'][] = $field->form_name;
                    }
                }
            }
                if ($invoice->count()) {
                        $innr = json_decode($invoice->fields, true);
                        $data = [$innr];

                        if ($data) {
                            foreach (array_first($data) as $k => $val) {
                                if(isset($val['answer'])) {

                                    if(is_array($val['answer']))
                                        $val['answer'] = implode(',', $val['answer']);
                                    $ar[$val['form_name']] = $val['answer'];
                                }
                            }
                            if(isset($ar))
                                $columns[$model->name]['data'][] = $ar;
                        }
                    }
                 else {
                    $dataSet = [];
                }

        Excel::create($model->name.'-'.$invoice->created_at, function($excel) use($columns) {


            foreach ($columns as $c => $v) {

                $excel->sheet($c, function ($sheet) use ($v) {


                    $sheet->row(1, $v['Columns']); // etc etc
                    if (isset($v['data']) && count($v['data'])>0)
                        $sheet->rows($v['data']);
                });
            }
        })->download('xls');


    }

    public function ExportRegistrationView($manage_show_reg_id)
    {
        $user_id = \Auth::user()->id;
         $manage_show_reg_id = nxb_decode($manage_show_reg_id);

        $Asset = ManageShowsRegister::with("show")->where("id",$manage_show_reg_id)->first();
        $show_id = $Asset->manage_show_id;
        $template_id = $Asset->show->template_id;

        /********* Form display start*************/
        $FormTemplate = Form::where('template_id',$template_id)->where('form_type',F_REGISTRATION)->first();
        $TemplateDesign = TemplateDesign::where('template_id', $template_id)->first();

        $templateName = GetTemplateName($template_id);

        $TD_variables = null;
        $pre_fields = null;
        $formid = null;
        $answer_fields = null;
        if ($FormTemplate) {
            $TD_variables = getTemplateDesign($TemplateDesign);
            $pre_fields = json_decode($FormTemplate->fields, true);
            $answer_fields = json_decode($Asset->fields, true);
            $formid = $FormTemplate->id;
        }


         $html = view('export.responses',compact('Asset','answer_fields','FormTemplate','TD_variables','template_id','pre_fields','formid','show_id'))->render();
        $pdf = PDF::loadHTML($html);
        $pdf->setPaper('a4', 'landscape');
        return $pdf->download($FormTemplate->name.'-'.getUserNamefromid($Asset->user_id).'-'.getDates($Asset->created_at).'.pdf');


    }


    public function ExportOrderSupplies($order_id, $orderType)
    {
        
        $order_id = nxb_decode($order_id);

        $suppliesOrders = ManageShowOrderSupplies::with('orderSupplie')->where('id', $order_id)->first();
        //dd($suppliesOrders->toArray());
        $html = view('export.OrderSupplies',compact('suppliesOrders','orderType'))->render();
        $pdf = PDF::loadHTML($html);
        $pdf->setPaper('a4', 'landscape');
        return $pdf->download(getUserNamefromid($suppliesOrders->trainer_user_id).'-'.getDates($suppliesOrders->created_at).'.pdf');
    }


    public function ExportSpectatorView($id)
    {
        $user_id = \Auth::user()->id;
        $id = nxb_decode($id);

        $Asset = ManageShowSpectator::where("id",$id)->first();
        $show_id = $Asset->show_id;
        $template_id = $Asset->template_id;

        /********* Form display start*************/
        $FormTemplate = Form::where('template_id',$template_id)->where('form_type',SPECTATOR_REGISTRATION)->first();
        $TemplateDesign = TemplateDesign::where('template_id', $template_id)->first();

        $templateName = GetTemplateName($template_id);

        $TD_variables = null;
        $pre_fields = null;
        $formid = null;
        $answer_fields = null;
        if ($FormTemplate) {
            $TD_variables = getTemplateDesign($TemplateDesign);
            $pre_fields = json_decode($FormTemplate->fields, true);
            $answer_fields = json_decode($Asset->fields, true);
            $formid = $FormTemplate->id;
        }


        $html = view('export.responses',compact('Asset','answer_fields','FormTemplate','TD_variables','template_id','pre_fields','formid'))->render();
        $pdf = PDF::loadHTML($html);
        $pdf->setPaper('a4', 'landscape');
        return $pdf->download($FormTemplate->name.'-'.getUserNamefromid($Asset->user_id).'-'.getDates($Asset->created_at).'.pdf');


    }


    public function ExportSponsorsView($show_id,$id)
    {
        $user_id = \Auth::user()->id;
        $show_id = nxb_decode($show_id);
        $id = nxb_decode($id);

        $Asset = ShowSponsors::where("id",$id)->first();

        $shows = ManageShows::where("id",$show_id)->first();


        $show_id = $Asset->show_id;
        $template_id = $shows->template_id;

        /********* Form display start*************/
        $FormTemplate = Form::where('template_id',$template_id)->where('form_type',SPONSOR_REGISTRATION)->first();
        $TemplateDesign = TemplateDesign::where('template_id', $template_id)->first();

        $templateName = GetTemplateName($template_id);

        $TD_variables = null;
        $pre_fields = null;
        $formid = null;
        $answer_fields = null;
        if ($FormTemplate) {
            $TD_variables = getTemplateDesign($TemplateDesign);
            $pre_fields = json_decode($FormTemplate->fields, true);
            $answer_fields = json_decode($Asset->fields, true);
            $formid = $FormTemplate->id;
        }


        $html = view('export.sponsorsResponses',compact('Asset','answer_fields','FormTemplate','TD_variables','template_id','pre_fields','formid'))->render();
        $pdf = PDF::loadHTML($html);
        $pdf->setPaper('a4', 'landscape');
        return $pdf->download($FormTemplate->name.'-'.getUserNamefromid($Asset->sponsor_user_id).'-'.getDates($Asset->created_at).'.pdf');


    }




    public function exportClaimForm($horse_id,$show_id,$type)
    {
        // $horse_id uses as template_id in order to amange all export data.
        $isEmail = \Session('isEmployee');
        if($isEmail==1) {
            $user_id = getAppOwnerId($userEmail,$horse_id);
        }
        else {
            $user_id = \Auth::user()->id;
        }

        $showIds = ManageShows::where('template_id',$horse_id)->where('user_id',$user_id)->pluck('id')->toArray();
        $data =[];

        if($type=='all')
            $prize = ShowPrizingListing::with("shows")->whereIn("show_id",$showIds)->get();
        else
           $prize = ShowPrizingListing::with("shows")->where("show_id",$show_id)->get();



        foreach($prize as $one_asset) {
            $decode_asset = json_decode($one_asset->position_fields);
            foreach ($decode_asset as $pResponse) {
                $claimFormData=[];

                if (isset($pResponse->horse_id)) {

                    if($type=='single') {
                     if($pResponse->horse_id==$horse_id && $one_asset->show_id==$show_id) {
                         $claimFormData = PrizeClaimForm::with(['user', 'show'])->where('show_id', $show_id)->where('horse_id', $horse_id)->get();
                     }
                    }
                    else
                    {
                      $claimFormData = PrizeClaimForm::with(['user', 'show'])->where('horse_id', $pResponse->horse_id)->where('show_id', $one_asset->show_id)->get();
                    }
                    if(isset($claimFormData)) {
                      foreach ($claimFormData as $row) {
                          $data[] = [$row->show->title,$row->user->name, GetAssetNamefromId($pResponse->horse_id),
                              $row->prize_amount, $row->social_security_number, $row->federal_id_number,
                              GetAssetNamefromId($one_asset->asset_id), getPostionTextNoFormate($pResponse->position), getpriceFormate($pResponse->price)
                          ];
                      }
                  }
                }
            }
        }

        $columns=['Show Title','User Name','Horse','TaxPayer Name','Social Security Number','Federal ID Number','Class','Position','Prize'];

        $excelArr['Prize Claim Details']=['Column'=>$columns,'data'=>$data];

        Excel::create('prize-claim-details', function($excel) use($columns,$data) {

            $excel->sheet('Prize Claim Details', function ($sheet) use ($columns,$data) {

                $sheet->row(1, $columns); // etc etc
                if (isset($data) && count($data)>0)
                    $sheet->rows($data);
            });

        })->download('xls');

    }


    public function exportShowsDetails($show_id)
    {


        $user_id = \Auth::user()->id;
        $email = \Auth::user()->email;
        $show_id = nxb_decode($show_id);

        $showTitle = getShowName($show_id);

        $collection = DB::select("select EXTRACT(YEAR FROM MS.date_from) as year,MS.usef_id,A.name,A.fields as assetProfile,SR.asset_id,CH.horse_id,
                (select fields from assets where assets.id=CH.horse_id) as horseProfile,(select name from assets where assets.id=CH.horse_id) as horseName,CC.heights,
                 (select name from assets where assets.id=D.division_id) as divisionName,CC.combined_class_id,CH.horse_reg,CASE WHEN CH.qualifing_check = 1 THEN 'Q' ELSE '' END as qualify,
                 (select fields from assets where assets.id=CH.horse_rider) as riderProfile,(select fields from assets where assets.id=HO.owner_id) as ownerProfile,
                 (select fields from manage_show_trainers where manage_show_trainers.id=MSR.trainer_id) as trainerProfile
                from manage_shows MS 
                INNER JOIN scheduler_restrictions SR on SR.show_id=MS.id
                INNER JOIN assets A on A.id=SR.asset_id
                INNER JOIN class_horses CH on CH.show_id = MS.id
                LEFT JOIN combined_classes CC on CC.class_id = SR.asset_id
                LEFT JOIN divisions D on D.horse_id = CH.horse_id
                LEFT JOIN horse_owners HO on HO.horse_id = CH.horse_id
                LEFT JOIN manage_shows_registers MSR on CH.msr_id = MSR.id
                LEFT JOIN manage_show_trainers MST on MSR.trainer_id = MST.id

                where MS.id =".$show_id." group by CH.horse_id,SR.asset_id limit 1000"
                );



        $excelArr = [];
        $data = [];
        $horseBirthYear = '';
        $scoreTotal = 0;

        foreach ($collection as $c)
        {
            $jFName1 = $jLName1 = $jPercentage1=$jScore1=$jFName2 = $jLName2 = $jPercentage2=$jScore2=$jFName3 = $jLName3 = $jPercentage3=$jScore3=
            $jFName4 = $jLName4 = $jPercentage4=$jScore4=$jFName5 = $jLName5 = $jPercentage5=$jScore5=$jFName6 = $jLName6 = $jPercentage6=$jScore6='';
            $scoreTotal = 0;
            $percentTotal = 0.00;


          $horseBreeds = GetAssetBreed($c->horseProfile,1);
          $horseBreedValue =  breedsWithPercentage($horseBreeds,getFieldsLabel($c->horseProfile,"Percentage of Breed"));
          $numberOfEntries = getClassParticipants($c->asset_id,$show_id,$c->combined_class_id);
          $getClassPrizeMoney= getClassPrizeMoney($c->asset_id);
          $getHorseAward = getPrizeMoneyAwarded($c->asset_id,$show_id,$c->horse_id);

          $judgesData = getJudgesData($c->asset_id,$show_id,$c->horse_id);




            if(isset($judgesData[$c->horse_id][0])) {
              $jFName1 = $judgesData[$c->horse_id][0]['firstName'];
              $jLName1 = $judgesData[$c->horse_id][0]['lastName'];
              $jPercentage1 = $judgesData[$c->horse_id][0]['judgePercentage'];
              $jScore1 = $judgesData[$c->horse_id][0]['judgeScore'];
              $scoreTotal += (int)$jScore1;
              $percentTotal += (float)$jPercentage1;


          }
            if(isset($judgesData[$c->horse_id][1])) {
                $jFName2 = $judgesData[$c->horse_id][1]['firstName'];
                $jLName2 = $judgesData[$c->horse_id][1]['lastName'];
                $jPercentage2 = $judgesData[$c->horse_id][1]['judgePercentage'];
                $jScore2 = $judgesData[$c->horse_id][1]['judgeScore'];
                $scoreTotal += (int)$jScore2;
                $percentTotal += (float)$jPercentage2;

            }
            if(isset($judgesData[$c->horse_id][2])) {
                $jFName3 = $judgesData[$c->horse_id][2]['firstName'];
                $jLName3 = $judgesData[$c->horse_id][2]['lastName'];
                $jPercentage3 = $judgesData[$c->horse_id][2]['judgePercentage'];
                $jScore3 = $judgesData[$c->horse_id][2]['judgeScore'];
                $scoreTotal += (int)$jScore3;
                $percentTotal += (float)$jPercentage3;

            }
            if(isset($judgesData[$c->horse_id][3])) {
                $jFName4 = $judgesData[$c->horse_id][3]['firstName'];
                $jLName4 = $judgesData[$c->horse_id][3]['lastName'];
                $jPercentage4 = $judgesData[$c->horse_id][3]['judgePercentage'];
                $jScore4 = $judgesData[$c->horse_id][3]['judgeScore'];
                $scoreTotal += (int)$jScore4;
                $percentTotal += (float)$jPercentage4;

            }
            if(isset($judgesData[$c->horse_id][4])) {
                $jFName5 = $judgesData[$c->horse_id][4]['firstName'];
                $jLName5 = $judgesData[$c->horse_id][4]['lastName'];
                $jPercentage5 = $judgesData[$c->horse_id][4]['judgePercentage'];
                $jScore5 = $judgesData[$c->horse_id][4]['judgeScore'];
                $scoreTotal += (int)$jScore5;
                $percentTotal += (float)$jPercentage5;

            }
            if(isset($judgesData[$c->horse_id][5])) {
                $jFName6 = $judgesData[$c->horse_id][5]['firstName'];
                $jLName6 = $judgesData[$c->horse_id][5]['lastName'];
                $jPercentage6 = $judgesData[$c->horse_id][5]['judgePercentage'];
                $jScore6 = $judgesData[$c->horse_id][5]['judgeScore'];
                $scoreTotal += (int)$jScore6;
                $percentTotal += (float)$jPercentage6;

            }

            if(isset($getHorseAward['price']))
              $getHorseAwardPrice = $getHorseAward['price'];
          else
              $getHorseAwardPrice = 0.00;

            if(isset($getHorseAward['placing']))
                $getHorseplacing = $getHorseAward['placing'];
            else
                $getHorseplacing = 0;

            if(getFieldsLabel($c->horseProfile,"Date of Birth")!='Not Found' && !empty(getFieldsLabel($c->horseProfile,"Date of Birth")))
            $horseBirthYear = date('Y',strtotime(getFieldsLabel($c->horseProfile,"Date of Birth")));


            $horseDeciplineNo = '';
            $deciplineNumber = [];
            if(getFieldsLabel($c->horseProfile,"FEI Number")!='Not Found')
            $deciplineNumber[] = getFieldsLabel($c->horseProfile,"FEI Number");
            if(getFieldsLabel($c->horseProfile,"AERC Number")!='Not Found')
            $deciplineNumber[] = getFieldsLabel($c->horseProfile,"AERC Number");
            if(getFieldsLabel($c->horseProfile,"AVA Number")!='Not Found')
            $deciplineNumber[] = getFieldsLabel($c->horseProfile,"AVA Number");
            if(getFieldsLabel($c->horseProfile,"USDF Number")!='Not Found')
            $deciplineNumber[] = getFieldsLabel($c->horseProfile,"USDF Number");
            if(getFieldsLabel($c->horseProfile,"USEA Number")!='Not Found')
            $deciplineNumber[] = getFieldsLabel($c->horseProfile,"USEA Number");
            if(getFieldsLabel($c->horseProfile,"USPEA Number")!='Not Found')
            $deciplineNumber[] = getFieldsLabel($c->horseProfile,"USPEA Number");
            if(getFieldsLabel($c->horseProfile,"USHJA Number")!='Not Found')
            $deciplineNumber[] = getFieldsLabel($c->horseProfile,"USHJA Number");
            if(!empty($deciplineNumber))
                $horseDeciplineNo = implode(',',array_filter($deciplineNumber));


            $riderDeciplineNo = '';
            $riderDeciplineNumber = [];
            if(getFieldsLabel($c->riderProfile,"FEI Number")!='Not Found')
            $riderDeciplineNumber[] = getFieldsLabel($c->riderProfile,"FEI Number");
            if(getFieldsLabel($c->riderProfile,"AERC Number")!='Not Found')
            $riderDeciplineNumber[] = getFieldsLabel($c->riderProfile,"AERC Number");
            if(getFieldsLabel($c->riderProfile,"AVA Number")!='Not Found')
            $riderDeciplineNumber[] = getFieldsLabel($c->riderProfile,"AVA Number");
            if(getFieldsLabel($c->riderProfile,"USDF Number")!='Not Found')
            $riderDeciplineNumber[] = getFieldsLabel($c->riderProfile,"USDF Number");
            if(getFieldsLabel($c->riderProfile,"USEA Number")!='Not Found')
            $riderDeciplineNumber[] = getFieldsLabel($c->riderProfile,"USEA Number");
            if(getFieldsLabel($c->riderProfile,"USPEA Number")!='Not Found')
            $riderDeciplineNumber[] = getFieldsLabel($c->riderProfile,"USPEA Number");
            if(getFieldsLabel($c->riderProfile,"USHJA Number")!='Not Found')
            $riderDeciplineNumber[] = getFieldsLabel($c->riderProfile,"USHJA Number");
            if(!empty($riderDeciplineNumber))
                $riderDeciplineNo = implode(',',array_filter($deciplineNumber));


            $horseAffiliate = '';
            $horseAffiliateNo = [];
            if(getFieldsLabel($c->horseProfile,"1(a) Affiliate Association")!='Not Found')
            $horseAffiliateNo[] = str_replace('|||','',getFieldsLabel($c->horseProfile,"1(a) Affiliate Association")).' = '.getFieldsLabel($c->horseProfile,"1(b) Affiliate Association Number");
            if(getFieldsLabel($c->horseProfile,"2(a) Affiliate Association")!='Not Found')
            $horseAffiliateNo[] = str_replace('|||','',getFieldsLabel($c->horseProfile,"2(a) Affiliate Association")).' = '.getFieldsLabel($c->horseProfile,"2(b) Affiliate Association Number");
            if(getFieldsLabel($c->horseProfile,"3(a) Affiliate Association")!='Not Found')
            $horseAffiliateNo[] = str_replace('|||','',getFieldsLabel($c->horseProfile,"3(a) Affiliate Association")).' = '.getFieldsLabel($c->horseProfile,"3(b) Affiliate Association Number");
            if(getFieldsLabel($c->horseProfile,"4(a) Affiliate Association")!='Not Found')
            $horseAffiliateNo[] = str_replace('|||','',getFieldsLabel($c->horseProfile,"4(a) Affiliate Association")).' = '.getFieldsLabel($c->horseProfile,"4(b) Affiliate Association Number");
            if(!empty($horseAffiliateNo))
                $horseAffiliate = implode(',',$horseAffiliateNo);

            $riderAffiliate = '';
            $riderAffiliateNo = [];
            if(getFieldsLabel($c->riderProfile,"1(a) Breed/Affiliate Association")!='Not Found')
            $riderAffiliateNo[] = str_replace('|||','',getFieldsLabel($c->riderProfile,"1(a) Breed/Affiliate Association")).' = '.getFieldsLabel($c->riderProfile,"1(b) Breed/Affiliate Number");
            if(getFieldsLabel($c->riderProfile,"2(a) Breed/Affiliate Association")!='Not Found')
            $riderAffiliateNo[] = str_replace('|||','',getFieldsLabel($c->riderProfile,"2(a) Breed/Affiliate Association")).' = '.getFieldsLabel($c->riderProfile,"2(b) Breed/Affiliate Number");
            if(getFieldsLabel($c->riderProfile,"3(a) Breed/Affiliate Association")!='Not Found')
            $riderAffiliateNo[] = str_replace('|||','',getFieldsLabel($c->riderProfile,"3(a) Breed/Affiliate Association")).' = '.getFieldsLabel($c->riderProfile,"3(b) Breed/Affiliate Number");
            if(getFieldsLabel($c->riderProfile,"4(a) Breed/Affiliate Association")!='Not Found')
            $riderAffiliateNo[] = str_replace('|||','',getFieldsLabel($c->riderProfile,"4(a) Breed/Affiliate Association")).' = '.getFieldsLabel($c->riderProfile,"4(b) Breed/Affiliate Number");
            if(!empty($riderAffiliateNo))
                $riderAffiliate = implode(',',$riderAffiliateNo);


            $trainerAffiliate = '';
            $trainerAffiliateNo = [];
            if(getFieldsLabel($c->trainerProfile,"1(a) Breed/Affiliate Association")!='Not Found' && getFieldsLabel($c->trainerProfile,"1(a) Breed/Affiliate Association")!='')
            $trainerAffiliateNo[] = str_replace('|||','',getFieldsLabel($c->trainerProfile,"1(a) Breed/Affiliate Association")).' = '.getFieldsLabel($c->trainerProfile,"1(b) Breed/Affiliate Number");
            if(getFieldsLabel($c->trainerProfile,"2(a) Breed/Affiliate Association")!='Not Found' && getFieldsLabel($c->trainerProfile,"2(a) Breed/Affiliate Association")!='')
            $trainerAffiliateNo[] = str_replace('|||','',getFieldsLabel($c->trainerProfile,"2(a) Breed/Affiliate Association")).' = '.getFieldsLabel($c->trainerProfile,"2(b) Breed/Affiliate Number");
            if(getFieldsLabel($c->trainerProfile,"3(a) Breed/Affiliate Association")!='Not Found' && getFieldsLabel($c->trainerProfile,"3(a) Breed/Affiliate Association")!='')
            $trainerAffiliateNo[] = str_replace('|||','',getFieldsLabel($c->trainerProfile,"3(a) Breed/Affiliate Association")).' = '.getFieldsLabel($c->trainerProfile,"3(b) Breed/Affiliate Number");
            if(getFieldsLabel($c->trainerProfile,"4(a) Breed/Affiliate Association")!='Not Found' && getFieldsLabel($c->trainerProfile,"4(a) Breed/Affiliate Association")!='')
            $trainerAffiliateNo[] = str_replace('|||','',getFieldsLabel($c->trainerProfile,"4(a) Breed/Affiliate Association")).' = '.getFieldsLabel($c->trainerProfile,"4(b) Breed/Affiliate Number");
            if(!empty($trainerAffiliateNo))
                $trainerAffiliate = implode(',',$trainerAffiliateNo);

            $ownerDeciplineNo = '';
            $ownerDeciplineNumber = [];
            if(getFieldsLabel($c->ownerProfile,"FEI Number")!='Not Found')
            $ownerDeciplineNumber[] = getFieldsLabel($c->ownerProfile,"FEI Number");
            if(getFieldsLabel($c->ownerProfile,"AERC Number")!='Not Found')
            $ownerDeciplineNumber[] = getFieldsLabel($c->ownerProfile,"AERC Number");
            if(getFieldsLabel($c->ownerProfile,"AVA Number")!='Not Found')
            $ownerDeciplineNumber[] = getFieldsLabel($c->ownerProfile,"AVA Number");
            if(getFieldsLabel($c->ownerProfile,"USDF Number")!='Not Found')
            $ownerDeciplineNumber[] = getFieldsLabel($c->ownerProfile,"USDF Number");
            if(getFieldsLabel($c->ownerProfile,"USEA Number")!='Not Found')
            $ownerDeciplineNumber[] = getFieldsLabel($c->ownerProfile,"USEA Number");
            if(getFieldsLabel($c->ownerProfile,"USPEA Number")!='Not Found')
            $ownerDeciplineNumber[] = getFieldsLabel($c->ownerProfile,"USPEA Number");
            if(getFieldsLabel($c->ownerProfile,"USHJA Number")!='Not Found')
            $ownerDeciplineNumber[] = getFieldsLabel($c->ownerProfile,"USHJA Number");
            if(!empty($ownerDeciplineNumber))
                $ownerDeciplineNo = implode(',',array_filter($ownerDeciplineNumber));


            $ownerAffiliate = '';
            $ownerAffiliateNo = [];
            if(getFieldsLabel($c->ownerProfile,"1(a) Breed/Affiliate Association")!='Not Found' && getFieldsLabel($c->ownerProfile,"1(a) Breed/Affiliate Association")!='')
            $ownerAffiliateNo[] = str_replace('|||','',getFieldsLabel($c->ownerProfile,"1(a) Breed/Affiliate Association")).' = '.getFieldsLabel($c->ownerProfile,"1(b) Breed/Affiliate Number");
            if(getFieldsLabel($c->ownerProfile,"2(a) Breed/Affiliate Association")!='Not Found' && getFieldsLabel($c->ownerProfile,"2(a) Breed/Affiliate Association")!='')
            $ownerAffiliateNo[] = str_replace('|||','',getFieldsLabel($c->ownerProfile,"2(a) Breed/Affiliate Association")).' = '.getFieldsLabel($c->ownerProfile,"2(b) Breed/Affiliate Number");
            if(getFieldsLabel($c->ownerProfile,"3(a) Breed/Affiliate Association")!='Not Found' && getFieldsLabel($c->ownerProfile,"3(a) Breed/Affiliate Association")!='')
            $ownerAffiliateNo[] = str_replace('|||','',getFieldsLabel($c->ownerProfile,"3(a) Breed/Affiliate Association")).' = '.getFieldsLabel($c->ownerProfile,"3(b) Breed/Affiliate Number");
            if(getFieldsLabel($c->ownerProfile,"4(a) Breed/Affiliate Association")!='Not Found' && getFieldsLabel($c->ownerProfile,"4(a) Breed/Affiliate Association")!='')
            $ownerAffiliateNo[] = str_replace('|||','',getFieldsLabel($c->ownerProfile,"4(a) Breed/Affiliate Association")).' = '.getFieldsLabel($c->ownerProfile,"4(b) Breed/Affiliate Number");
            if(!empty($ownerAffiliateNo))
                $ownerAffiliate = implode(',',$ownerAffiliateNo);



            $data[]=[
               0=>$c->year,
               1=>$c->usef_id,
               2=>ltrim(getFieldsLabel($c->assetProfile,"Class Number"), '0'),
               3=>str_replace(array('\'', '"'), '', getFieldsLabel($c->assetProfile,"Class Title")),
               4=>getFieldsLabel($c->assetProfile,"USEF Section Code"),
               5=>$c->heights,
               6=>$c->divisionName,
               7=>getFieldsLabel($c->assetProfile,"Jumper Height"),
               8=>str_replace('|||','',getFieldsLabel($c->assetProfile,"Jumper Type")),
               9=>$numberOfEntries,
               10=>$getHorseAwardPrice,
               11=>$getClassPrizeMoney,
               12=>$c->qualify,
               13=>$getHorseplacing,
               14=>$c->horse_reg,
               15=>getFieldsLabel($c->horseProfile,"USEF ID Number"),
               16=>getFieldsLabel($c->horseProfile,"Passport#"),
               17=>getFieldsLabel($c->horseProfile,"Name"),
               18=>$horseAffiliate,
               19=>$horseDeciplineNo,
               20=>$horseBirthYear,
               21=>$horseBreedValue,
               22=>'No USEF',
               23=>getFieldsLabel($c->riderProfile,"USEF ID Number"),
               24=>getFieldsLabel($c->riderProfile,"Name"),
               25=>getFieldsLabel($c->riderProfile,"Street Address"),
               26=>getFieldsLabel($c->riderProfile,"City"),
               27=>getFieldsLabel($c->riderProfile,"State"),
               28=>getFieldsLabel($c->riderProfile,"Zip"),
               29=>$riderAffiliate,
               30=>$riderDeciplineNo,
               31=>getFieldsLabel($c->ownerProfile,"USEF ID Number"),
               32=>'No USEF',
               33=>getFieldsLabel($c->ownerProfile,"Name"),
               34=>getFieldsLabel($c->ownerProfile,"Street Address"),
               35=>getFieldsLabel($c->ownerProfile,"City"),
               36=>getFieldsLabel($c->ownerProfile,"State"),
               37=>getFieldsLabel($c->ownerProfile,"Zip"),
               38=>$ownerAffiliate,
               39=>$ownerDeciplineNo,
               40=>getFieldsLabel($c->trainerProfile,"USEF ID Number"),
               41=>'No USEF',
               42=>getFieldsLabel($c->trainerProfile,"Name"),
               43=>getFieldsLabel($c->trainerProfile,"Street Address"),
               44=>getFieldsLabel($c->trainerProfile,"City"),
               45=>getFieldsLabel($c->trainerProfile,"State"),
               46=>getFieldsLabel($c->trainerProfile,"Zip"),
               47=>$trainerAffiliate,
               48=>$jFName1,
               49=>$jLName1,
               50=>$jPercentage1,
               51=>$jScore1,
               52=>$jFName2,
               53=>$jLName2,
               54=>$jPercentage2,
               55=>$jScore2,
               56=>$jFName3,
               57=>$jLName3,
               58=>$jPercentage3,
               59=>$jScore3,
               60=>$jFName4,
               61=>$jLName4,
               62=>$jPercentage4,
               63=>$jScore4,
               64=>$jFName5,
               65=>$jLName5,
               66=>$jPercentage5,
               67=>$jScore5,
               68=>$jFName6,
               69=>$jLName6,
               70=>$jPercentage6,
               71=>$jScore6,
               72=>$scoreTotal,
               73=>$percentTotal,
               74=>1,
               75=>1,
               76=>1,
               77=>1,
               78=>1,
               79=>1,
               80=>1,
               81=>1,
               82=>1,
               83=>1,
               84=>1,
               85=>1,
               86=>1,
               87=>1,
               88=>1,
               89=>1,
               90=>1,
               91=>1,
               92=>1,
               93=>1,
               94=>1,
               95=>1,
               96=>1,
               97=>1,
               98=>1,
               99=>1,
               100=>1,
               101=>1,
               102=>1,
               103=>1,
               104=>1,
               105=>1,
               106=>1,
               107=>1,
               108=>1,
               109=>1,
               110=>1,
               111=>1,
               112=>1

           ];
        }
        $columns=[
        0=>'COMP YEAR',
        1=>'USEF Comp ID number',
        2=>'Class Number',
        3=>'Class Title',
        4=>'USEF Section Code',
        5=>'Height',
        6=>'Hunter Derby/ Classic-Section Declarations',
        7=>'Jumper Height',
        8=>'Jumper Type',
        9=>'Number of entries',
        10=>'Prize Money awarded',
        11=>'Prize Money Offered',
        12=>'Qualifier Dressage or Eventing (Dressage mandatory)',
        13=>'Placing',
        14=>'Exhibitor Number',
        15=>'Horse USEF Number',
        16=>'Horse Passport Number',
        17=>'Horse Name',
        18=>'Horse Registration/Breed Affiliate Number',
        19=>'Horse FEI Number or Discipline Affiliate Number (USDF, USEA, etc.)',
        20=>'Horse Year of Birth',
        21=>'Horse Breed',
        22=>'If No USEF Number, WHY?',
        23=>'Rider USEF Number',
        24=>'Rider Name',
        25=>'Rider Street Address',
        26=>'Rider City',
        27=>'Rider State',
        28=>'Rider Zip',
        29=>'Rider Breed Affiliate No. (IAHA, AHA,  AMA, etc.)',
        30=>'Rider Discipline Number or FEI Number or Additional Affiliate Number (USDF, USEA, etc.)',
        31=>'Owner USEF Number',
        32=>'If No USEF Number, WHY?',
        33=>'Owner Name',
        34=>'Owner Street Address',
        35=>'Owner City',
        36=>'Owner State',
        37=>'Owner Zip',
        38=>'Owner Breed Affiliate No. (,AHA, IAHA, etc.)',
        39=>'Owner Discipline Number or FEI Number or Additional Affiliate Number (USDF, USEA, etc.)',
        40=>'Trainer USEF Number',
        41=>'If No USEF Number, WHY?',
        42=>'Trainer Name',
        43=>'Trainer Address',
        44=>'Trainer City',
        45=>'Trainer State',
        46=>'Trainer Zip',
        47=>'Trainer Affiliate Number (USDF, AHA, IAHA, etc.)',
        48=>'Judge 1 First Name',
        49=>'Judge 1 Last Name',
        50=>'Judge 1 Percentage',
        51=>'Judge 1 Score (Optional except Dressage)',
        52=>'Judge 2 First Name',
        53=>'Judge 2 Last Name',
        54=>'Judge 2 Percentage',
        55=>'Judge 2 Score (Optional except Dressage)',
        56=>'Judge 3 First Name',
        57=>'Judge 3 Last Name',
        58=>'Judge 3 Percentage',
        59=>'Judge 3 Score (Optional except Dressage)',
        60=>'Judge 4 First Name',
        61=>'Judge 4 Last Name',
        62=>'Judge 4 Percentage',
        63=>'Judge 4 Score (Optional except Dressage)',
        64=>'Judge 5 First Name',
        65=>'Judge 5 Last Name',
        66=>'Judge 5 Percentage',
        67=>'Judge 5 Score (Optional except Dressage)',
        68=>'Judge 6 First Name',
        69=>'Judge 6 Last Name',
        70=>'Judge 6 Percentage',
        71=>'Judge 6 Score (Optional except Dressage)',
        72=>'Dressage Score (Total)',
        73=>'Dressage Percentage (Total)',
        74=>'Dressage Level',
        75=>'Dressage Rider Status',
        76=>'Eventing Dressage Total Score',
        77=>'Eventing Dressage Percentage',
        78=>'Eventing XC Jump Pen',
        79=>'Eventing XC Time Pen',
        80=>'Eventing Score After XC',
        81=>'Eventing SJ Jump',
        82=>'Eventing SJ Time',
        83=>'Eventing Final Score',
        84=>'Dangerous Ride',
        85=>'1st Inspection',
        86=>'Final Inspection',
        87=>'First Round Faults  (Jumping)',
        88=>'First Round Time (Jumping)',
        89=>'Second Round Faults (Jumping)',
        90=>'Second Round Time (Jumping)',
        91=>'Jump-off 2 Faults',
        92=>'Jump-off 2 Time',
        93=>'Reining Total Score',
        94=>'Vaulting Round 1 Score',
        95=>'Vaulting Round 2 Score',
        96=>'Vaulting Overall Score',
        97=>'Combined Driving Dressage Penalties',
        98=>'Combined Driving Place after Dressage',
        99=>'Time Pen A',
        100=>'Time Pen D',
        101=>'Time Pen E',
        102=>'Combined Driving Place after Marathon',
        103=>'Combined Driving Marathon score',
        104=>'CONES Combined Driving  Time OB  Pen',
        105=>'CONES Combined Driving OB Pen',
        106=>'Combined Driving Cones Score',
        107=>'Combined Driving Place in Cones',
        108=>'Combined Driving Total Pen',
        109=> 'Combined Driving Final Placing',
        110=>'Arabian class codes (AHA)',
        111=>'FEI Rider Nationality',
        112=>'FEI Horse Nationality'
];
        $excelArr[$showTitle.' details']=['Column'=>$columns,'data'=>$data];

        Excel::create($showTitle.'-details', function($excel) use($columns,$data,$showTitle) {

            $excel->sheet($showTitle.' details', function ($sheet) use ($columns,$data) {

                $sheet->row(1, $columns); // etc etc
                if (isset($data) && count($data)>0)
                    $sheet->rows($data);
            });

        })->download('xls');


    }


}
