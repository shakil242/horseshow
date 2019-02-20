<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableHoreType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('class_type_points', function (Blueprint $table) {
            $table->dropColumn('asset_types');
            $table->integer('class_id')->unsigned();
            $table->foreign('class_id')->references('id')->on('assets')->onDelete('cascade');
            $table->integer('class_type')->unsigned();
            $table->foreign('class_type')->references('id')->on('horse_class_types')->onDelete('cascade');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('class_type_points', function (Blueprint $table) {
            $table->dropColumn('class_id');
            $table->dropColumn('class_type');
        });
    }
}
