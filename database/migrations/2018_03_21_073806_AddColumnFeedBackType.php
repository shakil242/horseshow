<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnFeedBackType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('scheduler_feed_backs', function (Blueprint $table) {
            $table->tinyInteger('feed_back_type')->comments("form type =judges feedback");
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
           $table->dropColumn('feed_back_type');
        });
    }
}
