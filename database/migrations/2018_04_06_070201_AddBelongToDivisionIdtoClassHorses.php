<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBelongToDivisionIdtoClassHorses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('class_horses', function (Blueprint $table) {
            $table->integer('belong_to_div')->comment("If belong to division, Add division ID.")->default(null)->nullable();
        
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('class_horses', function (Blueprint $table) {
            $table->dropColumn('belong_to_div');
        });
    }
}
