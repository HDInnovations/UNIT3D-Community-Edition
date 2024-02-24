<?php

namespace Tests\Old;

use App\Http\Livewire\PostSearch;
use App\Http\Livewire\SubscribedForum;
use App\Http\Livewire\TopicSearch;
use App\Models\Forum;
use App\Models\ForumPermission;
use App\Models\User;
use Database\Seeders\GroupsTableSeeder;
use Database\Seeders\UsersTableSeeder;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\ForumController
 */
final class ForumControllerTest extends TestCase
{
    #[Test]
    public function index_returns_an_ok_response(): void
    {
        $this->seed(UsersTableSeeder::class);
        $this->seed(GroupsTableSeeder::class);

        $user = User::factory()->create();

        $this->actingAs($user)->get(route('forums.index'))
            ->assertOk()
            ->assertViewIs('forum.index')
            ->assertViewHas('categories')
            ->assertViewHas('num_posts')
            ->assertViewHas('num_forums')
            ->assertViewHas('num_topics');
    }

    #[Test]
    public function latest_posts_returns_an_ok_response(): void
    {
        $this->seed(UsersTableSeeder::class);
        $this->seed(GroupsTableSeeder::class);

        $user = User::factory()->create();

        $this->actingAs($user)->get(route('posts.index'))
            ->assertOk()
            ->assertViewIs('forum.post.index')
            ->assertSeeLivewire(PostSearch::class);
    }

    #[Test]
    public function latest_topics_returns_an_ok_response(): void
    {
        $this->seed(UsersTableSeeder::class);
        $this->seed(GroupsTableSeeder::class);

        $user = User::factory()->create();

        $this->actingAs($user)->get(route('topics.index'))
            ->assertOk()
            ->assertViewIs('forum.topic.index')
            ->assertSeeLivewire(TopicSearch::class);
    }

    #[Test]
    public function show_forum_returns_an_ok_response(): void
    {
        $this->seed(UsersTableSeeder::class);
        $this->seed(GroupsTableSeeder::class);

        $user = User::factory()->create();

        $forum = Forum::factory()->create();

        $permissions = ForumPermission::factory()->create([
            'forum_id'   => $forum->id,
            'group_id'   => $user->group_id,
            'read_topic' => true,
        ]);

        $this->actingAs($user)->get(route('forums.show', ['id' => $forum->id]))
            ->assertRedirect(route('forums.categories.show', ['id' => $forum->id]));
    }

    #[Test]
    public function subscriptions_returns_an_ok_response(): void
    {
        $this->seed(UsersTableSeeder::class);
        $this->seed(GroupsTableSeeder::class);

        $user = User::factory()->create();

        $this->actingAs($user)->get(route('subscriptions.index'))
            ->assertOk()
            ->assertViewIs('forum.subscriptions')
            ->assertSeeLivewire(SubscribedForum::class);
    }
}
