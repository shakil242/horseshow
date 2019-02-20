<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateManageShowSpectatorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('manage_show_spectators', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('template_id')->comment("Manage Show template id")->nullable()->unsigned();
            $table->foreign('template_id')->references('id')->on('templates')->onDelete('cascade');
            $table->integer('show_id')->comment("Manage Show id")->nullable()->unsigned();
            $table->foreign('show_id')->references('id')->on('manage_shows')->onDelete('cascade');
            $table->integer('app_id')->comment("Application id on invitation")->nullable()->unsigned();

            $table->integer('user_id')->comment("participating user id")->nullable()->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->integer('form_id')->comment("participating form id")->nullable()->unsigned();
            $table->foreign('form_id')->references('id')->on('forms')->onDelete('cascade');

            $table->string('fields')->nullable();

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
        Schema::dropIfExists('manage_show_spectators');
    }
}
