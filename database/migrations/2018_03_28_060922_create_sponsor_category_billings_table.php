<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSponsorCategoryBillingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sponsor_category_billings', function (Blueprint $table) {

            $table->increments('id');

            $table->integer('show_id')->unsigned();
            $table->foreign('show_id')->references('id')->on('manage_shows')->onDelete('cascade');

            $table->integer('sender_id')->unsigned();
            $table->foreign('sender_id')->references('id')->on('users')->onDelete('cascade');

            $table->integer('show_owner_id')->unsigned();
            $table->foreign('show_owner_id')->references('id')->on('users')->onDelete('cascade');

            $table->text('category_id')->comments("category selction ids");

            $table->string('stripe_receiver_account_id')->comments("stripe receiver id")->nullalbe();

            $table->string('charge_id')->comments("stripe charge  id")->nullalbe();

            $table->float('amount_transfer')->comments("amount transfer by the sponsors")->nullalbe();

            $table->float('royalty_charges')->nullalbe();

            $table->string('billing_method_type')->nullable();

            $table->float('pay_id')->comments("paypal paying id")->nullalbe();

            $table->tinyInteger('payment_status')->default("0");


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
        Schema::dropIfExists('sponsor_category_billings');
    }
}
