<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Stripe\Stripe;

class Billing extends Model
{
    public function user()
    {
        return $this->hasOne("App\User", 'id','sender_id');
    }
    
    public function receiver()
    {
        return $this->hasOne("App\User", 'id','participant_id');
    }
    
    
    public function getPayment($id)
    {
        Stripe::setApiKey(config('services.stripe.secret'));
        
        $charge=\Stripe\Charge::retrieve($id);
    
       return $charge['amount'];
    
    }
    
    public function getEventTitle()
    {
        return $this->hasOne("App\Invoice", 'id','invoice_id');
    }

    public function horseinvoice()
    {
        return $this->hasOne("App\HorseInvoices", 'id','invoice_id');
    }
}
