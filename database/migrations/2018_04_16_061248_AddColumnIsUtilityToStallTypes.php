<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnIsUtilityToStallTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stall_types', function (Blueprint $table) {
            $table->tinyInteger('is_utility')->comments("utility stall types")->default('0');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stall_types', function (Blueprint $table) {
            $table->dropColumn('is_utility');
        });
    }
}
