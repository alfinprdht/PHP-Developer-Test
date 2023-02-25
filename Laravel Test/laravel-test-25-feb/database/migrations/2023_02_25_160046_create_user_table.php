<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user', function (Blueprint $table) {
            $table->increments('user_id');
            $table->string('name', 100);
            $table->text('address');
            $table->string('password');
            $table->string('email')->unique();
            $table->string('photos')->nullable();
            $table->string('creditcard_type', 50);
            $table->bigInteger('creditcard_number');
            $table->string('creditcard_name', 100);
            $table->string('creditcard_expired', 5);
            $table->integer('creditcard_ccv');
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
