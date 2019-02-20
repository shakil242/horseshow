<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnIsMultipleSelection extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('asset_schedulers', function (Blueprint $table) {
            $table->tinyInteger('is_multiple_selection')->comments("in order to verify group class")->default(0)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('asset_schedulers', function (Blueprint $table) {
            $table->dropColumn('is_multiple_selection');
        });
    }
}
