<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBillingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('billings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('invoice_id')->unsigned();
            $table->foreign('invoice_id')->references('id')->on('invoices')->onDelete('cascade');
            $table->integer('sender_id')->comment('User who send invoice')->unsigned();
            $table->string('stripe_receiver_account_id')->comment('stripe account information');
            $table->string('stripe_sender_account_id')->comment('stripe sender account information');
            $table->float('amount_transfer')->comment('the amount which transfer during billing')->unsigned();
    
            $table->string('charge_id')->comment('user billing charge id');
    
    
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
        Schema::dropIfExists('billings');
    }
}
