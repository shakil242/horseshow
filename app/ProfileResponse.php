<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProfileResponse extends Model
{

    public function forms()
    {
        return $this->hasOne('App\Form','id','form_id');
    }


}
