<?php

use App\Models\Group;
use App\Models\User;
use Database\Seeders\GroupsTableSeeder;
use Tests\TestCase;

uses(TestCase::class);

/**
 * @see \App\Http\Controllers\Staff\GiftController
 */
beforeEach(function () {
});

test('index returns an ok response', function () {
    $this->seed(GroupsTableSeeder::class);

    $user = createStaffUser();

    $response = $this->actingAs($user)->get(route('staff.gifts.index'));

    $response->assertOk();
    $response->assertViewIs('Staff.gift.index');
});

test('store returns an ok response', function () {
    $this->seed(GroupsTableSeeder::class);

    $staff = createStaffUser();
    $user = User::factory()->create();

    $response = $this->actingAs($staff)->post(route('staff.gifts.store'), [
        'username'  => $user->username,
        'seedbonus' => '100',
        'invites'   => '100',
        'fl_tokens' => '100',
    ]);

    $response->assertRedirect(route('staff.gifts.index'));
});

// Helpers
function createStaffUser()
{
    return User::factory()->create([
        'group_id' => fn () => Group::factory()->create([
            'is_owner' => true,
            'is_admin' => true,
            'is_modo'  => true,
        ])->id,
    ]);
}
