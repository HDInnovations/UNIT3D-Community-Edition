<?php

use App\Models\Application;
use Tests\TestCase;

uses(TestCase::class);

/**
 * @see \App\Http\Controllers\Auth\ApplicationController
 */
test('create returns an ok response', function () {
    $this->get(route('application.create'))
        ->assertOk()
        ->assertViewIs('auth.application.create');
});

test('store returns an ok response', function () {
    config(['captcha.enabled' => false]);

    $application = Application::factory()->make();

    $this->post(route('application.store'), $application->toArray())
        ->assertRedirect(route('login'))
        ->assertSessionHas('success', trans('auth.application-submitted'));
});
