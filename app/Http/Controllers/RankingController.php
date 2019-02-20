<?php
    /**
     * This is Ranking Controller For frontend to rank all the Forms Responses in project
     *
     * @author Faran Ahmed (Vteams)
     * @date 17-Feb-2017
     */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ParticipantResponse;
use App\Template;
use App\Schedual;
use App\Module;
use App\Form;
use App\Participant;
use App\subParticipants;
use App\ClassHorse;
use App\Asset;
class RankingController extends Controller
{
    /**
     * Display listing of the ranking of users.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($template_id)
    {
        $user_id = \Auth::user()->id;
        $template_id = nxb_decode($template_id);
        $MT_name = Template::where("id",$template_id)->first()->toArray();

        if($MT_name["cumulative_ranking"]){
            $cumulativeCheck = true;
            $participantResponse = ParticipantResponse::with("assets")->select("id","asset_id")->where('asset_id','!=',NULL)->where('template_id',$template_id)->with("participant")->groupBy('asset_id')->get();
        }else{
            $cumulativeCheck = false;
            $participantResponse = ParticipantResponse::select("id","user_id")->where('template_id',$template_id)->with("participant")->whereHas('participant', function ($query) use ($user_id) {
                    $query->where('invitee_id', $user_id);
                })->groupBy('user_id')->get();
        }
        $form_id=null;
        //Modules collection
        $collection = Module::where('template_id',$template_id)->where('linkto',0)->get()->toArray();
        $generalCollection = Module::where('template_id',$template_id)->where('general',1)->get()->toArray();

        return view('ranking.index')->with(compact('form_id','cumulativeCheck',"MT_name","generalCollection","collection","template_id","participantResponse"));
    }
        /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function backToAll($template_id)
    {
        
        $user_id = \Auth::user()->id;
        $template_id = nxb_decode($template_id);
        $collection = Module::where('template_id',$template_id)->where('linkto',0)->get()->toArray();
        $generalCollection = Module::where('template_id',$template_id)->where('general',1)->get()->toArray();
        $MT_name = Template::where("id",$template_id)->first()->toArray();
        return view('ranking.modules')->with(compact('MT_name','collection','template_id','generalCollection'));
    	
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($template_id,$moduleid)
    {
        $user_id = \Auth::user()->id;
        $template_id = nxb_decode($template_id);
        $moduleid = nxb_decode($moduleid);
        $FormTemplate = Form::where('template_id',$template_id)->where('linkto',$moduleid)->first();
        if ($FormTemplate) {
        		$participantResponse = ParticipantResponse::select("id","user_id")->where('form_id',$FormTemplate->id)->where('template_id',$template_id)->with("participant")->whereHas('participant', function ($query) use ($user_id) {
                    $query->where('invitee_id', $user_id);
                })->groupBy('user_id')->get();
        		return 1;
        }
        $collection = Module::where('template_id',$template_id)->where('linkto',$moduleid)->get()->toArray();
        $generalCollection = Module::where('template_id',$template_id)->where('general',1)->get()->toArray();
        $MT_name = Template::select('name')->where("id",$template_id)->first()->toArray();
        //return view('MasterTemplate.modules.listing')->with(compact('user_id','MT_name','breadcrumbsRoute','dataBreadcrum','collection', 'moduleid', 'template_id','generalCollection'));
        return view('ranking.modules')->with(compact('MT_name','collection','template_id','generalCollection'));
    	
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function moduleWise($template_id,$moduleid)
    {
        $user_id = \Auth::user()->id;
        $template_id = nxb_decode($template_id);
        $moduleid = nxb_decode($moduleid);
        $FormTemplate = Form::where('template_id',$template_id)->where('linkto',$moduleid)->first();
        if ($FormTemplate) {
        		$form_id = $FormTemplate->id;
        		$participantResponse = ParticipantResponse::select("id","user_id")->where('form_id',$FormTemplate->id)->where('template_id',$template_id)->with("participant")->whereHas('participant', function ($query) use ($user_id) {
                    $query->where('invitee_id', $user_id);
                })->groupBy('user_id')->get();
        		return view('ranking.moduleRanking')->with(compact('form_id','moduleid','template_id',"participantResponse"));
        }
    }
	/**
     * Display listing of the ranking to participants.
     *
     * @return \Illuminate\Http\Response
     */
    public function participantIndex($participant_id)
    {
        $participant_id = nxb_decode($participant_id);
        //getting info from participant table
        $participant_collection = Participant::where('id',$participant_id)->first();
        $invitee_id = $participant_collection->invitee_id;
        $template_id = $participant_collection->template_id;
        $asset_id = $participant_collection->asset_id;
        $permission = json_decode($participant_collection->modules_permission,true);
        
        //Response from participantResponse
        $participantResponse = ParticipantResponse::select("id","user_id")->where('template_id',$template_id)->with("participant")->whereHas('participant', function ($query) use ($invitee_id) {
                    $query->where('invitee_id', $invitee_id);
                })->groupBy('user_id')->get();
        $form_id=null;
        //Modules collection
        $collection = Module::where('template_id',$template_id)->where('linkto',0)->get()->toArray();
        $generalCollection = Module::where('template_id',$template_id)->where('general',1)->get()->toArray();
        $MT_name = Template::where("id",$template_id)->first()->toArray();

        return view('ranking.participants.index')->with(compact('participant_id','permission','asset_id','invitee_id','form_id',"MT_name","generalCollection","collection","template_id","participantResponse"));
    }
    /**
     * Display listing of the ranking to Sub participants.
     *
     * @return \Illuminate\Http\Response
     */
    public function subParticipantIndex($participant_id)
    {
        $participant_id = nxb_decode($participant_id);
        //getting info from participant table
        $participant_collection = subParticipants::where('id',$participant_id)->with("participant")->first();
        $invitee_id = $participant_collection->participant->invitee_id;
        $template_id = $participant_collection->participant->template_id;
        $asset_id = $participant_collection->participant->asset_id;
        $permission = json_decode($participant_collection->modules_permission,true);
        
        //Response from participantResponse
        $participantResponse = ParticipantResponse::select("id","user_id")->where('template_id',$template_id)->with("participant")->whereHas('participant', function ($query) use ($invitee_id) {
                    $query->where('invitee_id', $invitee_id);
                })->groupBy('user_id')->get();
        $form_id=null;
        //Modules collection
        $collection = Module::where('template_id',$template_id)->where('linkto',0)->get()->toArray();
        $generalCollection = Module::where('template_id',$template_id)->where('general',1)->get()->toArray();
        $MT_name = Template::where("id",$template_id)->first()->toArray();

        return view('ranking.subparticipants.index')->with(compact('participant_id','permission','asset_id','invitee_id','form_id',"MT_name","generalCollection","collection","template_id","participantResponse"));
    }
        /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function participantShow($template_id,$moduleid,$invitee_id,$participant_id)
    {
        $template_id = nxb_decode($template_id);
        $moduleid = nxb_decode($moduleid);
        $FormTemplate = Form::where('template_id',$template_id)->where('linkto',$moduleid)->first();
        if ($FormTemplate) {
        		$participantResponse = ParticipantResponse::select("id","user_id")->where('form_id',$FormTemplate->id)->where('template_id',$template_id)->with("participant")->whereHas('participant', function ($query) use ($invitee_id) {
                    $query->where('invitee_id', $invitee_id);
                })->groupBy('user_id')->get();
        		return 1;
        }
        $participant_collection = Participant::where('id',$participant_id)->first();
        $invitee_id = $participant_collection->invitee_id;
        $permission = json_decode($participant_collection->modules_permission,true);
        $asset_id = $participant_collection->asset_id;
        

        $collection = Module::where('template_id',$template_id)->where('linkto',$moduleid)->get()->toArray();
        $generalCollection = Module::where('template_id',$template_id)->where('general',1)->get()->toArray();
        $MT_name = Template::select('name')->where("id",$template_id)->first()->toArray();
        //return view('MasterTemplate.modules.listing')->with(compact('user_id','MT_name','breadcrumbsRoute','dataBreadcrum','collection', 'moduleid', 'template_id','generalCollection'));
        return view('ranking.participants.modules')->with(compact('participant_id','permission','asset_id','invitee_id','MT_name','collection','template_id','generalCollection'));
    	
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function moduleWiseParticipant($template_id,$moduleid,$invitee_id,$participant_id)
    {
        $template_id = nxb_decode($template_id);
        $moduleid = nxb_decode($moduleid);
        $participant_collection = Participant::where('id',$participant_id)->first();
        $asset_id = $participant_collection->asset_id;
        $FormTemplate = Form::where('template_id',$template_id)->where('linkto',$moduleid)->first();
        if ($FormTemplate) {
        		$form_id = $FormTemplate->id;
        		$participantResponse = ParticipantResponse::select("id","user_id")->where('form_id',$FormTemplate->id)->where('template_id',$template_id)->with("participant")->whereHas('participant', function ($query) use ($invitee_id) {
                    $query->where('invitee_id', $invitee_id);
                })->groupBy('user_id')->get();
        		return view('ranking.participants.moduleRanking')->with(compact('invitee_id','asset_id','participant_id','form_id','moduleid','template_id',"participantResponse"));
        }
    }
            /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function backToAllParticipant($participant_id)
    {
        //getting info from participant table
        $participant_collection = Participant::where('id',$participant_id)->first();
        $invitee_id = $participant_collection->invitee_id;
        $template_id = $participant_collection->template_id;
        $asset_id = $participant_collection->asset_id;
        $permission = json_decode($participant_collection->modules_permission,true);

        //Modules collection
        $collection = Module::where('template_id',$template_id)->where('linkto',0)->get()->toArray();
        $generalCollection = Module::where('template_id',$template_id)->where('general',1)->get()->toArray();
        $MT_name = Template::where("id",$template_id)->first()->toArray();
        
        return view('ranking.participants.modules')->with(compact('participant_id','permission','asset_id','invitee_id','form_id',"MT_name","generalCollection","collection","template_id","participantResponse"));
    
    }

    /**
     * Display All the ranking for horses.
     *
     * @return \Illuminate\Http\Response
     */
    // public function cumulativeOverall()
    // {
    //     $user_id = \Auth::user()->id;
    //     $cumulativeCheck = true;
    //     $participantResponse = ParticipantResponse::with("assets")->select("id","asset_id")->where('asset_id','!=',NULL)->with("participant")->groupBy('asset_id')->get();
    //     $participantResponse = null;
    //     return view('horse.ranking.index')->with(compact("participantResponse"));
    // }

    public function cumulativeOverall()
    {
        $user_id = \Auth::user()->id;
        $cumulativeCheck = true;
        // $participantResponse = ParticipantResponse::with("assets","templates","participant")->select("id","asset_id")->where('asset_id','!=',NULL)->whereHas('templates', function ($query) {
        //                             $query->where('cumulative_ranking', TEMPLATE_CUMULATIVE_TRUE);
        //                         })->groupBy('asset_id')->get();

        $participantResponse = Asset::with("template")->whereHas('template', function ($query) {
                                    $query->where('category', CONST_HORSE_TEMPLATE);
                                })->get();
        // dd($participantResponse->toArray());
        $participantOwnResponse = Asset::where('user_id',$user_id)->with("template")->whereHas('template', function ($query) {
                                    $query->where('category', CONST_HORSE_TEMPLATE);
                                })->get();
        return view('horse.ranking.index')->with(compact("participantResponse","participantOwnResponse"));
    }
    
    
}
