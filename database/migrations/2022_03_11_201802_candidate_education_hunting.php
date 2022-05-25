<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CandidateEducationHunting extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        //

        Schema::create('candidate_education_hunting', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('candidate_id')->nullable(false);
            $table->unsignedBigInteger('level_education_id')->nullable(false);            
            $table->string('institute')->nullable(false);
            $table->string('course')->nullable(false);

            $table->date('start_at')->nullable(false);
            $table->date('end_at')->nullable(true);            
            $table->foreign('level_education_id')->references('id')->on('level_education');
            $table->foreign('candidate_id')->references('id')->on('candidate_hunting');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        //
    }

}
