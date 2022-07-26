<?php

namespace Database\Factories;

use App\Models\CandidateHunting;
use Illuminate\Database\Eloquent\Factories\Factory;

class CandidateHuntingFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CandidateHunting::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
              'gid' => md5(Str::random(15)),
        'surname' => $this->faker->name,
        'birth_date' => $this->faker->date,
        'cellphone' => $this->faker->phoneNumber,
        'email' => $this->faker->email,
        'payment' => $this->faker->randomFloat(2),
        'portifolio_url' => $this->faker->url,
        'linkedin_url' => $this->faker->url,
        'pcd' => random_int(0, 1),
        'status' => null,
        'pcd_type_id' => random_int(0,6),
        'pcd_details' => Str::random(35),
        'pcd_report' => null,
        'first_job' => random_int(0, 1),
        'state_id' => random_int(0, 25),
        'city_id' => random_int(0, 5200),
        'remote' => random_int(0, 1)
            , 'move_out'=> random_int(0, 1)
        , 'race_id' => random_int(1, 4),
            'gender_id' => random_int(1, 4),
        'english_level'=> random_int(1, 4)
        ];
    }
}
