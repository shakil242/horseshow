<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HorseInvoices extends Model
{
    //division for the horses
    public function bill()
    {
        return $this->hasOne('App\Billing', 'invoice_id', 'id');
    }
}
