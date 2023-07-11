<?php

namespace Tests\Old;

use App\Models\User;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Auth\ForgotUsernameController
 */
final class ForgotUsernameControllerTest extends TestCase
{
    #[Test]
    public function send_username_reminder_returns_an_ok_response(): void
    {
        config(['captcha.enabled' => false]);

        $user = User::factory()->create();

        $this->post(route('username.email'), [
            'email' => $user->email,
        ])
            ->assertRedirect(route('login'))
            ->assertSessionHas('success', trans('email.username-sent'));
    }

    #[Test]
    public function show_forgot_username_form_returns_an_ok_response(): void
    {
        $this->get(route('username.request'))
            ->assertOk()
            ->assertViewIs('auth.username');
    }
}
