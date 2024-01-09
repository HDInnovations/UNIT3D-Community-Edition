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

use App\Models\Category;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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
     *
     * @return array<string, array<\Illuminate\Validation\ConditionalRules|string>|string>
     */
    public function rules(Request $request): array
    {
        $category = Category::findOrFail($request->integer('category_id'));

        return [
            'name' => [
                'required',
                'max:180',
            ],
            'imdb' => [
                Rule::when($category->movie_meta || $category->tv_meta, [
                    'required',
                    'numeric',
                ]),
                Rule::when(!($category->movie_meta || $category->tv_meta), [
                    Rule::in([0]),
                ]),
            ],
            'tvdb' => [
                Rule::when($category->tv_meta, [
                    'required',
                    'numeric',
                    'integer',
                ]),
                Rule::when(!$category->tv_meta, [
                    Rule::in([0]),
                ]),
            ],
            'tmdb' => [
                Rule::when($category->movie_meta || $category->tv_meta, [
                    'required',
                    'numeric',
                    'integer',
                ]),
                Rule::when(!($category->movie_meta || $category->tv_meta), [
                    Rule::in([0]),
                ]),
            ],
            'mal' => [
                Rule::when($category->movie_meta || $category->tv_meta, [
                    'required',
                    'numeric',
                    'integer',
                ]),
                Rule::when(!($category->movie_meta || $category->tv_meta), [
                    Rule::in([0]),
                ]),
            ],
            'igdb' => [
                Rule::when($category->game_meta, [
                    'required',
                    'numeric',
                    'integer',
                ]),
                Rule::when(!$category->game_meta, [
                    Rule::in([0]),
                ]),
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

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'igdb.in'    => 'The IGBB ID must be 0 if the media doesn\'t exist on IGDB or you\'re not requesting a game.',
            'tmdb.in'    => 'The TMDB ID must be 0 if the media doesn\'t exist on TMDB or you\'re not requesting a tv show or movie.',
            'imdb.in'    => 'The IMDB ID must be 0 if the media doesn\'t exist on IMDB or you\'re not requesting a tv show or movie.',
            'tvdb.in'    => 'The TVDB ID must be 0 if the media doesn\'t exist on TVDB or you\'re not requesting a tv show.',
            'mal.in'     => 'The MAL ID must be 0 if the media doesn\'t exist on MAL or you\'re not requesting a tv or movie.',
            'bounty.max' => 'You do not have enough BON to make this request.',
        ];
    }
}
