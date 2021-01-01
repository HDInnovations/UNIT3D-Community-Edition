<?php

namespace Database\Seeders;

use App\Models\Privilege;
use App\Models\Role;
use Illuminate\Database\Seeder;

class PrivilegesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Privilege::upsert([
            ['slug' => 'dashboard_can_view', 'name' => 'dashboard_can_view'],
            ['slug' => 'torrent_can_view', 'name' => 'torrent_can_view'],
            ['slug' => 'torrent_can_create', 'name' => 'torrent_can_create'],
            ['slug' => 'torrent_can_update', 'name' => 'torrent_can_update'],
            ['slug' => 'torrent_can_update_title', 'name' => 'torrent_can_update_title'],
            ['slug' => 'torrent_can_update_description', 'name' => 'torrent_can_update_description'],
            ['slug' => 'torrent_can_update_mediainfo', 'name' => 'torrent_can_update_mediainfo'],
            ['slug' => 'torrent_can_update_category', 'name' => 'torrent_can_update_category'],
            ['slug' => 'torrent_can_update_type', 'name' => 'torrent_can_update_type'],
            ['slug' => 'torrent_can_update_resolution', 'name' => 'torrent_can_update_resolution'],
            ['slug' => 'torrent_can_update_meta_ids', 'name' => 'torrent_can_update_meta_ids'],
            ['slug' => 'torrent_can_update_flags', 'name' => 'torrent_can_update_flags'],
            ['slug' => 'torrent_can_delete', 'name' => 'torrent_can_delete'],
            ['slug' => 'torrent_can_download', 'name' => 'torrent_can_download'],
            ['slug' => 'torrent_can_request_reseed', 'name' => 'torrent_can_request_reseed'],
            ['slug' => 'torrent_can_bump', 'name' => 'torrent_can_bump'],
            ['slug' => 'torrent_can_freeleech', 'name' => 'torrent_can_freeleech'],
            ['slug' => 'torrent_can_doubleupload', 'name' => 'torrent_can_doubleupload'],
            ['slug' => 'torrent_can_feature', 'name' => 'torrent_can_feature'],
            ['slug' => 'request_can_viewAny', 'name' => 'request_can_viewAny'],
            ['slug' => 'request_can_view', 'name' => 'request_can_view'],
            ['slug' => 'request_can_create', 'name' => 'request_can_create'],
            ['slug' => 'request_can_update', 'name' => 'request_can_update'],
            ['slug' => 'request_can_update_title', 'name' => 'request_can_update_title'],
            ['slug' => 'request_can_update_description', 'name' => 'request_can_update_description'],
            ['slug' => 'request_can_update_category', 'name' => 'request_can_update_category'],
            ['slug' => 'request_can_update_type', 'name' => 'request_can_update_type'],
            ['slug' => 'request_can_update_resolution', 'name' => 'request_can_update_resolution'],
            ['slug' => 'request_can_update_meta_ids', 'name' => 'request_can_update_meta_ids'],
            ['slug' => 'request_can_delete', 'name' => 'request_can_delete'],
            ['slug' => 'request_can_approve', 'name' => 'request_can_approve'],
            ['slug' => 'request_can_deny', 'name' => 'request_can_deny'],
            ['slug' => 'comment_can_view', 'name' => 'comment_can_view'],
            ['slug' => 'comment_can_create', 'name' => 'comment_can_create'],
            ['slug' => 'comment_can_update', 'name' => 'comment_can_update'],
            ['slug' => 'comment_can_delete', 'name' => 'comment_can_delete'],
            ['slug' => 'forum_can_viewAny', 'name' => 'forum_can_viewAny'],
            ['slug' => 'forum_can_view', 'name' => 'forum_can_view'],
            ['slug' => 'forum_can_sticky', 'name' => 'forum_can_sticky'],
            ['slug' => 'forum_can_bump', 'name' => 'forum_can_bump'],
            ['slug' => 'forum_can_create_topic', 'name' => 'forum_can_create_topic'],
            ['slug' => 'forum_can_delete_topic', 'name' => 'forum_can_delete_topic'],
            ['slug' => 'forum_can_comment', 'name' => 'forum_can_comment'],
            ['slug' => 'forum_can_update_comment', 'name' => 'forum_can_update_comment'],
            ['slug' => 'forum_can_delete_comment', 'name' => 'forum_can_delete_comment'],
            ['slug' => 'playlist_can_viewAny', 'name' => 'playlist_can_viewAny'],
            ['slug' => 'playlist_can_view', 'name' => 'playlist_can_view'],
            ['slug' => 'playlist_can_create', 'name' => 'playlist_can_create'],
            ['slug' => 'playlist_can_update', 'name' => 'playlist_can_update'],
            ['slug' => 'playlist_can_delete', 'name' => 'playlist_can_delete'],
            ['slug' => 'playlist_can_download_all', 'name' => 'playlist_can_download_all'],
            ['slug' => 'subtitle_can_viewAny', 'name' => 'subtitle_can_viewAny'],
            ['slug' => 'subtitle_can_view', 'name' => 'subtitle_can_view'],
            ['slug' => 'subtitle_can_create', 'name' => 'subtitle_can_create'],
            ['slug' => 'subtitle_can_update', 'name' => 'subtitle_can_update'],
            ['slug' => 'subtitle_can_delete', 'name' => 'subtitle_can_delete'],
            ['slug' => 'subtitle_can_download', 'name' => 'subtitle_can_download'],
            ['slug' => 'movie_can_create', 'name' => 'movie_can_create'],
            ['slug' => 'movie_can_update', 'name' => 'movie_can_update'],
            ['slug' => 'movie_can_update_title', 'name' => 'movie_can_update_title'],
            ['slug' => 'movie_can_update_original_language', 'name' => 'movie_can_update_original_language'],
            ['slug' => 'movie_can_update_backdrop', 'name' => 'movie_can_update_backdrop'],
            ['slug' => 'movie_can_update_poster', 'name' => 'movie_can_update_poster'],
            ['slug' => 'movie_can_update_overview', 'name' => 'movie_can_update_overview'],
            ['slug' => 'movie_can_update_tagline', 'name' => 'movie_can_update_tagline'],
            ['slug' => 'movie_can_update_runtime', 'name' => 'movie_can_update_runtime'],
            ['slug' => 'movie_can_update_status', 'name' => 'movie_can_update_status'],
            ['slug' => 'tv_can_create', 'name' => 'tv_can_create'],
            ['slug' => 'tv_can_update', 'name' => 'tv_can_update'],
            ['slug' => 'tv_can_update_title', 'name' => 'tv_can_update_title'],
            ['slug' => 'tv_can_update_original_language', 'name' => 'tv_can_update_original_language'],
            ['slug' => 'tv_can_update_backdrop', 'name' => 'tv_can_update_backdrop'],
            ['slug' => 'tv_can_update_poster', 'name' => 'tv_can_update_poster'],
            ['slug' => 'tv_can_update_overview', 'name' => 'tv_can_update_overview'],
            ['slug' => 'tv_can_update_tagline', 'name' => 'tv_can_update_tagline'],
            ['slug' => 'tv_can_update_runtime', 'name' => 'tv_can_update_runtime'],
            ['slug' => 'tv_can_update_status', 'name' => 'tv_can_update_status'],
        ], ['slug'], ['name']);
    }
}
