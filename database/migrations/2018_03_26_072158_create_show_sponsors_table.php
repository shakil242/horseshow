<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShowSponsorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('show_sponsors', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sponsor_category_id')->nullable();
            $table->integer('show_id')->unsigned();
            $table->foreign('show_id')->references('id')->on('manage_shows')->onDelete('cascade');
            $table->integer('sponsor_user_id')->unsigned();
            $table->foreign('sponsor_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->text('fields');
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
        Schema::dropIfExists('show_sponsors');
    }
}
