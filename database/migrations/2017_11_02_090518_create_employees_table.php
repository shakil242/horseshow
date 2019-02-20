<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('template_id')->unsigned();
            $table->integer('show_id')->unsigned();
            $table->integer('show_owner_id')->unsigned();
            $table->text('name')->comment('Employee Name')->default(null)->nullable();
            $table->text('email')->comment('Employee email address')->default(null)->nullable();
            $table->text('designation')->comment('Employee Job Designation')->default(null)->nullable();
            $table->string('permissions')->comment('permission to specific Modules')->default(null)->nullable();;
            $table->tinyInteger('status')->comment('0=Inactive,1= active')->default(1)->unsigned();

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
        Schema::dropIfExists('employees');
    }
}
