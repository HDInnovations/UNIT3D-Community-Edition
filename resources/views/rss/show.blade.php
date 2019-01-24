<?php
echo '<?xml version="1.0" encoding="utf-8"?>';
?>
<rss version="2.0" xmlns:dc="http://purl.org/dc/elements/1.1/">
    <channel>
        <xhtml:meta xmlns:xhtml="http://www.w3.org/1999/xhtml" name="robots" content="noindex" />
        <meta xmlns="http://pipes.yahoo.com" name="pipes" content="noprocess" />
        @if($torrents)
            @foreach($torrents as $data)
                <item>
                    @if($data->isFreeleech())
                        <title><![CDATA[{{ $data->name }} / {{ $data->getSize() }} / Free]]></title>
                    @else
                        <title><![CDATA[{{ $data->name }} / {{ $data->getSize() }}]]></title>
                    @endif
                    <description><![CDATA[]]></description>
                    <pubDate>{{ $data->created_at }}</pubDate>
                    <link>{{ route('torrent.download.rsskey', ['slug' => $data->slug, 'id' => $data->id, 'rsskey' => $rsskey ]) }}</link>
                    <comments>{{ route('torrent', ['slug' => $data->slug, 'id' => $data->id ]) }}</comments>
                    <category><![CDATA[]]></category>
                        @if(!$data->anon && $data->user)
                            <dc:creator>{{ $data->user->username }}</dc:creator>
                        @else
                            <dc:creator>Anonymous</dc:creator>
                        @endif
                </item>
            @endforeach
        @endif
    </channel>
</rss>