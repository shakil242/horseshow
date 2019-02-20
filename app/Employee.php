<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    public function template()
    {

        return $this->hasOne("App\Template", 'id','template_id');

    }


    public function invitedUser()
    {
        return $this->hasOne("App\InvitedUser", 'id','app_id');
    }


    public function permission($email,$template_id)
    {
        $permissionArr = \App\Employee::select('permissions')
            ->where('template_id', $template_id)
            ->where('email', $email)
            ->first();
        return json_decode($permissionArr->permissions);

    }
    public function masterFeedBackExist($id)
    {

        return  \App\SchedulerFeedBacks::select('id')->where('template_id', $id)
            ->count();
    }


}
