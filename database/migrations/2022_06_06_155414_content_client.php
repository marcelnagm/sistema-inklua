<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ContentClient extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
          Schema::create('contents_client', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('content_id')->nullable(false);
            $table->unsignedBigInteger('client_id')->nullable(false);                       
            $table->unsignedBigInteger('client_condition_id')->nullable(false);                       
            $table->integer('vacancy')->nullable(false);                
            $table->foreign('client_id')->references('id')->on('clients');
            $table->foreign('client_condition_id')->references('id')->on('client_condition');
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
