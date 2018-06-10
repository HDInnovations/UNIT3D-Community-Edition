<?php

if (!function_exists('appurl')) {
    function appurl()
    {
        return config('app.url');
    }
}

if (!function_exists('hrefProfile')) {
    function hrefProfile($user)
    {
        $appurl = appurl();
        return "{$appurl}/{$user->username}.{$user->id}";
    }
}

if (!function_exists('hrefArticle')) {
    function hrefArticle($article)
    {
        $appurl = appurl();
        return "{$appurl}/articles/{$article->slug}.{$article->id}";
    }
}

if (!function_exists('hrefTorrent')) {
    function hrefTorrent($torrent)
    {
        $appurl = appurl();
        return "{$appurl}/torrents/{$torrent->slug}.{$torrent->id}";
    }
}

if (!function_exists('hrefTorrentRequest')) {
    function hrefTorrentRequest($torrentRequest)
    {
        $appurl = appurl();
        $slug = str_slug($torrentRequest->name);
        return "{$appurl}/request/{$torrentRequest->id}";
    }
}

if (!function_exists('hrefPoll')) {
    function hrefPoll($poll)
    {
        $appurl = appurl();
        return "{$appurl}/poll/{$poll->slug}";
    }
}