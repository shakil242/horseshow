<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class subParticipants extends Model
{
    //Relation with Invitee
	public function Invitee()
    {
        return $this->hasOne("App\User", 'id','user_id');
    }
    	//Relation with assets
	public function InvitedOnAsset()
    {
        return $this->hasOne("App\Asset", 'id','asset_id');
    }
     	//Relation with assets
	public function participant()
    {
        return $this->hasOne("App\Participant", 'id','participant_id');
    }
    
    public function participantAsset($id)
    {
        $formsScheduler= subParticipantSchedulerForms($id);
        if($formsScheduler)
        return  $formsScheduler->count();
        else
        return 0;
    }
    
    public function invoiceExist($id,$invite_asociated_key,$asset_id,$inviteParticipantkey)
    {
        
        $userId = \Auth::user()->id;
        $userEmail = \Auth::user()->email;
    
        $formsScheduler= subParticipantInvoiceForms($id,$invite_asociated_key,$userEmail);
        
        $formArr  = $formsScheduler->get()->toArray();
        
        return  \App\Invoice::where('invite_asociated_key',trim($inviteParticipantkey))
            ->where('asset_id',$asset_id)
            ->whereIn('form_id',$formArr)
            ->where(function ($query) use($userId){
                $query->where('invitee_id',$userId)
                    ->orWhere('payer_id',$userId);
            })->count();
        
    }


    public function hastemplate()
    {

        return $this->hasOne("App\Template", 'id','template_id');

    }



}
