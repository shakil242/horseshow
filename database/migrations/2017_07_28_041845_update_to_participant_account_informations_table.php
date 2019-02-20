<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateToParticipantAccountInformationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('participant_account_informations', function (Blueprint $table) {
            $table->dropForeign('participant_id');
            $table->foreign('participant_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('participant_account_informations', function (Blueprint $table) {
           $table->dropColumn('participant_id');
        });
    }
}
