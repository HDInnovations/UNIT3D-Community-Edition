<?php

namespace Database\Factories;

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
     *
     * @return array
     */
    public function definition()
    {
        return [
            'application_id' => \App\Models\Application::factory(),
            'image'          => $this->faker->image,
        ];
    }
}
