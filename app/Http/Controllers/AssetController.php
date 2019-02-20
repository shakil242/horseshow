<?php
    /**
     * This is Module Controller For frontend to control all the Accets in project
     *
     * @author Faran Ahmed (Vteams)
     * @date 17-Feb-2017
     */


namespace App\Http\Controllers;

use App\AssetModules;
use App\AssetParent;
use App\AssetScheduler;
use App\ClassTypes;
use App\Exports\InvoicesExport;
use App\HorseOwner;
use App\Participant;
use App\SchedulerRestriction;
use Excel;
use PDF;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Module;
use App\Form;
use App\TemplateDesign;
use App\Template;
use App\Asset;
use App\ParticipantResponse;
use App\ShowPrizingListing;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\CombinedClass;
use App\ShowClassSplit;
use DataTables;
use App\ManageShows;
use App\AssetModuleTamplate;
use Yajra\DataTables\Html\Builder;

class AssetController extends Controller
{
    /**
     * Display a listing of the modules in view master template.
     *
     * @return \Illuminate\Http\Response
     */
    /**
     * Datatables Html Builder
     * @var Builder
     */
    protected $htmlBuilder;

    public function __construct(Builder $htmlBuilder)
    {
        $this->htmlBuilder = $htmlBuilder;
    }


    public function index($template_id)
    {

        $isEmail = \Session('isEmployee');

        $app_id = \Session('app_id');


        $user_id = \Auth::user()->id;
        $template_id = nxb_decode($template_id);
        $templateCategory = GetTemplateType($template_id);
        //$collection = Asset::where('template_id',$template_id)->where('user_id',$user_id)->get()->toArray();
        // $AssetsForms =  Form::with('assets')->where('template_id',$template_id)->where('form_type',F_ASSETS)->get();
       // exit;

        $rr[]=F_ASSETS;
        $rr[]=RIDER_ASSETS;


        $AssetsForms = Form::where('template_id',$template_id)->whereIn('form_type',$rr)->get();
        $parentAssetsForms = Form::where('template_id',$template_id)->where('form_type',F_PARENT_ASSETS)->get();
        return view('MasterTemplate.assets.index')->with(compact("template_id",'templateCategory','AssetsForms','parentAssetsForms','app_id'));
    }
    /**
     * Display a listing of search results.
     *
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $keywords = $request->get('keywords');
        $template_id = nxb_decode($request->get('template_id'));
        $collection = Module::where('template_id',$template_id)->where("name","LIKE","%{$keywords}%")->get()->toArray();
        $generalCollection = Module::where('template_id',$template_id)->where('general',1)->get()->toArray();
        $MT_name = Template::select('name')->where("id",$template_id)->first();
        $breadcrumbsRoute = "mastertemp-id-search";
        $dataBreadcrum = [
                'template_id' => $template_id,
                ];
        return view('MasterTemplate.modules.listing')->with(compact('MT_name','breadcrumbsRoute','dataBreadcrum','collection','template_id','generalCollection'));
    }
      /**
     * Search Auto complete results.
     *
     * @return \Illuminate\Http\Response
     */
    public function autocomplete(Request $request,$id)
    {
        $template_id = nxb_decode($id);
        $data = Module::select("name")->where('template_id',$template_id)->where("name","LIKE","%{$request->input('query')}%")->get();
        return response()->json($data);
    }

