<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShowClassPrice extends Model
{
    protected $fillable=[
    	'class_id','price','show_id'
    ];
     public function assets()
    {
        return $this->hasOne('App\Asset', 'id', 'class_id');
    }
}
