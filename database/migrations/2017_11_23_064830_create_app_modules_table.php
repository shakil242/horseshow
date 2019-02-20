<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppModulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('app_modules', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('module_id')->comments("module to which it is associated")->unsigned();
            $table->integer('user_id')->comments("app owner who change the logo and title of module")->unsigned();
            $table->integer('template_id')->unsigned();
            $table->mediumText('name')->nullable();
            $table->mediumText('logo')->nullable();
            $table->mediumText('orignal_logo')->nullable();
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
        Schema::dropIfExists('app_modules');
    }
}
