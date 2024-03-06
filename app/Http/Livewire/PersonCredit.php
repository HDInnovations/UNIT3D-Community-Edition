<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D Community Edition is open-sourced software licensed under the GNU Affero General Public License v3.0
 * The details is bundled with this project in the file LICENSE.tx
 *
 * @project    UNIT3D Community Edition
 *
 * @author     Roardom <roardom@protonmail.com>
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 */

namespace App\Http\Livewire;

use App\Enums\Occupation;
use App\Models\Category;
use App\Models\Person;
use App\Models\Torrent;
use App\Models\User;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Component;

class PersonCredit extends Component
{
    public Person $person;

    #TODO: Update URL attributes once Livewire 3 fixes upstream bug. See: https://github.com/livewire/livewire/discussions/7746

    #[Url(history: true)]
    public ?int $occupationId = null;

    final public function mount(): void
    {
        $this->occupationId ??= match (true) {
            0 < $this->createdCount            => Occupation::CREATOR->value,
            0 < $this->directedCount           => Occupation::DIRECTOR->value,
            0 < $this->writtenCount            => Occupation::WRITER->value,
            0 < $this->producedCount           => Occupation::PRODUCER->value,
            0 < $this->composedCount           => Occupation::COMPOSER->value,
            0 < $this->cinematographedCount    => Occupation::CINEMATOGRAPHER->value,
            0 < $this->editedCount             => Occupation::EDITOR->value,
            0 < $this->productionDesignedCount => Occupation::PRODUCTION_DESIGNER->value,
            0 < $this->artDirectedCount        => Occupation::ART_DIRECTOR->value,
            0 < $this->actedCount              => Occupation::ACTOR->value,
            default                            => null,
        };
    }

    #[Computed]
    final public function personalFreeleech(): bool
    {
        return cache()->get('personal_freeleech:'.auth()->user()->id) ?? false;
    }

    /*
     * Livewire doesn't support enum properties, so we have to convert it manually.
     */
    public function updatingOccupation(&$value): void
    {
        $value = Occupation::from($value);
    }

    #[Computed]
    public function directedCount(): int
    {
        return $this->person->directedMovies()->count() + $this->person->directedTv()->count();
    }

    #[Computed]
    public function createdCount(): int
    {
        return $this->person->createdTv()->count();
    }

    #[Computed]
    public function writtenCount(): int
    {
        return $this->person->writtenMovies()->count() + $this->person->writtenTv()->count();
    }

    #[Computed]
    public function producedCount(): int
    {
        return $this->person->producedMovies()->count() + $this->person->producedTv()->count();
    }

    #[Computed]
    public function composedCount(): int
    {
        return $this->person->composedMovies()->count() + $this->person->composedTv()->count();
    }

    #[Computed]
    public function cinematographedCount(): int
    {
        return $this->person->cinematographedMovies()->count() + $this->person->cinematographedTv()->count();
    }

    #[Computed]
    public function editedCount(): int
    {
        return $this->person->editedMovies()->count() + $this->person->editedTv()->count();
    }

    #[Computed]
    public function productionDesignedCount(): int
    {
        return $this->person->productionDesignedMovies()->count() + $this->person->productionDesignedTv()->count();
    }

    #[Computed]
    public function artDirectedCount(): int
    {
        return $this->person->artDirectedMovies()->count() + $this->person->artDirectedTv()->count();
    }

    #[Computed]
    public function actedCount(): int
    {
        return $this->person->actedMovies()->count() + $this->person->actedTv()->count();
    }

