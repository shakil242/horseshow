<?php
    /**
     * This is Form Controller to control all the Templates Forms in frontend project
     *
     * @author Faran Ahmed (Vteams)
     */
namespace App\Http\Controllers;
use Validator;
use App\AppModules;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Form;
use App\Module;
use App\FormType;
use App\TemplateDesign;
use App\Asset;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;


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
        $template_id = $request->template_id;
        $form_id = $request->form_id;
        $form_type = Form::select("form_type")->where('id', $form_id)->first()->toArray();
        $fieldsarray = $request->fields;
        if($form_type['form_type'] == F_ASSETS){
            $model = new Asset();
            $model->template_id = $template_id;
            $model->form_id = $form_id;
            $model->user_id = $user_id;
            //Assigning the fields in json form
            $model->fields = submitFormFields($request);
            $model->save();
            return redirect()->route('master-template-manage-assets', ['template_id' => nxb_encode($template_id)]);

        }else{
           dd("else");
        }
        $files = $request->fields;
        foreach($files as $key => $file) {
           
            if (isset($file["images"])) {
                # code...
            }
        }
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
        //dd($pre_fields);
        $masterid =$form->template_id;
        $modules = Module::where('template_id',$masterid)->whereNotIn('id', function($query)use ($masterid,$id){
                        $query->select('linkto')
                            ->from(with(new Form)->getTable())
                            ->where('template_id',$masterid)
                            ->where('id','!=', $id);
                    })->pluck('name', 'id')->toArray();
         $invoice =Form::where('template_id',$masterid)->where('form_type',4)->pluck('name', 'id')->toArray();
        $FormTypes = FormType::pluck('name', 'id')->toArray();
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
    }

     /**
     * Display the array in which all the inputs are saved.
     *
     * @param  int  $Request
     * @return dd(All the variables send via form);
     */
    public function getSavedValue(Request $request){
        dd($request);
        $files = $request->fields;
        foreach($files as $key => $file) {
           
            if (isset($file["images"])) {
                # code...
            }
        }
    }


    public function updateModuleLogo(Request $request)
    {

        $disk = Storage::disk('s3');

        $v = Validator::make($request->all(), [
            'userOrignalImage' => 'max:2605',
        ]);

        if ($v->fails())
        {

            return redirect()->back()->withErrors($v->errors());
        }

         $cropp = $request->userCroppedImage;

        $user_id   = \Auth::user()->id;
        $fileName   = nxb_encode($user_id);

       // echo $cropp.'>>>';exit;

        if($cropp!='') {
            $cropped = explode(';', $cropp);

            list(, $cropped) = explode(',', $cropped['1']);

            $cropped = base64_decode($cropped);

            $croppedPath = 'images/temp/cropped/' . $fileName . '.png';
            $originalPath = 'images/temp/original';
        }

        $model = AppModules::where('user_id',$user_id)->where('module_id',$request->module_id)->first();
        if(!$model) {
            $model = new AppModules();
        }

        $model->name = $request->name;
        $model->template_id = $request->template_id;
        $model->app_id = $request->app_id;
        $model->module_id = $request->module_id;
        $model->user_id = $user_id;

        if(isset($cropped)) {
            file_put_contents($croppedPath, $cropped);

            $cropped = $disk->putFile("profilePicture/cropped/$user_id", new File($croppedPath), "public");

            $model->logo = $cropped;
            if(\File::exists($croppedPath)){
                \File::delete($croppedPath);
            }

        }

        if($request->userOrignalImage!='') {
            $imageName = $fileName . '.' . $request->userOrignalImage->getClientOriginalExtension();
            $request->userOrignalImage->move(public_path($originalPath), $imageName);
            $original = $disk->putFile("profilePicture/original/$user_id",new File($originalPath.'/'.$imageName),"public");

            $model->orignal_logo = $original;
            $model->logo = $original;


            if(\File::exists($originalPath.'/'.$imageName)){
                \File::delete($originalPath.'/'.$imageName);
            }

        }

        $model->save();

        return redirect()->back();

    }


    /**
     * Make a copy feature for the Forms.
     *
     * @param  int  $id
     * @return New form copied;
     */
    public function copyForm($id){

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
}
