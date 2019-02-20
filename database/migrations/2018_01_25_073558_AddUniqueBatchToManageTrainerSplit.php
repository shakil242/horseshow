<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUniqueBatchToManageTrainerSplit extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('manage_show_trainer_splits', function (Blueprint $table) {
            $table->string('unique_batch')->nullable();
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
            $table->dropColumn('unique_batch');
        });
    }
}
