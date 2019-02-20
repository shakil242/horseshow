<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFormsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('forms', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('template_id')->unsigned();
            $table->foreign('template_id')->references('id')->on('templates')->onDelete('cascade');
            //information
            $table->string('name')->default(null)->nullable();
            $table->Integer('linkto')->default(null)->nullable();
            $table->Integer('form_type')->comment('1:Data Inputs 2:Assets 3:Feedback 4:Billing')->default(null)->nullable();
            $table->Integer('invoice')->default(null)->nullable();
            $table->tinyInteger('scheduler')->default(0)->comment('0:Not Checked 1:Checked')->nullable(); 
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
        Schema::dropIfExists('forms');
    }
}
