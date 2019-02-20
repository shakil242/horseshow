<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class inviteParticipantinvoice extends Model
{
    public function getUserName($id)
    {
        return  \App\User::where('id', $id)->first();
    
    
    
    }
}
