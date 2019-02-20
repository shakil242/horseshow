<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShowStallUtilitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('show_stall_utilities', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('horse_id')->unsigned();
            $table->string('total_price')->nullable();
            $table->string('divided_amoung')->nullable();
            $table->string('unique_id')->nullable();
            $table->integer('show_id')->unsigned();
            $table->integer('assigne_id')->comment('user_id for person who assigned the value')->unsigned();

            $table->foreign('show_id')->references('id')->on('manage_shows')->onDelete('cascade');
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
        Schema::dropIfExists('show_stall_utilities');
    }
}
