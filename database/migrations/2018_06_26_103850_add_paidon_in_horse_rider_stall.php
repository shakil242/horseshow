<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPaidonInHorseRiderStall extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('horses_riders_stalls', function (Blueprint $table) {
            $table->timestamp('paid_on')->comment("Invoice Paid on")->default(null)->nullable();
            //
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('horses_riders_stalls', function (Blueprint $table) {
            $table->dropColumn('paid_on');
            //
        });
    }
}
