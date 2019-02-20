<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInviteParticipantinvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invite_participantinvoices', function (Blueprint $table) {
            $table->increments('id');
            $table->string('invoiceFormKey')->comment('this key to unique the forms values');
            $table->integer('invitee_id')->unsigned();
            $table->integer('template_id')->unsigned();
            $table->integer('form_id')->unsigned();
            $table->integer('module_id')->unsigned();
            $table->string('fields');
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
        Schema::dropIfExists('invite_participantinvoices');
    }
}
