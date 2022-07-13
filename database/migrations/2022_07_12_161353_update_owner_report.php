<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateOwnerReport extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        //
        Schema::table('candidate_report', function (Blueprint $table) {
            $table->dropColumn('owner');                   
            $table->dropColumn('company');                   
        });
        Schema::table('candidate_report', function (Blueprint $table) {            
            $table->date('start_at')->nullable(true)->after('hired');
            $table->unsignedBigInteger('owner')->nullable(true)->after('hired');
            $table->foreign('owner')->references('id')->on('users');
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
