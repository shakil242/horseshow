<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPenaltyDateToParticipantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('participants', function (Blueprint $table) {
            $table->date('penalty_date')->comment('Date on whcih penalty applied')->default(null);
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
        Schema::table('participants', function (Blueprint $table) {
            $table->dropColumn('penalty_date');
            $table->dropColumn('is_penalty');
            
    
        });
    }
}
