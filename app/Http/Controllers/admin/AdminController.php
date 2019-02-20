<?php
/**
 * Handle All Admin Controller Main requests . and Master templates are manage in these 
 *
 * @author Faran Ahmed Khan
 * @date 12-Jan-2017
 */
namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Template;
use App\Template_associate;
use App\Module;
use App\Form;
use App\Mail\InviteUser;
use App\InvitedUser;
Use App\TemplateButtonLabel;
Use App\TemplateCategory;
use Illuminate\Support\Facades\DB;


class AdminController extends Controller
{
	/**
    * index function, Template list view
    *
    * @param none
    * @return Object of the templates
    */
    public function index()
    {
        $collection  = Template::where('status',GENERAL_ACTIVE)->orderBy('id','desc')->get();
        $regisCollection  = Template::where('status',GENERAL_ACTIVE)->orderBy('is_registration_on','desc')->get();
    	return view("admin.mastertemplates.index")->with(compact('collection','regisCollection'));
    }
    /**
    * Create, MasterTemplate Create 
    *
    * @param none
    * @return model of templates
    */
	public function create(Request $request){
  
        $collection = Template::all()->where('status',GENERAL_ACTIVE)->pluck('name', 'id')->toArray();
		    $tempCatCollection = TemplateCategory::all()->where('status',GENERAL_ACTIVE)->pluck('name', 'id')->toArray();
		
		return view('admin.mastertemplates.create')->with(compact('collection','tempCatCollection'));
	}
	/**
    * Create, MasterTemplate Create 
    *
    * @param none
    * @return model of templates
    */
	public function store(Request $request){
		$this->validate($request, [
            'name' => "required",
            'royalty' => "required",
            'value' => 'required',
        ]);
        
        if(!isset($request->invoice_to_asset))
        {
            $request->invoice_to_asset = '0';
        }
        if(!isset($request->invoice_to_event))
        {
            $request->invoice_to_event = '0';
        }
        
        $model = new Template();
        $model->name = $request->get('name');
        $model->royalty = $request->get('royalty');
        $model->value = $request->get('value');
        $model->invoice_to_event = $request->invoice_to_event;
        $model->invoice_to_asset = $request->invoice_to_asset;
        if($request->get('template_category') != null){
           $model->category=$request->get('template_category');
        }
        if($request->get('cumulative_ranking') != null){
           $model->cumulative_ranking=$request->get('cumulative_ranking');
        }else{
            $model->cumulative_ranking=0;
        }

        if($request->get('blog_type') != null){
           $model->blog_type=$request->get('blog_type');
        }else{
            $model->blog_type=0;
        }

        $model->save();

        $associated = $request->get('associated');
        if(isset($associated)){
            $model->associated_template()->sync($associated);
        }
		if ($request->get('actionafterstore') == "storeandlist") {
			   $collection = Template::orderBy('id','desc')->where('status',GENERAL_ACTIVE)->get();
        $regisCollection  = Template::where('status',GENERAL_ACTIVE)->orderBy('is_registration_on','desc')->get();

        return view("admin.mastertemplates.index")->with(compact('collection','regisCollection'));
		}else{
			$idofedit = $model->id;
			return redirect()->action(
			    'admin\AdminController@edit', ['id' => $idofedit]
			);
		}
	}

