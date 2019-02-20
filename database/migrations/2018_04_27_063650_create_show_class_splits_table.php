<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShowClassSplitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('show_class_splits', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('orignal_class_id')->comment('Asset Id for class')->unsigned();
            $table->integer('split_class_id')->comment('Asset Id for new class that has been splited')->unsigned();
            //$table->integer('show_id')->comment('show which it belongs to')->unsigned();
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
        Schema::dropIfExists('show_class_splits');
    }
}
