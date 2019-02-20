<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateManageShowOrderHorsesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('manage_show_order_horses', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('msos_id')->comment('foreign table: manage_show_order_supplie , id is msos id')->default(0)->unsigned();
            $table->integer('horse_id')->comment('foreign table: assets , asset_id is horse id')->default(0)->unsigned();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('manage_show_order_horses');
    }
}
