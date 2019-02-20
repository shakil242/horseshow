<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddQualifyingToClassHorses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('class_horses', function (Blueprint $table) {
            $table->tinyInteger('qualifing_check')->comments("0:No, 1: Yes")->default(0)->nullable();
            $table->float('qualifing_price')->comments("Price For qualifing")->default(0)->nullable();
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
            $table->dropColumn('qualifing_check');
            $table->dropColumn('qualifing_price');
        });
    }
}
