@php
    echo '<?xml version="1.0" encoding="UTF-8" ?>'
@endphp
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom" xmlns:torznab="http://torznab.com/schemas/2015/feed">
    <channel>
        <atom:link
            href="{{ route('rss.show.rsskey', ['id' => $rss->id, 'rsskey' => $user->rsskey]) }}"
            type="application/rss+xml"
            rel="self"
        ></atom:link>
        <title>{{ config('other.title') }}: Torznab API</title>
        <description>
            {{ __('This feed contains your secure rsskey, please do not share with anyone.') }}
        </description>
        <link>{{ config('app.url') }}</link>
        <language>en-US</language>
        <category>search</category>
        @foreach ($torrents as $torrent)
            <item>
                <title>{{ $torrent->name }}</title>
                <guid>{{ route('download', ['id' => $torrent->id]) }}</guid>
                <comments>{{ route('torrents.show', ['id' => $this->id]).'#comments' }}</comments>
                <pubDate>{{ $torrent->created_at->toRssString() }}</pubDate>
                <size>{{ $torrent->size }}</size>
                <files>{{ $torrent->files }}</files>
                <grabs>{{ $torrent->times_completed }}</grabs>
                <link>{{ route('torrent.download.rsskey', ['id' => $torrent->id, 'rsskey' => $user->rsskey]) }}</link>
                <description>{{ $torrent->description }}</description>
                <category>{{ 100100 + $torrent->category_id }}</category>
                <category>{{ 100200 + $torrent->resolution_id  }}</category>
                <category>{{ 100300 + $torrent->type_id  }}</category>
                <enclosure
                    url="{{ route('torrent.download.rsskey', ['id' => $torrent->id, 'rsskey' => $user->rsskey]) }}"
                    length="{{ $torrent->size }}"
                    type="application/x-bittorrent"
                />
{{--                <torznab:attr name="category" value="2000" />--}}
{{--                <torznab:attr name="category" value="100001" />--}}
                <torznab:attr name="tvdbid" value="{{ $torrent->tvdb }}" />
                <torznab:attr name="imdb" value="{{ $torrent->imdb }}" />
                <torznab:attr name="imdbid" value="tt{{ \str_pad((int) $torrent->imdb, \max(\strlen((int) $torrent->imdb), 7), '0', STR_PAD_LEFT) }}" />
                <torznab:attr name="tmdbid" value="{{ $torrent->tmdb }}" />
                <torznab:attr name="poster" value="{{ $torrent->anon ? __('common.anonymous') : $torrent->user->username }}" />
                <torznab:attr name="seeders" value="{{ $torrent->seeders }}" />
                <torznab:attr name="leechers" value="{{ $torrent->leechers }}" />
                <torznab:attr name="peers" value="{{ $torrent->seeders + $torrent->leechers }}" />
                <torznab:attr name="grabs" value="{{ $torrent->times_completed }}" />
                <torznab:attr name="files" value="{{ $torrent->num_file }}" />
                <torznab:attr name="season" value="{{ $torrent->season_number }}" />
                <torznab:attr name="episode" value="{{ $torrent->episode_number }}" />
                <torznab:attr name="comments" value="{{ $torrent->comment_count ?? 0 }}" />
                <torznab:attr name="year" value="{{ $torrent->release_year }}" />
                <torznab:attr name="infohash" value="{{ bin2hex($torrent->info_hash) }}" />
                <torznab:attr name="minimumseedtime" value="{{ config('hitrun.seedtime') }}" />
                <torznab:attr name="downloadvolumefactor" value="{{ match (true) {
                    cache()->get('freeleech_token:'.$request->user()->id.':'.$this->id) => 0,
                    $torrent->fl_until === null && $torrent->free > 0                   => (100 - min(100, $torrent->free)) / 100,
                    default                                                             => 1,
                } }}" />
                <torznab:attr name="uploadvolumefactor" value="{{ match (true) {
                    $torrent->du_until === null && $torrent->doubleup => 2,
                    default                                           => 1,
                } }}" />
                @if ($torrent->doubleup)
                    <torznab:attr name="tag" value="doubleup" />
                @endif
                @if ($torrent->refundable)
                    <torznab:attr name="tag" value="refundable" />
                @endif
                @if ($torrent->highspeed)
                    <torznab:attr name="tag" value="highspeed" />
                @endif
                @if ($torrent->anon)
                    <torznab:attr name="tag" value="anon" />
                @endif
                @if ($torrent->sticky)
                    <torznab:attr name="tag" value="sticky" />
                @endif
                @if ($torrent->sd)
                    <torznab:attr name="tag" value="sd" />
                @endif
                @if ($torrent->internal)
                    <torznab:attr name="tag" value="internal" />
                @endif
            </item>
        @endforeach
    </channel>
</rss>
