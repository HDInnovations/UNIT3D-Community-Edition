<?php

namespace Tests\Feature\Http\Controllers\Staff;

use App\Models\Article;
use App\Models\User;
use GroupsTableSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Staff\ArticleController
 */
class ArticleControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function create_returns_an_ok_response()
    {
        $this->seed(GroupsTableSeeder::class);

        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->get(route('staff.articles.create'));

        $response->assertOk();
        $response->assertViewIs('Staff.article.create');
    }

    /**
     * @test
     */
    public function destroy_returns_an_ok_response()
    {
        $this->seed(GroupsTableSeeder::class);

        $user = factory(User::class)->create();
        $article = factory(Article::class)->create();

        $response = $this->actingAs($user)->delete(route('staff.articles.destroy', ['id' => $article->id]));
        $response->assertRedirect(route('staff.articles.index'));
    }

    /**
     * @test
     */
    public function edit_returns_an_ok_response()
    {
        $this->seed(GroupsTableSeeder::class);

        $article = factory(Article::class)->create();
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->get(route('staff.articles.edit', ['id' => $article->id]));

        $response->assertOk();
        $response->assertViewIs('Staff.article.edit');
        $response->assertViewHas('article');
    }

    /**
     * @test
     */
    public function index_returns_an_ok_response()
    {
        $this->seed(GroupsTableSeeder::class);

        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->get(route('staff.articles.index'));

        $response->assertOk();
        $response->assertViewIs('Staff.article.index');
        $response->assertViewHas('articles');
    }

    /**
     * @test
     */
    public function store_returns_an_ok_response()
    {
        $this->seed(GroupsTableSeeder::class);

        $user = factory(User::class)->create();
        $article = factory(Article::class)->create();

        $response = $this->actingAs($user)->post(route('staff.articles.store'), [
            'title'   => $article->title,
            'slug'    => $article->slug,
            'content' => $article->content,
            'user_id' => $user->id,
        ]);

        $response->assertRedirect(route('staff.articles.index'));
    }

    /**
     * @test
     */
    public function update_returns_an_ok_response()
    {
        $this->seed(GroupsTableSeeder::class);

        $article = factory(Article::class)->create();
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->post(route('staff.articles.update', ['id' => $article->id]), [
            'title'   => $article->title,
            'slug'    => $article->slug,
            'content' => $article->content,
            'user_id' => $user->id,
        ]);

        $response->assertRedirect(route('staff.articles.index'));
    }
}