    /**
     * @return \Illuminate\Support\Collection<int, Torrent>
     */
    #[Computed]
    final public function medias(): \Illuminate\Support\Collection
    {
        if ($this->occupationId === null) {
            return collect();
        }

        $movies = $this->person
            ->movie()
            ->with('genres', 'directors')
            ->wherePivot('occupation_id', '=', $this->occupationId)
            ->orderBy('release_date')
            ->get()
            // Since the credits table unique index has nullable columns, we get duplicate credits, which means duplicate movies
            ->unique();
        $tv = $this->person
            ->tv()
            ->with('genres', 'creators')
            ->wherePivot('occupation_id', '=', $this->occupationId)
            ->orderBy('first_air_date')
            ->get()
            // Since the credits table unique index has nullable columns, we get duplicate credits, which means duplicate tv
            ->unique();

        $movieIds = $movies->pluck('id');
        $tvIds = $tv->pluck('id');

        $torrents = Torrent::query()
            ->with('type:id,name,position', 'resolution:id,name,position')
            ->withExists([
                'freeleechTokens' => fn ($query) => $query->where('user_id', '=', auth()->id()),
            ])
            ->select([
                'id',
                'name',
                'info_hash',
                'size',
                'leechers',
                'seeders',
                'times_completed',
                'category_id',
                'user_id',
                'season_number',
                'episode_number',
                'tmdb',
                'stream',
                'free',
                'doubleup',
                'highspeed',
                'featured',
                'sticky',
                'sd',
                'internal',
                'created_at',
                'bumped_at',
                'type_id',
                'resolution_id',
                'personal_release',
            ])
            ->selectRaw(
                "CASE
                    WHEN category_id IN (SELECT `id` from `categories` where `movie_meta` = 1) THEN 'movie'
                    WHEN category_id IN (SELECT `id` from `categories` where `tv_meta` = 1) THEN 'tv'
                END as meta"
            )
            ->where(
                fn ($query) => $query
                    ->where(
                        fn ($query) => $query
                            ->whereIn('category_id', Category::select('id')->where('movie_meta', '=', 1))
                            ->whereIntegerInRaw('tmdb', $movieIds)
                    )
                    ->orWhere(
                        fn ($query) => $query
                            ->whereIn('category_id', Category::select('id')->where('tv_meta', '=', 1))
                            ->whereIntegerInRaw('tmdb', $tvIds)
                    )
            )
            ->get()
            ->groupBy('meta')
            ->map(fn ($movieOrTv, $key) => match ($key) {
                'movie' => $movieOrTv
                    ->groupBy('tmdb')
                    ->map(
                        function ($movie) {
                            $category_id = $movie->first()->category_id;
                            $movie = $movie
                                ->sortBy('type.position')
                                ->values()
                                ->groupBy(fn ($torrent) => $torrent->type->name)
                                ->map(
                                    fn ($torrentsByType) => $torrentsByType
                                        ->sortBy([
                                            ['resolution.position', 'asc'],
                                            ['internal', 'desc'],
                                            ['size', 'desc']
                                        ])
                                        ->values()
                                );
                            $movie->put('category_id', $category_id);

                            return $movie;
                        }
                    ),
                'tv' => $movieOrTv
                    ->groupBy([
                        fn ($torrent) => $torrent->tmdb,
                    ])
                    ->map(
                        function ($tv) {
                            $category_id = $tv->first()->category_id;
                            $tv = $tv
                                ->groupBy(fn ($torrent) => $torrent->season_number === 0 ? ($torrent->episode_number === 0 ? 'Complete Pack' : 'Specials') : 'Seasons')
                                ->map(fn ($packOrSpecialOrSeasons, $key) => match ($key) {
                                    'Complete Pack' => $packOrSpecialOrSeasons
                                        ->sortBy('type.position')
                                        ->values()
                                        ->groupBy(fn ($torrent) => $torrent->type->name)
                                        ->map(
                                            fn ($torrentsByType) => $torrentsByType
                                                ->sortBy([
                                                    ['resolution.position', 'asc'],
                                                    ['internal', 'desc'],
                                                    ['size', 'desc']
                                                ])
                                                ->values()
                                        ),
                                    'Specials' => $packOrSpecialOrSeasons
                                        ->groupBy(fn ($torrent) => 'Special '.$torrent->episode_number)
                                        ->sortKeys(SORT_NATURAL)
                                        ->map(
                                            fn ($episode) => $episode
                                                ->sortBy('type.position')
                                                ->values()
                                                ->groupBy(fn ($torrent) => $torrent->type->name)
                                                ->map(
                                                    fn ($torrentsByType) => $torrentsByType
                                                        ->sortBy([
                                                            ['resolution.position', 'asc'],
                                                            ['internal', 'desc'],
                                                            ['size', 'desc']
                                                        ])
                                                        ->values()
                                                )
                                        ),
                                    'Seasons' => $packOrSpecialOrSeasons
                                        ->groupBy(fn ($torrent) => 'Season '.$torrent->season_number)
                                        ->sortKeys(SORT_NATURAL)
                                        ->map(
                                            fn ($season) => $season
                                                ->groupBy(fn ($torrent) => $torrent->episode_number === 0 ? 'Season Pack' : 'Episodes')
                                                ->map(fn ($packOrEpisodes, $key) => match ($key) {
                                                    'Season Pack' => $packOrEpisodes
                                                        ->sortBy('type.position')
                                                        ->values()
                                                        ->groupBy(fn ($torrent) => $torrent->type->name)
                                                        ->map(
                                                            fn ($torrentsByType) => $torrentsByType
                                                                ->sortBy([
                                                                    ['resolution.position', 'asc'],
                                                                    ['internal', 'desc'],
                                                                    ['size', 'desc']
                                                                ])
                                                                ->values()
                                                        ),
                                                    'Episodes' => $packOrEpisodes
                                                        ->groupBy(fn ($torrent) => 'Episode '.$torrent->episode_number)
                                                        ->sortKeys(SORT_NATURAL)
                                                        ->map(
                                                            fn ($episode) => $episode
                                                                ->sortBy('type.position')
                                                                ->values()
                                                                ->groupBy(fn ($torrent) => $torrent->type->name)
                                                                ->map(
                                                                    fn ($torrentsBytype) => $torrentsBytype
                                                                        ->sortBy([
                                                                            ['resolution.position', 'asc'],
                                                                            ['internal', 'desc'],
                                                                            ['size', 'desc']
                                                                        ])
                                                                        ->values()
                                                                )
                                                        ),
                                                    default => abort(500, 'Group found that isn\'t one of: Season Pack, Episodes.'),
                                                })
                                        ),
                                    default => abort(500, 'Group found that isn\'t one of: Complete Pack, Specials, Seasons'),
                                });
                            $tv->put('category_id', $category_id);

                            return $tv;
                        }
                    ),
                default => abort(500, 'Group found that isn\'t one of: movie, tv'),
            });

        $medias = collect();

        foreach ($movies as $movie) {
            if ($torrents->has('movie') && $torrents['movie']->has($movie->id)) {
                $media = $movie;
                $media->meta = 'movie';
                $media->torrents = $torrents['movie'][$movie->id];
                $media->category_id = $media->torrents->pop();
                $medias->add($media);
            }
        }

        foreach ($tv as $show) {
            if ($torrents->has('tv') && $torrents['tv']->has($show->id)) {
                $media = $show;
                $media->meta = 'tv';
                $media->torrents = $torrents['tv'][$show->id];
                $media->category_id = $media->torrents->pop();
                $medias->add($media);
            }
        }

        return $medias;
    }

    final public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        return view('livewire.person-credit', [
            'user'                    => User::with(['group'])->findOrFail(auth()->user()->id),
            'personalFreeleech'       => $this->personalFreeleech,
            'medias'                  => $this->medias,
            'directedCount'           => $this->directedCount,
            'createdCount'            => $this->createdCount,
            'writtenCount'            => $this->writtenCount,
            'producedCount'           => $this->producedCount,
            'composedCount'           => $this->composedCount,
            'cinematographedCount'    => $this->cinematographedCount,
            'editedCount'             => $this->editedCount,
            'productionDesignedCount' => $this->productionDesignedCount,
            'artDirectedCount'        => $this->artDirectedCount,
            'actedCount'              => $this->actedCount,
        ]);
    }
}
