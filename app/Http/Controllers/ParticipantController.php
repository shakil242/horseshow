<?php
    /**
     * This is Form Controller to control all the Templates Forms in frontend project
     *
     * @author Faran Ahmed (Vteams)
     */
namespace App\Http\Controllers;

use App\AssetModules;
use App\AssetParent;
use App\FeedBack;
use App\InviteInvoices;
use App\Invoice;
use App\ManageShows;
use App\ParticipantAsset;
use App\SchedulerFeedBacks;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Form;
use App\Module;
use App\ParticipantResponse;
use App\ParticipantResponseDraft;
use App\FormType;
use App\Template;
use App\TemplateDesign;
use App\Asset;
use App\Participant;
use App\Mail\Participants;
use App\InvitedUser;
use App\Mail\InviteUser;
use App\Mail\ParticipantResponseEmail;
use Excel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use App\InvitedUsersReponseDraft;
use App\ParticipantProjectov;


class ParticipantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function index($template_id)
    {


        $isEmail = \Session('isEmployee');

        $user_id = \Auth::user()->id;

        $template_id = nxb_decode($template_id);
        $assetsArr = Asset::select('assets.id','assets.form_id','assets.fields')->where('assets.template_id',$template_id)->where('assets.user_id',$user_id)
            ->join('asset_modules', function ($join) {
                $join->on('assets.id', '=', 'asset_modules.asset_id');
            });

        $assetsSecondary = $assetsArr->pluck('assets.id');

        $assetPrimary = AssetParent::select('assets.id','assets.form_id','assets.fields')->whereIn('asset_id',$assetsSecondary)
          ->join('assets', function ($join) {
              $join->on('assets.id', '=', 'asset_parents.parent_id');
          })->groupBy('assets.id')->get();


        $assets = $assetsArr->get();

       // dd($assets->toArray());

        $pastParticipants = Participant::where('template_id',$template_id)->where('invitee_id',$user_id)->where('status',1)->groupBy('email')->get();
        return view('MasterTemplate.participants.index')->with(compact("pastParticipants","template_id","assets","assetPrimary"));
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

        // to find either penaly template exist or not
    
        $penaltyTemplate = Form::where('template_id', $template_id)->where('form_type', 6)->count();
        $manageShows = ManageShows::where('template_id',$template_id)->where('user_id',$user_id)->orderBy('id','Desc')->get();


        $this->validate($request, [
            // 'location' => "required",
            'asset' => "required",
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

        $project_overview = Asset::where('template_id',$template_id)
                  ->where('user_id',$user_id)
                  ->where('asset_type',F_PROJECT_OVERVIEW)
                  ->where('class_type','!=',1)
                  ->get();

        //Preparing the invite
    
    
        $uniqueId = time().mt_rand().$user_id;
    
    
        $assets = $request->get("asset");
        
        if(($key = array_search('All', $assets)) !== false) {
            unset($assets[$key]);
        }
        
        $assets = array_values($assets);
      
        if(isset($assets))
        {
            
         $modules = getAssetModules($assets,$uniqueId);
        
        }
        
        $data = $request->all();
           
        $data['asset'] = $assets;
        
        //$modules = Module::where('template_id',$template_id)->get();
        
        $templates = Template::where('id',$template_id)->with("associated_template")->first();
        
        $participantResponse = ParticipantResponse::where("template_id",$template_id)->with("participant")
            ->whereHas('participant', function ($query) use ($user_id,$assets) {
                                    $query->where('invitee_id', $user_id);
                                    $query->whereIn('asset_id', $assets);
                                    
                                })->get();
        $associated = $templates->associated_template;

        //List of profiles for this template
        $profile_forms = Form::where('template_id', $template_id)->where("form_type",PROFILE_ASSETS)->where("accessable_to",PROFILE_APP_NORMAL_USER)->pluck('name','id');
    
        \session()->regenerate();
        \Session::put('participantResponseKey', $uniqueId);

        \Session::put('participantResponse', $participantResponse);
        \Session::put('excelData', $excelData);
        \Session::put('data', $data);
        \Session::put('modules', $modules);
        \Session::put('associated', $associated);
        \Session::put('penaltyTemplate', $penaltyTemplate);
        
        return view('MasterTemplate.participants.permission')->with(compact("participantResponse",'project_overview',"excelData","data","modules",'associated','penaltyTemplate','penaltyInvoice','manageShows','profile_forms'));
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
        $pastParticipats = $request->get('pastParticipats');
        $arr=[];
        $arr_mod = $request->get('module');
        //$formsAttached = count($arr_mod);
        $arr_module = getModuels($arr_mod);

        $array_asset = $request->get('asset');
        $array_invoiceAttach = $request->get('invoiceAttach');
        
        $asset_str = json_encode($array_asset);
        if(count($arr_module) > 0) {
            foreach ($arr_module as $key => $value) {
                // if ($value == 2) {
                $Forms = Form::where('linkto', $key)
                    ->where('scheduler', 1)
                    ->first();
                if ($Forms) {
                    if (isset($data['emailName']) && $data['emailName'] != null) {
                        foreach ($data['emailName'] as $NP) {
                            if ($NP['email'] != "") {
                                $participant = new ParticipantAsset();
                                $participant->form_id = $Forms->id;
                                $participant->template_id = $request->template_id;
                                $participant->invitee_id = $user_id;
                                $participant->email = $NP['email'];
                                $participant->assets = $asset_str;
                                $participant->save();
                            }
                        }
                    }
                    if (isset($data['pastParticipats']) && $data['pastParticipats'] != null) {
                        foreach ($pastParticipats as $PP) {
                            $participant = new ParticipantAsset();
                            $participant->form_id = $Forms->id;
                            $participant->template_id = $request->template_id;
                            $participant->invitee_id = $user_id;
                            $participant->email = $PP;
                            $participant->assets = $asset_str;
                            $participant->save();
                        }
                    }
                }
                //    }
            }
        }else
        {

            \Session::flash('message', 'There is no module associated with this request');
            return redirect()->route('master-template-invite-participants', ['id' => nxb_encode($request->template_id)]);

        }
        //For new participants
        if (isset($data['emailName']) && $data['emailName'] != null) {
            foreach ($data['emailName'] as  $NP){
                //$NP is New Participants
                if ($NP['email'] != "" && $NP['name'] != "" ) {
                    invoiceInvitation($array_invoiceAttach,$array_asset,$request,$NP['email']);
                     $sendmail = $this->createAndSendMail($array_asset,$request,$NP['email'],$NP['name']);
                }
            }
        }
        //In case of past participants Invited
        if (isset($data['pastParticipats']) && $data['pastParticipats'] != null) {
            foreach ($pastParticipats as  $PP){
                //$PP is Past Participants
                invoiceInvitation($array_invoiceAttach,$array_asset,$request,$PP);
               $sendmail = $this->createAndSendMail($array_asset,$request,$PP,getUserNamefromEmail($PP));
            }
        }
            
        if ($sendmail) {
    
            \Session::put('penaltyTemplate', '');
            \Session::put('penaltyInvoice', '');
    
            \Session::flash('message', 'Invite has been send to User(s) successfully');
                return redirect()->action('UserController@index');
        }
        
    }
    public function createAndSendMail($array_asset,$data,$email,$name){



        $isEmail = \Session('isEmployee');
        $userEmail = \Auth::user()->email;

        $formsAttached = count($data->get('module'));
        if($isEmail==1) {
            $user_id = getAppOwnerId($userEmail,$data->template_id);
            $employee_id = \Auth::user()->id;
        }
        else {
            $user_id = \Auth::user()->id;
            $employee_id = 0;
        }

        $uniqueId = \Session('participantResponseKey');
        
        //List of profiles for this template
        if($data->allowed_invite_profiles){
            $allowed_profiles = json_encode($data->allowed_invite_profiles);
        }else{
            $allowed_profiles = null;
        }
        
        foreach($array_asset as $asset) {
            $model = new Participant();
            $model->template_id = $data->template_id;
            $model->invitee_id = $user_id;
            $model->asset_id = $asset;
            $model->email = $email;
            $model->name = $name;
            $model->forms_attached = $formsAttached;
            $model->show_id = $data->get('show_id');
            $model->invited_profiles = $allowed_profiles;
            $model->invite_asociated_key = $uniqueId;

            //Getting location of asset
            $googlemap = GetAssetLocationfromId($asset,5);
            if(!is_string($googlemap)){
                $model->location = $googlemap['location'];
                $model->latitude = $googlemap['latitude'];
                $model->longitude = $googlemap['longitude'];
                $model->place_id = $googlemap['place_id'];
            }
            $model->description = $data->get('description');
            
            if ($data->get('attachmentHistory') != null) {
               $model->associated_history = json_encode($data->get('attachmentHistory')); 
            }
            if ($data->get('drp_permission') != null) {
                $model->allowed_time = $data->get('drp_permission');
            }else{
                $model->allowed_time = $data->get('permission');
            }
    
           // $assetModules = AssetModules::select('modules_permission')->where('asset_id',$asset)->first();


            //print_r($data->module);exit;

            $arr_module = getModuels($data->module);

            $modules_permission = json_encode($arr_module);


            $model->modules_permission = $modules_permission;
           //$model->invited_master_template = $data->get('invited_master_template');
    
            $penaltyInvoice = \Session('penaltyInvoice');
            
            if($penaltyInvoice > 0) {
                if ($data->get('penaltyDate') != '') {
                    $date = date('Y-m-d', strtotime($data->get('penaltyDate')));
                    $model->penalty_date = $date;
                    $model->is_penalty = 1;
                }
            }

            $model->employee_id =  $employee_id;

            $model->save();
              $projectOVerview = $data->get('project_overview');
                if(count($projectOVerview)>0){
                  foreach ($projectOVerview as $overview) {
                    $pov = new ParticipantProjectov();
                    $pov->participant_invited_id = $model->id;
                    $pov->project_overview_id = $overview;
                    $pov->save();
                  }
                }
        }
        
        $email = $model->email;
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
                    $model->employee_id =  $employee_id;
                    $invited_user->save();
                }
               
                
            \Mail::to($email)->send(new InviteUser($invited_user));
        }

        //Send email for the asset invite.
         \Mail::to($email)->send(new Participants($model,$array_asset));
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
       $participant = Participant::where('id',$invite_id)->first();

        $invitee_id = $participant->invitee_id;

       $email = User::where('id',$invitee_id)->pluck('email')->first();


        $permission = json_decode($participant->modules_permission,true);
        //Moduleswith(['emails' => function($q){
        $template_id = nxb_decode($template_id);

        $app_id = InvitedUser::where('email',$email)->where('template_id',$template_id)->pluck('id')->first();

        $collection = Module::where('template_id',$template_id)->where('linkto',0)->with(['moduleLogo'=>function($q) use($app_id,$invitee_id){
            $q->where('app_id', $app_id);
            $q->where('user_id', $invitee_id);

        }])->get()->toArray();


        $generalCollection = Module::where('template_id',$template_id)->where('general',1)->get()->toArray();
        $breadcrumbsRoute = "master-template2";
        $dataBreadcrum = [
                'template_id' => $template_id,
                'participant_id' => $participant->id,
                'asset_id' => $asset_id
                ];

        $MT_name = Template::where("id",$template_id)->first()->toArray();
        if($MT_name["module_launch_id"] != null && $MT_name["module_launch_id"] != 0){
            $module =Module::select("id")->where('template_id',$template_id)->where('id',$MT_name["module_launch_id"])->first();
            return $this->viewSubModules(nxb_encode($template_id),nxb_encode($invite_id),nxb_encode($module->id),$asset_id,$app_id,$invite_asociated_key);
        }
        $user_id=$participant->invitee_id;


        return view('MasterTemplate.modules.listing')->with(compact('user_id','participant','permission','MT_name','breadcrumbsRoute','dataBreadcrum','collection','template_id','generalCollection','asset_id','invite_asociated_key','app_id'));
    
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
        $app_idd = nxb_decode($app_id);
        $userid = \Auth::user()->id;
        $participant = Participant::where('id',$invited_id)->first();
        $permission = json_decode($participant->modules_permission,true);
        $FormTemplate = Form::where('template_id',$template_id)->where('linkto',$moduleid)->first();
        if ($FormTemplate) {
            
            $TemplateDesign = getTemplateDesignQry($FormTemplate->template_id,$participant->invitee_id);
            //MasterTemplate Design Variable  -->
            $TD_variables = getTemplateDesign($TemplateDesign);

            $pre_fields = json_decode($FormTemplate->fields, true);
            
            // END: MasterTemplate Design Variable  -->
            $formid = $FormTemplate->id;
            $draft_id = 0;
            $answer_fields = null;
            //$draft = ParticipantResponseDraft::where('user_id',$userid)->where('participant_id',$participant->id)->where('form_id',$formid)->whereNotNull('subparticipant')->first();
            $draft = ParticipantResponseDraft::where('user_id',$userid)->where('participant_id',$participant->id)->where('form_id',$formid)->first();
            $participantRCount = ParticipantResponse::where('user_id',$userid)->where('participant_id',$participant->id)->where('form_id',$formid)->get()->count();
            if ($draft != null) {
                $answer_fields = json_decode($draft->fields, true);
                $draft_id = $draft->id;
            }
            $participant_allow = 1;
            return view('MasterTemplate.participants.formView')->with(compact('participant_allow','participantRCount','draft','draft_id','answer_fields','participant','permission','moduleid','FormTemplate','TD_variables','template_id','pre_fields','formid','asset_id','invite_asociated_key'));
        }
        $collection = Module::where('template_id',$template_id)->where('linkto',$moduleid)->with(['moduleLogo'=>function($q) use($app_idd){
            $q->where('app_id', $app_idd);
        }])->get()->toArray();
        $generalCollection = Module::where('template_id',$template_id)->where('general',1)->get()->toArray();
        
        $breadcrumbsRoute = "mastertemp-id-submodule2";
        $dataBreadcrum = [
                'template_id' => $template_id,
                'participant_id' => $participant->id,
                'module_id' => $moduleid,
                'asset_id' =>$asset_id,
                'app_id' =>$app_id

        ];
        $MT_name = Template::select('name')->where("id",$template_id)->first()->toArray();
        $user_id=$participant->invitee_id;
        return view('MasterTemplate.modules.listing')->with(compact('user_id','participant','permission','MT_name','breadcrumbsRoute','dataBreadcrum','collection', 'moduleid', 'template_id','generalCollection','asset_id','invite_asociated_key','app_id'));
    
    }
        /**
     * Display the Assets. Read only
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function viewAsset($accet_id)
    {
        $accet_id = nxb_decode($accet_id);
        $Asset = Asset::findOrFail($accet_id);
        $template_id = $Asset->template_id;
        $form_id = $Asset->form_id;
        $FormTemplate = Form::where('id',$form_id)->first();
        $TemplateDesign = TemplateDesign::where('template_id', $template_id)->first(); 
            $TD_variables = null;
            $pre_fields = null;
            $formid = null;
           if ($FormTemplate) {
                //MasterTemplate Design Variable  -->
                $TD_variables = getTemplateDesign($TemplateDesign);
                $pre_fields = json_decode($FormTemplate->fields, true);
                $answer_fields = json_decode($Asset->fields, true);

                // END: MasterTemplate Design Variable  -->
                $formid = $FormTemplate->id;
           }
        return view('MasterTemplate.participants.viewaccets')->with(compact('Asset','answer_fields','FormTemplate','TD_variables','template_id','pre_fields','formid'));
    
    }
    
    /**
     * Display the assets detail for master template.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $id = nxb_decode($id);
        $userid = \Auth::user()->id;
        $useremail = \Auth::user()->email;
        $participant_collection = Participant::where('id',$id)->with("InvitedOnAsset",'Invitee')->first();
        return view('MasterTemplate.participants.assetdetails')->with(compact("participant_collection"));
    
    }

    /**
     * Save the record for Against participant.
     *
     * @return \Illuminate\Http\Response
     */
    public function saveResponse(Request $request)
    {

        $isEmail = \Session('isEmployee');
        $userEmail = \Auth::user()->email;


        if($isEmail==1) {
            $user_id = getAppOwnerId($userEmail,$request->template_id);
            $employee_id = \Auth::user()->id;
        }
        else {
            $user_id = \Auth::user()->id;
            $employee_id = 0;
        }

        $template_id = $request->template_id;
        $participant_id = $request->participant_id;
        $form_id = $request->form_id;
        $fieldsarray = $request->fields;
        $draft = $request->draft_id;
        $asset_id = $request->asset_id;
        //decrypt_asset_id is for saving decrypted id in database
        $decrypt_asset_id = nxb_decode($asset_id);
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
                //APP OWNER
                if ($request->app_owner == 1) {
                    if ($draft == 0) {
                        $model = new InvitedUsersReponseDraft();
                        $model->form_id = $form_id;
                        $model->user_id = $user_id;
                        $model->fields = submitFormFields($request);
                        $model->save();

                        \Session::flash('message', 'You have Drafted the form. You can open your form and submit it when you are ready.');
                        return redirect()->action(
                            'UserController@index'
                        );
                    }
                    //Already darafted, Update daraft
                    else{
                        $model = InvitedUsersReponseDraft::where('id',$draft)->first();
                        $model->form_id = $form_id;
                        $model->user_id = $user_id;
                        $model->fields = submitFormFields($request);
                        $model->update();
                        \Session::flash('message', 'You have Drafted the form. You can open your form and submit it when you are ready.');
                        return redirect()->action(
                            'UserController@index'
                        );
                    }
                    //end Model APP OWNER
                }else{
                    if ($draft == 0) {
                        $model = new ParticipantResponseDraft();
                        $model->form_id = $form_id;
                        if (!empty ($participant_id)) {
                            $model->participant_id =$participant_id;
                        }
                        $model->user_id = $user_id;
                        $model->asset_id = $decrypt_asset_id;
                        $model->fields = submitFormFields($request);
                        $model->employee_id = $employee_id;

                        $model->save();

                        \Session::flash('message', 'You have Drafted the form. You can open your form and submit it when you are ready.');
                        return redirect()->action(
                            'UserController@index'
                        );
                    }
                    //Already darafted, Update daraft
                    else{
                        $model = ParticipantResponseDraft::where('id',$draft)->first();
                        $model->form_id = $form_id;
                        if (!empty($participant_id)) {
                            $model->participant_id =$participant_id;
                        }
                        $model->user_id = $user_id;
                        $model->asset_id = $decrypt_asset_id;
                        $model->fields = submitFormFields($request);
                        $model->employee_id = $employee_id;

                        $model->update();
                        \Session::flash('message', 'You have Drafted the form. You can open your form and submit it when you are ready.');
                        return redirect()->action(
                            'UserController@index'
                        );
                    }
                }
                
            }else{
            //Multiple assets. Add multiple data in participant response for app owner.
            if (isset($ownerAssets) && !empty($ownerAssets)) {
                foreach ($ownerAssets as $asset) {
                    $model = new ParticipantResponse();
                    $model->template_id = $template_id;
                    $model->asset_id = $asset;
                    $model->form_id = $form_id;
                    $model->user_id = $user_id;
                    $model->employee_id = $employee_id;

                    //Assigning the fields in json form
                    $model->fields = submitFormFields($request);
                    $model->save();
                }
                //App owner return
                \Session::flash('message', 'Response has been stored successfully');
                return redirect()->action(
                    'UserController@index'
                );
            }else{
                $model = new ParticipantResponse();
                $model->template_id = $template_id;
                if (!empty ($participant_id)) {
                    $model->participant_id = $participant_id;
                }
                $model->asset_id = nxb_decode($request->asset_id);
                $model->form_id = $form_id;
                $model->user_id = $user_id;
                $model->employee_id = $employee_id;

                if ($request->app_owner == 1) {
                    $draftexist = InvitedUsersReponseDraft::where('id',$draft)->exists();
                    if ($draftexist) {
                        InvitedUsersReponseDraft::find($draft)->delete();
                    } 
                }else{
                     $draftexist = ParticipantResponseDraft::where('id',$draft)->exists();
                    if ($draftexist) {
                        ParticipantResponseDraft::find($draft)->delete();
                    } 
                }
            }
            
            \Session::flash('message', 'Response has been stored successfully');
        }
        //Assigning the fields in json form
        $model->fields = submitFormFields($request);
        
            if (!isset($ownerAssets) && empty($ownerAssets)) {
                $model->save();
            }
            if($invoice->invoice > 0 && $tempModel->invoice_to_event > 0) {
                return redirect()->route('master-template-billing-invoice-form',
                    ['id' => nxb_encode($invoice->invoice), 'form_id' => nxb_encode($form_id),
                        'template_id' => nxb_encode($template_id), 'asset_id' => $asset_id, 'participantId' => nxb_encode($participant_id),
                        'appOwnerRequest'=>0,'invite_asociated_key'=>$invite_asociated_key,'responseId' => nxb_encode($model->id)]);
            }
             if ($participant_id) {
             //  \Mail::to($participantEntity->Invitee->email)->send(new ParticipantResponseEmail($model,$participantEntity->Invitee));
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
    public function history($id)
    {
         $id = nxb_decode($id);
        $user_id = \Auth::user()->id;
        $aset = Asset::where('id',$id)->first();
        $asset_id = $aset->id;
        $template_id = $aset->template_id;
        $participantResponse = ParticipantResponse::with('form')->where("user_id",$user_id)
                                ->with("participant")
                                ->where("subparticipant_id",NULL)
                                ->whereHas('participant', function ($query) use ($id) {
                                    $query->where('asset_id', $id);
                                })->orderBy('id', 'desc')->get();
        return view('MasterTemplate.participants.history')->with(compact('participantResponse','asset_id','template_id'));
    }
        /**
     * Show the Attached History for the asset.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function Attachedhistory($id)
    {
        $id = nxb_decode($id);
        $user_id = \Auth::user()->id;
        $participant = Participant::where('id',$id)->first();
        $asset_id = $participant->asset_id;
        $asset_allowed = json_decode($participant->associated_history);
        $template_id = $participant->template_id;
        if ($asset_allowed != null) {
           $participantResponse = ParticipantResponse::whereIn('id',$asset_allowed)->with("participant")->whereHas('participant', function ($query) use ($asset_id) {
                                    $query->where('asset_id', $asset_id);
                                })->get();
        }else{
            $participantResponse = null;
        }
        return view('MasterTemplate.participants.attachedHistory')->with(compact('participantResponse','asset_id','template_id'));
    }
    
    /**
     * Display the Participant Response All. Read only
     *
     * @param  int  $response_id
     * @return \Illuminate\Http\Response
     */
    public function viewParticipantResponseAll($response_id)
    {
         $response_id = nxb_decode($response_id);

        $Asset = ParticipantResponse::findOrFail($response_id);
        $template_id = $Asset->template_id;
        $form_id = $Asset->form_id;
        $FormTemplate = Form::where('id',$form_id)->first();
        $TemplateDesign = TemplateDesign::where('template_id', $template_id)->first(); 
        
            $TD_variables = null;
            $pre_fields = null;
            $formid = null;
           if ($FormTemplate) {
                //MasterTemplate Design Variable  -->
                $TD_variables = getTemplateDesign($TemplateDesign);
                $pre_fields = json_decode($FormTemplate->fields, true);
                $answer_fields = json_decode($Asset->fields, true);

                // END: MasterTemplate Design Variable  -->
                $formid = $FormTemplate->id;
           }
        return view('MasterTemplate.participants.viewaccetshistory')->with(compact('Asset','answer_fields','FormTemplate','TD_variables','template_id','pre_fields','formid','response_id'));
    
    }

    /**
     * Display the Participant Response. Read only
     *
     * @param  int  $response_id
     * @return \Illuminate\Http\Response
     */
    public function viewParticipantResponse($response_id)
    {
        $accet_id = nxb_decode($response_id);
        $Asset = Asset::findOrFail($response_id);
        $template_id = $Asset->template_id;
        $form_id = $Asset->form_id;
        $FormTemplate = Form::where('id',$form_id)->first();
        $TemplateDesign = TemplateDesign::where('template_id', $template_id)->first(); 
            $TD_variables = null;
            $pre_fields = null;
            $formid = null;
           if ($FormTemplate) {
                //MasterTemplate Design Variable  -->
                $TD_variables = getTemplateDesign($TemplateDesign);
                $pre_fields = json_decode($FormTemplate->fields, true);
                $answer_fields = json_decode($Asset->fields, true);

                // END: MasterTemplate Design Variable  -->
                $formid = $FormTemplate->id;
           }
        return view('MasterTemplate.participants.viewaccets')->with(compact('Asset','answer_fields','FormTemplate','TD_variables','template_id','pre_fields','formid'));
    
    }
    /**
     * Display the Participant Overall response on one master template invited by one app user.
     *
     * @param  int  $response_id
     * @return \Illuminate\Http\Response
     */
    public function overallResponse($template_id,$invitee_id)
    {
        $template_id = nxb_decode($template_id);
        $invitee_id = nxb_decode($invitee_id);
        $user_id = \Auth::user()->id;
        \DB::enableQueryLog();
        $participantResponse = ParticipantResponse::where("template_id",$template_id)->where("user_id",$user_id)->with("participant")->whereHas('participant', function ($query) use ($invitee_id) {
                                    $query->where('invitee_id', $invitee_id);
                                })->get();
        dd(
            \DB::getQueryLog()
        );
        $forms = ParticipantResponse::select('form_id')->where("template_id",$template_id)->where("user_id",$user_id)->with("participant")->whereHas('participant', function ($query) use ($invitee_id) {
                                    $query->where('invitee_id', $invitee_id);
                                })->groupBy('form_id')->get();

        return view('MasterTemplate.responses.allHistory')->with(compact('invitee_id','user_id','forms','participantResponse','template_id'));
    }
    /**
     *compairFormReport
     * Show the Comaprison of all the answers given on the form by users graphically.
     *
     * @return response in reports.index view
     */
    public function compairFormReport($form_id,$invitee_id)
    {
        $form_id = nxb_decode($form_id);
        $invitee_id = nxb_decode($invitee_id);
        $user_id = \Auth::user()->id;
        $selectedAssets=array();
        $participantResponse = ParticipantResponse::where('form_id',$form_id)
        //Here user id is owner id
        ->where('user_id',$user_id)->with("participant")
        ->whereHas('participant', function ($query) use ($invitee_id) {
                    $query->where('invitee_id', $invitee_id);
                })->get();
        $forms =  Form::where('id',$form_id)->first();
        $asset_ids=array();
        foreach ($participantResponse as $value) {
            $asset_ids[]=$value->participant->asset_id; 
        }
        $assets =array_unique($asset_ids);
        $formfields = json_decode($forms->fields);
        $breadcrumb = "template-overall-graphical";
        return view('reports.index')->with(compact('invitee_id',"breadcrumb","selectedAssets","assets","forms","participantResponse","formfields"));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
    }
    
    /**
     * coded by Shakil
     *
     * @return string
     */
    public function getFeedBack($asset_id)
    {
        $asset_id = nxb_decode($asset_id);
    
        $user_id = \Auth::user()->id;
        
        $feedBack = SchedulerFeedBacks::where('feed_back_type', '!=' , 14)->where('asset_id',$asset_id)->where('user_id',$user_id)->get();
        return view('feedBack.invited.index')->with(compact("feedBack",'asset_id'));
    }
    
    
    public function viewFeedBack($feedBackId,$assethistory=null)
    {
        $feedBackId = nxb_decode($feedBackId);
        $Asset = SchedulerFeedBacks::findOrFail($feedBackId);
        
        $template_id = $Asset->template_id;
         $form_id = $Asset->form_id;
        $FormTemplate = Form::where('id',$form_id)->first();
        
        $TemplateDesign = TemplateDesign::where('template_id', $template_id)->first();
        
        $TD_variables = null;
        $pre_fields = null;
        $formid = null;
        if ($FormTemplate) {
            //MasterTemplate Design Variable  -->
            $TD_variables = getTemplateDesign($TemplateDesign);
            $pre_fields = json_decode($FormTemplate->fields, true);
            $answer_fields = json_decode($Asset->fields, true);
            
            // END: MasterTemplate Design Variable  -->
            $formid = $FormTemplate->id;
        }
        if ($assethistory) {
            return view('MasterTemplate.assets.shows.viewFeedBack')->with(compact('Asset','answer_fields','FormTemplate','TD_variables','template_id','pre_fields','formid'));
        }else{
            return view('feedBack.invited.viewFeedBack')->with(compact('Asset','answer_fields','FormTemplate','TD_variables','template_id','pre_fields','formid'));
        }
        
    }
    
    
    public function getFeedBackMaster($template_id,$spectatorId=null)
    {
        
        $template_id = nxb_decode($template_id);
        $user_id = \Auth::user()->id;
        $spectatorId = nxb_decode($spectatorId);
        $feedBackType = 1;

        $show_ids = ManageShows::select('id')->where('user_id',$user_id)->where('template_id',$template_id)->get()->toArray();


        if($spectatorId) {
            $feedBack = SchedulerFeedBacks::with('show')->where('template_id', $template_id)->where('feed_back_type',"!=", JUDGES_FEEDBACK)->where('spectator_id', $spectatorId)->whereIn('show_id',$show_ids);
        }
        else {
            $feedBack = SchedulerFeedBacks::with("horse",'show')->where('template_id', $template_id)->where('feed_back_type',"!=", JUDGES_FEEDBACK)->whereIn('show_id',$show_ids);
        }
        
        if($feedBack->count()>0)
            $feedBack = $feedBack->get();
        return view('feedBack.MasterTemplate.index')->with(compact("feedBack",'template_id','feedBackType'));
    }

    public function getJudgesFeedBack($template_id,$spectatorId=null)
    {
        $user_id = \Auth::user()->id;
        $template_id = nxb_decode($template_id);
        $spectatorId = nxb_decode($spectatorId);
        $feedBackType = JUDGES_FEEDBACK;
        $show_ids = ManageShows::select('id')->where('user_id',$user_id)->where('template_id',$template_id)->get()->toArray();


        if($spectatorId) {
            $feedBack = SchedulerFeedBacks::with('show')->where('template_id', $template_id)->where('feed_back_type', JUDGES_FEEDBACK)->whereIn('show_id',$show_ids);
        }
        else {
            $feedBack = SchedulerFeedBacks::with("horse",'show')->where('template_id', $template_id)->where('feed_back_type', JUDGES_FEEDBACK)->whereIn('show_id',$show_ids);
        }

        if($feedBack->count()>0)
            $feedBack = $feedBack->get();

        return view('feedBack.MasterTemplate.index')->with(compact("feedBack","template_id",'feedBackType'));
    }




    public function secondarAssets($parent_id)
    {

        $assetSecondary = AssetParent::select('assets.id','assets.form_id','assets.fields')->where('parent_id',$parent_id)
            ->join('assets', function ($join) {
                $join->on('assets.id', '=', 'asset_parents.asset_id');
            })
            ->join('asset_modules', function ($join) {
                $join->on('assets.id', '=', 'asset_modules.asset_id');
            })
            ->groupBy('assets.id')
            ->get();


        return view('MasterTemplate.participants.secondaryAssets')->with(compact("assetSecondary"));



    }

    public function submitFeedBackRequest(Request $request)
    {
        $user_id = \Auth::user()->id;
        \Session::flash('message', 'Access has been provided to selected participants');
        return  SchedulerFeedBacks::whereIn('id', $request->ids)->update(['rider_allowed_to_view' => '1']);
    }


    public function getRiderJudgesFeedBack($asset_id)
    {
        $asset_id = nxb_decode($asset_id);

        $user_id = \Auth::user()->id;

        $feedBack = SchedulerFeedBacks::where('feed_back_type', JUDGES_FEEDBACK)->where('asset_id',$asset_id)->where('user_id',$user_id)->where('rider_allowed_to_view',1)->get();
        return view('feedBack.invited.index')->with(compact("feedBack",'asset_id'));
    }




}
