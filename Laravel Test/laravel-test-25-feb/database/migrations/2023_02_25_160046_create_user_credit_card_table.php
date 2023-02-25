<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserCreditCardTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_credit_card', function (Blueprint $table) {
            $table->increments('user_credit_card_id');
            $table->string('creditcard_type', 50);
            $table->bigInteger('creditcard_number');
            $table->string('creditcard_name', 100);
            $table->string('creditcard_expired', 5);
            $table->integer('creditcard_ccv');
            $table->integer('user_id');
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
        Schema::dropIfExists('user_membership');
    }
}
