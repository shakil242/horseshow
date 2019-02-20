<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSchdualIdToSchedualsNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('scheduals_notes', function (Blueprint $table) {

            $table->integer('schedual_id')->unsigned();
//            $table->foreign('schedual_id')->references('id')->on('scheduals')->onDelete('cascade');
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
            $table->dropColumn('schedual_id');

        });
    }
}
