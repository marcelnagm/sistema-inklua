<?php

namespace Database\Factories;

use App\Models\Content;
use App\Models\Client;
use App\Models\User;
use App\Models\State;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\InkluaUser;

class ClientFactory extends Factory {

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Client::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition() {
        return [
            'cnpj' => $this->faker->numberBetween(),
		'formal_name' => $this->faker->company,
		'fantasy_name' => $this->faker->company,
		'sector' => $this->faker->companySuffix,
		'local_label' => $this->faker->name,
		'active' => random_int(0, 1),
		'state_id' => random_int(1, 25),
		'obs' => $this->faker->paragraph,];
       
    }

}
