<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PrizeClaimForm extends Model
{
    public function user()
    {
        return $this->hasOne("App\User", 'id','user_id');
    }

    public function show()
    {
        return $this->hasOne("App\ManageShows", 'id','show_id');
    }

}
