<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNotesIdToSchedulerRemindersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('scheduler_reminders', function (Blueprint $table) {
            $table->integer('notes_id')->unsigned();
            $table->foreign('notes_id')->references('id')->on('scheduals_notes')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('scheduler_reminders', function (Blueprint $table) {
            $table->dropColumn('notes_id');
        });
    }
}

