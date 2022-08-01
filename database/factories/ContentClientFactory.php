<?php

namespace Database\Factories;

use App\Models\Content;
use App\Models\ContentClient;
use App\Models\ClientCondition;
use App\Models\Client;
use App\Models\User;
use App\Models\State;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\InkluaUser;

class ContentClientFactory extends Factory {

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ContentClient::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition() {
        $def = [
        'user_id' => $this->faker->randomElement(User::where('type', 'PJ')->pluck('id')),
        'content_id' => $this->faker->randomElement(Content::WhereNotIn('id', ContentClient::all()->pluck('content_id'))->get()->pluck('id')),
        'client_id' => $this->faker->randomElement(Client::all()->pluck('id')),
//        'client_condition_id' => 1,
        'vacancy' => $this->faker->randomDigitNotNull()];
        $def['client_condition_id'] = $this->faker->randomElement(ClientCondition::where('client_id', $def['client_id'])->pluck('id'));
       return $def;
    }

}
