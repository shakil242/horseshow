<?php
    /**
     * This is Schedular Controller For frontend to control all the Forms in project
     *
     * @author Faran Ahmed (Vteams)
     * @date 17-Feb-2017
     */

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Gate;

use App\Asset;
use App\AssetParent;
use App\ClassHorse;
use App\CombinedClass;
use App\inviteParticipantinvoice;
use App\Mail\ReminderEmail;
use App\ManageShows;
use App\Module;
use App\Participant;
use App\ParticipantAsset;
use App\ParticipantResponse;
use App\ProfileResponse;
use App\SchedulerFeedBacks;
use App\SchedulerRestriction;
use App\ShowClassSplit;
use App\ShowDivision;
use App\SpectatorForm;
use App\Spectators;
use App\subParticipants;
use App\TemplateDesign;
use App\User;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Http\Request;
use App\Form;
use App\Template;
use App\Schedual;
use App\SchedualNotes;
use App\EventModel;
use Illuminate\Support\Facades\DB;
use Session;
use App\ShowPrizing;
use App\ShowPrizingListing;
use App\ShowType;
use App\HorseClassType;
use App\ClassTypePoint;
use App\ChampionDivision;
use App\ChampionDivisionClass;
use App\ShowClassPrice;

class SchedularController extends Controller
{
    /**
     * Display listing of the forms in template which has schedular.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($template_id, $appId,$fromPage=null)
    {

        $template_id = nxb_decode($template_id);
        $appId = nxb_decode($appId);

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


        $location = \Auth::user()->location;
        $schedular_forms = Form::where('template_id', $template_id)->where('scheduler', SCHEDULAR_CHECKED)->get();
        $manageShows = ManageShows::with("schedual")->where('template_id', $template_id)->where('user_id', $user_id)->orderBy('id', 'Desc')->get();

        $ShowType = ShowType::all();
        $AllClassType = HorseClassType::orderBy('id','desc')->get();
        $AllAssets = Asset::select('id','fields')->where('template_id',$template_id)->where('asset_type',0)->where('user_id',$user_id)->get();

        $divisions = Asset::where('template_id',$template_id)
            ->where('user_id',$user_id)
            ->where('asset_type',1)
           ->where('is_combined',0)
           ->where('is_split',0)
            ->get();

        return view('schedular.index')->with(compact("template_id","AllAssets","AllClassType","schedular_forms", "ShowType","manageShows", "location", "appId","divisions","fromPage"));

    }
        /**
     * Display listing of the forms in template which has schedular.
     *
     * @return \Illuminate\Http\Response
     */
    public function classPrice($show_id)
    {
        $show_id = nxb_decode($show_id);
        $collection = SchedulerRestriction::where('show_id',$show_id)->groupBy('asset_id')->get();
        $divisions = ShowDivision::where('show_id',$show_id)->get();
        
        $answer = ShowClassPrice::where('show_id',$show_id)->get()->toArray();
        $Show = ManageShows::select('show_type','template_id')->where('id',$show_id)->first();
        return view('schedular.class.price')->with(compact("collection","show_id",'answer','divisions','Show'));

    }
            /**
     * Save the price for the class.
     *
     * @return \Illuminate\Http\Response
     */
    public function addClassPrice(Request $request)
    {
        $data = array();
        $show_id = $request->show_id;
        $division = $request->division;
        if ( $division== 1) {
            ShowClassPrice::where('show_id',$show_id)->where('is_division',1)->delete();
        }else{

            ShowClassPrice::where('show_id',$show_id)->where('is_division',0)->delete();
        }
        
        foreach ($request->asset as $class) {
            if (isset($class['price']) && $class['price'] !="") {
               if(isset($class['price_judges'])){
                    $data[]=[
                    'class_id'=>$class['id'],
                    'price'=>$class['price'],
                    'show_id'=>$show_id,
                    'price_judges'=>$class['price_judges'],
                    'is_division'=>$division
                    ];
               }else{  
                   $data[]=[
                    'class_id'=>$class['id'],
                    'price'=>$class['price'],
                    'show_id'=>$show_id,
                    'is_division'=>$division
                    ];
               }
            }
        }
        
        ShowClassPrice::insert($data);

        Session::flash('message', 'Your pricing has been saved.');
        return \Redirect::back();

    }

    /**
     * Display listing of the forms in template which has schedular.
     *
     * @return \Illuminate\Http\Response
     */
    public function save(Request $request)
    {

       $postData = $request->all();
        $isEmail = \Session('isEmployee');
        $userEmail = \Auth::user()->email;
        $arr = [];

        if ($isEmail == 1) {
            $user_id = getAppOwnerId($userEmail, $request->template_id);
            $employee_id = \Auth::user()->id;
        } else {
            $user_id = \Auth::user()->id;
            $employee_id = 0;
        }

        $show_id = $request->get("show_id");
        $arr['dateChangeCon'] = $request->get("dateChangeCon");

        if ($show_id != '') {
            $shows = ManageShows::findOrFail($show_id);
        } else {
            $shows = new ManageShows();
        }

        $dateFrom = date('Y-m-d H:i:s',strtotime($request->get("dateFrom")));
        $dateTo = date('Y-m-d H:i:s',strtotime($request->get("dateTo")));

        $shows->title = $request->get("showTitle");
        $shows->template_id = $request->get("template_id");
        $shows->user_id = $user_id;

        $shows->date_from = $dateFrom;
        $shows->date_to = $dateTo;

        $shows->location = $request->get("location");
        $shows->app_id = $request->get("appId");
        $shows->usef_id = $request->get("usef_id");

        $shows->employee_id = $employee_id;
        if(GetTemplateType($request->template_id) == TRAINER){
            $shows->type = SHOW_TYPE_TRAINER;
        }
        $shows->show_type_id = $request->get("show_type_class");
        $shows->federal_tax = $request->get("federal_tax");
        $shows->state_tax = $request->get("state_tax");
        $shows->state_tax = $request->get("state_tax");
        $shows->contact_information = $request->get("contact_information");

        $shows->governing_body = $request->get("governing_body");
        $shows->show_type = $request->get("show_type");
        $shows->time_zone = $request->get("time_zone");
        $shows->show_description = $request->get("show_description");
        $shows->info_on_invoice = $request->get("info_on_invoice");
    
        $shows->save();


        $shows->division()->sync($request->divisions);

        $classTypes = $request->get("asset_class_type");
        $classArray = [];
        $show_id = $shows->id;

        //Class Type Points
        $classtypep = ClassTypePoint::where('show_id', $show_id)->delete();
        if(isset($classTypes)) {
            foreach ($classTypes as $class => $type) {
                if ($type != "" || $type != null) {
                    $classArray[] = ["show_id" => $show_id, "class_id" => $class, "class_type" => $type];
                }
            }
            ClassTypePoint::insert($classArray);
        }

        if (count($request->schedual_id) > 0)
            $scheduleId = $request->schedual_id;
        else
            $scheduleId = [];
//        if (count($request->isReminder) > 0)
//            $isReminder = $request->isReminder;
//        else
//            $isReminder = [];

        //  ArrayPrint($scheduleId);
        $ArrSlotsTime = [];
        $ArrDatetimeschedual = [];
        $ArrAsset = [];
        $ArrSlotsMinute = [];
        $ArrSlotsSecond = [];
        $multipleSelection = [];

        if (count($request->get("schedular_name")) > 0) {

            foreach ($request->get("schedular_name") as $key => $val) {


                //   $schedual_id = $request->schedual_id[$key];
                $schedual_id = $request->schedual_id[$key];

                if (!empty($schedual_id)) {
                    $model = Schedual::findorfail($schedual_id);
                    $model->template_id = $request->get("template_id");
                    $form_id = $request->form_id[$key];

                    $model->user_id = $user_id;
                    $model->form_id = $form_id;
                    $model->show_id = $shows->id;

                    $model->name = $val;


//                    if (is_array($request->isReminder)) {
//                        if (array_key_exists($key, $request->isReminder)) {
//                            if ($request->isReminder[$key] != null) {
//                                $model->isReminder = $request->isReminder[$key];
//                                $model->reminderDays = $request->reminderDays[$key];
//                                $model->reminderHours = $request->reminderHours[$key];
//                                $model->reminderMinutes = $request->reminderMinutes[$key];
//                            }
//                        }
//                    } else {
//
//                        $model->isReminder = 0;
//                        $model->reminderDays = null;
//                        $model->reminderHours = null;
//                        $model->reminderMinutes = null;
//                    }
                    $model->employee_id = $employee_id;

                    $model->update();
                    $arr['schedulerId'] = $model->id;
                    $arr['form_id'] = $form_id;
                    $arr['show_id'] = $shows->id;
                    $resArray = updateRestrcitionTime($postData);


                    Session::flash('message', 'Your restriction has been updated.');
                } else {

                    if ($val != '') {
                        $model = new Schedual();
                        $model->template_id = $request->get("template_id");

                        $model->user_id = $user_id;
                        $model->form_id = $key;
                        $model->name = $val;
                        $model->show_id = $shows->id;

//                        if (array_key_exists($key, $isReminder)) {
//                            if ($request->isReminder[$key] == null) {
//                                $model->isReminder = 0;
//                                $model->reminderDays = $request->reminderDays[$key];
//                                $model->reminderHours = $request->reminderHours[$key];
//                                $model->reminderMinutes = $request->reminderMinutes[$key];
//                            } else {
//                                $model->isReminder = $request->isReminder[$key];
//                                $model->reminderDays = $request->reminderDays[$key];
//                                $model->reminderHours = $request->reminderHours[$key];
//                                $model->reminderMinutes = $request->reminderMinutes[$key];
//                            }
//                        }


                        //  }
                        $model->employee_id = $employee_id;


                        $model->save();


                        $resArray = updateRestrcitionTime($postData);

//                        schedulerReminder($request, $model->id);

                        Session::flash('message', 'Your restriction has been saved.');
                    }

                }

            }


        }
        //schedulerReminder($request,$schedual_id);

        $fromPage = $request->get("fromPage");

        return \Redirect::back();
    }

    /*****created by Shakil****/

    /****using for form listing against the templates for schedualr*****/

    public function getSchedularForms($id, $assetId, $associatedId)
    {

        $user_id = \Auth::user()->id;
        $userEmail = \Auth::user()->email;

        $id = nxb_decode($id);

        $assetId = nxb_decode($assetId);

        $formsScheduler = getschedulerForms($id, $associatedId, $userEmail);

        $forms_collection = $formsScheduler->get();

        $isSubParticipant = 0;

        $subId = 0;

        return view('layouts.partials.schedularFormListing')->with(compact("forms_collection", 'assetId', 'associatedId', 'isSubParticipant', 'subId'));
    }

    public function getSubSchedularForms($id, $assetId, $associatedId, $subId)
    {

        $user_id = \Auth::user()->id;
        $userEmail = \Auth::user()->email;

        $id = nxb_decode($id);

        $assetId = nxb_decode($assetId);

        $formsScheduler = subParticipantSchedulerForms($subId);

        $forms_collection = $formsScheduler->get();

        $isSubParticipant = 1;

        return view('layouts.partials.schedularFormListing')->with(compact("forms_collection", 'assetId', 'associatedId', 'isSubParticipant', 'subId'));
    }

    public function getSchedule($show_id, $form_id, $asset_id, $associated_id, $isSubParticipant, $subId = 0)
    {

        $userEmail = \Auth::user()->email;

        $form_id = checkEncodeDecode($form_id);
        $show_id = checkEncodeDecode($show_id);

        $form = Form::where('id', $form_id)->first();
        $templateId = $form->template_id;
        $isMultpileClasses = count(json_decode($asset_id));
        //$asset_id = (array)json_decode($asset_id);

        $isCombined = 0;
        $htmlContent = '';
        $hasCombined = CombinedClass::where('class_id',$asset_id)->pluck('combined_class_id');

        if(count($hasCombined)>0) {
            $SchedulerCombined = SchedulerRestriction::whereIn('asset_id', $hasCombined)->where('show_id', $show_id)->first();
            if ($SchedulerCombined) {
                $isCombined = $SchedulerCombined->count();
                $eitherInscheduler = CombinedClass::where('combined_class_id', $SchedulerCombined->asset_id)->pluck('class_id');
                $assetClass = SchedulerRestriction::whereIn('asset_id', $eitherInscheduler)->where('show_id', $show_id)->pluck('asset_id');

                if (count($assetClass) > 0) {
                    foreach ($assetClass as $as) {
                        $asAr[] = GetAssetNamefromId($as);
                    }
                    $asAr = array_unique(array_filter($asAr));
                }
                $htmlContent = htmlentities(view('masterScheduler.combinedMessage', ['asAr' => $asAr, 'assetId' => $SchedulerCombined->asset_id,'assetTitle' => $SchedulerCombined->asset_id, 'formId' => $SchedulerCombined->form_id])->render());
            }
        }


        if ($isSubParticipant == 1) {
            $sub_participant_id = \Auth::user()->id;
            $useremail = \Auth::user()->email;
            $subparticipant_collection = subParticipants::where('id', $subId)->first();
            $userId = $subparticipant_collection->user_id;

            session::put('subParticipantInviteeId',$userId);

            $SchedulerRestriction = SchedulerRestriction::where('form_id', $form_id)
                ->where('asset_id', $asset_id)
                ->where('show_id', $show_id)
                ->orderBy('id', 'DESC');
            $FormTemplate = $SchedulerRestriction->get();

        } else {

            session::forget('subParticipantInviteeId');

            $isEmail = \Session('isEmployee');

            if ($isEmail == 1) {
                $userId = getAppOwnerId($userEmail, $templateId);
                $employee_id = \Auth::user()->id;
            } else {
                $userId = \Auth::user()->id;
                $employee_id = 0;
            }

            $SchedulerRestriction = SchedulerRestriction::where('form_id', $form_id)
                ->where('asset_id', $asset_id)
                ->where('show_id', $show_id)
                ->orderBy('id', 'desc');
            $FormTemplate = $SchedulerRestriction->get();

//            dd($FormTemplate->toArray());

        }
        $Templates = Schedual::where('form_id', $form_id)
            ->where('template_id', $templateId)
            ->where('show_id', $show_id);

        $SchedulerName = $Templates->first();


        $scheduler_id = '';
        $schedulerTitle = '';

        $templateType = GetTemplateType($templateId);
        if ($templateType == FACILTY) {
            $scheduler_id = $show_id;
            $schedulerTitle = GetAssetNamefromId($show_id);
        } else {
            if ($SchedulerName) {
                $scheduler_id = $SchedulerName->id;
                $schedulerTitle = $SchedulerName->name;
            }
        }
        if ($FormTemplate)
            $singleTimeSlot = $FormTemplate[0];

        $dateFrom = '';

        if ($singleTimeSlot) {

            $var = explode('-', $singleTimeSlot->restriction);

            //print_r($var);
            if (count($var))
                $dateFrom = date('Y-m-d H:i:s', strtotime($var[0]));

            $slotsTime = $singleTimeSlot->slots_duration;
            if ($slotsTime != '') {
                $slots_duration = $slotsTime;
            } else {
                $slots_duration = 5;
            }
        } else {
            $slots_duration = 5;
        }

        \Session::put('associated_key',$associated_id);

        $scheduals = SchedualNotes::where('form_id', $form_id)
            ->where('template_id', $templateId)
            // ->where('user_id', $userId)
            ->where('asset_id', $asset_id)
            ->get();

        $calendarVal = getCalendarEvents($FormTemplate, $scheduals, $isMultpileClasses, $templateId,$dateFrom);

        $calendar = $calendarVal['calendar'];
        $clId = $calendarVal['clId'];

        $variables = array('templateId' => $templateId, 'userId' => $userId, 'formId' => $form_id, 'calId' => $clId, 'assetId' => $asset_id, 'scheduler_id' => $scheduler_id, 'associatedKey' => $associated_id, 'show_id' => $show_id);

        $calendar = $calendarVal['calendar'];

        /*------------------
        Faran Code: Getting positioning.
        --------------------*/
        //edit positions.
        $existingPositions = ShowPrizingListing::where("asset_id", $asset_id)->where("show_id", $show_id)->where("form_id", $form_id)->get();

        //Getting Champion Division
        $CDC = ChampionDivisionClass::where('class_id',$asset_id)->where('show_id',$show_id)->first();
        $champ = null;
        if (isset($CDC)) {
            $champ = ChampionDivision::find($CDC->cd_id);
        }

        $horse_rating_type = Asset::where('id',$asset_id)->pluck('horse_rating_type')->first();


        /*------------------
       END: ------- Faran Code: Getting positioning.
       --------------------*/
        return view('schedular.schedulerInner')->with(compact("calendar", 'champ',"variables", "existingPositions", 'dateFrom', 'slots_duration','isCombined','htmlContent','horse_rating_type'));

    }

