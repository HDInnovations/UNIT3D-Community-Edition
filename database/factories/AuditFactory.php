<?php

namespace Database\Factories;

use App\Models\Audit;
use Illuminate\Database\Eloquent\Factories\Factory;

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
            'action'         => $this->faker->word,
            'model_entry_id' => $this->faker->integer,
            'model_name'     => $this->faker->word,
            'record'         => $this->faker->word,
            'user_id'        => \App\Models\User::factory(),
        ];
    }
}
