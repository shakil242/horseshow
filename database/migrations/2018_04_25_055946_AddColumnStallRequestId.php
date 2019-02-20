<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnStallRequestId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('horses_riders_stalls', function (Blueprint $table) {
            $table->integer('stall_request_id')->unsigned();
            $table->foreign('stall_request_id')->references('id')->on('show_stall_requests')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('horses_riders_stalls', function (Blueprint $table) {
            $table->dropColumn('stall_request_id');
        });
    }
}
