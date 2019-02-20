<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddShowIdToAssetScheduler extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('asset_schedulers', function (Blueprint $table) {
            $table->integer('show_id')->comments('for trainer Shows')->nullable();
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
            $table->dropColumn('show_id');
        });
    }
}
