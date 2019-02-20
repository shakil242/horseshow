<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubParticipantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_participants', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('participant_id')->unsigned();
            $table->foreign('participant_id')->references('id')->on('participants')->onDelete('cascade');
            $table->integer('user_id')->comment('Participants Users who invited sub-participant')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            
            $table->string('email');
            $table->string('name')->default(null)->nullable();
            $table->text('description')->default(null)->nullable();
            $table->string('allowed_time')->default(null)->nullable();
            $table->string('modules_permission')->default(null)->nullable();
            $table->integer('email_confirmation')->comment('0:Didnot checked 1:Approved Once')->default(0)->nullable();
            $table->Integer('status')->comment('0:Pending 1:Approved 2:Decline')->default(0)->nullable();       
            $table->text('associated_history')->default(null)->nullable();
            $table->integer('block')->comment('0: Active 1:block')->default(0)->nullable();
            $table->string('invite_asociated_key')->comment('Key for invite sent')->nullable();
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
        Schema::dropIfExists('sub_participants');
    }
}