    public function addNotes(Request $request)
    {

        $arr = [];

        $isEmail = \Session('isEmployee');
        $userEmail = \Auth::user()->email;

        if ($isEmail == 1) {
            $cur_user_id = getAppOwnerId($userEmail, $request->template_id);
            $employee_id = \Auth::user()->id;
        } else {
            $cur_user_id = \Auth::user()->id;
            $employee_id = 0;
        }

        $timeSlot = '';
        $schedule_id = $request->get("schedule_id");
      $timeFrom = $request->get("timeFrom");
        $timeTo = $request->get("timeTo");

        if(!is_null($timeFrom) && !is_null($timeTo)) {
            $dateStr = str_replace('-', '/', $timeFrom) . ' - ' . str_replace('-', '/', $timeTo);
            $timeSlot = json_encode($dateStr);
            $request->timeSlot = $timeSlot;
            $timeFrom = Carbon::parse($timeFrom);
            $timeFrom->addSecond();
        }

        $reason = $request->get("reason");

        $templateType = GetTemplateType($request->get("template_id"));

        $subId = $request->get("subId");

        $isSubParticipant = $request->get("isSubParticipant");

        if ($isSubParticipant == 1) {
            $sub_participant_id = \Auth::user()->id;
            $subparticipant_collection = subParticipants::select('user_id')->where('id', $subId)->first();
            $user_id = $subparticipant_collection->user_id;
        } else {
            $user_id = $request->get("userId");
            $sub_participant_id = 0;
        }
        $asset_id = $request->get("asset_id");

//        $assetArr = explode(',', $assets);

        $show_id = checkEncodeDecode($request->get("show_id"));

        if( isset($request->multiple_scheduler_key) && !empty($request->get("multiple_scheduler_key")))
        {
            $multiple_scheduler_key = $request->get("multiple_scheduler_key");
        }else {
            $multiple_scheduler_key = strtotime($request->get("timeFrom"));
        }



        $class_group_key = $request->get("class_group_key");
        $is_group_selection =  SchedulerRestriction::where('scheduler_key',$class_group_key)->where('is_group',1)->get();


        if ($schedule_id) {

                    $schedualNotes = SchedualNotes::findOrFail($schedule_id);
                    $schedualNotes->schedual_id = checkEncodeDecode($request->get("backgrounbdSlotId"));
                    if(isset($request->template_id))
                        $schedualNotes->template_id = checkEncodeDecode($request->template_id);
                   if($user_id!=0)
                    $schedualNotes->user_id = $user_id;
                    if(isset($request->form_id))
                        $schedualNotes->form_id = checkEncodeDecode($request->form_id);
                    $schedualNotes->asset_id = $asset_id;

                    if(isset($request->notes))
                        $schedualNotes->notes = $request->notes;

//                    if(!is_null($timeFrom) && !is_null($timeTo)) {
//                        $schedualNotes->timeFrom = $timeFrom;
//                        $schedualNotes->timeTo = $timeTo;
//                        $schedualNotes->time_slot = '[' . $timeSlot . ']';
//                    }
                    $schedualNotes->sub_participant_id = $sub_participant_id;

                    if(isset($request->ClassHorse))
                    $schedualNotes->horse_id = $request->get("ClassHorse");
                    $schedualNotes->show_id = checkEncodeDecode($request->get("show_id"));
                    $schedualNotes->employee_id = $employee_id;
                    $schedualNotes->reason = $reason;
                    $schedualNotes->restriction_id = $request->get("restriction_id");
                    $schedualNotes->is_multiple_selection = $request->get("is_multiple_selection");
                    $schedualNotes->height = $request->get("height");

                    $schedualNotes->multiple_scheduler_key = $multiple_scheduler_key;

                    if(isset($request->faults_option)) {
                        $faults_option = json_encode($request->faults_option);
                        $schedualNotes->faults_option = $faults_option;
                    }
            // dd($schedualNotes);
                    $schedualNotes->update();

                    if(isset($request->score)) {
                        $this->setScore($schedualNotes, $request->get("score"), $schedualNotes->restriction_id);
                    }


                if ($templateType != FACILTY) {
                    schedulerReminder($schedualNotes, $schedule_id);
                }

                if ($schedualNotes->horse_id != '') {
                    $horseTitle = GetAssetNamefromId($schedualNotes->horse_id);
                    $horse_rider = getHorsesRiderForScheduler($schedualNotes->horse_id ,$schedualNotes->asset_id );

                }
                    else {
                        $horseTitle = '';
                        $horse_rider ='';
                }
                // dd($request->toArray());


                $templateType = GetTemplateType($request->get("template_id"));

                if ($templateType == FACILTY) {
                    $description = '<span>' . getUserNamefromid($schedualNotes->user_id) . '</span> <br />
                                 <span class="assetClass">' . GetAssetNamefromId($schedualNotes->asset_id) . '</span>';

                } else {


                    $description = '<span>' . getUserNamefromid($schedualNotes->user_id) . '</span> 
                                <br /><a class="HorseAsset schedulerLink" target="_blank" style="position: relative; z-index: 99999999999999999999" href="/master-template/' . nxb_encode($schedualNotes->horse_id) . '/horseProfile">' . $horseTitle . '-' . GetHorseRegisteration($schedualNotes->horse_id, $schedualNotes->show_id, 1) . '</a>';

                    if($horse_rider!='')
                        $description .='<br /><a class="HorseAsset schedulerLink" target="_blank" style="position: relative; font-size: 12px; z-index: 99999999999999999999" href="/master-template/'.nxb_encode($horse_rider).'/horseProfile">'.GetAssetNamefromId($horse_rider).'</a>';


                }
                $SchedulerRestriction = SchedulerRestriction::where('form_id', $schedualNotes->form_id)
                    ->where('asset_id', $schedualNotes->asset_id)
                    ->where('show_id', $schedualNotes->show_id)
                    ->first();


                if ($SchedulerRestriction) {
                    $slotsTime = $SchedulerRestriction->slots_duration;
                    if ($slotsTime != '') {
                        $slots_duration = $slotsTime;
                    } else {
                        $slots_duration = 5;
                    }
                } else {
                    $slots_duration = 5;
                }

                $horse_rating_type = Asset::where('id',$schedualNotes->asset_id)->pluck('horse_rating_type')->first();


                return $Response = array(
                    'id' => $schedualNotes->id,
                    'horse_id' => $schedualNotes->horse_id,
                    'asset_id' => $schedualNotes->asset_id,
                    'user_id' => $schedualNotes->user_id,
                    'notes' => $schedualNotes->notes,
                    'show_id' => $schedualNotes->show_id,
                    'template_id' => $schedualNotes->template_id,
                    'formId' => $schedualNotes->form_id,
                    'scheduler_id' => $schedualNotes->schedual_id,
                    'multiple_scheduler_key' => $schedualNotes->multiple_scheduler_key,
                    'description' => $description,
                    'slots_duration' => $slots_duration,
                    'reason' => $reason,
                    'restriction_id' => $request->get("restriction_id"),
                    'is_multiple_selection' => $request->get("is_multiple_selection"),
                    'isMark' => $schedualNotes->is_mark,
                    'score' => $request->get("score"),
                    'horse_rating_type' => $horse_rating_type,
                    'is_update' => 1,
                    'faults_option' =>$request->faults_option,
                    'success' => 'Notes has been Updated successfully',
                );

            } else {


            if($is_group_selection->count()>0)
            {

                foreach ($is_group_selection as $grp)
                {

                    if($grp->asset_id==$asset_id)
                        $other_group_Class = 0;
                    else
                        $other_group_Class = 1;


                    $schedualNotes = new SchedualNotes();

                    $schedualNotes->schedual_id = checkEncodeDecode($request->get("backgrounbdSlotId"));
                    $schedualNotes->template_id = $request->get("template_id");
                    $schedualNotes->user_id = $user_id;
                    $schedualNotes->form_id = $request->get("form_id");
                    $schedualNotes->asset_id = $grp->asset_id;
                    $schedualNotes->notes = $request->get("notes");
                    $schedualNotes->timeFrom = $timeFrom;
                    $schedualNotes->timeTo = $request->get("timeTo");
                    $schedualNotes->time_slot = '[' . $timeSlot . ']';
                    $schedualNotes->sub_participant_id = $sub_participant_id;
                    $schedualNotes->horse_id = $request->get("ClassHorse");
                    $schedualNotes->show_id = checkEncodeDecode($request->get("show_id"));
                    $schedualNotes->employee_id = $employee_id;
                    $schedualNotes->reason = $reason;
                    $schedualNotes->restriction_id = $request->get("restriction_id");
                    $schedualNotes->is_multiple_selection = $request->get("is_multiple_selection");
                    $schedualNotes->height = $request->get("height");
//                    $schedualNotes->score = $request->get("score");
                    $schedualNotes->class_group_key = $request->get("class_group_key");
                    $schedualNotes->other_group_Class = $other_group_Class;


                    $schedualNotes->multiple_scheduler_key = $multiple_scheduler_key;
                    $schedualNotes->save();

                }

            }else {


                $schedualNotes = new SchedualNotes();

                $schedualNotes->schedual_id = checkEncodeDecode($request->get("backgrounbdSlotId"));
                $schedualNotes->template_id = $request->get("template_id");
                $schedualNotes->user_id = $user_id;
                $schedualNotes->form_id = $request->get("form_id");
                $schedualNotes->asset_id = $asset_id;
                $schedualNotes->notes = $request->get("notes");
                $schedualNotes->timeFrom = $timeFrom;
                $schedualNotes->timeTo = $request->get("timeTo");
                $schedualNotes->time_slot = '[' . $timeSlot . ']';
                $schedualNotes->sub_participant_id = $sub_participant_id;
                $schedualNotes->horse_id = $request->get("ClassHorse");
                $schedualNotes->show_id = checkEncodeDecode($request->get("show_id"));
                $schedualNotes->employee_id = $employee_id;
                $schedualNotes->reason = $reason;
                $schedualNotes->restriction_id = $request->get("restriction_id");
                $schedualNotes->is_multiple_selection = $request->get("is_multiple_selection");
                $schedualNotes->height = $request->get("height");
//                    $schedualNotes->score = $request->get("score");
                $schedualNotes->class_group_key = $request->get("class_group_key");

                $schedualNotes->multiple_scheduler_key = $multiple_scheduler_key;
                $schedualNotes->save();
            }
                   // $this->getPlacings($schedualNotes->show_id,$assetArr[$i]);

                    if(isset($request->score)) {
                        $this->setScore($schedualNotes, $request->get("score"), $schedualNotes->restriction_id);
                    }


                if ($templateType != FACILTY) {
                    schedulerReminder($schedualNotes, $schedualNotes->id);
                }

                if ($schedualNotes->horse_id != '') {
                    $horseTitle = GetAssetNamefromId($schedualNotes->horse_id);
                    $horse_rider = getHorsesRiderForScheduler($schedualNotes->horse_id ,$schedualNotes->asset_id );

                }
                    else {
                        $horseTitle = '';
                        $horse_rider ='';
                    }

                if ($templateType == FACILTY) {
                    $description = '<span>' . getUserNamefromid($cur_user_id) . '</span>';
                } else {

//                    $timeF = str_replace('/', '-', $request->get("timeFrom"));
//                    $timeT = str_replace('/', '-', $timeTo);
//
//
//                    $description = '<a style="width:100%;" href="javascript:" onclick="getEventsParticipants(\'' . $schedualNotes->show_id. '\',\'' . $schedualNotes->form_id. '\',\'' .$schedualNotes->asset_id . '\',5,\'' .$timeF. '\',\'' .$timeT . '\',1,\'' .$schedualNotes->restriction_id.'\')" class="viewBtn"   >View participants</a><br>';
//                    $description .= '<a href="javascript:"  onclick="participateInEvent(\'' . $schedualNotes->id . '\',5,0,1,\'' . $user_id . '\')" class="viewBtn participantLink">Participate</a>';


                    $description = '<span>' . getUserNamefromid($cur_user_id) . '</span>
                                <br /><a class="HorseAsset schedulerLink" target="_blank" style="position: relative; z-index: 99999999999999999999" href="/master-template/' . nxb_encode($schedualNotes->horse_id) . '/horseProfile">' . $horseTitle . '-' . GetHorseRegisteration($schedualNotes->horse_id, $schedualNotes->show_id, 1) . '</a>';
                    if($horse_rider!='')
                        $description .='<br /><a class="HorseAsset schedulerLink" target="_blank" style="position: relative; font-size: 12px; z-index: 99999999999999999999" href="/master-template/'.nxb_encode($horse_rider).'/horseProfile">'.GetAssetNamefromId($horse_rider).'</a>';

                }
                $SchedulerRestriction = SchedulerRestriction::where('form_id', $schedualNotes->form_id)
                    ->where('asset_id', $schedualNotes->asset_id)
                    ->where('show_id', $schedualNotes->show_id)
                    ->first();

                if ($SchedulerRestriction) {
                    $slotsTime = $SchedulerRestriction->slots_duration;
                    if ($slotsTime != '') {
                        $slots_duration = $slotsTime;
                    } else {
                        $slots_duration = 5;
                    }
                } else {
                    $slots_duration = 5;
                }
                $horse_rating_type = Asset::where('id',$schedualNotes->asset_id)->pluck('horse_rating_type')->first();

                $is_upDate = 0;

                if(isset($request->is_rider) && $request->is_rider==1 && $request->get("is_multiple_selection")==1)
                {
                    $is_upDate = 1; // in or der to restrict refresh for multi scheduler, we using same thing for master scehduler too
                }

                return $Response = array(
                    'id' => $schedualNotes->id,
                    'horse_id' => $schedualNotes->horse_id,
                    'asset_id' => $schedualNotes->asset_id,
                    'show_id' => $schedualNotes->show_id,
                    'user_id' => $schedualNotes->user_id,
                    'notes' => $schedualNotes->notes,
                    'schedual_id' => $schedualNotes->schedual_id,
                    'description' => $description,
                    'template_id' => $schedualNotes->template_id,
                    'formId' => $schedualNotes->form_id,
                    'slots_duration' => $slots_duration,
                    'multiple_scheduler_key' => $schedualNotes->multiple_scheduler_key,
                    'reason' => $reason,
                    'horse_rating_type' => $horse_rating_type,
                    'restriction_id' => $request->get("restriction_id"),
                    'is_multiple_selection' => $request->get("is_multiple_selection"),
                    'score' => $request->get("score"),
                    'is_update' => $is_upDate,
                    'success' => 'Notes has been added successfully',
                );
            }

    }

    public function deleteNotes($id)
    {

        $Schedual_notes = SchedualNotes::findOrFail($id);
        $Schedual_notes->delete();
        return $Response = array(
            'success' => 'Notes has been deleted successfully',
        );
    }

    public function deleteMultiNotes($id)
    {

        $Schedual_notes = SchedualNotes::where('multiple_scheduler_key', $id)->delete();
       // $Schedual_notes->delete();
        return $Response = array(
            'success' => 'Notes has been deleted successfully',
        );
    }


    public function masterSchedular($id, $show_id, $spectatorsId = null)
    {
        //getRemindersEmails();


        $dataBreadCrumb = [
            'id' => $id,
            'show_id' => $show_id,
            'spectatorsId' => $spectatorsId
        ];

        $template_id = nxb_decode($id);


        $isEmail = \Session('isEmployee');
        $user_email = \Auth::user()->email;


        if ($isEmail == 1) {
            $user_id = getAppOwnerId($user_email, $template_id);
            $employee_id = \Auth::user()->id;
        } else {
            $user_id = \Auth::user()->id;
            $employee_id = 0;
        }

        $spectatorsId = nxb_decode($spectatorsId);
        $show_id = nxb_decode($show_id);

        $schedulerRestrcition = SchedulerRestriction::where('show_id', $show_id)->orderBy('restriction','DESC')->get();

        $formArray = [];

        foreach ($schedulerRestrcition as $row) {

            $formArray[$row->form_id.','.$row->scheduler_id][$row->asset_id] =
            ['asset_id' => $row->asset_id, 'restrcition' => $row->restriction, 'scheduler_id' => $row->scheduler_id];

        }

        $formArray = array_filter($formArray);

      // ArrayPrint($formArray);
        if ($spectatorsId) {

            \Session::put('isSpectator',$spectatorsId);

            $manageShows = ManageShows::where('template_id', $template_id)
                ->where('id', $show_id)
                ->orderBy('id', 'Desc')
                ->get();

        } else {

            \Session::forget('isSpectator');

            $manageShows = ManageShows::where('template_id', $template_id)
                ->where('user_id', $user_id)
                ->orderBy('id', 'Desc')
                ->get();

        }
            $ParticipantAsset = ParticipantAsset::where('template_id', $template_id)
                ->orderBy('id', 'DESC')
                ->first();

            $AssetsForms = ParticipantAsset::select('form_id', 'assets')->where('template_id', $template_id)
                ->orderBy('id', 'DESC')
                ->groupBy('form_id')
                ->get();

            $formsFacilty = Form::where('template_id', $template_id)->where('scheduler', 1)
                ->with(['assets'])
                ->orderBY('id', 'desc')->get();

            //dd($formsFacilty->toArray());


            $formId = '';

            if ($ParticipantAsset)
                $formId = $ParticipantAsset->form_id;

            $FormTemplate = array();
            $scheduals = array();
            $assetId = '';

            if ($formId == '') {
                end($formArray);
                $formId = key($formArray);
            }
            // move the internal pointer to the end of the array

            $AssetsVal = ParticipantAsset::where('form_id', $formId)->where('template_id', $template_id)
                ->orderBy('id', 'ASC')
                ->first();
            if ($AssetsVal) {
                $assets = $AssetsVal->assets;

                $assetArr = json_decode($assets, true);

                $assetId = $assetArr[0];
            } else {
                $assetArr = [];
                $assetId = 0;
            }

            $FormTemplate = Schedual::where('template_id', $template_id)
                ->where('user_id', $user_id)
                ->where('form_id', $formId)
                ->orderBy('show_id', 'Desc')
                ->get();

        if ($manageShows->count()>0) {
            $show_ids = $manageShows[0]['id'];
        }

        $scheduals = SchedualNotes::where('template_id', $template_id)
            ->where('form_id', $formId)
            ->where('asset_id', $assetId)
            ->get();

        $calendarVal = getMasterSchedulerEvents($FormTemplate, $scheduals, $show_id, $template_id);
        $calendar = $calendarVal['calendar'];
        $clId = $calendarVal['clId'];

        //  echo $spectatorsId;exit;

        $variables = array('templateId' => $template_id, 'calId' => $clId, 'assetId' => $assetId, 'spectatorsId' => $spectatorsId, 'show_id' => $show_id);

        return view('masterScheduler.index')->with(compact("template_id", 'AssetsForms', "calendar", 'variables', "dataBreadCrumb", "manageShows", "formArray", "formsFacilty"));

    }

