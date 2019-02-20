<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnGoverningBodyToManageShows extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('manage_shows', function (Blueprint $table) {
            $table->string('governing_body')->comments("1=USEF,2=local,3=EC")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('manage_shows', function (Blueprint $table) {
            $table->dropColumn('governing_body');
        });
    }
}
