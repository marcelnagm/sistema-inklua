<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class OfficeRole extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    
    private $roles = [
        'Lider',
        'Estagiário',
          'Auxiliar',
        "Assistente Pleno",
        "Assistente Sênior"
  
    ]; 
            
            
    public function up()
    {
        //
         Schema::dropIfExists('office_role');
           Schema::create('office_role', function (Blueprint $table) {
            $table->id();
            $table->string('role');
            $table->timestamps();
        });
        
        foreach ($this->roles as $key) {
//            dd ($val);
            DB::table('office_role')->insert([
                'role' => $key
            ]);
        }
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