    public function getAssetForms($id, $templateId)
    {
        $isEmail = \Session('isEmployee');
        $userEmail = \Auth::user()->email;

        $formId = nxb_decode($id);
        $templateId = nxb_decode($templateId);

        if ($isEmail == 1) {
            $user_id = getAppOwnerId($userEmail, $templateId);
            $employee_id = \Auth::user()->id;
        } else {
            $user_id = \Auth::user()->id;
            $employee_id = 0;
        }

        $AssetsVal = ParticipantAsset::where('form_id', $formId)
            ->where('template_id', $templateId)
            ->where('invitee_id', $user_id)
            ->first();

        if ($AssetsVal) {
            $assets = $AssetsVal->assets;
            $assetArr = json_decode($assets, true);
            $assetId = $assetArr[0];
        } else
            $assetId = 0;

        $AssetsRes = ParticipantAsset::where('form_id', $formId)
            ->where('template_id', $templateId)
            ->where('invitee_id', $user_id)
            ->get();
        $arr = [];

        foreach ($AssetsRes as $r) {
            $arr['assets'][] = json_decode($r['assets']);
        }

        $k = [];

        if ($arr) {
            for ($i = 0; $i < count($arr['assets']); $i++) {
                for ($j = 0; $j < count($arr['assets'][$i]); $j++) {

                    $k[$arr['assets'][$i][$j]] = $arr['assets'][$i][$j];
                }
            }

            if (isset($k))
                $Assets = array_values($k);
            else
                $Assets = [];
        } else
            $Assets = [];


        $FormTemplate = Schedual::where('form_id', $formId)
            ->where('template_id', $templateId)
            ->where('user_id', $user_id)
            ->get();

        $scheduals = SchedualNotes::where('form_id', $formId)
            ->where('template_id', $templateId)
            ->where('asset_id', $assetId)
            ->get();

        $calendarVal = getMasterSchedulerEvents($FormTemplate, $scheduals);

        $calendar = $calendarVal['calendar'];
        $clId = $calendarVal['clId'];

        $variables = array('templateId' => $templateId, 'formId' => $formId, 'calId' => $clId, 'assetId' => $assetId);

        return view('masterScheduler.schedulerAsset')->with(compact("Assets", "calendar", 'variables'));

    }

    public function getEvents($id, $templateId, $formId, $showId, $spectatorsId = null)
    {

        $assetId = checkEncodeDecode($id);
        $templateId = checkEncodeDecode($templateId);
        $formId = checkEncodeDecode($formId);
        $showId = checkEncodeDecode($showId);

        $isEmail = \Session('isEmployee');
        $userEmail = \Auth::user()->email;

        //Check Either selected class in combined or not

        $isCombined=0;
        $combineValues = [];

        $iscombinedClass = CombinedClass::where('combined_class_id',$assetId)->pluck('combined_class_id');


        if(count($iscombinedClass)>0) {
            $SchedulerCombined = SchedulerRestriction::whereIn('asset_id', $iscombinedClass)->where('show_id', $showId)->first();
            if ($SchedulerCombined) {
                $combinedClass = CombinedClass::where('combined_class_id', $SchedulerCombined->asset_id);
                $combinedClasses = $combinedClass->pluck('class_id');
                $combineValues = SchedulerRestriction::whereIn('asset_id', $combinedClasses)->where('show_id', $showId)->pluck('asset_id');
            }
        }
        $htmlContent ='';


        $hasCombined = CombinedClass::where('class_id',$assetId)->pluck('combined_class_id');


        if(count($hasCombined)>0) {
            $SchedulerCombined = SchedulerRestriction::whereIn('asset_id', $hasCombined)->where('show_id', $showId)->first();
            if ($SchedulerCombined) {
                $isCombined = $SchedulerCombined->count();
                $eitherInscheduler = CombinedClass::where('combined_class_id', $SchedulerCombined->asset_id)->pluck('class_id');
                $assetClass = SchedulerRestriction::whereIn('asset_id', $eitherInscheduler)->where('show_id', $showId)->pluck('asset_id');

                if (count($assetClass) > 0) {
                    foreach ($assetClass as $as) {
                        $asAr[] = GetAssetNamefromId($as);
                    }
                    $asAr = array_unique(array_filter($asAr));
                }
                $htmlContent = htmlentities(view('masterScheduler.combinedMessage', ['asAr' => $asAr, 'assetId' => nxb_encode($SchedulerCombined->asset_id), 'assetTitle' => $SchedulerCombined->asset_id, 'formId' => $SchedulerCombined->form_id])->render());
            }
        }


        $splitId = ShowClassSplit::where('split_class_id',$assetId)->pluck('orignal_class_id')->first();

            if($splitId)
                $asset_id[] = $splitId;
            elseif(count($combineValues)>0)
                $asset_id = $combineValues;
            else
                $asset_id[] = $assetId;

        if ($isEmail == 1) {
            $user_id = getAppOwnerId($userEmail, $templateId);
            $employee_id = \Auth::user()->id;
        } else {
            $user_id = \Auth::user()->id;
            $employee_id = 0;
        }
        $assetArr = [];
        $participants = Participant::select('email')->where('status', 1)
            ->where('template_id', $templateId)
            ->where('invitee_id', $user_id)
            ->whereIn('asset_id', $asset_id)
            ->where('show_id', $showId)
            ->groupBy('email')
            ->get();

        $userArr = [];


            if ($participants) {
                foreach ($participants as $participant) {
                    $users = User::select('name', 'id')->where('email', '=', trim($participant->email))->first();
                    $userArr[$users->id] = $users->name;
                }
            }

        $SchedulerRestriction = SchedulerRestriction::where('form_id', $formId)
            ->where('asset_id', $assetId)
            ->where('show_id', $showId);


        $restrictions = $SchedulerRestriction->orderBy("id")->pluck('id');


        $FormTemplate = $SchedulerRestriction->get();


        $singleTimeSlot = [];

        if ($SchedulerRestriction->count() > 0) {
            $singleTimeSlot = $SchedulerRestriction->first()->toArray();
        }


        $dateFrom = '';

        if (count($singleTimeSlot) > 0) {

            $var = explode('-', $singleTimeSlot['restriction']);
            if (count($var))
                $dateFrom = date('Y-m-d H:i:s', strtotime($var[0]));

            $slotsTime = $singleTimeSlot['slots_duration'];
            if ($slotsTime != '') {
                $slots_duration = $slotsTime;
            } else {
                $slots_duration = 5;
            }
        } else {

            $slots_duration = 5;
        }


        $judgesForm = Form::where('template_id',$templateId)->where('form_type',JUDGES_FEEDBACK)->first();

        $form = Form::select('linkto')->where('id', $formId)->first();
        
        if ($form)
            $feedback_form = Module::select('feedback_form_ids')->where('id', $form->linkto)->first();


        $assetCompulsary = asset::select('feedback_compulsary','horse_rating_type')->where('id',$assetId)->first();


        if (isset($feedback_form)) {
            $feedbk = json_decode($feedback_form->feedback_form_ids);

            $feedbkforms_optional = array();
            $feedbkforms_compulsary = array();
            if ($feedbk) {
                foreach ($feedbk as $fforms) {
                    if (isset($assetCompulsary->feedback_compulsary) && in_array($fforms, json_decode($assetCompulsary->feedback_compulsary,true))) {
                        $feedbkforms_compulsary[$fforms] = getFormNamefromid($fforms);
                    }else{
                        $feedbkforms_optional[$fforms] = getFormNamefromid($fforms);
                    }
                }
            }

            if($judgesForm)
                $feedbkforms_optional[$judgesForm->id] = getFormNamefromid($judgesForm->id);

            $feedBackAssciated_opt = json_encode($feedbkforms_optional);
            $feedBackAssciated_cmp = json_encode($feedbkforms_compulsary);
        } else{
           // \Session::forget('feedBackAssciated');
            $feedBackAssciated_opt = [];
            $feedBackAssciated_cmp = [];
        }
        //\Session::put('feedBackAssciated', $feedBackAssciated);
        \Session::put('assetId', $assetId);

        $scheduals = SchedualNotes::where('form_id', $formId)
            ->where('template_id', $templateId)
            ->where('asset_id', $assetId)
            ->get();

        $calendarVal = getMasterSchedulerEvents($FormTemplate, $scheduals, $assetId, $templateId,$dateFrom);

        $calendar = $calendarVal['calendar'];

        /*------------------
        Faran Code: Getting positioning.
        --------------------*/
        $positions = ShowPrizing::where("asset_id", $assetId)->first();
        $placing = null;
        if ($positions) {
            $placing = json_decode($positions->fields);
        }

        //Getting Champion Division
        $CDC = ChampionDivisionClass::where('class_id',$assetId)->where('show_id',$showId)->first();
        $champ = null;
        if (isset($CDC)) {
            $champ = ChampionDivision::find($CDC->cd_id);
        }

        $asset_id = [];
        $splitId = ShowClassSplit::where('split_class_id',$assetId)->pluck('orignal_class_id')->first();

        if($splitId) {
            $asset_id[] = $splitId;
        }elseif(count($iscombinedClass)>0) {
            $SchedulerCombined = SchedulerRestriction::whereIn('asset_id', $iscombinedClass)->where('show_id', $showId)->first();
            if ($SchedulerCombined) {
                $combinedClass = CombinedClass::where('combined_class_id', $SchedulerCombined->asset_id);
                $combinedClasses = $combinedClass->pluck('class_id');
                $combineValues = SchedulerRestriction::whereIn('asset_id', $combinedClasses)->where('show_id', $showId)->pluck('asset_id');
                $asset_id = $combineValues;
            }
        }else{
            $asset_id[]= $assetId;
        }

        $split_class_id = ShowClassSplit::where('orignal_class_id',$assetId)->pluck('split_class_id');
        $splitId = ShowClassSplit::where('split_class_id',$assetId)->pluck('orignal_class_id')->first();
        $asetRes = [];


        if($split_class_id)
            $asetRes = $split_class_id;
        if($splitId)
            $asetRes[] = $splitId;
        $splitHorseId = [];

       // dd($asetRes);
        $splitHorseId =[];
        if(count($asetRes)>0)
        {
            $existingPositions = ShowPrizingListing::whereIn("asset_id", $asetRes)->where("show_id", $showId)->where("form_id", $formId)->get();

            foreach ($existingPositions as $res)
            {
                $pos_answers = json_decode($res->position_fields, true);

              foreach ($pos_answers as $row)
              {
                  $splitHorseId[]  = $row['horse_id'];
              }

            }

            $splitHorseSchedulerId = SchedualNotes::whereIn('asset_id', $asetRes)
                ->where('show_id', $showId)
                ->pluck('horse_id');

            $participants = ClassHorse::with("user", "horse")
                ->where("show_id", $showId)
                ->whereNotIn("horse_id",$splitHorseSchedulerId)
                ->whereNotIn("horse_id",$splitHorseId)
                ->whereIn("class_id", $asset_id)
                ->groupBy("horse_id")->get();
        }else{

            $participants = ClassHorse::with("user", "horse")
                ->where("show_id", $showId)
                ->whereIn("class_id", $asset_id)
                ->groupBy("horse_id")->get();

        }

        //edit positions.


        /***********************get classic classes for scores*****************/
        $clasicArr = [];
        $pos_an = [];
        foreach ($FormTemplate as $row)
        {
            if($row['score_from']!=null)
            $clasicArr[] =explode(',',$row['score_from']);
        }


        if(count($clasicArr)>0) {

            foreach ($clasicArr as $cls) {

                $existingPos = ShowPrizingListing::whereIn("asset_id", $cls)->where("show_id", $showId)->where("form_id", $formId)->get();
                foreach ($existingPos as $r) {
                    $pos_an[] = json_decode($r->position_fields, true);
                }
            }
           // dd($pos_an);
        }

        if ($spectatorsId == '') {

            \Session::forget('isSpectator');
            $existingPositions = ShowPrizingListing::where("asset_id", $assetId)->where("show_id", $showId)->where("form_id", $formId)->first();

            $pos_answers = null;
            $positioning_id = null;

            if ($existingPositions) {
                $pos_answers = json_decode($existingPositions->position_fields, true);
                $positioning_id = $existingPositions->id;
            }
        } else {

            \Session::put('isSpectator',$spectatorsId);
            $existingPositions = ShowPrizingListing::whereIn("asset_id", $asset_id)->where("show_id", $showId)->where("form_id", $formId)->first();
        }

        $horse_rating_type = $assetCompulsary->horse_rating_type;

        if(isset($pos_answers)) {
            if($horse_rating_type!=1)
                $pos_answers = record_sort($pos_answers, "score",true);
            array_unshift($pos_answers, "phoney");
            unset($pos_answers[0]);
        }

        //dd($pos_answers);

        return view('masterScheduler.calendarInner')->with(compact("calendar","champ","feedBackAssciated_cmp","feedBackAssciated_opt", "placing", "participants", 'formId', 'assetId', 'showId', 'userArr', 'templateId', 'pos_answers', 'existingPositions', 'positioning_id', 'spectatorsId', 'dateFrom', 'slots_duration','combinedClass','isCombined','htmlContent','horse_rating_type','restrictions'));

    }


