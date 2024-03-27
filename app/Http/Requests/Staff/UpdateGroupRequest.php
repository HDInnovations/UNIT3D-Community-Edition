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
            'name' => [
                Rule::when(!$group->system_required, [
                    'required',
                    'string',
                ]),
                Rule::prohibitedIf($group->system_required),
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
            'can_upload' => [
                'required',
                'boolean',
            ],
            'autogroup' => [
                'required',
                'boolean',
            ],
            'min_uploaded' => [
                'sometimes',
                'nullable',
                'integer',
                'min:0',
            ],
            'min_ratio' => [
                'sometimes',
                'nullable',
                'min:0',
                'max:99.99',
            ],
            'min_age' => [
                'sometimes',
                'nullable',
                'integer',
                'min:0',
            ],
            'min_avg_seedtime' => [
                'sometimes',
                'nullable',
                'integer',
                'min:0',
            ],
            'min_seedsize' => [
                'sometimes',
                'nullable',
                'integer',
                'min:0',
            ],
        ];
    }
}
