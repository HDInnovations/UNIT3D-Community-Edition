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

use App\Models\User;

test('index returns an ok response', function (): void {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('home.index'));
    $response->assertOk();
    $response->assertViewIs('home.index');
    $response->assertViewHas('user');
    $response->assertViewHas('users');
    $response->assertViewHas('groups');
    $response->assertViewHas('articles');
    $response->assertViewHas('topics');
    $response->assertViewHas('posts');
    $response->assertViewHas('featured');
    $response->assertViewHas('poll');
});
