<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class TrainerScratchHorse extends Mailable
{
    use Queueable, SerializesModels;
    public $event;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($showTitle,$trainer,$horse,$class,$user)
    {
        $this->showTitle = $showTitle;
        $this->trainer = $trainer;
        $this->horse = $horse;
        $this->class = $class;
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('Emails.show.trainerScratch',['showTitle'=>$this->showTitle,'trainer'=>$this->trainer,'horse'=>$this->horse,
            'user'=>$this->user,'class'=>$this->class])
            ->subject('Your trainer Scratched horse from '.$this->showTitle);
    }
}
