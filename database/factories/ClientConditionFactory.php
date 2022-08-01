<?php

namespace Database\Factories;

use App\Models\Content;
use App\Models\ClientCondition;
use App\Models\User;
use App\Models\State;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\InkluaUser;
use App\Models\Condition;
use App\Models\Client;

class ClientConditionFactory extends Factory {

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ClientCondition::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition() {
        return [
            'condition_id' => $this->faker->randomElement(Condition::all()->pluck('id')),
            'client_id' => $this->faker->randomElement(Client::all()->pluck('id')),
            'brute' => random_int(0, 1),
            'tax' => $this->faker->randomFloat(2, 100, 175),
            'guarantee' => random_int(30, 180),
            'active' => random_int(0, 1),
            'start_cond' => random_int(50, 100),
            'end_cond' => random_int(120, 3000)
        ];
    }

}
