<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInviteTemplatenamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invite_templatenames', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('invited_user_id')->unsigned();
            $table->foreign('invited_user_id')->references('id')->on('invited_users')->onDelete('cascade');
            $table->string('name')->comment("Name added by invited user for the master template")->default(0)->nullable();
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
        Schema::dropIfExists('invite_templatenames');
    }
}
