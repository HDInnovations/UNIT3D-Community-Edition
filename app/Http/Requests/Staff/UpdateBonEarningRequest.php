<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D Community Edition is open-sourced software licensed under the GNU Affero General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D Community Edition
 *
 * @author     Roardom <roardom@protonmail.com>
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 */

namespace App\Http\Requests\Staff;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBonEarningRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'position' => [
                'required',
                'numeric',
            ],
            'variable' => [
                'required',
                Rule::in([
                    '1',
                    'age',
                    'size',
                    'seeders',
                    'leechers',
                    'times_completed',
                    'seedtime',
                    'personal_release',
                    'internal',
                ]),
            ],
            'multiplier' => [
                'required',
                'numeric',
            ],
            'operation' => [
                'required',
                Rule::in([
                    'append',
                    'multiply',
                ]),
            ],
            'conditions.*' => [
                'nullable',
                'array:operand1,operator,operand2',
                'required_array_keys:operand1,operator,operand2',
            ],
            'conditions.*.operand1' => [
                'required',
                Rule::in([
                    '1',
                    'age',
                    'size',
                    'seeders',
                    'leechers',
                    'times_completed',
                    'seedtime',
                    'personal_release',
                    'internal',
                ]),
            ],
            'conditions.*.operator' => [
                'required',
                Rule::in([
                    '<',
                    '>',
                    '<=',
                    '>=',
                    '=',
                    '<>',
                ]),
            ],
            'conditions.*.operand2' => [
                'required',
                'numeric',
            ],
        ];
    }
}
