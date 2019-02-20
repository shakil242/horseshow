<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCombinedClassesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('combined_classes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('combined_class_id')->comment("class that is going to be combined")->unsigned();
            // $table->integer('show_id')->unsigned();
            $table->integer('class_id')->comment("class that is combined")->unsigned();
            $table->text('heights'); 
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
        Schema::dropIfExists('combined_classes');
    }
}