    /**
     * Faran Code: Save the positions for the template.
     *
     * @return \Illuminate\Http\Response
     */
    public function addPosition(Request $request)
    {
        $assetId = $request->asset_id;
        $showId = $request->show_id;
        $template_id = $request->template_id;
        $form_id = $request->form_id;


        $restrictions = $request->get('restrictions');

        $arr = [];

        $horse_rating_type = Asset::where('id',$assetId)->pluck('horse_rating_type')->first();

        $ClassicIds = SchedulerRestriction::where('show_id',$showId)->whereRaw('FIND_IN_SET('.$assetId.',score_from)')->where('form_id',$form_id)->pluck('asset_id')->first();
        $classicPositionFields = [];
        $classic = [];

        if($horse_rating_type!=1) {

            foreach ($request->participants as $k=>$record) {
                if(isset($record['horse_id'])) {
                    $arr[$k]['position'] = $record['position'];
                    $classic[$k]['position'] = $record['position'];

                    if (isset($record['horse_id'])) {
                        $arr[$k]['horse_id'] = $record['horse_id'];
                        $classic[$k]['horse_id'] = $record['horse_id'];
                    }
                    if (isset($record['rounds'])) {
                        $arr[$k]['score'] = array_sum($record['rounds']);
                        $classic[$k]['score'] = array_sum($record['rounds']);
                       }
                    $arr[$k]['price'] = $record['price'];
                    $classic[$k]['price'] = $record['price'];

                    $classic[$k]['scoreFrom'][$assetId] = ['class_id'=>$assetId,'ClassScore'=>array_sum($record['rounds'])];


                    if (isset($record['rounds']))
                        $arr[$k]['rounds'] = $record['rounds'];
                    if (isset($record['scoreFrom'])) {
                        foreach ($record['scoreFrom'] as $ke=>$val)
                        {
                            $arr[$k]['score']=$arr[$k]['score']+$val['ClassScore'];
                        }
                        $arr[$k]['scoreFrom'] = $record['scoreFrom'];
                    }
                }
            }


            $scoreUpdates =  record_sort_price_position($arr, "score", true,$assetId);
         //   dd($scoreUpdates);
            $participantData = $scoreUpdates;
            $participantsField = json_encode($scoreUpdates);

            $pos_answers = $participantData;

            /*********Classic score***************/
            if($ClassicIds)
            {
                $classPrizing = ShowPrizingListing::where('show_id',$showId)->where('asset_id',$ClassicIds)->first();
                if($classPrizing) {
                    $classicPositionFields = json_decode($classPrizing->position_fields, true);

                    if (isset($classicPositionFields)) {
                        array_unshift($classicPositionFields, "phoney");
                        unset($classicPositionFields[0]);
                    }
                    foreach ($classicPositionFields as $kee => $v) {
                       //ArrayPrint($v);
                        $score = checkHorseExistInClassic($v['horse_id'], $kee, $request->participants);

                        if ($score > 0) {
                            if(isset($classicPositionFields[$kee]['rounds'])) {
                                $scoreTotal = array_sum($classicPositionFields[$kee]['rounds']);
                                $classicPositionFields[$kee]['scoreFrom'][$assetId] = ['class_id' => $assetId, 'ClassScore' => $score];
                                $classScore = 0 ;
                            if(isset($classicPositionFields[$kee]['scoreFrom'])) {
                                foreach ($classicPositionFields[$kee]['scoreFrom'] as $kk => $vv) {
                                    $classScore = $classScore + $classicPositionFields[$kee]['scoreFrom'][$kk]['ClassScore']; // summing up all score classes points
                                }
                            }
                                $classicPositionFields[$kee]['score'] = $scoreTotal +$classScore;
                            }
                            }
                    }
                    $classicPositionFields = record_sort_price_position($classicPositionFields, "score", true, $classPrizing->asset_id);

                    $mod = ShowPrizingListing::find($classPrizing->id);
                    $mod->position_fields = json_encode($classicPositionFields);
                    $mod->form_id = $form_id;

                    $mod->update();
                }else{
                if(isset($classic)) {

                   // dd($classic);
                    $ClassicUpdates = record_sort_price_position($classic, "score", true, $assetId);
                    $ClassicFields = json_encode($ClassicUpdates);
                    $cls = new ShowPrizingListing();
                    $cls->position_fields = $ClassicFields;
                    $cls->show_id = $showId;
                    $cls->form_id = $form_id;

                    $cls->asset_id = $ClassicIds;
                    $cls->save();
                }
                }
            }
            /*********Classic score***************/

        }
        else
        {
            $participantsField = json_encode($request->participants);
            $pos_answers = $request->participants;

        }

        $showPid = ShowPrizingListing::select('id')->where('show_id',$showId)->where('asset_id',$assetId)->where('form_id',$form_id)->first();

        if (count($showPid)>0) {
            $model = ShowPrizingListing::find($showPid->id);
        } else {
            $model = new ShowPrizingListing();
        }
        $model->position_fields = $participantsField;
        $model->show_id = $showId;
        $model->asset_id = $assetId;
        $model->form_id = $form_id;

        $model->save();

        //  return redirect()->back();

        $iscombinedClass = CombinedClass::where('combined_class_id',$assetId)->pluck('combined_class_id');

        $asset_id = [];
        $splitId = ShowClassSplit::where('split_class_id',$assetId)->pluck('orignal_class_id')->first();

        if($splitId) {
            $asset_id[] = $splitId;

            $asset_id[] = $splitId;
        }elseif(count($iscombinedClass)>0) {
            $SchedulerCombined = SchedulerRestriction::whereIn('asset_id', $iscombinedClass)->where('show_id', $showId)->first();
            if ($SchedulerCombined) {
                $combinedClass = CombinedClass::where('combined_class_id', $SchedulerCombined->asset_id);
                $combinedClasses = $combinedClass->pluck('class_id');
                $combineValues = SchedulerRestriction::whereIn('asset_id', $combinedClasses)->where('show_id', $showId)->pluck('asset_id');
                $asset_id = $combineValues;
            }
        }else{
            $asset_id[]= $assetId;
        }

        $participants = ClassHorse::with("user", "horse")->where("show_id", $showId)->whereIn("class_id", $asset_id)->groupBy("horse_id")->get();


        $positions = ShowPrizing::where("asset_id", $assetId)->first();
        $placing = null;
        if ($positions) {
            $placing = json_decode($positions->fields);
        }

        $restrictions = json_decode($restrictions);

       if(isset($pos_answers)) {
           array_unshift($pos_answers, "phoney");
           unset($pos_answers[0]);
       }
        \Session::flash('message', 'Positions has been updated successfully');


        return view('masterScheduler.getPositionsScore')->with(compact("placing","pos_answers","participants","horse_rating_type","restrictions"));
    }

    public function getReminder()
    {

        getRemindersEmails();
    }

    public function markDone(Request $request)
    {
       // dd($request->all());
        if (isset($request->compulsory_form_ids)) {
            $compulsoryForms = $request->compulsory_form_ids;
            $horse_id = $request->ClassHorse;
            $show_id = nxb_decode($request->show_id);
            $schedule_id = $request->schedule_id;
            $SFB = SchedulerFeedBacks::where('horse_id',$horse_id)
                ->where('show_id',$show_id)
                ->where('schedule_id',$schedule_id)
                ->pluck('form_id')->toArray();
            $compulsoryMatch = $compulsoryForms === array_intersect($compulsoryForms,$SFB);
            if (!$compulsoryMatch) {
                return $Response = array(
                    'compulsoryRequired'=>1,
                );
            }
        }
        $cur_user_id = \Auth::user()->id;


        $schedualNotes = schedualNotes::findOrFail($request->schedule_id);
        $is_multiple_selection = $request->is_multiple_selection;

        $scheduler_type = $request->scheduler_type;

        $isSubParticipant = $request->get("isSubParticipant");

        if ($isSubParticipant == 1) {
            $sub_participant_id = \Auth::user()->id;
            $useremail = \Auth::user()->email;
            $subparticipant_collection = subParticipants::where('email', $useremail)->where('asset_id', $request->get("asset_id"))->first();
            $user_id = $subparticipant_collection->user_id;
        } else {
            $user_id = $request->get("userId");
            $sub_participant_id = 0;
        }
        $schedualNotes->sub_participant_id = $sub_participant_id;
        $schedualNotes->is_mark = 1;
        $schedualNotes->update();

        $model = $schedualNotes->first();

        $asset_id = $request->get('asset_id');

        $template_id = $request->get('template_id');

        $form_id = $request->get('form_id');

        $associatedKey = $request->get('associatedKey');

        $invoice = Form::select('invoice')->where('id', $form_id)->first();

        $tempalteModel = Template::select('invoice_to_asset')->where('id', $template_id)->first();
        // && $tempalteModel->invoice_to_asset > 0
        if (isset($invoice)){
        if ($invoice->invoice > 0) {

            pullInvoice($invoice->invoice, $asset_id, nxb_encode($form_id), $template_id, $associatedKey, $schedualNotes->user_id, $sub_participant_id);
        }
        }

        if ($schedualNotes->horse_id != '')
            $horseTitle = GetAssetNamefromId($schedualNotes->horse_id);
        else
            $horseTitle = '';

        $description = '<span>' . getUserNamefromid($cur_user_id) . '</span> <br />
                                 <span class="assetClass">' . GetAssetNamefromId($schedualNotes->asset_id) . '</span>
                                <br /><a class="HorseAsset schedulerLink" target="_blank" style="position: relative; z-index: 99999999999999999999" href="/master-template/' . nxb_encode($schedualNotes->horse_id) . '/horseProfile">' . $horseTitle . '</a>';

        return $Response = array(
            'id' => $request->schedule_id,
            'horse_id' => $schedualNotes->horse_id,
            'asset_id' => $schedualNotes->asset_id,
            'show_id' => $schedualNotes->show_id,
            'description' => $description,
            'scheduler_type'=>$scheduler_type,
            'is_multiple_selection'=>$is_multiple_selection,

            'success' => 'It has been Mark done successfully'
        );
    }

    public function updateTimeSlots(Request $request)
    {
//dd($request->all());
        $reason = $request->get("reason");
        $show_id = $request->get("show_id");

        $primaryScheduler = $request->get("primaryScheduler");

        $isTimeChange = $request->get("is_show_time_change");

        $changeClasses = $request->get("changeClasses");


     if(count($changeClasses)>0) {
         if ($request->get("reminderMinutes") != '')
             $reminderMinutes = $request->get("reminderMinutes");
             $bulkUpdateTimeSlots = bulkUpdateTimeSlots($changeClasses, $isTimeChange, $reminderMinutes, $request->get("asset_id"), $reason, $show_id);
     }

        if ($bulkUpdateTimeSlots) {

            if(isset($primaryScheduler) && $primaryScheduler=='primary')
            {
               // \Session::flash('message', 'Time Uas been updated From ' . $dateFrom . ' To ' . $dateTo . ' By ' . $reminderMinutes . ' minutes');
                return redirect()->back();
            }else {
                return $Response = array(
                    'success' => 'Time has been updated  By ' . $reminderMinutes . ' minutes',
                );
            }
        } else {


            if(isset($primaryScheduler) && $primaryScheduler=='primary')
            {
                \Session::flash('message', 'Something went wrong at here');
                return redirect()->back();
            }

            else {
                return $Response = array(
                    'error' => 'Something went wrong at here',
                );
            }
        }


    }

    /**
     * @return string
     */
    public function sendReminder(Request $request)
    {

        $scheduals = SchedualNotes::where('id', $request->get("schedule_id"))->first();
        $timeSlot = json_decode($scheduals->time_slot, true);

        $fromDate = explode('-', $timeSlot[0]);

        $scheduals->timeSlot = date('Y-m-d H:i:s', strtotime($fromDate[0]));

        $user = User::where('id', $request->get("userId"))->first();

        $scheduals->userName = $user->name;
        $scheduals->assetName =GetAssetNamefromId($scheduals->asset_id);

        \Mail::to($user->email)->send(new ReminderEmail($scheduals));

        return $Response = array(
            'success' => 'Reminder has been sent successfully',
        );
    }


    /**
     * @return string
     */
    public function feedBack($id, $form_id,$spectatorId = null)
    {


        // $invitee_id = \Auth::user()->id;
        $scheduals = SchedualNotes::where('id', $id)
            ->first();
        $horse_id = $scheduals->horse_id;
        $show_id = $scheduals->show_id;
        $show_type = ManageShows::where('id',$show_id)->pluck('show_type')->first();


        if ($scheduals->horse_id != '') {
            $horse_rider = getHorsesRiderForScheduler($scheduals->horse_id ,$scheduals->asset_id );
        }
        else {
            $horse_rider ='';
        }



        $isEmail = \Session('isEmployee');
        $userEmail = \Auth::user()->email;


        if ($isEmail == 1) {
            $invitee_id = getAppOwnerId($userEmail, $scheduals->template_id);
            $employee_id = \Auth::user()->id;
        } else {
            $invitee_id = \Auth::user()->id;
            $employee_id = 0;
        }


        $form = Form::select('linkto')->where('id', $scheduals->form_id)->first();

        $feedback_form = Module::select('feedback_form_ids')->where('id', $form->linkto)
            ->first();
        if ($form_id ==null) {
            $FFid = $feedback_form->feedback_form_id;
            $FormTemplate = Form::where('id', $FFid)->first();
        }else{
            $FFid = $form_id;
            $FormTemplate = Form::where('id', $form_id)->first();
        }
        $TemplateDesign = TemplateDesign::where('template_id', $scheduals->template_id)->first();


        //MasterTemplate Design Variable  -->
        $TD_variables = getTemplateDesign($TemplateDesign);
        $pre_fields = json_decode($FormTemplate->fields, true);
        // END: MasterTemplate Design Variable  -->

        //Check if answer exist $SFB = Scheduler Feedback
        $SFB = SchedulerFeedBacks::where('horse_id',$horse_id)
                ->where('show_id',$show_id)
                ->where('schedule_id',$scheduals->id)
                ->where('form_id',$FFid)
                ->first();
        $answer_fields = array();

        if (count($SFB)>0) { 
            $answer_fields = json_decode($SFB->fields, true);
        }

        return view('masterScheduler.feedBack')->with(compact('FormTemplate',"horse_id",'show_id','answer_fields', 'TD_variables', 'pre_fields', 'scheduals', 'invitee_id', 'spectatorId','horse_rider','show_type'));

    }

    /**
     * @return string
     */

    public function saveFeedBack(Request $request)
    {
        $isEmail = \Session('isEmployee');
        if ($isEmail == 1) {
            $employee_id = \Auth::user()->id;
        } else {
            $employee_id = 0;
        }

      $form_type = Form::where('id',$request->form_id)->pluck('form_type')->first();

        $model = new SchedulerFeedBacks();

        $model->template_id = $request->template_id;
        $model->form_id = $request->form_id;
        $model->asset_id = $request->asset_id;
        $model->invitee_id = $request->invitee_id;
        $model->user_id = $request->user_id;
        $model->schedule_id = $request->schedule_id;
        $model->spectator_id = $request->spectator_id;
        $model->horse_id = $request->horse_id;
        $model->employee_id = $employee_id;
        $model->show_id = $request->show_id;
        $model->feed_back_type = $form_type;

        $model->fields = submitFormFields($request);

        $model->save();

        \Session::flash('message', 'Feedback has been stored successfully');

        return redirect()->back();

    }


    public function liveSearch(Request $request)
    {
        $search = $request->id;

        if (is_null($search)) {
            return view('masterScheduler.livesearch');
        } else {
            $posts = User::where('name', 'LIKE', "%{$search}%")->get();

            return view('masterScheduler.livesearchajax')->with(compact('posts'));
        }
    }

    //ajax live search

    public function search(Request $request)
    {
        // try {
        // Start looking for the query
        $user_id = \Auth::user()->id;


        $template_id = $request->get('template_id');
        $spectatorsId = $request->get('spectatorsId');
        $show_id = $request->get('show_id');


        $html = '';

        $limit = 5;

        if ($request->get('ls_items_per_page')) {
            $limit = $request->get('ls_items_per_page');
        }

        $offSet = $limit * ($request->get('ls_current_page') - 1);

        $headers = ['Name','Horse Name',  'Module', 'Class', 'Status', 'Start Time', 'End Time', 'Action'];

        if (!empty($headers)) {
            $html .= '<tr>';
            foreach ($headers as $aHeader) {
                $html .= "<th>" . $aHeader . "</th>";
            }
            $html .= '</tr>';
        }

        $input = trim($request->get('ls_query'));

        $sched = getuserSearchRecords($template_id,$show_id, $input);

        $Total = $sched->count();

        $rows = $sched->limit($limit)->offset($offSet)->get();

        if ($Total > 0) {
            foreach ($rows as $row) {

                $pre_fields1 = json_decode($row->time_slot, true);

                $var1 = explode('-', $pre_fields1[0]);

                $dateFrom = date('Y-m-d H:i:s', strtotime($var1[0]));
                $dateTo = date('Y-m-d H:i:s', strtotime($var1[1]));

                $html .= '<tr>';
                $html .= "<td>" . $row->userName . "</td>";
                $html .= '<td><a class="HorseAsset" target="_blank"  href="/master-template/'.nxb_encode($row->horse_id).'/horseProfile" >'. GetAssetNamefromId($row->horse_id) . '</a></td>';
                $html .= "<td>" . $row->SchedualName . "</td>";
                $html .= "<td>" . GetAssetNamefromId($row->asset_id) . "</td>";
                if ($row->is_mark == 1)
                    $html .= "<td>Done</td>";
                else {

                    if ($spectatorsId == '')
                        $html .= "<td><a href='javascript:' onclick='searchMarkDone(this," . $row->id . ")'>Mark Done</a> </td>";
                    else
                        $html .= "<td>Pending</td>";
                }
                $html .= "<td>" . $dateFrom . "</td>";
                $html .= "<td>" . $dateTo . "</td>";
                $html .= "<td><a onclick='viewSearchScheduler(".$row->id.",".$row->form_id.",".json_encode(nxb_encode($row->asset_id)).",".json_encode(nxb_encode($row->show_id)).",".json_encode(nxb_encode($row->template_id)).",".json_encode(nxb_encode($row->form_id)).")' href='javascript:'>View</a> </td>";

                $html .= '</tr>';
            }
        } else {

            $html .= "<tr><td colspan='7' style='text-align: center'>There is no result for test</td></tr>";
        }


        $totalPages = ceil($Total / $limit);

        //echo $html;exit;

        $result = json_encode([
            'html' => $html,
            'number_of_results' => $Total,
            'total_pages' => $totalPages,
        ]);


        return json_encode([
            'status' => "success",
            'message' => "<tr><td class='success'>Successful request</td></tr>",
            'result' => $result
        ]);

    }

    /**
     * Process the request
     */
    public function searchMarkDone($schedule_id)
    {
        $schedualNotes = schedualNotes::findOrFail($schedule_id);
        $schedualNotes->is_mark = 1;
        $schedualNotes->update();
    }

    public function saveSchedulerTime(Request $request)
    {

        $template_id = $request->get('template_id');

        $model = Template::findOrFail($template_id);
        $model->date_from = $request->get('dateFrom');
        $model->date_to = $request->get('dateTo');
        $model->update();

    }

