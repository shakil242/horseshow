<?php
    /**
     * This is user modle to control all the model of users.
     *
     * @author Faran Ahmed (Vteams)
     */

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use App\Template;

class TemplateAssociate extends Model
{
	use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $table = 'template_associate';
    protected $fillable = [
        'id', 'template_id', 'associated_template_id'
    ];

     /**
     * Get the template name with invited users.
     */
    public function template()
    {
         return $this->belongsTo('App\Template', 'template_id', 'id');
    }

}
