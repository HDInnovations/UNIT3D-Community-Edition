<?php

namespace Database\Factories;

use App\Models\ApplicationUrlProof;
use Illuminate\Database\Eloquent\Factories\Factory;

class ApplicationUrlProofFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ApplicationUrlProof::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'application_id' => \App\Models\Application::factory(),
            'url'            => $this->faker->url,
        ];
    }
}
