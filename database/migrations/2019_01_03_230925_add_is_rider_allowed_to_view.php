<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsRiderAllowedToView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('scheduler_feed_backs', function (Blueprint $table) {
            $table->tinyInteger('rider_allowed_to_view')->comments("rider allowed to view judges feedback")->default(0);
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
            $table->dropColumn('rider_allowed_to_view');
        });
    }
}
