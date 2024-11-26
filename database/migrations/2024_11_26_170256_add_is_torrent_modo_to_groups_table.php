<?php

declare(strict_types=1);

use App\Models\Forum;
use App\Models\ForumPermission;
use App\Models\Group;
use App\Services\Unit3dAnnounce;
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

        Group::updateOrCreate(
            ['slug' => 'torrent-mod'],
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
        );

        $group = Group::where('slug', '=', 'torrent-moderator')->first();

        foreach (Forum::pluck('id') as $collection) {
            ForumPermission::create([
                'forum_id'    => $collection,
                'group_id'    => $group->id,
                'read_topic'  => false,
                'reply_topic' => false,
                'start_topic' => false,
            ]);
        }

        Unit3dAnnounce::addGroup($group);

        $staffGroups = Group::where('is_modo', '=', true)->get();

        foreach ($staffGroups as $staffGroup) {
            $staffGroup->update(['is_torrent_modo' => true]);
        }
    }
};
