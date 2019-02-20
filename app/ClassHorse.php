<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClassHorse extends Model
{
    protected $fillable = [
        'class_id','show_id'
    ];
    public function horse()
    {
        return $this->hasOne('App\Asset', 'id', 'horse_id');

    }
    //penalty for the horses
    public function penalty()
    {
        return $this->hasMany('App\ShowScratchPenalty', 'asset_id', 'class_id')->where("type",SCROPT_SCRATCH_PENALITY);
    }
     //penalty for the horses
    public function pclass()
    {
        return $this->hasOne('App\Asset', 'id', 'class_id');
    }
      //penalty for the horses
    public function splitclass()
    {
        return $this->hasOne('App\ShowClassSplit', 'orignal_class_id', 'class_id');
    }
    //combined class for the horses
    public function combinedClass()
    {
        return $this->hasOne('App\CombinedClass', 'class_id', 'class_id');
    }
     //penalty for the horses
    public function positions()
    {
        return $this->hasOne('App\ShowPrizingListing', 'asset_id', 'class_id');
    }
    
    //penalty for the horses
    public function Joining_penalty()
    {
        return $this->hasMany('App\ShowScratchPenalty', 'asset_id', 'class_id')->where("type",SCROPT_CLASS_JOINING_PENALITY);
    }
    //division for the horses
    public function division()
    {
        return $this->hasOne('App\Division', 'id', 'division_id');
    }

    //penalty for the horses
    public function MSR()
    {
        return $this->hasOne('App\ManageShowsRegister', 'id', 'msr_id');
    }
     public function user()
    {
        return $this->hasOne('App\User', 'id', 'user_id');

    }
    //shows
    public function show()
    {
        return $this->hasOne('App\ManageShows', 'id', 'show_id');
    }
    //shows
    public function showsclass()
    {
        return $this->show->belongsTo('App\ClassTypePoint', 'class_id', 'class_id');
    }
    //Getting the show class price
    public function ShowClassPrice(){
        return $this->hasOne("App\ShowClassPrice", 'class_id','class_id')->where('is_division',0);
    }
    public function assets()
    {
        return $this->hasOne('App\Asset', 'id', 'horse_id');
    }
    public function riders()
    {
        return $this->hasOne('App\Asset', 'id', 'horse_rider');
    }
    public function prizeClaim()
    {
        return  $this->hasOne('App\PrizeClaimForm', 'show_id', 'show_id');

    }
    public function prizeClaimCount($horse_id)
    {
        return $this->prizeClaim()->where('horse_id',$horse_id)->count();

    }
    
}
