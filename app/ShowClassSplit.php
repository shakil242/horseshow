<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShowClassSplit extends Model
{
    //penalty for the horses
    public function splitedclass()
    {
        return $this->hasOne('App\Asset', 'id', 'split_class_id');
    }
}
