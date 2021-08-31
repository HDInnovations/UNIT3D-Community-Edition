<?php

use App\Models\User;
use Database\Seeders\GroupsTableSeeder;
use Database\Seeders\UsersTableSeeder;
use Tests\TestCase;

uses(TestCase::class);

/**
 * @see \App\Http\Controllers\ContactController
 */
test('index returns an ok response', function () {
    $this->seed(UsersTableSeeder::class);
    $this->seed(GroupsTableSeeder::class);

    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('contact.index'));

    $response->assertOk()
        ->assertViewIs('contact.index');
});

test('store returns an ok response', function () {
    $this->seed(UsersTableSeeder::class);
    $this->seed(GroupsTableSeeder::class);

    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('contact.store'), [
        'email'        => 'foo@bar.com',
        'contact-name' => 'Foo Bar',
        'message'      => 'Hello, world!',
    ]);

    $response->assertRedirect(route('home.index'))
        ->assertSessionHas('success', 'Your Message Was Successfully Sent');
});
