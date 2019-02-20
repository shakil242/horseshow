<?php
    /**
     * This is Form Controller to control all the User actions in front end project
     *
     * @author Faran Ahmed (Vteams)
     */
namespace App\Http\Controllers;

use App\Asset;
use App\Employee;
use App\Spectators;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use App\User;
use App\InvitedUser;
use App\Participant;
use App\ParticipantResponse;
use App\Template;
use Excel;
use App\Mail\InviteUser;
use Illuminate\Support\Facades\Input;
use App\TemplateButtonLabel;
use App\subParticipants;
use App\TemplateAssociate;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {


       // $mod = User::findorFail('87');
       // $mod->password = bcrypt('123456');
       // $mod->update();

/********* to update asset_name **************/

//        $asset = Asset::all();
//        foreach ($asset as $as){
//        $assetName = GetAssetName($as);
//        $m = Asset::findorFail($as->id);
//        $m->name = $assetName;
//        $m->update();
//        }
        \Session::put('isEmployee', 0);
        $isEmail = \Session('isEmployee');

        $user_id   = \Auth::user()->id;
        $useremail = \Auth::user()->email;

        $collection = InvitedUser::with("template")->where('email',$useremail)->orderBy('id','desc')->first();

        return $this->your_app();
    }


    public function your_app()
    {
        $isEmail = \Session('isEmployee');

        \Session::put('currentTab', 'Myapp');

        $user_id   = \Auth::user()->id;
        $useremail = \Auth::user()->email;
        $appCollection = InvitedUser::with("template")->where('status','!=',2)->where('email',$useremail)->orderBy('id','desc')->select('template_id')->get();

        $selectedApps = \Session('selectedApps');
        if($selectedApps!='')
        $collection = InvitedUser::with("template")->where('email',$useremail)->where('template_id',$selectedApps)->orderBy('id','desc')->first();
        else
        $collection = InvitedUser::with("template")->where('email',$useremail)->orderBy('id','desc')->first();
       $employeeCollection = Employee::with('invitedUser')->where('email',$useremail)->get();

        \Session::put('app_id',$collection->id);

        return view('users.yourApp')->with(compact('user_id',"collection","employeeCollection","appCollection"));
    }

    public function loadTemplateApps($template_id)
    {
        \Session::put('isEmployee', 0);
        $isEmail = \Session('isEmployee');

        \Session::put('currentTab', 'myApp');

        \Session::put('selectedApps', $template_id);

        $user_id   = \Auth::user()->id;
        $useremail = \Auth::user()->email;

        $selectedApps = \Session('selectedApps');

        $app = InvitedUser::where('template_id',$template_id)->where('email',$useremail)->first();


        $appCollection = InvitedUser::with("template")->where('email',$useremail)->where('status','!=',2)->orderBy('id','desc')->select('template_id')->get();

        return view('users.app_innerView')->with(compact('user_id',"app","appCollection"));

    }

    public function loadActivityView($participant_id,$pageNo,$asset_id=null)
    {
        \Session::put('isEmployee', 0);
        $isEmail = \Session('isEmployee');


        \Session::put('currentTab', 'activity');

        $user_id   = \Auth::user()->id;
        $useremail = \Auth::user()->email;

        $q = Participant::with('show',"showRegistration","hastemplate");
        $q->where('email', $useremail);
        $limit = 20;

        if(!is_null($participant_id) && is_numeric($participant_id)) {
            \Session::put('selectedActivity', $participant_id);
            $q ->where('id',$participant_id);

        }else {
            $selectedActivity = \Session('selectedActivity');
            $pageNo = \Session('pageNo');

          //  $limit  = $pageNo* 20;
            if ($selectedActivity != '') {
                $q->where('id', $selectedActivity);

            }
        }

        $q ->orderBy('id','desc');
        $app =  $q ->first();

        $que = Participant::with('show',"showRegistration","hastemplate")
            ->where('email',$useremail);


        $appCollection = $que->orderBy('id','desc')->select('template_id','asset_id','show_id','manage_show_reg_id','id')
            ->orderBy('created_at','DESC')->limit($limit)->get();

       // dd($appCollection->toArray());

        $page = $pageNo+1;
        $nexPageUrl ='/ajax-request/loadActivityDataAjax?page='.$page;

        return view('users.activity_innerView')->with(compact('user_id',"app","appCollection","nexPageUrl","page"));

    }


    public function loadSubParticipantView($participant_id)
    {
        \Session::put('isEmployee', 0);
        $isEmail = \Session('isEmployee');


        \Session::put('currentTab', 'subParticipants');

        $user_id   = \Auth::user()->id;
        $useremail = \Auth::user()->email;


        $q = subParticipants::with('Participant');
        $q ->where('email',$useremail);

        if(!is_null($participant_id) && is_numeric($participant_id)) {
            \Session::put('selectedSubParticipant', $participant_id);
            $q->where('id', $participant_id);
        }else {
            $selectedSubParticipant = \Session('selectedSubParticipant');

            if ($selectedSubParticipant != '')
                $q->where('id', $selectedSubParticipant);
        }
        $q ->orderBy('id','desc');
        $app =  $q ->first();
       // dd($app->toArray());

        $que = subParticipants::with('Participant')->where('email',$useremail);
        $appCollection = $que->orderBy('id','desc')->select('template_id','asset_id','participant_id','id')
            ->groupBy('participant_id')->get();


        return view('users.subParticiapant_innerView')->with(compact('user_id',"app","appCollection"));

    }
    public function loadEmployeeView($template_id=null)
    {

        \Session::put('isEmployee', 1);
        $isEmail = \Session('isEmployee');
        
        \Session::put('currentTab', 'employee');

        $user_id   = \Auth::user()->id;
        $useremail = \Auth::user()->email;


        $q = Employee::with('invitedUser');
        $q ->where('email',$useremail);

        if(is_numeric($template_id)) {
            \Session::put('selectedEmployee', $template_id);
            $q->where('template_id', $template_id);
        }else {
            $selectedEmployee = \Session('selectedEmployee');
            if ($selectedEmployee != '')
                $q->where('template_id', $selectedEmployee);
        }

        $q ->orderBy('id','desc');
        $app =  $q ->first();


        $que = Employee::with('invitedUser')->where('email',$useremail);
        $appCollection = $que->orderBy('id','desc')->select('template_id')->groupBy('template_id')->get();


        return view('users.employee_innerView')->with(compact('user_id',"app","appCollection"));

    }


    public function loadActivityDataAjax(Request $request)
    {

        $limit = 20;
        $output = '';
        $id = $request->id;
        $useremail = \Auth::user()->email;

        $que = Participant::with('show',"showRegistration","hastemplate")
            ->where('email',$useremail)
            ->where('id','<',$id)
            ->select('id','template_id','asset_id','show_id','manage_show_reg_id');
        $appCollection = $que->orderBy('id','desc')
            ->limit($limit)->get();


        return view('users.activity_loadMore')->with(compact('appCollection'));

       // $posts = Post::where('id','<',$id)->orderBy('created_at','DESC')->limit(2)->get();

    }

    public function getAppsData(Request $request)
    {
        $query = $request->get('query','');
        $useremail = \Auth::user()->email;
        $appCollection = InvitedUser::with(["template"=>function($q) use ($query){
            $q->where('name','LIKE','%'.$query.'%');
    }])->where('email',$useremail)
            ->orderBy('id','desc')
            ->pluck('template_id')->toArray();

        $Template = Template::where('name','LIKE','%'.$query.'%')->whereIn('id',$appCollection)->select('name','id')->get();

        return response()->json($Template);
    }

    public function getActivityData(Request $request)
    {

        $assetArr = [];

         $query = $request->get('query','');

        $useremail = \Auth::user()->email;

        $appCollection = Participant::select('id','asset_id','template_id','show_id')
            ->with(['show',"showRegistration","hastemplate",
            "asset"=>function($que)
            {
            $que->addSelect(array('id', 'name'));
            }
            ])
            ->wherehas('asset',function ($que) use($query)
            {
            $que->where('name', 'like', '%' . $query . '%');
            })
             ->where('email',$useremail)
            ->groupBy('show_id')
            ->get();

        foreach ($appCollection as $app)
        {
           if($app->hastemplate->category!=HORSE)
                $assetArr[]=array('participant_id'=>$app->id,'id'=>$app->asset->id,'name'=>$app->asset->name);
            else {
             if($app->show)
                $assetArr[] =array('participant_id'=>$app->id,'id'=>$app->asset->id,'name'=>$app->asset->name);
            }
        }

        return response()->json($assetArr);
    }


    public function getSubParticipantData(Request $request)
    {

        $assetArr = [];
        $query = $request->get('query','');

        $useremail = \Auth::user()->email;

        $que = subParticipants::select('asset_id','template_id','participant_id')
            ->with(['Participant',"InvitedOnAsset"=>function($que)
            {
                $que->addSelect(array('id', 'name'));
            }])
            ->wherehas('InvitedOnAsset',function ($que) use($query)
            {
                $que->where('name', 'like', '%' . $query . '%');
            })
            ->where('email',$useremail);

        $appCollection = $que->groupBy('template_id','asset_id')->get();


        foreach ($appCollection as $app)
        {
            if($app->hastemplate->category!=HORSE)
                $assetArr[]=array('id'=>$app->InvitedOnAsset->id,'name'=>$app->InvitedOnAsset->name);
            else {
                if($app->Participant->show)
                    $assetArr[] =array('id'=>$app->InvitedOnAsset->id,'name'=>$app->InvitedOnAsset->name);
            }
        }

        return response()->json($assetArr);
    }




    public function getEmployeeData(Request $request)
    {
        $query = $request->get('query','');
        $useremail = \Auth::user()->email;


        $appCollection = Employee::select('template_id')->with(['invitedUser','template'=>function($q){
        $q->addSelect(array('id', 'name'));
         }])->wherehas('template',function ($qu) use($query)
            {
                $qu->where('name', 'like', '%' . $query . '%');
            })
            ->where('email',$useremail)->groupBy('template_id')->get();

        foreach ($appCollection as $ap)
        {
            $templates[]=array('id'=>$ap->template->id,'name'=>$ap->template->name);

        }
//
//
//
//        $appCollection = InvitedUser::with(["template"=>function($q) use ($query){
//            $q->where('name','LIKE','%'.$query.'%');
//        }])->where('email',$useremail)
//            ->orderBy('id','desc')
//            ->pluck('template_id')->toArray();

       // $Template = Template::where('name','LIKE','%'.$query.'%')->whereIn('id',$appCollection)->select('name','id')->get();

        return response()->json($templates);
    }


    /**
     * Invite User to master templates.
     *
     * @return \Illuminate\Http\Response
     */
    public function InviteUsers($template_id)
    {
        $template_id = nxb_decode($template_id);
        $user_id   = \Auth::user()->id;
        $templates = Template::where('id',$template_id)->with("associated_template")->first();
        $associated = $templates->associated_template;
        return view('MasterTemplate.users.invite')->with(compact('associated',"template_id",'templates'));
    }
     /**
     * Send Invite to Participant for particular assets + Master template.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendinvite(Request $request)
    {

        $isEmail = \Session('isEmployee');
        $userEmail = \Auth::user()->email;

        if ($isEmail == 1) {
            $user_id = getAppOwnerId($userEmail,$request->template_id);
            $employee_id = \Auth::user()->id;
        } else {
            $user_id = \Auth::user()->id;
            $employee_id = 0;
        }

        $excelData = array();
        $sendmail = false;
        $data = $request->all();
        $this->validate($request, [
            'invited_master_template' => "required",
            'emailName.*.email' => 'email|required_without:import_file',
            'emailName.*.name' => 'required_without:import_file',
        ],[
            'required_without' => 'Please enter a validate email and Name for the invite new participants',
        ]);
        $invited_master_template = $request->get('invited_master_template');
        //Uploading excel if exist
        try{
            if(Input::hasFile('import_file')){
                $path = Input::file('import_file')->getRealPath();
                $Uploaded_file = Excel::load($path, function($reader) {
                })->get();
                if(!empty($Uploaded_file) && $Uploaded_file->count()){
                    foreach ($Uploaded_file as $key => $value) {
                        if ($value->name != null && $value->email != null) {
                            $excelData[] = ['name' => $value->name, 'email' => $value->email];
                        }
                    }
                }
            }
        }
        catch(Exception $e){            
            $error = $e->getMessage();            
            $status = "error";            
            $msg = "Oops something went wrong";        
        }

       $uniq = unique_multidim_array(array_merge($data['emailName'],$excelData),'email');
        //Send email for new master template as invite user.
        if (isset($uniq) && $uniq != null) {
            foreach ($uniq as  $IU){
                //$IU is Invite Users
                $email = $IU["email"];
                if ($email != null && $email != "") {
                    
                    foreach ($invited_master_template as $template_id) {
                        if ( $template_id != null) {
                            //Check if already invited_user exists
                            $invited_user = InvitedUser::where('email',$email)->where('template_id',$template_id)->first();
                            if ($invited_user == null) {
                                $invited_user = new InvitedUser();
                                $invited_user->name = $IU["name"];
                                $invited_user->email = $email;
                                $invited_user->invited_by = $user_id;
                                $invited_user->template_id = $template_id;
                                $invited_user->employee_id = $employee_id;

                                $invited_user->save();
                            }
                           $sendmail = \Mail::to($email)->send(new InviteUser($invited_user));
                        }
                        
                    }
                }
            }
        }

    \Session::flash('message', 'Invite has been send to User(s) successfully');
    return redirect()->action('UserController@index');
        
    }
     /**
     * Display all User's invited participants.
     *
     * @return \Illuminate\Http\Response
     */
    public function uParticipantListing($template_id)
    {
        $user_id   = \Auth::user()->id; 
        $template_id = nxb_decode($template_id);
        $collection = Participant::with("asset")->where('invitee_id', $user_id)->where('template_id',$template_id)->orderBy('id','desc')->get();
        //dd($collection->toArray());
        return view('users.listing.index')->with(compact('collection','template_id'));
    }
    /**
     * Block invited participants.
     *
     * @return \Illuminate\Http\Response
     */
    public function blockInvite($invite_id)
    {
        $user_id   = \Auth::user()->id; 
        $invite_id = nxb_decode($invite_id);
        $model = Participant::find($invite_id);
        $model->block = 1;
        $model->save();
        \Session::flash('message', 'Invite has been blocked.');
        return redirect()->back();
        //return view('users.listing.index')->with(compact('collection','template_id'));
    }
        /**
     * Block invited participants.
     *
     * @return \Illuminate\Http\Response
     */
    public function unblockInvite($invite_id)
    {
        $user_id   = \Auth::user()->id; 
        $invite_id = nxb_decode($invite_id);
        $model = Participant::find($invite_id);
        $model->block = 0;
        $model->save();
        \Session::flash('message', 'Invite has been blocked.');
        return redirect()->back();
        //return view('users.listing.index')->with(compact('collection','template_id'));
    }



}
