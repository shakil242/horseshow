<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChampionDivisionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('champion_divisions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('division_name')->nullable();           
            $table->integer('show_id')->comment("Manage Show id")->nullable()->unsigned();
            $table->foreign('show_id')->references('id')->on('manage_shows')->onDelete('cascade');
            $table->integer('app_id')->comment("Application id on invitation")->nullable()->unsigned();
            $table->integer('champ')->comment("Champion of selected Classes - on table class_horses id ")->nullable()->unsigned();        
            $table->integer('reserve_champ')->comment("Reserve Champion of selected Classes - on table class_horses id ")->nullable()->unsigned();          
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
        Schema::dropIfExists('champion_divisions');
    }
}
