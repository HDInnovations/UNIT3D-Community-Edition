<?php

namespace Database\Factories;

use App\Models\Ticket;
use Illuminate\Database\Eloquent\Factories\Factory;

class TicketFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'body'        => $this->faker->text,
            'category_id' => \App\Models\TicketCategory::factory(),
            'priority_id' => \App\Models\TicketPriority::factory(),
            'staff_id'    => \App\Models\User::factory(),
            'subject'     => $this->faker->word,
            'user_id'     => \App\Models\User::factory(),
        ];
    }
}
