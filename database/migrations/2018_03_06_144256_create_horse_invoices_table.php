<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHorseInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('horse_invoices', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('horse_id');
            $table->integer('show_id');
            $table->integer('payer_id')->comments('user who is going to pay the invoice');
            $table->integer('payment_receiver_id')->comments('user who is receiving the invoice');
            $table->integer('payIn_office')->comments('the price user is going to pay in office');
            $table->float('class_price')->comments('Classes commulative price')->nullable();
            $table->float('additional_price')->default('0.00')->nullable();
            $table->float('royalty')->comments('Royalty that has calculated')->default('0.00')->nullable();
            $table->float('app_royalty')->comments('Application actual royalty')->nullable();
            $table->float('prize_won')->comments('Prize won during competition')->default('0.00')->nullable();
            $table->float('split_charges')->comments('split charges')->default('0.00')->nullable();
            $table->float('horse_total_price')->comments('Horse specific invoice total')->default('0.00')->nullable();
            $table->float('total_bill_price')->comments('OverAll invoice')->default('0.00')->nullable();
            $table->tinyInteger('invoice_status')->comments('invoice paid=1,pending=0 ')->default(0);

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
        Schema::dropIfExists('horse_invoices');
    }
}
