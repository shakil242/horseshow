<?php
/***********
@aurthor: Faran Ahmed Khan (Vteams)
 Show Controller  will have all the functions related to show. Like listing, save response

Notes: Keep in mind. 

@type
In database column:(type) for shows is always 1.
So for show listing. We must have where('type',1); or where('type',SHOW_TYPE_SHOWS)

@trainer_id
In database column:(trainer_id) is not user_id of trainer.
it is taken from manage_show_trainers table. ID of manage_show_trainers is forigen key in manage_shows_registration. 

************/
namespace App\Http\Controllers;

use App\AppownerBankAccountInformation;
use App\HorseRiderStall;
use App\Invoice;
use App\Mail\StallTypeNotification;
use App\ManageShowOrderSupplies;
use App\ManageShowSpectator;
use App\ParticipantAccountInformation;
use App\PaypalAccountDetail;
use App\PointsDivisionAssociation;
use App\SchedulerRestriction;
use App\ShowSponsors;
use App\ShowStables;
use App\ShowStallRequest;
use App\SponsorCategories;
use App\SponsorCategoryBelong;
use App\SponsorCategoryBilling;
use App\StallTypes;
use App\Template;
use Carbon\Carbon;
use function GuzzleHttp\Promise\all;
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
use App\User;
use App\Division;
use App\ShowStallUtility;
use App\ManageShowOrderHorse;
use App\ShowPayInOffice;
use App\Mail\TrainerParticipate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;

