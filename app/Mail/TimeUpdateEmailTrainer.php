<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class TimeUpdateEmailTrainer extends Mailable
{
    use Queueable, SerializesModels;
    public $event;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($showTitle,$trainer,$time_slot,$timeFrom,$timeTo,$reminderMinutes,$reason,$asset_title,$horse_title)
    {
        $this->showTitle = $showTitle;
        $this->trainer = $trainer;
        $this->timeFrom = $timeFrom;
        $this->timeTo = $timeTo;
        $this->reminderMinutes = $reminderMinutes;
        $this->reason = $reason;
        $this->asset_title = $asset_title;
        $this->time_slot = $time_slot;
        $this->horse_title = $horse_title;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('Emails.timeUpdateEmailTrainer',['showTitle'=>$this->showTitle,'trainer'=>$this->trainer,'timeFrom'=>$this->timeFrom,
            'timeTo'=>$this->timeTo,'reminderMinutes'=>$this->reminderMinutes,'reason'=>$this->reason,'asset_title'=>$this->asset_title,'time_slot'=>$this->time_slot,'horse_title'=>$this->horse_title])
            ->subject('Time Update Alert for '.$this->showTitle);
    }
}
