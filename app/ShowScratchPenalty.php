<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShowScratchPenalty extends Model
{
    public function assets()
    {
        return $this->hasOne('App\Asset', 'id', 'asset_id');
    }
}
