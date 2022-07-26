<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use \Illuminate\Support\Str;
use Faker\Generator;

class CandidateData extends Seeder {

    public function random_date() {
        return random_int(1999, 2022) . '-' . random_int(01, 12) . '-' . random_int(01, 28);
    }

    public function random_date_hour() {
        return random_int(1999, 2022) . '-' . random_int(01, 12) . '-' . random_int(01, 28) . ' ' . random_int(1, 12) . ':' . random_int(1, 59) . ':' . random_int(1, 59);
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        //
        $faker = new Generator();
//        
        for ($i = 1; $i < random_int(0, 590); $i++) {
            $first_job = random_int(0, 1);
            DB::table('content')->insert([  'user_id',
        'title',
        'contract_type',
        'salary',
        'image',
        'description',
        'application',
        'application_type',
        'remote',
        'status',
        'published_at',
        'observation',
        'url',
        'source',
        'type',
        'ordenation',
        'in_compleo',
        'compleo_code',
        'date',
        'city',
        'state',
        'cod_filial',
        'name_filial',
        'group_id',
        'category',
        'branch_code',
        'branch_name',
        'district',
        'benefits',
        'requirements',
        'hours',
        'english_level'
                'created_at' => $this->random_date_hour(),
                'updated_at' => $this->random_date_hour()
            ]);

    }

}
