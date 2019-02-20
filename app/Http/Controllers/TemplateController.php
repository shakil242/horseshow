<?php
    /**
     * This is Module Controller For frontend to control all the Modules in project
     *
     * @author Faran Ahmed (Vteams)
     * @date 17-Feb-2017
     */


namespace App\Http\Controllers;

use App\Asset;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Module;
use App\Form;
use App\TemplateDesign;
use App\Template;
use App\InvitedUser;
use App\inviteTemplatename;
use App\InviteTemplateTransfer;
use App\User;
use App\Mail\TransferAppEmail;
use App\InvitedUsersReponseDraft;
use App\CourseContent;

class TemplateController extends Controller
{
    /**
     * Display a listing of the modules in view master template.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($template_id)
    {
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
        $template_id = nxb_decode($template_id);
        $collection = Module::where('template_id',$template_id)->where('linkto',0)->with(['moduleLogo'=>function($q) use($user_id){
            $q->where('user_id',$user_id);
        }])->get()->toArray();

        $generalCollection = Module::where('template_id',$template_id)->where('general',1)->get()->toArray();
        $breadcrumbsRoute = "master-template";

        $dataBreadcrum = $template_id;
        $MT_name = Template::where("id",$template_id)->first()->toArray();
        return view('MasterTemplate.modules.listing')->with(compact('user_id','MT_name','breadcrumbsRoute','dataBreadcrum','collection','template_id','generalCollection'));
    }
     /**
     * Display a listing of the modules for module launcher.
     *
     * @return \Illuminate\Http\Response
     */
    public function module_launcher($template_id,$app_id=null)
    {
        $user_id = \Auth::user()->id;
        
        $template_id = nxb_decode($template_id);
        $app_id = nxb_decode($app_id);

        $collection = Module::where('template_id',$template_id)->where('linkto',0)->with(['moduleLogo'=>function($q) use($user_id){
            $q->where('user_id',$user_id);
        }])->get()->toArray();
        $generalCollection = Module::where('template_id',$template_id)->where('general',1)->get()->toArray();
        $breadcrumbsRoute = "master-template";
        $breadcrumbsParent = "Dashboard";
        $dataBreadcrum = $template_id;
        $MT_name = Template::where("id",$template_id)->first()->toArray();
        if($MT_name["module_launch_id"] != null && $MT_name["module_launch_id"] != 0){
            $module =Module::select("id")->where('template_id',$template_id)->where('id',$MT_name["module_launch_id"])->first();
            return $this->show(nxb_encode($template_id),nxb_encode($module->id),nxb_encode($app_id));
        }
        $breadcrumbsRoute = "launch-master-template";
        $dataBreadcrum = [
            'template_id' => $template_id,
            'app_id' => $app_id,
        ];
        return view('MasterTemplate.modules.listing')->with(compact('user_id','MT_name','breadcrumbsRoute','dataBreadcrum','collection','template_id','generalCollection','app_id'));
    }
 /**
     * Display a listing of the modules for module launcher.
     *
     * @return \Illuminate\Http\Response
     */
    public function selfInvite($template_id)
    {

        invitedBySelf($template_id);
        \Session::flash('message', 'You have a new app.');
                return redirect()->action(
                    'UserController@index'
                );
    }


