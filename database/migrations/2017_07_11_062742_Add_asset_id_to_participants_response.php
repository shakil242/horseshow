<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAssetIdToParticipantsResponse extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('participant_responses', function (Blueprint $table) {
            $table->integer('asset_id')->comment('This Asset Id is saved only if the response is from App owner himslef on his own app')->default(null)->nullable()->unsigned();
            $table->foreign('asset_id')->references('id')->on('assets')->onDelete('cascade');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('participant_responses', function (Blueprint $table) {
           $table->dropColumn('asset_id');
        });
    }
}
