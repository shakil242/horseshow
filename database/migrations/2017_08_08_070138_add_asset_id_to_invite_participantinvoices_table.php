<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAssetIdToInviteParticipantinvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invite_participantinvoices', function (Blueprint $table) {
            $table->integer('asset_id')->comment('Asset id to which invoice associate')->unsigned();
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
        Schema::table('invite_participantinvoices', function (Blueprint $table) {
            $table->dropColumn('asset_id');
        });
    }
}
