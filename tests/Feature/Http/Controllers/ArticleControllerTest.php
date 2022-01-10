<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Article;
use App\Models\User;
use Database\Seeders\GroupsTableSeeder;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\ArticleController
 */
class ArticleControllerTest extends TestCase
{
    /**
     * @test
     */
    public function index_returns_an_ok_response(): void
    {
        $this->seed(GroupsTableSeeder::class);

        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('articles.index'));

        $response->assertOk();
        $response->assertViewIs('article.index');
        $response->assertViewHas('articles');
    }

    /**
     * @test
     */
    public function show_returns_an_ok_response(): void
    {
        $this->seed(GroupsTableSeeder::class);

        $article = Article::factory()->create();
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('articles.show', ['id' => $article->id]));

        $response->assertOk();
        $response->assertViewIs('article.show');
        $response->assertViewHas('article');
    }
}
