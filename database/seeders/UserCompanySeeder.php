<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory;
use App\Models\Client;
use App\Models\ClientCondition;
use App\Models\Condition;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserCompanySeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        //
            $faker = Factory::create();
        
        $ids = User::
                where('fantasy_name',null)->
                where('cnpj',null)->
                get();
//        var_dump($ids);
        print 'QUantos Ids :'. $ids->count() . "\n";
        
        foreach ($ids as $user) {
          $user->fantasy_name= $faker->company;
          $user->cnpj= random_int(1000,35231235);
          $user->update();
        }
    }

}
