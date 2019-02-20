<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class FeedbackMail extends Mailable
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
        $this->questionResponse = json_decode($event->question_response);
        
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $user = getUserNamefromid($this->event->user_id);
        return $this->view('Emails.feedbackmail',['questionResponse'=>$this->questionResponse]) 
                    ->subject('Feedback Mail from '.$user);
    }
}
