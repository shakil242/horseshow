<?php

namespace App\Http\Controllers;

use App\Form;
use App\inviteParticipantinvoice;
use App\Spectators;
use App\TemplateDesign;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Mail\InviteUser;
use App\InvitedUser;
use App\Participant;
use App\User;
use App\InviteTemplateTransfer;
use App\Mail\Participants;
use App\Mail\InviteeUserResponse;
use App\Mail\ParticipantUserResponse;
use App\subParticipants;
use App\ProjectOvEmail;

class MailController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }
    /**
     * Email response form user.
     *
     * @return \Illuminate\Http\Response
     */
    public function responseInviteMail($id,$response)
    {
        $id = nxb_decode($id);
        $data = InvitedUser::where('id',$id)->first();
        $Invitee_email = getUserEmailfromid($data->invited_by);
        $checkAlreadyUser = checkUserAlreadyExist($data->email);
        //if to recive the response only once

        if ($response == 1) {
            $data->email_confirmation = 1;
            $data->status = 1;
            $data->save();
            \Mail::to($Invitee_email)->send(new InviteeUserResponse($data));

            if(Auth::user()){
                return redirect()->to('/user/dashboard');
            }
            if($checkAlreadyUser>0)
            {
                 return redirect()->to('/login');
            }
            $register_via = REGISTER_VIA_EMAIL;
            return view('auth.register')->with(compact('data','register_via'));

        }elseif ($response == 2) {
            $data->email_confirmation = 1;
            $data->status = 2;
            $data->save();
            \Mail::to($Invitee_email)->send(new InviteeUserResponse($data));
            $message="Thank you for your response. This link has now expired";
            if(Auth::user()){
                return redirect()->to('/user/dashboard');
            }
            if($checkAlreadyUser>0)
            {
                 return redirect()->to('/login');
            }
            return view('errors.exception')->with(compact('message'));
        }else{
            $data->status = 0;
            $data->save();
            return redirect()->to('/');
        }


    }
    /**
     * response form participant.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function responseDecline($id,$response)
    {
        $id = nxb_decode($id);
        $data = Participant::where('id',$id)->first();
    
        $Invitee_email = getUserEmailfromid($data->invitee_id);
        
        $data->status = 2;
        $data->update();
       // \Mail::to($Invitee_email)->send(new ParticipantUserResponse($data));
    
        \Session::flash('message', 'Asset has been declined successfully.');
        return redirect()->to('/user/dashboard');
    
    }
    public function responseParticipantMail($id,$response)
    {
        $id = nxb_decode($id);
        $data = Participant::where('id',$id)->first();

        $Invitee_email = getUserEmailfromid($data->invitee_id);
    
       $penalty_date = $data['penalty_date'];
        
        //if to recive the response only once
        if($data->email_confirmation == 1){
            \Session::flash('message', 'You have already Send a response on this asset.');
            return redirect()->to('/user/dashboard');
        }else{
    
            $dt = Carbon::today();
            $penaltyDate = Carbon::parse($penalty_date);
            $user_id = \Auth::user()->id;
            $useremail = \Auth::user()->email;

            createInvoiceForShow($data);
            // for show participant to save price for all the class invited.

            //$data->email_confirmation = 1;
            if ($response == 1) {
            $data->email_confirmation = 1;
            $data->status = 1;
            $data->accepted_request_time = $dt->toDateTimeString();
            $data->save();
           
            //$participant_collection = Participant::where('email',$useremail)->orderBy('id','desc')->get();
    
//         \Mail::to($Invitee_email)->send(new ParticipantUserResponse($data));




                \Session::flash('message', 'You have accepted the asset.');


                return redirect()->route('dashboard-activity-view', ['participant_id' => $id,'pageNo'=>1,'asset_id' => $data->asset_id]);

                //  loadActivityView($data->asset_id);


            }elseif ($response == 2) {
                
                
                $data->email_confirmation = 1;
                $data->status = 2;
                $data->save();

                $participant_collection = Participant::where('email',$useremail)->orderBy('id','desc')->get();
                
  //              \Mail::to($Invitee_email)->send(new ParticipantUserResponse($data));

                return redirect()->route('dashboard-activity-view', ['asset_id' => $data->asset_id]);
    
                // $message="Thank you for your response. This link has now expired";
                 \Session::flash('message', 'You have rejected the asset.');
            }else{
                $data->status = 0;
                $data->save();
            }
    
    
            if(!Auth::user())
                return view('auth.register')->with(compact('data'));
            
            
            if($penaltyDate->lte($dt))
            {
                
                $module = inviteParticipantinvoice::where('invoiceFormKey',$data->invite_asociated_key)
                    ->where('template_id',$data->template_id)
                    ->where('is_penalty',1)
                    ->first();
                if($module) {
                    getPenaltyInvoice($data->asset_id,$data->invite_asociated_key,$data->template_id);

                    $FormTemplate = Form::where('id', $module->form_id)->first();
                    $TemplateDesign = TemplateDesign::where('template_id', $module->template_id)->first();
                    //MasterTemplate Design Variable  -->
                    $TD_variables = getTemplateDesign($TemplateDesign);
                    $pre_fields = json_decode($FormTemplate->fields, true);
                    $answer_fields = json_decode($module->fields, true);
    
                    // END: MasterTemplate Design Variable  -->
                    $formid = $FormTemplate->id;
                    $invitedInvoice = '1';
    
                    $dataBreadcrum = [
                        'id' => $id,
                        'form_id' => $module->form_id,
                        'template_id' => $data->template_id,
                        'asset_id' => $data->asset_id,
                        'participantId' => $user_id,
                        'invite_asociated_key' => $data->invite_asociated_key,
                        'appOwnerRequest' => '',
                        'responseId' => ''
                    ];
    
                    return view('invoice.viewPenaltyInvoice')->with(compact('FormTemplate', 'TD_variables', 'pre_fields', 'answer_fields', 'data', 'dataBreadcrum'));
                }
                else
                {
    
                    return redirect()->to('/user/dashboard#activity');
                }
        
            }
            else
            {
                return redirect()->to('/user/dashboard#activity');
            }
    
        }
    }

    /**
     * Email response form  projectOverview .
     * @author Faran Ahmed Khan
     * @return \Illuminate\Http\Response
     */
    public function projectOverview(Request $request)
    {
          $user_id = \Auth::user()->id;
          $user_email = \Auth::user()->email;
          $user_name = \Auth::user()->name;
          try {

            if (isset($request->reponse_id)) {

                $body = $request->model_body;
                $email_to =$request->model_to;
                $ccbcc= json_encode($request->model_cc_bcc);
                $subject = $request->model_subject;
                //Upload attachment to s3
                $uploaded = $request->uplaod_attachment;
                $attachedVals = UploadFileToS3($request->uplaod_attachment);
                // $headerInfo = "App Owner Name: ".$user_name.", <br> Owner Email:".$user_email."<br>Project Name:".getAssetNameFromId($request->projectov_id);
                $headerInfo = " "; //App Owner Name: ".$user_name.", <br> Owner Email:".$user_email."<br>Project Name:".getAssetNameFromId($request->projectov_id)." <br>Asset Name: ".getAssetNameFromId($request->asset_id)."<br>Form Name: ".getFormNamefromid($request->form_id)."<br>";

                \Mail::send('Emails.project.index', ['body' => $body,'header' => $headerInfo], function ($message) use ($user_email,$email_to,$subject,$user_name,$ccbcc,$attachedVals)
                {
                    $cc =json_decode($ccbcc);
                    $message->from($user_email, $user_name);
                    $message->to($email_to);
                    if (!empty(array_non_empty_items($cc))) {
                      $message->cc($cc);
                    }
                    //Attach file
                    if (count($attachedVals)>0) {
                      foreach ($attachedVals as  $vaAttach) {
                        $path = getImageS3($vaAttach['path']);
                        $message->attach($path);
                      }
                    }
                    //Add a subject
                    $message->subject($subject);
                });


                $model = new ProjectOvEmail();
                $model->participant_response_id= $request->reponse_id;
                $model->email_to= $email_to;
                $model->app_owner_id= $user_id;
                $model->email_from= $user_email;
                $model->email_cc= $ccbcc;
                $model->email_subject = $request->model_subject;
                $model->projectovs_id = $request->projectov_id;
                $model->email_attachment = json_encode($attachedVals);
                $model->email_body= json_encode($body);
                $model->save();

                \Session::flash('message', 'Your email has been sent successfully');
                return \Redirect::back();
            }


         } catch(ClientException $exception) {
             if ($exception->getCode() == 500) throw new InternalServerErrorException((string) $exception->getResponse()->getBody());
             if ($exception->getCode() == 422) throw new UnprocessableEntityException((string) $exception->getResponse()->getBody());
         }
    }



       /**
     * Email response form  projectOverview .
     * @author Faran Ahmed Khan
     * @return \Illuminate\Http\Response
     */
    public function marketingEmail(Request $request)
    {
          $user_id = \Auth::user()->id;
          $user_email = \Auth::user()->email;
          $user_name = \Auth::user()->name;
          try {

                $body = $request->model_body;
                $email_to= json_encode($request->model_to);
                $subject = $request->model_subject;
                //Upload attachment to s3
                $uploaded = $request->uplaod_attachment;
                $attachedVals = UploadFileToS3($request->uplaod_attachment);
                // $headerInfo = "App Owner Name: ".$user_name.", <br> Owner Email:".$user_email."<br>Project Name:".getAssetNameFromId($request->projectov_id);
                $headerInfo = " "; //App Owner Name: ".$user_name.", <br> Owner Email:".$user_email."<br>Project Name:".getAssetNameFromId($request->projectov_id)." <br>Asset Name: ".getAssetNameFromId($request->asset_id)."<br>Form Name: ".getFormNamefromid($request->form_id)."<br>";

                \Mail::send('Emails.project.index', ['body' => $body,'header' => $headerInfo], function ($message) use ($user_email,$email_to,$subject,$user_name,$attachedVals)
                {
                 
                    $email_to = json_decode($email_to);
                    $message->from($user_email, $user_name);
                    $message->to($email_to);
                    // if (!empty(array_non_empty_items($cc))) {
                    //   $message->cc($cc);
                    // }
                    //Attach file
                    if (count($attachedVals)>0) {
                      foreach ($attachedVals as  $vaAttach) {
                        $path = getImageS3($vaAttach['path']);
                        $message->attach($path);
                      }
                    }
                    //Add a subject
                    $message->subject($subject);
                });


                \Session::flash('message', 'Your email has been sent successfully');
                return \Redirect::back();


         } catch(ClientException $exception) {
             if ($exception->getCode() == 500) throw new InternalServerErrorException((string) $exception->getResponse()->getBody());
             if ($exception->getCode() == 422) throw new UnprocessableEntityException((string) $exception->getResponse()->getBody());
         }
    }

    /**
     * Email response form participant .
     *
     * @return \Illuminate\Http\Response
     */
    public function responseParticipantMails($id,$response)
    {
        $id = nxb_decode($id);
        $data = Participant::where('id',$id)->first();
        $user_exists = User::where('email',$data->email)->first();
        $Invitee_email = getUserEmailfromid($data->invitee_id);

        if($user_exists!=null){
            return redirect()->to('/user/dashboard');
        }
        //if to recive the response only once
        if($data->email_confirmation == 1){
            $message="You have already send a response. This link has now expired";
            return view('errors.exception')->with(compact('message')); 
        }else{
            if ($response == 1) {
                if(Auth::user()){
                    return redirect()->to('/user/dashboard');
                }
                \Mail::to($Invitee_email)->send(new ParticipantUserResponse($data));

                return view('auth.register')->with(compact('data'));

            }elseif ($response == 2) {
                $data->email_confirmation = 1;
                $data->status = 2;
                $data->save();
                \Mail::to($Invitee_email)->send(new ParticipantUserResponse($data));
                
                $message="Thank you for your Response. This link has now expired";
                return view('errors.exception')->with(compact('message')); 
            }else{
                $data->status = 0;
                $data->save();
                return redirect()->to('/');
            }
        }
       
   
    }
    /**
     * Email response form Sub participant .
     *
     * @return \Illuminate\Http\Response
     */

    public function responseSubParticipantMail($id,$response)
    {
        $id = nxb_decode($id);
        $data = SubParticipants::where('id',$id)->first();  
        //$Invitee_email = getUserEmailfromid($data->invitee_id);
        
        //if to recive the response only once
        if($data->email_confirmation == 1){
            \Session::flash('message', 'You have already Send a response on this asset.');
            return redirect()->to('/user/dashboard#subParticipants');
        }else{
    
            $user_id = \Auth::user()->id;
            if ($response == 1) {
            $data->email_confirmation = 1;
            $data->status = 1;
            $data->save();
            //\Mail::to($Invitee_email)->send(new ParticipantUserResponse($data));
            \Session::flash('message', 'You have accepted the asset.');
            }elseif ($response == 2) {
                $data->email_confirmation = 1;
                $data->status = 2;
                $data->save();
                \Mail::to($Invitee_email)->send(new ParticipantUserResponse($data));

               // $message="Thank you for your response. This link has now expired";
                 \Session::flash('message', 'You have rejected the asset.');
            }else{
                $data->status = 0;
                $data->save();
            }
    
            if(!Auth::user())
                return view('auth.register')->with(compact('data'));
            
        
            return redirect()->to('/user/dashboard#subParticipants');
        }
    }

    /**
     * Email response form Sub participant .
     *
     * @return \Illuminate\Http\Response
     */
    public function responseSubParticipantMails($id,$response)
    {
        $id = nxb_decode($id);
        $data = SubParticipants::where('id',$id)->first();
        $user_exists = User::where('email',$data->email)->first();
        //$Invitee_email = getUserEmailfromid($data->invitee_id);

        if($user_exists!=null){
            return redirect()->to('/user/dashboard');
        }
        //if to recive the response only once
        if($data->email_confirmation == 1){
            $message="You have already send a response. This link has now expired";
            return view('errors.exception')->with(compact('message')); 
        }else{
            if ($response == 1) {
                if(Auth::user()){
                    return redirect()->to('/user/dashboard');
                }
              //  \Mail::to($Invitee_email)->send(new ParticipantUserResponse($data));

                return view('auth.register')->with(compact('data'));

            }elseif ($response == 2) {
                $data->email_confirmation = 1;
                $data->status = 2;
                $data->save();
               // \Mail::to($Invitee_email)->send(new ParticipantUserResponse($data));
                
                $message="Thank you for your Response. This link has now expired";
                return view('errors.exception')->with(compact('message')); 
            }else{
                $data->status = 0;
                $data->save();
                return redirect()->to('/');
            }
        }
       
   
    }
    
    
    
    public function responseSpectatorsMail($id,$response)
    {
        $id = nxb_decode($id);
        $data = Spectators::where('id',$id)->first();
        
        
        //if to recive the response only once
        if($data->email_confirmation == 1){
            \Session::flash('message', 'You have already Send a response to this Spectators.');
            return redirect()->to('/user/dashboard');
        }else{
            //$data->email_confirmation = 1;
            if ($response == 1) {
                $data->email_confirmation = 1;
                $data->status = 1;
                $data->update();
                if(Auth::user()){
                    \Session::flash('message', 'You have accepted the Spectators request.');
                    return redirect()->to('/user/dashboard');
                }
                return view('auth.register')->with(compact('data'));
                
            }elseif ($response == 2) {
                $data->email_confirmation = 1;
                $data->status = 2;
                $data->save();
                // $message="Thank you for your response. This link has now expired";
                \Session::flash('message', 'You have rejected the Spectators request.');
                return redirect()->to('/user/dashboard');
            }else{
                $data->status = 0;
                $data->save();
                return redirect()->to('/user/dashboard');
            }
        }
    }
    
    /**
     * Email response form user of app transfer.
     *
     * @return \Illuminate\Http\Response
     */
    public function responseTransferInviteMail($id,$response)
    {
        $id = nxb_decode($id);
        $data = InviteTemplateTransfer::where('id',$id)->first();
        //if to recive the response only once
        if ( $data == null) {
            $message="No data found for this email. Kindly contact admin";
            return view('errors.exception')->with(compact('message')); 
        }
        //get sender email
        $senderEmail = getUserEmailfromid($data->sender_id);

        if($data->status == 1 || $data->status == 2){
            $message="You have already send a response. This link has now expired";
            return view('errors.exception')->with(compact('message')); 
        }else{
            //if accepts
            if ($response == 1) {
                //Check if user exists
                $user_exists = User::where('email',$data->invite_email)->count();
                if ($user_exists) {
                    //Check if template Already Exists ($tAE) with user 
                    $tAE = InvitedUser::where('email',$data->invite_email)->where('template_id',$data->template_id)->count();
                        
                        // if ($data->invite_email != \Auth::user()->email) {
                        //     $message="You are not authorized to use this link.";
                        //     return view('errors.exception')->with(compact('message')); 
                        // }
                    //if this template is not assigned with this user
                    if ($tAE == 0) {
                        $model = InvitedUser::where('email',$senderEmail)->where('template_id',$data->template_id)->first();
                        $model->email=$data->invite_email;
                        $model->save();
                        \Session::flash('message', 'The app has been transfered successfully.');
                        $data->status = 1;
                        $data->save();
                        return redirect()->to('/user/dashboard');
                    }else{
                        \Session::flash('message', 'This user is already have same app.');
                        return redirect()->to('/user/dashboard');

                    }
                    //\Mail::to($Invitee_email)->send(new InviteeTranferUserResponse($data));
                }else{
                    return view('auth.register')->with(compact('data'));
                }

            }
            //Rejects
            elseif ($response == 2) {
                $data->status = 2;
                $data->save();
                //\Mail::to($Invitee_email)->send(new InviteeTranferUserResponse($data));
               $message="Thank you for your response. This link has now expired";
                return view('errors.exception')->with(compact('message')); 
            }else{
                $data->status = 0;
                $data->save();
                return redirect()->to('/');
            }
        }
       
   
    }  
    
}
