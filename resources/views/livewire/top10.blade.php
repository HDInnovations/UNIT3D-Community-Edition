<div style="display: contents">
    @foreach([
        'Top 10 (Day)'       => $torrentsDay,
        'Top 10 (Week)'      => $torrentsWeek,
        'Top 10 (Month)'     => $torrentsMonth,
        'Top 10 (Year)'      => $torrentsYear,
        'Top 10 (All Time)'  => $torrentsAll
    ] as $title => $torrents)
        <section class="panelV2">
            <h2 class="panel__heading">{{ $title }}</h2>
            <div class="data-table-wrapper">
                <table class="data-table">
                    <tbody>
                        @foreach($torrents->loadExists([
                            'bookmarks'       => fn ($query) => $query->where('user_id', '=', auth()->id()),
                            'freeleechTokens' => fn ($query) => $query->where('user_id', '=', auth()->id()),
                        ]) as $torrent)
                            @php
                                $meta = match(true) {
                                    $torrent->category->tv_meta && $torrent->tmdb != 0    => App\Models\Tv::find($torrent->tmdb),
                                    $torrent->category->movie_meta && $torrent->tmdb != 0 => App\Models\Movie::find($torrent->tmdb),
                                    $torrent->category->game_meta && $torrent->igdb != 0  => MarcReichel\IGDBLaravel\Models\Game::with(['cover' => ['url', 'image_id']])->find($torrent->igdb),
                                    default                                               => null,
                                };
                            @endphp
                            <x-torrent.row :$torrent :$meta :$personalFreeleech />
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>
    @endforeach
</div>
