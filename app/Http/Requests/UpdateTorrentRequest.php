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
use App\Models\Scopes\ApprovedScope;
use App\Models\Torrent;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UpdateTorrentRequest extends FormRequest
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
     * @return array<string, array<\Illuminate\Validation\ConditionalRules|\Illuminate\Validation\Rules\Unique|string>|string>
     */
    public function rules(Request $request): array
    {
        $category = Category::findOrFail($request->integer('category_id'));

        /** @var string $torrentId */
        $torrentId = $request->route('id');

        return [
            'name' => [
                'required',
                Rule::unique('torrents')->whereNot('id', $torrentId),
                'max:255',
            ],
            'description' => [
                'required',
                'max:2097152'
            ],
            'mediainfo' => [
                'nullable',
                'sometimes',
                'max:2097152',
            ],
            'bdinfo' => [
                'nullable',
                'sometimes',
                'max:2097152',
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
                Rule::when($category->movie_meta || $category->tv_meta, 'required'),
                Rule::when(!$category->movie_meta && !$category->tv_meta, 'nullable'),
                'exists:resolutions,id',
            ],
            'region_id' => [
                'nullable',
                'exists:regions,id',
            ],
            'distributor_id' => [
                'nullable',
                'exists:distributors,id',
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
            'season_number' => [
                Rule::when($category->tv_meta, 'required'),
                Rule::when(!$category->tv_meta, 'nullable'),
                'numeric',
            ],
            'episode_number' => [
                Rule::when($category->tv_meta, 'required'),
                Rule::when(!$category->tv_meta, 'nullable'),
                'numeric',
            ],
            'anon' => [
                'required',
                'boolean',
                Rule::when(Torrent::withoutGlobalScope(ApprovedScope::class)->find($request->route('id'))->user_id !== $request->user()->id && !$request->user()->group->is_modo, 'exclude'),
            ],
            'stream' => [
                'required',
                'boolean',
            ],
            'sd' => [
                'required',
                'boolean',
            ],
            'personal_release' => [
                'required',
                'boolean',
            ],
            'internal' => [
                'sometimes',
                'boolean',
                Rule::when(!$request->user()->group->is_modo && !$request->user()->group->is_internal, 'prohibited'),
            ],
            'free' => [
                'sometimes',
                'between:0,100',
                Rule::when(!$request->user()->group->is_modo && !$request->user()->group->is_internal, 'prohibited'),
            ],
            'refundable' => [
                'sometimes',
                'boolean',
                Rule::when(!$request->user()->group->is_modo && !$request->user()->group->is_internal, 'prohibited'),
            ],
        ];
    }
}
