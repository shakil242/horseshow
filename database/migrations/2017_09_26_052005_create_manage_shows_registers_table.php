<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateManageShowsRegistersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('manage_shows_registers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('manage_show_id')->nullable()->unsigned();
            $table->foreign('manage_show_id')->references('id')->on('manage_shows')->onDelete('cascade');
            $table->tinyInteger("status")->comment("0:Incomplete 1:Complete")->default(0)->nullable();
            //information
            $table->mediumText('fields')->default(null)->nullable();
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
        Schema::dropIfExists('manage_shows_registers');
    }
}
