<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

 class EventModel extends Model implements \MaddHatter\LaravelFullcalendar\Event
 {

    protected $table = 'scheduals';
    protected $fillable = [
        'id', 'template_id', 'associated_template_id'
    ];
     protected $dates = ['start', 'end'];

     /**
      * Get the event's id number
      *
      * @return int
      */
     public function getId() {
 		return $this->id;
 	}

     /**
      * Get the event's title
      *
      * @return string
      */
     public function getTitle()
     {
         return $this->name;
     }

     /**
      * Is it an all day event?
      *
      * @return bool
      */
     public function isAllDay()
     {
         return (bool)$this->restriction;
     }

     /**
      * Get the start time
      *
      * @return DateTime
      */
     public function getStart()
     {
         return $this->restriction;
     }

     /**
      * Get the end time
      *
      * @return DateTime
      */
     public function getEnd()
     {
         return $this->restriction;
     }




     public function addEvent()
    {
             return $this->restriction;
    }

 }