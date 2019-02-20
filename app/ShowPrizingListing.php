<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShowPrizingListing extends Model
{
	//Shows that are listed associated with show prizing.
    public function shows()
    {
        return $this->hasOne("App\ManageShows", 'id','show_id');
    }
    public function assets()
    {
        return $this->belongsTo("App\Asset", 'asset_id','id');
    }

    public function prizeClaim($show_id,$horse_id)
    {

       return PrizeClaimForm::where('show_id',$show_id)->where('horse_id',$horse_id)->first();

    }


}
