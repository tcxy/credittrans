<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatCreaditaccountTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('creditaccounts', function (Blueprint $table) {
            $table->increments('accountid');
            $table->string('holdername');
            $table->string('phonenumber');
            $table->string('address');
            $table->double('spendlinglimit');
            $table->double('balance');
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
