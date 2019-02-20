<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class StallTypeNotification extends Mailable
{
    use Queueable, SerializesModels;
    public $event;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($collection,$user,$showTitle)
    {
        $this->collection = $collection;
        $this->user = $user;
        $this->showTitle = $showTitle;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('Emails.show.unPaidStallNotifications',['showTitle'=>$this->showTitle,'user'=>$this->user,'collection'=>$this->collection])
            ->subject('Unpaid Stall for '.$this->showTitle);
    }
}
