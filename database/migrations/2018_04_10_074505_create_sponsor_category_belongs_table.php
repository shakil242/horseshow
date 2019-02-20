<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSponsorCategoryBelongsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sponsor_category_belongs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('scb_id')->comment("sponsor_category_billings id")->unsigned();
            $table->integer('category_id')->comment("sponsor_categories id")->unsigned();
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
        Schema::dropIfExists('sponsor_category_belongs');
    }
}
