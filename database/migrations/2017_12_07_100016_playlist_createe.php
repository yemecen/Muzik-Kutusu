<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PlaylistCreatee extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('playlist', function (Blueprint $table) {
            $table->increments('id');
            $table->string('playlist_id');
            $table->string('href');
            $table->string('images');
            $table->string('name');
            $table->string('owner_id');
            $table->string('is_share');
            $table->integer('account_id');
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
