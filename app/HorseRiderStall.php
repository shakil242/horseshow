<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HorseRiderStall extends Model
{
   protected $table = 'horses_riders_stalls';

    //division for the horses
    public function stalls()
    {
        return $this->hasOne('App\StallTypes', 'id', 'stall_type_id');
    }
     //division for the horses
    public function horse()
    {
        return $this->hasOne('App\Asset', 'id', 'horse_id');
    }
    //division for the horses
    public function stallrequest()
    {
        return $this->belongsTo('App\ShowStallRequest', 'stall_request_id', 'id');
    }

    
}