    public function participantScheduler($template_id, $asset_id, $associatedKey, $show_id, $isSubParticipant, $subId = 0)
    {
        $userEmail = \Auth::user()->email;
        $user_id = \Auth::user()->id;
        $template_id = nxb_decode($template_id);
        $asset_id = nxb_decode($asset_id);
        $show_id = nxb_decode($show_id);

        /********* For combined Classes************/

        $assetCom = Participant::where('invite_asociated_key', '=', $associatedKey)->where('show_id', $show_id)->pluck('asset_id')->toArray();

        $Combined = CombinedClass::whereIn('class_id',$assetCom);
        $hasCombined = $Combined->pluck('combined_class_id');

        //  dd($hasCombined);

        $heights = [];
        if(count($hasCombined)>0) {
            $SchedulerCombined = SchedulerRestriction::whereIn('asset_id', $hasCombined)->where('show_id', $show_id)
                ->orderBY("restriction","DESC")
                ->get();
            $heights = $Combined->pluck('heights')->first();
            $heights = json_decode($heights,true);
        }

        /*********end For combined Classes************/


      $splitId = ShowClassSplit::where('orignal_class_id',$asset_id)->pluck('split_class_id');
        if($splitId){
            $splitEntry = SchedulerRestriction::whereIn('asset_id', $splitId)->where('show_id', $show_id)
                ->orderBY("restriction","DESC")
                ->get();
        }
        $moduleArr = [];
        $schedulerRestrcition = [];
        $subInviteeId = '';
        if ($isSubParticipant == 1) {

            $subParticipant = subParticipants::where('id', $subId)->first();
            $permission = json_decode($subParticipant->modules_permission, true);

            if(is_array($permission))
                $moduleArr = array_keys($permission);

            $subInviteeId = $subParticipant->user_id;

            $formArr = Form::whereIn('linkto', $moduleArr)->pluck('id');
            $schedulerRestrcition = SchedulerRestriction::where('show_id', $show_id)
                ->where('asset_id', $asset_id)
//                ->whereIn('form_id', $formArr)
                ->orderBY("restriction","DESC")
                ->get();
        } else {
            $templateType = GetTemplateType($template_id);
            if ($templateType == FACILTY) {

                $assets = Participant::where('invite_asociated_key', '=', $associatedKey)->where('show_id', $show_id)->pluck('asset_id');

                $modules_permission = Participant::where('invite_asociated_key', '=', $associatedKey)->where('show_id', $show_id)->pluck('modules_permission')->first();

                $permission = json_decode($modules_permission, true);
                $moduleArr = array_keys($permission);
                $formArr = Form::whereIn('linkto', $moduleArr)->pluck('id');

                $schedulerRestrcition = SchedulerRestriction::where('show_id', $show_id)
                    ->whereIn('asset_id', $assets)
                    ->whereIn('form_id', $formArr)
                    ->orderBY("restriction","DESC")
                    ->get();
            } else {
                $assets = Participant::where('invite_asociated_key', '=', $associatedKey)->where('show_id', $show_id)->pluck('asset_id');
                $schedulerRestrcition = SchedulerRestriction::where('show_id', $show_id)
                    ->whereIn('asset_id', $assets)
                    ->orderBY("restriction","DESC")
                    ->get();
            }

        }
        $formArray = [];
        $AssetArray = [];
        $form_id = '';

        foreach ($schedulerRestrcition as $row) {

            $formArray[$row->form_id.','.$row->scheduler_id][$row->asset_id] = ['asset_id' => $row->asset_id, 'restrcition' => $row->restriction, 'scheduler_id' => $row->scheduler_id];

            $assetArray[$row->asset_id] = ['asset_id' => $row->asset_id, 'restrcition' => $row->restriction, 'scheduler_id' => $row->scheduler_id, 'form_id' => $row->form_id];

            $form_id = $row->form_id;
        }


        if(isset($SchedulerCombined)) {
            foreach ($SchedulerCombined as $r) {
                $formArray[$r->form_id . ',' . $r->scheduler_id][$r->asset_id] = ['asset_id' => $r->asset_id, 'restrcition' => $r->restriction, 'scheduler_id' => $r->scheduler_id];
            }
        }
        if(isset($splitEntry))
        {
            foreach ($splitEntry as $s) {
                $formArray[$s->form_id . ',' . $s->scheduler_id][$s->asset_id] = ['asset_id' => $s->asset_id, 'restrcition' => $s->restriction, 'scheduler_id' => $s->scheduler_id];
            }

        }


        $formArray = array_filter($formArray);
        $variables = array('templateId' => $template_id, 'userId' => $user_id, 'assetId' => $asset_id, 'show_id' => $show_id, 'associatedKey' => $associatedKey, 'associatedKey' => $associatedKey, 'isSubParticipant' => $isSubParticipant, 'subId' => $subId, 'form_id' => $form_id, 'scheduler_id' => $show_id,'subInviteeId'=>$subInviteeId);

        return view('schedular.calendar', compact('calendar', 'variables', 'formArray', 'assetArray', 'dateFrom', 'slots_duration','heights'));

    }

    public function sendInvite(Request $request)
    {

        \Session::forget('isHorseAlreadySelected');

        // dd($request->toArray());

        $isEmail = \Session('isEmployee');
        $userEmail = \Auth::user()->email;


        if ($isEmail == 1) {
            $user_id = getAppOwnerId($userEmail, $request->template_id);
            $employee_id = \Auth::user()->id;
        } else {
            $user_id = \Auth::user()->id;
            $employee_id = 0;
        }

        $form_id = $request->form_id;
        $template_id = $request->template_id;
        $show_id = $request->show_id;
        $asset_id = $request->asset_id;
        $notes = $request->notes;

        $schedulerId = [];

        $class_group_key = $request->get("class_group_key");
        $is_group_selection =  SchedulerRestriction::where('scheduler_key',$class_group_key)->where('is_group',1)->get();

        foreach ($request->users as $key => $v) {

           if($is_group_selection->count()>0)
           {

             foreach ($is_group_selection as $grp)
             {

                 if($grp->asset_id==$asset_id)
                     $other_group_Class = 0;
                 else
                     $other_group_Class = 1;

                 $timeFrom = $request->timeFrom[$key];
                 $height = $request->heights[$key];

                 $timeFrom = Carbon::parse($timeFrom);

                 $timeFrom->addSecond();

                 $timeTo = $request->timeTo[$key];

                 $dateStr = str_replace('-', '/', $timeFrom) . ' - ' . str_replace('-', '/', $timeTo);

                 $timeSlot = json_encode($dateStr);

                 $schedualNotes = new SchedualNotes();

                 $schedualNotes->schedual_id = $request->get("backgrounbdSlotId");
                 $schedualNotes->template_id = $request->get("template_id");
                 $schedualNotes->user_id = $v;
                 $schedualNotes->form_id = $request->get("form_id");
                 $schedualNotes->asset_id = $grp->asset_id;
                 $schedualNotes->notes = $request->get("notes");
                 $schedualNotes->timeFrom = $timeFrom;
                 $schedualNotes->timeTo = $timeTo;
                 $schedualNotes->time_slot = '[' . $timeSlot . ']';
                 $schedualNotes->invited_by = $user_id;
                 $schedualNotes->horse_id = $request->ClassHorse[$key];
                 $schedualNotes->show_id = $request->get("show_id");
                 $schedualNotes->is_multiple_selection = $request->get("is_multiple_selection");
                 $schedualNotes->restriction_id = $request->get("restriction_id");
                 $schedualNotes->class_group_key = $request->get("class_group_key");
                 $schedualNotes->other_group_Class = $other_group_Class;

                 $schedualNotes->multiple_scheduler_key = strtotime($timeFrom);

                 $schedualNotes->employee_id = $employee_id;

                 $schedualNotes->height = $height;
                 // dd($schedualNotes->toArray());
                 $schedualNotes->save();

             }

           }else {
               $timeFrom = $request->timeFrom[$key];
               $height = $request->heights[$key];

               $timeFrom = Carbon::parse($timeFrom);

               // dd($is_group_selection->toArray());

               $timeFrom->addSecond();

               $timeTo = $request->timeTo[$key];

               $dateStr = str_replace('-', '/', $timeFrom) . ' - ' . str_replace('-', '/', $timeTo);

               $timeSlot = json_encode($dateStr);

               $schedualNotes = new SchedualNotes();

               $schedualNotes->schedual_id = $request->get("backgrounbdSlotId");
               $schedualNotes->template_id = $request->get("template_id");
               $schedualNotes->user_id = $v;
               $schedualNotes->form_id = $request->get("form_id");
               $schedualNotes->asset_id = $asset_id;
               $schedualNotes->notes = $request->get("notes");
               $schedualNotes->timeFrom = $timeFrom;
               $schedualNotes->timeTo = $timeTo;
               $schedualNotes->time_slot = '[' . $timeSlot . ']';
               $schedualNotes->invited_by = $user_id;
               $schedualNotes->horse_id = $request->ClassHorse[$key];
               $schedualNotes->show_id = $request->get("show_id");
               $schedualNotes->is_multiple_selection = $request->get("is_multiple_selection");
               $schedualNotes->restriction_id = $request->get("restriction_id");
               $schedualNotes->class_group_key = $request->get("class_group_key");

               $schedualNotes->multiple_scheduler_key = strtotime($timeFrom);

               $schedualNotes->employee_id = $employee_id;


               $schedualNotes->height = $height;


               // dd($schedualNotes->toArray());
               $schedualNotes->save();
           }
            $customDateFrom = date('Y-m-d H:i:s', strtotime($timeFrom));
            $customDateTo = date('Y-m-d H:i:s', strtotime($timeTo));
            $customArr['customDateFrom'][] = $customDateFrom;
            $customArr['customDateTo'][] = $customDateTo;

            $customArr['multiple_scheduler_key'] = $schedualNotes->multiple_scheduler_key;
            $customArr['is_multiple_selection'] = $schedualNotes->is_multiple_selection;

            schedulerReminder($schedualNotes, $schedualNotes->id);

            $customArr['schedulerId'][] = $schedualNotes->id;

            if ($schedualNotes->horse_id != '') {
                $horseTitle = GetAssetNamefromId($schedualNotes->horse_id);
                $horse_rider = getHorsesRiderForScheduler($schedualNotes->horse_id ,$schedualNotes->asset_id );

            }
            else {

                $horseTitle = '';
                $horse_rider='';
            }

            $description = '<span>' . getUserNamefromid($v) . '</span> <br />
                                <br /><a class="HorseAsset schedulerLink" target="_blank" style="position: relative; z-index: 99999999999999999999" href="/master-template/' . nxb_encode($schedualNotes->horse_id) . '/horseProfile">' . $horseTitle . '-' . GetHorseRegisteration($schedualNotes->horse_id, $schedualNotes->show_id, 1) . '</a>';

            if($horse_rider!='')
                $description .='<br /><a class="HorseAsset schedulerLink" target="_blank" style="position: relative; font-size: 12px; z-index: 99999999999999999999" href="/master-template/'.nxb_encode($horse_rider).'/horseProfile">'.GetAssetNamefromId($horse_rider).'</a>';

            $customArr['userName'][] = $description;

            $SchedulerRestriction = SchedulerRestriction::where('form_id', $schedualNotes->form_id)
                ->where('asset_id', $schedualNotes->asset_id)
                ->where('show_id', $schedualNotes->show_id)
                ->first();


            if ($SchedulerRestriction) {

                $slotsTime = $SchedulerRestriction->slots_duration;
                if ($slotsTime != '')
                    $customArr['slots_duration'][] = $slotsTime;
                else
                    $customArr['slots_duration'][] = 5;

            }


        }


        $horse_rating_type = Asset::where('id',$asset_id)->pluck('horse_rating_type')->first();


        $request->request->add($customArr);

        return $Response = array(
            'is_multiple_selection' =>$request->get("is_multiple_selection") ,
            'horse_rating_type' =>$horse_rating_type,
            'results' => json_encode($request->toArray()),
            'success' => 'Notes has been added successfully',
        );

    }

    public function getCourses($show_id,$restriction_id, $user_id)
    {

       // $show_id = nxb_decode($show_id);
       $email = getUserEmailfromid($user_id);

        $associatedKey = session('associated_key');
        $restriction = SchedulerRestriction::where('id',$restriction_id)->pluck('restriction')->first();

        $assetInRestriction  =SchedulerRestriction::where('restriction',$restriction)->pluck('asset_id')->toArray();

        $assets = Participant::where('show_id', $show_id)->where('email',$email)->pluck('asset_id')->toArray();

        $assetArrId=array_unique($assets);

        $html = '<div class="form-group"><label>Select Asset</label> <select onchange="getMultipleHorseAssets('.$show_id.','.$user_id.',this,2)"  required   name="assets[]" class="mySelect  form-control">';
        $html .= "<option value=''>Select --</option>";

        if (count($assetArrId) > 0) {
            foreach ($assetArrId as $crs) {
                if(in_array($crs,$assetInRestriction))
                $html .= "<option value='" . $crs . "'>" . GetAssetNamefromId($crs) . "</option>";
            }
        } else {

            $html .= "<option value=''>No Course Found</option>";
        }
        $html .= ' </select></div>';
        return $html;

    }

    public function getTrainerHorses($show_id, $asset_id, $requestType, $user_id = null)
    {

        if ($user_id == '') {
            $user_id = \Auth::user()->id;
        }
        if ($requestType == 'single') {
            $name = 'ClassHorse';
        } else {

            $name = 'ClassHorse[]';
        }


        $classHorses = ClassHorse::where('show_id', $show_id)
            ->where('class_id', $asset_id)
            ->where('user_id', $user_id)
            ->groupBy('horse_id')
            ->get();


        $html = '<label>Select Horse</label> <select required   name="' . $name . '" class="ClassHorse ClassHorsess selectpicker form-control">';

        if ($classHorses->count() > 0) {
            foreach ($classHorses as $horse) {
                $html .= "<option value='" . $horse->horse_id . "'>" . GetAssetNamefromId($horse->horse_id) . "</option>";
            }
        } else {

            $html .= "<option value=''>No Horse Find</option>";
        }
        $html .= ' </select>';
        return $html;

    }


    public function getClassHorses($show_id, $asset_id, $restriction_id, $requestType, $user_id = null)
    {

        if ($user_id == '') {
            $user_id = \Auth::user()->id;
        }
        if ($requestType == 'single') {
            $name = 'ClassHorse';
        } else {

            $name = 'ClassHorse[]';
        }

        $assetArrId= [];
        $combinedClass = CombinedClass::where('combined_class_id',$asset_id)->pluck('class_id');

        $combineValues = SchedulerRestriction::whereIn('asset_id', $combinedClass)->where('show_id', $show_id)->pluck('asset_id')->toArray();

        $splitHorseId = [];
        $split_class_id = ShowClassSplit::where('orignal_class_id',$asset_id)->pluck('split_class_id');
        $splitId = ShowClassSplit::where('split_class_id',$asset_id)->pluck('orignal_class_id')->first();
        $asetRes = [];


        if($split_class_id)
            $asetRes = $split_class_id;
        if($splitId)
            $asetRes[] = $splitId;

        if(count($asetRes)>0)
        {
            $splitHorseId = SchedualNotes::whereIn('asset_id', $asetRes)
                ->where('show_id', $show_id)
                ->where('user_id', $user_id)
                ->pluck('horse_id');
        }
        if(count($combineValues)>0) {
            $combineValues[]=$asset_id;
            $assetArrId = $combineValues;
        }
        else {
            if($splitId)
                $assetArrId [] = $splitId;
            else
                $assetArrId [] = $asset_id;
        }

        $associatedKey = session('associated_key');

        $assets = Participant::where('invite_asociated_key', '=', $associatedKey)->where('show_id', $show_id)->pluck('asset_id')->toArray();

        $assetArrId=array_unique(array_merge($assetArrId,$assets));

        $isHorseAlreadySelected = session('isHorseAlreadySelected');

        if(!is_null($isHorseAlreadySelected))
        {
            $horseId =   $isHorseAlreadySelected;
        }else {

            //dd($assetArrId);
                $horseId = SchedualNotes::whereIn('asset_id', $assetArrId)
                    ->where('show_id', $show_id)
                    ->where('user_id', $user_id)
                    ->where('restriction_id', $restriction_id)
                    ->pluck('horse_id');
        }

        $ScratchedHorses = ClassHorse::
        where('show_id', $show_id)
            ->whereIn('class_id', $assetArrId)
            ->where('user_id', $user_id)
            ->where('scratch','=', 1)
            ->pluck('horse_id');

//dd($splitHorseId);
            $classHorses = ClassHorse::where('show_id', $show_id)
            ->whereIn('class_id', $assetArrId)
            ->whereNotIn('horse_id',$horseId)
            ->whereNotIn('horse_id',$splitHorseId)
             ->whereNotIn('horse_id',$ScratchedHorses)
            ->where('user_id', $user_id)
            ->groupBy('horse_id')
            ->get();


        $html = '<label>Select Horse</label> <select required   name="' . $name . '" class="ClassHorse ClassHorsess selectpicker form-control">';

        if ($classHorses->count() > 0) {
            foreach ($classHorses as $horse) {
                $html .= "<option value='" . $horse->horse_id . "'>" . GetAssetNamefromId($horse->horse_id) . "</option>";
            }
        } else {

            $html .= "<option value=''>No Horse Find</option>";
        }
        $html .= ' </select>';
        return $html;

    }

    public function getTimeSLots($ssetId, $form_id)
    {

        $assetArr = explode(',', $ssetId);

        $html = '';

        for ($i = 0; $i < count($assetArr); $i++) {

            $assetname = GetAssetNamefromId($assetArr[$i]);
            $html .= '<tr class="asset_' . $assetArr[$i] . '"><td>' . $assetname . '</td><td><div class="col-md-3 pull-left"><fieldset class="form-group select-bottom-line-only">';
            $html .= '<select class="form-inline form-control-bb-only" name="slotsMinutes[' . $form_id . '][' . $assetArr[$i] . ']">
            <option value="">Select</option>
            ' . getMinuteSelect() . '
            </select></div>';
            $html .= '<div class="col-md-3 pull-left"><fieldset class="form-group select-bottom-line-only">';
            $html .= '<select class="form-inline form-control-bb-only"  name="slotsSeconds[' . $form_id . '][' . $assetArr[$i] . ']">
            <option value="">Select</option>' . getSecondSelect() . '
            </select></div></td></tr>';
        }

        return $html;

    }


