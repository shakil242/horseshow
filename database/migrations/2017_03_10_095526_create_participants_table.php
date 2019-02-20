<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateParticipantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('participants', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('template_id')->unsigned();
            $table->foreign('template_id')->references('id')->on('templates')->onDelete('cascade');
            $table->integer('invitee_id')->comment('User who invited participant')->unsigned();
            $table->foreign('invitee_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('asset_id')->unsigned();
            $table->foreign('asset_id')->references('id')->on('assets')->onDelete('cascade');
            
            $table->string('location')->default(null)->nullable();
            $table->string('email');
            $table->string('name')->default(null)->nullable();
            $table->string('latitude')->default(null)->nullable();
            $table->string('longitude')->default(null)->nullable();
            $table->string('place_id')->default(null)->nullable();
            $table->text('description')->default(null)->nullable();
            $table->string('allowed_time')->default(null)->nullable();
            $table->string('modules_permission')->default(null)->nullable();
            $table->integer('email_confirmation')->comment('0:Didnot checked 1:Approved Once')->default(0)->nullable();
            $table->Integer('status')->comment('0:Pending 1:Approved 2:Decline')->default(0)->nullable();       
            
            $table->integer('invited_master_template')->default(null)->nullable();
            
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
        Schema::dropIfExists('participants');
    }
}
