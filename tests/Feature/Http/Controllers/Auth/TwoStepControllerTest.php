<?php

use App\Models\TwoStepAuth;
use App\Models\User;
use Database\Seeders\GroupsTableSeeder;
use Tests\TestCase;

uses(TestCase::class);

/**
 * @see \App\Http\Controllers\Auth\TwoStepController
 */
test('resend returns an ok response', function () {
    config(['auth.TwoStepEnabled' => true]);

    $this->seed(GroupsTableSeeder::class);

    $user = User::factory()->create([
        'twostep' => true,
    ]);

    TwoStepAuth::factory()->create([
        'userId' => $user->id,
    ]);

    $this->actingAs($user)->post(route('resend'))
        ->assertRedirect(route('verificationNeeded'));
});

test('show verification returns an ok response', function () {
    config(['auth.TwoStepEnabled' => true]);

    $this->seed(GroupsTableSeeder::class);

    $user = User::factory()->create([
        'twostep' => true,
    ]);

    TwoStepAuth::factory()->create([
        'userId' => $user->id,
    ]);

    $this->actingAs($user)->get(route('verificationNeeded'))
        ->assertOk()
        ->assertViewIs('auth.twostep-verification');
});

test('verify returns an ok response', function () {
    config(['auth.TwoStepEnabled' => true]);

    $this->seed(GroupsTableSeeder::class);

    $user = User::factory()->create([
        'twostep' => true,
    ]);

    $twoStep = TwoStepAuth::factory()->create([
        'userId' => $user->id,
    ]);

    $this->actingAs($user)->postJson(route('verify'), [
        'v_input_1' => $twoStep->authCode[0],
        'v_input_2' => $twoStep->authCode[1],
        'v_input_3' => $twoStep->authCode[2],
        'v_input_4' => $twoStep->authCode[3],
    ], ['HTTP_X-Requested-With' => 'XMLHttpRequest'])
        ->assertOk();
});
