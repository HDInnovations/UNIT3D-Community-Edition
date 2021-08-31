<?php

use App\Models\UserActivation;
use Database\Seeders\GroupsTableSeeder;
use Tests\TestCase;

uses(TestCase::class);

/**
 * @see \App\Http\Controllers\Auth\ActivationController
 */
test('activate returns an ok response', function () {
    $this->seed(GroupsTableSeeder::class);

    $activation = UserActivation::factory()->create();

    $this->get(route('activate', ['token' => $activation->token]))
        ->assertRedirect(route('login'))
        ->assertSessionHas('success', trans('auth.activation-success'));
});
