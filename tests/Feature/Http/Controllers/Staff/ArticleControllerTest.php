<?php

namespace Tests\Feature\Http\Controllers\Staff;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use PHPUnit\Framework\Attributes\Test;
use App\Models\Article;
use App\Models\Group;
use App\Models\User;
use Database\Seeders\GroupsTableSeeder;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Staff\ArticleController
 */
final class ArticleControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function createStaffUser(): Collection|Model
    {
        return User::factory()->create([
            'group_id' => fn () => Group::factory()->create([
                'is_owner' => true,
                'is_admin' => true,
                'is_modo'  => true,
            ])->id,
        ]);
    }

    #[Test]
    public function create_returns_an_ok_response(): void
    {
        $this->seed(GroupsTableSeeder::class);

        $user = $this->createStaffUser();

        $response = $this->actingAs($user)->get(route('staff.articles.create'));

        $response->assertOk();
        $response->assertViewIs('Staff.article.create');
    }

    #[Test]
    public function destroy_returns_an_ok_response(): void
    {
        $this->seed(GroupsTableSeeder::class);

        $user = $this->createStaffUser();
        $article = Article::factory()->create();

        $response = $this->actingAs($user)->delete(route('staff.articles.destroy', ['article' => $article]));
        $response->assertRedirect(route('staff.articles.index'));
    }

    #[Test]
    public function edit_returns_an_ok_response(): void
    {
        $this->seed(GroupsTableSeeder::class);

        $user = $this->createStaffUser();
        $article = Article::factory()->create();

        $response = $this->actingAs($user)->get(route('staff.articles.edit', ['article' => $article]));

        $response->assertOk();
        $response->assertViewIs('Staff.article.edit');
        $response->assertViewHas('article');
    }

    #[Test]
    public function index_returns_an_ok_response(): void
    {
        $this->seed(GroupsTableSeeder::class);

        $user = $this->createStaffUser();

        $response = $this->actingAs($user)->get(route('staff.articles.index'));

        $response->assertOk();
        $response->assertViewIs('Staff.article.index');
        $response->assertViewHas('articles');
    }

    #[Test]
    public function store_returns_an_ok_response(): void
    {
        $this->seed(GroupsTableSeeder::class);

        $user = $this->createStaffUser();
        $article = Article::factory()->make();

        $response = $this->actingAs($user)->post(route('staff.articles.store'), [
            'title'   => $article->title,
            'content' => $article->content,
            'user_id' => $user->id,
        ]);

        $response->assertRedirect(route('staff.articles.index'));
    }

    #[Test]
    public function update_returns_an_ok_response(): void
    {
        $this->seed(GroupsTableSeeder::class);

        $user = $this->createStaffUser();
        $article = Article::factory()->create();

        $response = $this->actingAs($user)->post(route('staff.articles.update', ['article' => $article]), [
            'title'   => $article->title,
            'content' => $article->content,
            'user_id' => $user->id,
        ]);

        $response->assertRedirect(route('staff.articles.index'));
    }
}