    /**
     * Show the form for creating a new resource.
     * For app owner section
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $isEmail = \Session('isEmployee');

        $user_id = \Auth::user()->id;

        $form_id = $request->get("form_id");
        $FormTemplate = Form::where('id',$form_id)->first();
        $template_id = $FormTemplate->template_id;


        $ownerForHorse = getRiderOwner($user_id,$template_id);


        //Feedback Form type:
        $cmpFeed = Form::whereIn('feedback_type',[1,2,3])->where('template_id', $template_id)->get();
        $TemplateDesign = getTemplateDesignQry($template_id,$user_id);
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

        $assets = [];
        $templateCategory = GetTemplateType($template_id);
        if($FormTemplate->form_type != 7)
            $assets = Asset::select('assets.id','assets.fields')->where('template_id',$template_id)->where('asset_type',1)->where('user_id',$user_id)->get();

        $showClasses = Asset::where('template_id',$template_id)->where('asset_type',0)->where('user_id',$user_id)->get();

        $classTypes = ClassTypes::all();

        return view('MasterTemplate.assets.add')->with(compact('FormTemplate','cmpFeed','showClasses','templateCategory','TD_variables','template_id','pre_fields','formid','assets','classTypes','ownerForHorse'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
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
        $templateCategory = GetTemplateType($template_id);
        $form_id = $request->form_id;
        $asset = $request->get('asset');

        $form_type = Form::select("form_type")->where('id', $form_id)->first()->toArray();
        $fieldsarray = $request->fields;
        if($form_type['form_type'] == F_ASSETS || $form_type['form_type'] == RIDER_ASSETS ||  $form_type['form_type'] == F_PARENT_ASSETS){
            $model = new Asset();
            $model->template_id = $template_id;
            $model->form_id = $form_id;
            $model->user_id = $user_id;
            $model->employee_id = $employee_id; // employee to manage the applciation

            if (isset($request->is_combined)) {
                $model->is_combined = $request->is_combined;
            }
            if (isset($request->primary_required)) {
                $model->primary_required = $request->primary_required;
            }

            if($form_type['form_type'] == F_PARENT_ASSETS)
                $model->asset_type = 1;
            if($form_type['form_type'] == RIDER_ASSETS)
                $model->asset_type = 2;
            if(isset($request->is_required_point_selection))
            $model->is_required_point_selection =$request->is_required_point_selection;

            if(isset($request->feedback_compulsary)){
                $model->feedback_compulsary = json_encode($request->feedback_compulsary);
            }
            $model->is_required_point_selection =$request->is_required_point_selection;

            if(isset($request->horse_rating_type)) {
                $model->horse_rating_type = $request->horse_rating_type;
            }
            //Assigning the fields in json form
            $model->fields = submitFormFields($request);
            $model->class_type = $request->class_type;

            $model->name = GetAssetName(submitFormFields($request));

            $model->save();

            if($request->owner_id) {
                $model->OwnerUpdate()->sync($request->owner_id);
            }
                //Assigning Combined class if applicable
                if (isset($request->is_combined) && isset($request->combinedClasses)) {
                    foreach ($request->combinedClasses as $classes) {
                        $combined_class = new CombinedClass();
                        $combined_class->class_id = $classes;
                        $combined_class->combined_class_id = $model->id;
                        $combined_class->heights = json_encode($request->cc_heights);
                        $combined_class->save();
                    }
                }

                if($asset) {
                    foreach ($asset as $key => $value) {

                        $mod = new AssetParent();

                        $mod->asset_id = $model->id;

                        $mod->parent_id = $value;

                        $mod->save();

                    }
                }

        }
        $assets_id = $model->id;
        /* ------------ THIS WILL ADD DEFAULT MODULES TO FORM WHEN THEY ARE STORED*/
        $assModTemp = AssetModuleTamplate::where('form_id',$form_id)->where('template_id',$template_id)->first();
                if (!is_null($assModTemp)>0) {
                    $model = new AssetModules();
                    $model->template_id = $template_id;
                    $model->asset_id = $assets_id;
                    $model->user_id = $user_id;
                    $model->modules_permission = $assModTemp->modules_permission;
                    $model->employee_id = $employee_id;
                    $model->save();
                }

        /****| This has been disabled because of client requirement changing rapidly. Dont Remove|**/
        
        /*if (isset($request->is_combined)) {
             \Session::flash('message', 'Placements for this asset');
             return redirect()->route('PositionController-index', ['asset_id' => nxb_encode($model->id)]);
        }
        if (isset($model->id)&& ($templateCategory == SHOW)) {
            \Session::flash('message', 'Add Invoice value for recent added asset.');
             //return redirect()->route('ShowController-invoice', ['asset_id' => nxb_encode($model->id)]);
            return redirect()->route('PositionController-index', ['asset_id' => nxb_encode($model->id)]);
        }*/
        
