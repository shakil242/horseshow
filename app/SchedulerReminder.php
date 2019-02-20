<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SchedulerReminder extends Model
{
    protected $table = 'scheduler_reminders';

    protected $fillable = [
        'scheduler_id'
    ];

}
