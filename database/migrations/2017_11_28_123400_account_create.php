<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AccountCreate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account', function (Blueprint $table) {
            $table->increments('id');
            $table->string('user_name');
            $table->string('email');
            $table->string('images');
            $table->string('birthdate');
            $table->string('external_url');
            $table->string('country');
            $table->string('product');
            $table->string('access_token'); 
            $table->string('refresh_token'); 
            $table->integer('account_type'); 
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
        //
    }
}
