@php
    echo '<?xml version="1.0" encoding="UTF-8" ?>'
@endphp
<rss version="2.0"
     xmlns:atom="http://www.w3.org/2005/Atom"
     xmlns:content="http://purl.org/rss/1.0/modules/content/"
     xmlns:media="http://search.yahoo.com/mrss/">
    <channel>
        <title>{{ config('other.title') }}: {{ $rss->name }}</title>
        <link>{{ config('app.url') }}</link>
        <description>
            {!! __('This feed contains your secure rsskey, please do not share with anyone.') !!}
        </description>
        <atom:link href="{{ route('rss.show.rsskey', ['id' => $rss->id, 'rsskey' => $user->rsskey]) }}"
                   type="application/rss+xml" rel="self"></atom:link>
        <copyright>{{ config('other.title') }} {{ now()->year }}</copyright>
        <language>en-us</language>
        <lastBuildDate>{{ now()->toRssString() }}</lastBuildDate>
        <ttl>5</ttl>
        @if($torrents)
            @foreach($torrents as $torrent)
                <item>
                    <title>{{ $torrent->name }}</title>
                    <category>{{ $torrent->category->name }}</category>
                    <contentlength>{{ $torrent->size }}</contentlength>
                    <link>{{ route('torrent.download.rsskey', ['id' => $torrent->id, 'rsskey' => $user->rsskey ]) }}</link>
                    <guid>{{ $torrent->id }}</guid>
                    <description>
                        <![CDATA[<p>
                            <strong>Name</strong>: {{ $torrent->name }}<br>
                            <strong>Category</strong>: {{ $torrent->category->name }}<br>
                            <strong>Type</strong>: {{ $torrent->type->name }}<br>
                            <strong>Resolution</strong>: {{ $torrent->resolution->name ?? 'No Res' }}<br>
                            <strong>Size</strong>: {{ $torrent->getSize() }}<br>
                            <strong>Uploaded</strong>: {{ $torrent->created_at->diffForHumans() }}<br>
                            <strong>Seeders</strong>: {{ $torrent->seeders }} |
                            <strong>Leechers</strong>: {{ $torrent->leechers }} |
                            <strong>Completed</strong>: {{ $torrent->times_completed }}<br>
                            <strong>Uploader</strong>:
                            @if(!$torrent->anon && $torrent->user)
                                {{ __('torrent.uploaded-by') }} {{ $torrent->user->username }}
                            @else
                                {{ __('common.anonymous') }} {{ __('torrent.uploader') }}
                            @endif<br>
                            @if (($torrent->category->movie_meta || $torrent->category->tv_meta) && $torrent->imdb != 0)
                                IMDB Link:<a href="https://anon.to?http://www.imdb.com/title/tt{{ $torrent->imdb }}"
                                             target="_blank">tt{{ $torrent->imdb }}</a><br>
                            @endif
                            @if ($torrent->category->movie_meta && $torrent->tmdb != 0)
                                TMDB Link: <a href="https://anon.to?https://www.themoviedb.org/movie/{{ $torrent->tmdb }}"
                                              target="_blank">{{ $torrent->tmdb }}</a><br>
                            @elseif ($torrent->category->tv_meta && $torrent->tmdb != 0)
                                TMDB Link: <a href="https://anon.to?https://www.themoviedb.org/tv/{{ $torrent->tmdb }}"
                                              target="_blank">{{ $torrent->tmdb }}</a><br>
                            @endif
                            @if (($torrent->category->tv_meta) && $torrent->tvdb != 0)
                                TVDB Link:<a href="https://anon.to?https://www.thetvdb.com/?tab=series&id={{ $torrent->tvdb }}"
                                             target="_blank">{{ $torrent->tvdb }}</a><br>
                            @endif
                            @if (($torrent->category->movie_meta || $torrent->category->tv_meta) && $torrent->mal != 0)
                                MAL Link:<a href="https://anon.to?https://myanimelist.net/anime/{{ $torrent->mal }}"
                                             target="_blank">{{ $torrent->mal }}</a><br>
                            @endif
                            @if ($torrent->internal == 1)
                                <comments>This is a high quality internal release!</comments>
                            @endif
                        </p>]]>
                    </description>
                    <dc:creator xmlns:dc="http://purl.org/dc/elements/1.1/">
                        @if(!$torrent->anon && $torrent->user)
                            {{ __('torrent.uploaded-by') }} {{ $torrent->user->username }}
                        @else
                            {{ __('common.anonymous') }} {{ __('torrent.uploader') }}
                        @endif
                    </dc:creator>
                    <pubDate>{{ $torrent->created_at->toRssString() }}</pubDate>
                </item>
            @endforeach
        @endif
    </channel>
</rss>
