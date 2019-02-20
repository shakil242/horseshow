<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnInvoiceStatusDivisions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('divisions', function (Blueprint $table) {
            $table->tinyInteger('invoice_status')->comments("inovice paid = 1, not paid =0")->default(0)->nullable();
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
        Schema::table('divisions', function (Blueprint $table) {
            $table->dropColumn('invoice_status');
            $table->dropColumn('paid_on');
            
        });
    }
}
