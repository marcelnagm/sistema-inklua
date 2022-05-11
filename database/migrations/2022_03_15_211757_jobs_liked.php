<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class JobsLiked extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('job_like', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('candidate_id')->nullable(false);            
            $table->unsignedBigInteger('job_id')->nullable(false);
            $table->foreign('candidate_id')->references('id')->on('candidate');
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
