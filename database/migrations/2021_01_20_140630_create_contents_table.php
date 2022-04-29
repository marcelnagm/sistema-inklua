<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contents', function (Blueprint $table) {
            $table->id();

            $table->integer('group_id')->nullable();
            $table->tinyInteger('type')->length(1);
            $table->integer('ordenation')->nullable();
            $table->date('date')->nullable();
            $table->string('title')->nullable();
            $table->string('category')->nullable();
            $table->string('image')->nullable();
            $table->text('description')->nullable();
            $table->string('url')->nullable();
            $table->string('source')->nullable();
            $table->string('compleo_code')->nullable();
            $table->tinyInteger('in_compleo')->length(1)->default(0);
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->integer('cod_filial')->nullable();
            $table->string('name_filial')->nullable();
            $table->string('branch_code')->nullable();
            $table->string('branch_name')->nullable();

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
        Schema::dropIfExists('contents');
    }
}
