<?php

use App\Models\Bot;
use App\Models\Group;
use App\Models\User;
use Database\Seeders\GroupsTableSeeder;
use Tests\TestCase;

uses(TestCase::class);

/**
 * @see \App\Http\Controllers\Staff\ChatBotController
 */
beforeEach(function () {
});

test('destroy returns an ok response', function () {
    $this->seed(GroupsTableSeeder::class);

    $user = createStaffUser();
    $bot = Bot::factory()->create([
        'is_protected' => false,
    ]);

    $response = $this->actingAs($user)->delete(route('staff.bots.destroy', ['id' => $bot->id]));
    $response->assertRedirect(route('staff.bots.index'));
});

test('disable returns an ok response', function () {
    $this->seed(GroupsTableSeeder::class);

    $user = createStaffUser();
    $bot = Bot::factory()->create();

    $response = $this->actingAs($user)->get(route('staff.bots.disable', ['id' => $bot->id]));
    $response->assertRedirect(route('staff.bots.index'));
});

test('edit returns an ok response', function () {
    $this->seed(GroupsTableSeeder::class);

    $user = createStaffUser();
    $bot = Bot::factory()->create();

    $response = $this->actingAs($user)->get(route('staff.bots.edit', ['id' => $bot->id]));

    $response->assertOk();
    $response->assertViewIs('Staff.chat.bot.edit');
    $response->assertViewHas('bot');
});

test('enable returns an ok response', function () {
    $this->seed(GroupsTableSeeder::class);

    $user = createStaffUser();
    $bot = Bot::factory()->create();

    $response = $this->actingAs($user)->get(route('staff.bots.enable', ['id' => $bot->id]));
    $response->assertRedirect(route('staff.bots.index'));
});

test('index returns an ok response', function () {
    $this->seed(GroupsTableSeeder::class);

    $user = createStaffUser();

    $response = $this->actingAs($user)->get(route('staff.bots.index'));

    $response->assertOk();
    $response->assertViewIs('Staff.chat.bot.index');
    $response->assertViewHas('bots');
});

test('update returns an ok response', function () {
    $this->seed(GroupsTableSeeder::class);

    $user = createStaffUser();
    $bot = Bot::factory()->create();

    $response = $this->actingAs($user)->patch(route('staff.bots.update', ['id' => $bot->id]), [
        'position'     => $bot->position,
        'slug'         => $bot->slug,
        'name'         => $bot->name,
        'command'      => $bot->command,
        'color'        => $bot->color,
        'icon'         => $bot->icon,
        'emoji'        => $bot->emoji,
        'info'         => $bot->info,
        'about'        => $bot->about,
        'help'         => $bot->help,
        'active'       => $bot->active,
        'is_protected' => $bot->is_protected,
        'is_triviabot' => $bot->is_triviabot,
        'is_nerdbot'   => $bot->is_nerdbot,
        'is_systembot' => $bot->is_systembot,
        'is_casinobot' => $bot->is_casinobot,
        'is_betbot'    => $bot->is_betbot,
        'uploaded'     => $bot->uploaded,
        'downloaded'   => $bot->downloaded,
        'fl_tokens'    => $bot->fl_tokens,
        'seedbonus'    => $bot->seedbonus,
        'invites'      => $bot->invites,
    ]);

    $response->assertRedirect(route('staff.bots.edit', ['id' => $bot->id]));
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
