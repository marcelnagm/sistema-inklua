<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ExternalLikes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
           Schema::create('external_like', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('likes')->nullable(false)->default(0);                        
            $table->foreign('id')->references('id')->on('contents');           
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
