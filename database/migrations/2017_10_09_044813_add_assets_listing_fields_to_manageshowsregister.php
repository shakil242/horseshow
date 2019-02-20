<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAssetsListingFieldsToManageshowsregister extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('manage_shows_registers', function (Blueprint $table) {
            $table->mediumText('assets_fields')->comment('list of assets that are displayed')->default(null)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('manage_shows_registers', function (Blueprint $table) {
            //
        });
    }
}
