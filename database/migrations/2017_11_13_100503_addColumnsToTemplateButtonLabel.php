<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToTemplateButtonLabel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('template_button_labels', function (Blueprint $table) {
            $table->mediumText('m_s_fields')->comment('Manage Scheduler fields')->default(null)->nullable();
            $table->mediumText('i_p_fields')->comment('Invite Participant fields')->default(null)->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('template_button_labels', function (Blueprint $table) {
          $table->dropColumn('m_s_fields');
            $table->dropColumn('i_p_fields');
        });
    }
}
