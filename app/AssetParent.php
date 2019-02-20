<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AssetParent extends Model
{
    public function assets()
    {

        return $this->hasMany("App\Asset", 'asset_id','id');

    }
    public function assetsScheduler()
    {

        return $this->hasMany("App\SchedulerRestriction", 'asset_id','asset_id');

    }

    public function assetsParent()
    {

        return $this->hasMany("App\Asset", 'parent_id','id');

    }

}
