<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class Participants extends Mailable
{
    use Queueable, SerializesModels;
    public $event;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($event,$asset)
    {
        $this->event = $event;
        $this->asset = $asset;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $invitee = getUserNamefromid($this->event->invitee_id);
        return $this->view('Emails.invitationparticipant',['asset'=>$this->asset])
                    ->subject($invitee.' has Invited you on asset');
    }
}
