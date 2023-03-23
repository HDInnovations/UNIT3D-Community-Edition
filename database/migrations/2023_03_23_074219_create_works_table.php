<?php

use App\Enums\Medias;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use MarcReichel\IGDBLaravel\Models\Game;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // No longer used

        Schema::drop('episodes');
        Schema::drop('seasons');

        /* Create new tables
        ==================================================================== */

        Schema::create('medias', function (Blueprint $table): void {
            $table->tinyIncrements('id');
            $table->unsignedTinyInteger('position');
            $table->string('name');
        });

        DB::table('medias')->insert([
            [
                'id'       => 1,
                'position' => 1,
                'name'     => 'Movie',
            ],
            [
                'id'       => 2,
                'position' => 2,
                'name'     => 'Tv',
            ],
            [
                'id'       => 3,
                'position' => 3,
                'name'     => 'Album',
            ],
            [
                'id'       => 4,
                'position' => 4,
                'name'     => 'Game',
            ],
        ]);

        Schema::create('works', function (Blueprint $table): void {
            $table->increments('id');
            $table->unsignedTinyInteger('media_id')->nullable();
            $table->unsignedInteger('tmdb_movie_id')->nullable();
            $table->unsignedInteger('tmdb_tv_id')->nullable();
            $table->unsignedInteger('tvdb_tv_id')->nullable();
            $table->unsignedInteger('igdb_game_id')->nullable();
            $table->unsignedInteger('mal_id')->nullable();
            $table->unsignedInteger('imdb_title_id')->nullable();
            $table->unsignedInteger('tmdb_vote_count')->nullable();
            $table->decimal('tmdb_vote_average', 3, 1)->nullable();
            $table->unsignedInteger('igdb_vote_count')->nullable();
            $table->decimal('igdb_vote_average', 4, 1)->nullable();
            $table->smallInteger('year');
            $table->string('name');
            $table->string('poster')->nullable();
            $table->string('backdrop')->nullable();
            $table->string('trailer')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();

            $table->foreign('media_id')->references('id')->on('medias')->cascadeOnUpdate();
            $table->index('tmdb_movie_id');
            $table->index('tmdb_tv_id');
            $table->index('tvdb_tv_id');
            $table->index('igdb_game_id');
            $table->index('mal_id');
            $table->index('imdb_title_id');
            $table->index('name');
            $table->index('year');
        });

        Schema::create('company_work', function (Blueprint $table): void {
            $table->unsignedBigInteger('company_id');
            $table->unsignedInteger('work_id');

            $table->primary(['company_id', 'work_id']);

            $table->foreign('company_id')->references('id')->on('companies')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('work_id')->references('id')->on('works')->cascadeOnUpdate()->cascadeOnDelete();
        });

        Schema::create('collection_work', function (Blueprint $table): void {
            $table->unsignedBigInteger('collection_id');
            $table->unsignedInteger('work_id');

            $table->primary(['collection_id', 'work_id']);

            $table->foreign('collection_id')->references('id')->on('collection')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('work_id')->references('id')->on('works')->cascadeOnUpdate()->cascadeOnDelete();
        });

        Schema::create('genre_work', function (Blueprint $table): void {
            $table->unsignedBigInteger('genre_id');
            $table->unsignedInteger('work_id');

            $table->primary(['genre_id', 'work_id']);

            $table->foreign('genre_id')->references('id')->on('genres')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('work_id')->references('id')->on('works')->cascadeOnUpdate()->cascadeOnDelete();
        });

        Schema::create('network_work', function (Blueprint $table): void {
            $table->unsignedBigInteger('network_id');
            $table->unsignedInteger('work_id');

            $table->primary(['network_id', 'work_id']);

            $table->foreign('network_id')->references('id')->on('networks')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('work_id')->references('id')->on('works')->cascadeOnUpdate()->cascadeOnDelete();
        });

        Schema::table('torrents', function (Blueprint $table): void {
            $table->unsignedInteger('work_id')->after('id');
        });

        /* Transfer old data to new tables
        ==================================================================== */

        // Works

        DB::table('works')->insertUsing(
            [
                'imdb_title_id',
                'mal_id',
                'tvdb_tv_id',
                'igdb_game_id',
                'media_id',
                'tmdb_movie_id',
                'tmdb_tv_id',
                'tmdb_vote_average',
                'tmdb_vote_count',
                'year',
                'name',
                'poster',
                'backdrop',
                'trailer',
                'description',
            ],
            DB::table('torrents')
                ->select([
                    'imdb as imdb_title_id',
                    'mal as mal_id',
                    'tvdb as tvdb_tv_id',
                ])
                ->selectRaw('GREATEST(igdb, 0) as igdb_game_id')
                ->selectRaw("CASE
                                        WHEN categories.movie_meta = 1 THEN 1
                                        WHEN categories.tv_meta = 1 THEN 2
                                        WHEN categories.music_meta = 1 THEN 3
                                        WHEN categories.game_meta = 1 THEN 4
                                    END as media_id")
                ->selectRaw("CASE WHEN categories.movie_meta = 1 THEN tmdb END as tmdb_movie_id")
                ->selectRaw("CASE WHEN categories.tv_meta = 1 THEN tmdb END as tmdb_tv_id")
                ->selectRaw('COALESCE(movie.vote_average, tv.vote_average) as tmdb_vote_average')
                ->selectRaw('COALESCE(movie.vote_count, tv.vote_count) as tmdb_vote_count')
                ->selectRaw("COALESCE(YEAR(movie.release_date), YEAR(tv.first_air_date), regexp_substr(MAX(torrents.name), '([0-9]{4})| [0-9]{4} '), 0) as year")
                ->selectRaw('COALESCE(movie.title, tv.name, MAX(torrents.name)) as name')
                ->selectRaw('COALESCE(movie.poster, tv.poster) as poster')
                ->selectRaw('COALESCE(movie.backdrop, tv.backdrop) as backdrop')
                ->selectRaw('NULL as trailer')
                ->selectRaw('COALESCE(movie.overview, tv.overview) as description')
                ->join('categories', 'torrents.category_id', '=', 'categories.id')
                ->leftJoin('movie', fn ($query) => $query->on('torrents.tmdb', '=', 'movie.id')->where('categories.movie_meta', '=', 1))
                ->leftJoin('tv', fn ($query) => $query->on('torrents.tmdb', '=', 'tv.id')->where('categories.tv_meta', '=', 1))
                ->groupBy(['media_id', 'tmdb', 'imdb', 'mal', 'tvdb', 'igdb'])
                ->havingNotNull('name')
        );

        // Games

        $igdbIds = DB::table('torrents')
            ->select('igdb')
            ->whereIn('category_id', DB::table('categories')->select('id')->where('game_meta', '=', 1))
            ->groupBy('igdb')
            ->get();

        foreach ($igdbIds as $igdbId) {
            $game = Game::with([
                'cover'    => ['url', 'image_id'],
                'artworks' => ['url', 'image_id'],
                'genres'   => ['name'],
                'videos'   => ['video_id', 'name'],
                'involved_companies.company',
                'involved_companies.company.logo',
                'platforms',
            ])
                ->find($igdbId);

            $backdrop = 'https://images.igdb.com/igdb/image/upload/t_screenshot_big/'.$game->artworks[0]['image_id'].'.jpg';
            $link = collect($game->videos)->take(1)->pluck('video_id');
            $trailer = isset($link[0]) ? 'https://www.youtube.com/embed/'.$link[0] : null;

            DB::table('works')->update([
                'media_id'          => Medias::GAME->value,
                'tmdb_vote_count'   => $game->rating_count ?? null,
                'tmdb_vote_average' => $game->rating ?? null,
                'year'              => substr($game->first_release_date, 0, 4),
                'name'              => $game->name,
                'poster'            => $game['cover']['url'] ?? null,
                'backdrop'          => $backdrop ?? null,
                'trailer'           => $trailer ?? null,
                'description'       => $game->summary ?? null,
            ]);
        }

        // Companies

        DB::table('company_work')->insertUsing(
            ['company_id', 'work_id'],
            DB::table('works')
                ->distinct()
                ->selectRaw('COALESCE(company_movie.company_id, company_tv.company_id) as company_id')
                ->addSelect('works.id')
                ->leftJoin('company_movie', 'works.tmdb_movie_id', '=', 'company_movie.movie_id')
                ->leftJoin('company_tv', 'works.tmdb_tv_id', '=', 'company_tv.tv_id')
                ->havingNotNull('company_id')
        );

        Schema::drop('company_movie');
        Schema::drop('company_tv');

        // Genres

        DB::table('genre_work')->insertUsing(
            ['genre_id', 'work_id'],
            DB::table('works')
                ->distinct()
                ->selectRaw('COALESCE(genre_movie.genre_id, genre_tv.genre_id) as genre_id')
                ->addSelect('works.id')
                ->leftJoin('genre_movie', 'works.tmdb_movie_id', '=', 'genre_movie.movie_id')
                ->leftJoin('genre_tv', 'works.tmdb_tv_id', '=', 'genre_tv.tv_id')
                ->havingNotNull('genre_id')
        );

        Schema::drop('genre_movie');
        Schema::drop('genre_tv');

        // Collections

        DB::table('collection_work')->insertUsing(
            ['collection_id', 'work_id'],
            DB::table('works')
                ->distinct()
                ->select('collection_movie.collection_id', 'works.id')
                ->join('collection_movie', 'works.tmdb_movie_id', '=', 'collection_movie.movie_id')
        );

        Schema::drop('collection_movie');

        // Networks

        DB::table('network_work')->insertUsing(
            ['network_id', 'work_id'],
            DB::table('works')
                ->distinct()
                ->addSelect('network_tv.network_id', 'works.id')
                ->join('network_tv', 'works.tmdb_tv_id', '=', 'network_tv.tv_id')
        );

        Schema::drop('network_tv');

        // Credits

        Schema::table('credits', function (Blueprint $table): void {
            $table->unsignedInteger('work_id')->nullable()->after('person_id');
            $table->foreign('work_id')->references('id')->on('works')->cascadeOnUpdate()->cascadeOnDelete();
        });

        DB::table('credits')
            ->leftJoin('works as movie_works', 'movie_works.tmdb_movie_id', '=', 'credits.movie_id')
            ->leftJoin('works as tv_works', 'tv_works.tmdb_tv_id', '=', 'credits.tv_id')
            ->update([
                'work_id' => DB::raw('COALESCE(movie_works.id, tv_works.id)'),
            ]);

        DB::table('credits')
            ->whereNull('work_id')
            ->delete();

        // Delete duplicate credits
        DB::table('credits')
            ->whereNotIn(
                'id',
                fn ($query) => $query->fromSub(
                    DB::table('credits')
                        ->selectRaw('MAX(id)')
                        ->groupBy(['work_id', 'person_id', 'occupation_id', 'character']),
                    'temp'
                )
            )
            ->delete();

        Schema::table('credits', function (Blueprint $table): void {
            $table->unsignedInteger('work_id')->change();

            $table->dropForeign(['movie_id']);
            $table->dropForeign(['tv_id']);
            $table->dropForeign(['person_id']);
            $table->dropForeign(['occupation_id']);
            $table->dropUnique(['person_id', 'movie_id', 'tv_id', 'occupation_id', 'character']);
            $table->dropColumn(['movie_id', 'tv_id']);

            $table->foreign('person_id')->references('id')->on('person')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('occupation_id')->references('id')->on('occupations')->cascadeOnUpdate();
            $table->unique(['person_id', 'work_id', 'occupation_id', 'character']);
        });

        // Recommendations

        Schema::table('recommendations', function (Blueprint $table): void {
            $table->unsignedInteger('work_id')->nullable();
            $table->unsignedInteger('recommended_work_id')->nullable();

            $table->foreign('work_id')->references('id')->on('works')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('recommended_work_id')->references('id')->on('works')->cascadeOnUpdate()->cascadeOnDelete();
        });

        DB::table('recommendations')
            ->leftJoin('works as movie_works', 'movie_works.tmdb_movie_id', '=', 'recommendations.movie_id')
            ->leftJoin('works as recommended_movie_works', 'recommended_movie_works.tmdb_movie_id', '=', 'recommendations.recommendation_movie_id')
            ->leftJoin('works as tv_works', 'tv_works.tmdb_tv_id', '=', 'recommendations.tv_id')
            ->leftJoin('works as recommended_tv_works', 'recommended_tv_works.tmdb_tv_id', '=', 'recommendations.recommendation_tv_id')
            ->update([
                'work_id'             => DB::raw('COALESCE(movie_works.id, tv_works.id)'),
                'recommended_work_id' => DB::raw('COALESCE(recommended_movie_works.id, recommended_tv_works.id)'),
            ]);

        DB::table('recommendations')
            ->whereNull('work_id')
            ->orWhereNull('recommended_work_id')
            ->delete();

        Schema::table('recommendations', function (Blueprint $table): void {
            $table->unsignedInteger('work_id')->change();
            $table->unsignedInteger('recommended_work_id')->change();
            $table->dropForeign(['movie_id']);
            $table->dropForeign(['tv_id']);
            $table->dropForeign(['recommendation_movie_id']);
            $table->dropForeign(['recommendation_tv_id']);
            $table->dropColumn([
                'movie_id',
                'tv_id',
                'recommendation_movie_id',
                'recommendation_tv_id',
                'title',
                'poster',
                'vote_average',
                'release_date',
                'first_air_date',
            ]);
        });

        // Delete duplicate recommendations
        DB::table('recommendations')
            ->whereNotIn(
                'id',
                fn ($query) => $query->fromSub(
                    DB::table('recommendations')
                        ->selectRaw('MAX(id)')
                        ->groupBy(['work_id', 'recommended_work_id']),
                    'temp'
                )
            )
            ->delete();

        Schema::table('recommendations', function (Blueprint $table): void {
            $table->dropColumn('id');
            $table->primary(['work_id', 'recommended_work_id']);
        });
        // Torrents

        DB::table('torrents')
            ->join('categories', 'torrents.category_id', '=', 'categories.id')
            ->leftJoin(
                'works',
                fn ($query) => $query
                    ->on(fn ($query) => $query->on('works.tmdb_movie_id', '=', 'torrents.tmdb')->where('categories.movie_meta', '=', 1))
                    ->orOn(fn ($query) => $query->on('works.tmdb_tv_id', '=', 'torrents.tmdb')->where('categories.tv_meta', '=', 1))
                    ->orOn(fn ($query) => $query->on('works.igdb_game_id', '=', 'torrents.igdb')->where('categories.game_meta', '=', 1))
            )
            ->whereNotNull('works.id')
            ->where(
                fn ($query) => $query
                    ->whereNotNull('works.tmdb_movie_id')
                    ->orWhereNotNull('works.tmdb_tv_id')
                    ->orWhereNotNull('works.igdb_game_id')
            )
            ->update([
                'work_id' => DB::raw('works.id'),
            ]);

        Schema::table('torrents', function (Blueprint $table): void {
            //            $table->foreign('work_id')->references('id')->on('works')->cascadeOnUpdate();
            $table->dropColumn(['tmdb', 'imdb', 'mal', 'tvdb', 'igdb']);
        });
    }
};
