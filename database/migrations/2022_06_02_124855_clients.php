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
            Schema::dropIfExists('clients');
          Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('cnpj')->nullable(false);
            $table->string('formal_name')->nullable(false);
            $table->string('fantasy_name')->nullable(false);
            $table->string('sector')->nullable(false);
            $table->string('local_label')->nullable(false);
            $table->text('obs')->nullable(true);
            $table->boolean('active')->default(1)->nullable(false);
            $table->unsignedBigInteger('state_id')->nullable(false);
            $table->foreign('state_id')->references('id')->on('state');
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
