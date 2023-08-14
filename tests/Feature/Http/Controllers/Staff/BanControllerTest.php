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

use App\Http\Controllers\Staff\BanController;
use App\Http\Requests\Staff\StoreBanRequest;
use App\Models\Group;
use App\Models\User;
use Database\Seeders\GroupsTableSeeder;

beforeEach(function (): void {
    $this->staffUser = User::factory()->create([
        'group_id' => fn () => Group::factory()->create([
            'is_owner' => true,
            'is_admin' => true,
            'is_modo'  => true,
        ])->id,
    ]);
});

test('index returns an ok response', function (): void {
    $response = $this->actingAs($this->staffUser)->get(route('staff.bans.index'));
    $response->assertOk();
    $response->assertViewIs('Staff.ban.index');
    $response->assertViewHas('bans');
});

test('store validates with a form request', function (): void {
    $this->assertActionUsesFormRequest(
        BanController::class,
        'store',
        StoreBanRequest::class
    );
});

test('store returns an ok response', function (): void {
    $this->seed(GroupsTableSeeder::class);

    $user = User::factory()->create();

    $response = $this->actingAs($this->staffUser)->post(route('staff.bans.store'), [
        'owned_by'   => $user->id,
        'ban_reason' => 'Test Ban Reason',
    ]);
    $response->assertRedirect(route('users.show', $user));
    $response->assertSessionHas('success', 'User Is Now Banned!');
});

test('store aborts with a 403', function (): void {
    $this->seed(GroupsTableSeeder::class);

    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('staff.bans.store'), [
        'owned_by'   => $this->staffUser->id,
        'ban_reason' => 'Test Ban Reason',
    ]);
    $response->assertForbidden();
});
