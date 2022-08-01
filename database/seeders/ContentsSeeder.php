<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ContentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
           
        Content::factory()->count(500)->create();
        ContentClient::factory()->count(Content::WhereNotIn('id', ContentClient::all()->pluck('content_id'))->get()->count())->create();
    }
}
