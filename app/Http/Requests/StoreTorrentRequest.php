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
            'decoded_torrent' => [
                'required',
            ],
            'decoded_torrent.comment' => [
                'sometimes',
                'max:255',
            ],
            'decoded_torrent.encoding' => [
                'sometimes',
                'max:255',
            ],
            'decoded_torrent.info' => [
                'required',
                'array',
                'required_array_keys:piece length,pieces',
                function (string $attributes, mixed $value, Closure $fail): void {
                    if (\is_array($value)) {
                        if(\array_key_exists('files', $value) && \is_array($value['files'])) {
                            $totalLength = 0;

                            foreach ($value['files'] as $file) {
                                if (\is_array($file) && \array_key_exists('length', $file) && \is_int($file['length'])) {
                                    $totalLength += $file['length'];
                                }
                            }

                            if (
                                \array_key_exists('piece length', $value)
                                && \is_int($value['piece length'])
                                && \array_key_exists('pieces', $value)
                                && \is_string($value['pieces'])
                                && (int) ceil($totalLength / $value['piece length']) * 20 !== \strlen($value['pieces'])
                            ) {
                                $fail('This torrent is corrupt: there are not enough hashes for all pieces. You currently have '(\strlen($value['pieces']) / 20).' hashes of pieces with size '.$value['piece length'].' but that doesn\'t total the provided '.$totalLength.' total filesize.');
                            }
                        } elseif (
                            \array_key_exists('piece length', $value)
                            && \is_int($value['piece length'])
                            && \array_key_exists('pieces', $value)
                            && \is_string($value['pieces'])
                            && \array_key_exists('length', $value)
                            && \is_int($value['length'])
                            && (int) ceil($value['length'] / $value['piece length']) * 20 !== \strlen($value['pieces'])
                        ) {
                            $fail('This torrent is corrupt: there are not enough hashes for all pieces.');
                        }
                    }

                    // Make sure there are an appropriate number of pieces in the torrent.
                    // 1000-2000 is optimal between performance of having to deal with less hashing
                    // balanced with potential of losing data due to network conditions. Other factors
                    // include the default 2 MiB PHP maximum file upload limit on seedboxes and older
                    // clients (namely utorrent) not supporting pieces sizes above 16 MiB (but also not
                    // supporting torrent sizes larger than 1 TiB).
                    if (
                        \array_key_exists('piece length', $value)
                        && \is_int($value['piece length'])
                        && \array_key_exists('pieces', $value)
                        && \is_string($value['pieces'])
                    ) {
                        $pieceCount = \strlen($value['pieces']) / 20;

                        switch ($value['piece length']) {
                            case 2 ** 14: // 16 KiB
                            case 2 ** 15: // 32 KiB
                            case 2 ** 16: // 64 KiB
                                if ($pieceCount > 1500) {
                                    $fail('A piece size of '.$value['piece length'].' must be less than 1500 pieces. You have '.$pieceCount.' pieces. Consider raising or lowering the piece size.');
                                }

                                break;
                            case 2 ** 17: // 128 KiB
                            case 2 ** 18: // 256 KiB
                            case 2 ** 19: // 512 KiB
                            case 2 ** 20: // 1 MiB
                            case 2 ** 21: // 2 MiB
                            case 2 ** 22: // 4 MiB
                            case 2 ** 23: // 8 MiB
                                if ($pieceCount < 500 || 3000 < $pieceCount) {
                                    $fail('A piece size of '.$value['piece length'].' must be between 500 and 3000 pieces. You have '.$pieceCount.' pieces. Consider raising or lowering the piece size.');
                                }

                                break;
                            case 2 ** 24: // 16 MiB
                                if ($pieceCount < 500 || 5000 < $pieceCount) {
                                    $fail('A piece size of '.$value['piece length'].' must be between 500 and 5000 pieces. You have '.$pieceCount.' pieces. Consider raising or lowering the piece size.');
                                }

                                break;
                            case 2 ** 25: // 32 MiB
                            case 2 ** 26: // 64 MiB
                            case 2 ** 27: // 128 MiB
                            case 2 ** 28: // 256 MiB
                                if ($pieceCount < 10000 || 20000 < $pieceCount) {
                                    $fail('A piece size of '.$value['piece length'].' must be between 10000 and 50000 pieces. You have '.$pieceCount.' pieces. Consider raising or lowering the piece size.');
                                }

                                break;
                        }
                    }
                },
                'exclude',
            ],
            'decoded_torrent.info.files.*.length' => [
                'required_without:decoded_torrent.info.length',
                'integer'
            ],
            'decoded_torrent.info.files.*.path.*' => [
                'required_without:decoded_torrent.info.length',
                'string',
                function (string $attribute, mixed $value, Closure $fail): void {
                    if (! TorrentTools::isValidFilename($value)) {
                        $fail('Invalid filename in torrent files: :input');
                    }
                },
            ],
            'decoded_torrent.info.length' => [
                'integer',
                'required_without:decoded_torrent.info.files',
            ],
            'decoded_torrent.info.name' => [
                'string',
                'required_without:decoded_torrent.info.files',
            ],
            'decoded_torrent.info.piece length' => [
                'bail',
                'required',
                'integer',
                function (string $attribute, mixed $value, Closure $fail): void {
                    $value = (int) $value;

                    if (($value & ($value - 1)) !== 0) {
                        $fail('This torrent is corrupt: the piece size must be a power of 2. Your piece size is currently :input.');
                    }

                    if ($value < 2 ** 14) {
                        $fail('This torrent is corrupt: the piece size must be greater than or equal to 16 KiB.');
                    }

                    if ($value > 2 ** 28) {
                        $fail('The piece size must be less than or equal to 256 MiB.');
                    }
                },
            ],
            'decoded_torrent.info.pieces' => [
                'required',
                'string',
                function (string $attributes, mixed $value, Closure $fail): void {
                    if (\strlen($value) % 20 !== 0) {
                        $fail('This torrent is corrupt: the pieces are not a multiple of 20.');
                    }
                },
            ],
            'folder' => [
                'nullable',
                'sometimes',
                'max:255',
            ],
            'info_hash' => [
                'required',
                function (string $attributes, mixed $value, Closure $fail): void {
                    $torrent = Torrent::withoutGlobalScope(ApprovedScope::class)->where('info_hash', '=', $value)->first();

                    if ($torrent !== null) {
                        match ($torrent->status) {
                            Torrent::PENDING   => $fail('A torrent with the same :attribute has already been uploaded and is pending moderation.'),
                            Torrent::APPROVED  => $fail('A torrent with the same :attribute has already been uploaded and has been approved.'),
                            Torrent::REJECTED  => $fail('A torrent with the same :attribute has already been uploaded and has been rejected.'),
                            Torrent::POSTPONED => $fail('A torrent with the same :attribute has already been uploaded and is currently postponed.'),
                            default            => null,
                        };
                    }
                },
            ],
            'torrent' => [
                'required',
                'file',
                function (string $attribute, mixed $value, Closure $fail): void {
                    if ($value->getClientOriginalExtension() !== 'torrent') {
                        $fail('The torrent file uploaded does not have a ".torrent" file extension (it has "'.$value->getClientOriginalExtension().'"). Did you upload the correct file?');
                    }
                },
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
                Rule::when($category->movie_meta || $category->tv_meta, [
                    'required',
                    'numeric',
                ]),
                Rule::when(! ($category->movie_meta || $category->tv_meta), [
                    Rule::in([0]),
                ]),
            ],
            'tvdb' => [
                Rule::when($category->tv_meta, [
                    'required',
                    'numeric',
                    'integer',
                ]),
                Rule::when(! $category->tv_meta, [
                    Rule::in([0]),
                ]),
            ],
            'tmdb' => [
                Rule::when($category->movie_meta || $category->tv_meta, [
                    'required',
                    'numeric',
                    'integer',
                ]),
                Rule::when(! ($category->movie_meta || $category->tv_meta), [
                    Rule::in([0]),
                ]),
            ],
            'mal' => [
                Rule::when($category->movie_meta || $category->tv_meta, [
                    'required',
                    'numeric',
                    'integer',
                ]),
                Rule::when(! ($category->movie_meta || $category->tv_meta), [
                    Rule::in([0]),
                ]),
            ],
            'igdb' => [
                Rule::when($category->game_meta, [
                    'required',
                    'numeric',
                    'integer',
                ]),
                Rule::when(! $category->game_meta, [
                    Rule::in([0]),
                ]),
            ],
            'season_number' => [
                Rule::when($category->tv_meta, [
                    'required',
                    'numeric',
                    'integer',
                ]),
                Rule::prohibitedIf(! $category->tv_meta),
            ],
            'episode_number' => [
                Rule::when($category->tv_meta, [
                    'required',
                    'numeric',
                    'integer',
                ]),
                Rule::prohibitedIf(! $category->tv_meta),
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

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $decodedTorrent = Bencode::bdecode_file($this->torrent);

        $this->merge([
            'torrent'         => $this->torrent,
            'decoded_torrent' => $decodedTorrent,
            'folder'          => Bencode::get_name($decodedTorrent),
            'info_hash'       => TorrentTools::getTorrentHash($decodedTorrent),
        ]);
    }
}
