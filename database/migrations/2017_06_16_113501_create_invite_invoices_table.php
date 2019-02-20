<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInviteInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invite_invoices', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('invitee_id')->comment('User who invited participant')->unsigned();
            $table->foreign('invitee_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('participant_email')->comment('User who have to pay invoice');
            $table->integer('template_id')->unsigned();
            $table->foreign('template_id')->references('id')->on('templates')->onDelete('cascade');
            $table->integer('module_id')->unsigned();
            $table->foreign('module_id')->references('id')->on('modules')->onDelete('cascade');
            $table->string('asset_id');
            $table->string('invoiceFormKey')->comment('Participant response associated Form Id');
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
        Schema::dropIfExists('invite_invoices');
    }
}
