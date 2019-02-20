<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateDateTimeFieldToAssetParent extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('asset_parents', function (Blueprint $table) {
            $table->dateTime('created_at')->useCurrent()->change();
            $table->dateTime('updated_at')->useCurrent()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('asset_parents', function (Blueprint $table) {
            //
        });
    }
}
