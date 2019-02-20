<?php
    /**
     * This is Setting Controller which contain all the setting related functions of the project
     *
     * @author Faran Ahmed (Vteams)
     */
namespace App\Http\Controllers;

use Validator;
use App\Spectators;
//use Aws\Api\Validator;
use Illuminate\Http\Request;
use App\User;
use App\InvitedUser;
use App\Participant;
use App\ProfileResponse;
use App\Template;
use Excel;
use Illuminate\Http\File;
use App\Mail\InviteUser;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use App\TemplateButtonLabel;
use App\Form;
use App\TemplateDesign;
use Illuminate\Contracts\Validation\ValidationException;



class SettingController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {


       // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_id   = \Auth::user()->id;
        $useremail = \Auth::user()->email;

        $collection = InvitedUser::where('email',$useremail)->get();
        
        $participant_collection = Participant::where('email',$useremail)->groupBy('template_id')->get();
        //dd($participant_collection->toArray());
        return view('setting.index')->with(compact('user_id',"collection","participant_collection"));
    }

    /**
     * View the Form for the settings.
     * @param form id, Accessable to. (1 or 2)
     * @return \Illuminate\Http\Response
     */
    public function view($form_id,$accessable_to)
    {
        $user_id   = \Auth::user()->id;
        $form_id = nxb_decode($form_id);
        $FormTemplate = Form::where('id',$form_id)->first();
        $answer_fields = null;
        $profile_response_id = "";

        if ($FormTemplate) {
            $profileResponse = ProfileResponse::where('form_id',$form_id)->where('user_id',$user_id)->first();
            if ($profileResponse) {
                $profile_response_id = $profileResponse->id;
                $answer_fields = json_decode($profileResponse->fields, true);
            }
            $formid = $FormTemplate->id;
            $template_id = $FormTemplate->template_id;
            $TemplateDesign = TemplateDesign::where('template_id', $template_id)->first(); 
                //MasterTemplate Design Variable  -->
                $TD_variables = getTemplateDesign($TemplateDesign);
                $pre_fields = json_decode($FormTemplate->fields, true);
                // END: MasterTemplate Design Variable  -->
                return view('setting.formView')->with(compact('FormTemplate','TD_variables','pre_fields','template_id','accessable_to','formid','answer_fields','profile_response_id'));
        }
        
       \Session::flash('message', 'There is no profile form attached with this master template yet!');
        return \Redirect::back();
    }

    /**
     * View Profile for the User.
     * @param form id, Accessable to. (1 or 2)
     * @return \Illuminate\Http\Response
     */
    public function viewProfile($form_id,$accessable_to,$user_id)
    {
        //$user_id   = nxb_decode($user_id);

        if ($user_id == "NoValue") {
            $user_id =\Auth::user()->id;
        }
         $form_id = nxb_decode($form_id);
        
        $FormTemplate = Form::where('id',$form_id)->first();
        $answer_fields = null;
        $profile_response_id = "";
        
        
        if ($FormTemplate) {
            $profileResponse = ProfileResponse::where('form_id',$form_id)->where('user_id',$user_id)->first();
            if (count($profileResponse)>0) {
                $profile_response_id = $profileResponse->id;
                $answer_fields = json_decode($profileResponse->fields, true);
            }
            // else{
            //     \Session::flash('message', 'There is no data added to this profile from the owner!');
            //     return \Redirect::back();
            // }
            $formid = $FormTemplate->id;
            $template_id = $FormTemplate->template_id;
            $TemplateDesign = TemplateDesign::where('template_id', $template_id)->first(); 
                //MasterTemplate Design Variable  -->
                $TD_variables = getTemplateDesign($TemplateDesign);
                $pre_fields = json_decode($FormTemplate->fields, true);
                // END: MasterTemplate Design Variable  -->
                return view('setting.profile.formView')->with(compact('FormTemplate','TD_variables','pre_fields','template_id','accessable_to','formid','answer_fields','profile_response_id'));
        }else {
            \Session::flash('message', 'There is no profile form attached with this master template yet!');
            return \Redirect::back();
        }
    }

 /**
     * Save the record for Against participant.
     *
     * @return \Illuminate\Http\Response
     */
    public function DeleteFileS3(Request $request)
    {
      $disk = getStorageDisk();
      
      $URL = $request->get("Path");
      $path = getStoragePath($URL);
       if($disk->exists($path)) {
          $done = $disk->delete($path);
        }else{
          $done = "Fail";
        }
      return ['status'=>$done,'path'=>$path];
    } 
      /**
     * Save the record for Against participant.
     *
     * @return \Illuminate\Http\Response
     */
    public function saveResponse(Request $request)
    {

        $user_id = \Auth::user()->id;
        $template_id = $request->template_id;
        $participant_id = $request->participant_id;
        $form_id = $request->form_id;
        $fieldsarray = $request->fields;
        $profileId =$request->profile_response_id;
        if (isset($profileId) && $profileId!="") {
            $model = ProfileResponse::find($profileId);
            $model->template_id = $template_id;
            $model->form_id = $form_id;
            $model->user_id = $user_id;
            $model->fields = submitFormFields($request);
            $model->accessable_to = $request->accessable_to;
            $model->update();
            \Session::flash('message', 'Profile has been updated successfully');
        }else{
            $model = new ProfileResponse();
            $model->template_id = $template_id;
            $model->form_id = $form_id;
            $model->user_id = $user_id;
            $model->fields = submitFormFields($request);
            $model->accessable_to = $request->accessable_to;
            $model->save();
            \Session::flash('message', 'Profile has been stored successfully');
        }
            

        return redirect()->action(
            'UserController@index'
        );


    }
    
    /**
     * @return string
     */
    public function userProfile()
    {
        $user_id   = \Auth::user()->id;
        $user = User::where('id',$user_id)->first();
        return view('setting.userProfile.index')->with(compact('user'));
    }
    
    public function imageUpload(Request $request)
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
        
        $cropped = explode(';', $cropp);

        list(, $cropped)      = explode(',', $cropped['1']);
        
        $cropped = base64_decode($cropped);
        
        $croppedPath = 'images/temp/cropped/' . $fileName . '.png';
        $originalPath = 'images/temp/original';
    
        $model = User::findOrFail($user_id);

        if($cropped) {
         file_put_contents($croppedPath, $cropped);
        
         $cropped = $disk->putFile("profilePicture/cropped/$user_id", new File($croppedPath), "public");
    
            $model->cropped_profile_picture = $cropped;
            if(\File::exists($croppedPath)){
             \File::delete($croppedPath);
         }
   
     }
     
        
        if($request->userOrignalImage!='') {
            $imageName = $fileName . '.' . $request->userOrignalImage->getClientOriginalExtension();
            $request->userOrignalImage->move(public_path($originalPath), $imageName);
            $original = $disk->putFile("profilePicture/original/$user_id",new File($originalPath.'/'.$imageName),"public");
           
            $model->orignal_profile_picture = $original;
            
    
            if(\File::exists($originalPath.'/'.$imageName)){
                \File::delete($originalPath.'/'.$imageName);
            }
    
        }
    
        $model->update();
    
    
        
        return redirect()->back();
        
    }

    /**
     * @return string
     */
    public function updateUser(Request $request)
    {
        $user_id   = \Auth::user()->id;
    
    
        $v = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'username' => 'required|unique:users,username,'.$user_id.'',
            'password' => 'min:6|confirmed',
        ]);
    
        if ($v->fails())
        {
            
            return redirect()->back()->withErrors($v->errors());
        }
        $model = User::findOrFail($user_id);
        
        if($request->name!='')
        $model->name = $request->name;
        if($request->business_name!='')
        $model->business_name = $request->business_name;
        if($request->password!='')
        $model->password = bcrypt($request->password);

        $model->location = $request->location;
        $model->username = $request->username;
        
        $model->update();
    
        \Session::flash('messageUser', 'Data has been updated successfully');
       return redirect()->back();
        
    }
    
    
    /**
     * @return string
     */
    public function removeProfileImage()
    {
        $disk = Storage::disk('s3');
        $user_id   = \Auth::user()->id;
        $model = User::findOrFail($user_id);
    
    
        $delete = $disk->delete($model->cropped_profile_picture);
        $delete = $disk->delete($model->orignal_profile_picture);
        
        $model->cropped_profile_picture='';
        $model->orignal_profile_picture='';
        $model->update();
        
    
        \Session::flash('message', 'Image has been deleted successfully');
    
        return redirect()->back();
    
    
    }

}
