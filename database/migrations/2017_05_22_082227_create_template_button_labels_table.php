<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTemplateButtonLabelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('template_button_labels', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('template_id')->unsigned();
            $table->foreign('template_id')->references('id')->on('templates')->onDelete('cascade');

            $table->mediumText('ya_fields')->comment('Your App fields')->default(null)->nullable();
            $table->mediumText('ia_fields')->comment('Invited Assets fields')->default(null)->nullable();
            $table->mediumText('or_fields')->comment('Overall Response fields')->default(null)->nullable();
            $table->mediumText('s_fields')->comment('Spectator fields')->default(null)->nullable();
            
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
        Schema::dropIfExists('template_button_labels');
    }
}
