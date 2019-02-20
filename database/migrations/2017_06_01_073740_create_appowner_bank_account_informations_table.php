<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppownerBankAccountInformationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appowner_bank_account_informations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('owner_id')->comment('User who send payment through banck account')->unsigned();
            $table->string('account_holder_name')->comment('Bank account information');
            $table->string('account_holder_type')->comment('Bank account information');
            $table->string('routing_number')->comment('Bank account information');
            $table->string('account_number')->comment('Bank account information');
            $table->string('stripe_customer_id')->comment('stripe Customer Id information');
            $table->string('stripe_bank_token_id')->comment('stripe bank Token Id');
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
        Schema::dropIfExists('appowner_bank_account_informations');
    }
}
