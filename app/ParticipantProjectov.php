<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ParticipantProjectov extends Model
{
  /**
  * Get the Asset name with object
  */
 public function projectOverview()
 {
      return $this->belongsTo('App\Asset', 'project_overview_id', 'id');
 }
}
