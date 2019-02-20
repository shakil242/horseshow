<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddReminderToSchedualsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('scheduals', function (Blueprint $table) {
            $table->tinyInteger('isReminder')->default(0);
            $table->mediumText('reminderDays')->nullable();
            $table->integer('reminderHours')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('scheduals', function (Blueprint $table) {
            $table->dropColumn('isReminder');
            $table->dropColumn('reminderDays');
            $table->dropColumn('reminderHours');
        });
    }
}
