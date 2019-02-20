<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddChampionArrayToChampDiv extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('champion_divisions', function (Blueprint $table) {
            $table->dropColumn('champ');        
            $table->dropColumn('reserve_champ');  
            $table->mediumText('champions')->comment("Champion Array-> key=>class_horse->id, value= Score achived ")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('champion_divisions', function (Blueprint $table) {
            $table->integer('champ')->comment("Champion of selected Classes - on table class_horses id ")->nullable()->unsigned();        
            $table->integer('reserve_champ')->comment("Reserve Champion of selected Classes - on table class_horses id ")->nullable()->unsigned();          
            $table->dropColumn('champions');  
            
        });
    }
}
