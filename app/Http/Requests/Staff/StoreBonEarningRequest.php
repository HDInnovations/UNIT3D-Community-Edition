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
 * @author     Roardom <roardom@protonmail.com>
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 */

namespace App\Http\Requests\Staff;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBonEarningRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array<\Illuminate\Validation\Rules\In|string>|string>
     */
    public function rules(): array
    {
        return [
            'bon_earning.position' => [
                'required',
                'numeric',
                'unique:bon_earnings,position',
            ],
            'bon_earning.name' => [
                'required',
                'string',
                'max:255',
            ],
            'bon_earning.description' => [
                'required',
                'string',
                'max:255',
            ],
            'bon_earning.variable' => [
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
                    'connectable',
                ]),
            ],
            'bon_earning.multiplier' => [
                'required',
                'numeric',
            ],
            'bon_earning.operation' => [
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
                    'internal',
                    'personal_release',
                    'seedtime',
                    'type_id',
                    'connectable',
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
