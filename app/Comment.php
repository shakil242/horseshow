<?php
/**
 * The CRUD functionality. All the variable and Eloquent function defined here for Comments model.
 *
 * @author Faran Ahmed Khan
 */
namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use Notifiable;

    //protected $table = 'like';
    
    // protected $primaryKey = 'id';
     protected $fillable = [
        'id', 'body','commentable_id','commentable_type','user_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        
    ];
    /**
     * Get the user from this comment
     *
     * @return owners
     */
    public function Commentowners() {
         return $this->belongsTo('App\User', 'user_id', 'id');
    }
    /**
     * Get all of the owning commentable models.
     */
    public function commentable()
    {
        return $this->morphTo();
    }

}
