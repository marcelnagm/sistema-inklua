<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Clients extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        //
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        //
          Schema::dropIfExists('client');
          Schema::dropIfExists('clients');
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('cnpj')->nullable()->after('password');
            $table->string('formal_name')->nullable()->after('cnpj');
            $table->string('fantasy_name')->nullable()->after('cnpj');
            $table->string('phone')->nullable()->after('fantasy_name');
            $table->string('local')->nullable()->after('fantasy_name');

            $table->boolean('active')->nullable(false);
            $table->unsignedBigInteger('state_id')->nullable(false);
            $table->foreign('state_id')->references('id')->on('state');
            $table->timestamps();
        });
    }

}
