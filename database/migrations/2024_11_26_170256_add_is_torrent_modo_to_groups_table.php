<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('groups', function (Blueprint $table): void {
            $table->boolean('is_torrent_modo')->default(false)->after('is_editor');
            $table->index(['is_torrent_modo']);
        });

        DB::table('groups')->upsert([
            [
                'name'            => 'Torrent Moderator',
                'slug'            => 'torrent-moderator',
                'position'        => 17,
                'color'           => '#15B097',
                'icon'            => config('other.font-awesome').' fa-badge-check',
                'effect'          => 'none',
                'autogroup'       => false,
                'is_owner'        => false,
                'is_admin'        => false,
                'is_modo'         => false,
                'is_torrent_modo' => true,
                'is_editor'       => true,
                'is_internal'     => false,
                'is_trusted'      => true,
                'is_freeleech'    => true,
                'is_immune'       => true,
                'can_upload'      => true,
                'level'           => 0,
            ]
        ], 'slug');

        $group = DB::table('groups')->where('slug', '=', 'torrent-moderator')->first();

        $forumIds = DB::table('forums')->pluck('id');

        foreach ($forumIds as $forumId) {
            DB::table('forum_permissions')->insert([
                'forum_id'    => $forumId,
                'group_id'    => $group->id,
                'read_topic'  => false,
                'reply_topic' => false,
                'start_topic' => false,
            ]);
        }

        $staffGroups = DB::table('groups')->where('is_modo', '=', true)->get();

        foreach ($staffGroups as $staffGroup) {
            DB::table('groups')->where('id', $staffGroup->id)->update(['is_torrent_modo' => true]);
        }
    }
};
