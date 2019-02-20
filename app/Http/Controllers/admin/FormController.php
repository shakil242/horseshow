<?php
    /**
     * This is Form Controller to control all the Templates Forms in admin project
     *
     * @author Faran Ahmed (Vteams)
     */
namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Form;
use App\Module;
use App\FormType;
use App\TemplateDesign;
use App\Template;
use Illuminate\Http\File;
use Excel;
use Response;

class FormController extends Controller
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
    public function create($masterid)
    {
       //$design_template = TemplateDesign::where('template_id',$masterid)->first();
        $modules = Module::where('template_id',$masterid)->whereNotIn('id', function($query)use ($masterid){
                        $query->select('linkto')
                            ->from(with(new Form)->getTable())
                            ->where('template_id',$masterid)
                            ->whereNotNull('linkto');
                    })->pluck('name', 'id')->toArray();
        $invoice = Form::where('template_id',$masterid)->where('form_type',4)->pluck('name', 'id')->toArray();


        $PenaltyExist = Form::where('template_id',$masterid)->where('form_type',F_PENALTY)->count();
        $showInvoice = Form::where('template_id',$masterid)->where('form_type',F_SHOW_INVOICE)->count();
        $showExist = Template::where('id',$masterid)->where("category",CONST_SHOW)->count();
        $showSpectator = Form::where('template_id',$masterid)->where('form_type',SPECTATOR_REGISTRATION)->count();
         $horseTemplate = Template::where('id',$masterid)->where("category",CONST_HORSE_TEMPLATE)->count();

        $judgesFeedBack = Form::where('template_id',$masterid)->where('form_type',JUDGES_FEEDBACK)->count();

        $sponsorRegistration = Form::where('template_id',$masterid)->where('form_type',SPONSOR_REGISTRATION)->count();


        $oneTimeExist = [];
        //get the form types
         //If template is show template
        if($horseTemplate==0)
            $oneTimeExist []= RIDER_ASSETS;
        if ($showExist > 0 && $showInvoice < 1)
            $oneTimeExist []= F_SHOW_INVOICE;
        if($PenaltyExist > 0)
            $oneTimeExist []= F_PENALTY;
        if($showSpectator > 0)
            $oneTimeExist []= SPECTATOR_REGISTRATION;
        if($judgesFeedBack > 0)
            $oneTimeExist []= JUDGES_FEEDBACK;
        if($sponsorRegistration > 0)
            $oneTimeExist []= SPONSOR_REGISTRATION;


        $FormTypes =FormType::whereNotIn('id',$oneTimeExist)->pluck('name', 'id')->toArray();

        return view('admin.mastertemplates.forms.create')->with(compact('masterid','modules','invoice','FormTypes'));
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
            'form_type' => "required|not_in:0",
        ],['form_type.not_in' => 'The Form Type field is required.']);
        if ($request->get('form_type')== DATAINPUT) {
            $this->validate($request, [
                'linkto' => "required|not_in:0",
            ],['linkto.not_in' => 'The Link To field is required.']);
        }
        if ($request->get('scheduler')) {
            $scheduler = $request->get('scheduler');
        }else{
            $scheduler = 0;
        }
        $model = new Form();
        $model->template_id = $request->get('template_id');
        $model->name = $request->get('name');


        if ($request->get('form_type') == PROFILE_ASSETS || $request->get('form_type') == FEEDBACK) {
            $model->accessable_to = $request->get('accessable_to');
        }elseif($request->get('form_type') == JUDGES_FEEDBACK)
        {
            $model->accessable_to = 1;
        }
        else{
            $model->linkto = $request->get('linkto');
            $model->invoice = $request->get('invoice');
        }
        if ($request->get('feedback_type')){
            $model->feedback_type = $request->get('feedback_type');  
        }
        $model->form_type = $request->get('form_type');
        $model->scheduler = $scheduler;
        $model->save();

        \Session::flash('message', 'Your Form has been Created successfully. Please add fields in form');
        

            return redirect()->action(
                'admin\FormController@edit', ['id' => $model->id]
            );

    }

    /**
     * Display the form in form viewer.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $id = nxb_decode($id);
        $formid= $id;
        $FormTemplate = Form::where('id', $id)->first();
        $TemplateDesign = TemplateDesign::where('template_id', $FormTemplate->template_id)->first(); 
        //MasterTemplate Design Variable  -->
        $TD_variables = getTemplateDesign($TemplateDesign);
        $pre_fields = json_decode($FormTemplate->fields, true);
        // END: MasterTemplate Design Variable  -->
        return view('admin.mastertemplates.forms.view')->with(compact('FormTemplate','TD_variables','pre_fields','id','formid'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $formid= $id;
        $form = Form::where('id', $id)->first();
        $pre_fields = json_decode($form->fields, true);
        //\DB::enableQueryLog();
        $masterid =$form->template_id;
        $modules = Module::where('template_id',$masterid)->whereNotIn('id', function($query)use ($masterid,$id){
                        $query->select('linkto')
                            ->from(with(new Form)->getTable())
                            ->where('template_id',$masterid)
                            ->where('id','!=', $id)
                            ->where('linkto','!=', 0)
                            ->where('linkto','!=', null);
                    })->pluck('name', 'id')->toArray();
         //dd(\DB::getQueryLog());
         $invoice =Form::where('template_id',$masterid)->where('form_type',4)->pluck('name', 'id')->toArray();
    
         $FormTypes =FormType::pluck('name', 'id')->toArray();
        
        return view('admin.mastertemplates.forms.edit')->with(compact('masterid','FormTypes','modules','form','invoice','pre_fields','formid'));
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
        //dd($request->all());
        $template_id = $request->get('template_id');
        $this->validate($request, [
            'name' => "required",
            'form_type' => "required|not_in:0",
        ],['form_type.not_in' => 'The Form Type field is required.']);
        if ($request->get('form_type')== DATAINPUT) {
            $this->validate($request, [
                'linkto' => "required|not_in:0",
            ],['linkto.not_in' => 'The Link To field is required.']);
        }
        if ($request->get('scheduler')) {
            $scheduler = $request->get('scheduler');
        }else{
            $scheduler ='0';
        }
        $fieldsarray = $request->get('fields');
        $form = Form::findOrFail($id);
        $form->template_id = $request->get('template_id');
        $form->name = $request->get('name');
         if ($request->get('form_type') == PROFILE_ASSETS || $request->get('form_type') == FEEDBACK) {
            $form->accessable_to = $request->get('accessable_to');
        }
         elseif($request->get('form_type') == JUDGES_FEEDBACK)
         {
             $form->accessable_to = 1;
         }
        else{
            $form->linkto = $request->get('linkto');
            $form->invoice = $request->get('invoice');
        }
        if ($request->get('feedback_type')){
            $form->feedback_type = $request->get('feedback_type');  
        }else{
            $form->feedback_type =0;
        }
        $form->form_type = $request->get('form_type');
        $form->scheduler = $scheduler;
        
        //Upload images.
        $disk = getStorageDisk();
        if (isset($request->fields)) {
           foreach ($request->fields as $key=>$field) {

                if(($field["form_field_type"] == OPTION_IMAGE || 
                    $field["form_field_type"] ==OPTION_VIDEO || 
                    $field["form_field_type"] == OPTION_ATTACHMENT) && 
                    isset($field["form_field_options"][1]) ){
                    if (isset($field["form_field_options"][1]["upload_files"])) {
                        $imageTitle = UploadFormFiles($form->template_id,$field["form_field_options"][1]["upload_files"],$id);
                        $oldimage = $field["form_field_options"][1]["old_upload_files"];
                        $save = $disk->putFile("admin/template_$template_id/form/$id",new File($imageTitle),"public");
                        //--- Remove Local Image
                        if(\File::exists($imageTitle)){
                            \File::delete($imageTitle);
                        }
                        //--- Remove Previous image
                        if($disk->exists($oldimage)) {
                            $disk->delete($oldimage);
                        }
                        
                    }else{
                        $save = $field["form_field_options"][1]["old_upload_files"];
                    }
                    // ---- Assigning URL to the image for json encode.
                    $fieldsarray[$key]["form_field_options"][1]["upload_files"] = $save; 
                }
            }
        }
        if(is_array($fieldsarray)){ 
            $fields_inputs = json_encode(array_values($fieldsarray)); 
        }else{ 
            $fields_inputs = null; 
        }
        $form->fields = $fields_inputs;

      //  dd($form->toArray());

        $form->save();

        if ($request->has("updatepreview")) {
            return redirect()->route('admin-preview-form', ['id' => nxb_encode($id)]);
        }else{
            \Session::flash('message', 'Your Form has been updated successfully');
            return redirect()->action(
                'admin\FormController@edit', ['id' => $id]
            ); 
        }
       
    }

     /**
     * Display the array in which all the inputs are saved.
     *
     * @param  int  $Request
     * @return dd(All the variables send via form);
     */
    public function getSavedValue(Request $request){
        dd($request->fields);
        $files = $request->fields;
        foreach($files as $key => $file) {
            if (isset($file["images"])) {
                # code...
            }
        }
    }

    /**
     * Make a copy feature for the Forms.
     *
     * @param  int  $id
     * @return New form copied;
     */
    public function copyForm($id){
        $id = nxb_decode($id);
        $form = Form::findOrFail($id);
        $template_id = $form->template_id;
        
        //New form creation database
        $new_form = $form->replicate();
        $new_form->name = "Copy of ".$new_form->name;
        $new_form->linkto = 0;
        $template_id = $new_form->template_id;
        //Save form
        $success = $new_form->save();
        //AWS storage media
        $disk = getStorageDisk();
        $directoryIteams = $disk->allFiles("admin/template_$template_id/form/$id");
        if ($directoryIteams) {
            $field_replacer = $new_form->fields;
            foreach($directoryIteams as $item) {
                $new_loc = str_replace("admin/template_$template_id/form/$id", "admin/template_$template_id/form/$new_form->id", $item);
                $success = $disk->copy($item, $new_loc);
            }
            $field_replacer = str_replace("admin\/template_$template_id\/form\/$id", "admin\/template_$template_id\/form\/$new_form->id", $field_replacer);
            //Save form
            $form = Form::findOrFail($new_form->id);
            $form->fields = $field_replacer;
            $form->update();
        }
        
        //Local folder copy and storage
        //$OldDirectory = public_path().'/'.PATH_UPLOAD_FORMS."master_temp_$template_id/form_$id/";
        //$NewDirectory = public_path().'/'.PATH_UPLOAD_FORMS."master_temp_$template_id/form_$new_form->id/";
        //$success =xcopy($OldDirectory, $NewDirectory);

        if($success){
            \Session::flash('message', 'Your Form has been Copied successfully');
        }else{
            \Session::flash('message', 'Your Form has been Copied But there is some error in saving the image files');
        }
        
        return redirect()->action(
                'admin\AdminController@edit', ['id' =>$template_id]
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
        $disk = getStorageDisk();
        $form = Form::findOrFail($id);
        $template_id = $form->template_id;
        //Local delete
        //DeleteFormFolderImage($folder_id,$id);
        //S3 delete
        $disk->deleteDirectory("admin/template_$template_id/form/$id");
        $form->delete();
        \Session::flash('message', 'Your Form has been deleted successfully');
        return \Redirect::back();
    }


        /**
     * Upload the excel file while adding options.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function optionExcel(Request $request)
    {

        ini_set('max_execution_time', 600);
        ini_set('memory_limit','512M');
        ini_set('memory_limit','512M');

        $data = $request->all();
       $filePath = $data[0]->getRealPath();

      $excelData = array();
        try{
            if($filePath){
                //$path = Input::file('import_file')->getRealPath();
                 $Uploaded_file = Excel::load($filePath, function($reader) { })->get();
                 //dd($Uploaded_file);
                if(!empty($Uploaded_file) && $Uploaded_file->count()){
                    foreach ($Uploaded_file as $key => $value) {
                        if ($value->options != null) {
                            $excelData[] = ['options' => $value->options, 'weight' => $value->weight];
                        }
                    }
                }
            }

            $status = 1;
            $message = "excel file data Imported";  
        }
        catch(Exception $e){            
            $error = $e->getMessage();            
            $status = 0;            
            $message = "Oops something went wrong";        
        }

         $response = array(
                'status' => $status,
                'msg' => $message,
                'dataExcel' => $excelData,
            );

        return Response::json($response);
    }



   
}
