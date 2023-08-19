<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D Community Edition is open-sourced software licensed under the GNU Affero General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D Community Edition
 *
 * @author     HDVinnie <hdinnovations@protonmail.com>
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 */

use App\Models\TwoStepAuth;
use App\Models\User;

test('resend returns an ok response', function (): void {
    config(['auth.TwoStepEnabled' => true]);

    $user = User::factory()->create([
        'twostep' => true,
    ]);

    TwoStepAuth::factory()->create([
        'userId' => $user->id,
    ]);

    $response = $this->actingAs($user)->post(route('resend'));
    $response->assertRedirect(route('verificationNeeded'));
});

test('resend aborts with a 404', function (): void {
    config(['auth.TwoStepEnabled' => false]);

    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('resend'));
    $response->assertNotFound();
});

test('show verification returns an ok response', function (): void {
    config(['auth.TwoStepEnabled' => true]);

    $user = User::factory()->create([
        'twostep' => true,
    ]);

    TwoStepAuth::factory()->create([
        'userId' => $user->id,
    ]);

    $response = $this->actingAs($user)->get(route('verificationNeeded'));
    $response->assertOk();
    $response->assertViewIs('auth.twostep-verification');
});

test('show verification aborts with a 404', function (): void {
    config(['auth.TwoStepEnabled' => false]);

    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('verificationNeeded'));
    $response->assertNotFound();
});

test('verify returns an ok response', function (): void {
    config(['auth.TwoStepEnabled' => true]);

    $user = User::factory()->create([
        'twostep' => true,
    ]);

    $twoStep = TwoStepAuth::factory()->create([
        'userId' => $user->id,
    ]);

    $response = $this->actingAs($user)->postJson(route('verify'), [
        'v_input_1' => $twoStep->authCode[0],
        'v_input_2' => $twoStep->authCode[1],
        'v_input_3' => $twoStep->authCode[2],
        'v_input_4' => $twoStep->authCode[3],
    ], ['HTTP_X-Requested-With' => 'XMLHttpRequest']);
    $response->assertOk();
});

test('verify aborts with a 404', function (): void {
    config(['auth.TwoStepEnabled' => false]);

    $user = User::factory()->create([
        'twostep' => true,
    ]);

    $twoStep = TwoStepAuth::factory()->create([
        'userId' => $user->id,
    ]);

    $response = $this->actingAs($user)->postJson(route('verify'), [
        'v_input_1' => $twoStep->authCode[0],
        'v_input_2' => $twoStep->authCode[1],
        'v_input_3' => $twoStep->authCode[2],
        'v_input_4' => $twoStep->authCode[3],
    ], ['HTTP_X-Requested-With' => 'XMLHttpRequest']);
    $response->assertNotFound();
});
