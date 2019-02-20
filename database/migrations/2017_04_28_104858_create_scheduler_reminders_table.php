<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSchedulerRemindersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scheduler_reminders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('scheduler_id')->unsigned();
            $table->foreign('scheduler_id')->references('id')->on('scheduals')->onDelete('cascade');
            $table->dateTime('remind_date')->comment('Reminder date to send email');
            $table->tinyInteger('is_sent')->comment('0 = email not sent, 1 = email sent');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('scheduler_reminders');
    }
}
