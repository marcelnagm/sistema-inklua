<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CandidateGender extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    
     private $race = [
       'não identificado' ,'branca','preta','parda','amarela' ,'indígena'
    ];
     private $gender = [
       'não identificado', 'masculino','feminino','transgênero' ,'gênero neutro'
    ];
     
    public function up()
    {
        //
         Schema::create('candidate_race', function (Blueprint $table) {
         $table->id();
            $table->string('name')->nullable(false)->unique();                
         });
         
         Schema::create('candidate_gender', function (Blueprint $table) {
         $table->id();
            $table->string('name')->nullable(false)->unique();                
         });
        
         foreach ($this->race as $key) {
//            dd ($val);
            DB::table('candidate_race')->insert([
                'name' => $key,
            ]);
        }
         foreach ($this->gender as $key) {
//            dd ($val);
            DB::table('candidate_gender')->insert([
                'name' => $key,
            ]);
        }
        
         Schema::table('candidate', function (Blueprint $table) {
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
