<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsAssignedToHorseToShowStallRequest extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('show_stall_requests', function (Blueprint $table) {
            $table->string('assigned_to_horse_uid')->comments("if Assigned. then add Unique ID. else 0")->default(null)->nullable();
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('show_stall_requests', function (Blueprint $table) {
            $table->dropColumn('assigned_to_horse_uid');
        });
    }
}
