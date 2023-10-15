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

use App\Enums\UserGroups;
use App\Models\User;
use Database\Seeders\GroupsTableSeeder;
use Illuminate\Support\Str;

test('edit returns an ok response', function (): void {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('users.apikey.edit', [$user]));

    $response->assertOk();
    $response->assertViewIs('user.apikey.edit');
    $response->assertViewHas('user', $user);
});

test('edit aborts with a 403', function (): void {
    $this->seed(GroupsTableSeeder::class);

    $staffUser = User::factory()->create([
        'group_id' => UserGroups::MODERATOR,
    ]);

    $user = User::factory()->create([
        'group_id' => UserGroups::USER,
    ]);

    $response = $this->actingAs($user)->get(route('users.apikey.edit', [$staffUser]));

    $response->assertForbidden();
});

test('update returns an ok response', function (): void {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->patch(route('users.apikey.update', [$user]), [
        'api_token' => Str::random(100),
    ]);

    $response->assertRedirect(route('users.apikey.edit', ['user' => $user]))
        ->assertSessionHas('success', 'Your API key was changed successfully.');
});

test('update aborts with a 403', function (): void {
    $this->seed(GroupsTableSeeder::class);

    $staffUser = User::factory()->create([
        'group_id' => UserGroups::MODERATOR,
    ]);

    $user = User::factory()->create([
        'group_id' => UserGroups::USER,
    ]);

    $response = $this->actingAs($user)->patch(route('users.apikey.update', [$staffUser]));

    $response->assertForbidden();
});
