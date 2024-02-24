<?php

use Illuminate\Database\Migrations\Migration;

return new class () extends Migration {
    public function up(): void
    {
        // This migration will move files from public to storage via filesystems

        // Article Images
        $articles = DB::table('articles')
            ->whereNotNull('image')
            ->pluck('image')
            ->toArray();

        foreach ($articles as $article) {
            $oldPath = public_path('files/img/'.$article);
            $newPath = storage_path('app/images/articles/'.$article);

            if (file_exists($oldPath)) {
                Storage::move($oldPath, $newPath);
            }
        }

        // Attachments
        $attachments = DB::table('ticket_attachments')
            ->pluck('file_name')
            ->toArray();

        foreach ($attachments as $attachment) {
            $oldPath = public_path('files/attachments/'.$attachment);
            $newPath = storage_path('app/files/attachments/'.$attachment);

            if (file_exists($oldPath)) {
                Storage::move($oldPath, $newPath);
            }
        }

        // Avatars
        $users = DB::table('users')
            ->whereNotNull('image')
            ->where('image', '!=', 'profil.png')
            ->pluck('image')
            ->toArray();

        foreach ($users as $user) {
            $oldPath = public_path('files/img/'.$user);

            $filename = uniqid('', true).'.'.File::extension($user);
            $newPath = storage_path('app/images/avatars/'.$filename);

            if (file_exists($oldPath)) {
                DB::table('users')
                    ->where('id', '=', $user->id)
                    ->update(['image' => $filename]);

                Storage::move($oldPath, $newPath);
            }
        }

        // Category Images
        $categories = DB::table('categories')
            ->whereNotNull('image')
            ->pluck('image')
            ->toArray();

        foreach ($categories as $category) {
            $oldPath = public_path('files/img/'.$category);
            $newPath = storage_path('app/images/categories/'.$category);

            if (file_exists($oldPath)) {
                Storage::move($oldPath, $newPath);
            }
        }

        // Playlist Images
        $playlists = DB::table('playlists')
            ->whereNotNull('cover_image')
            ->pluck('cover_image')
            ->toArray();

        foreach ($playlists as $playlist) {
            $oldPath = public_path('files/img/'.$playlist);
            $newPath = storage_path('app/images/playlists/'.$playlist);

            if (file_exists($oldPath)) {
                Storage::move($oldPath, $newPath);
            }
        }

        // Subtitles
        $subtitles = DB::table('subtitles')
            ->pluck('file_name')
            ->toArray();

        foreach ($subtitles as $subtitle) {
            $oldPath = public_path('files/subtitles/'.$subtitle);
            $newPath = storage_path('app/files/subtitles/'.$subtitle);

            if (file_exists($oldPath)) {
                Storage::move($oldPath, $newPath);
            }
        }

        // Torrent Images
        $torrents = DB::table('torrents')
            ->pluck('id')
            ->toArray();

        foreach ($torrents as $torrent) {
            $banner_filename = 'torrent-banner_'.$torrent->id.'.jpg';
            $filename_cover = 'torrent-cover_'.$torrent->id.'.jpg';

            $oldBannerPath = public_path('files/img/'.$banner_filename);
            $newBannerPath = storage_path('app/images/torrents/banners/'.$banner_filename);

            $oldCoverPath = public_path('files/img/'.$filename_cover);
            $newCoverPath = storage_path('app/images/torrents/covers/'.$filename_cover);

            if (file_exists($oldBannerPath)) {
                Storage::move($oldBannerPath, $newBannerPath);
            }

            if (file_exists($oldCoverPath)) {
                Storage::move($oldCoverPath, $newCoverPath);
            }
        }

        // Torrents
        $torrents = DB::table('torrents')
            ->pluck('file_name')
            ->toArray();

        foreach ($torrents as $torrent) {
            $oldPath = public_path('files/torrents/'.$torrent);
            $newPath = storage_path('app/files/torrents/'.$torrent);

            if (file_exists($oldPath)) {
                Storage::move($oldPath, $newPath);
            }
        }
    }
};
