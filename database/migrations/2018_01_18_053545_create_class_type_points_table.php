<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClassTypePointsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('class_type_points', function (Blueprint $table) {
            $table->increments('id');
            $table->mediumText('asset_types')->comment('horse_class_types id and assets id')->default(null)->nullable();
            $table->integer('show_id')->comment("Manage_Show_id")->unsigned();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('class_type_points');
    }
}
