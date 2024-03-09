<?php

namespace App\Http\Controllers\API;

use App\Enums\UserGroup;
use App\Http\Controllers\Controller;
use App\Http\Resources\Torznab\CapabilitiesResource;
use App\Http\Resources\Torznab\TorrentResource;
use App\Models\Category;
use App\Models\Torrent;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class TorznabController extends Controller
{
    final public const array TAGS = [
        'anon',
        'highspeed',
        'internal',
        'personal_release',
        'refundable',
        'sd',
        'sticky',
        'stream',
    ];

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): \Illuminate\Http\Resources\Json\AnonymousResourceCollection|CapabilitiesResource|\Illuminate\View\View
    {
        switch (strtolower($request->str('t'))) {
            case 'caps':
                return cache()->remember('torznab-capabilities', 3600, fn () => new CapabilitiesResource(collect()));
            case 'search':
            case 'tvsearch':
            case 'moviesearch':
                $validator = Validator::make($request->all(), [
                    'cat' => [ // todo separate between categories, resolutions and types
                        'sometimes',
                        'regex:/^d+(,d+)*$/',
                    ],
                    'extended' => [
                        'required',
                        'boolean',
                    ],
                    'offset' => [
                        'sometimes',
                        'integer',
                        'min:0',
                    ],
                    'limit' => [
                        'sometimes',
                        'integer',
                        'min:0',
                    ],
                    'tags' => [
                        'sometimes',
                        'regex:/^-?[a-zA-Z]+(,-?[a-zA-Z]+)*$/',
                    ],
                    'minsize' => [
                        'sometimes',
                        'integer',
                        'min:0',
                    ],
                    'maxsize' => [
                        'sometimes',
                        'integer',
                        'min:1',
                    ],
                    'sort' => [
                        'sometimes',
                        'regex:/(cat,name,size,files)_(asc|desc)/',
                    ],
                    'apikey' => [
                        'required',
                        Rule::exists('users', 'api_token')
                            ->whereNotIn('group_id', [UserGroup::PRUNED, UserGroup::BANNED, UserGroup::DISABLED, UserGroup::GUEST, UserGroup::VALIDATING]),
                    ],
                ]);

                abort_if($validator->fails(), 201, 'Incorrect parameter');

                auth()->login(User::where('api_token', '=', $request->str('apikey'))->sole());

                $query = Torrent::query()
                    ->when(
                        $request->filled('cat'),
                        fn ($query) => $query->whereIn('category_id', $request->str('cat')->explode(',')->filter())
                    )
                    ->when(
                        strtolower($request->string('t')) === 'moviesearch',
                        fn ($query) => $query
                            ->whereIn('category_id', Category::select('id')->where('movie_meta', '=', true))
                    )
                    ->when(
                        strtolower($request->string('t')) === 'tvsearch',
                        fn ($query) => $query->whereIn('category_id', Category::select('id')->where('tv_meta', '=', true))
                            ->when($request->filled('season'), fn ($query) => $query->where('season_number', '=', preg_replace('/[^0-9]/', '', $request->string('season'))))
                            ->when($request->filled('ep'), fn ($query) => $query->where('episode_number', '=', preg_replace('/[^0-9]/', '', $request->string('ep')))),
                    )
                    ->where(
                        fn ($query) => $query->whereRaw('1=1')
                            ->when($request->filled('imdbid'), fn ($query) => $query->where('imdb', '=', $request->integer('imdbid')))
                            ->when($request->filled('tmdbid'), fn ($query) => $query->where('tmdb', '=', $request->integer('tmdbid')))
                            ->when($request->filled('tvdbid'), fn ($query) => $query->where('tvdb', '=', $request->integer('tvdbid')))
                    )
                    ->when($request->filled('q'), fn ($query) => $query->where('name', 'LIKE', '%'.$request->str('q').'%'))
                    ->when($request->filled('minsize'), fn ($query) => $query->where('size', '>=', $request->integer('minsize')))
                    ->when($request->filled('maxsize'), fn ($query) => $query->where('size', '<=', $request->integer('maxsize')))
                    ->offset($request->integer('offset'))
                    ->limit(min(100, $request->integer('limit', 25)));

                foreach ($request->str('tags')->explode(',')->filter() as $tag) {
                    if (str_starts_with($tag, '-')) {
                        // if the negative tag isn't recognized, we can be sure
                        // that none of the torrents have the tag, therefore, we
                        // don't need to exclude any torrents from the filter
                        if (\in_array($tag = substr($tag, 1), self::TAGS)) {
                            $query->where($tag, '=', false);
                        }
                    } else {
                        // If the positive tag isn't recognized, we can be sure
                        // that none of the torrents have the tag, therefore, we
                        // have no matches.
                        if (\in_array($tag, self::TAGS)) {
                            $query->where($tag, '=', true);
                        } else {
                            $query->limit(0);
                        }
                    }
                }

                if (strtolower($request->str('o')) === 'json') {
                    TorrentResource::withoutWrapping();

                    return TorrentResource::collection($query->get());
                }

                return view('torznab.index', [
                    'torrents' => $query->get()
                ]);
            default:
                abort(202, 'No such function. (Function not defined in this specification).');
        }
    }
}
