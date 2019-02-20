<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectOvEmailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_ov_emails', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('participant_response_id')->comment('participate response id')->unsigned();
            $table->integer('app_owner_id')->comment('App owner user id')->unsigned();
            $table->string('email_to')->comment('Email to email')->nullable();
            $table->string('email_from')->comment('Email to from user')->nullable();
            $table->text('email_body')->comment('Email to from user')->nullable();
            $table->string('email_cc')->comment('Email to user cc')->nullable();
            $table->string('email_subject')->comment('Email to subject')->nullable();
            $table->text('email_attachment')->comment('Email to from user')->nullable();
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
        Schema::dropIfExists('project_ov_emails');
    }
}
