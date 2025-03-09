<?php

declare(strict_types=1);

/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D Community Edition is open-sourced software licensed under the GNU Affero General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D Community Edition
 *
 * @author     HDVinnie <hdinnovations@protonmail.com>
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('groups', function (Blueprint $table): void {
            $table->boolean('is_editor')->after('is_internal')->default(false);
            $table->index(['is_editor']);
        });

        DB::table('groups')->upsert([
            [
                'name'         => 'Editor',
                'slug'         => 'editor',
                'position'     => 17,
                'color'        => '#15B097',
                'icon'         => config('other.font-awesome').' fa-user-pen',
                'effect'       => 'none',
                'autogroup'    => false,
                'is_owner'     => false,
                'is_admin'     => false,
                'is_modo'      => false,
                'is_editor'    => true,
                'is_internal'  => false,
                'is_trusted'   => true,
                'is_freeleech' => true,
                'is_immune'    => true,
                'can_upload'   => true,
                'level'        => 0,
            ]
        ], 'slug');

        $group = DB::table('groups')->where('slug', '=', 'editor')->first();

        $forumIds = DB::table('forums')->pluck('id');

        foreach ($forumIds as $forumId) {
            DB::table('permissions')->insert([
                'forum_id'    => $forumId,
                'group_id'    => $group->id,
                'read_topic'  => false,
                'reply_topic' => false,
                'start_topic' => false,
            ]);
        }
    }
};
