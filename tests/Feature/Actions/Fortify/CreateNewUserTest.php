<?php

declare(strict_types=1);

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

use App\Models\Bot;
use App\Models\User;
use App\Models\Chatroom;
use App\Models\Invite;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;

test('user registration is not available when disabled', function (): void {
    User::factory()->system()->create();
    $this->withoutMiddleware();
    config(['other.invite-only' => true]);

    $this->get('/register')
        ->assertOk()
        ->assertSeeText('Open Registration Is Disabled')
    ;
});

test('user registration is available when enabled', function (): void {
    User::factory()->system()->create();
    $this->withoutMiddleware();
    config([
        'other.invite-only' => false,
        'captcha.enabled'   => false,
    ]);

    Bot::factory()->create([
        'command' => 'Systembot',
    ]);
    Chatroom::factory()->create([
        'name' => config('chat.system_chatroom'),
    ]);

    $this->get('/register')
        ->assertOk()
        ->assertDontSeeText('Open Registration Is Disabled')
    ;

    $this->post('/register', [
        'username'              => 'testuser',
        'email'                 => 'unit3d@protnmail.com',
        'password'              => 'password',
        'password_confirmation' => 'password',
    ])->assertRedirectToRoute('home.index');

    assertDatabaseHas('users', [
        'username' => 'testuser',
        'email'    => 'unit3d@protnmail.com',
    ]);
});

test('user can register using invite code', function (): void {
    User::factory()->system()->create();
    $this->withoutMiddleware();
    config([
        'other.invite-only' => true,
        'captcha.enabled'   => false,
    ]);

    Invite::factory()->create([
        'code'        => 'testcode',
        'accepted_at' => null,
        'accepted_by' => null,
        'expires_on'  => now()->addDays(7),
    ]);

    Bot::factory()->create([
        'command' => 'Systembot',
    ]);
    Chatroom::factory()->create([
        'name' => config('chat.system_chatroom'),
    ]);

    $email = fake()->safeEmail;

    $this->get('/register?code=testcode')
        ->assertOk()
        ->assertDontSeeText('Open Registration Is Disabled')
    ;

    $this->post('/register?code=testcode', [
        'username'              => 'testuser',
        'email'                 => $email,
        'password'              => 'password',
        'password_confirmation' => 'password',
    ])
        ->assertSessionHasNoErrors()
        ->assertRedirectToRoute('home.index');

    assertDatabaseHas('users', [
        'username' => 'testuser',
        'email'    => $email,
    ]);
});

test('user cannot register using invalid invite code', function (): void {
    $this->withoutMiddleware();
    config([
        'other.invite-only' => true,
        'captcha.enabled'   => false,
    ]);

    $email = fake()->safeEmail;

    $this->get('/register?code=testcode')
        ->assertOk()
        ->assertDontSeeText('Open Registration Is Disabled')
    ;

    $this->post('/register?code=testcode', [
        'username'              => 'testuser',
        'email'                 => $email,
        'password'              => 'password',
        'password_confirmation' => 'password',
    ])
        ->assertSessionHasErrors('code')
        ->assertRedirectToRoute('home.index');

    assertDatabaseMissing('users', [
        'username' => 'testuser',
        'email'    => $email,
    ]);
});

test('user can register using invite code with internal note assigned', function (): void {
    User::factory()->system()->create();
    $this->withoutMiddleware();
    config([
        'other.invite-only' => true,
        'captcha.enabled'   => false,
    ]);

    $invite = Invite::factory()->create([
        'code'          => 'testcode',
        'accepted_at'   => null,
        'accepted_by'   => null,
        'expires_on'    => now()->addDays(7),
        'internal_note' => 'This is a test note',
    ]);

    Bot::factory()->create([
        'command' => 'Systembot',
    ]);
    Chatroom::factory()->create([
        'name' => config('chat.system_chatroom'),
    ]);

    $email = fake()->safeEmail;

    $this->get('/register?code=testcode')
        ->assertOk()
        ->assertDontSeeText('Open Registration Is Disabled')
    ;

    $this->post('/register?code=testcode', [
        'username'              => 'testuser',
        'email'                 => $email,
        'password'              => 'password',
        'password_confirmation' => 'password',
    ])
        ->assertSessionHasNoErrors()
        ->assertRedirectToRoute('home.index');

    assertDatabaseHas('users', [
        'username' => 'testuser',
        'email'    => $email,
    ]);

    $invite->refresh();

    assertDatabaseHas('user_notes', [
        'message'  => 'This is a test note',
        'staff_id' => $invite->user_id,
        'user_id'  => $invite->accepted_by,
    ]);
});
