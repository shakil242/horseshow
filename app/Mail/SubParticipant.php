<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SubParticipant extends Mailable
{
    use Queueable, SerializesModels;
    public $event;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($event)
    {
        $this->event = $event;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $invitee = getUserNamefromid($this->event->user_id);
        return $this->view('Emails.invitationSubParticipant',['asset'=>$this->event->asset_id])
                    ->subject($invitee.' has Invited you on asset');
    }
}
