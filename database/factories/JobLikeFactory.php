<?php

namespace Database\Factories;

use App\Models\JobLike;
use App\Models\CandidateHunting;
use App\Models\Content;
use Illuminate\Database\Eloquent\Factories\Factory;

class JobLikeFactory extends Factory {

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = JobLike::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition() {
        return [
            'candidate_id' => $this->faker->randomElement(CandidateHunting::all()->pluck('id')),
            'job_id' => $this->faker->randomElement(Content::where('type', 1)->pluck('id'))
        ];
    }

}
