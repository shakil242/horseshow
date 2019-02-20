<?php

namespace App;
use Cog\Likeable\Contracts\HasLikes as HasLikesContract;
use Cog\Likeable\Traits\HasLikes;
use Illuminate\Database\Eloquent\Model;

class Post extends Model implements HasLikesContract 
{
	 use HasLikes;
    // public function comments()
    // {
    //     return $this->morphMany('App\Comment', 'commentable');
    // }

     public function comments()
     {
        return $this->hasMany('App\Comment', 'commentable_id', 'id');
     }
     public function postTemplate()
     {
        return $this->hasMany('App\PostTemplate', 'post_id', 'id');
     }
}
