<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class WorkingSchedule extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('working_schedule', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('client_id');
            $table->date('date');
            $table->integer('hour');
            $table->timestamps();
            $table->unique(['client_id', 'date', 'hour']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('working_schedule');
    }
}
