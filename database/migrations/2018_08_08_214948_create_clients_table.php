<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     * @throws Exception
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->increments('id');
            $table->string('phone')->unique();
            $table->string('refresh_token');
            $table->integer('role')->default(\App\Models\Clients::ROLE_CLIENT);
            $table->timestamps();
        });


        DB::table('clients')->insert([
           'phone' => 'docs-10101010101',
            'refresh_token' => \App\Tools\ApiHelper::create_token()
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clients');
    }


}
