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

use App\Models\Application;
use App\Rules\EmailBlacklist;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ApproveApplicationRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array<\Illuminate\Validation\ConditionalRules|\Illuminate\Validation\Rules\In|string>|string>
     */
    public function rules(): array
    {
        return [
            'status' => [
                Rule::in([Application::APPROVED]),
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:70',
                'unique:invites',
                'unique:users',
                Rule::when(config('email-blacklist.enabled'), fn () => new EmailBlacklist()),
            ],
            'approve' => [
                'required',
            ],
        ];
    }
}
