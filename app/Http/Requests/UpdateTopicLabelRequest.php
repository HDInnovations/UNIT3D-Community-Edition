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

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTopicLabelRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array<string>>
     */
    public function rules(): array
    {
        return [
            'approved' => [
                'boolean',
            ],
            'denied' => [
                'boolean',
            ],
            'solved' => [
                'boolean',
            ],
            'invalid' => [
                'boolean',
            ],
            'bug' => [
                'boolean',
            ],
            'suggestion' => [
                'boolean',
            ],
            'implemented' => [
                'boolean',
            ],
        ];
    }
}
