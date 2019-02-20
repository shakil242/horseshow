<?php
    /**
     * This is user modle to control all the model of users.
     *
     * @author Faran Ahmed (Vteams)
     */

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'business_name','email', 'password','user_type','location','id','username'
    ];    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    /**
     * This checks if the user is admin or not.
     *
     * @var array
     */
    public function isAdmin($user){
        if ($user->user_type == 1) {
            return true;
        }else {
            return false;
        }
    }

    public function scheduler()
    {
        return $this->hasMany('App\Schedual','user_id','id');
    }


}
