<?php

namespace Tests\Feature\Http\Controllers\Auth;

use App\Models\UserActivation;
use GroupsTableSeeder;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Auth\ActivationController
 */
class ActivationControllerTest extends TestCase
{
    /**
     * @test
     */
    public function activate_returns_an_ok_response()
    {
        $this->seed(GroupsTableSeeder::class);

        $activation = factory(UserActivation::class)->create();

        $this->get(route('activate', ['token' => $activation->token]))
            ->assertRedirect(route('login'))
            ->assertSessionHas('success', trans('auth.activation-success'));
    }
}
