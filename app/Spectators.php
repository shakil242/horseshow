<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Spectators extends Model
{

    
    public function spectatorsForm()
    {
        return $this->hasMany('App\SpectatorForm','spectator_id','id');
    }
    
    public function feedBack()
    {
        return $this->hasMany('App\SchedulerFeedBacks','spectator_id','id');
    }

    public function getShowId($user_id,$template_id)
    {

        return  \App\ManageShows::where('user_id', $user_id)
            ->where('template_id', $template_id);
    }
}
