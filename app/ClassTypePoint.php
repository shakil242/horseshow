<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClassTypePoint extends Model
{
   //shows
    public function type()
    {
        return $this->hasOne('App\HorseClassType', 'id', 'class_type');
    }
    //show Classes
    public function Sclasses()
    {
        return $this->hasOne('App\Asset', 'id', 'class_id');
    }
}
