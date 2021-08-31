<?php

use App\Models\Category;
use App\Models\User;
use Database\Seeders\GroupsTableSeeder;
use Tests\TestCase;

uses(TestCase::class);

/**
 * @see \App\Http\Controllers\CategoryController
 */
beforeEach(function () {
    $this->seed(GroupsTableSeeder::class);
});

test('index returns an ok response', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('categories.index'));

    $response->assertOk()
        ->assertViewIs('category.index')
        ->assertViewHas('categories');
});

test('show returns an ok response', function () {
    $category = Category::factory()->create();

    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('categories.show', ['id' => $category->id]));

    $response->assertOk()
        ->assertViewIs('category.show')
        ->assertViewHas('torrents')
        ->assertViewHas('user')
        ->assertViewHas('category')
        ->assertViewHas('personal_freeleech');
});
