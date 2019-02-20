<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSubparticipantIdInParticipantResponseDrafts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('participant_response_drafts', function (Blueprint $table) {
            $table->integer('subparticipant_id')->comment("Sub participant Entity ID")->default(null)->nullable(); 
            $table->integer('subparticipant')->comment("USER ID for SUB participant, If null, No sub participant. If value then, Some subparticipant has given form on someones behave")->default(null)->nullable(); 
            $table->integer('asset_id')->unsigned();
            $table->foreign('asset_id')->references('id')->on('assets')->onDelete('cascade');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('participant_response_drafts', function (Blueprint $table) {
            //
        });
    }
}
