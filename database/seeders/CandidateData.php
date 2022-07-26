<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use \Illuminate\Support\Str;

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

        for ($i = 1; $i < 400; $i++) {
            $first_job = random_int(0, 1);
            DB::table('candidate_hunting')->insert([
                'gid' => md5(random_int(1, 150) * time() . Str::random(20)),
                'name' => Str::random(40),
                'surname' => Str::random(40),
                'birth_date' => date("Y-m-d H:i:s"),
                'cellphone' => random_int(11111111, 99999999),
                'email' => Str::random(10) . '@' . Str::random(10) . "." . Str::random(3),
                'cv_path' => 'http://' . Str::random(10) . '.' . Str::random(10) . "." . Str::random(3),
                'portifolio_url' => 'http://' . Str::random(10) . '.' . Str::random(10) . "." . Str::random(3),
                'linkedin_url' => 'http://' . Str::random(10) . '.' . Str::random(10) . "." . Str::random(3),
                'pcd' => random_int(0, 1),
                'pcd_type_id' => random_int(1, 5),
                'pcd_details' => Str::random(10),
                'pcd_report' => Str::random(10),
                'payment' => random_int(1000, 15000),
                'first_job' => $first_job,
                'state_id' => random_int(1, 27),
                'city_id' => random_int(1, 560),
                'remote' => random_int(0, 1)
                , 'move_out' => random_int(0, 1),
                'english_level' => random_int(1, 5),
                'created_at' => $this->random_date_hour(),
                'updated_at' => $this->random_date_hour()
            ]);

            for ($j = 0; $j < random_int(1, 6); $j++) {
                DB::table('candidate_education_hunting')->insert([
                    'candidate_id' => $i,
                    'level_education_id' => random_int(1, 8),
                    'institute' => Str::random(10),
                    'course' => Str::random(30),
                    'start_at' => $this->random_date(),
                    'end_at' => $this->random_date()
                ]);
            }
            if ($first_job == 1) {
                for ($j = 0; $j < random_int(1, 6); $j++) {
                    DB::table('candidate_experience_hunting')->insert([
                        'candidate_id' => $i,
                        'role' => Str::random(10),
                        'company' => Str::random(10),
                        'description' => Str::random(30),
                        'start_at' => $this->random_date(),
                        'end_at' => $this->random_date()
                    ]);
                }
            }
        }
    }

}
