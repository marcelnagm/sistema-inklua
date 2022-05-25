<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCandidateHunting extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('candidate_hunting', function (Blueprint $table) {
            $table->id();
            $table->string('gid')->nullable(false)->unique();
            $table->string('name')->nullable(false);
            $table->string('surname')->nullable(false);
            $table->date('birth_date')->nullable(false);
            $table->string('cellphone')->nullable(false);
            $table->string('email')->nullable(false);
            $table->float('payment')->nullable(false)->defaul(0);
            $table->string('cv_path')->nullable(false);
            $table->string('portifolio_url')->nullable(true);
            $table->string('linkedin_url')->nullable(true);
            $table->boolean('pcd')->nullable(false);            
            $table->unsignedBigInteger('pcd_type_id')->nullable(true);
            $table->text('pcd_details')->nullable(true);
            $table->string('pcd_report')->nullable(true);
            $table->unsignedBigInteger('state_id')->nullable(false);
            $table->unsignedBigInteger('city_id')->nullable(false);
            $table->boolean('first_job')->defaul(1)->nullable(true);
            $table->boolean('remote')->defaul(0)->nullable(false);
            $table->boolean('move_out')->defaul(0)->nullable(false);            
            $table->unsignedBigInteger('english_level')->nullable(false);
            $table->unsignedBigInteger('user_id')->nullable(false);
            $table->foreign('english_level')->references('id')->on('candidate_english_level');
            $table->foreign('pcd_type_id')->references('id')->on('pcd_type');
            $table->foreign('state_id')->references('id')->on('state');
            $table->foreign('city_id')->references('id')->on('city');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('candidate');
    }

}
