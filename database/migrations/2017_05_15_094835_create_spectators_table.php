<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSpectatorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('spectators', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->default(null)->nullable();
            $table->string('email');
            $table->integer('template_id')->unsigned();
            $table->foreign('template_id')->references('id')->on('templates')->onDelete('cascade');
            $table->integer('invitee_id')->comment('User who invited Spectators')->unsigned();
            $table->foreign('invitee_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('invited_master_template')->default(null)->nullable();
            $table->text('description')->default(null)->nullable();
            $table->Integer('status')->comment('0:Pending 1:Approved 2:Decline')->default(0)->nullable();
            $table->integer('block')->comment('0: Active 1:block')->default(0)->nullable();
            $table->integer('email_confirmation')->comment('0:Didnot checked 1:Approved Once')->default(0)->nullable();
    
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
        Schema::dropIfExists('spectators');
    }
}