    /**** Facilty scheudler****************************/


    public function secondaryScheduler($template_id, $primary_asset_id, $associated_id, $isSubParticipant, $subId = 0)
    {

        $userEmail = \Auth::user()->email;
        $show_id = checkEncodeDecode($primary_asset_id);//here we using show id as primary id in scheduler restrcition
        $template_id = checkEncodeDecode($template_id);


        if ($isSubParticipant == 1) {
//            $sub_participant_id = \Auth::user()->id;
//            $useremail = \Auth::user()->email;
//            $subparticipant_collection = subParticipants::where('id', $subId)->first();
//            $userId = $subparticipant_collection->user_id;
//
//            $SchedulerRestriction =  SchedulerRestriction::where('form_id',$form_id)
//                ->whereIn('asset_id', $asset_id)
//                ->where('show_id',$show_id)
//                ->orderBy('id','DESC');
//            $FormTemplate =  $SchedulerRestriction->get();

        } else {

            $assets = Participant::where('invite_asociated_key', '=', $associated_id)->where('show_id', $show_id)->pluck('asset_id')->toArray();
            // dd($assets);

            $assets = implode(',',$assets);


            $isEmail = \Session('isEmployee');

            if ($isEmail == 1) {
                $userId = getAppOwnerId($userEmail, $template_id);
                $employee_id = \Auth::user()->id;
            } else {
                $userId = \Auth::user()->id;
                $employee_id = 0;
            }

            $SchedulerRestriction = SchedulerRestriction::
            where('show_id', $show_id)
                ->orderBy('id', 'ASC');
            $FormTemplate = $SchedulerRestriction->get();

                //dd($FormTemplate->toArray());

        }

        $singleTimeSlot = [];

        if ($SchedulerRestriction->count()>0) {
            $singleTimeSlot = $FormTemplate[0];
        }

        $dateFrom = '';

        if ($singleTimeSlot) {

            $var = explode('-', $singleTimeSlot->restriction);

            //print_r($var); exit;
            if (count($var))
                $dateFrom = date('Y-m-d H:i:s', strtotime($var[0]));

            $slotsTime = $singleTimeSlot->slots_duration;
            if ($slotsTime != '') {
                $slots_duration = $slotsTime;
            } else {
                $slots_duration = 5;
            }
        } else {
            $slots_duration = 5;
        }


        $scheduals = SchedualNotes::where('show_id', $show_id)
            ->get();


        $calendarVal = secondaryEvents($FormTemplate, $scheduals, $template_id,$dateFrom);
        $calendar = $calendarVal['calendar'];
        $clId = $calendarVal['clId'];

        $variables = array('templateId' => $template_id, 'userId' => $userId, 'calId' => $clId, 'associatedKey' => $associated_id, 'show_id' => $show_id, 'isSubParticipant' => $isSubParticipant, 'subId' => $subId);

        $calendar = $calendarVal['calendar'];

        /*------------------
        Faran Code: Getting positioning.
        --------------------*/
        //edit positions.
        $templateType = GetTemplateType($template_id);


        return view('facilitySchedular.secondaryCalendar')->with(compact("calendar", "variables", 'dateFrom', 'slots_duration','assets','templateType'));

        //  return view('schedular.calendar', compact('calendar','variables'));

    }

    public function primaryScheduler($template_id, $primary_asset_id,$show_id=null)
    {

        $spectatorsId = null;
        $templateId = checkEncodeDecode($template_id);

        $templateType = GetTemplateType($templateId);
        $primary_asset_id = checkEncodeDecode($primary_asset_id);

        if($templateType==TRAINER) {
            $show_id = checkEncodeDecode($show_id);
            if(empty($show_id)) {
                $show_id = SchedulerRestriction::where('scheduler_id', $primary_asset_id)->pluck('show_id')->first();
            }
        }
        else
            {
                $show_id = $primary_asset_id;
            }



        $isEmail = \Session('isEmployee');
        $userEmail = \Auth::user()->email;


        if ($isEmail == 1) {
            $user_id = getAppOwnerId($userEmail, $templateId);
            $employee_id = \Auth::user()->id;
        } else {
            $user_id = \Auth::user()->id;
            $employee_id = 0;
        }

        $participants = Participant::select('email')->where('status', 1)
            ->where('template_id', $templateId)
            ->where('invitee_id', $user_id)
            ->where('show_id', $show_id)
            ->groupBy('email')
            ->get();

        $userArr = [];

        if ($participants) {

            foreach ($participants as $participant) {
                $users = User::select('name', 'id')->where('email', '=', trim($participant->email))->first();
                $userArr[$users->id] = $users->name;
            }
        }
        $singleTime = SchedulerRestriction::where('scheduler_id', $primary_asset_id)->orderBy('id', 'ASC');

        //echo $primary_asset_id;
        $SchedulerRestriction = SchedulerRestriction::where('scheduler_id', $primary_asset_id)->orderBy('id', 'ASC');

        $singleTimeSlot = [];
        $FormTemplate = [];
        $dateFrom = '';
        $schedulers = [];
        if ($SchedulerRestriction->count() > 0) {
            $FormTemplate = $singleTime->where('show_id', $show_id)->groupBy('restriction')->get();
            $singleTimeSlot = $singleTime->first()->toArray();
            $schedulers = $SchedulerRestriction->groupBy('show_id')->pluck('show_id');

            $var = explode('-', $singleTimeSlot['restriction']);
            if (count($var))
                $dateFrom = date('Y-m-d H:i:s', strtotime($var[0]));

            $slotsTime = $singleTimeSlot['slots_duration'];
            if ($slotsTime != '') {
                $slots_duration = $slotsTime;
            } else {
                $slots_duration = 5;
            }
        } else {

            $slots_duration = 5;
        }
        $scheduals = SchedualNotes::where('show_id', $show_id)
            ->where('schedual_id', $primary_asset_id)
            ->get();

        //dd($scheduals->toArray());


//        if(isset($feedback_form) && $feedback_form->feedback_form_id > 0) {
//            $feedback_form->feedback_form_id;
//            $feedBackAssciated = 1;
//        }
//        else
        $feedBackAssciated = 0;


        \Session::put('feedBackAssciated', $feedBackAssciated);




        $calendarVal = primaryEvents($FormTemplate, $scheduals, $templateId,$dateFrom);


        $calendar = $calendarVal['calendar'];

        $clId = $calendarVal['clId'];


        $subAsset = AssetParent::where('parent_id', $primary_asset_id)->pluck('asset_id');

        //edit positions.
        $variables = array('templateId' => $templateId, 'userId' => $user_id, 'calId' => $clId, 'primary_asset_id' => $primary_asset_id,'show_id'=>$show_id,'spectatorsId'=>$spectatorsId);



        return view('facilitySchedular.primaryCalendar')->with(compact("calendar", "userArr", "subAsset", "variables", 'dateFrom', 'slots_duration','schedulers','show_id'));

        //  return view('schedular.calendar', compact('calendar','variables'));

    }


    public function getClassAssets($assets,$restriction_id,$show_id,$user_id,$current_asset = null)
    {

        $assetArr = explode(',', $assets);
        $restriction = SchedulerRestriction::where('id',$restriction_id)->pluck('restriction')->first();
        $assetInRestriction  = SchedulerRestriction::where('restriction',$restriction)->pluck('asset_id')->toArray();

      //  dd($assetInRestriction);

        $assetArr = array_values(array_unique($assetArr));

        $html = '<label>Select Asset</label> <select id="AssetsCon" onchange="getTrainerHorses('.$show_id.',this,2)"  name="asset_id" class="selectpicker form-control">';
        $html .= "<option value=''></option>";

        if (count($assetArr) > 0) {
            for ($i = 0; $i < count($assetArr); $i++) {
                if (in_array($assetArr[$i], $assetInRestriction)) {
                    if (($current_asset != null || $current_asset != 'undefined') && $current_asset == $assetArr[$i]) {
                        $selected = "selected=selected";
                    } else {
                        $selected = "";
                    }
                    $html .= "<option " . $selected . " value='" . $assetArr[$i] . "'>" . GetAssetNamefromId($assetArr[$i]) . "</option>";
                }
            }
        } else {
            $html .= "<option value=''>No Asset Found</option>";
        }
        $html .= ' </select>';

        return $html;

    }

    public function addFaciltyNotes(Request $request)
    {
        //dd($request->toArray());
        $arr = [];

        $isEmail = \Session('isEmployee');
        $userEmail = \Auth::user()->email;

        if ($isEmail == 1) {
            $cur_user_id = getAppOwnerId($userEmail, $request->template_id);
            $employee_id = \Auth::user()->id;
        } else {
            $cur_user_id = \Auth::user()->id;
            $employee_id = 0;
        }

        $schedule_id = $request->get("schedule_id");

        $timeFrom = $request->get("timeFrom");
        $timeTo = $request->get("timeTo");

        $reason = $request->get("reason");

        $templateType = GetTemplateType($request->get("template_id"));

        $dateStr = str_replace('-', '/', $timeFrom) . ' - ' . str_replace('-', '/', $timeTo);

        $timeSlot = json_encode($dateStr);

        $request->timeSlot = $timeSlot;
        $subId = $request->get("subId");

        $isSubParticipant = $request->get("isSubParticipant");

        if ($isSubParticipant == 1) {
            $sub_participant_id = \Auth::user()->id;
            $subparticipant_collection = subParticipants::select('user_id')->where('id', $subId)->first();
            $user_id = $subparticipant_collection->user_id;
        } else {
            $user_id = $request->get("userId");
            $sub_participant_id = 0;
        }
        $assets = $request->get("asset_id");

        $assetArr = explode(',', $assets);

        $asset_id_notes = $request->get("asset_id");
        if(isset($asset_id_notes) && $asset_id_notes!='')
        {
            $asset_id =   $asset_id_notes;
        }else
        {
            $asset_id = $request->get("asset_id_edit");
        }


        if ($schedule_id) {

            $schedualNotes = SchedualNotes::findOrFail($schedule_id);
            $schedualNotes->schedual_id = checkEncodeDecode($request->get("backgrounbdSlotId"));
            $schedualNotes->template_id = $request->get("template_id");
            $schedualNotes->user_id = $user_id;
            $schedualNotes->asset_id = $asset_id;
            $schedualNotes->notes = $request->get("notes");
            $schedualNotes->timeFrom = $request->get("timeFrom");
            $schedualNotes->multiple_scheduler_key = strtotime($request->get("timeFrom"));
            $schedualNotes->form_id = $request->get("form_id");
            $schedualNotes->timeTo = $request->get("timeTo");
            $schedualNotes->time_slot = '[' . $timeSlot . ']';
            $schedualNotes->sub_participant_id = $sub_participant_id;
            $schedualNotes->show_id = checkEncodeDecode($request->get("show_id"));
            $schedualNotes->employee_id = $employee_id;
            $schedualNotes->horse_id = $request->get("ClassHorse");
            $schedualNotes->restriction_id = $request->get("restriction_id");

            $schedualNotes->reason = $reason;
            $schedualNotes->form_id = 0;

            $schedualNotes->update();

         $description = '<span>' . getUserNamefromid($schedualNotes->user_id) . '</span> <br />
                                 <span class="assetClass">' . GetAssetNamefromId($schedualNotes->asset_id) . '</span> <br />
                                 <span class="assetClass">' . GetAssetNamefromId($schedualNotes->horse_id) . '</span>';

            $SchedulerRestriction = SchedulerRestriction::where('asset_id', $schedualNotes->asset_id)
                ->where('show_id', $schedualNotes->show_id)
                ->first();


            if ($SchedulerRestriction) {
                $slotsTime = $SchedulerRestriction->slots_duration;
                if ($slotsTime != '') {
                    $slots_duration = $slotsTime;
                } else {
                    $slots_duration = 5;
                }
            } else {
                $slots_duration = 5;
            }


            $customDateFrom = date('Y-m-d H:i:s', strtotime($request->get("timeFrom")));
            $customDateTo = date('Y-m-d H:i:s', strtotime($request->get("timeTo")));


            return $Response = array(
                'id' => $schedualNotes->id,
                'horse_id' => $schedualNotes->horse_id,
                'schedual_id' => $schedualNotes->schedual_id,
                'asset_id' => $schedualNotes->asset_id,
                'form_id' => $schedualNotes->form_id,
                'user_id' => $user_id,
                'notes' => $schedualNotes->notes,
                'show_id' => $schedualNotes->show_id,
                'template_id' => $schedualNotes->template_id,
                'description' => $description,
                'slots_duration' => $slots_duration,
                'reason' => $reason,
                "asset_name" => GetAssetNamefromId($schedualNotes->asset_id),
                "asset_user" => getUserNamefromid($schedualNotes->user_id),
                'assets' => $request->get('assetsCon'),
                'multiple_scheduler_key' => $schedualNotes->multiple_scheduler_key,
                'is_multiple_selection' => $request->get("is_multiple_selection"),
                'restriction_id' => $schedualNotes->restriction_id,
                'customDateFrom'=>$customDateFrom,
                'customDateTo'=>$customDateTo,
                'success' => 'Notes has been Updated successfully',
            );

        } else {


            $schedualNotes = new SchedualNotes();

            $schedualNotes->schedual_id = checkEncodeDecode($request->get("backgrounbdSlotId"));
            $schedualNotes->template_id = $request->get("template_id");
            $schedualNotes->user_id = $user_id;
            $schedualNotes->form_id = $request->get("form_id");
            $schedualNotes->asset_id = $asset_id;
            $schedualNotes->notes = $request->get("notes");
            $schedualNotes->timeFrom = $request->get("timeFrom");
            $schedualNotes->timeTo = $request->get("timeTo");
            $schedualNotes->is_multiple_selection = $request->get("is_multiple_selection");
            $schedualNotes->multiple_scheduler_key = strtotime($request->get("timeFrom"));
            $schedualNotes->time_slot = '[' . $timeSlot . ']';
            $schedualNotes->sub_participant_id = $sub_participant_id;
            $schedualNotes->show_id = checkEncodeDecode($request->get("show_id"));
            $schedualNotes->employee_id = $employee_id;
            $schedualNotes->reason = $reason;
            $schedualNotes->restriction_id = $request->get("restriction_id");
            $schedualNotes->horse_id = $request->get("ClassHorse");

            $schedualNotes->save();

               $description = '<span>' . getUserNamefromid($schedualNotes->user_id) . '</span> <br />
                                 <span class="assetClass">' . GetAssetNamefromId($schedualNotes->asset_id) . '</span></br>
                                 <span class="assetClass">' . GetAssetNamefromId($schedualNotes->horse_id) . '</span>';

            $customDateFrom = date('Y-m-d H:i:s', strtotime($request->get("timeFrom")));
            $customDateTo = date('Y-m-d H:i:s', strtotime($request->get("timeTo")));


            $SchedulerRestriction = SchedulerRestriction::where('asset_id', $schedualNotes->asset_id)
                ->where('show_id', $schedualNotes->show_id)
                ->first();

            if ($SchedulerRestriction) {
                $slotsTime = $SchedulerRestriction->slots_duration;
                if ($slotsTime != '') {
                    $slots_duration = $slotsTime;
                } else {
                    $slots_duration = 5;
                }
            } else {
                $slots_duration = 5;
            }

            return $Response = array(
                'id' => $schedualNotes->id,
                'horse_id' => $schedualNotes->horse_id,
                'asset_id' => $schedualNotes->asset_id,
                'show_id' => $schedualNotes->show_id,
                'form_id' => $schedualNotes->form_id,
                'slots_duration' => $schedualNotes->slots_duration,
                'schedual_id' => $schedualNotes->schedual_id,

                'user_id' => $user_id,
                'notes' => $schedualNotes->notes,
                'description' => $description,
                'template_id' => $schedualNotes->template_id,
                'schedual_id' => $schedualNotes->schedual_id,
                'restriction_id' => $schedualNotes->restriction_id,

                "asset_name" => GetAssetNamefromId($schedualNotes->asset_id),
                "asset_user" => getUserNamefromid($schedualNotes->user_id),
                'slots_duration' => $slots_duration,
                'reason' => $reason,
                'assets' => $request->get('assetsCon'),
                'multiple_scheduler_key' => $schedualNotes->multiple_scheduler_key,
                'is_multiple_selection' => $request->is_multiple_selection,
                'customDateFrom'=>$customDateFrom,
                'customDateTo'=>$customDateTo,

                'success' => 'Notes has been added successfully',
            );
        }
    }

