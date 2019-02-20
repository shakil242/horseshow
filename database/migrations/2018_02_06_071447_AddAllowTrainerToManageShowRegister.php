<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAllowTrainerToManageShowRegister extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('manage_shows_registers', function (Blueprint $table) {
            $table->integer('allow_trainer_register')->comment("0:Not Allowed to register by trainer 1:Allowed to registered")->default(0)->unsigned();
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
           $table->dropColumn('allow_trainer_register');
        });
    }
}
