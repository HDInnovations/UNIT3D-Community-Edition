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
use App\Models\ForumPermission;
use App\Models\User;

test('show returns an ok response', function (): void {
    $user = User::factory()->create();

    $forum = Forum::factory()->create();

    ForumPermission::factory()->create([
        'group_id' => $user->group_id,
        'forum_id' => $forum->id,
    ]);

    $this->actingAs($user)->get(route('forums.categories.show', ['id' => $forum->forum_category_id]));
});
