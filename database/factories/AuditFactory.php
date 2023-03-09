<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Audit;

class AuditFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Audit::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id'        => \App\Models\User::factory(),
            'model_name'     => $this->faker->word(),
            'model_entry_id' => $this->faker->randomDigitNotNull(),
            'action'         => $this->faker->word(),
            'record'         => $this->faker->word(),
        ];
    }
}