	 /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {

        $template = Template::with('associated_template')->where('id', $id)->first();
        $collection = Template::all()->where('status',GENERAL_ACTIVE)->pluck('name', 'id')->toArray();
        $associate_array = array();
         foreach ($template->associated_template as $key => $value) {
         	$associate_array[] =  $value['id'];
         }
        $modules_collection = Module::where('template_id', $id)->get();
        $forms_collection = Form::where('template_id', $id)->with('formtypes','moduleAttached')->get();
        $tempCatCollection = TemplateCategory::all()->where('status',GENERAL_ACTIVE)->pluck('name', 'id')->toArray();
        $regisCollection  = Template::where('status',GENERAL_ACTIVE)->orderBy('is_registration_on','desc')->get();

        return view('admin.mastertemplates.edit')->with(compact('tempCatCollection',"regisCollection",'collection','template','associate_array','modules_collection','forms_collection'));
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        //dd($request->all());
        $Template = Template::findOrFail($id);
        $Template->setCheckBoxValues($request);
        if($request->get('template_category') != null){
           $Template->category=$request->get('template_category');
        }
        if($request->get('cumulative_ranking') != null){
           $Template->cumulative_ranking=$request->get('cumulative_ranking');
        }else{
            $Template->cumulative_ranking=0;
        }
        if($request->get('blog_type') != null){
           $Template->blog_type=$request->get('blog_type');
        }else{
            $Template->blog_type=0;
        }
        
        $Template->update($request->all());
        $associated = $request->get('associated');
        if(isset($associated)){
            $Template->associated_template()->sync($associated);
        }
        
		
        \Session::flash('message', 'Your Master Template has been updated');
    	if ($request->get('actionafterstore') == "storeandlist") {
            $collection = Template::orderBy('id','desc')->where('status',GENERAL_ACTIVE)->get();
        $regisCollection  = Template::where('status',GENERAL_ACTIVE)->orderBy('is_registration_on','desc')->get();
    		
        return view("admin.mastertemplates.index")->with(compact('collection',"regisCollection"));
		}else{
			$idofedit = $id;
			return redirect()->action(
			    'admin\AdminController@edit', ['id' => $idofedit]
			);
		}
    }
    /**
     * Send invite email to the users to master template.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function sendInviteMail(Request $request) {
        $inviter_id = \Auth::user()->id;
        $data = $request->template;
        $sendto = 0;
        foreach ($data as $key => $user) {
            $invited_user = InvitedUser::where('email', '=', $user["email"])->where('template_id',$request->template_id)->first();
            if ($invited_user === null) {
                $invited_user = new InvitedUser();
                $invited_user->name = $user["name"];
                $invited_user->royalty = $user["royalty"];
                $invited_user->email = $user["email"];
                $invited_user->invited_by = $inviter_id;
                $invited_user->template_id = $request->template_id;
                $invited_user->save();
            }
            \Mail::to($user["email"])->send(new InviteUser($invited_user));
            $sendto = $sendto+1;
    
        }
        if (!\Mail::failures()) {
           \Session::flash('message', 'Email has been send to '.$sendto.' User(s) successfully');
                return redirect()->action(
                    'admin\AdminController@index'
                );
        }
        
    }

    /**
     * Rest launcher to default. 0 to be exect.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function restLauncher($id)
    {
        $template = Template::findOrFail($id);
        $template->module_launch_id = null;
        $template->update();
        \Session::flash('message', 'Your launcher has been rest to default view');
        return \Redirect::back();
    }
    /**
     * Change Label for the button of each template id.
     *
     * @param  int  $id = template_id
     * @return \Illuminate\Http\Response
     */
    public function buttonIndex($id)
    {
        $template = Template::findOrFail($id);
        $TBlabel = TemplateButtonLabel::where('template_id',$id)->first();
        $ya_fields = null;
        $ia_fields = null;
        $spec_fields = null;
        $i_p_fields =null;
        $m_s_fields =null;
        if ($TBlabel != null) {
            $ya_fields = json_decode($TBlabel->ya_fields, true);
            $ia_fields = json_decode($TBlabel->ia_fields, true);
            $spec_fields = json_decode($TBlabel->spec_fields, true);
            $m_s_fields = json_decode($TBlabel->m_s_fields, true);
            $i_p_fields = json_decode($TBlabel->i_p_fields, true);
        }

        return view('admin.mastertemplates.button.index')->with(compact('template','TBlabel','ya_fields','spec_fields','ia_fields','m_s_fields','i_p_fields'));
    }
    /**
     * Save Label for the button of each template id.
     *
     * @param  request
     * @return \Illuminate\Http\Response
     */
    public function buttonLabelSave(Request $request)
    {

        $template_id = $request->get("template_id");
        $ya_fields = null;
        $ia_fields = null;
        $spec_fields = null;
        $m_s_fields = null; // Manage scheudler fieds
        $i_p_fields =null;
            //Getting Labels for Your Apps (yap) and converting it to json
            if(is_array($request->get("yap"))){ 
                $yap_fields_inputs = json_encode($request->get("yap")); 
            }else{ 
                $yap_fields_inputs = null; 
            }
            //Getting Labels for Invited Assets (ia) and converting it to json
            if(is_array($request->get("ia"))){ 
                $ia_fields_inputs = json_encode($request->get("ia")); 
            }else{ 
                $ia_fields_inputs = null; 
            }
            //Getting Labels for Spectators (s) and converting it to json
            if(is_array($request->get("spec"))){ 
                $spec_fields_inputs = json_encode($request->get("spec")); 
            }else{ 
                $spec_fields_inputs = null; 
            }

        if(is_array($request->get("m_s"))){
            $m_s_fields_inputs = json_encode($request->get("m_s"));
        }else{
            $m_s_fields_inputs = null;
        }
         if(is_array($request->get("i_p"))){
             $i_p_fields_inputs = json_encode($request->get("i_p"));
         }else{
             $i_p_fields_inputs = null;
         }

        if ($request->get("TBlabel_id") != null) {
            $template = TemplateButtonLabel::findOrFail($request->get("TBlabel_id"));
            $template->ya_fields = $yap_fields_inputs;
            $template->ia_fields = $ia_fields_inputs;
            $template->s_fields = $spec_fields_inputs;
            $template->m_s_fields = $m_s_fields_inputs;
            $template->i_p_fields = $i_p_fields_inputs;

            $template->update();
        }else{
            $template = new TemplateButtonLabel();
            $template->ya_fields = $yap_fields_inputs;
            $template->template_id = $template_id;
            $template->s_fields = $spec_fields_inputs;
            $template->ia_fields = $ia_fields_inputs;
            $template->m_s_fields = $m_s_fields_inputs;
            $template->i_p_fields = $i_p_fields_inputs;

            $template->save(); 
        }
        //Sending back to edit page. Assigning variables below
        $TBlabel = $template;

        if ($TBlabel != null) {
            $ya_fields = json_decode($TBlabel->ya_fields, true);
            $ia_fields = json_decode($TBlabel->ia_fields, true);
            $spec_fields= json_decode($TBlabel->s_fields, true);
            $m_s_fields= json_decode($TBlabel->m_s_fields, true);
            $i_p_fields= json_decode($TBlabel->i_p_fields, true);

        }
        \Session::flash('message', 'The template button labels are saved successfully');
        return view('admin.mastertemplates.button.index')->with(compact('template','TBlabel','ya_fields','spec_fields','ia_fields','m_s_fields','i_p_fields'));
    }
     /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {   
        $template = Template::findOrFail($id);
        //---------> Hard delete
        // DeleteFolderImage($id);
        // $template->delete();
        //---------> Soft delete
        $template->status=GENERAL_DEACTIVE;
        $template->update();
        \Session::flash('message', 'Your Master Template has been deleted successfully');
	 	return redirect()->action(
			    'admin\AdminController@index'
		);
    }

