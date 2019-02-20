<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HorseOwner extends Model
{
    protected $fillable = [
        'owner_id',"horse_id"
    ];
}
