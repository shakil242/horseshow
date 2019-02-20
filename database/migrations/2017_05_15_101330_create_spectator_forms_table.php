<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSpectatorFormsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('spectator_forms', function (Blueprint $table) {
            $table->increments('id');
    
            $table->integer('spectator_id')->unsigned();
            $table->foreign('spectator_id')->references('id')->comment('Spectator user Id')->on('spectators')->onDelete('cascade');
    
            $table->integer('form_id')->comment('Form to which spectators user has access')->unsigned();
            $table->foreign('form_id')->references('id')->on('forms')->onDelete('cascade');
    
    
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
        Schema::dropIfExists('spectator_forms');
    }
}
