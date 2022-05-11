<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ReportCandidate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('candidate_report', function (Blueprint $table) {
            $table->id() ;
            $table->unsignedBigInteger('candidate_id')->nullable(true);
            $table->unsignedBigInteger('job_id')->nullable(true);
            $table->boolean('hired')->nullable(true);
            $table->boolean('owner')->nullable(true);
            $table->text('obs')->nullable(true);
            $table->string('company')->nullable(true);
            $table->unsignedBigInteger('report_status_id')->nullable(true);
            $table->unsignedBigInteger('user_id')->nullable(false);
            $table->foreign('report_status_id')->references('id')->on('report_status');                                
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