    public function manageRegistration(Request $request)
    {

      $registrationArr =  $request->get('enableRegistration');

     //ArrayPrint($registrationArr);

      if(count($registrationArr)>0) {
          foreach ($registrationArr as $k => $v) {
              $model = Template::findOrFail($k);
              $model->is_registration_on = 1;
              $model->update();
          }
      }else {
          Template::query()->update(['is_registration_on' => 0]);
      }
        \Session::flash('message', 'Registration access has been done successfully');
        return redirect()->action(
            'admin\AdminController@index'
        );


    }


    public function duplicateTemplate(Request $request)
    {

        $template_name = $request->get('template_name');
       $template_id = $request->get('template_id');


        $query1 = "CREATE TEMPORARY TABLE tempTable SELECT * FROM templates where id =".$template_id.";";
        $reu = DB::select($query1);

        $query2 =" UPDATE tempTable SET id = 0,name='".$template_name."'";
        $reu = DB::select($query2);

        $query3 =" INSERT INTO templates SELECT * FROM tempTable ;";
        $reu = DB::select($query3);

         $templates = DB::table('templates')->orderBy('id', 'desc')->first();

        $query4 =" DROP TABLE tempTable ;";
        $reu = DB::select($query4);


        $modules = Module::where('template_id',$template_id)->orderBy('id', 'desc')->get();
        foreach ($modules as $mod){
            $m = new Module();
            $m->name=$mod->name;
            $m->general=$mod->general;
            $m->linkto=$mod->linkto;
            $m->logo=$mod->logo;
            $m->template_id=$templates->id;
            $m->created_at=$mod->created_at;
            $m->updated_at=$mod->updated_at;
            $m->feedback_form_ids=$mod->feedback_form_ids;
            $m->save();
            $modulesArr[]=array('old_id'=>$mod->id,'new_id'=>$m->id);
        }

        $query5 =" CREATE TEMPORARY TABLE tempTable SELECT * FROM forms where template_id =".$template_id;
        $reu = DB::select($query5);

        $query6 =" UPDATE tempTable SET id = 0, template_id=".$templates->id;
        $reu = DB::select($query6);

        $query7 =" INSERT INTO forms SELECT * FROM tempTable";
        $reu = DB::select($query7);

        $query8 =" DROP TABLE tempTable";
        $reu = DB::select($query8);

        foreach ($modulesArr as $modu)
        {
            $forms = Form::where('template_id',$templates->id)->where('linkto',$modu['old_id'])->orderBy('id', 'desc')->first();
            $forms->linkto=$modu['new_id'];
            $forms->save();
        }


        \Session::flash('message', 'Template has been duplicated successfully');
        return redirect()->action(
            'admin\AdminController@index'
        );
    }

}
