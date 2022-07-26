<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use \Illuminate\Support\Str;
use Faker\Generator;
use App\Models\CandidateHunting;
use App\Models\JobLike;
use App\Models\Content;
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
     
//           Content::factory()->count(500)->create();
           CandidateHunting::factory()->count(500)->create();
           JobLike::factory()->count(5000)->create();
           CandidateReport::factory()->count(500)->create();

    }

}
