<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTimeZoneToManageShows extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('manage_shows', function (Blueprint $table) {
           $table->string('time_zone')->comments("in order to send reminder we need time zone of the show")->nullabla();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('manage_shows', function (Blueprint $table) {
            $table->dropColumn('time_zone');
        });
    }
}
