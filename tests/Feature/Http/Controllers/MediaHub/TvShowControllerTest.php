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

use App\Http\Livewire\TvSearch;
use App\Models\Tv;
use App\Models\User;

test('index returns an ok response', function (): void {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('mediahub.shows.index'));
    $response->assertOk();
    $response->assertViewIs('mediahub.tv.index');
    $response->assertSeeLivewire(TvSearch::class);
});

test('show returns an ok response', function (): void {
    $show = Tv::factory()->create();
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('mediahub.shows.show', ['id' => $show->id]));
    $response->assertOk();
    $response->assertViewIs('mediahub.tv.show');
    $response->assertViewHas(['show' => $show]);
});
