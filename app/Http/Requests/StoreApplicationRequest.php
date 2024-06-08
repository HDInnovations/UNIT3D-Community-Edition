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

namespace App\Http\Requests;

use App\Rules\EmailBlacklist;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreApplicationRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array<int, \Illuminate\Validation\ConditionalRules|\Illuminate\Validation\Rules\In|string>|\Illuminate\Validation\ConditionalRules|string>
     */
    public function rules(): array
    {
        return  [
            'application.type' => [
                'required',
                Rule::in(['New To The Game', 'Experienced With Private Trackers']),
            ],
            'application.email' => [
                'required',
                'string',
                'email',
                'max:70',
                'unique:invites,email',
                'unique:users,email',
                'unique:applications,email',
                Rule::when(config('email-blacklist.enabled'), new EmailBlacklist()),
            ],
            'application.referrer' => [
                'required',
            ],
            'images' => [
                'required',
                'min:2',
                'max:100',
                'list',
            ],
            'images.*' => [
                'required',
                'array:image',
            ],
            'images.*.image' => [
                'filled',
                'url:https,http',
                'distinct',
            ],
            'links' => [
                'required',
                'min:2',
                'max:100',
                'list',
            ],
            'links.*' => [
                'required',
                'array:url',
            ],
            'links.*.url' => [
                'filled',
                'url:https,http',
                'distinct',
            ],
            'captcha' => Rule::when(config('captcha.enabled'), 'hiddencaptcha'),
        ];
    }
}
