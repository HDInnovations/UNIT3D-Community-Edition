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

class UpdateUserRequest extends FormRequest
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
            'username'    => 'required',
            'email'       => 'required|max:255',
            'uploaded'    => 'required|integer',
            'downloaded'  => 'required|integer',
            'title'       => 'nullable|present|string|max:255',
            'about'       => 'nullable|present|string|max:16777216',
            'group_id'    => 'required|exists:groups,id',
            'internal_id' => 'nullable|exists:internals,id',
        ];
    }
}
