<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class TrainerParticipate extends Mailable
{
    use Queueable, SerializesModels;
    public $event;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($showTitle,$trainer,$array_asset,$user)
    {
        $this->showTitle = $showTitle;
        $this->trainer = $trainer;
        $this->array_asset = $array_asset;
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('Emails.show.trainerParticipate',['showTitle'=>$this->showTitle,'trainer'=>$this->trainer,'array_asset'=>$this->array_asset,
            'user'=>$this->user])
            ->subject('Trainer has Registered you for '.$this->showTitle);
    }
}
