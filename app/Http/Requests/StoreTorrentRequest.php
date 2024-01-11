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

use App\Helpers\Bencode;
use App\Helpers\TorrentTools;
use App\Models\Category;
use App\Models\Scopes\ApprovedScope;
use App\Models\Torrent;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Closure;
use Exception;

class StoreTorrentRequest extends FormRequest
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
     * @return array<string, array<Closure|\Illuminate\Validation\ConditionalRules|string>|string>
     */
    public function rules(Request $request): array
    {
        $category = Category::findOrFail($request->integer('category_id'));

        return [
            'torrent' => [
                'required',
                'file',
                function (string $attribute, mixed $value, Closure $fail): void {
                    if ($value->getClientOriginalExtension() !== 'torrent') {
                        $fail('The torrent file uploaded does not have a ".torrent" file extension (it has "'.$value->getClientOriginalExtension().'"). Did you upload the correct file?');
                    }

                    $decodedTorrent = TorrentTools::normalizeTorrent($value);

                    $v2 = Bencode::is_v2_or_hybrid($decodedTorrent);

                    if ($v2) {
                        $fail('BitTorrent v2 (BEP 52) is not supported!');
                    }

                    try {
                        $meta = Bencode::get_meta($decodedTorrent);
                    } catch (Exception) {
                        $fail('You Must Provide A Valid Torrent File For Upload!');
                    }

                    foreach (TorrentTools::getFilenameArray($decodedTorrent) as $name) {
                        if (!TorrentTools::isValidFilename($name)) {
                            $fail('Invalid Filenames In Torrent Files!');
                        }
                    }

                    $torrent = Torrent::withoutGlobalScope(ApprovedScope::class)->where('info_hash', '=', Bencode::get_infohash($decodedTorrent))->first();

                    if ($torrent !== null) {
                        match ($torrent->status) {
                            Torrent::PENDING   => $fail('A torrent with the same info_hash has already been uploaded and is pending moderation.'),
                            Torrent::APPROVED  => $fail('A torrent with the same info_hash has already been uploaded and has been approved.'),
                            Torrent::REJECTED  => $fail('A torrent with the same info_hash has already been uploaded and has been rejected.'),
                            Torrent::POSTPONED => $fail('A torrent with the same info_hash has already been uploaded and is currently postponed.'),
                            default            => null,
                        };
                    }
                }
            ],
            'nfo' => [
                'nullable',
                'sometimes',
                'file',
                function (string $attribute, mixed $value, Closure $fail): void {
                    if ($value->getClientOriginalExtension() !== 'nfo') {
                        $fail('The NFO uploaded does not have a ".nfo" file extension (it has "'.$value->getClientOriginalExtension().'"). Did you upload the correct file?');
                    }
                },
            ],
            'name' => [
                'required',
                'unique:torrents',
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
            'season_number' => [
                Rule::when($category->tv_meta, [
                    'required',
                    'numeric',
                    'integer',
                ]),
                Rule::prohibitedIf(!$category->tv_meta),
            ],
            'episode_number' => [
                Rule::when($category->tv_meta, [
                    'required',
                    'numeric',
                    'integer',
                ]),
                Rule::prohibitedIf(!$category->tv_meta),
            ],
            'anon' => [
                'required',
                'boolean',
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
                'integer',
                'numeric',
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

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'igdb.in' => 'The IGBB ID must be 0 if the media doesn\'t exist on IGDB or you\'re not uploading a game.',
            'tmdb.in' => 'The TMDB ID must be 0 if the media doesn\'t exist on TMDB or you\'re not uploading a tv show or movie.',
            'imdb.in' => 'The IMDB ID must be 0 if the media doesn\'t exist on IMDB or you\'re not uploading a tv show or movie.',
            'tvdb.in' => 'The TVDB ID must be 0 if the media doesn\'t exist on TVDB or you\'re not uploading a tv show.',
            'mal.in'  => 'The MAL ID must be 0 if the media doesn\'t exist on MAL or you\'re not uploading a tv or movie.',
        ];
    }
}
