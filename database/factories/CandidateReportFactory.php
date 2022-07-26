<?php

namespace Database\Factories;

use App\Models\CandidateReport;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Content;
use App\Models\CandidateHunting;
use App\Models\InkluaUser;


class CandidateReportFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CandidateReport::class;
 public function random_date_hour() {
        return random_int(1999, 2022) . '-' . random_int(01, 12) . '-' . random_int(01, 28) . ' ' . random_int(1, 12) . ':' . random_int(1, 59) . ':' . random_int(1, 59);
    }
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
           'candidate_id' => $this->faker->randomElement(CandidateHunting::all()->pluck('id')),
            'job_id' => $this->faker->randomElement(Content::where('type', 1)->pluck('id')),
        'hired' => random(0,1),
        'start_at' => $this->faker->date('d/m/Y'),
        'owner' => null,
        'obs' => $this->faker->paragraph,
        'report_status_id' => random(1,9),
        'user_id' => $this->faker->randomElement(InkluaUser::all()->pluck('user_id')),
        ];
    }
}
