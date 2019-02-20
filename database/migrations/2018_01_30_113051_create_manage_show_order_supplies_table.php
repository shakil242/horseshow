<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateManageShowOrderSuppliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('manage_show_order_supplies', function (Blueprint $table) {

                $table->increments('id');
                $table->integer('trainer_user_id')->unsigned();
                $table->foreign('trainer_user_id')->references('id')->on('users')->onDelete('cascade');
                $table->integer('show_owner_id')->unsigned();
                $table->foreign('show_owner_id')->references('id')->on('users')->onDelete('cascade');

                $table->integer('template_id')->comment("Manage Show template id")->nullable()->unsigned();
                $table->foreign('template_id')->references('id')->on('templates')->onDelete('cascade');
                $table->integer('show_id')->comment("Manage Show id")->nullable()->unsigned();
                $table->foreign('show_id')->references('id')->on('manage_shows')->onDelete('cascade');
                $table->text('additional_fields')->default(null)->nullable();
                $table->float('total_amount')->default(0)->comments('Ammount that is Divide among number of users')->nullable();
                $table->text('trainer_comments')->default(null)->nullable();
                $table->text('show_owner_comments')->default(null)->nullable();

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
        Schema::dropIfExists('manage_show_order_supplies');
    }
}
