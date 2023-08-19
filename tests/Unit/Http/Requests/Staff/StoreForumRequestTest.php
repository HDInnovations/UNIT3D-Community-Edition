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

use App\Http\Requests\Staff\StoreForumRequest;

beforeEach(function (): void {
    $this->subject = new StoreForumRequest();
});

test('authorize', function (): void {
    $actual = $this->subject->authorize();

    expect($actual)->toBeTrue();
});

test('rules', function (): void {
    $actual = $this->subject->rules();

    $this->assertValidationRules([
        'name' => [
            'required',
        ],
        'position' => [
            'required',
        ],
        'description' => [
            'required',
        ],
        'parent_id' => [
            'sometimes',
            'nullable',
            'integer',
        ],
        'permissions' => [
            'sometimes',
            'array',
        ],
        'permissions.*' => [
            'sometimes',
            'exists:groups,id',
        ],
        'permissions.*.show_forum' => [
            'sometimes',
            'boolean',
        ],
        'permissions.*.read_topic' => [
            'sometimes',
            'boolean',
        ],
        'permissions.*.reply_topic' => [
            'sometimes',
            'boolean',
        ],
        'permissions.*.start_topic' => [
            'sometimes',
            'boolean',
        ],
    ], $actual);
});
