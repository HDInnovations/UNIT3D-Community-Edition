<?php

namespace Tests\Feature\Http\Controllers\Auth;

use App\Models\User;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Auth\ForgotUsernameController
 */
class ForgotUsernameControllerTest extends TestCase
{
    public function testSendUsernameReminderReturnsAnOkResponse()
    {
        config(['captcha.enabled' => false]);

        $user = User::factory()->create();

        $this->post(route('username.email'), [
            'email' => $user->email,
        ])
            ->assertRedirect(route('login'))
            ->assertSessionHas('success', trans('email.username-sent'));
    }

    public function testShowForgotUsernameFormReturnsAnOkResponse()
    {
        $this->get(route('username.request'))
            ->assertOk()
            ->assertViewIs('auth.username');
    }
}
