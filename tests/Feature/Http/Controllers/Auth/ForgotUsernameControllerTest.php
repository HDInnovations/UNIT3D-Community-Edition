<?php

namespace Tests\Feature\Http\Controllers\Auth;

use App\Models\User;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Auth\ForgotUsernameController
 */
class ForgotUsernameControllerTest extends TestCase
{
    /**
     * @test
     */
    public function send_username_reminder_returns_an_ok_response()
    {
        config(['captcha.enabled' => false]);

        $user = factory(User::class)->create();

        $this->post(route('username.email'), [
            'email' => $user->email,
        ])
            ->assertRedirect(route('login'))
            ->assertSessionHas('success', trans('email.username-sent'));
    }

    /**
     * @test
     */
    public function show_forgot_username_form_returns_an_ok_response()
    {
        $this->get(route('username.request'))
            ->assertOk()
            ->assertViewIs('auth.username');
    }
}
