@php
echo '<?xml version="1.0" encoding="UTF-8" ?>'
@endphp

<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
    <channel>
        <atom:link href="!#" rel="self" type="application/rss+xml" />
        <title>{{ config('other.title') }} @lang('rss.rss-feed')</title>
        <link>{{ config('app.url') }}</link>
        <description>{{ config('unit3d.powered-by') }}</description>
        @if($torrents)
            @foreach($torrents as $data)
                <item>
                    <title>{{ $data->name }}</title>
                    <link>{{ route('torrent.download.rsskey', ['id' => $data->id, 'rsskey' => $rsskey ]) }}</link>
                    <description>{{ $data->category->name }} / {{ $data->type }} / {{ $data->getSize() }} @if($data->freeleech === 1) / Double Upload! @endif @if($data->doubleup === 1) / Freeleech! @endif</description>
                    @if(!$data->anon && $data->user)
                        <author>@lang('torrent.uploaded-by') {{ $data->user->username }}</author>
                    @else
                        <author>@lang('common.anonymous') @lang('torrent.uploader')</author>
                    @endif
                    <pubDate>{{ $data->created_at->toRssString() }}</pubDate>
                    <comments>{{ route('torrent', ['id' => $data->id ]) }}</comments>
                </item>
            @endforeach
        @endif
    </channel>
</rss>