    public function facilityMarkDone(Request $request)
    {


        $cur_user_id = \Auth::user()->id;

        $schedualNotes = schedualNotes::findOrFail($request->event_id);

        $isSubParticipant = $request->get("isSubParticipant");

        if ($isSubParticipant == 1) {
            $sub_participant_id = \Auth::user()->id;
            $useremail = \Auth::user()->email;
            $subparticipant_collection = subParticipants::where('email', $useremail)->where('asset_id', $request->get("asset_id"))->first();
            $user_id = $subparticipant_collection->user_id;
        } else {
            $user_id = $request->get("userId");
            $sub_participant_id = 0;
        }
        $schedualNotes->sub_participant_id = $sub_participant_id;
        $schedualNotes->is_mark = 1;
        $schedualNotes->update();

        $model = $schedualNotes->first();

        $asset_id = $request->get('asset_id');

        $template_id = $request->get('template_id');

        $associatedKey = $request->get('associatedKey');

        $tempalteModel = Template::select('invoice_to_asset')->where('id', $template_id)->first();


        $description = '<span>' . getUserNamefromid($schedualNotes->user_id) . '</span> <br />
                                 <span class="assetClass">' . GetAssetNamefromId($schedualNotes->asset_id) . '</span>
                                <br><span class="assetClass">' . GetAssetNamefromId($schedualNotes->horse_id) . '</span>';


//        return  $Response   = array(
//            'id' =>$schedualNotes->id ,
//            'horse_id' => $schedualNotes->horse_id,
//            'asset_id' => $schedualNotes->asset_id,
//            'show_id' => $schedualNotes->show_id,
//            'description' => $description,
//            'success' => 'Notes has been Updated successfully',
//        );
//
//

        return $Response = array(
            'id' => $request->schedule_id,
            'horse_id' => $schedualNotes->horse_id,
            'asset_id' => $schedualNotes->asset_id,
            'show_id' => $schedualNotes->show_id,
            'description' => $description,
            'multiple_scheduler_key' => $schedualNotes->multiple_scheduler_key,
            'is_multiple_selection' => $request->is_multiple_selection,
            "assets" => $request->get('assetsCon'),
            'success' => 'It has been Mark done successfully'
        );
    }

    public function primarySendInvite(Request $request)
    {

        // dd($request->toArray());

        $isEmail = \Session('isEmployee');
        $userEmail = \Auth::user()->email;

        if ($isEmail == 1) {
            $user_id = getAppOwnerId($userEmail, $request->template_id);
            $employee_id = \Auth::user()->id;
        } else {
            $user_id = \Auth::user()->id;
            $employee_id = 0;
        }

        $template_id = $request->template_id;
        $show_id = $request->show_id;
        $notes = $request->notes;

        $schedulerId = [];


        foreach ($request->assets as $key => $v) {

            $timeFrom = $request->timeFrom[$key];
            $timeTo = $request->timeTo[$key];

            $dateStr = str_replace('-', '/', $timeFrom) . ' - ' . str_replace('-', '/', $timeTo);

            $timeSlot = json_encode($dateStr);

            $schedualNotes = new SchedualNotes();

            $schedualNotes->schedual_id = $request->get("backgrounbdSlotId");
            $schedualNotes->template_id = $request->get("template_id");
            $schedualNotes->is_multiple_selection = $request->get("is_multiple_selection");
            $schedualNotes->form_id = $request->get("form_id");

            $schedualNotes->user_id = $request->users[$key];
            $schedualNotes->asset_id = $v;
            $schedualNotes->notes = $request->get("notes");
            $schedualNotes->timeFrom = $timeFrom;
            $schedualNotes->multiple_scheduler_key = strtotime($timeFrom);

            $schedualNotes->timeTo = $timeTo;
            $schedualNotes->time_slot = '[' . $timeSlot . ']';
            $schedualNotes->invited_by = $user_id;
            $schedualNotes->show_id = $request->get("show_id");
            $schedualNotes->employee_id = $employee_id;
            $schedualNotes->horse_id = $request->ClassHorse[$key];
            $schedualNotes->restriction_id = $request->get("restriction_id");

            // dd($schedualNotes->toArray());
            $schedualNotes->save();

            $customArr['schedulerId'][] = $schedualNotes->id;


            $description = '<span>' . getUserNamefromid($request->users[$key]) . '</span> <br />
                                 <span class="assetClass">' . GetAssetNamefromId($v) . '</span><br />
                                 <span class="assetClass">' . GetAssetNamefromId($schedualNotes->horse_id) . '</span>';

            $customArr['userName'][] = $description;

            $customDateFrom = date('Y-m-d H:i:s', strtotime($timeFrom));
            $customDateTo = date('Y-m-d H:i:s', strtotime($timeTo));
            $customArr['customDateFrom'][] = $customDateFrom;
            $customArr['customDateTo'][] = $customDateTo;
            $customArr['id'][] = $schedualNotes->id;


            $SchedulerRestriction = SchedulerRestriction::where('show_id', $schedualNotes->show_id)->first();


            if ($SchedulerRestriction) {

                $slotsTime = $SchedulerRestriction->slots_duration;
                if ($slotsTime != '')
                    $customArr['slots_duration'][] = $slotsTime;
                else
                    $customArr['slots_duration'][] = 5;

            }


        }
        $request->request->add($customArr);


        return $Response = array(
            'results' => json_encode($request->toArray()),
            'success' => 'Notes has been added successfully',
        );

    }
    public function getFeedbackLinks($id,$template_id,$type)
    {
        return view('feedBack.getFeedbackLinks')->with(compact('template_id',"id",'type'));


    }
    public function faciltyFeedBack($template_id,$form_id,$schedule_id, $spectatorId = null)
    {


        $form_id =  nxb_decode($form_id);

        $template_id =  nxb_decode($template_id);

        // $invitee_id = \Auth::user()->id;
        $scheduals = SchedualNotes::where('id', $schedule_id)
            ->first();
        $isEmail = \Session('isEmployee');
        $userEmail = \Auth::user()->email;


        if ($isEmail == 1) {
            $invitee_id = getAppOwnerId($userEmail, $scheduals->template_id);
            $employee_id = \Auth::user()->id;
        } else {
            $invitee_id = \Auth::user()->id;
            $employee_id = 0;
        }


        $horse_id = $scheduals->horse_id;
        $show_id = $scheduals->show_id;


        if ($scheduals->horse_id != '') {
            $horse_rider = getHorsesRiderForScheduler($scheduals->horse_id ,$scheduals->asset_id );
        }
        else {
            $horse_rider ='';
        }

        $isEmail = \Session('isEmployee');
        $userEmail = \Auth::user()->email;


        if ($isEmail == 1) {
            $invitee_id = getAppOwnerId($userEmail, $scheduals->template_id);
            $employee_id = \Auth::user()->id;
        } else {
            $invitee_id = \Auth::user()->id;
            $employee_id = 0;
        }


        $form = Form::select('linkto')->where('id', $scheduals->form_id)->first();

        $feedback_form = Module::select('feedback_form_ids')->where('id', $form->linkto)
            ->first();
        if ($form_id ==null) {
            $FFid = $feedback_form->feedback_form_id;
            $FormTemplate = Form::where('id', $FFid)->first();
        }else{
            $FFid = $form_id;
            $FormTemplate = Form::where('id', $form_id)->first();
        }
        $TemplateDesign = TemplateDesign::where('template_id', $scheduals->template_id)->first();

        //MasterTemplate Design Variable  -->
        $TD_variables = getTemplateDesign($TemplateDesign);
        $pre_fields = json_decode($FormTemplate->fields, true);
        // END: MasterTemplate Design Variable  -->

        //Check if answer exist $SFB = Scheduler Feedback
        $SFB = SchedulerFeedBacks::where('horse_id',$horse_id)
            ->where('show_id',$show_id)
            ->where('schedule_id',$scheduals->id)
            ->where('form_id',$FFid)
            ->first();
        $answer_fields = array();

        if (count($SFB)>0) {
            $answer_fields = json_decode($SFB->fields, true);
        }

        return view('masterScheduler.facilityFeedback')->with(compact('FormTemplate',"horse_id",'show_id','answer_fields', 'TD_variables', 'pre_fields', 'scheduals', 'invitee_id', 'spectatorId','horse_rider'));

    }

public function inviteeMasterScheduler(Request $request)
{


    \Session::forget('isHorseAlreadySelected');

   $is_multiple_selection =  $request->get('is_multiple_selection');
   $timeFromInvite=  $request->get('timeFromInvite');
   $timeToInvite=  $request->get('timeToInvite');
   $counterVar =$request->get('counterVar');
    $assetId =  $request->get('assetId');
    $showId =  $request->get('showId');


    $heights =  $request->get('heights');





    $horseJson =  $request->get('horseJson');

    $horseArray = json_decode($horseJson);

    \Session::put('isHorseAlreadySelected',$horseArray);


    $userArr =  json_decode($request->get('users'),true);


    $timeFrom = str_replace('-','/',$timeFromInvite);
    $timeTo = str_replace('-','/',$timeToInvite);

    $schedualNotes = SchedualNotes::where('show_id', $showId)
        ->where(function ($q) use ($timeFrom, $timeTo) {
            $q->whereBetween('timeFrom', [$timeFrom, $timeTo])
                ->orWhereBetween('timeTo', [$timeFrom, $timeTo]);
        });
    $userArray = [];
    if($schedualNotes->count()>0) {
        $userArray = $schedualNotes->pluck('user_id')->toArray();
    }




    if(count($userArray)>0) {

       $userA = array_diff(array_keys($userArr), $userArray);
        $userArr =[];
       $user = User::whereIn('id',$userA)->get();
      if($user->count()>0) {
          foreach ($user as $u)
              $userArr[$u->id] = $u->name;
      }

    }else
    {
        $userArr = $userArr;
    }

    $startTime = Carbon::parse($timeFromInvite);
    $finishTime = Carbon::parse($timeToInvite);

    $minutes = $finishTime->diff($startTime)->format('%i:%s');

    $timeToArr=explode(":",$minutes);
    $timeToCalc=$timeToArr[0]*60+$timeToArr[1];


    /************** Time From calc*********************************/
    $time=explode(":",$minutes);
    $TimeFromCalc=$counterVar*$time[0]*60+$time[1]*$counterVar;

    if(isset($is_multiple_selection) && $is_multiple_selection==1) {
        $timeFrom = $startTime;
    }else {
        $timeFrom = $startTime->addSeconds($TimeFromCalc);
    }
    $old_date_timestamp = strtotime($timeFrom);

    $new_date_From = date('Y/m/d H:i:s', $old_date_timestamp);
    $new_time_From = date('h:i:s A', $old_date_timestamp);

    /*************************End Time From ......Time To calc********************/

    $timeTo = $timeFrom->addSeconds($timeToCalc);

     $time_to = strtotime($timeTo);


    $new_date_to = date('Y/m/d H:i:s', $time_to);
    $new_Time_to = date('h:i:s A', $time_to);

    /***************Time To calc******************/



    return view('masterScheduler.inner.inviteeMasterScheduler')->with(compact('userArr','heights','showId','assetId','new_date_From','new_time_From','new_date_to','new_Time_to'));

}

    public function getEventsData($id)
    {
       return $results = SchedualNotes::where('id',$id)->first();
        //dd($results->toArray());

    }

    public function getEventsParticipants($show_id,$form_id,$asset_id,$dateFrom,$dateTo,$type,$slot_time,$restriction_id)
    {

        $user_id = \Auth::user()->id;
        $trainer = '';

        $dateFrom = str_replace('-','/',$dateFrom);
        $dateTo = str_replace('-','/',$dateTo);

       $multiple_scheduler_key = strtotime($dateFrom);


        $results = SchedualNotes::where('asset_id',$asset_id)->where('show_id',$show_id)->where('form_id',$form_id)->where('restriction_id',$restriction_id)
           ->where('multiple_scheduler_key', $multiple_scheduler_key)->get();

        return view('schedular.eventParticipants')->with(compact('results','trainer','asset_id','user_id','type','slot_time'));

    }

    public function getGroupParticipants($show_id,$scheduler_id,$dateFrom,$dateTo,$type,$slot_time,$restriction_id)
    {

        $user_id = \Auth::user()->id;


        $dateFrom = str_replace('-','/',$dateFrom);
        $dateTo = str_replace('-','/',$dateTo);

       $multiple_scheduler_key = strtotime($dateFrom);


        $results = SchedualNotes::where('schedual_id',$scheduler_id)->where('show_id',$show_id)
            ->where('multiple_scheduler_key', $multiple_scheduler_key)->get();

        //dd($results->toArray());

        $trainer = 4;
        return view('schedular.eventParticipants')->with(compact('results','asset_id','user_id','type','slot_time','trainer'));

    }



    public function markDoneAllGroups(Request $request)
    {
        $user_id = \Auth::user()->id;

        //dd($request->toArray());
        $data =  $request->get('markDone');
        $slot_time =  $request->get('slot_Time');

        $scheduler_id =  $request->get('scheduler_id');

        $cur_user_id = \Auth::user()->id;
        // dd($data);
        foreach ($data as $key=>$value) {

            $schedualNotes = schedualNotes::findOrFail($key);
            $schedualNotes->is_mark = 1;
            $schedualNotes->update();
        }
        $trainer = 4;

        $results = SchedualNotes::where('show_id',$schedualNotes->show_id)->where('schedual_id',$scheduler_id)
            ->where('multiple_scheduler_key', $schedualNotes->multiple_scheduler_key)->get();


        $type = 2;
        $asset_id= $schedualNotes->asset_id;
        Session::flash('message', 'All Selected has mark done Successfully.');

        return view('schedular.eventParticipants')->with(compact('results','asset_id','user_id','type','slot_time','trainer'));
    }


    public function markDoneAll(Request $request)
    {
        $user_id = \Auth::user()->id;

        //dd($request->toArray());
        $data =  $request->get('markDone');
        $slot_time =  $request->get('slot_Time');


        $cur_user_id = \Auth::user()->id;
       // dd($data);
        foreach ($data as $key=>$value) {

            $schedualNotes = schedualNotes::findOrFail($key);
            $schedualNotes->is_mark = 1;
            $schedualNotes->update();
        }

        $results = SchedualNotes::where('asset_id',$schedualNotes->asset_id)->where('show_id',$schedualNotes->show_id)->where('form_id',$schedualNotes->form_id)
            ->where('multiple_scheduler_key', $schedualNotes->multiple_scheduler_key)->get();


        $type = 2;
        $asset_id= $schedualNotes->asset_id;
        Session::flash('message', 'All Selected has mark done Successfully.');

        return view('schedular.eventParticipants')->with(compact('results','asset_id','user_id','type','slot_time'));
    }


    public function getHorseName($horse_id)
    {

        $classHorses = ClassHorse::where('horse_id',$horse_id)->first();

        $html = '<label>Select Horse</label> <select required  name="ClassHorse" class="ClassHorse ClassHorsess selectpicker">';

        if ($classHorses) {
                $html .= "<option value='" . $horse_id . "'>" . GetAssetNamefromId($classHorses->horse_id) . "</option>";
        } else {

            $html .= "<option value=''>No Horse Find</option>";
        }
        $html .= ' </select>';
        return $html;

    }

    public function checkTimeAvailability($timeFrom,$timeTo,$show_id,$asset_id,$type)
    {
        $user_id = \Auth::user()->id;

        $timeFrom = str_replace('-','/',$timeFrom);
        $timeTo = str_replace('-','/',$timeTo);

        $timeFrom = Carbon::parse($timeFrom);
        $timeFrom->addSecond();
        $userArray = [];
        $userArr = [];
        if($type==1) {
            $schedualNotes = SchedualNotes::where('show_id', $show_id)
                ->where('user_id', $user_id)
                ->where(function ($q) use ($timeFrom, $timeTo) {
                    $q->whereBetween('timeFrom', [$timeFrom, $timeTo])
                        ->orWhereBetween('timeTo', [$timeFrom, $timeTo]);
                });

            return $Response = array(
                'results' => $schedualNotes->count(),
                'message' => ' You already have a ride at this time for <strong>'.GetAssetNamefromId($asset_id).'</strong>'
            );

        }else {

            $schedualNotes = SchedualNotes::where('show_id', $show_id)
                ->where(function ($q) use ($timeFrom, $timeTo) {
                    $q->whereBetween('timeFrom', [$timeFrom, $timeTo])
                        ->orWhereBetween('timeTo', [$timeFrom, $timeTo]);
                });


            $participants = Participant::select('email')->where('status', 1)
                ->where('invitee_id', $user_id)
                ->where('asset_id', $asset_id)
                ->where('show_id', $show_id)
                ->groupBy('email')
                ->get();

            $userArr = [];
            $users = [];
            if ($participants) {
                foreach ($participants as $participant) {
                    $users = User::select('name', 'id')->where('email', '=', trim($participant->email))->first();
                    $userArr[$users->name] = $users->id;
                }
            }



            if($schedualNotes->count()>0) {
                $userArray = $schedualNotes->pluck('user_id')->toArray();
                $template_id = $schedualNotes->first()->template_id;

            }
            if(count($userArray)>0) {

                $arr_1 = array_diff($userArr, $userArray);
            }else {
                $arr_1 = $userArr;
            }
                if(count($arr_1)>0){
                $html ='<label>Select User</label>';
                $html .='<select required name="users[]" onchange="getMultipleHorseAssets('.$show_id.','.$asset_id.',this,1)" class="selectpicker mySelect">';
                $html .= '<option value="">Select User</option>';
                foreach ($arr_1 as $arr=>$value)
                {
                    $html .= ' <option value="'.$value.'">'.$arr.'</option>';
                }
                $html .='</select>';

                return $Response = array(
                    'result' => $html,
                );
            }else
            {
                return $Response = array(
                    'result' => '0'
                );

            }

            }



    }


    public function getHorseHeight($id)
    {


  $horseHeight =  SchedualNotes::where('id',$id)->pluck('height')->first();

  $html ='<label>Height</label><select class="heightCon selectpicker"><option value='.$horseHeight.'>'.$horseHeight.'</option></select>';
  return $html;


    }


