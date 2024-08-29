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
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StoreGroupRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(Request $request): bool
    {
        return $request->user()->group->is_owner || $request->is_owner != 1;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array<\Illuminate\Validation\ConditionalRules|string>|string>
     */
    public function rules(Request $request): array
    {
        return [
            'name' => [
                'required',
                'string',
                'unique:groups',
            ],
            'position' => [
                'required',
                'integer',
            ],
            'level' => [
                'required',
                'integer',
            ],
            'download_slots' => [
                'nullable',
                'integer',
            ],
            'description' => [
                'nullable',
            ],
            'color' => [
                'required',
            ],
            'icon' => [
                'required',
            ],
            'effect' => [
                'sometimes',
            ],
            'is_uploader' => [
                'required',
                'boolean',
            ],
            'is_internal' => [
                'required',
                'boolean',
            ],
            'is_editor' => [
                'required',
                'boolean',
            ],
            'is_modo' => [
                'required',
                'boolean',
            ],
            'is_admin' => [
                'required',
                'boolean',
            ],
            'is_owner' => [
                'required',
                'boolean',
            ],
            'is_trusted' => [
                'required',
                'boolean',
            ],
            'is_immune' => [
                'required',
                'boolean',
            ],
            'is_freeleech' => [
                'required',
                'boolean',
            ],
            'is_double_upload' => [
                'required',
                'boolean',
            ],
            'is_incognito' => [
                'required',
                'boolean',
            ],
            'can_chat' => [
                'required',
                'boolean',
            ],
            'can_comment' => [
                'required',
                'boolean',
            ],
            'can_invite' => [
                'required',
                'boolean',
            ],
            'can_request' => [
                'required',
                'boolean',
            ],
            'can_upload' => [
                'required',
                'boolean',
            ],
            'autogroup' => [
                'required',
                'boolean',
            ],
            'min_uploaded' => [
                Rule::when($request->boolean('autogroup'), [
                    'sometimes',
                    'integer',
                    'min:0',
                ], 'prohibited'),
            ],
            'min_ratio' => [
                Rule::when($request->boolean('autogroup'), [
                    'sometimes',
                    'min:0',
                    'max:99.99',
                ], 'prohibited'),
            ],
            'min_age' => [
                Rule::when($request->boolean('autogroup'), [
                    'sometimes',
                    'integer',
                    'min:0',
                ], 'prohibited'),
            ],
            'min_avg_seedtime' => [
                Rule::when($request->boolean('autogroup'), [
                    'sometimes',
                    'integer',
                    'min:0',
                ], 'prohibited'),
            ],
            'min_seedsize' => [
                Rule::when($request->boolean('autogroup'), [
                    'sometimes',
                    'integer',
                    'min:0',
                ], 'prohibited'),
            ],
            'min_uploads' => [
                Rule::when($request->boolean('autogroup'), [
                    'sometimes',
                    'integer',
                    'min:0',
                ], 'prohibited'),
            ],
        ];
    }
}
