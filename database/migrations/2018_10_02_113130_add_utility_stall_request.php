<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUtilityStallRequest extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('show_stall_utilities', function (Blueprint $table) {
            $table->text('utility_stall_request_id')->comments("Approved utility stall request id's")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('show_stall_utilities', function (Blueprint $table) {
            $table->dropColumn('utility_stall_request_id');
        });
    }
}
