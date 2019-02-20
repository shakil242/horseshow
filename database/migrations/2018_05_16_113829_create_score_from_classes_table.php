<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScoreFromClassesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('score_from_classes', function (Blueprint $table) {

            $table->increments('id');

            $table->integer('class_id')->unsigned();
            $table->foreign('class_id')->references('id')->on('assets')->onDelete('cascade');

            $table->integer('score_from_class')->unsigned();
            $table->foreign('score_from_class')->references('id')->on('assets')->onDelete('cascade');

            $table->integer('form_id')->default(0)->unsigned();
            $table->integer('restriction_id')->default(0)->nullable();

            $table->integer('show_id')->unsigned();
            $table->foreign('show_id')->references('id')->on('manage_shows')->onDelete('cascade');

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
        Schema::dropIfExists('score_from_classes');
    }
}
