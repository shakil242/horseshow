<?php
/**
 * This is Report Controller to control all the Templates Forms reporting in 
 * the project
 *
 * @author Faran Ahmed (Vteams)
 */
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\InvitedUser;
use App\ParticipantResponse;
use App\Form;
use App\Asset;

class ReportController extends Controller
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
     *compairFormReport
     * Show the Comaprison of all the answers given on the form by users graphically.
     *
     * @return response in reports.index view
     */
    public function compairFormReport($form_id)
    {
        $form_id = nxb_decode($form_id);
        $user_id = \Auth::user()->id;
        $selectedAssets=array();
        $participantResponse = ParticipantResponse::where('form_id',$form_id)->with("participant")->whereHas('participant', function ($query) use ($user_id,$selectedAssets) {
                    $query->where('invitee_id', $user_id);
                })->orWhere(function ($query) use ($form_id, $user_id,$selectedAssets) {
                    $query->where("form_id",$form_id)
                    ->where('user_id', $user_id);

        })->get();
        $forms =  Form::where('id',$form_id)->first();
        $asset_ids=array();
        foreach ($participantResponse as $value) {
            if($value->participant){
                $asset_ids[]=$value->participant->asset_id; 
            }else{
                $asset_ids[]=$value->asset_id; 
            }
            
        }
        //dd($assets);
        $assets =array_unique($asset_ids);
        //$assets = Asset::where('template_id',$forms->template_id)->where('user_id',$user_id)->pluck("id")->toArray();
        $formfields = json_decode($forms->fields);
        return view('reports.index')->with(compact("selectedAssets","assets","forms","participantResponse","formfields"));
    }

    /**
     *selectAssetReport
     * Show Selected Assets for Comaprison of all the answers given on the form by users graphically.
     *
     * @return response in reports.index view
     */
    public function selectAssetReport(Request $request)
    {

        $this->validate($request, [
            'asset' => "required|array|min:1",
        ]);
        $selectedAssets=$request->asset;
        $form_id=$request->form_id;
        $user_id = \Auth::user()->id;
        $participantResponse = ParticipantResponse::where('form_id',$form_id)->with("participant")->whereHas('participant', function ($query) use ($user_id,$selectedAssets) {
                    $query->where('invitee_id', $user_id);
                    $query->whereIn('asset_id', $selectedAssets);
                })->orWhere(function ($query) use ($form_id, $user_id,$selectedAssets) {
                    $query->where("form_id",$form_id)
                    ->where('user_id', $user_id)
                    ->whereIn('asset_id', $selectedAssets);

        })->get();
        $assetResponse = ParticipantResponse::where('form_id',$form_id)->with("participant")->whereHas('participant', function ($query) use ($user_id,$selectedAssets) {
                    $query->where('invitee_id', $user_id);
                })->orWhere(function ($query) use ($form_id, $user_id,$selectedAssets) {
                    $query->where("form_id",$form_id)
                    ->where('user_id', $user_id)
                    ->whereIn('asset_id', $selectedAssets);

        })->get();
        $forms =  Form::where('id',$form_id)->first();
        //Creating asset dropdown for app owner and users
        $asset_ids=array();
        foreach ($assetResponse as $value) {
            if ($value->participant) {
                $asset_ids[]=$value->participant->asset_id;
            }else{
                $asset_ids[]=$value->asset_id;
            }
        }
        $assets =array_unique($asset_ids);
        $formfields = json_decode($forms->fields);
        return view('reports.index')->with(compact("selectedAssets","assets","forms","participantResponse","formfields"));
   
    }
}