class ShowController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($show_duration=null,Request $request)
    {

        //dd($request->query);
       $query = $request->get('query','');



        $email = \Auth::user()->email;
        $user_id = \Auth::user()->id;

        $horseContains = 0;
        $riderContains = 0;
        $horseCollection = InvitedUser::where('status', 1)->where('block', 0)->where('email', $email)
            ->whereHas("template", function ($query) {
                $query->where('category', CONST_HORSE_TEMPLATE);
            })->orderBy('id', 'desc')->pluck('template_id');

        if (count($horseCollection) > 0) {
            $horseContains = Asset::whereIn('template_id', $horseCollection)->where('user_id', $user_id)->count();
            $rider = Asset::whereIn('template_id', $horseCollection)->where('asset_type', 2)->where('user_id', $user_id);
            $riderValues = $rider->get();

           // dd($riderValues->toArray());
            $arr = [];
            foreach ($riderValues as $v)
            {
             $arr[]  = GetRiderOwnerStatus($v,"Relationship To Horse");
            }

            $riderOwner = [];
            if(count($arr)>0) {
              $riderowner = array_filter($arr);
                foreach ($riderowner as $item) {
                    if (in_array('Rider', $item)) {
                        $riderOwner[] = 'Rider';
                    }
                    if (in_array('Owner', $item)) {
                        $riderOwner[] = 'Owner';
                    }
                }
            }

            if(!in_array('Rider',$riderOwner))
                $notRiderOwner[] = 'Rider';
            if(!in_array('Owner',$riderOwner))
                $notRiderOwner[] = 'Owner';



            $riderContains = $rider->count();
        }


        $today = Carbon::today();

        $collection = ManageShows::with("template", "appowner");

        if($request->ajax()) {
            $query =  $request->get('query');
            $collection = $collection->where(function($q) use ($query) {
                $q->where('title', 'LIKE', '%'.$query.'%')
                    ->orWhere('location', 'LIKE', '%'.$query.'%')
                    ->orWhere('show_type', 'LIKE', '%'.$query.'%')
                    ->orWhere('governing_body', 'LIKE', '%'.$query.'%');
            });
        }

        $dateFrom = Carbon::now()->startOfMonth();
        if($show_duration=='previous')
            $collection = $collection->whereRaw('MONTH(date_from) < (MONTH(NOW())) AND YEAR(date_from) = YEAR(NOW())');
        elseif($show_duration=='Upcoming')
            $collection = $collection->whereRaw('MONTH(date_from) > (MONTH(NOW())) AND YEAR(date_from) = YEAR(NOW())');
        else
            $collection = $collection->where('date_from','>=',$dateFrom);


        $collection = $collection->where("type", SHOW_TYPE_SHOWS)
            ->whereHas("template", function ($query) {
            $query->where('category', CONST_SHOW);
        })->whereHas("appowner", function ($query) {
            $query->where('status', 1)->where('block', 0);
        })->orderBy('date_from', 'desc')->limit(50)->get();

      //  dd($collection);
        // ArrayPrint($collection);
        //   $FormTemplate = Form::where('template_id',$template_id)->where('form_type',SPECTATOR_REGISTRATION)->first();
        if($request->ajax()) {
        return view('shows.search_view')->with(compact("collection", "horseCollection", "horseContains", "riderContains","riderOwner","notRiderOwner"));
        }
        return view('shows.index')->with(compact("collection", "horseCollection", "horseContains", "riderContains","riderOwner","notRiderOwner"));
    }

    /**
     * Listing of all the trainers.
     *
     * @return \Illuminate\Http\Response
     */
    public function trainers($show_id)
    {
        $show_id = nxb_decode($show_id);
        $manageShow = ManageShows::with("template", "appowner")->where("id", $show_id)->whereHas("template", function ($query) {
            $query->where('category', CONST_SHOW);
        })->whereHas("appowner", function ($query) {
            $query->where('status', 1)->where('block', 0);
        })->orderBy('date_from', 'desc')->first();
        $collection = ManageShowTrainer::with("user")->where("manage_show_id", $show_id)->get();
        //Check if this user is already registered. MANAGE SHOW TRAINER $MST
        if (Auth::check()) {
            $user_email = \Auth::user()->email;
            $user_id = \Auth::user()->id;
            $MST = ManageShowTrainer::where("manage_show_id", $show_id)->where("user_id", $user_id)->first();
            $trainerApp = InvitedUser::where('status', 1)->where('block', 0)->where('email', $user_email) ->whereHas("template", function ($query) {
                $query->where('category', CONST_TRAINERS);
            })->get()->count();
        } else
            $MST = [];

        $trainerApps = Template::where('category',4)->orderBy('id','desc')->get();


        return view('shows.trainers.index')->with(compact("collection", "manageShow", "MST",'trainerApp',"trainerApps"));
    }

    /**
     * Listing of all the trainers.
     *
     * @return \Illuminate\Http\Response
     */
    public function viewTrainerOnly($edit_id)
    {
        $edit_id = nxb_decode($edit_id);
        $trainer = ManageShowTrainer::find($edit_id);
        $show_id = 0;
        if ($trainer) {
            $show_id = $trainer->manage_show_id;
            $show = ManageShows::where('id', $show_id)->first();
            $FormTemplate = Form::where('template_id', $show->template_id)->where('form_type', F_SHOW_TRAINER_REG)->first();
            $TemplateDesign = TemplateDesign::where('template_id', $show->template_id)->first();
            $answer_fields = null;
            $TD_variables = null;
            $pre_fields = null;
            $formid = null;
            if ($FormTemplate) {
                //MasterTemplate Design Variable  -->
                $TD_variables = getTemplateDesign($TemplateDesign);
                $pre_fields = json_decode($FormTemplate->fields, true);
                $answer_fields = json_decode($trainer->fields, true);
                // END: MasterTemplate Design Variable  -->
                $formid = $FormTemplate->id;
            }
        } else {
            \Session::flash('message', 'There was a problem editing this.');
            return redirect()->route('ShowController-trainers', ['show_id' => nxb_encode($show_id)]);

        }
        return view('shows.trainers.viewonly')->with(compact("show_id", "answer_fields", "edit_id", 'FormTemplate', 'TD_variables', "TemplateDesign", 'pre_fields'));

    }

    /**
     * Listing of all the trainers.
     *
     * @return \Illuminate\Http\Response
     */
    public function add_trainers($show_id, $edit_id = 0)
    {
        $user_id = \Auth::user()->id;
        $edit_id = nxb_decode($edit_id);
        $show_id = nxb_decode($show_id);

        //Check if this user is already registered. MANAGE SHOW TRAINER $MST
        if ($edit_id == 0) {
            $MST = ManageShowTrainer::where("manage_show_id", $show_id)->where("user_id", $user_id)->first();
            if (!is_null($MST)) {
                \Session::flash('message', 'You have already registered as a trainer.');
                return redirect()->route('ShowController-trainers', ['show_id' => nxb_encode($show_id)]);

            }
        }
        $show = ManageShows::where('id', $show_id)->first();
        $FormTemplate = Form::where('template_id', $show->template_id)->where('form_type', F_SHOW_TRAINER_REG)->first();
        $TemplateDesign = TemplateDesign::where('template_id', $show->template_id)->first();
        $answer_fields = null;
        $TD_variables = null;
        $pre_fields = null;
        $formid = null;
        if ($FormTemplate) {
            //MasterTemplate Design Variable  -->
            $TD_variables = getTemplateDesign($TemplateDesign);
            $pre_fields = json_decode($FormTemplate->fields, true);
            if ($edit_id != 0) {
                $trainerRegistered = ManageShowTrainer::find($edit_id);
                if ($trainerRegistered) {
                    $answer_fields = json_decode($trainerRegistered->fields, true);
                }
            }

            // END: MasterTemplate Design Variable  -->
            $formid = $FormTemplate->id;
        }
        return view('shows.trainers.register')->with(compact("show_id", "answer_fields", "edit_id", 'FormTemplate', 'TD_variables', "TemplateDesign", 'pre_fields'));
    }

    /**
     * Store a Trainer in database.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store_trainers(Request $request)
    {
        $user_id = \Auth::user()->id;
        $template_id = $request->template_id;
        $show_id = $request->show_id;
        if (isset($request->edit_id) && $request->edit_id != "") {
            $model = ManageShowTrainer::find($request->edit_id);
        } else {
            $model = new ManageShowTrainer();
        }
        $model->manage_show_id = $show_id;
        $model->user_id = $user_id;
        $model->fields = submitFormFields($request);
        $model->save();

        \Session::flash('message', 'You have successfully Registered as a Trainer.');
        return redirect()->route('ShowController-trainers', ['show_id' => nxb_encode($show_id)]);

    }

    /**
     * Delete Trainer from system.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function deleteTrainer($id)
    {
        $id = nxb_decode($id);
        $additional = ManageShowTrainer::find($id);
        $show_id = $additional->manage_show_id;
        $additional->delete();
        \Session::flash('message', 'You have un-registered form a trainer.');
        return redirect()->route('ShowController-trainers', ['show_id' => nxb_encode($show_id)]);

    }

    /**
     * Delete Trainer from system.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function splite_trainers($template_id, $app_id, $show_id)
    {
        $template_id = nxb_decode($template_id);
        $app_id = nxb_decode($app_id);
        $show_id = nxb_decode($show_id);
        $user_id = \Auth::user()->id;

        $trainers = ManageShowTrainer::select("id")->where("user_id", $user_id)->where("manage_show_id", $show_id)->first();
        if (isset($trainers->id)) {
            $trainerID = $trainers->id;
            $users = ClassHorse::with("MSR", "user", "horse")->whereHas('MSR', function ($query) use ($trainerID, $show_id) {
                $query->where('trainer_id', $trainerID);
                $query->where("manage_show_id", $show_id);
            })->groupBy('horse_id', 'invite_asociated_key')->get();
        } else {
            $users = null;
        }

        $suppliesOrders = ManageShowOrderSupplies::where('template_id', $template_id)->where('show_id', $show_id)->where('status',GENERAL_ACTIVE)->where('trainer_user_id', $user_id)->orderBy('id', 'desc')->get();
        $additional_price = AdditionalCharges::where("template_id", $template_id)->where('app_id', $app_id)->get();
        return view('shows.trainers.splitInvoice')->with(compact("show_id", "additional_price", "users", "suppliesOrders"));

    }

    /**
     * List of splite invoices.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function splite_trainers_history($show_id)
    {
        $show_id = nxb_decode($show_id);
        $user_id = \Auth::user()->id;
        $collection = ManageShowTrainerSplit::where("show_id", $show_id)->where("trainer_user_id", $user_id)->groupBy("created_at")->orderBy('id','desc')->get();
        return view('shows.trainers.splitHistory')->with(compact("collection", "show_id"));

    }

    /**
     * View detail for the split.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function historyTrainerSplit($split_id)
    {
        $split_id = nxb_decode($split_id);
        $split = ManageShowTrainerSplit::find($split_id);
        return view('shows.trainers.splitHistorydetail')->with(compact("split"));

    }

    /**
     * Split invoice save in database.
     *
     * @param  \Illuminate\Http\Request $request
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
        ], $messages);
        $user_id = \Auth::user()->id;
        $uniqueId = time() . mt_rand() . $user_id;
        if (count($request->MSR_ids) > 0) {
            //$userCount = splitBetweenUsers($request->MSR_ids);
            $comments = $request->comment;
            $userCount = count($request->MSR_ids);
            //dd($request->MSR_ids);
            foreach ($request->MSR_ids as $class_reg_id) {
                $MSTS = new ManageShowTrainerSplit();
                $MSTS->class_horses_id = $class_reg_id;
                $MSTS->trainer_user_id = $user_id;
                $MSTS->show_id = $request->show_id;
                $MSTS->additional_fields = json_encode($request->additional);
                $MSTS->divided_amoung = $userCount;
                $MSTS->unique_batch = $uniqueId;
                $MSTS->total_amount = $request->additional_price;
                $MSTS->comment = $comments;
                $MSTS->save();
            }
            \Session::flash('message', "You have Split the invoice between $userCount horse(s).");

        } else {
            \Session::flash('message', "There was some error while spliting your invoice. Please try again!");
        }
        return redirect()->route('ShowController-index');


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
        $trainer_id = null;

        $show = ManageShows::where('id', $show_id)->first();
        $FormTemplate = Form::where('template_id', $show->template_id)->where('form_type', F_REGISTRATION)->first();

        $template_id = $show->template_id;

        $TemplateDesign = TemplateDesign::where('template_id', $show->template_id)->first();

        $manageShows = ManageShowsRegister::where('user_id', $user_id)->where('manage_show_id', $show_id)->orderBy('id', 'DESC')->first();
        if (!is_null($manageShows)) {

            $show_id = $manageShows->manage_show_id;
            $trainer_id[] = $manageShows->trainer_id;
            /********* Form display start*************/
            $FormTemplate = Form::where('template_id', $template_id)->where('form_type', F_REGISTRATION)->first();
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
        } else {

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
        $trainers = ManageShowTrainer::with("user")->where("manage_show_id", $show_id)->get();


        return view('shows.participate.register')->with(compact("trainers", "show_id", 'FormTemplate', 'TD_variables', 'pre_fields', 'answer_fields', 'template_id', 'trainer_id'));

    }


    /**
     * Save the trainer for the show.
     *
     * @return \Illuminate\Http\Response
     */
    public function saveTrainersAjax(Request $request)
    {
        $user_id = \Auth::user()->id;
        $MSR_id = nxb_decode($request->msr_id);
        $MSR = ManageShowsRegister::find($MSR_id);
        $MSR->trainer_id = $request->trainer;
        $MSR->save();
        \Session::flash('message', 'Saved trainer successfully');
        return redirect()->action('UserController@index');

    }

    /**
     * Display the trainer for the show.
     *
     * @return \Illuminate\Http\Response
     */
    public function getTrainersAjax($show_id, $MSR_id)
    {
        $user_id = \Auth::user()->id;
        $show_id = nxb_decode($show_id);
        $trainers = ManageShowTrainer::with("user")->where("manage_show_id", $show_id)->get();
        return view('shows.trainers.ajaxnewtrainer')->with(compact("trainers", "show_id", "MSR_id"));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        //echo '>>>>'.nxb_decode('ZVY1SkQrb00zK2s9');
        $user_id = \Auth::user()->id;
        if (isset($request->trainer)) {
            $trainer = $request->trainer;
        } else {
            $trainer = null;
        }
        if (!isset($request->step2)) {
            //step1
            $template_id = $request->template_id;
            $show_id = $request->show_id;
            $model = new ManageShowsRegister();
            $model->manage_show_id = $show_id;
            $model->trainer_id = $trainer;
            $model->user_id = $user_id;
            $model->fields = submitFormFields($request);
            $model->save();
            //MSR manage show register
            $MSR = $model->id;
            \Session::flash('message', 'Step 1 of the registration has been saved.');

            //Step 2
            $show = ManageShows::where('id',$show_id)->first();
            
            $collection = Asset::with("ShowClassPrice","assetParent",'SchedulerRestriction')->where('template_id',$template_id)->where('user_id',$show->user_id)->where('asset_type',0)
                                    ->whereHas("SchedulerRestriction",function ($query) use ($show_id) {
                                         $query->where('show_id',$show_id);
                                    })
                                    ->where('is_combined',0)
                                    ->where('is_split',0)
                                    ->doesnthave("assetParent")
                                    ->get();
            $parentCollection = Asset::with("ShowAssetInvoice","subAssets.SchedulerRestriction")->where('template_id',$template_id)->where('user_id',$show->user_id)->where('asset_type',1)
                    ->whereHas("subAssets.SchedulerRestriction",function ($query) use ($show_id) {
                        $query->where('show_id',$show_id);
                    })
                ->where('is_combined',0)
                    ->where('is_split',0)
                    ->get();


            $OwnerHorses = Asset::with("template")->where('user_id',$user_id)
                           ->where('asset_type','!=',2)
                           ->whereHas("template",function ($query) use ($show_id) {
                                         $query->where('category',CONST_HORSE_TEMPLATE);
                                    })->get();

            $riderHorses = Asset::with("template")->where('user_id',$user_id)->where('asset_type',2)
                ->whereHas("template",function ($query) use ($show_id) {
                $query->where('category',CONST_HORSE_TEMPLATE);
            })->get();

            $participatedHorses = ClassHorse::select('class_id', 'horse_id')->where('show_id', $show_id)
                ->where('user_id', $user_id)->get()->toArray();
            $additional_price = AdditionalCharges::where("template_id", $template_id)->where('app_id', $show->app_id)->get();
            return view('shows.participate.assets')->with(compact("OwnerHorses", 'participatedHorses', 'parentCollection', "MSR", 'show', 'collection', 'additional_price', 'riderHorses'));
        } else {
            $uniqueId = time() . mt_rand() . $user_id;
            //  dd($request->toArray());

            $show = ManageShows::select('user_id')->where('id', $request->show_id)->first();
            $additionalC = json_encode($request->additional);
            //step 2

            //Save Total price in payment.
            $MSR = $request->MSR;
            //$increamentModal = ManageShowsRegister::select('show_reg_number','id')->where('manage_show_id',$request->show_id)->orderBy('show_reg_number','DESC')->first();
            $model = ManageShowsRegister::find($MSR);
            $model->total_price = $request->total_price;
            $model->additional_fields = $additionalC;
            //$model->show_reg_number = $increamentModal->show_reg_number +1;
            $model->assets_fields = json_encode($request->get('assets'));
            $model->status = 1;
            //Check if trainer is allowed to submit the form
            if (isset($request->allow_trainer_register)) {
                if ($request->allow_trainer_register == 1) {
                    //2 means trainer will complete the registration
                    $model->status = 2;
                    $model->allow_trainer_register = 1;
                }
            }
            $model->unique_horses = $request->get('unique_horses');
            $model->update();

            //Invoicing
            $invoice = new Invoice();
            $invoice->template_id = $request->template_id;
            $invoice->show_id = $request->show_id;
            $invoice->fields = json_encode($request->assets);
            $invoice->payer_id = $user_id;
            //$invoice->form_id = 92; //we have to change it in future just to avoid errors
            $invoice->invitee_id = $show->user_id;
            $invoice->show_owner_id = $show->user_id;
            $invoice->invitee_id = $show->user_id;
            $invoice->invite_asociated_key = $uniqueId;
            $invoice->amount = $request->total_price;
            $invoice->is_draft = 2;
            //$invoice->save();


            $show_id = $model->manage_show_id;
            //Participate.
            $data = $request->all();
            $user_id = \Auth::user()->id;
            $user_email = \Auth::user()->email;
            $uname = \Auth::user()->name;
            $user_array = ['user_id' => $user_id, 'user_email' => $user_email, 'uname' => $uname];
            $arr = [];
            $arr_module = $request->get('module');
            $array_asset = $request->get('assets');

            //  ArrayPrint($array_asset);
            //dd($array_asset);
            foreach ($array_asset as $key => $asset) {

                if ($key == "division") {
                    foreach ($asset as $Divkey => $division) {
                        if (isset($division["innerclasses"]) && isset($division['orignal_id'])) {
                            $belong_to_div = $division['orignal_id'];

                            if (isset($division['id'])) {
                                $div_id = $division["id"];
                                $div_price = $division['price'];
                                $division_total_classes = $division['total_classes'];

                            } else {
                                $div_id = null;
                                $div_price = null;
                                $division_total_classes = null;
                            }
                            foreach ($division["innerclasses"] as $division_classes) {
                                $this->saveHorseInShow($division_classes, $request, $show, $show_id, $uniqueId, $user_array, $MSR, $belong_to_div, $division_total_classes, $div_id, $div_price);
                            }
                        }
                    }
                } else {
                    $this->saveHorseInShow($asset, $request, $show, $show_id, $uniqueId, $user_array, $MSR);
                }

            }


            \Session::flash('message', 'Invite has been send to User(s) successfully');
            return redirect()->action('UserController@index');


        }
    }

    public function saveHorseInShow($asset, $request, $show, $show_id, $uniqueId, $user_array, $MSR, $belong_to_div = null, $division_total_classes = null, $divid = null, $div_price = null)
    {

        
        $user_email = $user_array["user_email"];
        $uname = $user_array["uname"];
        $user_id = $user_array["user_id"];
        $additionalC = json_encode($request->additional);

            if (isset($asset["id"])) {
               
                 if (isset($asset["already_registered"])) {
                        $participant = Participant::where('invite_asociated_key',$uniqueId)
                                                        ->where('manage_show_reg_id',$MSR)
                                                        ->where('asset_id',$asset["id"])
                                                        ->where('email',$user_email)->first();
                    }else{
                        $participant = new Participant();
                            $participant->template_id = $request->template_id;
                            $participant->invitee_id = $show->user_id;
                            $participant->asset_id = $asset["id"];
                            $participant->email = $user_email;
                            $participant->name = $uname;
                            $participant->manage_show_reg_id = $MSR;
                            $participant->show_id = $show_id;
                            $participant->status = 1;
                            $participant->allowed_time = 1;
                            $participant->email_confirmation = 1;
                            $participant->invite_asociated_key = $uniqueId;
                            $assetModules = AssetModules::select('modules_permission')->where('asset_id', $asset["id"])->first();
                            if ($assetModules)
                                $participant->modules_permission = $assetModules->modules_permission;
                            $participant->save(); 
                    }

                //
                if (isset($asset["horses"])) {

                    foreach ($asset["horses"] as $horse) {

                        $ClassHorseExist = ClassHorse::where('horse_id',$horse)->
                                                where('show_id',$show_id)->
                                                where('class_id',$asset["id"])->exists();
                        if (!$ClassHorseExist) {
                                //Division Save
                                if ($divid) {
                                    $division = new Division();
                                    $division->horse_id = $horse;
                                    $division->show_id = $show_id;
                                    $division->user_id = $user_id;
                                    $division->division_id = $divid;
                                    $division->price = $div_price;
                                    $division->invite_key = $uniqueId;
                                    $division->primary_required = $asset["primary_required"];
                                    $division->total_classes = $division_total_classes;
                                    $division->save();
                                }
                                //Adding it to class horse

                                $model = new ClassHorse();
                                $model->horse_id = $horse;
                                $model->class_id = $asset["id"];
                                $model->show_id = $show_id;
                                $model->horse_reg = getHorseRegistrationId($show_id, $horse);
                                $model->invoice_no = getHorseInvoice($show_id, $horse);
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
                                $model->belong_to_div = $belong_to_div;
                                // for horse riders
                                $model->horse_rider = $asset["riders"][$horse];
                                //save
                                $model->save();

                        }
                    }
                }

            }
        //}

        if (isset($asset["id"])) {
            if (isset($request->ClassDivision)) {
                $model = new PointsDivisionAssociation();
                $model->class_id = $asset["id"];
                if(isset($request->ClassDivision[$asset["id"]]))
                $model->division_id = $request->ClassDivision[$asset["id"]];
                $model->show_id = $show_id;
                $model->save();
            }
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function viewInvoice($manage_show_r_id, $participant_id)
    {
        $user_id = \Auth::user()->id;
        $manage_show_r_id = nxb_decode($manage_show_r_id);
        $participant_id = nxb_decode($participant_id);
        //Queries
        $participant = Participant::find($participant_id);
        $royalty = 0;

        $appOwner = User::find($participant->invitee_id);
        if ($appOwner) {

            $inviteUser = InvitedUser::where('email', '=', $appOwner->email)->where('template_id', $participant->template_id)->first();
            $royalty = $inviteUser->royalty;
        }
        $MSR = ManageShowsRegister::find($manage_show_r_id);
        $invoice = Invoice::where('show_id', $MSR->manage_show_id)->where('payer_id', $user_id)->first();
        // dd($invoice);

        $prize = ShowPrizingListing::with("shows")->where("show_id", $participant->show_id)->get();
        $SpliteCharges = ManageShowTrainerSplit::with("ClassHorse", "TrainerUser")->whereHas('ClassHorse', function ($query) use ($manage_show_r_id) {
            $query->where('msr_id', $manage_show_r_id);
        })->get();
        //Assigning variables
        $assets = json_decode($MSR->assets_fields);
        $additional_price = json_decode($MSR->additional_fields);
        return view('shows.Invoice')->with(compact("SpliteCharges", "manage_show_r_id", "participant", "participant_id", "MSR", "invoice", "assets", "collection", 'additional_price', 'prize', 'user_id', 'royalty'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function payInOffice(Request $request)
    {
        $horse_id = $request->horse_id;
        $show_id = $request->show_id;

        if($request->payinoffice ==1){
            $model = new ShowPayInOffice();
            $model->horse_id = $horse_id;
            $model->show_id = $show_id;
            $model->save();
        }else{
            $model = ShowPayInOffice::where('show_id',$show_id)->where('horse_id',$horse_id)->where('invoice_status',0)->first();
            $model->delete();
        }
        
        return "true";
    }

    public function payInvoice(Request $request)
    {

        $user_id = \Auth::user()->id;

        $OwnerInfo = AppownerBankAccountInformation::where('owner_id', $user_id)->first();

        $paypalAccountDetail = PaypalAccountDetail::where('userId', $user_id)->first();

        $show_id = $request->get('show_id');

        $manageShows = ManageShows::where('show_id', $show_id)->first();

        $userName = $manageShows->user->name;
        $showTitle = $manageShows->title;
        $total_price = $request->get('total_price');


        return view('shows.participant.invoiceDetail')->with(compact("total_price", "OwnerInfo", "paypalAccountDetail", "user_id", "userName", "showTitle"));


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

        if ($isEmail == 1) {
            $user_id = getAppOwnerId($userEmail, $template_id);
        } else {
            $user_id = \Auth::user()->id;
            $employee_id = 0;
        }

        $manageShows = ManageShows::with(["participants" => function ($q) {
            $q->orderBy('id', 'Desc');
        }])->where('template_id', $template_id)
            ->where('user_id', $user_id)
            ->orderBy('id', 'Desc')->get();
        //dd($manageShows->toArray());
        return view('shows.participant.index')->with(compact("template_id", "manageShows"));

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

        $manageShows = ManageShowsRegister::with("show")->where("id", $manage_show_reg_id)->first();
        $show_id = $manageShows->manage_show_id;
        $template_id = $manageShows->show->template_id;

        /********* Form display start*************/
        $FormTemplate = Form::where('template_id', $template_id)->where('form_type', F_REGISTRATION)->first();
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
        return view('shows.participate.registerview')->with(compact("template_id", "answer_fields", "show_id", 'FormTemplate', 'TD_variables', 'pre_fields'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function viewParticipantInvoice($manage_show_r_id, $participant_id)
    {
        $user_id = \Auth::user()->id;
        $manage_show_r_id = nxb_decode($manage_show_r_id);
        $participant_id = nxb_decode($participant_id);
        //Queries
        $participant = Participant::find($participant_id);
        $payer_id = getIdFromEmail($participant->email);

        $MSR = ManageShowsRegister::find($manage_show_r_id);
        $invoice = Invoice::where('show_id', $MSR->manage_show_id)->where('payer_id', $payer_id)->first();
        $prize = ShowPrizingListing::with("shows")->where("show_id", $participant->show_id)->get();

        //Assigning variables
        $assets = json_decode($MSR->assets_fields);
        $additional_price = json_decode($MSR->additional_fields);

        return view('shows.participant.viewInvoice')->with(compact("participant_id", "participant", "manage_show_r_id", "payer_id", "MSR", "invoice", "assets", "collection", 'additional_price', 'prize', 'user_id'));

    }

    /**
     * Get the invoice for the assets in view assets module.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function invoice($asset_id)
    {
        //store
        $user_id = \Auth::user()->id;
        $asset_id = nxb_decode($asset_id);
        $asset = Asset::where('id', $asset_id)->first();
        $template_id = $asset->template_id;
        $FormTemplate = Form::where('template_id', $template_id)->where('form_type', F_SHOW_INVOICE)->first();
        $assetInvoice = ShowAssetInvoice::where('asset_id', $asset_id)->first();

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
        
        return view('shows.invoice')->with(compact("asset_id", 'assetInvoiceID', 'answer_fields', 'FormTemplate', 'TD_variables', 'pre_fields'));

    }

    /**
     * Store a newly created resource in storeInvoice.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function saveInvoice(Request $request)
    {
        //store
        $template_id = $request->template_id;
        $asset_id = $request->asset_id;
        $showassetid = $request->assetInvoiceID;
        if ($showassetid) {
            $model = ShowAssetInvoice::findOrFail($showassetid);
        } else {
            $model = new ShowAssetInvoice();
        }
        $model->asset_id = $asset_id;
        $model->fields = submitFormFields($request);
        $model->save();
        if (isset($asset_id)) {
            \Session::flash('message', 'Placements for this asset');
             return redirect()->route('PositionController-index', ['asset_id' => nxb_encode($asset_id)]);
        }
        \Session::flash('message', 'Shows Invoice has been added successfully');
        return redirect()->route('master-template-manage-assets', ['template_id' => nxb_encode($template_id)]);

    }

    /**
     * Shows Additonal charges from dashboard.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function additionalCharges($app_id)
    {
        //show
        $app_id = nxb_decode($app_id);
        $invited = InvitedUser::where('id', $app_id)->first();
        $template_id = $invited->template_id;
        $additional_charges = AdditionalCharges::where('app_id', $app_id)->get();
        return view('shows.additional-charges.index')->with(compact("additional_charges", 'app_id', 'template_id'));

    }

    /**
     * Shows Additonal charges from dashboard.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function additionalCsave(Request $request)
    {

        $templateType = GetTemplateType($request->template_id);

        $app_id = $request->app_id;
        if (isset($request->required)) {
            $requ = $request->required;
        } else {
            $requ = 0;
        }
        //show
        if (isset($request->additional_charge_id)) {
            $model = AdditionalCharges::find($request->additional_charge_id);
        } else {
            $model = new AdditionalCharges();
        }
        $model->app_id = $request->app_id;
        $model->template_id = $request->template_id;
        $model->title = $request->title;
        $model->description = $request->description;
        $model->amount = $request->amount;
        $model->required = $requ;
        $model->save();

        if($templateType==TRAINER)
        \Session::flash('message', 'Trainer’s additional charges have been updated successfully');
         else
        \Session::flash('message', 'Show\'s additional charges has been added successfully');

        return redirect()->route('ShowController-additionalCharges', ['app_id' => nxb_encode($app_id)]);

    }

    /**
     * Additonal charges from dashboard.
     *
     * @param  \Illuminate\Http\Request $request
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
     * Delete Additonal charges from dashboard.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function viewLowParticipants($template_id)
    {
        $isEmail = \Session('isEmployee');
        $userEmail = \Auth::user()->email;

        $template_id = nxb_decode($template_id);

        if ($isEmail == 1) {
            $user_id = getAppOwnerId($userEmail, $template_id);
        } else {
            $user_id = \Auth::user()->id;
            $employee_id = 0;
        }

        $manageShows = ManageShows::with('classTypes.Sclasses')
            // with(["participants"=>function($q){
            //     $q->orderBy('id','Desc');
            // }])
            ->where('template_id', $template_id)
            ->where('user_id', $user_id)
            ->orderBy('id', 'Desc')->get();
        return view('shows.low-participants.index')->with(compact("template_id", "manageShows"));

    }


    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function scratchHorse($id, $add = 0)
    {
        $id = nxb_decode($id);
        $classHorse = ClassHorse::where('id', $id)->with('division')->first();
        if ($add == 1) {
            //Check if belongs to division
            if ($classHorse->division) {
                $division = $classHorse->division;
                $PriReq = $division->primary_required;
                $division_id = $division->division_id;
                if ($PriReq == DIVISION_MUST_REQ) {
                    $divisionHorses = ClassHorse::where('invite_asociated_key', $classHorse->invite_asociated_key)
                        ->where('horse_id', $classHorse->horse_id)
                        ->whereHas('division', function ($query) use ($division_id) {
                            $query->where('division_id', $division_id);
                        })->update(['scratch' => 0]);
                    //dd($divisionHorses->toArray());
                }
            }
            $classHorse->scratch = 0;
        } else {
            //Check if belongs to division
            if ($classHorse->division) {
                $division = $classHorse->division;
                $PriReq = $division->primary_required;
                $division_id = $division->division_id;
                if ($PriReq == DIVISION_MUST_REQ) {
                    $divisionHorses = ClassHorse::where('invite_asociated_key', $classHorse->invite_asociated_key)
                        ->where('horse_id', $classHorse->horse_id)
                        ->whereHas('division', function ($query) use ($division_id) {
                            $query->where('division_id', $division_id);
                        })->update(['scratch' => 1]);
                    //dd($divisionHorses->toArray());
                }
            }
            removeScratchScheduler($classHorse->show_id, $classHorse->user_id, $classHorse->class_id, $classHorse->horse_id);
            $classHorse->scratch = 1;

        }
        $classHorse->update();
        \Session::flash('message', 'This horse has been scratched');
        //return redirect()->route('user.dashboard');
        //  return redirect()->back();

        return Redirect::to(URL::previous() . "#" . $classHorse->show_id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function addScratch($template_id)
    {

        $isEmail = \Session('isEmployee');
        $userEmail = \Auth::user()->email;

        $template_id = nxb_decode($template_id);

        if ($isEmail == 1) {
            $user_id = getAppOwnerId($userEmail, $template_id);
        } else {
            $user_id = \Auth::user()->id;
            $employee_id = 0;
        }

        $ClasseScratch = Asset::where('template_id', $template_id)
            ->where('user_id', $user_id)
            // ->whereNotIn('id', function ($query) use ($template_id, $user_id) {
            //     $query->select('asset_id')
            //         ->from(with(new ShowScratchPenalty)->getTable())
            //         ->where('template_id', $template_id)
            //         ->where('owner_id', $user_id)
            //         ->where('type', SCROPT_SCRATCH_PENALITY);
            // })
            ->where('asset_type','!=',1)->get();
        $ClasseJoining = Asset::where('template_id', $template_id)
            ->where('user_id', $user_id)
            // ->whereNotIn('id', function ($query) use ($template_id, $user_id) {
            //     $query->select('asset_id')
            //         ->from(with(new ShowScratchPenalty)->getTable())
            //         ->where('template_id', $template_id)
            //         ->where('owner_id', $user_id)
            //         ->where('type', SCROPT_CLASS_JOINING_PENALITY);
            // })
            ->where('asset_type','!=',1)->get();
        $collection = ShowScratchPenalty::where('template_id', $template_id)
            ->where('owner_id', $user_id)
            ->get();
        return view('shows.scratch.index')->with(compact('ClasseJoining', 'ClasseScratch', 'collection', 'template_id'));


    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function saveScratch(Request $request)
    { $template_id = $request->template_id;

        $isEmail = \Session('isEmployee');
        $userEmail = \Auth::user()->email;

        if ($isEmail == 1) {
            $user_id = getAppOwnerId($userEmail, $template_id);
            $employee_id = \Auth::user()->id;
        } else {
            $user_id = \Auth::user()->id;
            $employee_id = 0;
        }
        $datefrom = $request->date_from;
        $dateto =$request->date_to;
        $datefrom =Carbon::parse($datefrom)->format('Y-m-d');
        $dateto =Carbon::parse($dateto)->format('Y-m-d');

        foreach ($request->scratch_classes as $asset) {
            $model = new ShowScratchPenalty();
            $model->template_id = $template_id;
            $model->owner_id = $user_id;
            $model->penality = $request->penality;
            $model->asset_id = $asset;
            $model->date_from = $datefrom;
            $model->date_to = $dateto;
            $model->type = $request->type;
            $model->employee_id = $employee_id;
            $model->save();
        }

        if ($request->type == 1) {
            \Session::flash('message', 'The scratch restriction has been added');
        } else {
            \Session::flash('message', 'The class restriction has been added');
        }
        return redirect()->route('ShowController-add-scratch', ['template_id' => nxb_encode($template_id)]);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function feedbackTrainer($asset_id)
    {
        $asset_id = nxb_decode($asset_id);
        $asset = Asset::find($asset_id);
        $template_id = $asset->template_id;
        $feedBack = SchedulerFeedBacks::with("horse", "invitee")->where('horse_id', $asset_id)->get();
        return view('MasterTemplate.assets.shows.feedback')->with(compact('feedBack', "template_id", 'asset_id'));

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
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

    /**
     * Show Trainer which riders have registered on their behalf.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function riderIndex($show_id)
    {
        $show_id = nxb_decode($show_id);
        $user_id = \Auth::user()->id;
        $registerList = ManageShowsRegister::with('user')
            ->where("type", SHOW_TYPE_SHOWS)
            ->where("allow_trainer_register", 1)
            ->where("manage_show_id", $show_id)->whereHas('trainer', function ($query) use ($user_id) {
                $query->where('user_id', $user_id);
            })->orderBy('id', "desc")->get();
        return view('shows.trainers.riders.index')->with(compact("show_id", "registerList"));
    }

    /**
     * Show Rider Registration to trainer.
     *
     * @param  int $manage_show_registration id
     * @return \Illuminate\Http\Response
     */
    public function riderDetail($reg_id)
    {
        $reg_id = nxb_decode($reg_id);
        $registration = ManageShowsRegister::with("user")->where('id', $reg_id)->first();

        //dd($registration->toArray());

        $user_id = $registration->user_id;
        $show_id = $registration->manage_show_id;
        $show = ManageShows::where('id', $show_id)->first();
        $template_id = $show->template_id;
        $owner_id = $show->user_id;
         $templateType = GetTemplateType($template_id);
            $collection = Asset::with("ShowClassPrice",'SchedulerRestriction')->where('template_id',$template_id)
                                    ->where('user_id',$owner_id)
                                    ->where('asset_type',0)
                                    ->whereHas("SchedulerRestriction",function ($query) use ($show_id) {
                                         $query->where('show_id',$show_id);
                                    })
                                    ->where('is_combined',0)
                                    ->where('is_split',0);
                                    if($templateType!=TRAINER) {
                                        $collection = $collection->doesnthave("assetParent");
                                    }
             $collection = $collection->get();

            //dd($collection->toArray());
            $parentCollection = Asset::with("ShowAssetInvoice","subAssets.SchedulerRestriction")->where('template_id',$template_id)->where('user_id',$show->user_id)->where('asset_type',1)
                    ->whereHas("subAssets.SchedulerRestriction",function ($query) use ($show_id) {
                        $query->where('show_id',$show_id);
                    })
                    ->where('is_combined',0)
                    ->where('is_split',0)
                    ->get();
            $OwnerHorses = Asset::with("template")->where('user_id',$user_id)
                ->where('asset_type','!=',2)
                ->whereHas("template",function ($query){
                                         $query->where('category',CONST_HORSE_TEMPLATE);
                                    })->get();
            $additional_price = AdditionalCharges::where("template_id",$template_id)->where('app_id',$show->app_id)->get();

        $riderHorses = Asset::with("template")->where('user_id',$user_id)->where('asset_type',2)
            ->whereHas("template",function ($query) use ($show_id) {
                $query->where('category',CONST_HORSE_TEMPLATE);
            })->get();

        $participatedHorses = ClassHorse::select('class_id', 'horse_id')->where('show_id', $show_id)
            ->where('user_id', $user_id)->get()->toArray();

        return view('shows.trainers.riders.viewDetail')->with(compact("OwnerHorses", 'participatedHorses', "parentCollection", "riderHorses", 'registration', "user_id", "reg_id", 'show', 'collection', 'additional_price','templateType'));
    }

    /**
     * Registration Rider by trainer.
     *
     * @param  int $request
     * @return \Illuminate\Http\Response
     */
    public function riderParticipate(Request $request)
    {
        //Save Total price in payment.
        //dd($request->all());
        $trainer_id = \Auth::user()->id;
        $show = ManageShows::select('user_id')->where('id', $request->show_id)->first();

        $MSR = $request->reg_id;
        $horseModel = ClassHorse::where("msr_id", $MSR)->first();


        $model = ManageShowsRegister::find($MSR);
        $model->total_price = $request->total_price;
        $model->additional_fields = json_encode($request->additional);
        //$model->show_reg_number = $increamentModal->show_reg_number +1;
        $model->assets_fields = json_encode($request->get('assets'));
        $model->status = 1;
        $model->unique_horses = $request->get('unique_horses');
        $model->update();

        $show_id = $model->manage_show_id;
        //Invoicing
        if (isset($horseModel->invite_asociated_key)) {
            // $invoice =Invoice::where("invite_asociated_key",$horseModel->invite_asociated_key)->first();
            // $invoice->fields = json_encode($request->assets);
            // $invoice->amount = $request->total_price;
            // $invoice->update();
        }

        //Participate.
        $data = $request->all();
        $user = User::find($request->actual_user_id);
        $user_id = $user->id;
        $user_email = $user->email;
        $uname = $user->name;
        $user_array = ['user_id' => $user_id, 'user_email' => $user_email, 'uname' => $uname];
        $arr = [];
        $arr_module = $request->get('module');
        $array_asset = $request->get('assets');
        $uniqueId = '';

        //$uniqueId = time().mt_rand().$user_id;
        if (isset($horseModel->invite_asociated_key)) {

            $uniqueId = $horseModel->invite_asociated_key;

        }
        foreach ($array_asset as $key => $asset) {
            if ($key == "division") {
                foreach ($asset as $Divkey => $division) {
                    if (isset($division["innerclasses"])) {
                        $belong_to_div = $division['orignal_id'];

                        if (isset($division['id'])) {
                            $div_id = $division["id"];
                            $div_price = $division['price'];
                            $division_total_classes = $division['total_classes'];
                        } else {
                            $div_id = null;
                            $div_price = null;
                            $division_total_classes = null;

                        }
                        foreach ($division["innerclasses"] as $division_classes) {
                            $this->saveHorseInShow($division_classes, $request, $show, $show_id, $uniqueId, $user_array, $MSR, $belong_to_div, $division_total_classes, $div_id, $div_price);
                        }
                    }
                }
            } else {
                $this->saveHorseInShow($asset, $request, $show, $show_id, $uniqueId, $user_array, $MSR);
            }

        }
        \Mail::to($user_email)->send(new TrainerParticipate($show->title, $trainer_id, $array_asset, $user));

        \Session::flash('message', 'Invite has been send to User(s) successfully');
        return redirect()->action('UserController@index');
    }


    public function orders_supplies($template_id, $app_id, $show_id)
    {
        $template_id = nxb_decode($template_id);
        $app_id = nxb_decode($app_id);
        $show_id = nxb_decode($show_id);
        $user_id = \Auth::user()->id;

        $trainers = ManageShowTrainer::select("id")->where("user_id", $user_id)->where("manage_show_id", $show_id)->first();
        if (isset($trainers->id)) {
            $trainerID = $trainers->id;
            $users = ClassHorse::with("MSR", "user")->whereHas('MSR', function ($query) use ($trainerID, $show_id) {
                $query->where('trainer_id', $trainerID);
                $query->where("manage_show_id", $show_id);
            })->groupBy('horse_id')->get();
        } else {
            $users = null;
        }
        $additional_price = AdditionalCharges::where("template_id", $template_id)->where('app_id', $app_id)->get();
        $riderHorses = getParticipatingHorses($show_id);

        $approvedStalls =ShowStallRequest::with('stable')->select('stall_number','id',"approve_stable_id")
            ->where('show_id', $show_id)
            ->where('user_id', $user_id)
            ->where('status', 1)
            ->orderBy('id', 'DESC')->get();
        $stalls = array();
        if (count($approvedStalls)>0) {
            foreach ($approvedStalls as $stall) {
                foreach(explode(',', $stall->stall_number) as $st){
                        $stalls[] = $stall->stable->name." -- ".$st;
                    
                }
            }
        }

        return view('shows.trainers.orderSupplies')->with(compact("show_id", "additional_price",'stalls' ,'riderHorses' ,"users", "app_id"));

    }

    public function order_supplies_save(Request $request)
    {

        $messages = [
            'MSR_ids.required' => 'Kindly select atleast 1 user to split the invoice amoung them!',
        ];


        if (isset($request->supplierId)) {
            $user_id = $request->supplierId;
            $status = $request->status;
        } else {
            $user_id = \Auth::user()->id;
            $status = 0;
        }
        $MS = ManageShows::where('id', $request->show_id)->first();
        $order_id = $request->get('order_id');


        if (!is_null($order_id))
            $MSTS = ManageShowOrderSupplies::findOrFail($order_id);
        else
            $MSTS = new ManageShowOrderSupplies();

        $MSTS->trainer_user_id = $user_id;
        $MSTS->order_title = $request->order_title;
        $MSTS->ordered_as = $request->ordered_as;
        $MSTS->show_owner_id = $MS->user_id;
        $MSTS->show_id = $request->show_id;
        $MSTS->template_id = $MS->template_id;
        $MSTS->additional_fields = json_encode(array_values($request->additional));
        $MSTS->total_amount = $request->additional_price;
        if (isset($request->trainer_comments))
            $MSTS->trainer_comments = $request->trainer_comments;
        $MSTS->status = $status;
        $MSTS->show_owner_comments = $request->show_owner_comments;

        $MSTS->save();

        if (isset($request->selected_horses)) {
            foreach ($request->selected_horses as $horse) {
                $msoh = new ManageShowOrderHorse();
                $msoh->horse_id = $horse;
                $msoh->msos_id = $MSTS->id;
                $msoh->save();
            }
        }

        \Session::flash('message', "Your supplies have been successfully submitted.");
        if (!is_null($order_id))
            return redirect()->back();
        else
            return redirect()->route('ShowController-index');

    }

    public function ViewOrderSupplies($template_id)
    {


        $template_id = nxb_decode($template_id);
        $userEmail = \Auth::user()->email;

        $isEmail = \Session('isEmployee');


        if ($isEmail == 1) {
            $user_id = getAppOwnerId($userEmail, $template_id);
            $employee_id = \Auth::user()->id;
        } else {
            $user_id = \Auth::user()->id;
            $employee_id = 0;
        }


        $suppliesOrders = ManageShowOrderSupplies::where('template_id', $template_id)
                            ->where('show_owner_id', $user_id)
                            ->orderBy('id', 'desc')
                            //->paginate(15);
                            ->get();

        return view('shows.trainers.orderSuppliesRequests')->with(compact("suppliesOrders", "template_id"));

    }

    public function viewOrderDetail($order_id, $orderType)
    {

        $order_id = nxb_decode($order_id);

        $suppliesOrders = ManageShowOrderSupplies::with('orderSupplie')->where('id', $order_id)->first();
        //dd($suppliesOrders->toArray());
        $dataBreadCrumb = [
            'template_id' => nxb_encode($suppliesOrders->template_id),
            'show_id' => nxb_encode($suppliesOrders->show_id)
        ];

        return view('shows.trainers.viewOrderDetail')->with(compact("suppliesOrders",'order_id', "orderType", "dataBreadCrumb"));

    }


    public function viewOrderHistory($template_id, $app_id, $show_id)
    {
        $template_id = nxb_decode($template_id);
        $app_id = nxb_decode($app_id);
        $show_id = nxb_decode($show_id);
        $user_id = \Auth::user()->id;

        $suppliesOrders = ManageShowOrderSupplies::where('template_id', $template_id)->where('show_id', $show_id)
            ->where('trainer_user_id', $user_id)
            ->orderBy('id', 'desc')->get();

        return view('shows.trainers.orderHistory')->with(compact("suppliesOrders", "template_id"));

    }

    public function getSplitInvoice(Request $request)
    {

        $orderArr = json_decode($request->data, true);
        $suppliesOrders = ManageShowOrderSupplies::whereIn('id', $orderArr)->get();
        $arr = [];
        $final = [];

        foreach ($suppliesOrders as $row) {
            $additionalFields = json_decode($row->additional_fields, true);
            foreach ($additionalFields as $row) {
                if (isset($row['approveQty']) && $row['approveQty'] > 0 && $row['status'] == 'completed')
                    $arr[$row['id']][] = $row['approveQty'];
            }
        }
        if (count($arr) > 0) {
            foreach ($arr as $k => $v) {
                $final[] = array('id' => $k, 'approveQty' => array_sum($v));
            }
        }

        return json_encode($final);
    }


    // for new spectators flow to add user as spectators

    function viewSchedulerForm($template_id, $app_id, $show_id)
    {

        $template_id = nxb_decode($template_id);
        $app_id = nxb_decode($app_id);
        $show_id = nxb_decode($show_id);
        $user_id = \Auth::user()->id;

        $FormTemplate = Form::where('template_id', $template_id)->where('form_type', SPECTATOR_REGISTRATION)->first();
        $TemplateDesign = TemplateDesign::where('template_id', $template_id)->first();
        $answer_fields = null;
        $TD_variables = null;
        $pre_fields = null;
        $form_id = null;
        if ($FormTemplate) {
            //MasterTemplate Design Variable  -->
            $TD_variables = getTemplateDesign($TemplateDesign);
            $pre_fields = json_decode($FormTemplate->fields, true);


            // END: MasterTemplate Design Variable  -->
            $form_id = $FormTemplate->id;
        } else {

            $FormTemplate = null;
        }


        return view('shows.spectators.index')->with(compact("template_id", "app_id", "show_id", 'FormTemplate', 'TD_variables', 'pre_fields', 'form_id'));
    }

    function submitSpectatorForm(Request $request)
    {

        $template_id = $request->template_id;
        $show_id = $request->show_id;
        $app_id = $request->app_id;
        $form_id = $request->form_id;

        $user_id = \Auth::user()->id;

        $model = new ManageShowSpectator();

        $model->user_id = $user_id;
        $model->show_id = $show_id;
        $model->template_id = $template_id;
        $model->app_id = $app_id;
        $model->form_id = $form_id;

        $model->fields = submitFormFields($request);
        $model->save();
        return redirect()->route('master-template-masterSchedular', ['template_id' => nxb_encode($template_id), 'show_id' => nxb_encode($show_id), 'spectatorsId' => nxb_encode($app_id)]);

    }


    public function showSpectators($template_id)
    {
        $isEmail = \Session('isEmployee');
        $userEmail = \Auth::user()->email;

        $template_id = nxb_decode($template_id);

        if ($isEmail == 1) {
            $user_id = getAppOwnerId($userEmail, $template_id);
        } else {
            $user_id = \Auth::user()->id;
            $employee_id = 0;
        }


        $manageShows = ManageShows::with(["spectators" => function ($q) {
            $q->orderBy('id', 'Desc');
        }])->where('template_id', $template_id)
            ->where('user_id', $user_id)
            ->get();

         //dd($manageShows->toArray());

        return view('shows.spectators.viewParticipants')->with(compact("template_id", "manageShows"));

    }

    public function spectatorView($id)
    {
        $user_id = \Auth::user()->id;
        $spectator_id = nxb_decode($id);

        $manageShows = ManageShowSpectator::where("id", $spectator_id)->first();
        $show_id = $manageShows->show_id;
        $template_id = $manageShows->template_id;

        /********* Form display start*************/
        $FormTemplate = Form::where('template_id', $template_id)->where('form_type', SPECTATOR_REGISTRATION)->first();
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

                // ArrayPrint($answer_fields);exit;

            }
            // END: MasterTemplate Design Variable  -->
            $formid = $FormTemplate->id;
        }

        /********* Form display end*************/
        return view('shows.spectators.registerview')->with(compact("template_id", "answer_fields", "show_id", 'FormTemplate', 'TD_variables', 'pre_fields'));
    }

    public function GetScratchCount($class_id, $show_id)
    {
        $arr = [];
        if ($class_id > 0) {
            $arr['scratch'] = ClassHorse::where('show_id', $show_id)->where('class_id', $class_id)->where('scratch', 1)->count();
            $arr['unScratch'] = ClassHorse::where('show_id', $show_id)->where('class_id', $class_id)->where('scratch', 0)->count();
        } else {
            $arr['scratch'] = ClassHorse::where('show_id', $show_id)->where('scratch', 1)->count();
            $arr['unScratch'] = ClassHorse::where('show_id', $show_id)->where('scratch', 0)->count();
        }

        return response()->json($arr);


    }

    public function horseAgeRestriction($id, $horse_id)
    {
        $assetBreed = Asset::where('id', $id)->first();

        $horseBreed = Asset::where('id', $horse_id)->first();

        $arr = [];

        $assetBreedRestriction = GetAssetBreed($assetBreed->fields, 3);
        $horseBreedValues = GetAssetBreed($horseBreed->fields, 3);
        // exit;

        $result = !empty(array_intersect($assetBreedRestriction, $horseBreedValues));

        if ($result)
            return "fail";
        else
            return "success";

    }

    public function riderAgeRestriction($id, $horse_id)
    {
        $assetBreed = Asset::where('id', $id)->first();

        $horseBreed = Asset::where('id', $horse_id)->first();

        $arr = [];

        $assetBreedRestriction = GetAssetBreed($assetBreed->fields, 4);
        $horseBreedValues = GetAssetBreed($horseBreed->fields, 4);
        // exit;

        $result = !empty(array_intersect($assetBreedRestriction, $horseBreedValues));

        if ($result)
            return "fail";
        else
            return "success";

    }


    public function horseBreeds($id, $horse_id)
    {
        $assetBreed = Asset::where('id', $id)->first();

        $horseBreed = Asset::where('id', $horse_id)->first();

        $arr = [];

        $assetBreedRestriction = GetAssetBreed($assetBreed->fields, 1);
        $horseBreedValues = GetAssetBreed($horseBreed->fields, 1);
        // exit;

        $result = !empty(array_intersect($assetBreedRestriction, $horseBreedValues));

        if ($result)
            return "fail";
        else
            return "success";

    }

    public function checkShowRestriction($id, $horse_id,$show_id)
    {
        $show_type = ManageShows::where('id',$show_id)->pluck('show_type')->first();

        $horseAsset = Asset::where('id', $horse_id)->first();

        $name = "<a class=\"HorseAsset\" target=\"_blank\" href=\"".url("master-template")."/". nxb_encode($horse_id) . "/edit/assets\" style='margin:0px !important; float:none !important;'>" . GetAssetNamefromId($horse_id)."</a>";

        $arr = [];

        if($show_type!='')
        {
          $data = getShowTypeRestrictions($horseAsset,$show_type);
        }

        return response()->json(["unFilledData"=>$data,"showType"=>$show_type,"Name"=>$name]);

    }

    public function checkRiderRestriction($id, $rider_id,$show_id)
    {

        $show_type = ManageShows::where('id',$show_id)->pluck('show_type')->first();

        $riderAsset = Asset::where('id', $rider_id)->first();

        $name = "<a class=\"HorseAsset\" target=\"_blank\" href=\"".url("master-template")."/". nxb_encode($rider_id) . "/edit/assets\" style='margin:0px !important; float:none !important;'>" . GetAssetNamefromId($rider_id)."</a>";

        $arr = [];

        if($show_type!='')
        {
            $data = getRiderRestrictions($riderAsset,$show_type);
        }

        return response()->json(["unFilledData"=>$data,"showType"=>$show_type,"Name"=>$name]);

    }



    public function trainerBreeds($id, $trainer_id)
    {
        $assetBreed = Asset::where('id', $id)->first();

        $trainerBreed = Asset::where('id', $trainer_id)->first();

        $assetBreedRestriction = GetAssetBreed($assetBreed->fields, 2);

        $trainerBreedValues = GetAssetBreed($trainerBreed->fields, 2);

        // print_r($assetBreedRestriction);
        //print_r($trainerBreedValues);

        $result = !empty(array_intersect($assetBreedRestriction, $trainerBreedValues));

        if ($result)
            return "fail";
        else
            return "success";

    }


    public function viewSponsorRequest($show_id, $id, $type)
    {
        $user_id = \Auth::user()->id;
        $show_id = nxb_decode($show_id);
        $id = nxb_decode($id);

        $answer_fields = null;
        $trainer_id = null;

        $show = ManageShows::where('id', $show_id)->first();
        $FormTemplate = Form::where('template_id', $show->template_id)->where('form_type', SPONSOR_REGISTRATION)->first();

        $template_id = $show->template_id;

        $TemplateDesign = TemplateDesign::where('template_id', $show->template_id)->first();

        $manageShows = ShowSponsors::where('show_id', $show_id)->where('id', $id)->orderBy('id', 'DESC')->first();
        if (!is_null($manageShows)) {

            $show_id = $manageShows->show_id;
            /********* Form display start*************/
            $FormTemplate = Form::where('template_id', $template_id)->where('form_type', SPONSOR_REGISTRATION)->first();
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
        } else {

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


        return view('shows.sponsor.register')->with(compact("show_id", 'FormTemplate', 'TD_variables', 'pre_fields', 'answer_fields', 'template_id', 'type'));

    }


    public function sponsorRegistration($show_id, $type)
    {
        $user_id = \Auth::user()->id;
        $show_id = nxb_decode($show_id);

        $answer_fields = null;
        $trainer_id = null;

        $show = ManageShows::where('id', $show_id)->first();
        $FormTemplate = Form::where('template_id', $show->template_id)->where('form_type', SPONSOR_REGISTRATION)->first();

        $template_id = $show->template_id;

        $TemplateDesign = TemplateDesign::where('template_id', $show->template_id)->first();

        $manageShows = ShowSponsors::where('sponsor_user_id', $user_id)->where('show_id', $show_id)->orderBy('id', 'DESC')->first();
        if (!is_null($manageShows)) {

            $show_id = $manageShows->show_id;
            /********* Form display start*************/
            $FormTemplate = Form::where('template_id', $template_id)->where('form_type', SPONSOR_REGISTRATION)->first();
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
        } else {

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


        return view('shows.sponsor.register')->with(compact("show_id", 'FormTemplate', 'TD_variables', 'pre_fields', 'answer_fields', 'template_id', 'type'));

    }


    public function manageSponsorRequest($template_id)
    {
        $isEmail = \Session('isEmployee');
        $userEmail = \Auth::user()->email;

        $template_id = nxb_decode($template_id);

        if ($isEmail == 1) {
            $user_id = getAppOwnerId($userEmail, $template_id);
        } else {
            $user_id = \Auth::user()->id;
            $employee_id = 0;
        }


        $manageShows = ManageShows::with(["sponsorCategories" => function ($q) {
            $q->orderBy('id', 'Desc');
        }])->where('template_id', $template_id)
            ->where('user_id', $user_id)
            ->orderBy('id', 'Desc')->get();


        //   dd($manageShows->toArray());

        return view('shows.sponsor.sponsorCategoryListing')->with(compact("template_id", "manageShows"));
    }


    public function submitSponsorcategory(Request $request)
    {

        $isEmail = \Session('isEmployee');
        $userEmail = \Auth::user()->email;
        $template_id = \App\ManageShows::where('id',$request->show_id)->pluck('template_id')->first();

        if ($isEmail == 1) {
            $user_id = getAppOwnerId($userEmail, $template_id);
            $employee_id = \Auth::user()->id;
        } else {
            $user_id = \Auth::user()->id;
            $employee_id = 0;
        }



        if ($request->sponsor_id != '')
            $model = SponsorCategories::findOrFail($request->sponsor_id);
        else
            $model = new SponsorCategories();

        $model->show_id = $request->show_id;
        $model->show_owner_id = $user_id;
        $model->category_title = $request->category_title;
        $model->category_price = $request->category_price;
        $model->category_description = $request->category_description;
        $model->sponsor_on_invoice = $request->sponsor_on_invoice;
        $model->sponsor_on_invoice = $request->sponsor_on_invoice;
        $model->employee_id = $employee_id;

        $model->save();

        \Session::flash('message', "Sponsor category has been added successfully.");

        return Redirect::to(URL::previous() . "#" . $request->show_id);


    }


    public function getSponsorCategories($id)
    {

        $model = SponsorCategories::where('id', $id)->first();

        return $model;

    }

    public function sposnorRequest(Request $request)
    {
       $user_id = \Auth::user()->id;

        $data = $request->all();

        $collection = SponsorCategories::where('show_id', $request->show_id)->get();


        $model = new ShowSponsors();

        $model->sponsor_user_id = $user_id;
        $model->show_id = $request->show_id;
        $model->sponsor_user_id = $user_id;
        $model->fields = submitFormFields($request);

        $model->save();
        $sponsorFormId = $model->id;

        $show_owner_id = ManageShows::where('id',$request->show_id)->pluck('user_id')->first();


       $paypalAccountDetail = PaypalAccountDetail::where('userId',$show_owner_id)->count();

        $stripeDetails = ParticipantAccountInformation::where('participant_id',$show_owner_id)->count();

        return view('shows.sponsor.categorySelection')->with(compact("collection", "data", "sponsorFormId","paypalAccountDetail","stripeDetails"));

    }


    public function getSelectedCategories($category_ids, $show_id, $sponsor_form_id)
    {

        $spon = [];
        $arr = [];
        if($category_ids!='null') {
            $categoryArr = json_decode($category_ids);
            $spon = SponsorCategories::whereIn('id', $categoryArr)->get();
        }

        $totalAmount = 0;
        if(count($spon)>0){
        foreach ($spon as $row) {
            $totalAmount = $row->category_price + $totalAmount;
            $arr['id'][] = $row->id;
        }
        }


        $MS = ManageShows::find($show_id);

        $appOwner = User::find($MS->user_id);

        if ($appOwner) {

            $inviteUser = InvitedUser::where('email', '=', $appOwner->email)->where('template_id', $MS->template_id)->first();
            $royalty = $inviteUser->royalty;
        }

        $arr['royaltyFinal'] = twodecimalformate($totalAmount / 100 * $royalty);

        $tfederal = $MS->federal_tax;
        $tstate = $MS->state_tax;
        $arr['taxFederal'] = ($tfederal * $totalAmount) / 100;
        $arr['taxState'] = ($tstate * $totalAmount) / 100;
        $arr['taxTotal'] = twodecimalformate($total = $arr['taxState'] + $arr['taxFederal']);
        $arr['grandTotal'] = $arr['taxTotal'] + $arr['royaltyFinal'] + $totalAmount;
        $arr['show_id'] = $show_id;
        $arr['sponsor_form_id'] = $sponsor_form_id;


        $show_owner_id = ManageShows::where('id',$show_id)->pluck('user_id')->first();

        $paypalAccountDetail = PaypalAccountDetail::where('userId',$show_owner_id)->count();

        $stripeDetails = ParticipantAccountInformation::where('participant_id',$show_owner_id)->count();


        return view('shows.sponsor.sponsorAmount')->with(compact("arr","paypalAccountDetail","stripeDetails"));


    }

    public function deleteSponsors($id)
    {

        $id = nxb_decode($id);
        $model = SponsorCategories::findOrFail($id);
        $model->delete();

        \Session::flash('message', 'Sposnsor category has been deleted successfully');
        return Redirect::to(URL::previous() . "#" . $model->show_id);

    }


    public function showSponsors($template_id)
    {
        $isEmail = \Session('isEmployee');
        $userEmail = \Auth::user()->email;

        $template_id = nxb_decode($template_id);

        if ($isEmail == 1) {
            $user_id = getAppOwnerId($userEmail, $template_id);
        } else {
            $user_id = \Auth::user()->id;
            $employee_id = 0;
        }

        $manageShows = ManageShows::with(["sponsorsBilling" => function ($q) {
            $q->where('payment_status', '1')
                ->with('hasCategory')
                ->orderBy('id', 'Desc');
        }])->where('template_id', $template_id)
            ->where('user_id', $user_id)
            ->orderBy('id', 'Desc')
            ->get();

        // dd($manageShows->toArray());

        return view('shows.sponsor.viewSponsors')->with(compact("template_id", "manageShows"));

    }


    public function showSponsorsDetails($show_id)
    {
        $show_id = nxb_decode($show_id);

        $collection = SponsorCategoryBilling::with('hasCategory')
            ->where('show_id', $show_id)
            ->where('payment_status', 1)
            ->orderBy('id', 'Desc')
            ->get();
        return view('shows.sponsor.viewShowSponsors')->with(compact("show_id", "collection"));
    }


    public function sponsorHistory($show_id)
    {
        $isEmail = \Session('isEmployee');
        $userEmail = \Auth::user()->email;
        $user_id = \Auth::user()->id;

        $show_id = nxb_decode($show_id);

        $collection = SponsorCategoryBilling::with('hasCategory')
            ->where('show_id', $show_id)
            ->where('sender_id', $user_id)
            ->where('payment_status', 1)
            ->orderBy('id', 'Desc')
            ->get()
            ->toArray();

        // dd($collection->toArray());

        //     $collection = SponsorCategoryBelong::whereIn('scb_id',$scbIdArr)->get();

        return view('shows.sponsor.sponsorsHistory')->with(compact("show_id", "collection"));
    }


    public function viewSponsorInvoice($id){

        $id = nxb_decode($id);

        $query = SponsorCategoryBilling::with('hasCategory')->where('id', $id);
        $collection =$query->get();
        $item =$query->first();

        //dd($item->toArray());

        $MS = ManageShows::find($item->show_id);

        $appOwner = User::find($MS->user_id);

        if ($appOwner) {

            $inviteUser = InvitedUser::where('email', '=', $appOwner->email)->where('template_id', $MS->template_id)->first();
            $royalty = $inviteUser->royalty;
        }
        $arr['royalty'] = $royalty;
        $arr['taxFederal'] =$MS->federal_tax;
        $arr['taxState'] = $MS->state_tax;
        $arr['location'] = $MS->location;
        $arr['contact_information'] = $MS->contact_information;

        return view('shows.sponsor.viewSponsorInvoice')->with(compact("collection",'item','arr'));





    }

    public function addScratchEntries($template_id)
    {
        $template_id = nxb_decode($template_id);
        $user_id = \Auth::user()->id;
        $registerList = ManageShows::with(['ManageShowRegister' => function ($q) {
           // $q->where("type", SHOW_TYPE_SHOWS);
            $q->where("status","!=",0);
            $q->orderBy('id','desc');
        }])
        ->where('template_id', $template_id)
        ->where('user_id', $user_id)
        ->orderBy('id', "desc")
        ->get();
        //dd($registerList->toArray());
        return view('shows.addScratchEntries')->with(compact("registerList"));
    }


    public function showStables($template_id)
    {
        $template_id = nxb_decode($template_id);
        $user_id = \Auth::user()->id;

        $manageShows = ManageShows::with(["showStables" => function ($q) {
            $q->orderBy('id', 'Desc');
        }])->where('template_id', $template_id)
            ->where('user_id', $user_id)
            ->orderBy('id', 'Desc')
            ->get();

        //dd($manageShows->toArray());

        return view('shows.stable.viewStables')->with(compact("template_id", "manageShows"));

    }

    public function saveStallTypes(Request $request)
    {

        $data = $request;

        if (isset($data) && count($data) > 0) {

            foreach ($data->stallTypes as $row) {

                if (isset($row['is_update'])) {
                    $m = StallTypes::findorFail($row['is_update']);
                    if (isset($row['utility_type'])) {
                        $m->is_utility = 1;
                        $m->stall_type = $row['utility_type'];
                        $m->price = $row['utility_price'];
                    } else {
                        $m->stall_type = $row['stall_type'];
                        $m->price = $row['price'];
                    }
                    $m->update();

                } else {
                    $model = new StallTypes();

                    // dd($row);

                    if (isset($row['utility_type'])) {
                        $model->is_utility = 1;
                        $model->stall_type = $row['utility_type'];
                        $model->price = $row['utility_price'];
                    } else {
                        $model->stall_type = $row['stall_type'];
                        $model->price = $row['price'];
                    }
                    $model->show_id = $data->show_id;
                    $model->save();
                }

            }
        }
        return Redirect::to(URL::previous() . "#" . $data->show_id);

    }


    public function getStallTypes($show_id)
    {

        $collection = StallTypes::where('show_id', $show_id)->orderBy('is_utility', 'ASC')->get();


        if ($collection->count() > 0)
            return view('shows.stable.getStallTypes')->with(compact("show_id", "collection"));
        else
            return 1;
    }

    public function removeStallType($id)
    {
        $model = StallTypes::find($id);
        $model->delete();
        return 'success';
    }


    public function StallTypesListing($show_id)
    {
        $collection = StallTypes::where('show_id', $show_id)->get();

        return view('shows.stable.stallTypesListing')->with(compact("show_id", "collection"));

    }

    public function saveStable(Request $request)
    {

        if (isset($request->stable_id) && $request->stable_id != '')
            $model = ShowStables::findorFail($request->stable_id);
        else
            $model = new ShowStables();

        $model->show_id = $request->show_id;
        $model->name = $request->name;
        $model->stall_types = json_encode($request->stall_types);
        $model->save();

        \Session::flash('message', 'Stable Entries has been updated Successfully.');
        return Redirect::to(URL::previous() . "#" . $request->show_id);


    }

    public function deleteStable($id, $show_id)
    {

        $id = nxb_decode($id);
        $show_id = nxb_decode($show_id);

        $model = ShowStables::find($id);

        $model->delete();

        \Session::flash('message', 'Stable has been deleted Successfully.');


        return Redirect::to(URL::previous() . "#" . $show_id);


    }


    public function stallRequest($show_id)
    {
        $user_id = \Auth::user()->id;

        $show_id = nxb_decode($show_id);

        $collection = StallTypes::where('show_id', $show_id)->get();

        $userArr = [];

        $trainers = ManageShowsRegister::with('user')
            ->where("type", SHOW_TYPE_SHOWS)
           // ->where("allow_trainer_register", 1)
            ->where("manage_show_id", $show_id)->whereHas('trainer', function ($query) use ($user_id) {
                $query ->where('user_id', $user_id);
            })->orderBy('id', "desc")->get();

        $userArr[$user_id] = \Auth::user()->name;

        foreach ($trainers as $trainer) {
            $userArr[$trainer->user->id] = $trainer->user->name;
        }

        $showStallRequest = ShowStallRequest::With(['stallType','stallHorse'])
            ->where('show_id', $show_id)
            ->where('user_id', $user_id)
            ->orderBy('id', 'DESC')->get();
        //dd($showStallRequest->toArray());

        //count number of utility stalls
        $utility = ShowStallRequest::with('stallType','stable')->where('show_id', $show_id)
            ->where('user_id', $user_id)
            ->where('status',1)
            ->where('assigned_to_horse_uid',null) //Means that this is not assigned to any horse yet.
            ->whereHas('stallType',function($query){
                $query->where('is_utility',SHOW_STALL_UTILITY);
            })->get();
        if (count($utility)>0) {
            $utilityCount = $utility->sum('quantity');
            foreach ($utility as $st)
            {
               $utility_stall_Numbers[$st->stall_type_id][] = $st->stall_number;
            }
            if(count($utility_stall_Numbers)>0){
                $utility_stalls =  json_encode($utility_stall_Numbers);
            }

                $utilityPrice = $utility->first()->stallType->price;
        }else{
            $utilityCount=0;
            $utilityPrice =0;
        }
        $HRS = HorseRiderStall::with('horse')->where('show_id', $show_id)
        ->whereHas('stallrequest',function($query) use ($user_id){
                //$query->whereIn('user_id', array_keys($userArr));
                $query->where('user_id', $user_id);
        })
        ->get();

        return view('shows.stable.stallRequest')->with(compact("show_id",'user_id','utilityPrice','utilityCount','HRS',"collection", "userArr", "showStallRequest","utility_stalls"));

    }

    /**
     * Dividing the utility stall amoung horses to reflect them on invoices.
     *
     * @return \Illuminate\Http\Response
     */


    public function utilityStallDivide(Request $request)
    {


      // dd($request->all());
        $user_id = \Auth::user()->id;
        $horses = $request->printHorseInvoice;
        $horsesCount = count($request->printHorseInvoice);
        $TUP = $request->total_utility_price;
        $uniqueId = time() . mt_rand() . $user_id;
        $show_id=$request->show_id;
        if (count($horses)>0) {
            foreach ($horses as $horse) {
                $model = new ShowStallUtility();
                $model->horse_id = $horse;
                $model->total_price = $TUP;
                $model->assigne_id = $user_id;
                $model->show_id = $show_id;
                $model->divided_amoung = $horsesCount;
                $model->unique_id = $uniqueId;
                $model->save();
            }


            $utility_stalls = json_decode($request->utility_stalls,true);

            $stall_type_id =  key($utility_stalls);

            $utility = ShowStallRequest::where('show_id', $show_id)
                ->where('user_id', $user_id)
                ->where('status',1)
                ->where('stall_type_id',$stall_type_id)
                ->whereHas('stallType',function($query){
                    $query->where('is_utility',SHOW_STALL_UTILITY);
                });

           // dd($utility->get()->toArray());

            $approve_stall_in_office = $utility->pluck('approve_stall_in_office')->first();

            foreach ($utility_stalls as $k=>$v){
                $approve_stall_in_office= array_merge(explode(',',$approve_stall_in_office),$v);
            }

            $approve_stall_in_office =  implode(',',array_filter($approve_stall_in_office));
            //print_r($approve_stall_in_office);exit;
            $utility->update(['assigned_to_horse_uid' => $uniqueId,'approve_stall_in_office'=>$approve_stall_in_office]);
        }
        
        /********* Form display end*************/
        return redirect()->route('ShowController-stallRequest', ['show_id' => nxb_encode($show_id)]);

    }

    public function submitStallRequest(Request $request)
    {
        $user_id = \Auth::user()->id;

        foreach ($request->quantity as $k => $v) {
            if (!is_null($v) && $v != 0) {
                $model = new ShowStallRequest();
                $model->stall_type_id = $k;
                $model->quantity = $v;
                $model->show_id = $request->show_id;
                $model->user_id = $user_id;
                $model->save();

                if (isset($request->assign[$k])) {
                    $assign_values = $request->assign[$k];
                    foreach ($assign_values['horses'] as $key=>$v)
                    {
                        $hModel = new HorseRiderStall();
                        $hModel->show_id = $request->show_id;
                        $hModel->stall_type_id = $k;
                        $hModel->horse_id = $v;
                        $hModel->rider_id = $assign_values['riders'][$key];
                        $hModel->stall_request_id = $model->id;
                        $hModel->save();
                    }

                }


            }
        }
        return redirect()->back();
    }

    public function viewStallRequests($show_id)
    {
        $show_id = nxb_decode($show_id);
        $showStallRequest = ShowStallRequest::With(['stallType', "user"])
            ->where('show_id', $show_id)
            ->orderBy('id', 'desc')->get();

        $stableCollection = ShowStables::where('show_id', $show_id)->get();

        return view('shows.stable.viewStallRequests')->with(compact("show_id", "showStallRequest", "stableCollection"));

    }

    public function getTrainerHorses($user_id, $show_id)
    {
        $horseContains = ClassHorse::where('user_id', $user_id)->where('show_id', $show_id)->groupBy('horse_id')->pluck('horse_id');

        $horseExists =HorseRiderStall ::where('show_id', $show_id)->pluck('horse_id')->toArray();
        if (isset($horseExists))
            $horseExists = array_filter($horseExists);

        $html = '<option value="">--Horse--</option>';

        foreach ($horseContains as $horse_id) {
            if (!in_array($horse_id, $horseExists)) {
                $horseArr[$horse_id] = GetAssetNamefromId($horse_id);
                $html .= "<option value='" . $horse_id . "'>" . GetAssetNamefromId($horse_id) . "</option>";
            }
        }
        return $html;

    }


    public function getEditStable($id)
    {

        $stable = ShowStables::where('id', $id)->first();

        $row['name'] = $stable->name;
        $row['id'] = $stable->id;

        $stallTypes = json_decode($stable->stall_types);

        if($stallTypes) {
            foreach ($stallTypes as $k => $v) {
                $row['quantity'][$k] = $v;
            }
        }
        return $row;
    }


    public function stallRequestResponse(Request $request, $id)
    {
        $stallNumber = '';
         // dd($request->all());

        if (isset($request->stallNumber[$id]) && !is_null($request->stallNumber[$id])) {

            foreach ($request->stallNumber[$id] as $stallNumber) {
                $stallNumberExsit = ShowStallRequest::whereRaw("FIND_IN_SET('".$stallNumber."',stall_number)")->where('approve_stable_id', $request->stable[$id])->where('stall_type_id', $request->stall_type_id)->get()->count();
                $status['status'] = 'failed';
                $status['stallNumber'] =$stallNumber;
                if($stallNumberExsit > 0){
                    return $status;

                }
            }
            $stallNumber = implode(',', $request->stallNumber[$id]);
        }

        $model = ShowStallRequest::findorFail($id);
        $model->status = $request->approve;
        $model->stall_number = $stallNumber;
        $model->approve_stable_id = $request->stable[$id];
        $model->comments = $request->comments;

        $model->update();

        return getViewResponseData($id);

    }

    public function stallAssociateRiders(Request $request, $id)
    {

        $user_id = \Auth::user()->id;

        $stallNumber = '';
        $arr = [];
        $arID = [];
        $data = $request->all();
        $html ='';
        $data = array_non_empty_items($data);

        if(isset($data['showStallHorses'])) {
            foreach ($data['showStallHorses'] as $K=>$v) {

                if (isset($data['stallNumber'][$v])) {
                    $model = HorseRiderStall::findorFail($v);
                    $model->stall_no = $data['stallNumber'][$v];
                    $model->update();

                }
            }
        }
        if (isset($request->riders)) {
            foreach ($request->riders as $key=>$v)
            {
                foreach ($v as $k=>$r) {
                    if($r!=0) {
                        $hModel = new HorseRiderStall();
                        $hModel->show_id = $request->show_id;
                        $hModel->stall_type_id = $request->stall_type_id;
                        $hModel->horse_id = $request->horses[$key][$k];
                        $hModel->rider_id = $r;
                        $hModel->stall_no = $request->stalls[$key][$k];
                        $hModel->stall_request_id = $key;
                        $hModel->save();
                    }
                }
                }
        }



        $horsetrainerArr = HorseRiderStall::where('stall_request_id',$id)->get();


        $userArr = [];

        $trainers = ManageShowsRegister::with('user')
            ->where("type", SHOW_TYPE_SHOWS)
            ->where("allow_trainer_register", 1)
            ->where("manage_show_id", $request->show_id)->whereHas('trainer', function ($query) use ($user_id) {
                $query->where('user_id', $user_id);
            })->orderBy('id', "desc")->get();

        // dd($trainers->toArray());
        $userArr[$user_id] = \Auth::user()->name;

        foreach ($trainers as $trainer) {
            $userArr[$trainer->user->id] = $trainer->user->name;
        }

        return getstallSavedValues($id, $userArr,$horsetrainerArr);

    }

    public function viewStableDetails($stable_id)
    {

        $stable_id = nxb_decode($stable_id);

        $collection = ShowStallRequest::where('approve_stable_id', $stable_id)->groupBy('stall_type_id')->get();

        $stableName = ShowStables::where('id', $stable_id)->pluck('name')->first();


        return view('shows.stable.viewStallDetails')->with(compact("collection", "stable_id", "stableName"));


    }


    public function getRemainigStalls($show_id)
    {

        $stableCollection = ShowStables::where('show_id', $show_id)->get();

        return view('shows.stable.getRemainigStalls')->with(compact("show_id", "stableCollection"));

    }

    public function viewUnpaidStalls($show_id)
    {
        $show_id = nxb_decode($show_id);


        $stallCollection = ShowStallRequest::with('user')->where('show_id', $show_id)->selectRaw('*,GROUP_CONCAT(stall_number) as stall_number')->groupBy('stall_type_id','user_id')->get();

        //ArrayPrint($stallCollection->toArray());

        $st = [];
        $stall_number = [];
        $rStall = [];
        $fStalls = [];
        $collection = [];
        $ar = [];
        $gStall =[];
        $approve_stall_in_office = [];
        $utilityStalls = [];
        foreach ($stallCollection as $row) {

            $assingedStall = [];

            $assingedS = HorseRiderStall::where('stall_type_id', $row->stall_type_id)->where('rider_id', $row->user_id)->where('stall_no','!=','')->selectRaw('GROUP_CONCAT(stall_no) as stall_no')->orderBy('id', 'desc')->get()->toArray();

            if(!is_null($assingedS[0]))
            {
                $assingedStall = explode(',',$assingedS[0]['stall_no']);
            }
            if(!is_null($row->approve_stall_in_office))
             $approve_stall_in_office = explode(',',$row->approve_stall_in_office);

            if (!is_null($row->stall_number)) {
                $stall_number = explode(',', $row->stall_number);
                $stall_number = array_filter($stall_number);

                $rStall = array_diff($stall_number, $assingedStall);

                $gStall=array_diff($rStall,$approve_stall_in_office);




                if (count($gStall) > 0) {
                    $ar = array_values(array_filter($gStall));

                    $is_utility =  StallTypes::where('id',$row->stall_type_id)->where('is_utility',1)->count();
                    if($is_utility>0)
                    {

                        foreach ($stall_number as $r) {
                             $stallCollection = ShowStallRequest::where('show_id', $show_id)->where('stall_type_id', $row->stall_type_id)->whereRaw('FIND_IN_SET("'.$r.'",approve_stall_in_office)')->count();
                            if($stallCollection>0){
                                $utilityStalls[]= $r;
                            }

                        }

                        $UTstall = explode(',', $row->stall_number);
                            $UTstall = array_filter($stall_number);
                            $UTstall=array_diff($UTstall,$utilityStalls);
                            $fStalls[$row->user_id][$row->stall_type_id] = $UTstall;
                   }else {
                        $fStalls[$row->user_id][$row->stall_type_id] = $ar;
                    }
                }
//                else
//                {
//                    $fStalls[$row->user_id][$row->stall_type_id]= [];
//
//                }
            }

        }


        $collection = $fStalls;

        return view('shows.stable.stallUnpaid')->with(compact("show_id", "collection"));

    }

    public function sendNotification(Request $request, $user_id)
    {

        $showTitle = $request->showTitle;
        $collection = json_decode($request->stalls);

        $user = User::where('id', $user_id)->first();

        \Mail::to($user->email)->send(new StallTypeNotification($collection, $user, $showTitle));

        return response()->json(['msg' => 'Unpaid stall numbers notification has been sent successfully']);

    }

    public function checkDivisions(Request $request, $id)
    {

        if ($request->divisions != '0') {
            $divisions = json_decode($request->divisions, true);
        }
        $asset = Asset::where('id', $id)->first();
        $html ='<div class="form-group-inline"><label style="width:5em">Declaration </label>';
        if ($asset->is_required_point_selection == 1) {
            $html = '<select required class="form-control" name="ClassDivision[' . $id . ']"><option value="">Select Declaration</option>';
            if (count($divisions) > 0) {
                foreach ($divisions as $div)
                    $html .= "<option value='" . $div . "'>" . GetAssetNamefromId($div) . "</option>";
            }
            $html .= "</select></div>";
        }

        return $html;


    }


    public function stallRequestInOffice(Request $request,$user_id,$show_id)
    {

       foreach ($request->stallNos as $r) {
        $stallN = current(explode('|||', $r));
        $stallT = last(explode('|||', $r));
        $arr[$stallT][]  =  $stallN;
       }
        foreach ($arr as $k=>$stall) {
            $model = ShowStallRequest::where('stall_type_id',$k)->where('show_id',$show_id)->where('user_id',$user_id)->first();

            $stall = array_merge(explode(',',$model->approve_stall_in_office),$stall);
            $stall = array_filter($stall);

            $model->approve_stall_in_office = implode(',',$stall);

            $model->update();
        }

        return response()->json(['msg' => 'Stall Number has been saved successfully']);
    }

    public function getScoringClasses($asset_id)
    {
     $scoreArr = [];
     $scoreFroms =  SchedulerRestriction::where('asset_id',$asset_id)->where('score_from','!=',null)->get();

     foreach ($scoreFroms as $score){
        $scoreArr[] =explode(',',$score->score_from);
     }

    return $scoreArr;


    }


    public function checkTrainerRestrictions(Request $request)
    {

        $show_type = ManageShows::where('id', $request->show_id)->pluck('show_type')->first();

        $arr = [];
        $data = [];

        if ($show_type != '') {
            $pre_fields = $request->fields;
            if ($show_type == 'Dressage')
                $checkData = ['Breed Association Horse is registered 1', 'Local#', 'Trainer Signature'];
            elseIf ($show_type == 'Hunter')
                $checkData = ['Trainer Signature'];
            elseIf ($show_type == 'Eventing')
                $checkData = ['Breed Association Horse is registered 3', 'Fax', 'Trainer Signature'];
            elseIf ($show_type == 'Western')
                $checkData = [];
            elseIf ($show_type == 'Breeding')
                $checkData = ['Breed Association Horse is registered 1', 'Breed Association Horse is registered 2', 'Breed Association Horse is registered 3', 'Breed Association Horse is registered 4', 'Trainer Signature'];


             //ArrayPrint($pre_fields);

            $name = "";
            $data = [];
            if (isset($checkData)) {
                if (isset($pre_fields)) {
                    foreach ($pre_fields as $key => $value) {
                        if (in_array($value['form_name'], $checkData)) {
                            if (isset($value['answer']) && $value['answer'] == '')
                                $data[] = $value['form_name'];
                            elseif (!isset($value['answer']))
                                $data[] = $value['form_name'];
                        }
                    }

                }
            }


        }

        return response()->json(["unFilledData" => $data, "showType" => $show_type]);


    }

    public function getShowPaginateData($show_duration=null)
    {

        $email = \Auth::user()->email;
        $user_id = \Auth::user()->id;

        $horseContains = 0;
        $riderContains = 0;
        $horseCollection = InvitedUser::where('status', 1)->where('block', 0)->where('email', $email)
            ->whereHas("template", function ($query) {
                $query->where('category', CONST_HORSE_TEMPLATE);
            })->orderBy('id', 'desc')->pluck('template_id');

        if (count($horseCollection) > 0) {
            $horseContains = Asset::whereIn('template_id', $horseCollection)->where('user_id', $user_id)->count();
            $rider = Asset::whereIn('template_id', $horseCollection)->where('asset_type', 2)->where('user_id', $user_id);
            $riderValues = $rider->get();

            // dd($riderValues->toArray());
            $arr = [];
            foreach ($riderValues as $v)
            {
                $arr[]  = GetRiderOwnerStatus($v,"Relationship To Horse");
            }

            $riderOwner = [];
            if(count($arr)>0) {
                $riderowner = array_filter($arr);
                foreach ($riderowner as $item) {
                    if (in_array('Rider', $item)) {
                        $riderOwner[] = 'Rider';
                    }
                    if (in_array('Owner', $item)) {
                        $riderOwner[] = 'Owner';
                    }
                }
            }

            if(!in_array('Rider',$riderOwner))
                $notRiderOwner[] = 'Rider';
            if(!in_array('Owner',$riderOwner))
                $notRiderOwner[] = 'Owner';



            $riderContains = $rider->count();
        }


        $today = Carbon::today();

        $collection = ManageShows::with("template", "appowner");

        if($show_duration=='previous')
            $collection = $collection->whereRaw('MONTH(date_from) < (MONTH(NOW())) AND YEAR(date_from) = YEAR(NOW())');
        elseif($show_duration=='current')
            $collection = $collection->where('date_from',' >=',Carbon::now()->startOfMonth());
        else
            $collection = $collection->whereRaw('MONTH(date_from) > (MONTH(NOW())) AND YEAR(date_from) = YEAR(NOW())');


        $collection = $collection->where("type", SHOW_TYPE_SHOWS)
            ->whereHas("template", function ($query) {
                $query->where('category', CONST_SHOW);
            })->whereHas("appowner", function ($query) {
                $query->where('status', 1)->where('block', 0);
            })->orderBy('date_from', 'desc')->skip(50)
              ->take(50)
              ->get();
        //  dd($collection);
        // ArrayPrint($collection);
        //   $FormTemplate = Form::where('template_id',$template_id)->where('form_type',SPECTATOR_REGISTRATION)->first();

        return view('shows.shows_pagination_view')->with(compact("collection", "horseCollection", "horseContains", "riderContains","riderOwner","notRiderOwner"));
    }
    public function getUnSelectedClasses($form_id,$show_id,$template_id)
    {
        $isEmail = \Session('isEmployee');
        $userEmail = \Auth::user()->email;
        $user_id = \Auth::user()->id;

        if ($isEmail == 1) {
            $user_id = getAppOwnerId($userEmail, $template_id);
            $employee_id = \Auth::user()->id;
        } else {
            $user_id = \Auth::user()->id;
            $employee_id = 0;
        }
        $selectedAssets = SchedulerRestriction::where('show_id',$show_id)->where('form_id','!=',$form_id)->pluck('asset_id')->toArray();
        $AllAssets = Asset::select('id','fields')->whereNotIn('id',$selectedAssets)->where('template_id',$template_id)->where('asset_type',0)->where('user_id',$user_id)->get();
        return view('shows.classesView')->with(compact("AllAssets"));
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * add additional charges on invoice by app owner
     * By Shakil
     */
    public function addAddionalCharges(Request $request,$class_id)
    {
        //dd($request->all());
      $selectedIds = $request->charges_selected;
      if(isset($selectedIds)) {
          foreach ($selectedIds as $key => $value) {
              //dd($value);
              foreach ($value as $k => $v) {
                  $additional = AdditionalCharges::where('id', $k)->first();
                  $selectedIdsArr[] = ['price' => $additional->amount, 'qty' => $request->quantity_selected[$k], 'id' => $additional->id];
              }
          }

          //dd($selectedIdsArr);

          $ch = ClassHorse::where('id', $class_id)->first();
          if (!empty($ch->additional_charges) && $ch->additional_charges != 'null')
              $additional_charges_arr = array_merge(json_decode($ch->additional_charges, true), $selectedIdsArr);
          else
              $additional_charges_arr = $selectedIdsArr;

          //dd($ch->toArray());

          array_unshift($additional_charges_arr, "");
          unset($additional_charges_arr[0]);
          $additional_charges = json_encode($additional_charges_arr);
          $ch->additional_charges = $additional_charges;
          $ch->update();
      }
        return redirect()->back();
    }

    public function exportShows($template_id)
    {

        $isEmail = \Session('isEmployee');
        $userEmail = \Auth::user()->email;

        $template_id = nxb_decode($template_id);

        if ($isEmail == 1) {
            $user_id = getAppOwnerId($userEmail, $template_id);
        } else {
            $user_id = \Auth::user()->id;
            $employee_id = 0;
        }

        $manageShows = ManageShows::where('template_id', $template_id)
            ->where('user_id', $user_id)
            ->orderBy('id', 'Desc')->get();
        //dd($manageShows->toArray());
        return view('shows.participant.showsListing')->with(compact("template_id", "manageShows"));

    }





}
