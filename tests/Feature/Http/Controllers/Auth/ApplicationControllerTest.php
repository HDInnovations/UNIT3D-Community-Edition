<?php

namespace Tests\Feature\Http\Controllers\Auth;

use App\Models\Application;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Auth\ApplicationController
 */
class ApplicationControllerTest extends TestCase
{
    public function testCreateReturnsAnOkResponse()
    {
        $this->get(route('application.create'))
            ->assertOk()
            ->assertViewIs('auth.application.create');
    }

    public function testStoreReturnsAnOkResponse()
    {
        config(['captcha.enabled' => false]);

        $application = Application::factory()->make();

        $this->post(route('application.store'), $application->toArray())
            ->assertRedirect(route('login'))
            ->assertSessionHas('success', trans('auth.application-submitted'));
    }
}
