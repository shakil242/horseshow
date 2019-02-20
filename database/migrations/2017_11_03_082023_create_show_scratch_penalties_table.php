<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShowScratchPenaltiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('show_scratch_penalties', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('template_id')->unsigned();
            $table->integer('owner_id')->unsigned();
            $table->text('penality')->comment('$ penality')->default(null)->nullable();
            $table->integer('asset_id')->comment('assets id for classes selected to add penality')->unsigned();
            $table->date('date_from')->default(null)->nullable();
            $table->date('date_to')->default(null)->nullable();
            $table->integer('type')->unsigned()->comment('1:Scratch penality 2:Class Joining Date penality')->default(1)->nullable();
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
        Schema::dropIfExists('show_scratch_penalties');
    }
}
