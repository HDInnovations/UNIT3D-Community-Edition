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

use App\Enums\UserGroup;
use App\Models\User;
use Database\Seeders\GroupsTableSeeder;

test('index returns an ok response', function (): void {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('users.earnings.index', [$user]));

    $response->assertOk();
    $response->assertViewIs('user.earning.index');
    $response->assertViewHas('user', $user);
    $response->assertViewHas('bon');
    $response->assertViewHas('dying');
    $response->assertViewHas('legendary');
    $response->assertViewHas('old');
    $response->assertViewHas('huge');
    $response->assertViewHas('large');
    $response->assertViewHas('regular');
    $response->assertViewHas('participant');
    $response->assertViewHas('teamplayer');
    $response->assertViewHas('committed');
    $response->assertViewHas('mvp');
    $response->assertViewHas('legend');
    $response->assertViewHas('total');
});

test('index aborts with a 403', function (): void {
    $this->seed(GroupsTableSeeder::class);

    $user = User::factory()->create([
        'group_id' => UserGroup::MODERATOR->value,
    ]);

    $authUser = User::factory()->create([
        'group_id' => UserGroup::USER->value,
    ]);

    $response = $this->actingAs($authUser)->get(route('users.earnings.index', [$user]));

    $response->assertForbidden();
});
