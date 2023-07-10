<?php

namespace Tests\Feature\Http\Controllers\Auth;

use PHPUnit\Framework\Attributes\Test;
use App\Models\Application;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Auth\ApplicationController
 */
class ApplicationControllerTest extends TestCase
{
    #[Test]
    public function create_returns_an_ok_response(): void
    {
        $this->get(route('application.create'))
            ->assertOk()
            ->assertViewIs('auth.application.create');
    }

    #[Test]
    public function store_returns_an_ok_response(): void
    {
        config(['captcha.enabled' => false]);

        $application = Application::factory()->make();

        $this->post(route('application.store'), [
            'type'     => $application->type,
            'email'    => $application->email,
            'referrer' => $application->referrer,
        ])
            ->assertRedirect(route('login'))
            ->assertSessionHas('success', trans('auth.application-submitted'));
    }
}
