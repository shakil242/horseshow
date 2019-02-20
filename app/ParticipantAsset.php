<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class participantAsset extends Model
{
    protected $table = 'participant_assets';

    public function forms()
    {

        return $this->hasOne("App\Form", 'id','form_id');

    }


}
