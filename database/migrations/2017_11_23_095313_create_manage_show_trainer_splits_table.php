<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateManageShowTrainerSplitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('manage_show_trainer_splits', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('trainer_user_id')->unsigned();
            $table->foreign('trainer_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('show_id')->comment("Manage Show registration id")->nullable()->unsigned();
            $table->foreign('show_id')->references('id')->on('manage_shows_registers')->onDelete('cascade');
            $table->mediumText('additional_fields')->default(null)->nullable();
            $table->integer('divided_amoung')->default(0)->comments('Divide among number of users')->nullable();
            $table->float('total_amount')->default(0)->comments('Ammount that is Divide among number of users')->nullable();
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
        Schema::dropIfExists('manage_show_trainer_splits');
    }
}
