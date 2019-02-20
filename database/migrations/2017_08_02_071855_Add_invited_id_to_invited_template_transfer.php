<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddInvitedIdToInvitedTemplateTransfer extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invite_template_transfers', function (Blueprint $table) {
            $table->integer('invited_id')->comment('Invited_users table id foreign key')->unsigned();
            $table->foreign('invited_id')->references('id')->on('invited_users')->onDelete('cascade');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invite_template_transfers', function (Blueprint $table) {
            $table->dropColumn('invited_id');
        });
    }
}
