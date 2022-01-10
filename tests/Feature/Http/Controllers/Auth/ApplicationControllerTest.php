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

        $application = Application::factory()->make();

        $this->post(route('application.store'), $application->toArray())
            ->assertRedirect(route('login'))
            ->assertSessionHas('success', trans('auth.application-submitted'));
    }
}
