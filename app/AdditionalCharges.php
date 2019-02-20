<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdditionalCharges extends Model
{
    public function app()
    {
        return $this->hasOne("App\InvitedUsers", 'id','app_id');
    
    }
}
