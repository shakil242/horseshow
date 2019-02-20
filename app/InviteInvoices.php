<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InviteInvoices extends Model
{
    public function getInvitedBy($id,$module_id)
    {
        
        
        $model = \App\inviteParticipantinvoice::where('invoiceFormKey', $id)
            ->where('module_id', $module_id)->first();
    
       $users =  \App\User::where('id', $model->invitee_id)->first();
        
        return $users->name;
        
    }
    
    public function invoiceTitle($id,$module_id)
    {
    
        $model = \App\inviteParticipantinvoice::where('invoiceFormKey', $id)
            ->where('module_id', $module_id)->first();
    
           return \App\Form::where('id',$model->form_id)->first();

    }
    
    
    /**
     * @return array
     */
    public function GetEventname($module_id)
    {
        
        $moduleForm = \App\Module::where('id',$module_id)->first();
        
        echo '>>>'.$module_id.'>>>>>'.$moduleForm->linkto;exit;
        
        
        
        return \App\Form::where('id',$moduleForm->linkto)->first();
    
    }
    
    /**
     * @return array
     */
    
    
    
}
