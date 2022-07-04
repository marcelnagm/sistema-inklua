<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTraceableContent extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
          Schema::table('contents', function (Blueprint $table) {
             $table->unsignedBigInteger('created_by')->nullable(true);  
             $table->unsignedBigInteger('updated_by')->nullable(true);  
              $table->foreign('created_by')->references('id')->on('users');
              $table->foreign('updated_by')->references('id')->on('users');
             
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
