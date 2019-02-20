<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InvitedUser extends Model
{
     /**
     * Get the template name with invited users.
     */
    public function template()
    {
         return $this->belongsTo('App\Template', 'template_id', 'id');
    }
    /**
     * Relation with templates
     */
    public function hastemplate()
    {
        return $this->hasOne("App\Template", 'id','template_id');
    }
    /**
     * Get all related Email Users.
     */
    public function emailUsers()
    {
        return $this->belongsTo('App\User', 'email', 'email');
    }

    /**
     * Get the related shows.
     */
    public function shows(){
        return $this->belongsTo('App\ManageShowRegistration', 'id', 'manage_show_reg_id');
    }
    /**
     * Get the Feedback for show. 
     * @return number of feedbacks
     */
    public function masterFeedBackExist($id)
    {  
        return  \App\SchedulerFeedBacks::select('id')->where('template_id', $id)
            ->count();
    }

    public function masterJudgesFeedBackExist($id)
    {
        return  \App\SchedulerFeedBacks::select('id')->where('template_id', $id)->where('feed_back_type', JUDGES_FEEDBACK)
            ->count();
    }
     /**
     * Get the Master Invoice for show. 
     * @return number of template invoices
     */
    public function masterInvoiceExist($id)
    {    
         return  \App\Invoice::select('id')
            ->where('template_id', $id)
            ->count();
    }
    


}
