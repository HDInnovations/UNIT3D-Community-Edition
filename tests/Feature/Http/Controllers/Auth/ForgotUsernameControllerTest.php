<?php

use App\Models\User;
use Tests\TestCase;

uses(TestCase::class);

/**
 * @see \App\Http\Controllers\Auth\ForgotUsernameController
 */
test('send username reminder returns an ok response', function () {
    config(['captcha.enabled' => false]);

    $user = User::factory()->create();

    $this->post(route('username.email'), [
        'email' => $user->email,
    ])
        ->assertRedirect(route('login'))
        ->assertSessionHas('success', trans('email.username-sent'));
});

test('show forgot username form returns an ok response', function () {
    $this->get(route('username.request'))
        ->assertOk()
        ->assertViewIs('auth.username');
});
