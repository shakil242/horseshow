<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class InviteUser extends Mailable
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

        $inviteeName = getUserNamefromid($this->event->invited_by);
        $templateName = GetTemplateName($this->event->template_id);

        return $this->view('Emails.invitation',['data'=>$this->event]) 
                    ->subject($inviteeName." invited You to Participate in ".$templateName);
    }
}
