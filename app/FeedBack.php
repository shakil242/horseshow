<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FeedBack extends Model
{
    protected $table = 'feed_backs';
    
    public function invitee()
    {
        return $this->hasOne("App\User", 'id','invitee_id');
    }
    //Relation with Invitee
    public function template()
    {
        return $this->hasOne("App\Template", 'id','template_id');
    }
    
    public function user()
    {
        return $this->hasOne("App\User", 'id','user_id');
    }
    
    public function schedualNotes()
    {
        return $this->hasOne("App\SchedualNotes", 'id','schedule_id');
    }
    
 
    
    

    //
}
