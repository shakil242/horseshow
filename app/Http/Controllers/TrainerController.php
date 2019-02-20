<?php
/***********
@aurthor: Faran Ahmed Khan (Vteams)
 Show Controller  will have all the functions related to show. Like listing, save response

************/
namespace App\Http\Controllers;

use App\AppownerBankAccountInformation;
use App\Invoice;
use App\ManageShowOrderSupplies;
use App\PaypalAccountDetail;
use Illuminate\Http\Request;
use App\InvitedUser;
use App\ManageShows;
use App\Form;
use App\TemplateDesign;
use App\ManageShowsRegister;
use App\Asset;
use App\ShowAssetInvoice;
use App\AssetModules;
use App\Participant;
use App\AdditionalCharges;
use App\ShowPrizingListing;
use App\ClassHorse;
use App\ShowScratchPenalty;
use App\ManageShowTrainer;
use App\ManageShowTrainerSplit;
use App\SchedulerFeedBacks;
use App\Mail\TrainerScratchHorse;

class TrainerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $email = \Auth::user()->email;
        $user_id = \Auth::user()->id;

        $horseContains = 0;

        $horseCollection = InvitedUser::where('status',1)->where('block',0)->where('email',$email)
                           ->whereHas("template",function ($query){
                           $query->where('category', CONST_HORSE_TEMPLATE);
                         })->orderBy('id','desc')->pluck('template_id');

        if(count($horseCollection)>0) {
            $horseContains = Asset::whereIn('template_id', $horseCollection)->where('user_id',$user_id)->count();
        }
        $collection = ManageShows::with("template","appowner");


        if($request->ajax()) {
            $query =  $request->get('query');
            $collection = $collection->where(function($q) use ($query) {
                $q->where('title', 'LIKE', '%'.$query.'%')
                    ->orWhere('location', 'LIKE', '%'.$query.'%')
                    ->orWhere('show_type', 'LIKE', '%'.$query.'%');
            });
        }


        $collection = $collection->where("type",SHOW_TYPE_TRAINER)->whereHas("template",function ($query){
                                     $query->where('category', TRAINER); 
                                })->whereHas("appowner",function ($query){
                                     $query->where('status',1)->where('block',0);
                                })->orderBy('date_from','desc')->get();
        if($request->ajax()) {
            return view('shows.trainers.search_view')->with(compact("collection", "horseCollection", "horseContains"));
        }



        return view('trainer.index')->with(compact("collection","horseCollection","horseContains"));
    }


            /**
     * Split invoice save in database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function splite_trainers_invoice(Request $request)
    {
        $messages = [
                'MSR_ids.required' => 'Kindly select atleast 1 user to split the invoice amoung them!',
            ];
        $this->validate($request, [
                'additional_price' => 'required|numeric|min:1',
                'MSR_ids' => 'required' 
            ],$messages);
        $user_id = \Auth::user()->id;
        $uniqueId = time().mt_rand().$user_id;
        if (count($request->MSR_ids) > 0) {
            //$userCount = splitBetweenUsers($request->MSR_ids);
            $userCount = count($request->MSR_ids);
            foreach ($request->MSR_ids as $class_reg_id) {
                $MSTS = new ManageShowTrainerSplit();
                $MSTS->class_horses_id = $class_reg_id;
                $MSTS->trainer_user_id = $user_id;
                $MSTS->show_id = $request->show_id;
                $MSTS->additional_fields = json_encode($request->additional);
                $MSTS->divided_amoung = $userCount;
                $MSTS->unique_batch = $uniqueId;
                $MSTS->total_amount = $request->additional_price;
                $MSTS->save();
            }
            \Session::flash('message', "You have Split the invoice between $userCount users.");

        }else{
            \Session::flash('message', "There was some error while spliting your invoice. Please try again!");
        }
        return redirect()->route('TrainerController-index');
       
    }

    /**
     * Show the form for creating a new resource. Registration from will be shown for the template
     *
     * @return \Illuminate\Http\Response
     */
    public function create($show_id)
    {
        $user_id = \Auth::user()->id;
        $show_id = nxb_decode($show_id);

        $answer_fields = null;
        $trainer_id =null;

        $show = ManageShows::where('id',$show_id)->first();
        $FormTemplate = Form::where('template_id',$show->template_id)->where('form_type',F_REGISTRATION)->first();

        $template_id = $show->template_id;

        $TemplateDesign = TemplateDesign::where('template_id', $show->template_id)->first();


        $manageShows = ManageShowsRegister::where('user_id',$user_id)->where('manage_show_id',$show_id)->orderBy('id','DESC')->first();
      if(!is_null($manageShows))
      {
          $show_id = $manageShows->manage_show_id;
           $trainer_id[] = $manageShows->trainer_id;
          /********* Form display start*************/
          $FormTemplate = Form::where('template_id',$template_id)->where('form_type',F_REGISTRATION)->first();
          $TemplateDesign = TemplateDesign::where('template_id', $template_id)->first();
          $TD_variables = null;
          $pre_fields = null;
          $formid = null;
          $answer_fields = null;
          if ($FormTemplate) {
              //MasterTemplate Design Variable  -->
              $TD_variables = getTemplateDesign($TemplateDesign);
              $pre_fields = json_decode($FormTemplate->fields, true);
              if ($manageShows) {
                  $answer_fields = json_decode($manageShows->fields, true);
              }
              // END: MasterTemplate Design Variable  -->
              $formid = $FormTemplate->id;
          }
      }else {

          $TD_variables = null;
          $pre_fields = null;
          $formid = null;
          if ($FormTemplate) {
              //MasterTemplate Design Variable  -->
              $TD_variables = getTemplateDesign($TemplateDesign);
              $pre_fields = json_decode($FormTemplate->fields, true);
              // END: MasterTemplate Design Variable  -->
              $formid = $FormTemplate->id;
          }
      }
        $trainers = ManageShowTrainer::with("user")->where("manage_show_id",$show_id)->get();


        return view('trainer.participate.register')->with(compact("trainers","show_id",'FormTemplate','TD_variables','pre_fields','answer_fields','template_id','trainer_id'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user_id = \Auth::user()->id;
        if(isset($request->trainer)){
            $trainer = $request->trainer;
        }else{
            $trainer = null;
        }
        if(!isset($request->step2)){
            //step1
            $template_id = $request->template_id;
            $show_id = $request->show_id;
            $model = new ManageShowsRegister();
            $model->manage_show_id = $show_id;
            $model->type = SHOW_TYPE_TRAINER;
            $model->trainer_id = $trainer;
            $model->user_id = $user_id;
            $model->fields = submitFormFields($request);
            $model->save();
            //MSR manage show register
            $MSR = $model->id;
            \Session::flash('message', 'Step 1 of the registration has been saved.');
            
            //Step 2
            $show = ManageShows::where('id',$show_id)->first();
            $collection = Asset::with("ShowAssetInvoice")->where('template_id',$template_id)->where('user_id',$show->user_id)->where('asset_type',0)
                                    ->whereHas("SchedulerRestriction",function ($query) use ($show_id) {
                                         $query->where('show_id',$show_id);
                                    })->get();
            $OwnerHorses = Asset::with("template")->where('user_id',$user_id)
                         ->where('asset_type','!=',2)
                         ->whereHas("template",function ($query) use ($show_id) {
                                       $query->where('category',CONST_HORSE_TEMPLATE);
                                  })->get();
            $additional_price = AdditionalCharges::where("template_id",$template_id)->where('app_id',$show->app_id)->get();
             $riderHorses = Asset::with("template")->where('user_id',$user_id)->where('asset_type',2)
            ->whereHas("template",function ($query) use ($show_id) {
                $query->where('category',CONST_HORSE_TEMPLATE);
            })->get();

            return view('trainer.participate.assets')->with(compact("OwnerHorses","MSR",'show','collection','riderHorses','additional_price'));
        }else{

            $uniqueId = time().mt_rand().$user_id;
           $additionalC = json_encode($request->additional);

            $invoice =new Invoice();
           // dd($request->toArray());

            $show = ManageShows::select('user_id')->where('id',$request->show_id)->first();


            $invoice->template_id = $request->template_id;

            $invoice->show_id = $request->show_id;
            //$show_id = $request->assets;


            $invoice->fields = json_encode($request->assets);
            $invoice->payer_id = $user_id;
            $invoice->form_id = 92; //we have to change it in future just to avoid errors

            $invoice->invitee_id = $show->user_id;
            $invoice->show_owner_id = $show->user_id;
            $invoice->invitee_id = $show->user_id;

            $invoice->invite_asociated_key = $uniqueId;
            $invoice->amount = $request->total_price;
            $invoice->is_draft = 2;

            $invoice->save();

            //step 2
            //Save Total price in payment.
            $MSR = $request->MSR;
            $increamentModal = ManageShowsRegister::select('show_reg_number','id')->where('manage_show_id',$request->show_id)->orderBy('show_reg_number','DESC')->first();
            $model = ManageShowsRegister::find($MSR);
            $model->total_price = $request->total_price;
            $model->additional_fields = $additionalC;
            $model->status = 1;
            $model->show_reg_number = $increamentModal->show_reg_number +1;
            $model->assets_fields = json_encode($request->get('assets'));
            $model->update();

            $show_id = $model->manage_show_id;

            
            //Participate.
            $data = $request->all();
            $user_id = \Auth::user()->id;
            $user_email = \Auth::user()->email;
            $uname = \Auth::user()->name;
            $arr=[];
            $arr_module=$request->get('module');
            $array_asset = $request->get('assets');

            foreach ($array_asset as $key => $asset) {
                if (isset($asset["id"])) {
                    $participant = new Participant();
                    $participant->template_id = $request->template_id;
                    $participant->invitee_id = $show->user_id;
                    $participant->asset_id = $asset["id"];
                    $participant->email = $user_email;
                    $participant->name = $uname;
                    $participant->manage_show_reg_id = $MSR;
                    $participant->show_id = $show_id;
                    $participant->status = 1;
                    $participant->allowed_time = "unlimited";
                    $participant->email_confirmation = 1;
                    $participant->invite_asociated_key = $uniqueId;
                    $assetModules = AssetModules::select('modules_permission')->where('asset_id',$asset["id"])->first();
                    if($assetModules)
                    $participant->modules_permission = $assetModules->modules_permission;   
                    $participant->save();

                    
                    //Adding unique horses to class 
                    if (isset($asset["horses"])) {
                        foreach ($asset["horses"] as $horse) {
                            //Adding it to class horse
                            $model = new ClassHorse();
                            $model->horse_id = $horse;
                            $model->class_id = $asset["id"];
                            $model->show_id = $show_id;
                            $model->horse_reg = getHorseRegistrationId($show_id, $horse);
                            $model->participant_id = $participant->id;
                            $model->user_id = $user_id;
                            $model->show_id = $show_id;
                            if(isset( $asset["qualifing"]))
                            $model->qualifing_check = $asset["qualifing"];
                            
                            if(isset( $asset["qualifing_price"]))
                            $model->qualifing_price = $asset["qualifing_price"];
                            
                            $model->msr_id = $MSR;
                            $model->invite_asociated_key = $uniqueId;
                            if (isset($division->id)) {
                                $model->division_id = $division->id;
                            }
                            //For Billing purpose
                            if (isset($asset['orignal_price'])) {
                                $model->price = $asset['orignal_price'];
                            }
                            $model->additional_charges = $additionalC;
                            $model->belong_to_div = null;
                            // for horse riders
                            $model->horse_rider = $asset["riders"][$horse];
                            if(isset($asset["qty"][$horse]))
                            $model->horse_quantity = $asset["qty"][$horse];

                            //save
                            $model->save();
                        }
                    }
                }
            }

            
            \Session::flash('message', 'Invite has been send to User(s) successfully');
            return redirect()->action('UserController@index');
            

        }
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function viewInvoice($manage_show_r_id,$participant_id)
    {
        $user_id = \Auth::user()->id;
        $manage_show_r_id = nxb_decode($manage_show_r_id);
        $participant_id = nxb_decode($participant_id);
        //Queries
        $participant = Participant::find($participant_id);
        $MSR = ManageShowsRegister::find($manage_show_r_id);
        $invoice = Invoice::where('show_id',$MSR->manage_show_id)->where('payer_id',$user_id)->first();
        $prize = ShowPrizingListing::with("shows")->where("show_id",$participant->show_id)->get();
        $SpliteCharges = ManageShowTrainerSplit::with("ClassHorse","TrainerUser")->whereHas('ClassHorse', function ($query) use ($manage_show_r_id) {
                                    $query->where('msr_id', $manage_show_r_id);
                                })->get();
        //Assigning variables
        $assets = json_decode($MSR->assets_fields);
        $additional_price = json_decode($MSR->additional_fields);
        return view('trainer.Invoice')->with(compact("SpliteCharges","manage_show_r_id","participant","participant_id","MSR","invoice","assets","collection",'additional_price','prize','user_id'));
    }
        /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function payInOffice(Request $request)
    {
        $user_id = \Auth::user()->id;
        $invoice_id = nxb_decode($request->invoice_id);
        $invoice = Invoice::find($invoice_id);
        $invoice->payinoffice = $request->payinoffice;
        $invoice->save();

        \Session::flash('message', 'Your pay in office information has been saved.');
        return "true";     
    }

     public function payInvoice(Request $request)
     {


         dd($request);
         $user_id = \Auth::user()->id;


         $OwnerInfo = AppownerBankAccountInformation::where('owner_id',$user_id)->first();

         $paypalAccountDetail = PaypalAccountDetail::where('userId',$user_id)->first();



         $show_id = $request->get('show_id');

       $manageShows = ManageShows::where('show_id',$show_id)->first();

       $userName = $manageShows->user->name;
       $showTitle = $manageShows->title;
       $total_price = $request->get('total_price');


         return view('trainer.participant.invoiceDetail')->with(compact("total_price","OwnerInfo","paypalAccountDetail","user_id","userName","showTitle"));


         // dd($request->all());
     }
         /**
     * Display listing of the shows .
     *
     * @return \Illuminate\Http\Response
     */
    public function showParticipants($template_id)
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


        $manageShows = ManageShows::with("participants")->where('template_id',$template_id)
            ->where('user_id',$user_id)
            ->orderBy('id','Desc')->get();
        return view('shows.participant.index')->with(compact("template_id","manageShows"));

    }
             /**
     * Display registration of the participant.
     *
     * @return \Illuminate\Http\Response
     */
    public function registrationView($manage_show_reg_id)
    {
        $user_id = \Auth::user()->id;
        $manage_show_reg_id = nxb_decode($manage_show_reg_id);

        $manageShows = ManageShowsRegister::with("show")->where("id",$manage_show_reg_id)->first();
        $show_id = $manageShows->manage_show_id;
        $template_id = $manageShows->show->template_id;
        
        /********* Form display start*************/
        $FormTemplate = Form::where('template_id',$template_id)->where('form_type',F_REGISTRATION)->first();
        $TemplateDesign = TemplateDesign::where('template_id', $template_id)->first(); 
        $TD_variables = null;
            $pre_fields = null;
            $formid = null;
            $answer_fields = null;
           if ($FormTemplate) {
                //MasterTemplate Design Variable  -->
                $TD_variables = getTemplateDesign($TemplateDesign);
                $pre_fields = json_decode($FormTemplate->fields, true);
                if ($manageShows) {
                    $answer_fields = json_decode($manageShows->fields, true);
                }
                // END: MasterTemplate Design Variable  -->
                $formid = $FormTemplate->id;
           }


        /********* Form display end*************/
        return view('trainer.participate.registerview')->with(compact("template_id","answer_fields","show_id",'FormTemplate','TD_variables','pre_fields'));
    }
        /**
     * Store a newly created resource in storage.
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

        $MSR = ManageShowsRegister::find($manage_show_r_id);
        $invoice = Invoice::where('show_id',$MSR->manage_show_id)->where('payer_id',$payer_id)->first();
        $prize = ShowPrizingListing::with("shows")->where("show_id",$participant->show_id)->get();

        //Assigning variables
        $assets = json_decode($MSR->assets_fields);
        $additional_price = json_decode($MSR->additional_fields);
        
        return view('shows.participant.viewInvoice')->with(compact("participant_id","participant","manage_show_r_id","payer_id","MSR","invoice","assets","collection",'additional_price','prize','user_id'));
        
    }

    /**
     * Get the invoice for the assets in view assets module.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function invoice($asset_id)
    {
        //store
        $user_id = \Auth::user()->id;
        $asset_id = nxb_decode($asset_id);
        $asset = Asset::where('id',$asset_id)->first();
        $template_id =$asset->template_id;
        $FormTemplate = Form::where('template_id',$template_id)->where('form_type',F_SHOW_INVOICE)->first();
        $assetInvoice = ShowAssetInvoice::where('asset_id',$asset_id)->first();
        
        if (is_null($FormTemplate)) {
            \Session::flash('message', 'There is no Invoice form attached by admin.');
            
            return redirect()->route('master-template-manage-assets', ['template_id' => nxb_encode($template_id)]);
        }
        /********* Form display start*************/
        $TemplateDesign = TemplateDesign::where('template_id', $template_id)->first(); 
        $TD_variables = null;
            $pre_fields = null;
            $formid = null;
            $answer_fields = null;
            $assetInvoiceID = null;
           if ($FormTemplate) {
                //MasterTemplate Design Variable  -->
                $TD_variables = getTemplateDesign($TemplateDesign);
                $pre_fields = json_decode($FormTemplate->fields, true);
                if ($assetInvoice) {
                    $answer_fields = json_decode($assetInvoice->fields, true);
                    $assetInvoiceID = $assetInvoice->id;
                }
                // END: MasterTemplate Design Variable  -->
                $formid = $FormTemplate->id;
           }
        /********* Form display end*************/

        return view('shows.invoice')->with(compact("asset_id",'assetInvoiceID','answer_fields','FormTemplate','TD_variables','pre_fields'));

      }
      
        /**
     * Store a newly created resource in storeInvoice.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function saveInvoice(Request $request)
    {
        //store
        $template_id =$request->template_id;
        $asset_id = $request->asset_id;
        $showassetid = $request->assetInvoiceID;
        if ($showassetid) {
            $model = ShowAssetInvoice::findOrFail($showassetid);
        }else{
            $model = new ShowAssetInvoice();
        }
        $model->asset_id = $asset_id;
        $model->fields = submitFormFields($request);
        $model->save();
        \Session::flash('message', 'Shows Invoice has been added successfully');
        return redirect()->route('master-template-manage-assets', ['template_id' => nxb_encode($template_id)]);
        
    }
    /**
     * Shows Additonal charges from dashboard.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function additionalCharges($app_id)
    {
        //show
        $app_id = nxb_decode($app_id);
        $invited = InvitedUser::where('id',$app_id)->first();
        $template_id = $invited->template_id;
        $additional_charges =  AdditionalCharges::where('app_id',$app_id)->get();
        return view('shows.additional-charges.index')->with(compact("additional_charges",'app_id','template_id'));
        
    }
    /**
     * Shows Additonal charges from dashboard.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function additionalCsave(Request $request)
    {
        $app_id =$request->app_id;
        if (isset($request->required)) {
            $requ = $request->required;
        }else{
            $requ = 0; 
        }
        //show
        if (isset($request->additional_charge_id)) {
            $model = AdditionalCharges::find($request->additional_charge_id);
        }else{
            $model = new AdditionalCharges();
        }
        $model->app_id = $request->app_id;
        $model->template_id = $request->template_id;
        $model->title = $request->title;
        $model->description = $request->description;
        $model->amount = $request->amount;
        $model->required = $requ;
        $model->save();
        \Session::flash('message', 'Show\'s additional charges has been added successfully');
        return redirect()->route('ShowController-additionalCharges', ['app_id' => nxb_encode($app_id)]);
        
    }
        /**
     * Delete Additonal charges from dashboard.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function additionalCdelete($id)
    {
        $id = nxb_decode($id);
        $additional = AdditionalCharges::find($id);
        $app_id = $additional->app_id;
        $additional->delete();
        \Session::flash('message', 'Show\'s additional charges has been deleted successfully');
        return redirect()->route('ShowController-additionalCharges', ['app_id' => nxb_encode($app_id)]);
        
    }


    /**
     * Scratch (Delete) the horse resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function scratchHorse($id,$add=0)
    {
        $id = nxb_decode($id);
        $classHorse = ClassHorse::where('id',$id)->with('division')->first();
        if ($add == 1) {
            //Check if belongs to division

            if ($classHorse->division) {
                $division = $classHorse->division;
                $PriReq = $division->primary_required;
                $division_id = $division->division_id;
                if ($PriReq == DIVISION_MUST_REQ) {
                    $divisionHorses = ClassHorse::where('invite_asociated_key',$classHorse->invite_asociated_key)
                    ->where('horse_id',$classHorse->horse_id)
                    ->whereHas('division', function ($query) use ($division_id) {
                        $query->where('division_id', $division_id);
                    })->update(['scratch' => 0]);
                    //dd($divisionHorses->toArray());
                }
            }
            $classHorse->scratch = 0;
        }else{
            //Check if belongs to division
            if ($classHorse->division) {
                $division = $classHorse->division;
                $PriReq = $division->primary_required;
                $division_id = $division->division_id;
                if ($PriReq == DIVISION_MUST_REQ) {
                    $divisionHorses = ClassHorse::where('invite_asociated_key',$classHorse->invite_asociated_key)
                    ->where('horse_id',$classHorse->horse_id)
                    ->whereHas('division', function ($query) use ($division_id) {
                        $query->where('division_id', $division_id);
                    })->update(['scratch' => 1]);
                    //dd($divisionHorses->toArray());
                }
            }
            removeScratchScheduler($classHorse->show_id,$classHorse->user_id,$classHorse->class_id,$classHorse->horse_id);
            $classHorse->scratch = 1;

        }
        $classHorse->update();
        \Session::flash('message', 'This horse has been scratched');
        return redirect()->back();
        
    }
    /**
     * Scratch (Delete) the horse resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function scratchHorseTrainer($id,$trainer_id,$add=0)
    {
        $id = nxb_decode($id);
        //$classHorse = ClassHorse::find($id);
        $classHorse = ClassHorse::where('id',$id)->with('division')->first();

        if ($add == 1) {
            if ($classHorse->division) {
                $division = $classHorse->division;
                $PriReq = $division->primary_required;
                $division_id = $division->division_id;
                if ($PriReq == DIVISION_MUST_REQ) {
                    $divisionHorses = ClassHorse::where('invite_asociated_key',$classHorse->invite_asociated_key)
                    ->where('horse_id',$classHorse->horse_id)
                    ->whereHas('division', function ($query) use ($division_id) {
                        $query->where('division_id', $division_id);
                    })->update(['scratch' => 0]);
                    //dd($divisionHorses->toArray());
                }
            }
            $classHorse->scratch = 0;
        }else{
            $classHorse->scratch = 1; 
             //Check if belongs to division
            if ($classHorse->division) {
                $division = $classHorse->division;
                $PriReq = $division->primary_required;
                $division_id = $division->division_id;
                if ($PriReq == DIVISION_MUST_REQ) {
                    $divisionHorses = ClassHorse::where('invite_asociated_key',$classHorse->invite_asociated_key)
                    ->where('horse_id',$classHorse->horse_id)
                    ->whereHas('division', function ($query) use ($division_id) {
                        $query->where('division_id', $division_id);
                    })->update(['scratch' => 1]);
                    //dd($divisionHorses->toArray());
                }
            }
            removeScratchScheduler($classHorse->show_id,$classHorse->user_id,$classHorse->class_id,$classHorse->horse_id);


            $email = getUserEmailfromid($classHorse->user_id);
            $username = getUserNamefromid($classHorse->user_id);
            $showTitle = getShowName($classHorse->show_id);
            $class = GetAssetNamefromId($classHorse->class_id);
            $trainer_id = nxb_decode($trainer_id);
            \Mail::to($email)->send(new TrainerScratchHorse($showTitle,$trainer_id,$classHorse->horse_id,$class,$username));
            $classHorse->horse_rider = '';
        }
        $classHorse->update();
        \Session::flash('message', 'This horse has been scratched');
        //return redirect()->route('user.dashboard');
        return redirect()->back();
        
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function addScratch($template_id)
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

       $ClasseScratch = Asset::where('template_id',$template_id)
            ->where('user_id',$user_id)->whereNotIn('id', function($query)use ($template_id,$user_id){
                        $query->select('asset_id')
                            ->from(with(new ShowScratchPenalty)->getTable())
                            ->where('template_id',$template_id)
                            ->where('owner_id',$user_id)
                            ->where('type',SCROPT_SCRATCH_PENALITY);
                    })->get();
        $ClasseJoining = Asset::where('template_id',$template_id)
            ->where('user_id',$user_id)->whereNotIn('id', function($query)use ($template_id,$user_id){
                        $query->select('asset_id')
                            ->from(with(new ShowScratchPenalty)->getTable())
                            ->where('template_id',$template_id)
                            ->where('owner_id',$user_id)
                            ->where('type',SCROPT_CLASS_JOINING_PENALITY);
                    })->get();
        $collection = ShowScratchPenalty::where('template_id',$template_id)
            ->where('owner_id',$user_id)
            ->get();
        return view('shows.scratch.index')->with(compact('ClasseJoining','ClasseScratch','collection','template_id'));

        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function saveScratch(Request $request)
    {
        $template_id = $request->template_id;

        $isEmail = \Session('isEmployee');
        $userEmail = \Auth::user()->email;

        if($isEmail==1) {
            $user_id = getAppOwnerId($userEmail,$template_id);
            $employee_id = \Auth::user()->id;
        }
        else {
            $user_id = \Auth::user()->id;
            $employee_id = 0;
        }


        foreach ($request->scratch_classes as $asset) {
            $model = new ShowScratchPenalty();
            $model->template_id = $template_id;
            $model->owner_id = $user_id;
            $model->penality = $request->penality;
            $model->asset_id = $asset;
            $model->date_from = $request->date_from;
            $model->date_to = $request->date_to;
            $model->type = $request->type;
            $model->employee_id = $employee_id;

            $model->save();
        }
        
        if ($request->type == 1) {
            \Session::flash('message', 'The scratch restriction has been added');
        }else{
            \Session::flash('message', 'The class restriction has been added');
        }
        return redirect()->route('ShowController-add-scratch',['template_id' => nxb_encode($template_id)]);
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function feedbackTrainer($asset_id)
    {
        $asset_id = nxb_decode($asset_id);
        $asset = Asset::find($asset_id);
        $template_id = $asset->template_id;
        $feedBack = SchedulerFeedBacks::with("horse","invitee")->where('horse_id', $asset_id)->get();
        return view('MasterTemplate.assets.shows.feedback')->with(compact('feedBack',"template_id",'asset_id'));
        
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroyScratch($id)
    {
        $id = nxb_decode($id);
        $form = ShowScratchPenalty::findOrFail($id);
        $template_id = $form->template_id;
        $form->delete();
        \Session::flash('message', 'Your Form has been deleted successfully');
        return \Redirect::back();
    }

    public function orders_supplies($template_id,$app_id,$show_id)
    {
        $template_id = nxb_decode($template_id);
        $app_id = nxb_decode($app_id);
        $show_id = nxb_decode($show_id);
        $user_id = \Auth::user()->id;

        $trainers = ManageShowTrainer::select("id")->where("user_id",$user_id)->where("manage_show_id",$show_id)->first();
        if (isset($trainers->id)) {
            $trainerID = $trainers->id;
            $users = ClassHorse::with("MSR","user")->whereHas('MSR', function ($query) use ($trainerID,$show_id) {
                $query->where('trainer_id', $trainerID);
                $query->where("manage_show_id",$show_id);
            })->groupBy('horse_id')->get();
        }else{
            $users = null;
        }

        $additional_price = AdditionalCharges::where("template_id",$template_id)->where('app_id',$app_id)->get();
        return view('shows.trainers.orderSupplies')->with(compact("show_id","additional_price","users","app_id"));

    }


public function ViewOrderSupplies($template_id)
{


    $template_id = nxb_decode($template_id);
    $email = \Auth::user()->email;

    $user_id = \Auth::user()->id;

    $suppliesOrders = ManageShowOrderSupplies::where('template_id',$template_id)->where('show_owner_id',$user_id)->orderBy('id','desc')->get();

    return view('shows.trainers.orderSuppliesRequests')->with(compact("suppliesOrders","template_id"));

}
    public function viewOrderDetail($order_id,$orderType)
    {

        $order_id = nxb_decode($order_id);

        $suppliesOrders = ManageShowOrderSupplies::where('id',$order_id)->first();

        //dd(json_decode($suppliesOrders->additional_fields));
        $dataBreadCrumb = [
            'template_id'=>nxb_encode($suppliesOrders->template_id),
            'show_id'=>nxb_encode($suppliesOrders->show_id)
        ];

        return view('shows.trainers.viewOrderDetail')->with(compact("suppliesOrders","orderType","dataBreadCrumb"));

    }


    public function viewOrderHistory($template_id,$app_id,$show_id)
    {
        $template_id = nxb_decode($template_id);
        $app_id = nxb_decode($app_id);
        $show_id = nxb_decode($show_id);
        $user_id = \Auth::user()->id;

        $suppliesOrders = ManageShowOrderSupplies::where('template_id',$template_id)->where('show_id',$show_id)->where('trainer_user_id',$user_id)->orderBy('id','desc')->get();

        return view('shows.trainers.orderHistory')->with(compact("suppliesOrders","template_id"));

    }



}
