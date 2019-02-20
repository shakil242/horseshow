<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CombinedClass extends Model
{
    public function SchedulerRestriction(){
        return $this->hasMany("App\SchedulerRestriction", 'asset_id','combined_class_id');
    }
}
