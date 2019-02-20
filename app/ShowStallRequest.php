<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShowStallRequest extends Model
{


    public function stallType()
    {
        return $this->hasOne("App\StallTypes", 'id','stall_type_id');
    }


    public function user()
    {
        return $this->hasOne('App\User','id','user_id');
    }


    public function stable()
    {
        return $this->hasOne('App\ShowStables','id','approve_stable_id');
    }
    public function stallHorse()
    {
        return $this->hasMany('App\HorseRiderStall','stall_request_id','id');
    }


}
