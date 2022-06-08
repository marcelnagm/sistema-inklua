<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ContentsCancel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
               Schema::create('content_canceled', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('content_id')->nullable(false);
            $table->unsignedBigInteger('client_id')->nullable(false);                                                      
            $table->unsignedBigInteger('user_id')->nullable(false);                                                      
            $table->string('reason')->nullable(false);                
            $table->foreign('client_id')->references('id')->on('clients');            
            $table->foreign('user_id')->references('id')->on('users');            
            $table->foreign('content_id')->references('id')->on('contents');
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
