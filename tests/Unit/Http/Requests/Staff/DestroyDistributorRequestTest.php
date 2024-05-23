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

use App\Http\Requests\Staff\DestroyDistributorRequest;
use App\Models\Distributor;

beforeEach(function (): void {
    $this->subject = new DestroyDistributorRequest();
});

test('rules', function (): void {
    $distributor = Distributor::factory()->create();

    $actual = $this->subject->rules($distributor);

    $this->assertValidationRules([
        'distributor_id' => [
            'required',
        ],
    ], $actual);
});
