<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShowPayInOfficesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('show_pay_in_offices', function (Blueprint $table) {

            $table->increments('id');

            $table->integer('horse_id')->unsigned();
            $table->foreign('horse_id')->references('id')->on('assets')->onDelete('cascade');

            $table->integer('show_id')->unsigned();
            $table->foreign('show_id')->references('id')->on('manage_shows')->onDelete('cascade');

            $table->integer('invoice_status')->default(0)->unsigned();

            $table->timestamp('paid_on')->comment("Invoice Paid on")->default(null)->nullable();

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
        Schema::dropIfExists('show_pay_in_offices');
    }
}
