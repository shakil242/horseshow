<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsRiderRestricted extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('scheduler_restrictions', function (Blueprint $table) {
           $table->tinyInteger('is_rider_restricted')->comments('for value 1 user will be restrcited to book his rider for this class')->default(0);
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
           $table->dropColumn('is_rider_restricted');
        });
    }
}
