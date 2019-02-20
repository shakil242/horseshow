<?php
    /**
     * This is Module Controller For frontend to control all the project overvew business logic in this project
     *
     * @author Faran Ahmed (Vteams)
     * @date 03-July-2018
     */


namespace App\Http\Controllers;

use App\AssetParent;
use App\Participant;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Module;
use App\Form;
use App\TemplateDesign;
use App\Template;
use App\Asset;
use App\ParticipantResponse;
use App\CombinedClass;
use App\ShowClassSplit;
use DataTables;
use App\ParticipantProjectov;
use App\ProjectOvEmail;

class ProjectController extends Controller
{
    /**
     * Display a listing of the modules in view master template.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($template_id)
    {

        $isEmail = \Session('isEmployee');

        $user_id = \Auth::user()->id;
        $template_id = nxb_decode($template_id);
        //$collection = Asset::where('template_id',$template_id)->where('user_id',$user_id)->get()->toArray();
        // $AssetsForms =  Form::with('assets')->where('template_id',$template_id)->where('form_type',F_ASSETS)->get();
       // exit;

        $rr[]=F_PROJECT_OVERVIEW;
        $rr[]=RIDER_ASSETS;

        $AssetsForms = Form::where('template_id',$template_id)->whereIn('form_type',$rr)->get();
        $parentAssetsForms = Form::where('template_id',$template_id)->where('form_type',F_PARENT_ASSETS)->get();
        return view('MasterTemplate.projectoverview.index')->with(compact("template_id",'AssetsForms'));
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

        //Feedback Form type:
        $cmpFeed = Form::whereIn('feedback_type',[1,2,3])->where('template_id', $template_id)->get();
        $TemplateDesign = TemplateDesign::where('template_id', $template_id)->first();
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

        return view('MasterTemplate.projectoverview.add')->with(compact('FormTemplate','cmpFeed','showClasses','templateCategory','TD_variables','template_id','pre_fields','formid','assets'));
    }

    /**
     * Display the Module resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function projectHistory($participant_id)
    {

        $participant_id = nxb_decode($participant_id);
        $participantResponse = ParticipantProjectov::with('projectOverview')->where('participant_invited_id',$participant_id)->get();
        return view('MasterTemplate.projectoverview.attachedHistory')->with(compact('participantResponse','participant_id'));
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
        $form_id = $request->form_id;
        $asset = $request->get('asset');

        $form_type = Form::select("form_type")->where('id', $form_id)->first()->toArray();
        $fieldsarray = $request->fields;

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

            $model->asset_type = F_PROJECT_OVERVIEW;

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

            $model->save();

        return redirect()->route('master-template-manage-Project', ['template_id' => nxb_encode($template_id)]);
    }

    /**
     * Display the project list of submissions.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function projectSubmissions($projectoverview_id,$template_id)
    {

        $projectoverview_id = nxb_decode($projectoverview_id);
        $userid = \Auth::user()->id;
        $useremail = \Auth::user()->email;
        $username = \Auth::user()->name;
        $template_id = nxb_decode($template_id);
        $participantResponse = null;
        $projectInvite = ParticipantProjectov::select('participant_invited_id')->where('project_overview_id',$projectoverview_id)->get()->toArray();
        if (count($projectInvite)>0) {
          $participantResponse = ParticipantResponse::with("user","form",'assets')->whereIn('participant_id',$projectInvite)->orderBy('id', 'desc')->get();
        }
        // dd($participantResponse->toArray());
        return view('MasterTemplate.projectoverview.viewSubmissions')->with(compact('participantResponse','projectoverview_id','template_id','username','useremail'));
    }

    /**
     * Display the overview emails send by admin
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function projectEmailList($participant_response_id)
    {

        $participant_response_id = nxb_decode($participant_response_id);
        $userid = \Auth::user()->id;
        $participantResponse = ProjectOvEmail::where('participant_response_id',$participant_response_id)->get();
        return view('MasterTemplate.projectoverview.emailSent')->with(compact('participant_response_id','participantResponse'));
    }
    /**
     * Display the overview emails send by admin Full list
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function projectEmailListFull($template_id)
    {
        $template_id = nxb_decode($template_id);
        $participantResponse = ProjectOvEmail::with('ParticipantResponse')->whereHas('ParticipantResponse', function ($query) use ($template_id) {
                                    $query->where('template_id', $template_id);
                                })->orderBy('id', 'desc')->get();
        $displayAll = true;
        return view('MasterTemplate.projectoverview.emailSent')->with(compact('participant_response_id','participantResponse','displayAll'));
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

        $FormTemplate = Form::where('id',$form_id)->first();
        $cmpFeed = Form::whereIn('feedback_type',[1,2,3])->where('template_id', $template_id)->get();
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
        return view('MasterTemplate.projectoverview.edit')->with(compact('Asset','cmpFeed','compulsoryfeedback','combined_class','showClasses','templateCategory','answer_fields','FormTemplate','TD_variables','template_id','pre_fields','formid','assets','parentAsset'));
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


        $participantResponse = ParticipantResponse::where("participant_responses.template_id",$template_id)
            ->with(["participant"=> function ($que) use ($user_id) {
                $que->where('invitee_id', $user_id);
            }])->orderBy('participant_responses.id', 'desc')->get();


        if($request->ajax())
        {

            return Datatables::of($participantResponse)
                ->addColumn('users', function (ParticipantResponse $ParticipantResponse) {
                    return $ParticipantResponse->user->name;
                })
                ->addColumn('assetName', function (ParticipantResponse $ParticipantResponse) {
                    return GetAssetNamefromId($ParticipantResponse->asset_id);
                })
                ->addColumn('forms', function (ParticipantResponse $ParticipantResponse) {
                    return $ParticipantResponse->form->name;
                })
                ->addColumn('location', function (ParticipantResponse $ParticipantResponse) {
                    return $ParticipantResponse->location;
                })
                ->addColumn('participant_responses', function (ParticipantResponse $ParticipantResponse) {
                    return $ParticipantResponse->id;
                })
                ->addColumn('created_on', function (ParticipantResponse $ParticipantResponse) {
                    return $ParticipantResponse->created_at;
                })
                ->addColumn('Actions', function (ParticipantResponse $ParticipantResponse) {
                    return ('<a href="'.route('participant-repsones-history',nxb_encode($ParticipantResponse->id)).'" data-toggle="tooltip" data-placement="top" title="View Response"><i class="fa fa-eye" aria-hidden="true"></i></a>
                                                     <a  class="btn-sm btn-default viewInvoiceBtn"
                                                         style="background: green none repeat scroll 0% 0%;"
             href="'.route('export-response-pdf',nxb_encode($ParticipantResponse->id)).'" class="ic_bd_export">Export PDF</a>');

                })
                ->rawColumns(['Actions'])
                ->make(true);


        }


     //   dd($participantResponse->toArray());

        //  Get forms
        $forms = ParticipantResponse::select('form_id')->where("participant_responses.template_id",$template_id)
            ->with(["participant"=> function ($query) use ($user_id) {
                                    $query->where('invitee_id', $user_id);
                                }])->groupBy('form_id')->get();

        return view('MasterTemplate.assets.allHistory')->with(compact('forms','participantResponse','user_id','template_id'));
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
            if($asset!=null)
             $model->assetParent()->sync($asset);

            return redirect()->route('master-template-manage-Project', ['template_id' => nxb_encode($template_id)]);


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
        $columns["name"][]= "Status";
        $Col_length = sizeof($columns["name"]);

        $columns["name"][]= "Action";

        //Collection of the assets
        $showExist = Template::where('id',$form->template_id)->where("category",CONST_SHOW)->count();

        $collection = Asset::where('form_id',$form_id)->where('user_id',$user_id)->get();


            if ($collection->count()) {
            foreach ($collection as $key => $field) {
                $innr  = json_decode($field->fields, true);
                $data = [$innr];
                //$found = recursive_array_search($field->form_field_id,$columns["unique_id"]);
                array_walk($data, 'parseGridRowProject', ["lengths"=>$Col_length,"assetid"=> $field->id,"class_type"=> $field->class_type,"showExist"=>$showExist,"parent"=>$field->assetParent(),'asset_type'=>$field->asset_type,'sub'=>$field->subAssets(),'template_id'=>$form->template_id]);
                $dataSet[] = array_first($data);
            }
        }else{
            $dataSet = [];
        }
        //All data for the row and colums
        $datas = ["columns" =>$columns, "dat" => $dataSet ];
        }
        else{
            $columns= null;
            $dataSet= null;

            if($formType==7) {
                $assetType = "Primary Asset";
                $columns[] = $assetType;
            }
            else {
                $assetType = "Seconday Asset";
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
    public function changeAsset($id,$type)
    {
        $id = nxb_decode($id);
        $type= nxb_decode($type);
        $Asset = Asset::findOrFail($id);
        $Asset->class_type = $type;
        $Asset->save();
        \Session::flash('message', 'Your Asset has been Updated successfully');
        return \Redirect::back();
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



}
