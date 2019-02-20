<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ParticipantAccountInformation extends Model
{
    public function participant()
    {
        return $this->hasOne("App\Participant", 'id','participant_id');
    }
}
