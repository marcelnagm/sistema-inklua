<?php

namespace Database\Factories;

use App\Models\Content;
use App\Models\User;
use App\Models\State;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\InkluaUser;
class ContentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Content::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            
      'user_id' => $this->faker->randomElement(User::where('type','PJ')->pluck('id')),
        'title' => $this->faker->jobTitle(),
        'contract_type' => 'presencial',
        'salary' => $this->faker->randomFloat(2000,20000),
        'source' => null,
        'in_compleo' => null,
        'compleo_code' => null,
        'cod_filial' => null,
        'name_filial' => null,
        'image' => null,
        'description' => $this->faker->paragraph(),
        'application' => $this->faker->url(),
        'application_type' => 'url',
        'remote' => random_int(0,1),
        'status' =>  $this->faker->randomElement(Content::$status),
        'in_compleo' => 1,
        'published_at' => $this->faker->dateTime(),
        'observation' => $this->faker->paragraph(),
        'url' => $this->faker->url(),
        'type' => 1,
        'date' => $this->faker->dateTime,
        'city' => $this->faker->city(),
        'state' => $this->faker->randomElement(State::all()->pluck('UF')),        
        'district' => $this->faker->name(),
        'benefits' => $this->faker->paragraph(),
        'requirements' => $this->faker->paragraph(),
        'hours' => $this->faker->name(),
        'english_level' => random_int(1,6)];
            
        
    }
}
