<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChampionDivisionClassesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('champion_division_classes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cd_id')->comment("champion division ID: id on champion_divisions table ")->nullable()->unsigned();
            $table->integer('class_id')->comment("Asset id acting as class: id on assets table ")->nullable()->unsigned();
            $table->integer('show_id')->comment("Show id : id on manage_shows table ")->nullable()->unsigned();
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
        Schema::dropIfExists('champion_division_classes');
    }
}
