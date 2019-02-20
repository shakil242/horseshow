<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{

    protected $fillable = [
         'template_id'
    ];
protected $dates =[
    'accepted_request_time',
    'penalty_date'
];
    //Relation with Asset
    public function asset()
    {
        return $this->hasOne("App\Asset", 'id','asset_id');
    }
    //Relation with show
    public function show()
    {
        return $this->hasOne("App\ManageShows", 'id','show_id');
    }

    public function showRegistration(){
        return $this->belongsTo('App\ManageShowsRegister', 'manage_show_reg_id', 'id');
    }
	//participant modules
	public function participants_modules() {
	    //return $this->belongsToMany('App\Module', 'participant_modules', 'id', '');
	}
	//Relation with assets
	public function InvitedOnAsset()
    {
        return $this->hasOne("App\Asset", 'id','asset_id');
    }
    //Relation with Invitee
	public function Invitee()
    {
        return $this->hasOne("App\User", 'id','invitee_id');
    }
    //Relation with Invitee
    public function GetUserObj()
    {
        return $this->hasOne("App\User", 'email','email');
    }
    //Relation with Invitee
    public function hastemplate()
    {
        return $this->hasOne("App\Template", 'id','template_id');
    }

    public function participantAsset($id,$associatedId)
    {
        $userEmail = \Auth::user()->email;

    
        $formsScheduler= getschedulerForms($id,$associatedId,$userEmail);

        return  $formsScheduler->count();
    }
    
    public function participantFeedBackExist($id)
    {
        
        return  \App\SchedulerFeedBacks::select('id')->where('asset_id', $id)
            ->count();
    }
    
    public function invoice($id,$assetId)
    {
        return participantInvoiceForms($id,$assetId)->count();
    
    }
    
    
    public function isFormSubmitted($id)
    {
        $user_id = \Auth::user()->id;
    
        return $participantResponse = ParticipantResponse::where('participant_id',$id)->count();
        
    }
    
    public function checkPenaltyDate($penalty_date)
    {
         $dt = Carbon::today();
         $penaltyDate = Carbon::parse($penalty_date);
       
        if($penaltyDate->lte($dt)) {
            return 1;
        }
        else
        {
            return 0;
        }
        
    }
    
    /**
     * @return array
     */
    public function invoiceExist($invite_asociated_key,$asset_id)
    {
    
        $userId = \Auth::user()->id;
        return  \App\Invoice::where('invite_asociated_key',trim($invite_asociated_key))
            ->where('asset_id',$asset_id)
            ->where(function ($query) use($userId){
                $query->where('invitee_id',$userId)
                ->orWhere('payer_id',$userId);
            })->count();

    }


    public function invoicePaid($show_id,$user_id)
    {

       $invoice = \App\Invoice::where('show_id',$show_id)
            ->where('payer_id',$user_id)->first();

      if($invoice)
        return \App\Billing::where('invoice_id',$invoice->id)->count();
      else
       return 0;
    }

    public function payinoffice($show_id,$user_id)
    {

       $invoice = \App\Invoice::where('show_id',$show_id)
            ->where('payer_id',$user_id)->first();
        
      if(isset($invoice) && $invoice->payinoffice == 1)
        return true;
      else
       return false;
    }


    public function judgesFeedBackExist($id)
    {

        return  \App\SchedulerFeedBacks::select('id')->where('asset_id', $id)->where('feed_back_type', JUDGES_FEEDBACK)->where('rider_allowed_to_view', 1)
            ->count();
    }

}
