<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class schedualNotes extends Model
{
    protected $table = 'scheduals_notes';
    protected $fillable = [
        'notes'
    ];

    public function feedBack($id)
    {
        return  \App\Form::select('id')
            ->where('template_id', $id)
            ->where('form_type', 3)
            ->count();
    }


    public function user()
    {
        return $this->hasOne('App\User','id','user_id');
    }

    public function schedual()
    {
        return $this->hasOne('App\Schedual','id','schedual_id');
    }

    public function userProfile()
    {
        return $this->hasMany('App\ProfileResponse','user_id','user_id');
    }

    public function templateProfile($template_id)
    {
       $result = $this->userProfile()->where('template_id',$template_id);
       if($result->count()>0)
       {
           return $result->get();
       }
       return false;

    }


    public function restrictions()
    {
        return $this->hasOne('App\SchedulerRestriction','id','restriction_id');
    }


}
