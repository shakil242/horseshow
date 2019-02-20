<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TemplateDesign extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('template_design', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('template_id')->unsigned();
            $table->foreign('template_id')->references('id')->on('templates')->onDelete('cascade');
            //logo
            $table->string('logo_image')->default(null)->nullable();
            $table->Integer('logo_resolution_width')->default(50)->nullable();
            $table->Integer('logo_resolution_hight')->default(50)->nullable();
            $table->tinyInteger('logo_position')->default(1)->comment('1:Top 2:Bottom')->nullable(); 
            $table->tinyInteger('logo_allignment')->default(1)->comment('1:Left 2:Center 3:Right')->nullable(); 
            //background
            $table->string('background_image')->default(null)->nullable();
            $table->string('background_color')->default("#ffffff")->nullable();
            $table->string('background_image_repeat')->default(null)->nullable();
            //title
            $table->tinyInteger('title_font_size')->default(14)->comment('12:Small 14:Medium 18:Large')->nullable();
            $table->string('title_font_color')->default("#000000")->nullable();
            $table->tinyInteger('title_font_allignment')->default(1)->comment('1:Left 2:Center 3:Right')->nullable();
            //field
            $table->string('field_font_size')->default(14)->comment('12:Small 14:Medium 18:Large')->nullable();
            $table->string('field_font_color')->default("#000000")->nullable();
            //Options
            $table->string('options_font_size')->default(14)->comment('12:Small 14:Medium 18:Large')->nullable();
            $table->string('options_font_color')->default("#000000")->nullable();

            $table->Integer('customizable_app_user')->default(0)->comment('0:Not Customizable 1:Customizable')->nullable();

            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

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
        Schema::drop('template_design');

    }
}
