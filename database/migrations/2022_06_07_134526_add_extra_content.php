<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExtraContent extends Migration
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
          $table->string('district')->nullable(true);                
          $table->string('benefits')->nullable(true);                
          $table->string('requirements')->nullable(true);                
          $table->string('hours')->nullable(true);                
       
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
