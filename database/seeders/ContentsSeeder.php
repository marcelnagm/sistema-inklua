<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Content;
use App\Models\ContentClient;
use App\Models\InkluaUser;
use App\Models\Client;
use App\Models\ClientCondition;
use Faker\Factory;
use Illuminate\Support\Facades\DB;

class ContentsSeeder extends Seeder {

    private $faker;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {

        $faker = Factory::create();
      
        Content::factory()->count(500)->create();//        
        $ids = Content::WhereNotIn('id', ContentClient::select('content_id'))->pluck('id');
        for ($j = count($ids); $j != 1; $j--) {
            $i = $ids->pop();
            $data = [
                'user_id' => $faker->randomElement(InkluaUser::where('active', '1')->pluck('user_id')),
                'content_id' => $i,
                'client_id' => $faker->randomElement(Client::all()->pluck('id')),
                'vacancy' => $faker->randomDigitNotNull()
            ];
            
//            var_dump($data['client_id']);
            $data['client_condition_id'] = $faker->randomElement(ClientCondition::where('client_id', $data['client_id'])->pluck('id'));
            
            DB::table('contents_client')->insert($data);
        }
    }

}
