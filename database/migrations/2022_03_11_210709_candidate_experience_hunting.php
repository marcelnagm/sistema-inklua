<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CandidateExperienceHunting extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('candidate_experience_hunting', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('candidate_id')->nullable(false);
            
            $table->string('role')->nullable(false);
            $table->string('company')->nullable(false);
            $table->string('description')->nullable(false);

            $table->date('start_at')->nullable(false);
            $table->date('end_at')->nullable(true);
            
            $table->foreign('candidate_id')->references('id')->on('candidate_hunting');
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
