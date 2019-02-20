<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddHorseIdToFeedbackSchedular extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('scheduler_feed_backs', function (Blueprint $table) {
            $table->integer('horse_id')->comment("Asset Id of the horse")->unsigned()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('scheduler_feed_backs', function (Blueprint $table) {
            $table->dropColumn('horse_id');
        });
    }
}