    public function joinTrainerAppBYSelf($template_id,$show_id)
    {
      invitedBySelf($template_id);

          return redirect()->route('ShowController-add-trainers', ['id' => $show_id]);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($template_id,$moduleid,$app_id=null)
    {


        $template_id = nxb_decode($template_id);
        $app_id = nxb_decode($app_id);


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

        $moduleid = nxb_decode($moduleid);
        $FormTemplate = Form::where('template_id',$template_id)->where('linkto',$moduleid)->first();
        
        $assets = Asset::where('template_id',$template_id)->where('user_id',$user_id)->where("asset_type",ASSET_TYPE_CHILD)->get();

        if ($FormTemplate) {
            
            $TemplateDesign = getTemplateDesignQry($template_id,$user_id);
            //MasterTemplate Design Variable  -->
            $TD_variables = getTemplateDesign($TemplateDesign);
            $pre_fields = json_decode($FormTemplate->fields, true);
            // END: MasterTemplate Design Variable  -->
            $draft_id = 0;
            $answer_fields = null;
            $formid = $FormTemplate->id;
            $draft = InvitedUsersReponseDraft::where('user_id',$user_id)->where('form_id',$formid)->first();
            if ($draft != null) {
                $answer_fields = json_decode($draft->fields, true);
                $draft_id = $draft->id;
            }
            $dataBreadcrum = [
                            'template_id' => $template_id,
                            'module_id' => $moduleid,
                            'app_id' => $app_id,

                    ];

            return view('MasterTemplate.form.view')->with(compact('user_id','assets','FormTemplate','TD_variables','template_id','pre_fields','formid','answer_fields','draft_id','dataBreadcrum'));
        }
        $collection = Module::where('template_id',$template_id)->where('linkto',$moduleid)->with(['moduleLogo'=>function($q) use($user_id){
            $q->where('user_id',$user_id);
        }])->get()->toArray();
        $generalCollection = Module::where('template_id',$template_id)->where('general',1)->get()->toArray();
        $breadcrumbsRoute = "mastertemp-id-submodule";
        $dataBreadcrum = [
                'template_id' => $template_id,
                'module_id' => $moduleid,
                'app_id' => $app_id,

        ];

        $MT_name = Template::select('name')->where("id",$template_id)->first()->toArray();

        return view('MasterTemplate.modules.listing')->with(compact('user_id','MT_name','breadcrumbsRoute','dataBreadcrum','collection', 'moduleid', 'template_id','generalCollection','app_id','isMultiLevel'));
    
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
     *
     * @return \Illuminate\Http\Response
     */
    public function create($masterid)
    {
        $collection = Module::where('template_id',$masterid)->pluck('name', 'id')->toArray();
        return view('admin.modules.create')->with(compact('collection','masterid'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => "required",
            'logo' => "image|mimes:jpg,png,JPEG,PNG,jpeg",

        ]);
        if ($request->get('general')) {
            $general = $request->get('general');
        }else{
            $general = 0;
        }

        $model = new Module();
        $model->name = $request->get('name');
        $model->general = $general;
        $model->linkto = $request->get('linkto');
        $model->template_id = $request->get('template_id');
        if ($request->file('logo')) {
                    // File Upload Process
                    $file = $request->file('logo');

                        $destinationPath = public_path('uploads/modules/logo');
                        $extension = $file->getClientOriginalExtension(); 
                        $rand = rand(1000000000, 9999999999).'.'.$extension;
                        $upload_success = $file->move($destinationPath, $rand);
                        $pathofimage = '/uploads/modules/logo'. '/' . $rand;
                        //\URL::to('/').
                        $model->logo = $pathofimage;
        }
        $model->save();

            return redirect()->action(
                'admin\AdminController@edit', ['id' => $request->get('template_id')]
            );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $template = Module::where('id', $id)->first();
        $masterid =$template->template_id;
        $collection = Module::where('template_id',$masterid)->pluck('name', 'id')->toArray();
        return view('admin.modules.edit')->with(compact('collection','template','masterid'));
    
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
        $this->validate($request, [
            'name' => "required",
            'logo' => "image|mimes:jpg,png,JPEG,PNG,jpeg",

        ]);
        $master_template = $request->get('template_id');
        if (!$request->get('general')) {
            $general = 0;
        }else{
            $general = 1;
        }
        $Module = Module::findOrFail($id);
        $Module->update($request->all());
        $Module->general = $general;
        $Module->update();
        if ($request->file('logo')) {
            // File Upload Process
            $file = $request->file('logo');

            $destinationPath = public_path('uploads/modules/logo');
            $extension = $file->getClientOriginalExtension(); 
            $rand = rand(1000000000, 9999999999).'.'.$extension;
            $upload_success = $file->move($destinationPath, $rand);
            $pathofimage = '/uploads/modules/logo'. '/' . $rand;
            //\URL::to('/').
            $Module->logo = $pathofimage;
            $Module->update();
        }
        \Session::flash('message', 'Your Module has been Updated');

        return redirect()->action(
            'admin\AdminController@edit', ['id' => $master_template]
        );

    }
    /**
     * Setting for the spacific app.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function settings($invited_id)
    {
        $user_id = \Auth::user()->id;
        $admin_design= false;
        $premitedToChange = 0;
        $invited_id= nxb_decode($invited_id);
        $apps = inviteTemplatename::where('invited_user_id',$invited_id)->first();
        
        if ($apps == null) {
            $apps = InvitedUser::where('id',$invited_id)->with('template')->first();
            $name = $apps->template->name;
            $template_id = $apps->template_id;
            $invite_templatenames_id = null;
            $invited_user_id = $apps->id;

        }else{
            $name = $apps->name;
            $template_id = $apps->template_id;
            $invite_templatenames_id = $apps->id;
            $invited_user_id = $apps->invited_user_id;
        }
        //Design template
        $design_template = TemplateDesign::where('template_id',$template_id)->where('user_id',$user_id)->first();
        if (!isset($design_template)) {
            $design_template = TemplateDesign::where('template_id',$template_id)->where('user_id',ADMIN_ID)->first();
            (($design_template) ?  $premitedToChange = $design_template->customizable_app_user :  $premitedToChange = 0);
            $admin_design= true;
        }else{
            $premitedToChange = 1;
        }
        //Check if transfered request put.
        $transferedReq = InviteTemplateTransfer::where('template_id',$template_id)->where('sender_id', $user_id)->where('status',0)->first();
        $transferedHistory = InviteTemplateTransfer::where('invited_id',$invited_id)->get();
        return view('users.edit')->with(compact('user_id','invited_id','transferedHistory','premitedToChange','transferedReq','design_template','admin_design','invited_user_id','apps','name','template_id','invite_templatenames_id'));
    }
    /**
     * Transfering spacific app.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function tranferRequest(Request $request)
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


        $users = User::where('id',$user_id)->first();
        $this->validate($request, [
            'transferemail' => "required|email",
        ]);
        $transferemail = $request->get('transferemail');
        
        //Check If user is already registered on our site.
        $userexists = User::where('email',$transferemail)->first();
        //Save for history in database 
        $model = new InviteTemplateTransfer();
        $model->template_id = $request->get('template_id');
        $model->sender_id = $user_id;
        $model->invited_id = $request->get('invited_id');
        if ($userexists->count()) {
            $model->reciver_id = $userexists->id;
        }else{
            $model->reciver_id = 0;

        }
        $model->notes = $request->get('notes');
        $model->invite_email = $transferemail;
        $model->employee_id = $employee_id;
        $model->save();
        //Send email
        if($transferemail) {
           \Mail::to($transferemail)->send(new TransferAppEmail($users,$model));
        }
        \Session::flash('message', "We have send the request for transfer of App to $transferemail");

        return redirect()->action(
            'TemplateController@settings', ['id' => nxb_encode($request->get("invited_user_id"))]
        );
    }
    /**
     * Change name for the user app.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $request
     * @return \Illuminate\Http\Response
     */
    public function editName(Request $request)
    {
        $this->validate($request, [
            'template_name' => "required",
        ]);
        //Check if value already exists
        $invite_templatenames_id = $request->get('invite_templatenames_id');


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


        //Add new entry
        if($invite_templatenames_id == null){
            $model = new inviteTemplatename();
            $model->name = $request->get('template_name');
            $model->template_id = $request->get('template_id');
            $model->invited_user_id = $request->get('invited_user_id');
            $model->user_id = $user_id;
            $model->employee_id = $employee_id;

            $model->save();
        }//Update old entry
        else{
            $model = inviteTemplatename::where('id',$invite_templatenames_id)->first();
            $model->name = $request->get('template_name');
            $model->template_id = $request->get('template_id');
            $model->invited_user_id = $request->get('invited_user_id');
            $model->user_id = $user_id;
            $model->employee_id = $employee_id;

            $model->update();
        }
       
        \Session::flash('message', 'Your Master Template Name has been Updated');

        return redirect()->action(
            'TemplateController@settings', ['id' => nxb_encode($model->invited_user_id)]
        );
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeDesign(Request $request)
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

            $this->validate($request, [
                'logo_resolution_width' => "numeric|min:5|max:999",
                'logo_resolution_hight' => "numeric|min:5|max:999",
            ]);
            
            if($request->get('admin_design')){
                $model = new TemplateDesign();
            }else{
                if($request->get('design_template_id')){ 
                    $id = $request->get('design_template_id');
                    $model = TemplateDesign::findOrFail($id);
                }else{
                    $model = new TemplateDesign();
                }
            }
            
            $folder_id = $request->get('template_id');
            $LG_width = empty($request->get('logo_resolution_width')) ? 175 : $request->get('logo_resolution_width');
            $LG_hight = empty($request->get('logo_resolution_hight')) ? 100 : $request->get('logo_resolution_hight');
            
            //insert
            $model->template_id = $request->get('template_id');
            $model->logo_resolution_width = $LG_width;
            $model->logo_resolution_hight = $LG_hight;
            $model->logo_position = $request->get('logo_position');
            $model->logo_allignment = $request->get('logo_allignment');
            $model->background_color = empty($request->get('background_color')) ? null : $request->get('background_color');
            $model->background_image_repeat = $request->get('background_image_repeat');
            $model->title_font_size = $request->get('title_font_size');
            $model->title_font_color = empty($request->get('title_font_color')) ? null : $request->get('title_font_color');
            $model->title_font_allignment = $request->get('title_font_allignment');
            $model->field_font_size = $request->get('field_font_size');
            $model->field_font_color = empty($request->get('field_font_color')) ? null : $request->get('field_font_color');
            $model->options_font_size = $request->get('options_font_size');
            $model->options_font_color = empty($request->get('options_font_color')) ? null : $request->get('options_font_color');
            $model->customizable_app_user = $request->get('customizable_app_user');
            
            if ($request->file('logo_image')) {
                 if ($model->logo_image) {
                    $image_url = $model->logo_image;
                    \File::delete($image_url);
                }
                $model->logo_image = UploadAllFiles($folder_id,$request->file('logo_image'),$LG_width,$LG_hight);
            }

            if ($request->file('background_image')) {
                 if ($model->background_image) {
                    $image_url = $model->background_image;
                    \File::delete($image_url);
                }
                $model->background_image = UploadAllFiles($folder_id,$request->file('background_image'));
            }
            $model->user_id = $user_id;
             $model->employee_id = $employee_id;

            $model->save();

            $invited_user_id = $request->get('invited_user_id');
            return redirect()->action(
                'TemplateController@settings', ['id' => nxb_encode($invited_user_id)]
            );
        
    }
       /**
     * Remove the specified Image from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function background_image_destroy($id)
    {
        $user_id = \Auth::user()->id;
        $id = nxb_decode($id);
        $model = TemplateDesign::findOrFail($id);
        if($user_id == $model->user_id){
            $image_url = $model->background_image;
            \File::delete($image_url);
            $model->background_image = null;
            $model->save();
            \Session::flash('message', 'Your Image has been deleted successfully');
        }else{
            \Session::flash('message', 'You donot have permissions to delete this!'); 
        }
        
        return \Redirect::back();
    }
    /**
     * Remove the specified logo Image from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function logo_image_destroy($id)
    {
        $user_id = \Auth::user()->id;
        $id = nxb_decode($id);
        $model = TemplateDesign::findOrFail($id);
        if($user_id == $model->user_id){
            $image_url = $model->logo_image;
            \File::delete($image_url);
            $model->logo_image = null;
            $model->save();
            \Session::flash('message', 'Your Logo Image has been deleted successfully');
        }else{
            \Session::flash('message', 'You donot have permissions to delete this!');
        }
        return \Redirect::back();
    }
    /**
     * course outline.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function couserOutline($form_id,$type=0)
    {
        $user_id   = \Auth::user()->id;
        $form_id = nxb_decode($form_id);
        $FormTemplate = Form::where('id',$form_id)->first();
        $answer_fields = null;
        $coursecontent_id = "";

        if ($FormTemplate) {
            $profileResponse = null;
            if (is_numeric($type)) {
                $profileResponse = CourseContent::where('form_id',$form_id)->where('owner_id',$user_id)->first();
            }else{
                $invite_id =  nxb_decode($type);
                $profileResponse = CourseContent::where('form_id',$form_id)->where('owner_id',$invite_id)->first();
            }
            
            if ($profileResponse) {
                $coursecontent_id = $profileResponse->id;
                $answer_fields = json_decode($profileResponse->fields, true);
            }
            $formid = $FormTemplate->id;
            $template_id = $FormTemplate->template_id;
            $TemplateDesign = TemplateDesign::where('template_id', $template_id)->first(); 
                //MasterTemplate Design Variable  -->
                $TD_variables = getTemplateDesign($TemplateDesign);
                $pre_fields = json_decode($FormTemplate->fields, true);
                // END: MasterTemplate Design Variable  -->
                if(is_numeric($type)) {
                    return view('MasterTemplate.users.formView')->with(compact('FormTemplate','TD_variables','pre_fields','template_id','formid','answer_fields','coursecontent_id'));
                }else{
                    return view('MasterTemplate.participants.courseView')->with(compact('FormTemplate','TD_variables','pre_fields','template_id','formid','answer_fields','coursecontent_id'));
                }
        }
        
       \Session::flash('message', 'There is no course added!');
        return \Redirect::back();
    }

    /**
     * Save the record for course contents.
     *
     * @return \Illuminate\Http\Response
     */
    public function saveCourseContent(Request $request)
    {
        $user_id = \Auth::user()->id;
        $template_id = $request->template_id;
        $form_id = $request->form_id;
        $profileId =$request->coursecontent_id;
        if (isset($profileId) && $profileId!="") {
            $model = CourseContent::find($profileId);
            $model->template_id = $template_id;
            $model->form_id = $form_id;
            $model->owner_id = $user_id;
            $model->fields = submitFormFields($request);
            $model->update();
            \Session::flash('message', 'Course Contents has been updated successfully');
        }else{
            $model = new CourseContent();
            $model->template_id = $template_id;
            $model->form_id = $form_id;
            $model->owner_id = $user_id;
            $model->fields = submitFormFields($request);
            $model->save();
            \Session::flash('message', 'Course Contents has been stored successfully');
        }
            
        return redirect()->action(
            'UserController@index'
        );


    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $Module = Module::findOrFail($id);
        $Module->delete();
        \Session::flash('message', 'Your Module has been deleted successfully');
        
        return \Redirect::back();
    }
}
