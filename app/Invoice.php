<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    
    public function form()
    {
        return $this->hasOne("App\Form", 'id','form_id');
    }
    
    public function inviteeId()
    {
        return $this->hasOne("App\Participant", 'asset_id','asset_id');
    }
    
    public function submittedBy()
    {
        return $this->hasOne("App\User", 'id','payer_id');
    }
    
    public function invoiceTitle()
    {
        return $this->hasOne("App\Form", 'id','invoice_form_id');
    }
    
    public function invoiceValues($id)
    {
        
        return  \App\Invoice::where('id', $id);
    }
    
    public function invoiceStatus($id,$fieldName,$value)
    {
        return $this->invoiceValues($id)->where($fieldName,$value)->count();
    }
    
    public function accountExist($id)
    {
        
        return  \App\ParticipantAccountInformation::where('participant_id', $id)->count();
    }
    
    
    public function bankAccountExist($id)
    {
        
        return  \App\AppownerBankAccountInformation::where('owner_id', $id)->count();
    }
    
    public function participant($id)
    {
        
        return  \App\Participant::where('id', $id)->first();
    }
    
    public function responseForm($id,$templateId)
    {
        
        $userId = \Auth::user()->id;
        
        return  $this->where('form_id', $id)
            ->where('payer_id', $userId)
            ->where('template_id', $templateId)
            ->where('response_id','!=',0);
        
    }
    
    public function submittedByOWner()
    {
        return $this->hasOne("App\User", 'id','invitee_id');
    }
    
    public function invoiceUser($id,$assetId)
    {
        
        $userId = \Auth::user()->id;
        
        return  \App\Invoice::where('form_id', $id)
            ->where('payer_id', $userId)
            ->where('asset_id', $assetId);
        
    }
    
    public function invoiceOwnerStatus($id,$assetId,$fieldName,$value)
    {
        return $this->invoiceUser($id,$assetId)->where($fieldName,$value)->count();
    }
    
    public function sendToInvitee()
    {
        return $this->hasOne("App\User", 'id','show_owner_id');
    }

    public function inviteeUser()
    {
        return $this->hasOne("App\User", 'id','invitee_id');
    }

    
    public function submittedInvitee($user_id)
    {

       $user = User::select('name')->where('id',$user_id)->first();
    
        if($user)
        return $user->name;
        
    }

    public function billing($invoice_id)
    {

       return Billing::where('invoice_id',$invoice_id);

    }


}
