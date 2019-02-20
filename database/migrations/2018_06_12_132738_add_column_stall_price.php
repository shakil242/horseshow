<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnStallPrice extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('horse_invoices', function (Blueprint $table) {
            $table->float('division_price')->comments("division price For the class")->default(0)->nullable();
            $table->float('stall_price')->comments("stall price For the class")->default(0)->nullable();

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
            $table->dropColumn('division_price');
            $table->dropColumn('stall_price');
        });
    }
}
