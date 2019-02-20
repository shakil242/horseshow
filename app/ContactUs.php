<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContactUs extends Model
{
    protected $fillable = [
        '_token','first_name','last_name','email','message'
    ];
    protected $table = 'contact_us';

}
