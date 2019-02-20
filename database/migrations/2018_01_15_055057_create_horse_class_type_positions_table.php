<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHorseClassTypePositionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('horse_class_type_positions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('HCT_id')->unsigned();
            $table->foreign('HCT_id')->references('id')->on('horse_class_types')->onDelete('cascade');
            $table->integer('status')->default(1)->comment('1: Active, 0:deactive')->unsigned();
            $table->mediumText('position_fields')->comment('Position and prizing.')->default(null)->nullable();
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
        Schema::dropIfExists('horse_class_type_positions');
    }
}
