<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSubparticipantInParticipantResponses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('participant_responses', function (Blueprint $table) {
            $table->integer('subparticipant')->comment("USER ID for SUB participant, If null, No sub participant. If value then, Some subparticipant has given form on someones behave")->default(null)->nullable(); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('participant_responses', function (Blueprint $table) {
            //
        });
    }
}
