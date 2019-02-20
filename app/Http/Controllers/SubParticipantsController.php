<?php

namespace App\Http\Controllers;

use App\InvitedUser;
use App\inviteParticipantinvoice;
use App\Invoice;
use App\schedualNotes;
use App\User;
use function GuzzleHttp\Promise\all;
use Illuminate\Http\Request;
use App\Participant;
use App\Form;
use App\Module;
use Excel;
use App\Template;
use App\subParticipants;
use App\FormType;
use App\TemplateDesign;
use Illuminate\Support\Facades\Input;
use App\Mail\InviteUser;
use App\Mail\SubParticipant;
use App\ParticipantResponseDraft;
use App\ParticipantResponse;

class SubParticipantsController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($participant_id,$invite_key)
    {
        $user_id = \Auth::user()->id;
        $participant_id = nxb_decode($participant_id);
        $participants = Participant::where('invite_asociated_key','=',$invite_key)->with('asset')->groupBy('asset_id');
        $assetArr =$participants ->pluck('asset_id')->toArray();
        $participantArr =$participants ->pluck('id')->toArray();

        $participants =$participants ->get();
        $template_id = $participants[0]->template_id;
        $pastParticipants = subParticipants::whereIn('asset_id',$assetArr)->where('user_id',$user_id)->where('status',1)->groupBy('email')->get();

       return view('MasterTemplate.subparticipants.index')->with(compact("pastParticipants","participantArr","template_id","assetArr","participants","invite_key"));
    }
    /**
     * Display the assets detail for Sub participants.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $id = nxb_decode($id);
        $userid = \Auth::user()->id;
        $useremail = \Auth::user()->email;
        $participant_collection = subParticipants::where('id',$id)->with("InvitedOnAsset",'Invitee','participant')->first();
        //dd($participant_collection->toArray());
        return view('MasterTemplate.subparticipants.assetdetails')->with(compact("participant_collection"));
    
    }
    /**
     * Enter the information to send an invite for perticular asset.
     *
     * @return \Illuminate\Http\Response
     */
    public function prepareinvite(Request $request)
    {
        $user_id = \Auth::user()->id;
        $excelData = array();
        $penaltyInvoice = 0; //intialization
        $template_id = $request->template_id;
    	$permited_modules = array();
        // to find either penaly template exist or not


        $penaltyTemplate = Form::where('template_id', $template_id)->where('form_type', 6)->count();
        
        //Participant object
        $participant_id = $request->participant_id;
        $invite_key = $request->key_id;

        $participants = Participant::where('invite_asociated_key','=',$invite_key)->where('modules_permission','!=',NULL)->with('asset')->groupBy('asset_id')->get();
        //get perimited modules in array to filter

        $associated_history = [];
        $Pmod = [];
        if($participants->count()>0) {
        $Pmod = json_decode($participants[0]->modules_permission);
        $associated_history = json_decode($participants[0]->associated_history);
        }
        $this->validate($request, [
            // 'location' => "required",
            'emailName.*.email' => 'email|required_without_all:pastParticipats,import_file',
            'emailName.*.name' => 'required_without_all:pastParticipats,import_file',
        ],[
            'required_without_all' => 'Please enter a validate email and Name for the invite new participants',
        ]);
        //Uploading excel if exist


        try{
            if(Input::hasFile('import_file')){
                $path = Input::file('import_file')->getRealPath();
                $Uploaded_file = Excel::load($path, function($reader) {
                })->get();
                if(!empty($Uploaded_file) && $Uploaded_file->count()){
                    foreach ($Uploaded_file as $key => $value) {
                        if ($value->name != null && $value->email != null) {
                            $excelData[] = ['name' => $value->name, 'email' => $value->email];
                        }
                    }
                }
            }
        }
        catch(Exception $e){            
            $error = $e->getMessage();            
            $status = "error";            
            $msg = "Oops something went wrong";        
        }

        //Preparing the invite
        $uniqueId = time().mt_rand().$user_id;

        $assets = $request->get("asset");
        $data = $request->all();
           
        $data['asset'] = $assets;

        $modules = getSubParticipantAssetModules($assets,$Pmod,$uniqueId);

        //$modules = Module::where('template_id',$template_id)->whereIn('id',$permited_modules)->get();
        $templates = Template::where('id',$template_id)->with("associated_template")->first();

        if(count($associated_history) > 0)
         $participantResponse = ParticipantResponse::whereIn('id', $associated_history)->get();
        else
            $participantResponse = null;

//        $participantResponse = null;
        $associated = $templates->associated_template;
        $uniqueId = time().mt_rand().$user_id;
    
        \session()->regenerate();
        \Session::put('participantResponseKey', $uniqueId);

        \Session::put('participantResponse', $participantResponse);
        \Session::put('excelData', $excelData);
        \Session::put('data', $data);
        \Session::put('modules', $modules);
        \Session::put('associated', $associated);
        \Session::put('penaltyTemplate', $penaltyTemplate);


        return view('MasterTemplate.subparticipants.permission')->with(compact("participantResponse",'templates',"excelData","data","modules",'associated','penaltyTemplate','penaltyInvoice'));
    }
    /**
     * Send Invite to Participant for particular assets + Master template.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendinvite(Request $request)
    {
        $data = $request->all();
        $user_id = \Auth::user()->id;
        //$pastParticipats = $request->get('pastParticipats');
        $arr=[];
        $arr_module=$request->get('module');
        $array_asset = $request->get('asset');
        $array_invoiceAttach = $request->get('invoiceAttach');
        $participant_id= $request->get('participant_id');
        //$asset_str = json_encode($array_asset);
        
        // foreach ($arr_module as $key=>$value) {
        //    if ($value == 2) {
        //         $Forms = Form::where('linkto', $key)
        //             ->where('scheduler', 1)
        //             ->first();
        //         if ($Forms) {
        //             if (isset($data['emailName']) && $data['emailName'] != null) {
        //                 foreach ($data['emailName'] as $NP) {
        //                     if ($NP['email'] != "") {
        //                         $participant = new ParticipantAsset();
        //                         $participant->form_id = $Forms->id;
        //                         $participant->template_id = $request->template_id;
        //                         $participant->invitee_id = $user_id;
        //                         $participant->email = $NP['email'];
        //                         $participant->assets = $asset_str;
        //                         $participant->save();
        //                     }
        //                 }
        //             }
        //             if (isset($data['pastParticipats']) && $data['pastParticipats'] != null) {
        //                 foreach ($pastParticipats as $PP) {
        //                     $participant = new ParticipantAsset();
        //                     $participant->form_id = $Forms->id;
        //                     $participant->template_id = $request->template_id;
        //                     $participant->invitee_id = $user_id;
        //                     $participant->email = $PP;
        //                     $participant->assets = $asset_str;
        //                     $participant->save();
        //                 }
        //             }
        //         }
        //     }
        // }

        //For new participants
        if (isset($data['emailName']) && $data['emailName'] != null) {
            foreach ($data['emailName'] as  $NP){
                //$NP is New Participants
                if ($NP['email'] != "" && $NP['name'] != "" ) {
                     $sendmail = $this->createAndSendMail($array_asset,$request,$NP['email'],$NP['name'],$participant_id);
                    //invoiceInvitation($array_invoiceAttach,$array_asset,$request,$NP['email']);
                }
            }
        }
        //In case of past participants Invited
        if (isset($data['pastParticipats']) && $data['pastParticipats'] != null) {
            foreach ($data['pastParticipats'] as  $PP){
                //$PP is Past Participants
               	$sendmail = $this->createAndSendMail($array_asset,$request,$PP,getUserNamefromEmail($PP),$participant_id);
                //invoiceInvitation($array_invoiceAttach,$array_asset,$request,$PP);
            }
        }
            
        if ($sendmail) {
            \Session::put('penaltyTemplate', '');
            \Session::put('penaltyInvoice', '');
            \Session::flash('message', 'Invite has been send to User(s) successfully');
                return redirect()->action('UserController@index');
        }
        
    }
    public function createAndSendMail($asset,$data,$email,$name,$participant_id){
        $user_id = \Auth::user()->id;
        $uniqueId = \Session('participantResponseKey');

        $participantArr = explode(',',$participant_id);

            foreach ($participantArr as $participant) {

                $par = Participant::where('id',$participant)->first();

                $model = new subParticipants();
                $model->template_id = $data->template_id;
                $model->user_id = $user_id;
                $model->asset_id = $par->asset_id;
                $model->email = $email;
                $model->name = $name;
                $model->participant_id = $participant;
                $model->invite_asociated_key = $uniqueId;

                $model->description = $data->get('description');

                if ($data->get('attachmentHistory') != null) {
                    $model->associated_history = json_encode($data->get('attachmentHistory'));
                }
                if ($data->get('drp_permission') != null) {
                    $model->allowed_time = $data->get('drp_permission');
                } else {
                    $model->allowed_time = $data->get('permission');
                }


                $arr_module = getModuels($data->module);

                $modules_permission = json_encode($arr_module);

                $model->modules_permission = $modules_permission;

//            $fields_inputs = json_encode($data->get('module'));
//            $model->modules_permission = $fields_inputs;
                //$model->invited_master_template = $data->get('invited_master_template');

                // $penaltyInvoice = \Session('penaltyInvoice');

                // if($penaltyInvoice > 0) {
                //     if ($data->get('penaltyDate') != '') {
                //         $date = date('Y-m-d', strtotime($data->get('penaltyDate')));
                //         $model->penalty_date = $date;
                //         $model->is_penalty = 1;
                //     }
                // }
                $model->save();
            }
        
        //Send email for new master template as invite user.
        if ($data->get('invited_master_template')!= null) {
                //Check if already invited_user exists
                $invited_user = InvitedUser::where('email',$email)->where('template_id',$data->get('invited_master_template'))->first();
                if ($invited_user == null) {
                    $invited_user = new InvitedUser();
                    $invited_user->name = $data->get('name');
                    $invited_user->email = $email;
                    $invited_user->invited_by = $user_id;
                    $invited_user->template_id = $data->get('invited_master_template');
                    $invited_user->save();
                }
               
                
            \Mail::to($email)->send(new InviteUser($invited_user));
        }

        //Send email for the asset invite.
         \Mail::to($email)->send(new SubParticipant($model));
        return true;
    }

        /**
     * Show the modules allowed for for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function viewModules($template_id,$invite_id,$asset_id,$invite_asociated_key=null)
    {
      
        $invite_id = nxb_decode($invite_id);
        $template_id = nxb_decode($template_id);

        $participant = subParticipants::where('id',$invite_id)->first();

        $participant_id = $participant->participant_id;

        $getInvitee = participant::where('id',$participant_id)->first();

        $invitee_id = $getInvitee->invitee_id;

        $email = User::where('id',$invitee_id)->pluck('email')->first();

        $app_id = InvitedUser::where('email',$email)->where('template_id',$template_id)->pluck('id')->first();

        $permission = json_decode($participant->modules_permission,true);
        //Modules
        $collection = Module::where('template_id',$template_id)->where('linkto',0)->with('moduleLogo')->get()->toArray();

        $generalCollection = Module::where('template_id',$template_id)->where('general',1)->get()->toArray();
        $breadcrumbsRoute = "master-template3";
        $dataBreadcrum = [
                'template_id' => $template_id,
                'participant_id' => $participant->id,
                'asset_id' => $asset_id

                ];

        $MT_name = Template::where("id",$template_id)->first()->toArray();
        if($MT_name["module_launch_id"] != null && $MT_name["module_launch_id"] != 0){
            $module =Module::select("id")->where('template_id',$template_id)->with('moduleLogo')->where('id',$MT_name["module_launch_id"])->first();
            return $this->viewSubModules(nxb_encode($template_id),nxb_encode($invite_id),nxb_encode($module->id),$asset_id,$invite_asociated_key);
        }

        $user_id=$participant->invitee_id;
        $subparticipants = true;
        return view('MasterTemplate.modules.listing')->with(compact('subparticipants','user_id','participant','permission','MT_name','breadcrumbsRoute','dataBreadcrum','collection','template_id','generalCollection','asset_id','invite_asociated_key','app_id'));
    
    }

    /**
     * Display the Module resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function viewSubModules($template_id,$invited_id,$moduleid,$asset_id,$app_id,$invite_asociated_key=null)
    {
     
        $invited_id = nxb_decode($invited_id);
        $template_id = nxb_decode($template_id);
        $moduleid = nxb_decode($moduleid);
        $userid = \Auth::user()->id;
        
        $participant = subParticipants::where('id',$invited_id)->with('participant')->first();
        $permission = json_decode($participant->modules_permission,true);
        $FormTemplate = Form::where('template_id',$template_id)->where('linkto',$moduleid)->first();
        if ($FormTemplate) {
            $TemplateDesign = TemplateDesign::where('template_id', $FormTemplate->template_id)->where('user_id',$participant->invitee_id)->first(); 
            if (!isset($TemplateDesign)) {
                $TemplateDesign = TemplateDesign::where('template_id', $FormTemplate->template_id)->first(); 
            }
            //MasterTemplate Design Variable  -->
            $TD_variables = getTemplateDesign($TemplateDesign);
            
            
            $pre_fields = json_decode($FormTemplate->fields, true);
            
            // END: MasterTemplate Design Variable  -->
            $formid = $FormTemplate->id;
            $draft_id = 0;
            $answer_fields = null;
            $draft = ParticipantResponseDraft::where('subparticipant',$userid)->where('participant_id',$participant->participant_id)->where('form_id',$formid)->first();
            $participantRCount = ParticipantResponse::where('user_id',$userid)->where('participant_id',$participant->id)->where('form_id',$formid)->get()->count();
            if ($draft != null) {
                $answer_fields = json_decode($draft->fields, true);
                $draft_id = $draft->id;
            }
        	$subparticipants = true;

            return view('MasterTemplate.subparticipants.formView')->with(compact('subparticipants','participantRCount','draft','draft_id','answer_fields','participant','permission','moduleid','FormTemplate','TD_variables','template_id','pre_fields','formid','asset_id','invite_asociated_key'));
        }
        $collection = Module::where('template_id',$template_id)->where('linkto',$moduleid)->with('moduleLogo')->get()->toArray();
        $generalCollection = Module::where('template_id',$template_id)->where('general',1)->get()->toArray();
        
        $breadcrumbsRoute = "mastertemp-id-submodule3";
        $dataBreadcrum = [
                'template_id' => $template_id,
                'participant_id' => $participant->id,
                'module_id' => $moduleid,
                'asset_id' =>$asset_id,
                 'app_id' =>$app_id
                ];
        $MT_name = Template::select('name')->where("id",$template_id)->first()->toArray();
        $user_id=$participant->invitee_id;
        $subparticipants = true;

        return view('MasterTemplate.modules.listing')->with(compact('subparticipants','user_id','participant','permission','MT_name','breadcrumbsRoute','dataBreadcrum','collection', 'moduleid', 'template_id','generalCollection','asset_id','invite_asociated_key','app_id'));
    
    }
      /**
     * Save the record for Against participant.
     *
     * @return \Illuminate\Http\Response
     */
    public function saveResponse(Request $request)
    {
        $user_id = \Auth::user()->id;
        $owner_id = $request->owner_id;
        $template_id = $request->template_id;
        $participant_id = $request->participant_id;
        $form_id = $request->form_id;
        $fieldsarray = $request->fields;
        $draft = $request->draft_id;
        $asset_id = $request->asset_id;
        //decrypt_asset_id is for saving decrypted id in database
        $decrypt_asset_id = nxb_decode($asset_id);

         $subparticipant_id = $request->subparticipant_id;
    
          $sub = subParticipants::select('user_id')->where('id',$subparticipant_id)->first();
         
          $subId = $sub->user_id;
           
        //Only used asset when app owner is submiting the reponse for himself
        if ($request->asset) {
            $ownerAssets = $request->asset;
        }
        
        $invite_asociated_key = $request->invite_asociated_key;
        
        $tempModel = Template::where('id',$template_id)->first();
        
        
        $invoice = Form::select('invoice')->where('id',$form_id)->first();
        if ($participant_id) {
            $participantEntity = Participant::with('Invitee')->where("id",$participant_id)->first();
        }
        //If we are drafting the form
        if ($request->has("Draft")) {
                if ($draft == 0) {
                    $model = new ParticipantResponseDraft();
                    $model->form_id = $form_id;
                    if (!empty ($participant_id)) {
                        $model->participant_id =$participant_id;
                    }
                    $model->user_id = $owner_id;
                    $model->asset_id = $decrypt_asset_id;
                    $model->subparticipant = $user_id;
                    $model->subparticipant_id = $subparticipant_id;

                    \Session::flash('message', 'You have Drafted the form. You can open your form and submit it when you are ready.');
                }
                //Already darafted, Update daraft
                else{
                    $model = ParticipantResponseDraft::where('id',$draft)->first();
                    $model->form_id = $form_id;
                    if (!empty($participant_id)) {
                        $model->participant_id =$participant_id;
                    }
                    $model->user_id = $owner_id;
                    $model->asset_id = $decrypt_asset_id;
                    $model->subparticipant = $user_id;
                    $model->subparticipant_id = $subparticipant_id;


                    \Session::flash('message', 'You have Drafted the form. You can open your form and submit it when you are ready.');
                }
            }else{
            //Multiple assets. Add multiple data in participant response for app owner.
            if (isset($ownerAssets) && !empty($ownerAssets)) {
                foreach ($ownerAssets as $asset) {
                    $model = new ParticipantResponse();
                    $model->template_id = $template_id;
                    $model->asset_id = $asset;
                    $model->form_id = $form_id;
                    $model->user_id = $owner_id;
                    $model->subparticipant = $user_id;
                    $model->subparticipant_id = $subparticipant_id;


                    //Assigning the fields in json form
                    $model->fields = submitFormFields($request);
                    $model->save();
                }
            }else{
                $model = new ParticipantResponse();
                $model->template_id = $template_id;
                if (!empty ($participant_id)) {
                    $model->participant_id = $participant_id;
                }
                $model->form_id = $form_id;
                $model->user_id = $owner_id;
                $model->subparticipant = $user_id;
                $model->subparticipant_id = $subparticipant_id;
                $model->asset_id = $decrypt_asset_id;

                $draftexist = ParticipantResponseDraft::where('id',$draft)->exists();
                if ($draftexist) {
                    ParticipantResponseDraft::find($draft)->delete();
                } 
            }
            
            \Session::flash('message', 'Response has been stored successfully');
        }
        //Assigning the fields in json form
        $model->fields = submitFormFields($request);
        if ($request->has("Draft")) {
            if ($draft != 0) {
                $model->update();
            }else{
                $model->save();
            }
        }else{
            if (!isset($ownerAssets) && empty($ownerAssets)) {
                $model->save();
            }
    
            
            
            //&& $tempModel->invoice_to_event
            if($invoice->invoice > 0) {

                
                return redirect()->route('master-template-subParticipant-invoice',
                    ['id' => nxb_encode($invoice->invoice), 'form_id' => nxb_encode($form_id),
                        'template_id' => nxb_encode($template_id), 'asset_id' => $asset_id, 'participantId' => nxb_encode($participant_id),
                       'invite_asociated_key'=>$invite_asociated_key,'subId' => nxb_encode($subId)]);
            }
             if ($participant_id) {
             //  \Mail::to($participantEntity->Invitee->email)->send(new ParticipantResponseEmail($model,$participantEntity->Invitee));
            }
        }
        return redirect()->action(
            'UserController@index'
        );
    }
    /**
     * Show the History for the asset.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function viewResponse($id)
    {
    	$subParticipant_id =$id; 
        $id = nxb_decode($id);
        $user_id = \Auth::user()->id;
        $participantResponse = ParticipantResponse::where("user_id",$user_id)->where('participant_id', $id)->whereNotNull("subparticipant")->with("participant")->orderBy('id', 'desc')->get();
        return view('MasterTemplate.subparticipants.history')->with(compact('participantResponse','subParticipant_id'));
    }
      /**
     * Show the Own Response for Subparticipants.
     *
     * @param  int  $id- participant id
     * @return \Illuminate\Http\Response
     */
    public function viewOwnResponse($id)
    {
    	$subParticipant_id =$id; 
        $id = nxb_decode($id);
        $user_id = \Auth::user()->id;
        $participantResponse = ParticipantResponse::where("subparticipant",$user_id)->where('subparticipant_id', $id)->with("participant")->orderBy('id', 'desc')->get();
        return view('MasterTemplate.subparticipants.ownhistory')->with(compact('participantResponse','subParticipant_id'));
    }
    
    
    public function getSubParticipantInvoice($id,$form_id,$template_id,$asset_id,$participantId,$invite_asociated_key,$subId)
    {
        $id = nxb_decode($id);
        
        $user_id = \Auth::user()->id;
    
        $email = \Auth::user()->email;
        $subId = nxb_decode($subId);
        
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
            
            pullInvoice($id,nxb_decode($asset_id),$form_id,nxb_decode($template_id),$invite_asociated_key,$subId,$user_id);
            
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
                ->where('user_id', $user_id)
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
            'invite_asociated_key' => $invite_asociated_key
        ];
        
        return view('invoice.viewSubParticipantInvoice')->with(compact('FormTemplate','TD_variables','pre_fields','answer_fields','id','form_id','template_id','asset_id','invoiceId','participantId','invitedInvoice','subId','dataBreadcrum'));
        
    }
    public function Attachedhistory($id)
    {


        $subParticipant_id =$id;
        $id = nxb_decode($id);
        $subParticipants = subParticipants::where('id',$id)->first();
        $asset_id = $subParticipants->asset_id;
        $template_id = $subParticipants->template_id;
        $associated_history = json_decode($subParticipants->associated_history);

        $participantResponse = null;

        $user_id = \Auth::user()->id;
        if ($associated_history != null) {
            $participantResponse = ParticipantResponse::whereIn('id', $associated_history)->with("participant")->orderBy('id', 'desc')->get();
        }

        return view('MasterTemplate.participants.attachedHistory')->with(compact('participantResponse','asset_id','template_id'));
    }


}
