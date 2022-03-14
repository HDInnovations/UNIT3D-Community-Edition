<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Ticket;

class TicketFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Ticket::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'body' => $this->faker->text,
            'category_id' => \App\Models\TicketCategory::factory(),
            'priority_id' => \App\Models\TicketPriority::factory(),
            'staff_id' => \App\Models\User::factory(),
            'subject' => $this->faker->word,
            'user_id' => \App\Models\User::factory(),
        ];
    }
}
