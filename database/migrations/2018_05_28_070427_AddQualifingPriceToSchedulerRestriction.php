<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddQualifingPriceToSchedulerRestriction extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('scheduler_restrictions', function (Blueprint $table) {
            $table->tinyInteger('qualifing_check')->default(0)->nullable();
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
        Schema::table('scheduler_restrictions', function (Blueprint $table) {
            $table->dropColumn('qualifing_check');
            $table->dropColumn('qualifing_price');
            //
        });
    }
}
