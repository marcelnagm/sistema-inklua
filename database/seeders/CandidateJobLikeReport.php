<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use \Illuminate\Support\Str;

class CandidateJobLikeReport extends Seeder {

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

        $jobs = array(7636,
            7649,
            7634,
            7635);

//        for ($i = 1; $i < 400; $i++) {
//            $first_job = random_int(0, 1);
//            DB::table('job_like')->insert([
//                
//            ]);

        for ($j = 0; $j < random_int(1, 16); $j++) {
            DB::table('job_like')->insert([
                'candidate_id' => random_int(3, 16),
                'job_id' => $jobs[random_int(1, 3)]
            ]);
        }
//        }
    }

}
