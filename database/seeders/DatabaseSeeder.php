<?php
namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
    
//        $this->call([UsersTableSeeder::class]);
//        $this->call([StateSeeder::class]);
//        $this->call([CandidateEnglishLevelSeeder::class]);
//        $this->call([CandidatePCDSeeder::class]);
        $this->call([CandidateData::class]);
        $this->call([CandidateJobLikeReport::class]);
    }
}
