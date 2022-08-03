<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory;
use App\Models\Client;
use App\Models\ClientCondition;
use App\Models\Condition;
use Illuminate\Support\Facades\DB;

class ClientsSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        //
        $faker = Factory::create();
//        Client::factory()->count(500)->create();
        
        $ids = Client::WhereNotIn('id', ClientCondition::select('client_id'))->pluck('id');
        print 'QUantos Ids :'. $ids->count() . "\n";
        
        for ($i = $ids->pop(); $ids->count() != 0; $i = $ids->pop()) {
            
            $data = [
               'condition_id' => $faker->randomElement(Condition::all()->pluck('id')),
            'client_id' => $i,
            'brute' => random_int(0, 1),
            'tax' => $faker->randomFloat(2, 100, 175),
            'guarantee' => random_int(30, 180),
            'active' => random_int(0, 1),
            'start_cond' => random_int(50, 100),
            'end_cond' => random_int(120, 3000)
            ];
            
            DB::table('client_condition')->insert($data);
        }
    }

}
