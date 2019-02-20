<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTypeToManageShowRegister extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('manage_shows_registers', function (Blueprint $table) {
            $table->integer('type')->comment("1:Belongs to show 2:Belongs to Trainer")->default(1)->unsigned();
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
           $table->dropColumn('type');
           
        });
    }
}
