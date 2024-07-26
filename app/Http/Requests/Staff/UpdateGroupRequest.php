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

use App\Models\Group;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UpdateGroupRequest extends FormRequest
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
     * @return array<string, array<\Illuminate\Validation\ConditionalRules|\Illuminate\Validation\Rules\ProhibitedIf|string>>
     */
    public function rules(Request $request): array
    {
        /** @var Group $group */
        $group = $request->route('group');

        return [
            'name' => [
                Rule::when(! $group->system_required, [
                    'required',
                    'string',
                ]),
                Rule::prohibitedIf($group->system_required && $request->name !== $group->name),
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
                ], 'nullable'),
            ],
            'min_ratio' => [
                Rule::when($request->boolean('autogroup'), [
                    'sometimes',
                    'min:0',
                    'max:99.99',
                ], 'nullable'),
            ],
            'min_age' => [
                Rule::when($request->boolean('autogroup'), [
                    'sometimes',
                    'integer',
                    'min:0',
                ], 'nullable'),
            ],
            'min_avg_seedtime' => [
                Rule::when($request->boolean('autogroup'), [
                    'sometimes',
                    'integer',
                    'min:0',
                ], 'nullable'),
            ],
            'min_seedsize' => [
                Rule::when($request->boolean('autogroup'), [
                    'sometimes',
                    'integer',
                    'min:0',
                ], 'nullable'),
            ],
            'min_uploads' => [
                Rule::when($request->boolean('autogroup'), [
                    'sometimes',
                    'integer',
                    'min:0',
                ], 'nullable'),
            ],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.prohibited' => 'You cannot change the name of a system required group.',
        ];
    }
}
