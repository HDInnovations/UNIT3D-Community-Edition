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

use App\Models\Category;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UpdateTorrentRequestRequest extends FormRequest
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
                    'decimal:0',
                    'min:0',
                ]),
                Rule::when(!($category->movie_meta || $category->tv_meta), [
                    Rule::in([0]),
                ]),
            ],
            'tvdb' => [
                Rule::when($category->tv_meta, [
                    'required',
                    'decimal:0',
                    'min:0',
                ]),
                Rule::when(!$category->tv_meta, [
                    Rule::in([0]),
                ]),
            ],
            'tmdb' => [
                Rule::when($category->movie_meta || $category->tv_meta, [
                    'required',
                    'decimal:0',
                    'min:0',
                ]),
                Rule::when(!($category->movie_meta || $category->tv_meta), [
                    Rule::in([0]),
                ]),
            ],
            'mal' => [
                Rule::when($category->movie_meta || $category->tv_meta, [
                    'required',
                    'decimal:0',
                    'min:0',
                ]),
                Rule::when(!($category->movie_meta || $category->tv_meta), [
                    Rule::in([0]),
                ]),
            ],
            'igdb' => [
                Rule::when($category->game_meta, [
                    'required',
                    'decimal:0',
                    'min:0',
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
            'anon' => [
                'required',
                'boolean',
            ],
        ];
    }
}
