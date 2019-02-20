<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ManageEmployeeIdForAppOwner extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('participants', function (Blueprint $table) {
            $table->integer('employee_id')->comments('to manage employee History for multiple types of actions')->default(0)->nullable();
        });
        Schema::table('asset_modules', function (Blueprint $table) {
            $table->integer('employee_id')->comments('to manage employee History for multiple types of actions')->default(0)->nullable();
        });
        Schema::table('assets', function (Blueprint $table) {
            $table->integer('employee_id')->comments('to manage employee History for multiple types of actions')->default(0)->nullable();
        });
        Schema::table('invited_users', function (Blueprint $table) {
            $table->integer('employee_id')->comments('to manage employee History for multiple types of actions')->default(0)->nullable();
        });
        Schema::table('manage_shows', function (Blueprint $table) {
            $table->integer('employee_id')->comments('to manage employee History for multiple types of actions')->default(0)->nullable();
        });
        Schema::table('participant_assets', function (Blueprint $table) {
            $table->integer('employee_id')->comments('to manage employee History for multiple types of actions')->default(0)->nullable();
        });
        Schema::table('scheduals', function (Blueprint $table) {
            $table->integer('employee_id')->comments('to manage employee History for multiple types of actions')->default(0)->nullable();
        });
        Schema::table('scheduals_notes', function (Blueprint $table) {
            $table->integer('employee_id')->comments('to manage employee History for multiple types of actions')->default(0)->nullable();
        });
        Schema::table('scheduler_feed_backs', function (Blueprint $table) {
            $table->integer('employee_id')->comments('to manage employee History for multiple types of actions')->default(0)->nullable();
        });
        Schema::table('spectators', function (Blueprint $table) {
            $table->integer('employee_id')->comments('to manage employee History for multiple types of actions')->default(0)->nullable();
        });

        Schema::table('participant_response_drafts', function (Blueprint $table) {
            $table->integer('employee_id')->comments('to manage employee History for multiple types of actions')->default(0)->nullable();
        });
        Schema::table('participant_responses', function (Blueprint $table) {
            $table->integer('employee_id')->comments('to manage employee History for multiple types of actions')->default(0)->nullable();
        });
        Schema::table('show_scratch_penalties', function (Blueprint $table) {
            $table->integer('employee_id')->comments('to manage employee History for multiple types of actions')->default(0)->nullable();
        });

        Schema::table('invite_template_transfers', function (Blueprint $table) {
            $table->integer('employee_id')->comments('to manage employee History for multiple types of actions')->default(0)->nullable();
        });

        Schema::table('template_design', function (Blueprint $table) {
            $table->integer('employee_id')->comments('to manage employee History for multiple types of actions')->default(0)->nullable();
        });
        Schema::table('invite_templatenames', function (Blueprint $table) {
            $table->integer('employee_id')->comments('to manage employee History for multiple types of actions')->default(0)->nullable();
        });




    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('participants', function (Blueprint $table) {
            $table->dropColumn('employee_id');
        });

        Schema::table('asset_modules', function (Blueprint $table) {
            $table->dropColumn('employee_id');
        });

        Schema::table('assets', function (Blueprint $table) {
            $table->dropColumn('employee_id');
        });
        Schema::table('invited_users', function (Blueprint $table) {
            $table->dropColumn('employee_id');
        });
        Schema::table('manage_shows', function (Blueprint $table) {
            $table->dropColumn('employee_id');
        });
        Schema::table('participant_assets', function (Blueprint $table) {
            $table->dropColumn('employee_id');
        });
        Schema::table('scheduals', function (Blueprint $table) {
            $table->dropColumn('employee_id');
        });
        Schema::table('scheduals_notes', function (Blueprint $table) {
            $table->dropColumn('employee_id');
        });
        Schema::table('scheduler_feed_backs', function (Blueprint $table) {
            $table->dropColumn('employee_id');
        });
        Schema::table('spectators', function (Blueprint $table) {
            $table->dropColumn('employee_id');
        });

        Schema::table('participant_responses', function (Blueprint $table) {
            $table->dropColumn('employee_id');
        });
        Schema::table('participant_response_drafts', function (Blueprint $table) {
            $table->dropColumn('employee_id');
        });
        Schema::table('show_scratch_penalties', function (Blueprint $table) {
            $table->dropColumn('employee_id');
        });
        Schema::table('invite_template_transfers', function (Blueprint $table) {
            $table->dropColumn('employee_id');
        });
        Schema::table('template_design', function (Blueprint $table) {
            $table->dropColumn('employee_id');
        });
        Schema::table('invite_templatenames', function (Blueprint $table) {
            $table->dropColumn('employee_id');
        });



    }
}
