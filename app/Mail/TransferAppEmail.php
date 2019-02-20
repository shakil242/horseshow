<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class TransferAppEmail extends Mailable
{
    use Queueable, SerializesModels;
    public $event;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($users,$model)
    {
        $this->users = $users;
        $this->model = $model;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('Emails.transferApp',['users'=>$this->users,'model'=>$this->model]) 
                    ->subject('App Transfering');
    }
}
