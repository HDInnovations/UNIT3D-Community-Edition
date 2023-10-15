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
use Database\Seeders\GroupsTableSeeder;
use Database\Seeders\UsersTableSeeder;

test('index returns an ok response', function (): void {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('forums.index'));
    $response->assertOk();
    $response->assertViewIs('forum.index');
    $response->assertViewHas('categories');
    $response->assertViewHas('num_posts');
    $response->assertViewHas('num_forums');
    $response->assertViewHas('num_topics');
});

test('show returns an ok response', function (): void {
    $this->seed(UsersTableSeeder::class);
    $this->seed(GroupsTableSeeder::class);

    $user = User::factory()->create();

    $forum = Forum::factory()->create([
        'parent_id'               => null, // This Forum does not have a parent, which makes it a proper Forum and not a "Forum Category".
        'last_post_user_id'       => $user->id,
        'last_post_user_username' => $user->username,
        'last_topic_id'           => null,
    ]);

    Permission::factory()->create([
        'forum_id'   => $forum->id,
        'show_forum' => true,
    ]);

    $response = $this->actingAs($user)->get(route('forums.show', ['id' => $forum->id]));
    $response->assertRedirect(route('forums.categories.show', ['id' => $forum->id]));
});
