<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPresenHybridContents extends Migration
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
            $table->boolean('presential')->nullable(true)->default(0)->after('remote');
            $table->boolean('hybrid')->nullable(true)->default(0)->after('presential');
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
