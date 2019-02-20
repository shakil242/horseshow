<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateInviteAssociateKeyToNullToInviteParticipantinvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invite_participantinvoices', function (Blueprint $table) {
                $table->string('invoiceFormKey')->nullable()->change();
            
        });
        Schema::table('invoices', function (Blueprint $table) {
            $table->string('invite_asociated_key')->nullable()->change();
        
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invite_participantinvoices', function (Blueprint $table) {
            //
        });
    }
}
