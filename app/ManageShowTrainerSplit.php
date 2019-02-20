<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ManageShowTrainerSplit extends Model
{
	 //penalty for the horses
    public function ClassHorse()
    {
        return $this->hasOne('App\ClassHorse', 'id', 'class_horses_id');
    }
    // Users
    public function TrainerUser()
    {
        return $this->hasOne('App\User', 'id', 'trainer_user_id');

    }
}
