<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class () extends Migration {
    public function up(): void
    {
        DB::table('articles')
            ->lazyById()
            ->each(function (object $article): void {
                /** @var object{id: int, content: string} $article */
                DB::table('articles')
                    ->where('id', '=', $article->id)
                    ->update([
                        'content' => htmlspecialchars_decode($article->content),
                    ]);
            });

        DB::table('comments')
            ->lazyById()
            ->each(function (object $comment): void {
                /** @var object{id: int, content: string} $comment */
                DB::table('comments')
                    ->where('id', '=', $comment->id)
                    ->update([
                        'content' => htmlspecialchars_decode($comment->content),
                    ]);
            });

        DB::table('messages')
            ->lazyById()
            ->each(function (object $message): void {
                /** @var object{id: int, message: string} $message */
                DB::table('messages')
                    ->where('id', '=', $message->id)
                    ->update([
                        'message' => htmlspecialchars_decode($message->message),
                    ]);
            });

        DB::table('user_notes')
            ->lazyById()
            ->each(function (object $userNote): void {
                /** @var object{id: int, message: string} $userNote */
                DB::table('user_notes')
                    ->where('id', '=', $userNote->id)
                    ->update([
                        'message' => htmlspecialchars_decode($userNote->message),
                    ]);
            });

        DB::table('playlists')
            ->lazyById()
            ->each(function (object $playlist): void {
                /** @var object{id: int, description: string} $playlist */
                DB::table('playlists')
                    ->where('id', '=', $playlist->id)
                    ->update([
                        'description' => htmlspecialchars_decode($playlist->description),
                    ]);
            });

        DB::table('posts')
            ->lazyById()
            ->each(function (object $post): void {
                /** @var object{id: int, content: string} $post */
                DB::table('posts')
                    ->where('id', '=', $post->id)
                    ->update([
                        'content' => htmlspecialchars_decode($post->content),
                    ]);
            });

        DB::table('private_messages')
            ->lazyById()
            ->each(function (object $privateMessage): void {
                /** @var object{id: int, message: string} $privateMessage */
                DB::table('private_messages')
                    ->where('id', '=', $privateMessage->id)
                    ->update([
                        'message' => htmlspecialchars_decode($privateMessage->message),
                    ]);
            });

        DB::table('ticket_notes')
            ->lazyById()
            ->each(function (object $ticketNote): void {
                /** @var object{id: int, message: string} $ticketNote */
                DB::table('ticket_notes')
                    ->where('id', '=', $ticketNote->id)
                    ->update([
                        'message' => htmlspecialchars_decode($ticketNote->message),
                    ]);
            });

        DB::table('torrents')
            ->lazyById()
            ->each(function (object $torrent): void {
                /** @var object{id: int, description: string} $torrent */
                DB::table('torrents')
                    ->where('id', '=', $torrent->id)
                    ->update([
                        'description' => htmlspecialchars_decode($torrent->description),
                    ]);
            });

        DB::table('requests')
            ->lazyById()
            ->each(function (object $request): void {
                /** @var object{id: int, description: string} $request */
                DB::table('requests')
                    ->where('id', '=', $request->id)
                    ->update([
                        'description' => htmlspecialchars_decode($request->description),
                    ]);
            });

        DB::table('users')
            ->lazyById()
            ->each(function (object $user): void {
                /** @var object{id: int, about: ?string, signature: ?string} $user */
                DB::table('users')
                    ->where('id', '=', $user->id)
                    ->update([
                        'about'     => htmlspecialchars_decode($user->about ?? ''),
                        'signature' => htmlspecialchars_decode($user->signature ?? ''),
                    ]);
            });
    }
};
