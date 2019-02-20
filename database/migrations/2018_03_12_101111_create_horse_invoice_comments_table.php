<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHorseInvoiceCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('horse_invoice_comments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('horse_id');
            $table->integer('show_id');
            $table->text('comment')->comment("This is the comments form appowner")->nullable();
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
        Schema::dropIfExists('horse_invoice_comments');
    }
}
