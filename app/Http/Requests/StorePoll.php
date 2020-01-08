<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D is open-sourced software licensed under the GNU Affero General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 *
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     HDVinnie
 */

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class StorePoll extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return string[]
     */
    public function rules(): array
    {
        return [
            'title'     => 'required|min:10',
            'options.*' => 'filled',
            'options'   => 'min:2',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return string[]
     */
    public function messages(): array
    {
        return [
            'options.*.filled' => 'You must fill in all options fields',
        ];
    }
}
