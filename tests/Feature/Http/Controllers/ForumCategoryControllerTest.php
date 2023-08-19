<?php
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

use App\Models\Forum;
use App\Models\Permission;
use App\Models\User;

test('show returns an ok response', function (): void {
    $user = User::factory()->create();

    // This Forum has a parent Forum, which makes it a "Forum Category".
    $parentForum = Forum::factory()->create();

    Permission::factory()->create([
        'forum_id' => $parentForum->id,
    ]);

    $forum = Forum::factory()->create([
        'parent_id'               => $parentForum->id,
        'last_post_user_id'       => $user->id,
        'last_post_user_username' => $user->username,
    ]);


    $this->actingAs($user)->get(route('forums.categories.show', ['id' => $forum->id]));
});
