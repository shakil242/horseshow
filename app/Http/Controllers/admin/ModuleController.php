<?php
    /**
     * This is Module Controller to control all the Modules in admin project
     *
     * @author Faran Ahmed (Vteams)
     */


namespace App\Http\Controllers\admin;

use App\Form;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Module;
use Illuminate\Http\File;

class ModuleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Listing seperate page. But we donot need it now for our project
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($masterid)
    {
        $collection = Module::where('template_id',$masterid)->pluck('name', 'id')->toArray();
        
        $feedBack = Form::where('template_id',$masterid)
                    ->where('form_type',3)
                    ->pluck('name', 'id')
                    ->toArray();

        return view('admin.modules.create')->with(compact('collection','feedBack','masterid'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $disk = getStorageDisk();
        $this->validate($request, [
            'name' => "required",
            'logo' => "image|mimes:jpg,png,JPEG,PNG,jpeg",

        ]);
        if ($request->get('general')) {
            $general = $request->get('general');
        }else{
            $general = 0;
        }
        $folder_id = $request->get('template_id');


        $model = new Module();
        $model->name = $request->get('name');
        $model->general = $general;
        $model->linkto = $request->get('linkto');
        $model->feedback_form_ids = json_encode($request->get('feedback_form_ids'));
        $model->template_id = $request->get('template_id');
        if ($request->file('logo')) {
                    // File Upload Process
                    $file = $request->file('logo');

                        // $destinationPath = public_path('uploads/modules/logo');
                        // $extension = $file->getClientOriginalExtension(); 
                        // $rand = rand(1000000000, 9999999999).'.'.$extension;
                        // $upload_success = $file->move($destinationPath, $rand);
                        // $pathofimage = '/uploads/modules/logo'. '/' . $rand;
                       //S3 file upload
                $imageTitle = UploadAllFiles($folder_id,$request->file('logo'));
                $save = $disk->putFile("admin/template_$folder_id/module",new File($imageTitle),"public");
                 //--- Remove Local Image
                if(\File::exists($imageTitle)){
                    \File::delete($imageTitle);
                }
                $model->logo = $save;
        }
        $model->save();

            return redirect()->action(
                'admin\AdminController@edit', ['id' => $request->get('template_id')]
            );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
    
        $feedBack = Form::where('template_id',$masterid)
            ->where('form_type',3)
            ->pluck('name', 'id')
            ->toArray();
        $selectedFeedback = json_decode($template->feedback_form_ids);
        return view('admin.modules.edit')->with(compact('collection','selectedFeedback','feedBack','template','masterid'));
    
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
        $disk = getStorageDisk();
        $this->validate($request, [
            'name' => "required",
            'logo' => "image|mimes:jpg,png,JPEG,PNG,jpeg",

        ]);
        $template_id = $request->get('template_id');
        if (!$request->get('general')) {
            $general = 0;
        }else{
            $general = 1;
        }
        $folder_id = $request->get('template_id');
        
        $Module = Module::findOrFail($id);
  
        $Module->update($request->all());
        $Module->general = $general;
        $Module->feedback_form_ids = json_encode($request->get('feedback_form_ids'));
    
        $Module->update();
        if ($request->file('logo')) {
            // File Upload Process
            // $file = $request->file('logo');

            // $destinationPath = public_path('uploads/modules/logo');
            // $extension = $file->getClientOriginalExtension(); 
            // $rand = rand(1000000000, 9999999999).'.'.$extension;
            // $upload_success = $file->move($destinationPath, $rand);
            // $pathofimage = '/uploads/modules/logo'. '/' . $rand;

            //S3 file upload
                //--- Remove Previous image
                if($disk->exists($Module->logo)) {
                    $disk->delete($Module->logo);
                }
                $imageTitle = UploadAllFiles($folder_id,$request->file('logo'));
                $save = $disk->putFile("admin/template_$template_id/module",new File($imageTitle),"public");
                 //--- Remove Local Image
                if(\File::exists($imageTitle)){
                    \File::delete($imageTitle);
                }
                $Module->logo = $save;
                $Module->update();
        }
        \Session::flash('message', 'Your Module has been Updated');

        return redirect()->action(
            'admin\AdminController@edit', ['id' => $template_id]
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
