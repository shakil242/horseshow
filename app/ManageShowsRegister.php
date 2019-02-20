<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ManageShowsRegister extends Model
{
    public function shows()
    {
        return $this->hasMany('App\ManageShows', 'manage_show_id', 'id');

    }
        public function show()
    {
        return $this->hasOne('App\ManageShows', 'id', 'manage_show_id');

    }
    public function user()
    {
        return $this->hasOne('App\User', 'id', 'user_id');

    }

    public function trainerEamil()
    {
        return $this->hasOne('App\User', 'id', 'trainer_id');

    }

    public function trainer()
    {
        return $this->hasOne('App\ManageShowTrainer', 'id', 'trainer_id');

    }
    public function modelShowHorse($horse_id,$assetId)
    {
        return  \App\ClassHorse::where('horse_id', $horse_id)
            ->where('class_id', $assetId)->first();
        
    }

}
