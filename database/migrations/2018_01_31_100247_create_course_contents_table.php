<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCourseContentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_contents', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('template_id')->comment("Template id for course Content")->nullable()->unsigned();
            $table->integer('form_id')->comment("Form id for course Content")->nullable()->unsigned();
            $table->integer('owner_id')->comment("User Id for this form.")->nullable()->unsigned();
            $table->mediumText('fields')->comment('Json Form fields')->default(null)->nullable();
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
        Schema::dropIfExists('course_contents');
    }
}
