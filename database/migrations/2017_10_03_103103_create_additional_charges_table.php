<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdditionalChargesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('additional_charges', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('app_id')->unsigned();
            $table->foreign('app_id')->references('id')->on('invited_users')->onDelete('cascade');
            $table->integer('template_id')->unsigned();
            $table->string('title')->default(null)->nullable();
            $table->string('description')->default(null)->nullable();
            $table->float('amount')->default(0.00)->nullable();
            $table->integer('required')->comment("0:Not required 1:required")->default(0)->nullable();
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
        Schema::dropIfExists('additional_charges');
    }
}
