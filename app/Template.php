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

class Template extends Model
{
	use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $hidden = array('pivot');
    protected $fillable = [
        'name', 'royalty', 'value','module_launch_id','form_launch_id','invoice_to_event','invoice_to_asset'
    ];
	public function associated_template() {
	    return $this->belongsToMany('App\Template', 'template_associate', 'template_id', 'associated_template_id');
	}

	public function masters_template() {
	    return $this->belongsToMany('App\Template', 'template_associate', 'associated_template_id', 'template_id');
	}
    
    /**
     * @return array
     */
    public function setCheckBoxValues($request)
    {
    
        if(!isset($request->invoice_to_asset))
        {
            $this->invoice_to_asset = '0';
        }
        if(!isset($request->invoice_to_event))
        {
            $this->invoice_to_event = '0';
        }
        
       
    }
    public function CourseOutline() {
        return $this->hasOne('App\Form', 'template_id',  'id');
    }


    public function getCourseOutline() {
        return $this->CourseOutline()->where('form_type',F_COURSE_CONTENT)->first();
    }
}
