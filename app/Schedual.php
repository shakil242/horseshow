<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Schedual extends Model
{
    protected $table = 'scheduals';
    protected $fillable = [
        'restriction','user_id','name'
    ];

    public function user()
    {
        return $this->hasMany('App\User','id','user_id');
    }

    public function schedulerNotes()
    {
        return $this->hasMany('App\SchedualNotes','id','schedual_id');
    }

    public function form()
    {
        return $this->hasOne('App\Form', 'id', 'form_id');

    }

    public function SchedulerRestriction($scheduler_id,$form_id,$show_id=null)
    {

        if($show_id!='') {
            return \App\SchedulerRestriction::selectRaw('group_concat(asset_id) as asset_id,restriction,id,slots_duration,block_time,block_time_title,is_multiple_selection,is_rider_restricted,score_from,scheduler_key,qualifing_price,qualifing_check')
                ->where('scheduler_id', $scheduler_id)
                ->where('form_id', $form_id)
                ->where('show_id', $show_id)
                ->orderBy('scheduler_key','desc')
                ->groupBy('scheduler_key');
        }else
        {
            return false;

        }
       // return $this->hasMany('App\SchedulerRestriction','scheduler_id','id');
    }

    public function SchedulerSlots($scheduler_id,$form_id,$show_id)
    {

            return \App\SchedulerRestriction::select('slots_duration','asset_id','form_id')
                ->where('show_id', $show_id)
                ->where('scheduler_id', $scheduler_id)
                ->where('form_id', $form_id)
                ->groupBy('asset_id')
                ->get();

        // return $this->hasMany('App\SchedulerRestriction','scheduler_id','id');
    }


}
