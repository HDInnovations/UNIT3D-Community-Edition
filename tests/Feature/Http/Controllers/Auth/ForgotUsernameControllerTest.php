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

use App\Models\User;

test('send username reminder returns an ok response', function (): void {
    config(['captcha.enabled' => false]);

    $user = User::factory()->create();

    $response = $this->post(route('username.email'), [
        'email' => $user->email,
    ]);
    $response->assertRedirect(route('login'));
    $response->assertSessionHas('success', trans('email.username-sent'));
});

test('show forgot username form returns an ok response', function (): void {
    $response = $this->get(route('username.request'));
    $response->assertOk();
    $response->assertViewIs('auth.username');
});
