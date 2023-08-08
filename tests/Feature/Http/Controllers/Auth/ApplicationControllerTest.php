<?php

namespace Tests\Feature\Http\Controllers\Auth;

use App\Models\Application;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Auth\ApplicationController
 */
class ApplicationControllerTest extends TestCase
{
    /**
     * @test
     */
    public function create_returns_an_ok_response(): void
    {
        config(['other.application_signups' => true]);

        $this->get(route('application.create'))
            ->assertOk()
            ->assertViewIs('auth.application.create');
    }

    /**
     * @test
     */
    public function store_returns_an_ok_response(): void
    {
        config(['captcha.enabled' => false]);
        config(['other.application_signups' => true]);

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
