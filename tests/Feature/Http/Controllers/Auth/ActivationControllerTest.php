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

use App\Models\UserActivation;
use Database\Seeders\GroupsTableSeeder;

test('activate returns an ok response', function (): void {
    $this->seed(GroupsTableSeeder::class);

    $token = UserActivation::factory()->create()->token;

    $response = $this->get(route('activate', ['token' => $token]));
    $response->assertRedirect(route('login'));
    $response->assertSessionHas('success', trans('auth.activation-success'));
});
