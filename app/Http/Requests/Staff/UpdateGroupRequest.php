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
     * @return array<string, array<\Illuminate\Validation\ConditionalRules|string>|string>
     */
    public function rules(Request $request): array
    {
        /** @var Group $group */
        $group = $request->route('group');

        return [
            'group.name' => [
                Rule::when(!$group->system_required, [
                    'required',
                    'string',
                ]),
                Rule::prohibitedIf($group->system_required),
            ],
            'group.position' => [
                'required',
                'integer',
            ],
            'group.level' => [
                'required',
                'integer',
            ],
            'group.download_slots' => [
                'nullable',
                'integer',
            ],
            'group.color' => [
                'required',
            ],
            'group.icon' => [
                'required',
            ],
            'group.effect' => [
                'sometimes',
            ],
            'group.is_internal' => [
                'required',
                'boolean',
            ],
            'group.is_editor' => [
                'required',
                'boolean',
            ],
            'group.is_modo' => [
                'required',
                'boolean',
            ],
            'group.is_admin' => [
                'required',
                'boolean',
            ],
            'group.is_owner' => [
                'required',
                'boolean',
            ],
            'group.is_trusted' => [
                'required',
                'boolean',
            ],
            'group.is_immune' => [
                'required',
                'boolean',
            ],
            'group.is_freeleech' => [
                'required',
                'boolean',
            ],
            'group.is_double_upload' => [
                'required',
                'boolean',
            ],
            'group.is_incognito' => [
                'required',
                'boolean',
            ],
            'group.autogroup' => [
                'required',
                'boolean',
            ],
            'group.min_uploaded' => [
                'sometimes',
                'nullable',
                'integer',
                'min:0',
            ],
            'group.min_ratio' => [
                'sometimes',
                'nullable',
                'min:0',
                'max:99.99',
            ],
            'group.min_age' => [
                'sometimes',
                'nullable',
                'integer',
                'min:0',
            ],
            'group.min_avg_seedtime' => [
                'sometimes',
                'nullable',
                'integer',
                'min:0',
            ],
            'group.min_seedtime' => [
                'sometimes',
                'nullable',
                'integer',
                'min:0',
            ],
            'roles.*' => [
                'required',
                'exists:roles,id',
            ],
        ];
    }
}
