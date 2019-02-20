<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateParticipantProjectovsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('participant_projectovs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('participant_invited_id')->comment('participate id')->unsigned();
            $table->integer('project_overview_id')->unsigned();
            $table->foreign('project_overview_id')->references('id')->on('assets')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('participant_projectovs');
    }
}
