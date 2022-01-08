<?php

/* @var \Illuminate\Database\Eloquent\Factory $factory */

namespace Database\Factories;

use App\Models\Application;
use App\Models\ApplicationImageProof;
use Illuminate\Database\Eloquent\Factories\Factory;

class ApplicationImageProofFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ApplicationImageProof::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'application_id' => fn () => Application::factory()->create()->id,
            'image'          => $this->faker->word,
        ];
    }
}
