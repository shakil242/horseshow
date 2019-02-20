<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOrderFromToMSOS extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('manage_show_order_supplies', function (Blueprint $table) {
            $table->integer('ordered_as')->comment('1:Trainer , 2:Rider')->default(1)->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('manage_show_order_supplies', function (Blueprint $table) {
            $table->dropColumn('ordered_as');
            //
        });
    }
}
