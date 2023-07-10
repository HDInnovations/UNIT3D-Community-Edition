<?php

namespace Tests\Feature\Http\Controllers;

use PHPUnit\Framework\Attributes\Test;
use App\Models\Article;
use App\Models\User;
use Database\Seeders\GroupsTableSeeder;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\ArticleController
 */
class ArticleControllerTest extends TestCase
{
    #[Test]
    public function index_returns_an_ok_response(): void
    {
        $this->seed(GroupsTableSeeder::class);

        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('articles.index'));

        $response->assertOk();
        $response->assertViewIs('article.index');
        $response->assertViewHas('articles');
    }

    #[Test]
    public function show_returns_an_ok_response(): void
    {
        $this->seed(GroupsTableSeeder::class);

        $article = Article::factory()->create();
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('articles.show', ['article' => $article]));

        $response->assertOk();
        $response->assertViewIs('article.show');
        $response->assertViewHas('article');
    }
}