        return redirect()->route('master-template-manage-assets', ['template_id' => nxb_encode($template_id)]);
    }

        /**
     * Show the form for adding the split.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function splitClassIndex($id)
    {
        $id = nxb_decode($id);
        $Asset = Asset::findOrFail($id);
        $user_id = \Auth::user()->id;
        
        $template_id = $Asset->template_id;
        $form_id = $Asset->form_id;
        $FormTemplate = Form::where('id',$form_id)->first();
        $TemplateDesign = getTemplateDesignQry($template_id,$user_id); 
            $TD_variables = null;
            $pre_fields = null;
            $formid = null;
           if ($FormTemplate) {
                //MasterTemplate Design Variable  -->
                $TD_variables = getTemplateDesign($TemplateDesign);
                $pre_fields = json_decode($FormTemplate->fields, true);
                $answer_fields = json_decode($Asset->fields, true);
                $orignal_name = "No name";
                if(isset($answer_fields[0])){
                    $orignal_name = $answer_fields[0]['answer'];
                    $answer_fields[0]['answer'] = "California Split ".$orignal_name;
                }

                // END: MasterTemplate Design Variable  -->
                $formid = $FormTemplate->id;
           }

          $assets = [];
        $templateCategory = GetTemplateType($template_id);
       return view('MasterTemplate.assets.shows.split')->with(compact('Asset','combined_class','orignal_name','showClasses','templateCategory','answer_fields','FormTemplate','TD_variables','template_id','pre_fields','formid','assets','parentAsset'));
    }

        /**
     * split a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function splitClassSave(Request $request)
    {
        $orignal_id = $request->orignal_id;

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
        $form_id = $request->form_id;

        $form_type = Form::select("form_type")->where('id', $form_id)->first()->toArray();
        $fieldsarray = $request->fields;
        if($form_type['form_type'] == F_ASSETS || $form_type['form_type'] == RIDER_ASSETS ||  $form_type['form_type'] == F_PARENT_ASSETS){
            $model = new Asset();
            $model->template_id = $template_id;
            $model->form_id = $form_id;
            $model->user_id = $user_id;
            $model->employee_id = $employee_id; // employee to manage the applciation
            if (isset($request->is_split)) {
                $model->is_split = $request->is_split;
            }

            if($form_type['form_type'] == F_PARENT_ASSETS)
                $model->asset_type = 1;
            if($form_type['form_type'] == RIDER_ASSETS)
                $model->asset_type = 2;

            if(isset($request->horse_rating_type)) {
                $model->horse_rating_type = $request->horse_rating_type;
            }else{
                $model->horse_rating_type = 0;
            }

            //Assigning the fields in json form
            $model->fields = submitFormFields($request);
            $model->save();

            if ($model->id) {
                # code...
                $SCS = new ShowClassSplit();
                $SCS->orignal_class_id = $orignal_id;
                $SCS->split_class_id = $model->id;
                $SCS->save();
            }

            \Session::flash('message', 'Your Split Has been made successfully');


        }
        //  if (isset($request->is_split)) {
        //      \Session::flash('message', 'Placements for this asset');
        //      return redirect()->route('PositionController-index', ['asset_id' => nxb_encode($model->id)]);
        // }
        return redirect()->route('master-template-manage-assets', ['template_id' => nxb_encode($template_id)]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $id = nxb_decode($id);
        $Asset = Asset::with('splitOrignalClass')->where('id',$id)->first();
        $user_id = \Auth::user()->id;
        
        $template_id = $Asset->template_id;
        $form_id = $Asset->form_id;

        $ownerSelected = HorseOwner::where('horse_id',$id)->pluck('owner_id')->toArray();

        $ownerForHorse = getRiderOwner($user_id,$template_id);

        $FormTemplate = Form::where('id',$form_id)->first();
        $cmpFeed = Form::whereIn('feedback_type',[1,2,3])->where('template_id', $template_id)->get();
        $TemplateDesign = getTemplateDesignQry($template_id,$user_id); 
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

          $assets = [];
        $templateCategory = GetTemplateType($template_id);
        if($FormTemplate->form_type != 7)
            $assets = Asset::select('assets.id','assets.fields')->where('template_id',$template_id)->where('asset_type',1)->where('user_id',$user_id)->get();

        $combined_class = CombinedClass::where('combined_class_id',$id)->pluck('class_id')->toArray();

        $parentAsset = AssetParent::where('asset_id',$id)->pluck('parent_id')->toArray();
        //dd($parentAsset);
        $showClasses = Asset::where('template_id',$template_id)->where('asset_type',0)->where('user_id',$user_id)->get();
        //Checking compulsory feedback
        $compulsoryfeedback = null;
        if (isset($Asset->feedback_compulsary)) {
           $compulsoryfeedback =json_decode($Asset->feedback_compulsary,true);
        }

        $classTypes = ClassTypes::all();

        $heights = CombinedClass::where('combined_class_id',$id)->pluck('heights')->first();
        $heights = json_decode($heights);


        return view('MasterTemplate.assets.edit')->with(compact('Asset','cmpFeed','compulsoryfeedback','combined_class','showClasses','templateCategory','answer_fields','FormTemplate','TD_variables','template_id','pre_fields','formid','assets','parentAsset','classTypes','ownerForHorse','ownerSelected','heights'));
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

        $aset = Asset::where('id',$id)->first();
        $asset_id = $aset->id;
        $template_id = $aset->template_id;
        $participantResponse = ParticipantResponse::with("participant")->whereHas('participant', function ($query) use ($id) {
            $query->where('asset_id', $id);
        })->orWhere(function ($query) use ($id) {
            $query->where('asset_id',$id);
        })->orderBy('id', 'desc')->get();


        return view('MasterTemplate.assets.history')->with(compact('participantResponse','asset_id','template_id'));
    }
    /**
     * Show the History for the All available template assets.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function allHistory(Request $request, $template_id)
    {
        $template_id = nxb_decode($template_id);
        $user_id = \Auth::user()->id;
        $data['data']=array();
        
        $show_ids = ManageShows::select('id')->where('user_id',$user_id)->where('template_id',$template_id)->get()->toArray();
        $templateType = GetTemplateType($template_id);



        if($templateType==SHOW || $templateType == TRAINER) {
            $participantResponse = ParticipantResponse::with('subparticipants', 'participant')->whereHas("participant", function ($que) use ($show_ids,$user_id) {
                $que->whereIn('show_id', $show_ids);
                $que->where('invitee_id', $user_id);

            })
                ->with('assets')
                ->where("participant_responses.template_id", $template_id)
                ->orderBy('participant_responses.id', 'desc')->get();
        }else {

            $participantResponse = ParticipantResponse::with(['subparticipants', 'assets', 'participant'])
                ->whereHas('participant', function ($query) use ($user_id,$template_id) {
                    $query->where('invitee_id', $user_id);
                    $query->where("template_id", $template_id);
                })->orWhere(function ($query) use ($user_id,$template_id) {
                    $query->where('asset_id', '!=', null);
                    $query->where('user_id', $user_id);
                    $query->where("template_id", $template_id);
                })
                ->orderBy('participant_responses.id', 'desc')->get();
        }

        //dd($participantResponse->toArray());

        foreach ($participantResponse as $row)
        {
            if(isset($row->participant))
                $assetName=GetAssetNamefromId($row->participant->asset_id);
            else
                $assetName ='';

            if (isset($row->participant)) {
                $location = $row->participant->location;
            }else{
                $location = "";
            }
            if($row->subparticipants && $row->user)
            {
               $subName = getUserNamefromEmail($row->subparticipants->email);
               $userName = $subName.' on behalf of '.$row->user->name;
            }else{

                $userName = $row->user->name;
            }
            if($templateType==SHOW || $templateType == TRAINER)
            $miscColumn = getShowName($row->participant->show_id);
            else
            $miscColumn = $location;

                $data['data'][]=['response_number'=>nxb_encode($row->id),'user_name'=>$userName,'asset_name'=>$assetName,
              'form_name'=>$row->form->name,
              'miscColumn'=>$miscColumn,
              'created_at'=>getDates($row->created_at),
              'response_id'=>nxb_encode($row->id)];
        }
        if($request->ajax()) {
            $collection = collect($data['data']);

            return Datatables::of($collection)->toJson();
        }
        //  Get forms
        $forms = ParticipantResponse::select('form_id')->where("participant_responses.template_id",$template_id)
            ->with(["participant"=> function ($query) use ($user_id) {
                                    $query->where('invitee_id', $user_id);
                                }])->groupBy('form_id')->orderBy('id','des')->get();

        return view('MasterTemplate.assets.allHistory')->with(compact('forms','participantResponse','user_id','template_id','templateType'));
    }
    
    /**
     * Display the Participant Response. Read only
     *
     * @param  int  $response_id
     * @return \Illuminate\Http\Response
     */
    public function viewParticipantResponse($response_id)
    {
        $response_id = nxb_decode($response_id);
        $Asset = ParticipantResponse::findOrFail($response_id);
        $template_id = $Asset->template_id;
        $form_id = $Asset->form_id;
        $user_id = \Auth::user()->id;


        $FormTemplate = Form::where('id',$form_id)->first();
        $TemplateDesign = getTemplateDesignQry($template_id,$user_id);
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $model = Asset::findOrFail($id);
        $user_id = \Auth::user()->id;


        $template_id = $request->template_id;
        $form_id = $request->form_id;
        $form_type = Form::select("form_type")->where('id', $form_id)->first()->toArray();
        $fieldsarray = $request->fields;

        $asset = $request->get('asset');


        $owner = $request->get('owner_id');


        if($form_type['form_type'] == F_ASSETS || $form_type['form_type'] == RIDER_ASSETS || $form_type['form_type'] == F_PARENT_ASSETS){
            $model->template_id = $template_id;
            $model->form_id = $form_id;
           // $model->user_id = $user_id;

            if($form_type['form_type'] == F_PARENT_ASSETS)
                $model->asset_type = 1;
            if($form_type['form_type'] == RIDER_ASSETS)
                $model->asset_type = 2;

            //Assigning the fields in json form
             if (isset($request->primary_required)) {
                $model->primary_required = $request->primary_required;
            }
            if (isset($request->is_combined)) {
                $model->is_combined = $request->is_combined;
            }
            $model->fields = submitFormFields($request);
            if(isset($request->is_required_point_selection))
            $model->is_required_point_selection =$request->is_required_point_selection;

            if(isset($request->feedback_compulsary)){
                $model->feedback_compulsary = json_encode($request->feedback_compulsary);
            }else{
                $model->feedback_compulsary = null;
            }

            if(isset($request->horse_rating_type)) {
                $model->horse_rating_type = $request->horse_rating_type;
            }else{
                $model->horse_rating_type = 0;
            }

            $model->class_type = $request->class_type;

            /*********** in order to save asset name *****************/

            //$model->name = GetAssetName(submitFormFields($request));
            $model->name = GetAssetName(submitFormFields($request));


            $model->update();

             //Assigning Combined class if applicable
            if (isset($request->is_combined) && isset($request->combinedClasses)) {
                $cclass = CombinedClass::where('combined_class_id',$id);
                if (count($cclass->get())>0) {
                    $cclass->delete();
                }
                foreach ($request->combinedClasses as $classes) {
                    $combined_class = new CombinedClass();
                    $combined_class->class_id = $classes;
                    $combined_class->combined_class_id = $model->id;
                    $combined_class->heights = json_encode($request->cc_heights);
                    $combined_class->save();
                }
            }

            $model->assetParent()->sync($asset);


            if($owner!=null)
             $model->OwnerUpdate()->sync($owner);

            return redirect()->route('master-template-manage-assets', ['template_id' => nxb_encode($template_id)]);

        }else{
            return redirect()->back();
        }
        
    }

    /**
     * Dynamically creating the table of Assets for users.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function getDynamicDataTable($template_id)
    // {
    //     $user_id = \Auth::user()->id;
    //     $header = Form::where('template_id',$template_id)->where("form_type",2)->first();
    //     $header  = json_decode($header->fields);
    //     //Header Columns
    //     $columns= array();
    //     foreach ($header as $key => $field) {
    //         //Donot need images , Videos, uploads ,label
    //         if (exclueded_fields_datatable($field->form_field_type)) {
    //             $columns[]= $field->form_name;
    //         }
    //     }
    //     $columns[]= "Action";
    //     //Collection of the assets
    //     $collection = Asset::where('template_id',$template_id)->where('user_id',$user_id)->get();
    //     foreach ($collection as $key => $field) {
    //         $innr  = json_decode($field->fields, true);
    //         $data = [$innr];
    //         array_walk($data, 'parseGridRow', ["assetid"=> $field->id]);
    //         $dataSet[] = array_first($data);
    //     }
    //     //All data for the row and colums
    //     $datas = ["columns" =>$columns, "dat" => $dataSet ];
    //     return response()->json($datas);
    // }
    

    /**
     * Dynamically creating the table of Assets for users.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getDynamicDataTableAjax($form_id)
    {
        $form = Form::where('id',$form_id)->first();
        $formType = $form->form_type;
        $header  = json_decode($form->fields);

        $isEmail = \Session('isEmployee');
        $userEmail = \Auth::user()->email;


        if($isEmail==1) {
            $user_id = getAppOwnerId($userEmail,$form->template_id);
        }
        else {
            $user_id = \Auth::user()->id;
            $employee_id = 0;
        }


        if ($header != null) {
        //Header Columns
        $columns= array();
        foreach ($header as $key => $field) {
            //Donot need images , Videos, uploads ,label
            if (exclueded_fields_datatable($field->form_field_type)) {
                //$columns[]=$field->form_name;
                $columns["name"][]= $field->form_name;
                $columns["unique_id"][]= $field->unique_id;
            }
        }
        $Col_length = sizeof($columns["name"]);

          if($formType==7) {

              $assetType = "Primary Asset";

              $columns[] = "Secondary Asset";

          }
           else {
               $assetType = "Secondary Asset";

               $columns["name"][] = "Primary Asset";
           }
            $columns["name"][]= "Action";

        //Collection of the assets
        $showExist = Template::where('id',$form->template_id)->where("category",CONST_SHOW)->count();

        $collection = Asset::where('form_id',$form_id)->where('user_id',$user_id)->get();


            if ($collection->count()) {
            foreach ($collection as $key => $field) {
                $innr  = json_decode($field->fields, true);
                $data = [$innr];
                //$found = recursive_array_search($field->form_field_id,$columns["unique_id"]);
                array_walk($data, 'parseGridRow', ["lengths"=>$Col_length,"assetid"=> $field->id,"showExist"=>$showExist,"parent"=>$field->assetParent(),'asset_type'=>$field->asset_type,'sub'=>$field->subAssets(),'template_id'=>$form->template_id]);
                $dataSet[] = array_first($data);
            }
        }else{
            $dataSet = [];
        }

        //All data for the row and colums
        $datas = ["columns" =>$columns, "dat" => $dataSet,"assetType"=>$assetType ];
        }
        else{
            $columns= null;
            $dataSet= null;

            if($formType==7) {
                $assetType = "Primary Asset";
                $columns[] = $assetType;
            }
            else {
                $assetType = "Secondary Asset";
                $columns[] = $assetType;
            }

            $datas = ["columns" =>$columns, "dat" => $dataSet,"assetType"=>$assetType];
        }

        return response()->json($datas);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $id = nxb_decode($id);
        $Asset = Asset::findOrFail($id);
       
        $scs = ShowClassSplit::where('split_class_id',$id);
        if (count($scs->get())>0) {
            $scs->delete();
        }
        $cclass = CombinedClass::where('combined_class_id',$id);
        if (count($cclass->get())>0) {
            $cclass->delete();
        }

         $Asset->delete();
        \Session::flash('message', 'Your Asset has been deleted successfully');
        
        return \Redirect::back();
    }
    
    /**
     * @return string
     */
    public function associateModules($assetId)
    {
       $asset_id = nxb_decode($assetId);
        
      $temp = Asset::where('id',$asset_id)->first();
      $template_id =  $temp->template_id;
      $modules = Module::with(['form_module','childModule'=>function($q) use($template_id){
          $q->where('template_id',$template_id);
      },'parentModule'=>function($qu) use($template_id){
          $qu->where('template_id',$template_id);
      }])
          ->where('template_id',$template_id)->groupBy('id')->get();

      $showExist = Template::where('id',$template_id)->where("category",CONST_SHOW)->count();
      $modulesArray = [];
        $readOnlyArray = [];
      $assetModules = AssetModules::where('template_id',$template_id)->where('asset_id',$asset_id)->first();


      if($assetModules) {
          
          $modules_permission = json_decode($assetModules->modules_permission, true);


          If(count($modules_permission)>0) {
              $modulesArray = array_filter($modules_permission, 'filterModulePermissionArray');

              $modulesArray = array_keys($modulesArray);

              $readOnlyArray = array_filter($modules_permission, 'filterReadOnlyArray');

              $readOnlyArray = array_keys($readOnlyArray);
          }
      }
        return view('MasterTemplate.assets.modules')->with(compact('modules','showExist','asset_id','template_id','modulesArray','readOnlyArray'));
    }
    
    
    /**
     * @return string
     */
    public function submitModules(Request $request)
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


        $modules = $request->get('module');

        $arr = getModuels($modules);

       // ArrayPrint($arr);

        $fields_inputs = json_encode($arr);

        $assetModules = AssetModules::where('template_id', $request->get('template_id'))
                        ->where('asset_id',$request->get('asset_id'))->first();
        if($assetModules)
        {
           $model = AssetModules::findorFail($assetModules->id);
            $model->template_id = $request->get('template_id');
            $model->asset_id = $request->get('asset_id');
            $model->user_id = $user_id;
            $model->modules_permission = $fields_inputs;
            $model->employee_id = $employee_id;

            $model->update();
        }
          else {
              $model = new AssetModules();
              $model->template_id = $request->get('template_id');
              $model->asset_id = $request->get('asset_id');
              $model->user_id = $user_id;
              $model->modules_permission = $fields_inputs;
              $model->employee_id = $employee_id;

              $model->save();
          }
        \Session::flash('message', 'Modules has been associated with the asset successfully');
    
        //echo 'success';exit;
        $url = $request->only('redirects_to');
        return redirect()->to($url['redirects_to']);
        //return redirect()->back();

        //return redirect()->route('master-template-associate-modules', ['id' => nxb_encode($request->get('asset_id'))]);
    
     //   return \Redirect::back();
     
    }

    /** 
     * @return string
     */
    public function associateModulesTemplate(Request $request)
    {
      $template_id = $request->template_id;
      $form_id = $request->form_id;
      $modules = Module::with(['childModule'=>function($q) use($template_id){
          $q->where('template_id',$template_id);
      },'parentModule'=>function($qu) use($template_id){
          $qu->where('template_id',$template_id);
      }])
          ->where('template_id',$template_id)->groupBy('id')->get();

     // dd($modules->toArray());
      $modulesArray = [];
        $readOnlyArray = [];
      $assetModules = AssetModuleTamplate::where('template_id',$template_id)->where('form_id',$form_id)->first();

      if($assetModules) {

          $modules_permission = json_decode($assetModules->modules_permission, true);


          If(count($modules_permission)>0) {
              $modulesArray = array_filter($modules_permission, 'filterModulePermissionArray');

              $modulesArray = array_keys($modulesArray);

              $readOnlyArray = array_filter($modules_permission, 'filterReadOnlyArray');

              $readOnlyArray = array_keys($readOnlyArray);
          }
      }
      
        return view('MasterTemplate.assets.modulesTemplate')->with(compact('modules','form_id','template_id','modulesArray','readOnlyArray'));
    }


    /**
     * @return string
     */
    public function submitModulesTemplate(Request $request)
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

        $modules = $request->get('module');
        $arr = getModuels($modules);
        $fields_inputs = json_encode($arr);
        $assetModules = AssetModuleTamplate::where('template_id', $request->get('template_id'))
                        ->where('form_id',$request->get('form_id'))->first();
        if($assetModules)
        {
           $model = AssetModuleTamplate::findorFail($assetModules->id);
            $model->template_id = $request->get('template_id');
            $model->form_id = $request->get('form_id');
            $model->user_id = $user_id;
            $model->modules_permission = $fields_inputs;
            //$model->employee_id = $employee_id;
            $model->update();
        }else{
              $model = new AssetModuleTamplate();
              $model->template_id = $request->get('template_id');
              $model->form_id = $request->get('form_id');
              $model->user_id = $user_id;
              $model->modules_permission = $fields_inputs;
              //$model->employee_id = $employee_id;
              $model->save();
        }
        \Session::flash('message', 'Modules has been associated with the asset successfully');
        $url = $request->only('redirects_to');
        return redirect()->to($url['redirects_to']);

    }



    public function subAssets($id)
    {
        $id = nxb_decode($id);

        $assets = Asset::where('id',$id)->select('template_id','form_id')->first();

        $template_id = $assets->template_id;
        $form_id = $assets->form_id;

        $subAsset = AssetParent::where('parent_id',$id)->orderBy('id', 'desc')->get();
        return view('MasterTemplate.assets.viewSubAssets')->with(compact('id','subAsset','template_id'));

//
//
//        $assets = Asset::where('id',$id)->select('template_id','form_id')->first();
//
//       $template_id = $assets->template_id;
//        $form_id = $assets->form_id;
//
//        $subAsset = AssetParent::where('parent_id',$id)
//            ->with(['assetsScheduler'=>function($q) use($form_id,$id){
//                $q->where('form_id',$form_id)
//                ->where('show_id',$id);
//            }])->orderBy('id', 'desc')->get();
//
//        return view('MasterTemplate.assets.viewSubAssets')->with(compact('id','subAsset','template_id','form_id'));
    }

    public function assetManageScheduler($id)
    {

        $id = nxb_decode($id);

        $app_id = \Session('app_id');

        $assets = Asset::where('id',$id)->select('template_id','form_id')->first();


        $isEmail = \Session('isEmployee');
        $userEmail = \Auth::user()->email;

        if($isEmail==1) {
            $user_id = getAppOwnerId($userEmail,$assets->template_id);
            $employee_id = \Auth::user()->id;
        }
        else {
            $user_id = \Auth::user()->id;
            $employee_id = 0;
        }

        $template_id = $assets->template_id;
        $form_id = $assets->form_id;

        $templateType = GetTemplateType($assets->template_id);
        $manageShows = ManageShows::where('template_id', $template_id)->where('user_id', $user_id)->orderBy('id', 'Desc')->get();

        if($templateType==TRAINER) { // Trainer Scheduler

            if($manageShows->count() == 0)
            {
                return redirect()->route('master-template-list-schedular',['id' => nxb_encode($assets->template_id),'appId'=>nxb_encode($app_id)]);
            }

            $subAsset = AssetParent::where('parent_id', $id)
                ->with(['assetsScheduler' => function ($q) use ($form_id, $id) {
                    $q->where('scheduler_id', $id)->groupBy('show_id');
                }])->orderBy('id', 'desc')->get();
        }
        else {
            $subAsset = AssetParent::where('parent_id', $id)
                ->with(['assetsScheduler' => function ($q) use ($form_id, $id) {
                    $q->where('show_id', $id)->groupBy('asset_id', 'restriction');
                }])->orderBy('id', 'desc')->get();
        }
        //dd($subAsset->toArray());




        return view('MasterTemplate.assets.viewManageScheduler')->with(compact('id','subAsset','template_id','form_id','app_id','manageShows'));
    }



