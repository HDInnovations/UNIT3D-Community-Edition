<?php

use Illuminate\Database\Migrations\Migration;

return new class () extends Migration {
    public function up(): void
    {
        DB::table('posts')
            ->lazyById()
            ->each(function (object $post): void {
                DB::table('posts')
                    ->where('id', '=', $post->id)
                    ->update([
                        'content' => htmlspecialchars_decode($post->content),
                    ]);
            });

        DB::table('playlists')
            ->lazyById()
            ->each(function (object $playlist): void {
                DB::table('playlists')
                    ->where('id', '=', $playlist->id)
                    ->update([
                        'description' => htmlspecialchars_decode($playlist->description),
                    ]);
            });
    }
};
