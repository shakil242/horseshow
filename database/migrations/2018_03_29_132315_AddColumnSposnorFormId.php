<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnSposnorFormId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sponsor_category_billings', function (Blueprint $table) {
            $table->integer('sponsor_form_id')->comments('Sponsor Registration form id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sponsor_category_billings', function (Blueprint $table) {
            $table->dropColumn('sponsor_form_id');
        });
    }
}
