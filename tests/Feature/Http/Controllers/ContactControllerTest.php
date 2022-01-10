<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\User;
use Database\Seeders\GroupsTableSeeder;
use Database\Seeders\UsersTableSeeder;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\ContactController
 */
class ContactControllerTest extends TestCase
{
    /** @test */
    public function index_returns_an_ok_response(): void
    {
        $this->seed(UsersTableSeeder::class);
        $this->seed(GroupsTableSeeder::class);

        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('contact.index'));

        $response->assertOk()
            ->assertViewIs('contact.index');
    }

    /** @test */
    public function store_returns_an_ok_response(): void
    {
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
    }
}
