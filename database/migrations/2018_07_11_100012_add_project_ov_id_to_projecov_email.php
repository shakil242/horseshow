<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddProjectOvIdToProjecovEmail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('project_ov_emails', function (Blueprint $table) {
          $table->integer('projectovs_id')->nullable()->comment('Asset Id for project overview')->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('project_ov_emails', function (Blueprint $table) {
            $table->dropColumn('projectovs_id');

        });
    }
}
