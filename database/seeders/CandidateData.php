<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use \Illuminate\Support\Str;
use Faker\Generator;
use App\Models\Client;
use App\Models\ClientCondition;
use App\Models\CandidateHunting;
use App\Models\JobLike;
use App\Models\Content;
use App\Models\ContentClient;
use App\Models\CandidateReport;
use Database\Factories\CandidateHuntingFactory;

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
        CandidateHunting::factory()->count(500)->create();
//        

        $cand = CandidateHunting::all()->pluck('id');
        for ($k = count($cand); $k != 1 ; $k--) {
            $first_job = random_int(0, 1);
            $i = $cand->pop();
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
