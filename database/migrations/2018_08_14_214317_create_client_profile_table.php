<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientProfileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_profile', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('client_id')->unique();
            $table->integer('gender')->nullable();
            $table->integer('home_call')->nullable();
            $table->string('address')->nullable();
            $table->string('about')->nullable();

            $table->string('first_name')->nullable();
            $table->string('surname')->nullable();
            $table->string('avatar')->nullable();

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
        Schema::dropIfExists('client_profile');
    }
}
