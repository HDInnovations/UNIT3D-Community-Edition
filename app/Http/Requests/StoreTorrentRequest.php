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
use App\Models\Torrent;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Closure;

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
     */
    public function rules(Request $request): array
    {
        $category = Category::findOrFail($request->integer('category_id'));

        return [
            'torrent' => [
                'required',
                'file',
                'mimes:torrent',
                'mimetypes:application/x-bittorrent',
                function (string $attribute, mixed $value, Closure $fail): void {
                    $decodedTorrent = TorrentTools::normalizeTorrent($value);

                    $v2 = Bencode::is_v2_or_hybrid($decodedTorrent);

                    if ($v2) {
                        $fail('BitTorrent v2 (BEP 52) is not supported!');
                    }

                    try {
                        $meta = Bencode::get_meta($decodedTorrent);
                    } catch (\Exception) {
                        $fail('You Must Provide A Valid Torrent File For Upload!');
                    }

                    foreach (TorrentTools::getFilenameArray($decodedTorrent) as $name) {
                        if (! TorrentTools::isValidFilename($name)) {
                            $fail('Invalid Filenames In Torrent Files!');
                        }
                    }

                    $torrent = Torrent::withAnyStatus()->where('info_hash', '=', Bencode::get_infohash($decodedTorrent))->first();

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
            'name' => [
                'required',
                'unique:torrents',
                'max:255',
            ],
            'description' => [
                'required',
                'max:4294967296'
            ],
            'mediainfo' => [
                'nullable',
                'sometimes',
                'max:4294967296',
            ],
            'bdinfo' => [
                'nullable',
                'sometimes',
                'max:4294967296',
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
                Rule::when(! $category->movie_meta && ! $category->tv_meta, 'nullable'),
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
                'integer',
                'numeric',
            ],
            'tvdb' => [
                'required',
                'integer',
                'numeric',
            ],
            'tmdb' => [
                'required',
                'integer',
                'numeric',
            ],
            'mal' => [
                'required',
                'integer',
                'numeric',
            ],
            'igdb' => [
                'required',
                'integer',
                'numeric',
            ],
            'season_number' => [
                Rule::when($category->tv_meta, 'required'),
                Rule::when(! $category->tv_meta, 'nullable'),
                'sometimes',
                'integer',
                'numeric',
            ],
            'episode_number' => [
                Rule::when($category->tv_meta, 'required'),
                Rule::when(! $category->tv_meta, 'nullable'),
                'sometimes',
                'integer',
                'numeric',
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
                Rule::when(! $request->user()->group->is_modo && ! $request->user()->group->is_internal, 'prohibited'),
            ],
            'free' => [
                'sometimes',
                'integer',
                'numeric',
                'between:0,100',
                Rule::when(! $request->user()->group->is_modo && ! $request->user()->group->is_internal, 'prohibited'),
            ],
            'refundable' => [
                'sometimes',
                'boolean',
                Rule::when(! $request->user()->group->is_modo && ! $request->user()->group->is_internal, 'prohibited'),
            ],
        ];
    }
}
