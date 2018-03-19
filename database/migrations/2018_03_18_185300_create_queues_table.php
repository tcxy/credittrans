<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQueuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('queues', function (Blueprint $table) {
            $table->increments('id');
            $table->string('message')->nullable();
            $table->integer('from');
            $table->json('path');
            $table->string('card');
            $table->string('cvv');
            $table->string('holder_name');
            $table->double('amount');
            $table->string('result')->nullable();
            $table->integer('status');
            $table->integer('current');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('queues');
    }
}
