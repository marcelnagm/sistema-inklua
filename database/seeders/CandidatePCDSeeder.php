<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use \Illuminate\Support\Str;

class CandidatePCDSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    private $levels = [
        'Não Possuo','Técnico', 'Básico', 'Intermediário', 'Avançado', 'Fluente'
    ];
    private $pcd_type = [
        'Motora',
        'Visual',
        'Auditiva',
        'Intelectual',
        'Psicossocial',
        'Reabilitado'
    ];
    
    private $report_status= [
        'Não respondeu meu contato',
        'Telefone desatualizado',
        'O perfil não era o que procurava',
        'Não foi aprovado pelo cliente',
        'Não considerar este candidato',
        'Contratado'
        ];
    
    private $level_education = [
        'Ensino Fundamental',
        'Ensino Médio Incompleto',
        'Ensino Médio Completo',
        'Curso Técnico',
        'Curso profissionalizante',
        'Ensino Superior',
        'Pós-Graduação/MBA',
        'Mestrado / Doutorado',        
    ];

public function random_date(){
    return random_int(1999, 2022).'-'.random_int(01, 12).'-'.random_int(01, 28);
}

public function random_date_hour(){
    return random_int(1999, 2022).'-'.random_int(01, 12).'-'.random_int(01, 28).' '.random_int(1, 12).':'.random_int(1, 59).':'.random_int(1, 59);
}
    
    public function run() {
        //
        foreach ($this->levels as $key) {
//            dd ($val);
            DB::table('candidate_english_level')->insert([
                'level' => $key
            ]);
        }
        foreach ($this->pcd_type as $key) {
//            dd ($val);
            DB::table('pcd_type')->insert([
                'type' => $key
            ]);
        }
        foreach ($this->level_education as $key) {
//            dd ($val);
            DB::table('level_education')->insert([
                'name' => $key
            ]);
        }
        foreach ($this->report_status as $key) {
//            dd ($val);
            DB::table('report_status')->insert([
                'status' => $key
            ]);
        }

    }

}
