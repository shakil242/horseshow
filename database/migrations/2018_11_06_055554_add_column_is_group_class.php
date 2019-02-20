<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnIsGroupClass extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('scheduals_notes', function (Blueprint $table) {
            $table->tinyInteger('other_group_Class')->comments('1=the classes are belongs to the riding class,0=default booking class')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('scheduals_notes', function (Blueprint $table) {
            $table->dropColumn('other_group_Class');
        });
    }
}
