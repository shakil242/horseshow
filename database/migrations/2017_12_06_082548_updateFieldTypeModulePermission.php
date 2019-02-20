<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateFieldTypeModulePermission extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('asset_modules', function (Blueprint $table) {
            $table->text('modules_permission')->change();
        });
        Schema::table('participants', function (Blueprint $table) {
            $table->text('modules_permission')->change();
        });
        Schema::table('sub_participants', function (Blueprint $table) {
            $table->text('modules_permission')->change();
        });



    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('asset_modules', function (Blueprint $table) {
            $table->string('modules_permission')->change();
        });
        Schema::table('participants', function (Blueprint $table) {
            $table->string('modules_permission')->change();
        });
        Schema::table('sub_participants', function (Blueprint $table) {
            $table->string('modules_permission')->change();
        });
    }
}
