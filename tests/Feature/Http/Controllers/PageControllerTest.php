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

use App\Models\Page;
use App\Models\User;

test('about returns an ok response', function (): void {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('about'));
    $response->assertOk();
    $response->assertViewIs('page.aboutus');
});

test('clientblacklist returns an ok response', function (): void {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('client_blacklist'));
    $response->assertOk();
    $response->assertViewIs('page.blacklist.client');
    $response->assertViewHas('clients');
});

test('index returns an ok response', function (): void {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('pages.index'));
    $response->assertOk();
    $response->assertViewIs('page.index');
    $response->assertViewHas('pages');
});

test('internal returns an ok response', function (): void {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('internal'));
    $response->assertOk();
    $response->assertViewIs('page.internal');
    $response->assertViewHas('internals');
});

test('show returns an ok response', function (): void {
    $user = User::factory()->create();
    $page = Page::factory()->create();

    $response = $this->actingAs($user)->get(route('pages.show', [$page]));
    $response->assertOk();
    $response->assertViewIs('page.page');
    $response->assertViewHas('page', $page);
});

test('staff returns an ok response', function (): void {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('staff'));
    $response->assertOk();
    $response->assertViewIs('page.staff');
    $response->assertViewHas('staff');
});
