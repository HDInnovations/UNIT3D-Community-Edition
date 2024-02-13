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
        'slug' => [
            'required',
        ],
        'description' => [
            'required',
        ],
        'forum_category_id' => [
            'required',
            'exists:forum_categories,id',
        ],
        'permissions' => [
            'required',
            'array',
        ],
        'permissions.*' => [
            'required',
            'array:group_id,read_topic,reply_topic,start_topic',
        ],
        'permissions.*.group_id' => [
            'required',
            'exists:groups,id',
        ],
        'permissions.*.read_topic' => [
            'required',
            'boolean',
        ],
        'permissions.*.reply_topic' => [
            'required',
            'boolean',
        ],
        'permissions.*.start_topic' => [
            'required',
            'boolean',
        ],
    ], $actual);
});
