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

use App\Http\Requests\StoreSubtitleRequest;

beforeEach(function (): void {
    $this->subject = new StoreSubtitleRequest();
});

test('authorize', function (): void {
    $actual = $this->subject->authorize();

    $this->assertTrue($actual);
});

test('rules', function (): void {
    $actual = $this->subject->rules();

    $this->assertValidationRules([
        'subtitle_file' => [
            'required',
            'mimes:srt,ass,sup,zip',
        ],
        'language_id' => [
            'required',
        ],
        'note' => [
            'required',
            'max:65535',
        ],
        'anon' => [
            'required',
            'boolean',
        ],
        'torrent_id' => [
            'required',
            'integer',
        ],
    ], $actual);
});
