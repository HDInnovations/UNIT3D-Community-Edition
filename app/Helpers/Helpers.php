<?php

use Illuminate\Support\Str;

/*
 * NOTICE OF LICENSE.
 *
 * UNIT3D is open-sourced software licensed under the GNU Affero General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 *
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     HDVinnie
 */
if (! function_exists('appurl')) {
    function appurl()
    {
        return config('app.url');
    }
}

if (! function_exists('hrefProfile')) {
    function hrefProfile($user)
    {
        $appurl = appurl();

        return "{$appurl}/{$user->username}.{$user->id}";
    }
}

if (! function_exists('hrefArticle')) {
    function hrefArticle($article)
    {
        $appurl = appurl();

        return "{$appurl}/articles/{$article->slug}.{$article->id}";
    }
}

if (! function_exists('hrefTorrent')) {
    function hrefTorrent($torrent)
    {
        $appurl = appurl();

        return "{$appurl}/torrents/{$torrent->slug}.{$torrent->id}";
    }
}

if (! function_exists('hrefRequest')) {
    function hrefRequest($torrentRequest)
    {
        $appurl = appurl();
        $slug = Str::slug($torrentRequest->name);

        return "{$appurl}/request/{$torrentRequest->id}";
    }
}

if (! function_exists('hrefPoll')) {
    function hrefPoll($poll)
    {
        $appurl = appurl();

        return "{$appurl}/poll/{$poll->slug}";
    }
}

if (! function_exists('hrefPlaylist')) {
    function hrefPlaylist($playlist)
    {
        $appurl = appurl();

        return "{$appurl}/playlists/{$playlist->id}";
    }
}
