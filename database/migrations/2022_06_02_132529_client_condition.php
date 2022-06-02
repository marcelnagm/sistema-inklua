<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ClientCondition extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
         Schema::dropIfExists('client_condition');
        Schema::create('client_condition', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('condition_id')->nullable(false);
            $table->unsignedBigInteger('client_id')->nullable(false);                       
            $table->boolean('brute')->nullable(false);                       
            $table->float('tax')->nullable(false);
            $table->integer('guarantee')->nullable(false);
            $table->float('start_cond')->nullable(true);
            $table->float('end_cond')->nullable(true);                       
            $table->foreign('client_id')->references('id')->on('clients');
            $table->foreign('condition_id')->references('id')->on('conditions');
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
