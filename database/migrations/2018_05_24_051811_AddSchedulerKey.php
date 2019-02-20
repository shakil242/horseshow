<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSchedulerKey extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('scheduler_restrictions', function (Blueprint $table) {
            $table->string('scheduler_key')->comments("in order to mange entries for single section of classes")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('scheduler_restrictions', function (Blueprint $table) {
           $table->dropColumn('schedulerKey');
        });
    }
}
