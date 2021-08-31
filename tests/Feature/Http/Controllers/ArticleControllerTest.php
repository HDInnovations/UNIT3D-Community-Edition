<?php

use App\Models\Article;
use App\Models\User;
use Database\Seeders\GroupsTableSeeder;
use Tests\TestCase;

uses(TestCase::class);

/**
 * @see \App\Http\Controllers\ArticleController
 */
test('index returns an ok response', function () {
    $this->seed(GroupsTableSeeder::class);

    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('articles.index'));

    $response->assertOk();
    $response->assertViewIs('article.index');
    $response->assertViewHas('articles');
});

test('show returns an ok response', function () {
    $this->seed(GroupsTableSeeder::class);

    $article = Article::factory()->create();
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('articles.show', ['id' => $article->id]));

    $response->assertOk();
    $response->assertViewIs('article.show');
    $response->assertViewHas('article');
});
