<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPanaltyToInviteParticipantinvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invite_participantinvoices', function (Blueprint $table) {
            $table->tinyInteger('is_penalty')->comment('Either invoice associated with penalty invoice')->default('0');
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
            $table->dropColumn('is_penalty');
    
        });
    }
}
