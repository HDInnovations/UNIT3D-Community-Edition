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

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class StoreTorrentRequestRequest extends FormRequest
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
        return [
            'name' => [
                'required',
                'max:180',
            ],
            'imdb' => [
                'required',
                'numeric',
            ],
            'tvdb' => [
                'required',
                'numeric',
            ],
            'tmdb' => [
                'required',
                'numeric',
            ],
            'mal' => [
                'required',
                'numeric',
            ],
            'igdb' => [
                'required',
                'numeric',
            ],
            'category_id' => [
                'required',
                'exists:categories,id',
            ],
            'type_id' => [
                'required',
                'exists:types,id',
            ],
            'resolution_id' => [
                'nullable',
                'exists:resolutions,id',
            ],
            'description' => [
                'required',
                'string',
            ],
            'bounty' => [
                'required',
                'numeric',
                'min:100',
                'max:'.$request->user()->seedbonus,
            ],
            'anon' => [
                'boolean',
                'required',
            ],
        ];
    }
}
