<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSchedulerRestrictionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scheduler_restrictions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('scheduler_id')->comment('parent table id')->unsigned();
            $table->integer('asset_id')->comment('parent table id')->unsigned();
            $table->integer('form_id')->comment('parent table id')->unsigned();
            $table->integer('show_id')->comment('parent table id')->unsigned();
            $table->string('restriction')->nullable();
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
        Schema::dropIfExists('scheduler_restrictions');
    }
}
