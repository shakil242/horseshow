<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateManageShowsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('manage_shows', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->nullable();
            $table->integer('template_id')->unsigned();
            $table->integer('app_id')->comment('application id for  app owner')->unsigned();
            $table->integer('user_id')->comment('app owner user id')->unsigned();
            $table->string('date_from')->nullable();;
            $table->string('date_to')->nullable();
            $table->string('location')->nullable();

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
        Schema::dropIfExists('manage_shows');
    }
}
