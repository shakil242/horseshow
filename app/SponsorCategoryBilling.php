<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SponsorCategoryBilling extends Model
{
    public function user()
    {
        return $this->hasOne("App\User", 'id','sender_id');

    }
    public function sponsor()
    {
        return $this->hasOne("App\ShowSponsors", 'id','sponsor_form_id');

    }
    public function hasCategory(){
        return $this->belongsToMany('App\SponsorCategories', 'sponsor_category_belongs', 'scb_id', 'category_id');
    }


    public function spon(){
        return $this->hasMany('App\SponsorCategoryBelong', 'scb_id', 'id');
    }
}
