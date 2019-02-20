<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSponsorCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sponsor_categories', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('show_id')->unsigned();
            $table->foreign('show_id')->references('id')->on('manage_shows')->onDelete('cascade');
            $table->integer('show_owner_id')->unsigned();
            $table->foreign('show_owner_id')->references('id')->on('users')->onDelete('cascade');

            $table->string('category_title')->nullable();
            $table->float('category_price')->nullable();
            $table->text('category_description')->nullable();

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
        Schema::dropIfExists('sponsor_categories');
    }
}
