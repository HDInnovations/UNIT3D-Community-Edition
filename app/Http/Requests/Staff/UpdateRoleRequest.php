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

use App\Enums\Permission;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRoleRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array<\Illuminate\Contracts\Validation\Rule|string>|string>
     */
    public function rules(): array
    {
        return [
            'role.name' => [
                'required',
            ],
            'role.position' => [
                'required',
            ],
            'role.description' => [
                'required',
            ],
            'permissions' => [
                'sometimes',
                'array',
            ],
            'permissions.*' => [
                'sometimes',
                'array:role_id,permission_id,authorized',
                'required_array_keys:role_id,permission_id,authorized',
                'exclude_if:permissions.*.authorized,null',
            ],
            'permissions.*.permission_id' => [
                'required',
                Rule::enum(Permission::class),
            ],
            'permissions.*.role_id' => [
                'required',
                'exists:roles,id',
            ],
            'permissions.*.authorized' => [
                'sometimes',
                'nullable',
                'boolean',
            ],
        ];
    }
}
