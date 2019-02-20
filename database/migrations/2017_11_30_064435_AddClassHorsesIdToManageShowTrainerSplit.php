<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddClassHorsesIdToManageShowTrainerSplit extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('manage_show_trainer_splits', function (Blueprint $table) {
            $table->integer('class_horses_id')->comment("Manage Show registration horses id")->nullable()->unsigned();
            $table->foreign('class_horses_id')->references('id')->on('class_horses')->onDelete('cascade');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('manage_show_trainer_splits', function (Blueprint $table) {
             $table->dropColumn('class_horses_id');
        });
    }
}
