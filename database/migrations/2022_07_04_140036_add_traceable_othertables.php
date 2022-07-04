<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTraceableOthertables extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        //
          $tables = array('candidate_report', 'clients', 'client_condition', 'contents_client', 'content_canceled', 'inklua_office', 'inklua_users');

        foreach ($tables as $t) {
//            dd($t);
            Schema::table($t, function (Blueprint $table) {
                $table->unsignedBigInteger('created_by')->nullable(true);
                $table->unsignedBigInteger('updated_by')->nullable(true);
                $table->foreign('created_by')->references('id')->on('users');
                $table->foreign('updated_by')->references('id')->on('users');
            });
        }
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