    public function getPlacings($show_id,$asset_id)
    {

        $positions = ShowPrizing::where("asset_id", $asset_id)->first();
        $placing = null;
        if ($positions) {
            $placing = json_decode($positions->fields, true);
        }
        $scoreArr = [];

        if (isset($placing['place']) && count($placing['place']) > 0) {
            $totalPos = count($placing['place']);
            $scoreArr = SchedualNotes::where('asset_id', $asset_id)->where('show_id', $show_id)->orderBy('score', 'desc')->limit($totalPos)->get()->toArray();
        }

        if (count($scoreArr) > 0) {
            array_unshift($scoreArr, null);
            unset($scoreArr[0]);

            foreach ($scoreArr as $k => $v) {
                if ($v['score'] > 0) {
                    $p[$k]['horse_id'] = $v['horse_id'];
                    $p[$k]['position'] = $placing['place'][$k]['position'];
                    $p[$k]['price'] = $placing['place'][$k]['price'];
                    $p[$k]['score'] = $v['score'];
                }
            }

            $showPrize = ShowPrizingListing::where('show_id', $show_id)->where('asset_id', $asset_id)->first();

            if ($showPrize) {
                $showPrize->position_fields = json_encode($p);
                $showPrize->update();
            } else {

                $model = new ShowPrizingListing();
                $model->show_id = $show_id;
                $model->asset_id = $asset_id;
                $model->position_fields = json_encode($p);
                $model->save();
            }

        }
    }

public function getPositionsScore($assetId, $showId,$restriction_id,$form_id=null)
{


    $iscombinedClass = CombinedClass::where('combined_class_id',$assetId)->pluck('combined_class_id');


    $asset_id = [];
    $splitId = ShowClassSplit::where('split_class_id',$assetId)->pluck('orignal_class_id')->first();

    if($splitId) {
        $asset_id[] = $splitId;
    }elseif(count($iscombinedClass)>0) {
        $SchedulerCombined = SchedulerRestriction::whereIn('asset_id', $iscombinedClass)->where('show_id', $showId)->where('form_id', $form_id)->first();
        if ($SchedulerCombined) {
            $combinedClass = CombinedClass::where('combined_class_id', $SchedulerCombined->asset_id);
            $combinedClasses = $combinedClass->pluck('class_id');
            $combineValues = SchedulerRestriction::whereIn('asset_id', $combinedClasses)->where('show_id', $showId)->where('form_id', $form_id)->pluck('asset_id');
            $asset_id = $combineValues;
        }
    }else{
        $asset_id[]= $assetId;
    }

    $participants = ClassHorse::with("user", "horse")->where("show_id", $showId)->whereIn("class_id", $asset_id)->groupBy("horse_id")->get();



    $positions = ShowPrizing::where("asset_id", $assetId)->first();
    $placing = null;
    if ($positions) {
        $placing = json_decode($positions->fields);
    }
    $existingPositions = ShowPrizingListing::where("asset_id", $assetId)->where("show_id", $showId)->where('form_id', $form_id)->first();
    $pos_answers = null;
    $positioning_id = null;
    if ($existingPositions) {
        $pos_answers = json_decode($existingPositions->position_fields, true);
        $positioning_id = $existingPositions->id;
    }
    $horse_rating_type = Asset::where('id',$assetId)->pluck('horse_rating_type')->first();

    $restrictions = SchedulerRestriction::where('asset_id',$assetId)->where('show_id',$showId)->where('form_id', $form_id)->orderBy('id')->pluck('id');
    if(!is_null($pos_answers)) {
        array_unshift($pos_answers, "phoney");
        unset($pos_answers[0]);
    }
    return view('masterScheduler.getPositionsScore')->with(compact("placing","pos_answers","positioning_id","participants","horse_rating_type","restrictions"));

}

    public function setScore($ar, $score,$restriction_id)
    {


        $ClassicIds = SchedulerRestriction::where('show_id',$ar->show_id)->whereRaw('FIND_IN_SET('.$ar->asset_id.',score_from)')->where('form_id',$ar->form_id)->pluck('asset_id');

        $classicPositionFields = [];
        if(count($ClassicIds)>0)
        {
            $classPrizing = ShowPrizingListing::where('show_id',$ar->show_id)->where('form_id',$ar->form_id)->whereIn('asset_id',$ClassicIds)->first();
            if($classPrizing)
            {
                $classicPositionFields = json_decode($classPrizing->position_fields,true);
            }
            if(isset($classicPositionFields)) {
                array_unshift($classicPositionFields, "phoney");
                unset($classicPositionFields[0]);
            }
           // dd($classicPositionFields);


//            foreach ($classicPositionFields as $kee=>$v)
//            {
//                $score = checkHorseExistInClassic($v['horse_id'],$kee,$request->participants);
//                if($score>0) {
//                    $scoreTotal = array_sum($classicPositionFields[$kee]['rounds']);
//                    $classicPositionFields[$kee]['score'] = $scoreTotal + $score;
//                    $classicPositionFields[$kee]['scoreFrom'][$assetId]=['class_id'=>$ar->asset_id,'ClassScore'=>$score];
//                }
//            }

           // $classicPositionFields =  record_sort_price_position($classicPositionFields, "score", true,$classPrizing->asset_id);

//            $mod = ShowPrizingListing::find($classPrizing->id);
//            $mod->position_fields=json_encode($classicPositionFields);
//            $mod->update();
        $classicCount = count($classicPositionFields);

        }

            $existingPositions = ShowPrizingListing::where("asset_id", $ar->asset_id)->where('form_id', $ar->form_id)->where("show_id", $ar->show_id)->first();


            $positions = ShowPrizing::where("asset_id", $ar->asset_id)->first();
            $placing = null;
            if ($positions) {
                $placing = json_decode($positions->fields, true);
            }

            if ($existingPositions) {

                $pos_answers = json_decode($existingPositions->position_fields, true);
                $pre = array_values($pos_answers);

                $checkHorseExist = checkHorseExist($pre, $ar->horse_id);
                if ($checkHorseExist) {
                    foreach ($pre as $ke => $pr) {
                        if (isset($pr['rounds'])) {
                            foreach ($pr['rounds'] as $k => $v) {
                                if ($pr['horse_id'] == $ar->horse_id) {
                                    if ($k == $restriction_id)
                                        $pre[$ke]['rounds'][$k] = $score;
                                    else
                                        $pre[$ke]['rounds'][$restriction_id] = $score;
                                }
                            }
                        }
                        if (isset($pr['horse_id']) && $pr['horse_id'] == $ar->horse_id) {
                            if (isset($pre[$ke]['rounds'])) {
                              //  $pre[$ke]['score'] = array_sum($pre[$ke]['rounds']);

                                $scoreTotal = array_sum($pre[$ke]['rounds']);
                              //  $pre[$ke]['scoreFrom'][$ar->asset_id] = ['class_id' => $ar->asset_id, 'ClassScore' => $score];
                                $classScore = 0 ;
                                if(isset($pre[$ke]['scoreFrom'])) {
                                    foreach ($pre[$ke]['scoreFrom'] as $kk => $vv) {
                                        $classScore = $classScore + $pre[$ke]['scoreFrom'][$kk]['ClassScore']; // summing up all score classes points
                                    }
                                }
                                $pre[$ke]['score'] = $scoreTotal +$classScore;


                            }
                                if (isset($classicPositionFields) && count($classicPositionFields) > 0) {
                                $scoreTotal = 0;
                                if (isset($classicPositionFields[$ke]['rounds']))
                                    $scoreTotal = array_sum($classicPositionFields[$ke]['rounds']);
                                $classicPositionFields[$ke]['score'] = $scoreTotal + $pre[$ke]['score'];
                                $classicPositionFields[$ke]['scoreFrom'][$ar->asset_id] = ['class_id' => $ar->asset_id, 'ClassScore' => $pre[$ke]['score']];
                            }
                        }

                    }


                    if (isset($classPrizing)) {
                        $classPrizing->position_fields = json_encode($classicPositionFields);
                        $classPrizing->update();
                    }

                    $pre = record_sort_price_position($pre, "score", true, $ar->asset_id);

                    //dd($pre);

                    $position_fields = json_encode($pre);
                    $existingPositions->position_fields = $position_fields;
                    $existingPositions->update();

                } else {
                    $totalArrayElements = count($pre);

                    $pre[$totalArrayElements + 1]['horse_id'] = $ar->horse_id;
                    $pre[$totalArrayElements + 1]['position'] = $placing['place'][1]['position'];
                    $pre[$totalArrayElements + 1]['price'] = $placing['place'][1]['price'];
                    $pre[$totalArrayElements + 1]['score'] = $score;
                    $pre[$totalArrayElements + 1]['rounds'][$restriction_id] = $score;
                    $pre = record_sort_price_position($pre, "score", true, $ar->asset_id);
                    $position_fields = json_encode($pre);
                    $existingPositions->position_fields = $position_fields;
                    $existingPositions->update();

                    if (isset($classPrizing)) {
                        $classicPositionFields[$classicCount + 1]['horse_id'] = $ar->horse_id;
                        $classicPositionFields[$classicCount + 1]['position'] = $placing['place'][1]['position'];
                        $classicPositionFields[$classicCount + 1]['price'] = $placing['place'][1]['price'];
                        $classicPositionFields[$classicCount + 1]['rounds'][$restriction_id] = $score;
                        $classicPositionFields[$classicCount + 1]['score'] = $score;
                        $classicPositionFields[$classicCount + 1]['scoreFrom'][$ar->asset_id] = ['class_id' => $ar->asset_id, 'ClassScore' => $score];

                        // dd($classicPositionFields);
                        $classPrizing->position_fields = json_encode($classicPositionFields);
                        $classPrizing->update();
                    }


                }
            } else {
                $existingPositions = new ShowPrizingListing();
                $pre[1]['horse_id'] = $ar->horse_id;
                $pre[1]['position'] = $placing['place'][1]['position'];
                $pre[1]['price'] = $placing['place'][1]['price'];
                $pre[1]['rounds'][$restriction_id] = $score;
                $pre[1]['score'] = $score;

                if (isset($classPrizing) && $score!='') {

                    $classicPositionFields[$classicCount + 1]['horse_id'] = $ar->horse_id;
                    $classicPositionFields[$classicCount + 1]['position'] = $placing['place'][1]['position'];
                    $classicPositionFields[$classicCount + 1]['price'] = $placing['place'][1]['price'];
                    $classicPositionFields[$classicCount + 1]['rounds'][$restriction_id] = $score;
                    $classicPositionFields[$classicCount + 1]['score'] = $score;
                    $classicPositionFields[$classicCount + 1]['scoreFrom'][$ar->asset_id] = ['class_id' => $ar->asset_id, 'ClassScore' => $score];

                    // dd($classicPositionFields);
                    $classPrizing->position_fields = json_encode($classicPositionFields);
                    $existingPositions->form_id = $ar->form_id;

                    $classPrizing->update();
                }

                if($score!='') {
                    $pre = record_sort_price_position($pre, "score", true, $ar->asset_id);

                    $position_fields = json_encode($pre);
                    $existingPositions->asset_id = $ar->asset_id;
                    $existingPositions->show_id = $ar->show_id;
                    $existingPositions->form_id = $ar->form_id;
                    $existingPositions->position_fields = $position_fields;
                    $existingPositions->save();
                }
            }

    }


    protected function getScoreForScheduler($asset_id,$show_id,$horse_id,$restriction_id,$form_id)
    {
      return getScoreValues($asset_id,$show_id,$horse_id,$restriction_id,$form_id);
    }

    public function deleteSchedulerClass($asset_id,$scheduler_key,$show_id)
    {
        $model = SchedulerRestriction::where('asset_id', $asset_id)->where('scheduler_key', $scheduler_key)->where('show_id', $show_id)->first();
        if($model)
            $model->delete();
    }

    public function deleteSchduler($scheduler_key)
    {
        SchedulerRestriction::where('scheduler_key', $scheduler_key)->delete();
    }


    public function deleteScoreClass($scheduler_key,$asset_id,$classes)
    {

//    $scoreValues  =json_decode($scoreValues,true);
//
//    if($scoreValues!=null) {
//    $score_from = implode(',', $scoreValues);
//    }else
//    $score_from = $scoreValues;
//
//
//    $dateTime = nxb_decode($dateTime);
    $classes = json_decode($classes,true);


    foreach ($classes as $cls) {
    $model = SchedulerRestriction::where('asset_id', $cls)->where('scheduler_key',$scheduler_key)->first();

    if($model->score_from!=''){

        $score_from = explode(',',$model->score_from);
        if (($key = array_search($asset_id, $score_from)) !== false) {
            unset($score_from[$key]);
        }
        $score_from =   implode(',',$score_from);
        $model->score_from=$score_from;
        $model->update();
    }

    }
    }

    public function addRestrictions(Request $request)
    {
    $user_id = \Auth::user()->id;

    $updateRequest = 0;

    if(isset($request->scheduler_key) && $request->scheduler_key!='') {
        $scheduler_key = $request->scheduler_key;
        $updateRequest =1 ;
    }
    else
        $scheduler_key = time() . mt_rand();

    foreach ($request->assets as $asset_id) {

    $scheduler = SchedulerRestriction::where('scheduler_key',$request->scheduler_key)->where('asset_id',$asset_id)->first();

    if($scheduler) {
        $model = $scheduler;
        if($request->is_group==0)
        {
           $schedualNotes = SchedualNotes::where('class_group_key',$request->scheduler_key)->where('other_group_Class',1)->delete();
        }

    }
    else
        $model = new SchedulerRestriction();

    $model->asset_id = $asset_id;
    $model->form_id = $request->form_id;
    $model->restriction = $request->dateTimeSchedual;
    $model->scheduler_id = $request->scheduler_id;
    $model->show_id = $request->show_id;
    if (isset($request->qualifingPoints)) {
        $model->qualifing_check = $request->qualifingPoints;
    }else{
        $model->qualifing_check = 0;
    }
     if (isset($request->qualifingPrice)) {
        $model->qualifing_price = $request->qualifingPrice;
    }else{
        $model->qualifing_price = 0;
    }
    
    
    if(isset($request->scheduler_key) && $request->scheduler_key!='')
        $model->scheduler_key = $request->scheduler_key;
    else
        $model->scheduler_key = $scheduler_key;

    if(isset($request->blockTime)) {
    $model->block_time = $request->blockTime;
    $time = explode('-', $request->blockTime);

    $dateFrom = new Carbon(array_first($time));
    $date_from = $dateFrom->format('Y-m-d H:i:s');

    $dateTo = new Carbon(array_last($time));
    $date_to = $dateTo->format('Y-m-d H:i:s');

    $date_from = $date_from;
    $date_to = $date_to;
    $model->date_from = $date_from;
    $model->date_to = $date_to;
    }

    if(isset($request->multipleSelection))
    $model->is_multiple_selection = $request->multipleSelection;
    else
    $model->is_multiple_selection = 0;

    if(isset($request->restrictRiders))
    $model->is_rider_restricted =$request->restrictRiders;
    else
    $model->is_rider_restricted = 0;

    $model->block_time_title = $request->blockTimeTitle;

    if (isset($request->score_from)) {
    $model->score_from = implode(',', $request->score_from);
    }
    $model->is_group = $request->is_group;

    $model->save();

    }

    $row = SchedulerRestriction::selectRaw('group_concat(asset_id) as asset_id,restriction,block_time,block_time_title,is_multiple_selection,is_rider_restricted,score_from,scheduler_key,form_id,scheduler_id,show_id,qualifing_check,qualifing_price')
        ->where('scheduler_key',$scheduler_key)->groupBy("scheduler_key")->first()->toArray();


    $template_id = ManageShows::where('id',$request->show_id)->pluck('template_id')->first();

    $m_s_fields = getButtonLabelFromTemplateId($template_id,'m_s_fields');

    return view('schedular.schedulerTimeInner')->with(compact("row","m_s_fields","updateRequest"));

    }

    public function editSchedulerTime($scheduler_key)
    {
         $restrictionData = SchedulerRestriction::selectRaw('group_concat(asset_id) as asset_id,restriction,block_time,block_time_title,is_multiple_selection,is_rider_restricted,score_from,scheduler_key,form_id,scheduler_id,show_id,qualifing_check,qualifing_price,is_group')
        ->where('scheduler_key',$scheduler_key)->groupBy("scheduler_key")->first();

        $selectedClasses = '['.$restrictionData->asset_id.']';

        $scoreFrom = '['.$restrictionData->score_from.']';

        return response()->json(['resData'=>$restrictionData,'selectedClasses'=>$selectedClasses,'scoreFrom'=>$scoreFrom]);
    }

    public function addReminder(Request $request)
    {

        $user_id = \Auth::user()->id;

        $model = Schedual::findorfail($request->scheduler_id);
        $model->reminderDays = $request->reminderDays;
        $model->reminderHours = $request->reminderHours;
        $model->reminderMinutes = $request->reminderMinutes;
        $model->isReminder = 1;

        $model->update();

     //   schedulerReminder($model, $model->id);

        return view('schedular.schedulerReminder')->with(compact("model"));
    }



    public function checkAlreadyExist($asset_id,$form_key,$show_id)
    {
       return  SchedulerRestriction::where('asset_id', $asset_id)->where('form_id', $form_key)->where('show_id', $show_id)->count();
    }


}



