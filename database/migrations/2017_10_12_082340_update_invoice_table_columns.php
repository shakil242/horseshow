<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateInvoiceTableColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoices', function (Blueprint $table) {

            $table->dropForeign(['asset_id']);
            $table->dropForeign(['form_id']);
            $table->dropForeign(['user_id']);
//            $table->dropColumn(['user_id']);
//            $table->dropColumn(['invitee_id']);
            $table->integer('show_owner_id')->unsigned();
            $table->integer('show_id')->unsigned()->nullable();
            //$table->integer('invitee_id')->unsigned();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invoices', function (Blueprint $table) {
          $table->dropColumn(['show_owner_id']);
            $table->dropColumn(['show_id']);

        });
    }
}
