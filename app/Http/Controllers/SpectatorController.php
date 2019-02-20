<?php

namespace App\Http\Controllers;

use App\Form;
use App\Mail\InviteUser;
use App\Mail\Participants;
use App\Schedual;
use App\SpectatorForm;
use App\Spectators;
use App\Template;
use Illuminate\Http\Request;
use App\InvitedUser;
use App\SchedualNotes;
use Excel;
use Illuminate\Support\Facades\Input;

class SpectatorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($template_id)
    {
        $user_id = \Auth::user()->id;
        $template_id = nxb_decode($template_id);
        $forms = Form::where('template_id',$template_id)->where('scheduler',1)->get();
    
        $templates = Template::where('id',$template_id)->with("associated_template")->first();
        $associated = $templates->associated_template;

        return view('MasterTemplate.spectators.index')->with(compact("template_id","forms","associated"));
    
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function invite(Request $request)
    {
    
        $excelData = array();
        $data = $request->all();
        $this->validate($request, [
            // 'location' => "required",
            'emailName.*.email' => 'email|required_without:import_file',
            'emailName.*.name' => 'required_without:import_file',
        ],[
            'required_without' => 'Please enter a validate email and Name for the invite new participants',
        ]);
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
        

        //$excelData has all the records for users uploaded from excel file
        //dd($excelData);
        
        
        $uniq = unique_multidim_array(array_merge($data['emailName'],$excelData),'email');

        //Shakeel code to manage employee

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

        if (isset($uniq) && $uniq != null) {
            foreach ($uniq as  $IS){
                
                $spectator = new Spectators();
                $spectatorForm = new SpectatorForm();
             
                //$IS is Invited Spectators
                if ($IS['email'] != "" && $IS['name'] != "" ) {
    
                    $spectators = Spectators::select('id')->where('email',$IS['email'])
                                  ->where('template_id',$request->get('template_id'))
                                  ->first();
                    
                    if(is_null($spectators)) {
                        
                        $spectator->email = $IS['email'];
                        $spectator->name = $IS['name'];
                        $spectator->template_id = $request->get('template_id');
                        $spectator->invitee_id = $user_id;
                        $spectator->invited_master_template = $request->get('invited_master_template');
                        $spectator->status = 0;
                        $spectator->employee_id = $employee_id;

                        $spectator->save();
                        $forms = $request->get('form_id');
    
                        $dataArr = [];
                        
                        for ($i=0;$i<count($forms);$i++)
                        {
                            $dataArr[$i]['form_id']= $forms[$i];
                            $dataArr[$i]['spectator_id'] = $spectator->id;
                        }
                        $spectatorForm->insert($dataArr);
                        
                    }else {
                        
                        
                        $Module = Spectators::findOrFail($spectators->id);

                        $Module->email = $IS['email'];
                        $Module->name = $IS['name'];
                        $Module->template_id = $request->get('template_id');
                        $Module->invitee_id = $user_id;
                        $Module->invited_master_template = $request->get('invited_master_template');
                        $spectator->employee_id = $employee_id;

                        $Module->update();

                        $forms = $request->get('form_id');

                        SpectatorForm::where('spectator_id', '=', $spectators->id)->delete();
                        $dataArr=[];
                        for ($i=0;$i<count($forms);$i++)
                        {
                            $dataArr[$i]['form_id']= $forms[$i];
                            $dataArr[$i]['spectator_id'] = $spectators->id;
                        }
                        if($dataArr)
                        SpectatorForm::insert($dataArr);
                    }
                }
            }
    
            
        }
        
        return redirect()->action('UserController@index');
//
//        if ($request->get('email')) {
//            $sendmail = $this->createAndSendMail($forms,$request,$request->get('email'),$request->get('name'));
//        }
//
//        if ($sendmail) {
//            \Session::flash('message', 'Invite has been send to User successfully');
//            return redirect()->action('UserController@index');
//        }

        
    }
    
    public function createAndSendMail($array_asset,$data,$email,$name){
        $isEmail = \Session('isEmployee');
        $userEmail = \Auth::user()->email;

        if($isEmail==1) {
            $user_id = getAppOwnerId($userEmail,$data->template_id);
            $employee_id = \Auth::user()->id;

        }
        else {
            $user_id = \Auth::user()->id;
            $employee_id = 0;
        }
        foreach($array_asset as $asset) {
            $model = new Spectators();
            $model->template_id = $data->template_id;
            $model->invitee_id = $user_id;
            $model->employee_id = $employee_id;
            $model->email = $email;
            $model->name = $name;
            $model->save();
        }
        
        $email = $model->email;
        //Send email for new master template as invite user.
        if ($data->get('invited_master_template')!= null) {
            //Check if already invited_user exists
            $invited_user = InvitedUser::where('email',$email)->where('template_id',$data->get('invited_master_template'))->first();
            if ($invited_user == null) {
                $invited_user = new InvitedUser();
                $invited_user->name = $data->get('name');
                $invited_user->email = $email;
                $invited_user->invited_by = $user_id;
                $model->employee_id = $employee_id;
                $invited_user->template_id = $data->get('invited_master_template');
                $invited_user->save();
            }
            
            
            \Mail::to($email)->send(new InviteUser($invited_user));
        }
        
        //Send email for the asset invite.
        \Mail::to($email)->send(new Participants($model,$array_asset));
        return true;
    }
    
    
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function masterSchedular($id,$spectatorsId)
    {
        
        $user_id = \Auth::user()->id;




        $template_id = nxb_decode($id);
        $spectatorsId = nxb_decode($spectatorsId);
    
        
        $FormTemplate=array();
        $scheduals=array();
        $assetId='';
        
        $spectatorsForms = Spectators::select('*')->where('id', $spectatorsId)
            ->orderBy('id', 'DESC')
            ->get();
        
        if (count($spectatorsForms)>0)
        {
            $spectatorsForms = Spectators::where('id', $spectatorsId)
                ->orderBy('id', 'DESC')
                ->first();
    
            $formId = $spectatorsForms->spectatorsForm[0]->form_id;
            
            
            $FormTemplate = Schedual::where('template_id', $template_id)
                ->where('user_id', $user_id)
                ->where('form_id', $formId)
                ->get();
            
            $scheduals = SchedualNotes::where('template_id', $template_id)
                ->where('form_id', $formId)
                ->get();
            
        }
        
       // dd($spectatorsForms->spectatorsForm);
        
        $calendarVal = getMasterSchedulerEvents($FormTemplate, $scheduals);
        $calendar = $calendarVal['calendar'];
        $clId = $calendarVal['clId'];
        
        $variables = array('templateId' => $template_id, 'calId' => $clId);
        
        return view('spectators.masterScheduler.index')->with(compact("template_id", 'spectatorsForms', "calendar", 'variables'));
        
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
