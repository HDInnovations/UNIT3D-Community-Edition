<?php

namespace Tests\Feature\Http\Controllers\Auth;

use App\Models\TwoStepAuth;
use App\Models\User;
use GroupsTableSeeder;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Auth\TwoStepController
 */
class TwoStepControllerTest extends TestCase
{
    /**
     * @test
     */
    public function resend_returns_an_ok_response()
    {
        config(['auth.TwoStepEnabled' => true]);

        $this->seed(GroupsTableSeeder::class);

        $user = factory(User::class)->create([
            'twostep' => true,
        ]);

        factory(TwoStepAuth::class)->create([
            'userId' => $user->id,
        ]);

        $this->actingAs($user)->post(route('resend'))
            ->assertRedirect(route('verificationNeeded'));
    }

    /**
     * @test
     */
    public function show_verification_returns_an_ok_response()
    {
        config(['auth.TwoStepEnabled' => true]);

        $this->seed(GroupsTableSeeder::class);

        $user = factory(User::class)->create([
            'twostep' => true,
        ]);

        factory(TwoStepAuth::class)->create([
            'userId' => $user->id,
        ]);

        $this->actingAs($user)->get(route('verificationNeeded'))
            ->assertOk()
            ->assertViewIs('auth.twostep-verification');
    }

    /**
     * @test
     */
    public function verify_returns_an_ok_response()
    {
        config(['auth.TwoStepEnabled' => true]);

        $this->seed(GroupsTableSeeder::class);

        $user = factory(User::class)->create([
            'twostep' => true,
        ]);

        $twoStep = factory(TwoStepAuth::class)->create([
            'userId' => $user->id,
        ]);

        $this->actingAs($user)->postJson(route('verify'), [
            'v_input_1' => $twoStep->authCode[0],
            'v_input_2' => $twoStep->authCode[1],
            'v_input_3' => $twoStep->authCode[2],
            'v_input_4' => $twoStep->authCode[3],
        ], ['HTTP_X-Requested-With' => 'XMLHttpRequest'])
            ->assertOk();
    }
}
