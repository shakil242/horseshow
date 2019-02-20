<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnSponsorOnInvoice extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sponsor_categories', function (Blueprint $table) {
            $table->tinyInteger('sponsor_on_invoice')->comments("show sponsor name on invoice or not-- show=1--not show=0")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sponsor_categories', function (Blueprint $table) {
            $table->dropColumn('sponsor_on_invoice');
        });
    }
}
