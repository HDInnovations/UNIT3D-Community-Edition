<?php

namespace Tests\Feature\Http\Controllers\Auth;

use App\Models\UserActivation;
use Database\Seeders\GroupsTableSeeder;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Auth\ActivationController
 */
class ActivationControllerTest extends TestCase
{
    /**
     * @test
     */
    public function activate_returns_an_ok_response(): void
    {
        $this->seed(GroupsTableSeeder::class);

        $activation = UserActivation::factory()->create();

        $this->get(route('activate', ['token' => $activation->token]))
            ->assertRedirect(route('login'))
            ->assertSessionHas('success', trans('auth.activation-success'));
    }
}
