<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShowStallRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('show_stall_requests', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->integer('show_id')->unsigned();
            $table->foreign('show_id')->references('id')->on('manage_shows')->onDelete('cascade');
            $table->integer('stall_type_id')->unsigned();
            $table->foreign('stall_type_id')->references('id')->on('stall_types')->onDelete('cascade');
            $table->integer('quantity')->comments('stall type quantiry request')->nullable();
            $table->text('assign_values')->comments('Horse and trainer assigned to it')->nullable();

            $table->tinyInteger('status')->comments("approve=1,Rejected=2,Pending=0")->default('0');
            $table->integer('approve_stable_id')->nullable();
            $table->string('stall_number')->nullable();
            $table->text('comments')->nullable();

            $table->text('assign_rider_to_stallnumber')->comments("save the riders and horse assigned to stall numbers")->nullable();

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
        Schema::dropIfExists('show_stall_requests');
    }
}
