<rss xmlns:atom="http://www.w3.org/2005/Atom" version="2.0">
  <channel>
    <title>{{ config('other.title') }}</title>
    <description>{{ config('other.title') }} {{ trans('common.rss-system') }}</description>
    <link>{{ url('/') }}</link>
    <language>{{ config('app.locale') }}</language>
    @foreach ($torrents as $data)
    <item>
      <title>{{ $data->name }}</title>
      <link>{{ route('rssDownload', ['id' => $data->id, 'passkey' => $passkey]) }}</link>
      <guid>{{ route('torrent', ['id' => $data->id ,'slug' => $data->slug]) }}</guid>
      <blu:size>{{ $data->getSize() }}<blu:size>
      <blu:seeders>{{ $data->seeders }}</blu:seeders>
      <blu:leechers>{{ $data->leechers }}</blu:leechers>
      <pubDate>{{ $data->created_at }}</pubDate>
      <description><![CDATA[{{ $data->name }} || {{ $data->getSize() }} || {{ $data->category->name }} || {{ $data->info_hash }}]]></description>
    </item>
    @endforeach
</channel>
</rss>
