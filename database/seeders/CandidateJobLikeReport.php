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

        $candi = \App\Models\CandidateHunting::all()->pluck('id');
        $jobs = \App\Models\ContentClient::all()->pluck('content_id');

        for ($i = 1; $i < count($candi); $i++) {

            DB::table('job_like')->insert([
                'candidate_id' => $candi[random_int(1, count($candi) - 1)],
                'job_id' => $jobs[random_int(1, count($jobs) - 1)],
            ]);
        }

//      
//        
        $status = \App\Models\ReportStatus::all()->pluck('id');
        $users = \App\Models\InkluaUser::all()->pluck('user_id');
        for ($j = 0; $j < 4000; $j++) {
            DB::table('candidate_report')->insert([
                'report_status_id' => $status[random_int(0, count($status) - 1)],
                'job_id' => $jobs[random_int(1, count($jobs) - 1)],
                'hired' => random_int(0, 1),
                'candidate_id' => $candi[random_int(1, count($candi) - 1)],
                'user_id' => $users[random_int(1, count($users) - 1)]
            ]);
//        }
        }
    }

}
