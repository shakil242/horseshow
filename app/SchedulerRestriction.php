<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SchedulerRestriction extends Model
{
    protected $table = 'scheduler_restrictions';
//    protected $dates =  ['date_from','date_to'];

    public function schedual()
    {
        return $this->hasMany('App\Schedual','id','scheduler_id');
    }



    public function showClasses()
    {
        return $this->hasMany('App\ShowClasses','id','restriction_id');
    }


}
