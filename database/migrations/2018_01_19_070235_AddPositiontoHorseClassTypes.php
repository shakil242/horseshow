<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPositiontoHorseClassTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('horse_class_types', function (Blueprint $table) {
            $table->mediumText('position_fields')->comment('Position and prizing.')->default(null)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('horse_class_types', function (Blueprint $table) {
            $table->dropColumn('position_fields');
        });
    }
}
