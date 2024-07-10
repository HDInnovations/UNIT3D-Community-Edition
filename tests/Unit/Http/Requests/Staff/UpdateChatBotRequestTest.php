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

use App\Http\Requests\Staff\UpdateChatBotRequest;

beforeEach(function (): void {
    $this->subject = new UpdateChatBotRequest();
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
            'min:1',
            'max:255',
        ],
        'command' => [
            'required',
            'min:1',
            'max:255',
        ],
        'position' => [
            'required',
        ],
        'color' => [
            'required',
        ],
        'icon' => [
            'required',
        ],
        'emoji' => [
            'required',
        ],
        'help' => [
            'sometimes',
            'max:9999',
        ],
    ], $actual);
});
