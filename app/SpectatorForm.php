<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SpectatorForm extends Model
{
    
    protected $fillable = [
        'form_id',
        'spectator_id',
    
    ];
    
    public function spectators()
    {
        return $this->hasOne('App\Spectators');
    }
}
