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

use App\Http\Requests\Staff\DestroyRegionRequest;
use App\Models\Region;

beforeEach(function (): void {
    $this->subject = new DestroyRegionRequest();
});

test('rules', function (): void {
    $region = Region::factory()->create();

    $actual = $this->subject->rules($region);

    $this->assertValidationRules([
        'region_id' => [
            'required',
        ],
    ], $actual);
});
