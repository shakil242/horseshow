<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ParticipantResponse extends Model
{
	/**
     * Get the participant record associated with the user.
     */
    public function participant()
    {
        return $this->belongsTo('App\Participant','participant_id','id');
    }
    public function subparticipants()
    {
        return $this->belongsTo('App\subParticipants','subparticipant_id','id');
    }
    /**
     * Get the participant record associated with the user. And grouped by asset id
     */
    public function participantgroup()
    {
        return $this->belongsTo('App\Participant','participant_id','id')->groupBy("asset_id");
    }
    /**
     * Get the participant record associated with the user.
     */
    public function assets()
    {
        return $this->belongsTo('App\Asset','asset_id','id');
    }
    /**
     * Get the participant record associated with the user.
     */
    public function templates()
    {
        return $this->belongsTo('App\Template','template_id','id');
    }

    public function user()
    {
        return $this->belongsTo('App\User','user_id','id');
    }
    public function form()
    {
        return $this->belongsTo('App\Form','form_id','id');
    }
}
