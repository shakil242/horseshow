<?php
    /**
     * This is Template design Controller to control all the Templates Design in admin project
     *
     * @author Faran Ahmed (Vteams)
     */


namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\TemplateDesign;
use Illuminate\Http\File;

class TemplateDesignController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.mastertemplates.design.create');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($masterid)
    {
        $design_template = TemplateDesign::where('template_id',$masterid)->where('user_id',ADMIN_ID)->first();
        return view('admin.mastertemplates.design.create')->with(compact('masterid','design_template'));
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
        $template_id = $request->get('template_id');
            $this->validate($request, [
                'logo_resolution_width' => "numeric|min:5|max:999",
                'logo_resolution_hight' => "numeric|min:5|max:999",
            ]);

            if($request->get('design_template_id')){ 
                $id = $request->get('design_template_id');
                $model = TemplateDesign::findOrFail($id);
            }else{
                $model = new TemplateDesign();
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
            $disk = getStorageDisk();
            if ($request->file('logo_image')) {
                //--- Remove Previous image
                if($disk->exists($model->logo_image)) {
                    $disk->delete($model->logo_image);
                }
                $imageTitle = UploadAllFiles($folder_id,$request->file('logo_image'),$LG_width,$LG_hight);
                $save = $disk->putFile("admin/template_$template_id/form/design",new File($imageTitle),"public");
                //--- Remove Local Image
                if(\File::exists($imageTitle)){
                    \File::delete($imageTitle);
                }
                $model->logo_image = $save;
            }

            if ($request->file('background_image')) {
                //  if ($model->background_image) {
                //     $image_url = $model->background_image;
                //     File::delete($image_url);
                // }
                //--- Remove Previous image
                if($disk->exists($model->background_image)) {
                    $disk->delete($model->background_image);
                }
                $imageTitle = UploadAllFiles($folder_id,$request->file('background_image'));
                $save = $disk->putFile("admin/template_$template_id/form/design",new File($imageTitle),"public");
                 //--- Remove Local Image
                if(\File::exists($imageTitle)){
                    \File::delete($imageTitle);
                }
                $model->background_image = $save;
            }
            $model->user_id = $user_id;
            $model->save();
            \Session::flash('message', 'Your Template design has been updated');
            if ($request->has('saveClose')) {
                return redirect()->action(
                    'admin\AdminController@edit', ['id' => $request->get('template_id')]
                ); 
            }else{
                return \Redirect::back();
            }
        
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
        //
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
        //
    }

    /**
     * Remove the specified Image from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function background_image_destroy($id)
    {
        $disk = getStorageDisk();
        $model = TemplateDesign::findOrFail($id);
        $image_url = $model->background_image;
        //Local image destory
        //File::delete($image_url);
        //S3 image remove
        if($disk->exists($image_url)) {
            $disk->delete($image_url);
        }

        $model->background_image = null;
        $model->save();
        \Session::flash('message', 'Your Image has been deleted successfully');
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
        $disk = getStorageDisk();
        $model = TemplateDesign::findOrFail($id);
        $image_url = $model->logo_image;
        //File::delete($image_url);
        //S3 image remove
        if($disk->exists($image_url)) {
            $disk->delete($image_url);
        }
        $model->logo_image = null;
        $model->save();
        \Session::flash('message', 'Your Logo Image has been deleted successfully');
        return \Redirect::back();
    }
}
