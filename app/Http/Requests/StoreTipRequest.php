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

namespace App\Http\Requests;

use App\Models\Post;
use App\Models\Torrent;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

/**
 * @see \Tests\Todo\Unit\Http\Requests\StorePollTest
 */
class StoreTipRequest extends FormRequest
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
    public function rules(Request $request): array
    {
        $user = $request->user();

        return [
            'torrent' => [
                'bail',
                'prohibits:post',
                'required_without:post',
                'exists:torrents,id',
                function ($attribute, $value, $fail) use ($user) {
                    if (Torrent::find($value)->user->id === $user->id) {
                        $fail(\trans('bon.failed-yourself'));
                    }
                },
            ],
            'post' => [
                'bail',
                'prohibits:torrent',
                'required_without:torrent',
                'exists:posts,id',
                function ($attribute, $value, $fail) use ($user) {
                    if (Post::find($value)->user->id === $user->id) {
                        $fail(\trans('bon.failed-yourself'));
                    }
                },
            ],
            'tip' => [
                'required',
                'numeric',
                'min:1',
                'max:'.$user->seedbonus,
            ],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'tip.min' => \trans('bon.failed-negative'),
            'tip.max' => \trans('bon.failed-funds-poster'),
        ];
    }
}
