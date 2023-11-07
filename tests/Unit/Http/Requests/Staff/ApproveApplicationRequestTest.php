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

use App\Http\Requests\Staff\ApproveApplicationRequest;

beforeEach(function (): void {
    $this->subject = new ApproveApplicationRequest();
});

test('rules', function (): void {
    $actual = $this->subject->rules();

    $this->assertValidationRules([
        'email' => [
            'required',
            'string',
            'email',
            'max:70',
            'unique:invites',
            'unique:users',
        ],
        'approve' => [
            'required',
        ],
    ], $actual);
});
