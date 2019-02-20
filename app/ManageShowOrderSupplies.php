<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ManageShowOrderSupplies extends Model
{

    public function trainer()
    {
        return $this->hasOne('App\User', 'id', 'trainer_user_id');
    }

    public function show()
    {
        return $this->hasOne('App\ManageShows', 'id', 'show_id');
    }

    public function orderSupplie()
    {
        return $this->hasMany('App\ManageShowOrderHorse', 'msos_id', 'id');
    }

}
