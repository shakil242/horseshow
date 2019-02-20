<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ManageShowTrainer extends Model
{
    /**
     * Get the User with the trainer registeration.
     */
    public function user()
    {
        return $this->belongsTo('App\User','user_id','id');
    }
    /**
     * Get the User with the trainer registeration.
     */
    public function showtemp()
    {
        return $this->belongsTo('App\ManageShows','manage_show_id','id');
    }

    
}
