<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddInviteeIdToInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->integer('invitee_id')->comment('User who invited participant')->unsigned();
            $table->foreign('invitee_id')->references('id')->on('users')->onDelete('no action');
         
         
            $table->tinyInteger('is_discard')->comment('1 = discarded, 0 = non-discarded')->default(0)->unsigned();
            $table->integer('response_id')->comment('saved form response id')->unsigned();
            $table->foreign('response_id')->references('id')->on('participant_responses')->onDelete('no action');
    
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
            $table->dropColumn('invitee_id');
            $table->dropColumn('is_discard');
            $table->dropColumn('response_id');
    
        });
    }
}
