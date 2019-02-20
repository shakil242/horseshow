<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    /**
     * @return array
     */
    protected $fillable = [
        'owner_id'
    ];


    public function assetModules()
    {
        return $this->hasOne("App\AssetModules", 'id','asset_id');
    
    }
    //Get Asset Template
    public function template()
    {
        return $this->hasOne("App\Template", 'id','template_id');
    
    }
    //Get Asset Template
    public function splitOrignalClass()
    {
        return $this->hasOne("App\ShowClassSplit", 'split_class_id','id');
    
    }
    //Get Asset Owner
    public function owner()
    {
        return $this->hasOne("App\User", 'id','user_id');
    
    }

    public function assetParent()
    {
        return $this->belongsToMany('App\Asset', 'asset_parents',
            'asset_id','parent_id');
    }


    public function subAssets()
    {
        return $this->belongsToMany('App\Asset', 'asset_parents',
            'parent_id','asset_id');
    }

    public function SchedulerRestriction(){
        return $this->hasMany("App\SchedulerRestriction", 'asset_id','id');
    }
    
    public function showPrizingListing(){
        return $this->hasMany("App\ShowPrizingListing", 'asset_id','id');
    }
    
    public function showPrizing(){
        return $this->hasOne("App\ShowPrizing", 'asset_id','id');
    }
    public function ShowClassPrice(){
        return $this->hasOne("App\ShowClassPrice", 'class_id','id')->where('is_division',0);
    }
    public function ShowAssetInvoice(){
        return $this->hasOne("App\ShowClassPrice", 'class_id','id')->where('is_division',1);
    }
    // public function ShowAssetInvoice(){
    //     return $this->hasOne("App\ShowAssetInvoice", 'asset_id','id');
    // }

        //penalty for the horses
    public function Joining_penalty()
    {
        return $this->hasMany('App\ShowScratchPenalty', 'asset_id', 'id')->where("type",SCROPT_CLASS_JOINING_PENALITY);
    }

    public function show()
    {
        return $this->belongsToMany('App\ManageShows', 'show_divisions','division_id','show_id')->withPivot('division_id');
    }


    public function classType()
    {
        return $this->hasOne("App\ClassTypes", 'id','class_type');

    }


    public function horse_owner(){
        return $this->hasMany("App\HorseOwner", 'horse_id','id');
    }


    public function OwnerUpdate()
    {
        return $this->belongsToMany('App\Asset', 'horse_owners',
            'horse_id','owner_id');
    }



}
