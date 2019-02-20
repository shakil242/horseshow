<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDragOutTimeToSchedulerRestriction extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('scheduler_restrictions', function (Blueprint $table) {
            $table->dateTime('date_from')->nullable();
            $table->dateTime('date_to')->nullable();
            $table->string('block_time')->nullable();
            $table->string('block_time_title')->nullable();



        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('scheduler_restrictions', function (Blueprint $table) {
            $table->dropColumn('date_from');
            $table->dropColumn('date_to');
           $table->dropColumn('block_time');
            $table->dropColumn('block_time_title');

        });
    }
}
