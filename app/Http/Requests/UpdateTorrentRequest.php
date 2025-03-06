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
     * @return array<string, list<\Illuminate\Validation\ConditionalRules|\Illuminate\Validation\Rules\ExcludeIf|\Illuminate\Validation\Rules\RequiredIf|\Illuminate\Validation\Rules\Unique|string>>
     */
    public function rules(Request $request): array
    {
        $category = Category::findOrFail($request->integer('category_id'));

        /** @var string $torrentId */
        $torrentId = $request->route('id');

        $torrent = Torrent::withoutGlobalScope(ApprovedScope::class)->find($torrentId);
        $user = $request->user()->load('group')->loadExists('internals');

        return [
            'name' => [
                'required',
                Rule::unique('torrents')->whereNot('id', $torrentId)->whereNull('deleted_at'),
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
                'decimal:0',
                'min:0',
            ],
            'tvdb' => [
                'required',
                'decimal:0',
                'min:0',
            ],
            'tmdb' => [
                'required',
                'decimal:0',
                'min:0',
            ],
            'mal' => [
                'required',
                'decimal:0',
                'min:0',
            ],
            'igdb' => [
                'required',
                'decimal:0',
                'min:0',
            ],
            'season_number' => [
                Rule::when($category->tv_meta, 'required'),
                Rule::when(!$category->tv_meta, 'nullable'),
                'decimal:0',
                'min:0',
            ],
            'episode_number' => [
                Rule::when($category->tv_meta, 'required'),
                Rule::when(!$category->tv_meta, 'nullable'),
                'decimal:0',
                'min:0',
            ],
            'anon' => [
                'sometimes',
                'boolean',
                Rule::requiredIf($torrent->user_id === $user->id || $user->group->is_modo),
                Rule::excludeIf(!($torrent->user_id === $user->id || $user->group->is_modo)),
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
                'sometimes',
                'boolean',
                Rule::requiredIf($torrent->user_id === $user->id || $user->group->is_modo),
                Rule::excludeIf(!($torrent->user_id === $user->id || $user->group->is_modo)),
            ],
            'internal' => [
                'sometimes',
                'boolean',
                /** @phpstan-ignore property.notFound (Larastan doesn't yet support loadExists()) */
                Rule::requiredIf($user->group->is_modo || $user->internals_exists),
                /** @phpstan-ignore property.notFound (Larastan doesn't yet support loadExists()) */
                Rule::excludeIf(!($user->group->is_modo || $user->internals_exists)),
            ],
            'free' => [
                'sometimes',
                'integer',
                'numeric',
                'between:0,100',
                /** @phpstan-ignore property.notFound (Larastan doesn't yet support loadExists()) */
                Rule::requiredIf($user->group->is_modo || $user->internals_exists),
                /** @phpstan-ignore property.notFound (Larastan doesn't yet support loadExists()) */
                Rule::excludeIf(!($user->group->is_modo || $user->internals_exists)),
            ],
            'refundable' => [
                'sometimes',
                'boolean',
                /** @phpstan-ignore property.notFound (Larastan doesn't yet support loadExists()) */
                Rule::requiredIf($user->group->is_modo || $user->internals_exists),
                /** @phpstan-ignore property.notFound (Larastan doesn't yet support loadExists()) */
                Rule::excludeIf(!($user->group->is_modo || $user->internals_exists)),
            ],
        ];
    }
}
