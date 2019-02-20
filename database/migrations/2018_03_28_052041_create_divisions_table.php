<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDivisionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('divisions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('division_id')->comment("Asset id which is held as division.");
            $table->integer('show_id');
            $table->integer('horse_id');
            $table->integer('user_id');
            $table->float('price')->nullable();
            $table->text('invite_key');
            //$table->text('comment')->comment("This is the comments form appowner")->nullable();
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
        Schema::dropIfExists('divisions');
    }
}
