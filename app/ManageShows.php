<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ManageShows extends Model
{


    protected $dates = ['date_from','date_to'];

    public function schedual()
    {
        return $this->hasMany('App\Schedual', 'show_id', 'id');

    }
    /**
     * Get the InvitedUser record associated with the user. And grouped by asset id
     */
    public function appowner()
    {
        return $this->belongsTo('App\InvitedUser','app_id','id');
    }
    /**
     * Get the trainers in the show.
     */
    public function getTrainers()
    {
        return $this->hasMany('App\ManageShowTrainer', 'manage_show_id', 'id');
    }
    
       /**
     * Get the template record associated with the user. And grouped by asset id
     */
    public function template()
    {
        return $this->belongsTo('App\Template','template_id','id');
    }

    public function forms()
    {
        return $this->hasMany('App\Form', 'id', 'form_id');
    }

    public function user()
    {
        return $this->hasMany('App\User', 'id', 'user_id');
    }

    public function invoiceUser()
    {
        return $this->hasOne('App\User', 'id', 'user_id');
    }

    public function classTypes()
    {
        return $this->hasMany('App\ClassTypePoint', 'show_id', 'id');
    }


    /**
     * Get Invited participants 
     */
    public function participants()
    {
        return $this->hasMany('App\Participant', 'show_id', 'id');
    }
    /**
     * Get Invited participants 
     */
    public function champion()
    {
        return $this->hasMany('App\ChampionDivision', 'show_id', 'id')->orderBy('id','desc');
    }
    /**
     * Get Show Types
     */
    public function types()
    {
        return $this->belongsTo('App\ShowType', 'show_type_id', 'id');
    }

    public function checkForm()
    {
        return $this->belongsTo('App\Form', 'template_id', 'template_id')->where('form_type',SPECTATOR_REGISTRATION);
    }

    public function checkSponsor()
    {
        return $this->belongsTo('App\Form', 'template_id', 'template_id')->where('form_type',SPONSOR_REGISTRATION);
    }

    public function checkRequest()
    {
        if (Auth::check()) {
            $user_id = \Auth::user()->id;
            return $this->belongsTo('App\ManageShowSpectator', 'id', 'show_id')->where('user_id', $user_id);
        }else
        {
            return $this->belongsTo('App\ManageShowSpectator', 'id', 'show_id');
        }


        }
    public function spectators()
    {
        return $this->hasMany('App\ManageShowSpectator', 'show_id', 'id');
    }

    public function sponsorsBilling()
    {
        return $this->hasMany('App\SponsorCategoryBilling', 'show_id', 'id');
    }


    /**
     * Get Prize Claim Listing
     */

    public function prizeClaims()
    {
        return $this->hasMany('App\PrizeClaimForm', 'show_id', 'id');
    }


    public function sponsorCategories()
    {
        return $this->hasMany('App\SponsorCategories', 'show_id', 'id');
    }


    public function prizeWon()
    {
        return $this->hasMany('App\ShowPrizingListing', 'show_id', 'id');
    }

    public function ManageShowRegister()
    {
        return $this->hasMany('App\ManageShowsRegister', 'manage_show_id', 'id');
    }


    public function showStables()
    {
        return $this->hasMany('App\ShowStables', 'show_id', 'id');
    }
    public function stallTypes()
    {
        return $this->hasMany('App\StallTypes', 'show_id', 'id');
    }
    public function division()
    {
        return $this->belongsToMany('App\Asset', 'show_divisions','show_id','division_id')->withPivot('division_id');
    }



}
