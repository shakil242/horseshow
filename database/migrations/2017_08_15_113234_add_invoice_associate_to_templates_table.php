<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddInvoiceAssociateToTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('templates', function (Blueprint $table) {
            $table->tinyInteger('invoice_to_event')->comment('Invoice associated with event forms')->default(0)->nullable();
            $table->tinyInteger('invoice_to_asset')->comment('Invoice associated with Asset Modules')->default(0)->nullable();
    
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('templates', function (Blueprint $table) {
            $table->dropColumn('invoice_to_event');
            $table->dropColumn('invoice_to_asset');
        });
    }
}
