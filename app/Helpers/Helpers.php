<?php
/**
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
    function hrefProfile($user): string
    {
        $appurl = appurl();

        return sprintf('%s/users/%s', $appurl, $user->username);
    }
}

if (! function_exists('hrefArticle')) {
    function hrefArticle($article): string
    {
        $appurl = appurl();

        return sprintf('%s/articles/%s', $appurl, $article->id);
    }
}

if (! function_exists('hrefTorrent')) {
    function hrefTorrent($torrent): string
    {
        $appurl = appurl();

        return sprintf('%s/torrents/%s', $appurl, $torrent->id);
    }
}

if (! function_exists('hrefRequest')) {
    function hrefRequest($torrentRequest): string
    {
        $appurl = appurl();

        return sprintf('%s/requests/%s', $appurl, $torrentRequest->id);
    }
}

if (! function_exists('hrefPoll')) {
    function hrefPoll($poll): string
    {
        $appurl = appurl();

        return sprintf('%s/polls/%s', $appurl, $poll->slug);
    }
}

if (! function_exists('hrefPlaylist')) {
    function hrefPlaylist($playlist): string
    {
        $appurl = appurl();

        return sprintf('%s/playlists/%s', $appurl, $playlist->id);
    }
}
