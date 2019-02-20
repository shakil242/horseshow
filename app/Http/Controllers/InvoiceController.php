<?php

namespace App\Http\Controllers;

use App\AssetModules;
use App\Billing;
use App\Form;
use App\InviteInvoices;
use App\inviteParticipantinvoice;
use App\Invoice;
use App\Module;
use App\Participant;
use App\ParticipantAccountInformation;
use App\ParticipantResponse;
use App\PaypalAccountDetail;
use App\subParticipants;
use App\Template;
use App\TemplateDesign;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Prophecy\Argument\Token\ArrayCountToken;
use Stripe\Stripe;
use Stripe\Charge;
use Stripe\Customer;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    



    public function getInvoiceForm($id,$form_id,$template_id,$asset_id,$participantId,$invite_asociated_key,$appOwnerRequest=null,$responseId=null)
    {
        $id = nxb_decode($id);
        
        $user_id = \Auth::user()->id;
      
       $email = \Auth::user()->email;
        
        $frm = Form::where('id',nxb_decode($form_id))->first();
        
//        $model = InviteInvoices::where('participant_email','=',$email)
//            ->whereRaw('FIND_IN_SET(?, asset_id)', [nxb_decode($asset_id)])
//            ->where('invoiceFormKey',$invite_asociated_key)
//            ->where('module_id',$frm->linkto)
//            ->where('template_id',nxb_decode($template_id))
//            ->first();
    
        $model = inviteParticipantinvoice::where('invoiceFormKey',trim($invite_asociated_key))->where('module_id',$frm->linkto)
            ->where('asset_id',nxb_decode($asset_id))
            ->first();
        
        $invitedInvoice = '';
    
        
        if($model)
        {

            pullInvoice($id,nxb_decode($asset_id),$form_id,nxb_decode($template_id),$invite_asociated_key);
            
           $module = inviteParticipantinvoice::where('invoiceFormKey',trim($invite_asociated_key))->where('module_id',$frm->linkto)
               ->where('asset_id',nxb_decode($asset_id))
               ->first();
                $FormTemplate = Form::where('id', $module->form_id)->first();
                $TemplateDesign = TemplateDesign::where('template_id', $module->template_id)->first();
                //MasterTemplate Design Variable  -->
                $TD_variables = getTemplateDesign($TemplateDesign);
                $pre_fields = json_decode($FormTemplate->fields, true);
                $answer_fields = json_decode($module->fields, true);
    
                // END: MasterTemplate Design Variable  -->
                $formid = $FormTemplate->id;
                $invitedInvoice = '1';
    
                $invoiceId = '';
            
    
        }else {
    
    
            $Invoice = Invoice::where('template_id', nxb_decode($template_id))
                    ->where('form_id', nxb_decode($form_id))
                    ->where('asset_id', nxb_decode($asset_id))
                    ->where('invoice_form_id', $id)
                    ->where('payer_id', $user_id)
                    ->first();
            
            if ($Invoice) {
    
                if ($Invoice->is_draft == 1) {
                    
                    $FormTemplate = Form::where('id', $id)->first();
                    $TemplateDesign = TemplateDesign::where('template_id', $template_id)->first();
                    $TD_variables = null;
                    $pre_fields = null;
                    $TD_variables = getTemplateDesign($TemplateDesign);
                    $answer_fields = json_decode($Invoice->fields, true);
                    $pre_fields = json_decode($FormTemplate->fields, true);
                    $invoiceId = $Invoice->id;
                }
                else {
                    
                    $FormTemplate = Form::where('id', $id)->first();
                    $TemplateDesign = TemplateDesign::where('template_id', $template_id)->first();
                    //MasterTemplate Design Variable  -->
                    $TD_variables = getTemplateDesign($TemplateDesign);
                    $pre_fields = json_decode($FormTemplate->fields, true);
                    $invoiceId = '';
                }
                
            }

            else {
                
                $FormTemplate = Form::where('id', $id)->first();
                $TemplateDesign = TemplateDesign::where('template_id', $template_id)->first();
                //MasterTemplate Design Variable  -->
                $TD_variables = getTemplateDesign($TemplateDesign);
                $pre_fields = json_decode($FormTemplate->fields, true);
                $invoiceId = '';
            }
            
        }
    
        $dataBreadcrum = [
            'id' => $id,
            'form_id' => $form_id,
            'template_id' => $template_id,
            'asset_id' => $asset_id,
            'participantId' => $participantId,
            'invite_asociated_key' => $invite_asociated_key,
            'appOwnerRequest' => $appOwnerRequest,
            'responseId' => $responseId
        ];
        
        return view('invoice.viewInvoiceForm')->with(compact('FormTemplate','TD_variables','pre_fields','answer_fields','id','form_id','template_id','asset_id','invoiceId','participantId','responseId','invitedInvoice','appOwnerRequest','dataBreadcrum'));
        
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function saveInvoice(Request $request)
    {
    
        $user_id = \Auth::user()->id;
        
        $template_id = nxb_decode($request->template_id);
        $invoice_form_id = $request->inVoiceformId;
        $form_id = nxb_decode($request->form_id);
        $asset_id = nxb_decode($request->asset_id);
        $participantId =nxb_decode($request->participantId);
        $responseId =nxb_decode($request->responseId);
        $appOwnerRequest = $request->appOwnerRequest;
        
        $invitee=Participant::select('invitee_id','invite_asociated_key')->where('id',$participantId)->first();
        
        $invitee_id = $invitee->invitee_id;
        $sub_participant_id = 0;
//        if(\Session::has('subparticipantId')){
//            $user_id = \Session('subparticipantId');
//            $sub_participant_id = \Auth::user()->id;
//            \Session::put('subparticipantId', '');
//        }else
//        {
//            $sub_participant_id = 0;
//        }
        
            if($request->invoiceId!='') {
            $model = Invoice::findOrFail($request->invoiceId);
            
            $model->template_id = $template_id;
            $model->invoice_form_id = $invoice_form_id;
            $model->form_id = $form_id;
            $model->asset_id = $asset_id;
            $model->invitee_id = $user_id;
           $model->show_owner_id = $user_id;

            $model->payer_id = $invitee_id;
                
            $model->response_id = $responseId;

            
            if ($request->has("Draft"))
                $model->is_draft = 1;
            elseif(!$request->has("Discard"))
                $model->is_draft = 2;
    
            if ($request->has("Discard"))
                   $model->is_discard = 1;
    
            $model->fields = submitFormFields($request);
            $model->amount = getAmount(submitFormFields($request));
            $model->invite_asociated_key =   $invitee->invite_asociated_key;
            $model->sub_participant_id = $sub_participant_id;
    
            $model->update();
    
        }else
        {
    
            $model = new Invoice();
    
            $model->template_id = $template_id;
            $model->invoice_form_id = $invoice_form_id;
            $model->form_id =$form_id;
            $model->asset_id = $asset_id;
            $model->fields = submitFormFields($request);
            $model->invitee_id = $user_id;
            $model->payer_id = $invitee_id;
            $model->response_id = $responseId;
            $model->amount = getAmount(submitFormFields($request));
    
    
            if ($request->has("Draft"))
                $model->is_draft = 1;
            elseif(!$request->has("Discard"))
                $model->is_draft = 2;
    
            if ($request->has("Discard"))
                $model->is_discard = 1;

            $model->invite_asociated_key =   $invitee->invite_asociated_key;
            $model->sub_participant_id = $sub_participant_id;

            $model->save();
    
        }
       
        
        if ($request->has("Draft")) {
            \Session::flash('message', 'Invoice has been Drafted successfully');
        }
        else {
            \Session::flash('message', 'Invoice has been submitted successfully');
        }
        
        if($appOwnerRequest==1)
            return redirect()->route('master-template-invoice-listing', ['id'=>$template_id]);
        else
        return redirect()->route('participant-invoice-listing', ['id'=>nxb_encode($participantId),'assetId'=>nxb_encode($asset_id),'associatedKey'=>$invitee->invite_asociated_key]);
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function invoiceListing($id,$assetId,$associatedKey=null)
    {
    
        $dataBreadCrumb =[
            'id' => $id,
            'asset_id' => $assetId,
            'invite_asociated_key' => $associatedKey
        ];
        
         $id = nxb_decode($id);
        $email = \Auth::user()->email;
    
        $assetId = nxb_decode($assetId);
    
        $user_id = \Auth::user()->id;
    
        $inviteInvoices = Invoice::where('asset_id',$assetId)->where('payer_id',$user_id)->orderBy('id','desc')->get();

        $viewInvoices = Invoice::where('asset_id',$assetId)->where('invitee_id',$user_id)->orderBy('id','desc')->get();


        $paypalAccountDetail = PaypalAccountDetail::where('userId',$user_id)->first();
    
        $invoiceForms = participantInvoiceForms($id,$assetId)->orderBy('id','desc')->get();
    
       $module = checkInvboice($associatedKey);
        $arr=[];
        foreach ($module as $mod)
        {
            $formId = Form::where('linkto',$mod['module_id'])->first();
            $arr[]=$formId->id;
            
        }
        \Session::put('urlInvoice', url()->current());

        return view('invoice.invited.index')->with(compact("invoiceForms","assetId","id","inviteInvoices","paypalAccountDetail","arr",'dataBreadCrumb','viewInvoices'));
    }
    
    public function SubInvoiceListing($id,$assetId,$associatedKey=null)
    {
        
        $dataBreadCrumb =[
            'id' => $id,
            'asset_id' => $assetId,
            'invite_asociated_key' => $associatedKey
        ];
        
        $id = nxb_decode($id);
        $email = \Auth::user()->email;
        
        $assetId = nxb_decode($assetId);
        
        $user_id = \Auth::user()->id;
    
        $forms_collection =  subParticipants::where('asset_id', $assetId)
            ->where('invite_asociated_key', $associatedKey)
            ->where('email', $email)
            ->first();

        $forms_collection = getFormsFromModules($forms_collection->modules_permission)->get()->toArray();
    
    
        $inviteInvoices = Invoice::where('asset_id',$assetId)->where('payer_id',$user_id)->where('invoice_email',"=",$email)->whereIn('form_id',$forms_collection)->orderBy('id','desc')->get();
        $paypalAccountDetail = PaypalAccountDetail::where('userId',$user_id)->first();
        
        $invoiceForms = participantInvoiceForms($id,$assetId)->orderBy('id','desc')->get();
        
        $module = checkInvboice($associatedKey);
        $arr=[];
        foreach ($module as $mod)
        {
            $formId = Form::where('linkto',$mod['module_id'])->first();
            $arr[]=$formId->id;
            
        }
        
        return view('invoice.invited.index')->with(compact("invoiceForms","assetId","id","inviteInvoices","paypalAccountDetail","arr",'dataBreadCrumb'));
    }
    
    
    
    public function OwnerInvoiceListing($id)
    {
    
        $dataBreadCrumb = [
            'id'=>$id
        ];
        $id = nxb_decode($id);


        $isEmail = \Session('isEmployee');
        $email = \Auth::user()->email;


        if($isEmail==1) {
            $user_id = getAppOwnerId($email,$id);
            $employee_id = \Auth::user()->id;
        }
        else {
            $user_id = \Auth::user()->id;
            $employee_id = 0;
        }

        $invoiceForms = Invoice::where('template_id',$id)->where('is_draft',2)->where('asset_id',"!=",0)->where(function($qry) use($user_id, $email){
            return $qry->where('payer_id',$user_id)->orWhere('invoice_email','=',$email);
        })->orderBy('id','desc')->get();



        $paypalAccountDetail = PaypalAccountDetail::where('userId',$user_id)->first();
    
        $inviteInvoices = Invoice::where('template_id',$id)->where('asset_id',"!=",0)->where('invitee_id',$user_id)->orderBy('id','desc')->get();



//        $inviteInvoices = Invoice::all();
//
//        foreach ($inviteInvoices as $r)
//        {
//            $model = Invoice::findOrFail($r->id);
//            $model->amount=getAmount($r->fields);
//            $model->update();
//        }
        \Session::put('urlInvoice', url()->current());
        return view('invoice.invitee.index')->with(compact("invoiceForms","id","paypalAccountDetail","inviteInvoices",'dataBreadCrumb'));
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
   public function viewInvoice($formId,$templateId,$invoiceFormId,$assetId)
    {
    
        $dataBreadCrumb = [
            'id'=>$formId,
            'templateId'=>$templateId,
            'invoiceFormId'=>$invoiceFormId,
            'assetId'=>$assetId
        ];
        
        $formId = nxb_decode($formId);
        $template_id = nxb_decode($templateId);
        $invoiceFormId = nxb_decode($invoiceFormId);
        $assetId = nxb_decode($assetId);
        
        $invoice = Invoice::where('form_id',$formId)
                        ->where('template_id',$template_id)
                        ->where('invoice_form_id',$invoiceFormId)
                       ->where('asset_id',$assetId)
                        ->first();
        
        $answer_fields=[];
        
        $FormTemplate = Form::where('id',$invoiceFormId)->first();
        
        $TemplateDesign = TemplateDesign::where('template_id', $template_id)->first();
        
        $TD_variables = null;
        $pre_fields = null;
        $formid = null;
        if ($FormTemplate) {
            //MasterTemplate Design Variable  -->
            $TD_variables = getTemplateDesign($TemplateDesign);
            $pre_fields = json_decode($FormTemplate->fields, true);
            if($invoice)
            $answer_fields = json_decode($invoice->fields, true);
            
            // END: MasterTemplate Design Variable  -->
            $formid = $FormTemplate->id;
        }
        
        return view('invoice.invited.viewInvoice')->with(compact('invoice','answer_fields','FormTemplate','TD_variables','template_id','pre_fields','formid','dataBreadCrumb'));
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function viewOwnerInvoice($id,$template_id)
    {
        $dataBreadCrumb = [
            'id' => $id,
            'templateId' => $template_id
        ];
    
    
        $id = nxb_decode($id);
        $template_id = nxb_decode($template_id);
        
        $invoice = Invoice::where('id',$id)->first();
        
        $answer_fields=[];
        
        $FormTemplate = Form::where('id',$invoice->invoice_form_id)->first();
        
        $TemplateDesign = TemplateDesign::where('template_id', $template_id)->first();
        
        $TD_variables = null;
        $pre_fields = null;
        $formid = null;
        if ($FormTemplate) {
            //MasterTemplate Design Variable  -->
            $TD_variables = getTemplateDesign($TemplateDesign);
            $pre_fields = json_decode($FormTemplate->fields, true);
            if($invoice)
                $answer_fields = json_decode($invoice->fields, true);
            
            // END: MasterTemplate Design Variable  -->
            $formid = $FormTemplate->id;
        }
        return view('invoice.invitee.viewInvoice')->with(compact('invoice','answer_fields','FormTemplate','TD_variables','template_id','pre_fields','formid','dataBreadCrumb'));
        
    }
    
    
    public function viewEventForm($id,$template_id)
    {
    
    
        $dataBreadCrumb = [
            'id' => $id,
            'templateId' => $template_id
        ];
    
    
        $responseId = nxb_decode($id);
        
        $template_id = nxb_decode($template_id);
        
        $ParticipantResponse = ParticipantResponse::where('id',$responseId)->first();
    
        $answer_fields = json_decode($ParticipantResponse->fields, true);

        $answer_fields=[];
        
        $formid = $ParticipantResponse->form_id;
    
    
        $FormTemplate = Form::where('id', $ParticipantResponse->form_id)->first();
        
        $TemplateDesign = TemplateDesign::where('template_id', $template_id)->first();
        
        $TD_variables = null;
        $pre_fields = null;
        $formid = null;
        if ($FormTemplate) {
            //MasterTemplate Design Variable  -->
            $TD_variables = getTemplateDesign($TemplateDesign);
            $pre_fields = json_decode($FormTemplate->fields, true);
           
            if($ParticipantResponse)
                $answer_fields = json_decode($ParticipantResponse->fields, true);
            
            // END: MasterTemplate Design Variable  -->
            $formid = $FormTemplate->id;
        }
        return view('invoice.viewForm')->with(compact('ParticipantResponse','answer_fields','FormTemplate','TD_variables','template_id','pre_fields','formid','dataBreadCrumb'));
        
    }
    
    /**
     * @return string
     */
    public function saveInvoiceAmount(Request $request)
    {
    
       $invoiceId = nxb_decode($request->get('invoiceId'));
        
        
        $model = Invoice::findOrFail($invoiceId);
    
        $model->amount =$request->get('amount');
    
        $model->update();
        
        if($model->id)
        {
            return $Response = array(
                'success' => 'Amount has been saved successfully',
            );
            
        }

    }
    
    public function getInvoiceFormView($id,$moduleId,$assetId=null)
    {
        $id = nxb_decode($id);
        $form_id = $id;
        $FormTemplate = Form::where('id', $id)->first();
        $TemplateDesign = TemplateDesign::where('template_id', $FormTemplate->template_id)->first();
        //MasterTemplate Design Variable  -->
        $TD_variables = getTemplateDesign($TemplateDesign);
        $pre_fields = json_decode($FormTemplate->fields, true);
        $invoiceId='';
     
     
        return view('invoice.forms.view')->with(compact('FormTemplate','TD_variables','pre_fields','moduleId','form_id','assetId'));
    }
    
    public function getPanaltyInvoice($tempalteId)
    {
        $tempalteId = nxb_decode($tempalteId);
        $FormTemplate = Form::where('template_id', $tempalteId)->where('form_type', 6)->first();
        $TemplateDesign = TemplateDesign::where('template_id', $tempalteId)->first();
        //MasterTemplate Design Variable  -->
        $TD_variables = getTemplateDesign($TemplateDesign);
        $pre_fields = json_decode($FormTemplate->fields, true);
        $invoiceId='';
        $form_id = $FormTemplate->id;
        return view('invoice.forms.penaltyView')->with(compact('FormTemplate','TD_variables','pre_fields','form_id'));
    }

    
    public function saveInviteInvoice(Request $request)
    {
        
      // we required Asset id here for new requirement, so we need asset id
      //  and module as primary key for invoice to module
      //  we would have to remove following fields from the  inviteParticipantinvoice
      // tempalte_id,invoiceFormKey
      // we will add new column asset_id into this table
      
        $user_id = \Auth::user()->id;
        $template_id = $request->template_id;
        $form_id = $request->form_id;
        $moduleId = nxb_decode($request->moduleId);
        $asset_id = nxb_decode($request->assetId);
        $modules = Module::where('template_id',$template_id)->get();
    
        
        $modulesArray = [];
        $readOnlyArray = [];
        $assetModules = AssetModules::where('template_id',$template_id)->where('asset_id',$asset_id)->first();
         if($assetModules) {
             $modules_permission = json_decode($assetModules->modules_permission, true);
    
             $modulesArray = array_filter($modules_permission, 'filterModulePermissionArray');
    
             $modulesArray = array_keys($modulesArray);

             $readOnlyArray = array_filter($modules_permission, 'filterReadOnlyArray');

             $readOnlyArray = array_keys($readOnlyArray);

         }
        
        $uniqueId = \Session('participantResponseKey'); //for old purpose
        
        
        $inviteModel = inviteParticipantinvoice::where('asset_id',$asset_id)
            ->where('invitee_id',$user_id)
            ->where('module_id',$moduleId);
        
        if($inviteModel->count() > 0)
        {
           $invoiceModel = $inviteModel->first();
    
            $invoiceModel->invoiceFormKey = $uniqueId;
            $invoiceModel->asset_id = $asset_id; //update the key with assets at here
    
            $invoiceModel->invitee_id = $user_id;
            $invoiceModel->template_id = $template_id;
            $invoiceModel->form_id = $form_id;
            $invoiceModel->module_id = $moduleId;
            $invoiceModel->fields = submitFormFields($request);
            $invoiceModel->update();
            
        }
        else {
            $model = new inviteParticipantinvoice();
            $model->asset_id = $asset_id; //update the key with assets at here
            $model->invoiceFormKey = $uniqueId;
            $model->invitee_id = $user_id;
            $model->template_id = $template_id;
            $model->form_id = $form_id;
            $model->module_id = $moduleId;
            $model->fields = submitFormFields($request);;
    
            $model->save();
        }
        
        $invoiceArr = inviteParticipantinvoice::select('module_id','invoiceFormKey')->where('invoiceFormKey',$uniqueId)->get()->toArray();
        
        foreach ($invoiceArr as $key=>$value)
        {
            $invoiceArr[]= $value['module_id'];
            $invoiceFormKey= $value['invoiceFormKey'];

        }
        // $model->invoiceFormKey;
        $showExist = Template::where('id',$template_id)->where("category",CONST_SHOW)->count();
       
        //return redirect()->back();
    
        $participantResponse = \Session('participantResponse');
        $excelData = \Session('excelData');
        $data = \Session('data');
        $associated = \Session('associated');
        $penaltyInvoice = \Session('penaltyInvoice');
        $penaltyTemplate = \Session('penaltyTemplate');
        
        \Session::put('invoiceArr', $invoiceArr);
        \Session::put('invoiceFormKey', $uniqueId);
        
        return view('MasterTemplate.assets.modules')->with(compact('modules','showExist','asset_id','template_id','modulesArray','readOnlyArray'));
        
    }
    
    
    public function savePenaltyInviteInvoice(Request $request)
    {
        
        $user_id = \Auth::user()->id;
        $template_id = $request->template_id;
        $form_id = $request->form_id;
        $uniqueId = \Session('participantResponseKey');
        
        
        $inviteModel = inviteParticipantinvoice::where('invoiceFormKey',$uniqueId)
            ->where('invitee_id',$user_id)
            ->where('template_id',$template_id)
          ->where('is_penalty',1);
        
        
        
        if($inviteModel->count() > 0)
        {
            $invoiceModel = $inviteModel->first();
            
            $invoiceModel->invoiceFormKey = $uniqueId;
            $invoiceModel->invitee_id = $user_id;
            $invoiceModel->template_id = $template_id;
            $invoiceModel->form_id = $form_id;
            $invoiceModel->fields = submitFormFields($request);
            $invoiceModel->update();
    
        }
        else {
            $model = new inviteParticipantinvoice();
            
            $model->invoiceFormKey = $uniqueId;
            $model->invitee_id = $user_id;
            $model->template_id = $template_id;
            $model->form_id = $form_id;
            $model->is_penalty = 1;
            $model->fields = submitFormFields($request);;
            
            $model->save();
        }
    
         $penaltyInvoice = $inviteModel->count();
    
    
        // $model->invoiceFormKey;
        
        //return redirect()->back();
    
        \Session::put('invoiceFormKey', $uniqueId);
        $participantResponse = \Session('participantResponse');
        $excelData = \Session('excelData');
        $data = \Session('data');
        $modules = \Session('modules');
        $associated = \Session('associated');
        $penaltyTemplate = \Session('penaltyTemplate');
        $invoiceArr = \Session('invoiceArr');
        $invoiceFormKey = \Session('invoiceFormKey');


        \Session::put('penaltyInvoice', $penaltyInvoice);
    
        
        //return redirect()->back();
        
        return view('MasterTemplate.participants.permission')->with(compact("participantResponse","excelData","data","modules",'associated','penaltyTemplate','penaltyInvoice','invoiceArr','invoiceFormKey'));
        
        
        //return redirect()->route('participant-invoice-listing', ['id'=>nxb_encode($participantId),'assetId'=>nxb_encode($asset_id)]);
        
    }
    
    
    public function viewAssociatedInvoice($id,$asset_id)
    {
        $id = nxb_decode($id);
        $user_id = \Auth::user()->id;
        $invoice = inviteParticipantinvoice::where('asset_id',$asset_id)
            ->where('invitee_id',$user_id)
            ->where('module_id',$id)
            ->first();
        
        $template_id = $invoice->template_id;
        
        $answer_fields=[];
        
        $FormTemplate = Form::where('id',$invoice->form_id)->first();
        
        $TemplateDesign = TemplateDesign::where('template_id', $template_id)->first();
        
        $TD_variables = null;
        $pre_fields = null;
        $formid = null;
        if ($FormTemplate) {
            //MasterTemplate Design Variable  -->
            $TD_variables = getTemplateDesign($TemplateDesign);
            $pre_fields = json_decode($FormTemplate->fields, true);
            if($invoice) {
                $answer_fields = json_decode($invoice->fields, true);
            }
            // END: MasterTemplate Design Variable  -->
            $formid = $FormTemplate->id;
        }
        return view('invoice.forms.viewInvoice')->with(compact('invoice','answer_fields','FormTemplate','TD_variables','template_id','pre_fields','id','formid','asset_id'));

     //   return view('invoice.invitee.viewInvoice')->with(compact('invoice','answer_fields','FormTemplate','TD_variables','template_id','pre_fields','formid'));
        
    }
    
    public function viewPenaltyAssociatedInvoice($template_id,$invoiceFormKey)
    {
        $user_id = \Auth::user()->id;
        $template_id = nxb_decode($template_id);
        
        $invoice = inviteParticipantinvoice::where('invoiceFormKey',trim($invoiceFormKey))
            ->where('invitee_id',$user_id)
            ->where('template_id',$template_id)
            ->where('is_penalty',1)
            ->first();
        
        $answer_fields=[];
        
        $FormTemplate = Form::where('id',$invoice->form_id)->first();
        
        $TemplateDesign = TemplateDesign::where('template_id', $template_id)->first();
        
        $TD_variables = null;
        $pre_fields = null;
        $formid = null;
        if ($FormTemplate) {
            //MasterTemplate Design Variable  -->
            $TD_variables = getTemplateDesign($TemplateDesign);
            $pre_fields = json_decode($FormTemplate->fields, true);
            if($invoice) {
                $answer_fields = json_decode($invoice->fields, true);
            }
            // END: MasterTemplate Design Variable  -->
            $formid = $FormTemplate->id;
        }
        return view('invoice.forms.viewPenaltyInvoice')->with(compact('invoice','answer_fields','FormTemplate','TD_variables','template_id','pre_fields','formid'));
        
        //   return view('invoice.invitee.viewInvoice')->with(compact('invoice','answer_fields','FormTemplate','TD_variables','template_id','pre_fields','formid'));
        
    }
    
    
    
}
