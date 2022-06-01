<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class InkluaOffice extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::dropIfExists('inklua_office');
         Schema::create('inklua_office', function (Blueprint $table) {
             $table->id();
             $table->string('name');
            $table->unsignedBigInteger('leader_id')->nullable(true);
            $table->unsignedBigInteger('pfl_id')->nullable(true);
            $table->foreign('leader_id')->references('id')->on('users');
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
