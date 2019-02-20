<?php
    /**
     * This is extention of class horses table as a change request from client.
     * Now we have to manage the invoice WRT horses. So we have to utilize this table.
     * Will have to add price and other fields
     *
     * @author Faran Ahmed (Vteams)
     */
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddInvoicingToClassHorses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('class_horses', function (Blueprint $table) {
            $table->float('price')->nullable();
            $table->tinyInteger('status')->comment("0:Un paid invoice, 1:Paid Invoice")->default('0');
            $table->timestamp('paid_on')->comment("Invoice Paid on")->default(null)->nullable();
            $table->text('description')->comment("This is the comments form appowner")->nullable();
            $table->mediumText('additional_charges')->comment("saving additional charges")->nullable();
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('class_horses', function (Blueprint $table) {
            $table->dropColumn('price');
            $table->dropColumn('status');
            $table->dropColumn('paid_on');
            $table->dropColumn('description');
            $table->dropColumn('additional_charges');
            //
        });
    }
}
