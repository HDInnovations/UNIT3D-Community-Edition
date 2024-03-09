@php
    echo '<?xml version="1.0" encoding="UTF-8" ?>'
@endphp
<caps>
    <server
        version="1.3"
        title="{{ config('other.title') }}"
        strapline="{{ config('other.subTitle') }}"
        url="{{ config('app.url') }}"
    />
    <limits max="100" default="25"/>
    <registration available="no" open="yes" />
    <searching>
        <search available="yes" supportedParams="q,imdbid,tvdbid,tmdbid,tag" />
        <tv-search available="yes" supportedParams="q,season,ep,imdbid,tvdbid,tmdbid,tag" />
        <movie-search available="yes" supportedParams="q,imdbid,tmdbid,tag" />
        <audio-search available="yes" supportedParams="q,tag" />
        <book-search available="no" supportedParams="" />
    </searching>
    <categories>
        <category id="110000" name="category">
            @foreach($categories as $category)
                <subcat id="{{ 110000 + $category->id }}" name="{{ $category->name }}" />
            @endforeach
        </category>
        <category id="120000" name="resolution">
            @foreach($resolutions as $resolution)
                <subcat id="{{ 120000 + $resolution->id }}" name="{{ $resolution->name }}"/>
            @endforeach
        </category>
        <category id="130000" name="type">
            @foreach($types as $type)
                <subcat id="{{ 130000 + $type->id }}" name="{{ $type->name }}"/>
            @endforeach
        </category>
        <category id="140000" name="region">
            @foreach($types as $type)
                <subcat id="{{ 140000 + $type->id }}" name="{{ $type->name }}"/>
            @endforeach
        </category>
        <category id="150000" name="distributor">
            @foreach($types as $type)
                <subcat id="{{ 150000 + $type->id }}" name="{{ $type->name }}"/>
            @endforeach
        </category>
    </categories>
    <tags>
        <tag name="anon" description="This torrent was uploaded by an anonymous user." />
        <tag name="featured" description="This torrent is featured by staff and has both 100% freeleech and double upload." />
        <tag name="highspeed" description="An IP of a registered seedbox is in this torrent\'s swarm" />
        <tag name="internal" description="This torrent is an internal release." />
        <tag name="personal_release" description="The content of this torrent was created by the uploader." />
        <tag name="refundable" description="You are refunded downloaded credit if you continue seeding this torrent." />
        <tag name="sd" description="This torrent contains standard-definition content." />
        <tag name="sticky" description="This torrent is pinned to the top of the torrent list." />
        <tag name="stream" description="This torrent is optimized for streaming remotely." />
    </tags>
</caps>
</xml>
