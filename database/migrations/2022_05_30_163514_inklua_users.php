<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class InkluaUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //inklua_users
         //
        Schema::dropIfExists('inklua_users');
        Schema::create('inklua_users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable(false);
            $table->unsignedBigInteger('office_id')->nullable(false);
            $table->unsignedBigInteger('role_id')->nullable(false)->default(0);
            $table->boolean('active')->nullable(false);
            $table->date('start_at')->nullable(false);
            $table->date('end_at')->nullable(true);                       
            $table->foreign('office_id')->references('id')->on('inklua_office');
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
