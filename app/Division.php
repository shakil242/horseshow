<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Division extends Model
{
     //penalty for the horses
    public function pclass()
    {
        return $this->hasOne('App\Asset', 'id', 'division_id');
    }

    //division for the horses
    public function classhorses()
    {
        return $this->hasOne('App\ClassHorse', 'division_id', 'id');
    }
}
