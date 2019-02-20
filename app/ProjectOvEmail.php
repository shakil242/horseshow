<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProjectOvEmail extends Model
{
  /**
     * Get the participant record associated with the user.
     */
    public function ParticipantResponse()
    {
        return $this->belongsTo('App\ParticipantResponse','participant_response_id','id');
    }

}