public function exportAssetCsv($template_id)
{


    $isEmail = \Session('isEmployee');
    $userEmail = \Auth::user()->email;


    if($isEmail==1) {
        $user_id = getAppOwnerId($userEmail,$template_id);
    }
    else {
        $user_id = \Auth::user()->id;
        $employee_id = 0;
    }

   $template_id = nxb_decode($template_id);

    $forms = Form::where('template_id',$template_id)
            ->where(function ($query) {
            $query->where("form_type",F_ASSETS)
                ->orWhere('form_type', F_PARENT_ASSETS)
                ->orWhere('form_type', RIDER_ASSETS);
        })->orderBy('form_type', 'desc')->get();


    $columns = array();

    foreach ($forms as $row) {
        $header = Form::where('id', $row->id)->first();
        $formType = $header->form_type;
        $header = json_decode($header->fields);

        if ($header != null) {
            //Header Columns
            foreach ($header as $key => $field) {
                //Donot need images , Videos, uploads ,label
                if (exclueded_fields_datatable($field->form_field_type)) {
                    $columns[$row->name]['Columns'][] = $field->form_name;
                }
            }

            $collection = Asset::where('form_id', $row->id)->where('user_id', $user_id)->where('template_id', $template_id)->get();
            if ($collection->count()) {
                foreach ($collection as $key => $field) {
                    $innr = json_decode($field->fields, true);
                    $data = [$innr];

                    if ($data) {
                        $ar = array();
                        foreach (array_first($data) as $k => $val) {
                            if ($val["form_field_type"] == OPTION_DROPDOWN || 
                                $val["form_field_type"] == OPTION_AUTO_POPULATE ||
                                $val["form_field_type"] == OPTION_BREEDS_AUTO_POPULATE ||
                                $val["form_field_type"] == OPTION_BREEDS_STATUS_AUTO_POPULATE ||
                                $val["form_field_type"] == OPTION_HORSE_AGE_AUTO_POPULATE ||
                                $val["form_field_type"] == OPTION_RIDER_AGE_AUTO_POPULATE ) {
                                if (isset($val['answer'])) {
                                    if (is_array($val['answer'])) {
                                        $val['answer'] = implode(',', $val['answer']);
                                    }else {
                                        $assete = explode("|||", $val["answer"]);
                                        $assete = $assete[0];
                                        $ar[$val['form_name']] = $assete;
                                    }
                                }


                            } else {
                                if (isset($val['answer'])) {

                                    if (is_array($val['answer']))
                                        $val['answer'] = implode(',', $val['answer']);
                                    $ar[$val['form_name']] = $val['answer'];

                                }
                            }
                        }
                        if(isset($ar))
                         $columns[$row->name]['data'][] = $ar;

                    }
                }
            } else {
                $dataSet = [];
            }
        } else {
            //$columns = null;
            $dataSet = null;
            $datas[] = ["columns" => $columns, "dat" => $dataSet];
        }

    }

        if (isset($columns)) {
            Excel::create('AssetsData', function($excel) use($columns) {
                $invalidCharacters = array('*', ':', '/', '\\', '?', '[', ']');
                  
                foreach ($columns as $c => $v) {
                    $c = str_replace($invalidCharacters, ' ', $c);

                    $excel->sheet($c, function ($sheet) use ($v) {

                        $sheet->row(1, $v['Columns']); // etc etc
                         if (isset($v['data']) && count($v['data'])>0)
                            $sheet->rows($v['data']);
                    });
                }
            })->download('xls');
        }else{
             \Session::flash('message', 'There is an issue in fetching your data');
             return \Redirect::back();
        }


}

    /**
     * Faran: Show assets Prizing listing history for shows templates.
     *
     * @return \Illuminate\Http\Response
     */
    public function prizingListing($asset_id)
    {
        $user_id = \Auth::user()->id;
        $asset_id = nxb_decode($asset_id);
        $collection = ShowPrizingListing::with("shows")->where("asset_id",$asset_id)->get();
        $asset = Asset::find($asset_id);
        $template_id= $asset->template_id;
        return view('MasterTemplate.assets.shows.prizing')->with(compact("asset_id","asset",'collection','template_id'));
        
    }


    public function horseProfile($id)
    {
        $id = nxb_decode($id);
        $Asset = Asset::findOrFail($id);
        $template_id = $Asset->template_id;
        $form_id = $Asset->form_id;
        $FormTemplate = Form::where('id',$form_id)->first();
        
        $TemplateDesign = getTemplateDesignQry($template_id);
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

        $assets = [];
        if($FormTemplate->form_type != 7)
            $assets = Asset::select('assets.id','assets.fields')->where('template_id',$template_id)->where('asset_type',1)->get();

        $parentAsset = AssetParent::where('asset_id',$id)->pluck('parent_id')->toArray();
        // $secret = 1;
        // if($Asset->user_id == $user_id){
        //     $secret = 0;
        // }
        return view('MasterTemplate.assets.horseProfile')->with(compact('Asset','answer_fields','FormTemplate','TD_variables','template_id','pre_fields','formid','assets','parentAsset'));
    }
    //Adding secret Profile link
    public function secrethorseProfile($id)
    {
        $user_id = \Auth::user()->id;
        $id = nxb_decode($id);
        $Asset = Asset::findOrFail($id);
        $template_id = $Asset->template_id;
        $form_id = $Asset->form_id;
        $FormTemplate = Form::where('id',$form_id)->first();
        $TemplateDesign = getTemplateDesignQry($template_id,$user_id);;
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

        $assets = [];
        if($FormTemplate->form_type != 7)
            $assets = Asset::select('assets.id','assets.fields')->where('template_id',$template_id)->where('asset_type',1)->get();

        $parentAsset = AssetParent::where('asset_id',$id)->pluck('parent_id')->toArray();
        $secret = 1;
        if($Asset->user_id == $user_id){
            $secret = 0;
        }
        return view('MasterTemplate.assets.horseProfile')->with(compact('Asset','secret','answer_fields','FormTemplate','TD_variables','template_id','pre_fields','formid','assets','parentAsset'));
    }
    public function updateScheduleTime(Request $request)
    {
    // For Trainer Scheduler
    $show_id = $request->get('show_id');// we use it for facilty as show level...as show id in horse
    $primaryAsset = $request->get('parentAsset');// we use it for facilty as show level...as show id in horse
    if ($show_id==null)
    $show_id =$primaryAsset;

    $asset_id   = $request->get('asset_id'); //asset to whcih scheduler is setting
    $form_id   = $request->get('form_id'); //parent asset form id to keep the elvel same as horse scheduler
    $template_id   = $request->get('template_id');

    $timeFrom  = $request->get('timeFrom');
    $timeTo  = $request->get('timeTo');
    $is_multiple  = $request->get('is_multiple');

   // dd($is_multiple);
    $dates = '';
    $date  = $request->get('date');

    $facilty_slot_duration = '30:00';

    $assetsArr = explode(',',$asset_id);

    // print_r($assetsArr);exit;

    $time = SchedulerRestriction::where('show_id',$show_id)->whereIn('asset_id',$assetsArr);
    $time->delete();

    for ($k=0;$k<count($assetsArr);$k++) {

    $asset_update = AssetScheduler::where('asset_id',$assetsArr[$k]);
    $asset_update->delete();
    for ($i = 0; $i < count($date); $i++) {
    $d = str_replace(', ',",",$date[$i]);
    $dateArr = explode(',', $date[$i]);

    $m = new AssetScheduler();
    $m->asset_id = trim($assetsArr[$k]);
    $m->show_id = $show_id;
    $m->primary_id = $primaryAsset;
    $m->form_id = $form_id;
    $m->timeFrom = $timeFrom[$i];
    $m->timeTo = $timeTo[$i];

    if(isset($is_multiple[$i]))
    $m->is_multiple_selection = $is_multiple[$i];

    $m->scheduler_date = $d;
    $m->save();

    for ($j = 0; $j < count($dateArr); $j++) {
        $dateFrom = $dateArr[$j] . ' ' . $timeFrom[$i];
        $dateTo = $dateArr[$j] . ' ' . $timeTo[$i];

    $dates = $dateFrom . ' - ' . $dateTo;

    $model = new SchedulerRestriction();
    $model->restriction = $dates;
    if(isset($is_multiple[$i]))
    $model->is_multiple_selection = $is_multiple[$i];

    $model->asset_id = trim($assetsArr[$k]);
    $model->form_id = $form_id;
    $model->restriction = $dates;
    $model->date_from =date('Y-m-d H:i:s',strtotime($dateFrom));
    $model->date_to = date('Y-m-d H:i:s',strtotime($dateTo));
    $model->scheduler_id = $primaryAsset;
    $model->show_id = $show_id;
    $model->slots_duration = $facilty_slot_duration;
    $model->save();

    }

    }
    }

    return redirect()->route('master-template-asset-scheduler',['id' => nxb_encode($primaryAsset)]);

    }

    public function assetSchedulers($asset_id,$primary_id,$form_id,$show_id=null)
    {

        if(!is_null($show_id))
            $assetScheduler = AssetScheduler::where('asset_id',$asset_id)->where('show_id',$show_id)->get();
        else
        $assetScheduler = AssetScheduler::where('asset_id',$asset_id)->get();

       $view = view('MasterTemplate.assets.assetSchedulers')->with(compact('assetScheduler'))->render();

       return response()->json(['view'=>$view,'asset_title'=>GetAssetNamefromId($asset_id)]);
    }

    public function getQrCode($asset_id)
    {
        $url = route('participant-mastertemp-view-accet',nxb_encode($asset_id));
        $png = QrCode::format('png')->size(256)->generate($url);
        $png = base64_encode($png);
        $arr['qrCodeUrl'] = 'data:image/png;base64,'. $png;
        $arr['qrCode'] = "<img src='data:image/png;base64," . $png . "'>";
        return $arr;
    }

    /**
     * @param $asset_id
     * getting asset tiles against asset id's in comma seperated
     */
    public function getAssetTitles($asset_id)
    {
        $asset_id =  explode(',',$asset_id);
    return Asset::whereIn('id',$asset_id)->pluck('name')->all();
    }



}
