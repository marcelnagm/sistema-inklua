<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ClientsSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        //
        Client::factory()->count(500)->create();
        ClientCondition::factory()->count(700)->create();
    }

}
