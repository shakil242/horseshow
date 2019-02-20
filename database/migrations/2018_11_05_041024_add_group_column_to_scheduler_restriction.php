<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGroupColumnToSchedulerRestriction extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('scheduler_restrictions', function (Blueprint $table) {
            $table->tinyInteger('is_group')->comments('1=group (all the classes in this selection can not book ride at same time),0=non-group(default)')->default(0)->nullable();
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
            $table->dropColumn('is_group');
        });
    }
}
