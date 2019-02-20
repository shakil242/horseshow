<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnTotalTaxes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('horse_invoices', function (Blueprint $table) {
            $table->float('total_taxis')->nullable();
            $table->timestamp('paid_on')->comment("Invoice Paid on")->default(null)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('horse_invoices', function (Blueprint $table) {
           $table->dropColumn('total_taxis');
            $table->dropColumn('paid_on');

        });
    }
}
