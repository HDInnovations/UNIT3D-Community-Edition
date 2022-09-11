<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Article;
use App\Models\Comment;
use App\Models\Playlist;
use App\Models\Torrent;
use App\Models\TorrentRequest;
use App\Models\User;
use Database\Seeders\BotsTableSeeder;
use Database\Seeders\ChatroomTableSeeder;
use Database\Seeders\GroupsTableSeeder;
use Database\Seeders\UsersTableSeeder;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\CommentController
 */
class CommentControllerTest extends TestCase
{
    /**
     * @test
     */
    public function article_returns_an_ok_response(): void
    {
        $this->seed(UsersTableSeeder::class);
        $this->seed(GroupsTableSeeder::class);
        $this->seed(BotsTableSeeder::class);
        $this->seed(ChatroomTableSeeder::class);

        $user = User::factory()->create([
            'can_comment' => true,
        ]);

        $article = Article::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->post(route('comment_article', ['id' => $article->id]), [
            'content'   => 'foo',
            'anonymous' => '0',
        ]);

        $response->assertRedirect(route('articles.show', $article->id))
            ->assertSessionHas('success', 'Your Comment Has Been Added!');
    }

    /**
     * @test
     */
    public function delete_comment_returns_an_ok_response(): void
    {
        $this->seed(UsersTableSeeder::class);
        $this->seed(GroupsTableSeeder::class);
        $this->seed(BotsTableSeeder::class);
        $this->seed(ChatroomTableSeeder::class);

        $user = User::factory()->create([
            'can_comment' => true,
        ]);

        $comment = Comment::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->delete(route('comment_delete', ['comment_id' => $comment->id]));

        $response->assertRedirect(route('home.index'))
            ->assertSessionHas('success', 'Comment Has Been Deleted.');
    }

    /**
     * @test
     */
    public function edit_comment_returns_an_ok_response(): void
    {
        $this->seed(UsersTableSeeder::class);
        $this->seed(GroupsTableSeeder::class);

        $user = User::factory()->create([
            'can_comment' => true,
        ]);

        $comment = Comment::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->post(route('comment_edit', ['comment_id' => $comment->id]), [
            'comment-edit' => 'bar',
        ]);

        $response->assertRedirect()
            ->assertSessionHas('success', 'Comment Has Been Edited.');
    }

    /**
     * @test
     */
    public function playlist_returns_an_ok_response(): void
    {
        $this->seed(UsersTableSeeder::class);
        $this->seed(GroupsTableSeeder::class);
        $this->seed(BotsTableSeeder::class);
        $this->seed(ChatroomTableSeeder::class);

        $user = User::factory()->create([
            'can_comment' => true,
        ]);

        $playlist = Playlist::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->post(route('comment_playlist', ['id' => $playlist->id]), [
            'content'   => 'foo',
            'anonymous' => 0,
        ]);

        $response->assertRedirect(route('playlists.show', [
            'id'   => $playlist->id,
            'hash' => '#comments',
        ]))->assertSessionHas('success', 'Your Comment Has Been Added!');
    }

    /**
     * @test
     */
    public function quickthanks_returns_an_ok_response(): void
    {
        $this->seed(UsersTableSeeder::class);
        $this->seed(GroupsTableSeeder::class);
        $this->seed(BotsTableSeeder::class);
        $this->seed(ChatroomTableSeeder::class);

        $user = User::factory()->create([
            'can_comment' => true,
        ]);

        $torrent = Torrent::factory()->create([
            'user_id' => $user->id,
            'status'  => 1,
        ]);

        $response = $this->actingAs($user)->post(route('comment_thanks', ['id' => $torrent->id]));

        $response->assertRedirect(route('torrent', ['id' => $torrent->id]))
            ->assertSessionHas('success', 'Your Comment Has Been Added!');
    }

    /**
     * @test
     */
    public function request_returns_an_ok_response(): void
    {
        $this->seed(UsersTableSeeder::class);
        $this->seed(GroupsTableSeeder::class);
        $this->seed(BotsTableSeeder::class);
        $this->seed(ChatroomTableSeeder::class);

        $user = User::factory()->create([
            'can_comment' => true,
        ]);

        $torrentRequest = TorrentRequest::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->post(route('comment_request', ['id' => $torrentRequest->id]), [
            'content'   => 'foo',
            'anonymous' => 0,
        ]);

        $response->assertRedirect(route('request', ['id' => $torrentRequest->id, 'hash' => '#comments']))
            ->assertSessionHas('success', 'Your Comment Has Been Added!');
    }

    /**
     * @test
     */
    public function torrent_returns_an_ok_response(): void
    {
        $this->seed(UsersTableSeeder::class);
        $this->seed(GroupsTableSeeder::class);
        $this->seed(BotsTableSeeder::class);
        $this->seed(ChatroomTableSeeder::class);

        $user = User::factory()->create([
            'can_comment' => true,
        ]);

        $torrent = Torrent::factory()->create([
            'user_id' => $user->id,
            'status'  => 1,
        ]);

        $response = $this->actingAs($user)->post(route('comment_torrent', ['id' => $torrent->id]), [
            'content'   => 'foo',
            'anonymous' => 0,
        ]);

        $response->assertRedirect(route('torrent', ['id' => $torrent->id, 'hash' => '#comments']))
            ->assertSessionHas('success', 'Your Comment Has Been Added!');
    }
}
