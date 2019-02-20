<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRestrictionIdToSchedualNotes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('scheduals_notes', function (Blueprint $table) {
            $table->integer('restriction_id')->default(0)->nullable();
            $table->tinyInteger('is_multiple_selection')->default(0)->nullable();

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
            $table->dropColumn('restriction_id');
            $table->dropColumn('is_multiple_selection');

        });
    }
}
