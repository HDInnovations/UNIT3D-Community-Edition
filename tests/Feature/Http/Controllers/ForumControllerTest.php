<?php

use App\Models\Forum;
use App\Models\Permission;
use App\Models\User;
use Database\Seeders\GroupsTableSeeder;
use Database\Seeders\UsersTableSeeder;
use Tests\TestCase;

uses(TestCase::class);

/**
 * @see \App\Http\Controllers\ForumController
 */
test('index returns an ok response', function () {
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
});

test('latest posts returns an ok response', function () {
    $this->seed(UsersTableSeeder::class);
    $this->seed(GroupsTableSeeder::class);

    $user = User::factory()->create();

    $this->actingAs($user)->get(route('forum_latest_posts'))
        ->assertOk()
        ->assertViewIs('forum.latest_posts')
        ->assertViewHas('results')
        ->assertViewHas('user')
        ->assertViewHas('num_posts')
        ->assertViewHas('num_forums')
        ->assertViewHas('num_topics');
});

test('latest topics returns an ok response', function () {
    $this->seed(UsersTableSeeder::class);
    $this->seed(GroupsTableSeeder::class);

    $user = User::factory()->create();

    $this->actingAs($user)->get(route('forum_latest_topics'))
        ->assertOk()
        ->assertViewIs('forum.latest_topics')
        ->assertViewHas('results')
        ->assertViewHas('user')
        ->assertViewHas('num_posts')
        ->assertViewHas('num_forums')
        ->assertViewHas('num_topics');
});

test('search topics returns an ok response', function () {
    $this->seed(UsersTableSeeder::class);
    $this->seed(GroupsTableSeeder::class);

    $user = User::factory()->create();

    $this->actingAs($user)->get(route('forum_search_form'))
        ->assertOk()
        ->assertViewIs('forum.results_topics')
        ->assertViewHas('categories')
        ->assertViewHas('results')
        ->assertViewHas('user')
        ->assertViewHas('name')
        ->assertViewHas('body')
        ->assertViewHas('num_posts')
        ->assertViewHas('num_forums')
        ->assertViewHas('num_topics')
        ->assertViewHas('params');
});

test('search posts returns an ok response', function () {
    $this->seed(UsersTableSeeder::class);
    $this->seed(GroupsTableSeeder::class);

    $user = User::factory()->create();

    $this->actingAs($user)->call('GET', route('forum_search_form', ['body' => 1]))
        ->assertOk()
        ->assertViewIs('forum.results_posts')
        ->assertViewHas('categories')
        ->assertViewHas('results')
        ->assertViewHas('user')
        ->assertViewHas('name')
        ->assertViewHas('body')
        ->assertViewHas('num_posts')
        ->assertViewHas('num_forums')
        ->assertViewHas('num_topics')
        ->assertViewHas('params');
});

test('show category returns an ok response', function () {
    $this->seed(UsersTableSeeder::class);
    $this->seed(GroupsTableSeeder::class);

    // This Forum has a parent Forum, which makes it a "Forum Category".

    $parentForum = Forum::factory()->create();

    $permissions = Permission::factory()->create([
        'forum_id'   => $parentForum->id,
        'show_forum' => true,
    ]);

    $forum = Forum::factory()->create([
        'parent_id' => $parentForum->id,
    ]);

    // Note that permissions are not necessary on the parent Forum, and
    // the Group ID is instead derived from the child ("Forum Category")
    // permissions.

    $user = User::factory()->create([
        'group_id' => $permissions['group_id'],
    ]);

    $this->actingAs($user)->get(route('forums.show', ['id' => $forum->id]))
        ->assertViewIs('forum.display')
        ->assertViewHas('forum')
        ->assertViewHas('topics')
        ->assertViewHas('category')
        ->assertViewHas('num_posts')
        ->assertViewHas('num_forums')
        ->assertViewHas('num_topics');
});

test('show forum returns an ok response', function () {
    $this->seed(UsersTableSeeder::class);
    $this->seed(GroupsTableSeeder::class);

    // This Forum does not have a parent, which makes it a proper Forum
    // (and not a "Forum Category").

    $forum = Forum::factory()->create([
        'parent_id' => 0,
    ]);

    $permissions = Permission::factory()->create([
        'forum_id'   => $forum->id,
        'show_forum' => true,
    ]);

    $user = User::factory()->create([
        'group_id' => $permissions['group_id'],
    ]);

    $this->actingAs($user)->get(route('forums.show', ['id' => $forum->id]))
        ->assertRedirect(route('forums.categories.show', ['id' => $forum->id]));
});

test('subscriptions returns an ok response', function () {
    $this->seed(UsersTableSeeder::class);
    $this->seed(GroupsTableSeeder::class);

    $user = User::factory()->create();

    $this->actingAs($user)->get(route('forum_subscriptions'))
        ->assertOk()
        ->assertViewIs('forum.subscriptions')
        ->assertViewHas('results')
        ->assertViewHas('user')
        ->assertViewHas('name')
        ->assertViewHas('body')
        ->assertViewHas('num_posts')
        ->assertViewHas('num_forums')
        ->assertViewHas('num_topics')
        ->assertViewHas('params')
        ->assertViewHas('forum_neos')
        ->assertViewHas('topic_neos');
});
