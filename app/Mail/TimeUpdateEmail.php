<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class TimeUpdateEmail extends Mailable
{
    use Queueable, SerializesModels;
    public $event;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($time_slot,$timeFrom,$timeTo,$reminderMinutes,$reason,$user,$asset_title,$horse_title)
    {
        $this->time_slot = $time_slot;
        $this->timeFrom = $timeFrom;
        $this->timeTo = $timeTo;
        $this->reminderMinutes = $reminderMinutes;
        $this->user = $user;
        $this->reason = $reason;
        $this->asset_title =$asset_title;
        $this->horse_title =$horse_title;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('Emails.timeUpdateEmail',['time_slot'=>$this->time_slot,'time_slot'=>$this->time_slot,'timeFrom'=>$this->timeFrom,
            'timeTo'=>$this->timeTo,'reminderMinutes'=>$this->reminderMinutes,'user'=>$this->user,'reason'=>$this->reason,'asset_title'=>$this->asset_title,'horse_title'=>$this->horse_title])
            ->subject('Time Update Alert');
    }
}
