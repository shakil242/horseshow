<?php
    /**
     * This is user modle to control all the model of design of master template.
     *
     * @author Faran Ahmed (Vteams)
     */

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use App\Template;

class TemplateDesign extends Model
{
     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $table = 'template_design';
    protected $fillable = [
        'id', 'template_id', "customizable_app_user" , "logo_image", "background_image", "logo_resolution_width",
        "logo_resolution_hight" ,
        "logo_position",
        "logo_allignment" ,
        "background_color" ,
        "background_image_repeat" ,
        "title_font_size" ,
        "title_font_color",
        "title_font_allignment",
        "field_font_size" ,
        "field_font_color" ,
        "options_font_size" ,
        "options_font_color" 
    ];

}
