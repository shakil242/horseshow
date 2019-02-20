<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddInviteAssociativeKeyToClassHorses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('class_horses', function (Blueprint $table) {
            $table->text('invite_asociated_key')->comment('The associative key will define the entry of user')->default(null)->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('class_horses', function (Blueprint $table) {
             $table->dropColumn('invite_asociated_key');
        });
    }
}
