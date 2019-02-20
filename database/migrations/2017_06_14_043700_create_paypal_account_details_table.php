<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaypalAccountDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paypal_account_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('userId')->comment('User who send created paypal Account')->unsigned();
            $table->string('paypalEmail');
            $table->string('correlationId');
            $table->string('accountId');
            $table->string('createAccountKey');
            $table->string('redirectURL');
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
        Schema::dropIfExists('paypal_account_details');
    }
}
