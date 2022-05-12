<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddHuntingGender extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('candidate_hunting', function (Blueprint $table) {
            $table->unsignedBigInteger('gender_id')->default(1)->nullable(false);
            $table->unsignedBigInteger('race_id')->default(1)->nullable(false);
             $table->foreign('race_id')->references('id')->on('candidate_race');
             $table->foreign('gender_id')->references('id')->on('candidate_gender');
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
