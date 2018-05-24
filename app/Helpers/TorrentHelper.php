<?php
/**
 * NOTICE OF LICENSE
 *
 * UNIT3D is open-sourced software licensed under the GNU General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     Mr.G
 */

namespace App\Helpers;

use App\PersonalFreeleech;
use App\FreeleechToken;
use App\History;
use App\Torrent;
use App\Message;
use App\PrivateMessage;
use App\Wish;
use App\Achievements\UserMadeUpload;
use App\Achievements\UserMade25Uploads;
use App\Achievements\UserMade50Uploads;
use App\Achievements\UserMade100Uploads;
use App\Achievements\UserMade200Uploads;
use App\Achievements\UserMade300Uploads;
use App\Achievements\UserMade400Uploads;
use App\Achievements\UserMade500Uploads;
use App\Achievements\UserMade600Uploads;
use App\Achievements\UserMade700Uploads;
use App\Achievements\UserMade800Uploads;
use App\Achievements\UserMade900Uploads;
use App\Bots\IRCAnnounceBot;
use App\Services\MovieScrapper;

class TorrentHelper
{
    public static function view($results)
    {
        $user = auth()->user();
        $personal_freeleech = PersonalFreeleech::where('user_id', '=', $user->id)->first();

        $data = [];

        foreach ($results as $list) {
            if ($list->sticky == 1) {
                $sticky = "<tr class='info'>";
            } else {
                $sticky = "<tr>";
            }

            $client = new MovieScrapper(config('api-keys.tmdb'), config('api-keys.tvdb'), config('api-keys.omdb'));
            if ($list->category_id == 2) {
                if ($list->tmdb || $list->tmdb != 0) {
                    $movie = $client->scrape('tv', null, $list->tmdb);
                } else {
                    $movie = $client->scrape('tv', 'tt'. $list->imdb);
                }
            } else {
                if ($list->tmdb || $list->tmdb != 0) {
                    $movie = $client->scrape('movie', null, $list->tmdb);
                } else {
                    $movie = $client->scrape('movie', 'tt'. $list->imdb);
                }
            }

            if ($user->show_poster == 1 && $list->category->meta == 1) {
                $poster = "<div class='torrent-poster pull-left'><img src='{$movie->poster}' data-poster-mid='{$movie->poster}' class='img-tor-poster torrent-poster-img-small' alt='Poster' data-original-title='' title=''></div>";
            } else {
                $poster = "";
            }

            $category_link = route('category', ['slug' => $list->category->slug, 'id' => $list->category->id]);

            $category = "<i class='{$list->category->icon} torrent-icon' data-toggle='tooltip' title='' data-original-title='{$list->category->name} Torrent'></i>";

            $torrent_link = route('torrent', ['slug' => $list->slug, 'id' => $list->id]);
            $download_check_link = route('download_check', ['slug' => $list->slug, 'id' => $list->id]);
            $user_link = route('profile', ['username' => $list->user->username, 'id' => $list->user->id]);
            $peers_link = route('peers', ['slug' => $list->slug, 'id' => $list->id]);
            $history_link = route('history', ['slug' => $list->slug, 'id' => $list->id]);

            $unbookmark_link = route('unbookmark', ['id' => $list->id]);
            $bookmark_link = route('bookmark', ['id' => $list->id]);
            if ($user->isBookmarked($list->id)) {
                $bookmark = "<a href='{$unbookmark_link}'><button class='btn btn-danger btn-circle' type='button' data-toggle='tooltip' title='' data-original-title='trans('torrent.unbookmark')!'><i class='fa fa-bookmark'></i></button></a>";
            } else {
                $bookmark = "<a href='{$bookmark_link}'><button class='btn btn-primary btn-circle' type='button' data-toggle='tooltip' title='' data-original-title='trans('torrent.bookmark')!'><i class='fa fa-bookmark'></i></button></a>";
            }

            if ($list->anon == 1) {
                if ($user->id == $list->user->id || $user->group->is_modo) {
                    $staff_anon = "<a href='{$user_link}'>({$list->user->username})</a>";
                } else {
                    $staff_anon = "";
                }

                $anon = "ANONYMOUS {$staff_anon}";
            } else {
                $anon = "<a href='{$user_link}'>{$list->user->username}</a>";
            }

            if ($list->category->meta == 1) {
                if ($user->ratings == 1) {
                    $link = "https://anon.to?http://www.imdb.com/title/tt" . $list->imdb;
                    $rating = $movie->imdbRating;
                    $votes = $movie->imdbVotes;
                } else {
                    $rating = $movie->tmdbRating;
                    $votes = $movie->tmdbVotes;
                    if ($list->category_id == '2') {
                        $link = "https://www.themoviedb.org/tv/" . $movie->tmdb;
                    } else {
                        $link = "https://www.themoviedb.org/movie/" . $movie->tmdb;
                    }
                }
            } else {
                $link = "#";
                $rating = "0";
                $votes = "0";
            }

            $thank_count = $list->thanks()->count();

            $icons = "";

            if ($list->stream == "1") {
                $icons .= "<span class='badge-extra text-bold'><i class='fa fa-play text-red' data-toggle='tooltip' title='' data-original-title='Stream Optimized'></i> Stream Optimized</span>";
            }

            if ($list->featured == "0") {
                if ($list->doubleup == "1") {
                    $icons .= "<span class='badge-extra text-bold'><i class='fa fa-diamond text-green' data-toggle='tooltip' title='' data-original-title='Double upload'></i> Double Upload</span>";
                }

                if ($list->free == "1") {
                    $icons .= "<span class='badge-extra text-bold'><i class='fa fa-star text-gold' data-toggle='tooltip' title='' data-original-title='100% Free'></i> 100% Free</span>";
                }
            }

            if ($personal_freeleech) {
                $icons .= "<span class='badge-extra text-bold'><i class='fa fa-id-badge text-orange' data-toggle='tooltip' title='' data-original-title='Personal FL'></i> Personal FL</span>";
            }

            $freeleech_token = FreeleechToken::where('user_id', '=', $user->id)->where('torrent_id', '=', $list->id)->first();
            if ($freeleech_token) {
                $icons .= "<span class='badge-extra text-bold'><i class='fa fa-viacoin text-bold' data-toggle='tooltip' title='' data-original-title='Freeleech Token'></i> Freeleech Token</span>";
            }

            if ($list->featured == "1") {
                $icons .= "<span class='badge-extra text-bold' style='background-image:url(https://i.imgur.com/F0UCb7A.gif);'><i class='fa fa-certificate text-pink' data-toggle='tooltip' title='' data-original-title='Featured Torrent'></i> Featured</span>";
            }

            if ($user->group->is_freeleech == 1) {
                $icons .= "<span class='badge-extra text-bold'><i class='fa fa-trophy text-purple' data-toggle='tooltip' title='' data-original-title='Special FL'></i> Special FL</span>";
            }

            if (config('other.freeleech') == true) {
                $icons .= "<span class='badge-extra text-bold'><i class='fa fa-globe text-blue' data-toggle='tooltip' title='' data-original-title='Global FreeLeech'></i> Global FreeLeech</span>";
            }

            if (config('other.doubleup') == true) {
                $icons .= "<span class='badge-extra text-bold'><i class='fa fa-globe text-green' data-toggle='tooltip' title='' data-original-title='Double Upload'></i> Global Double Upload</span>";
            }

            if ($list->leechers >= "5") {
                $icons .= "<span class='badge-extra text-bold'><i class='fa fa-fire text-orange' data-toggle='tooltip' title='' data-original-title='Hot!'></i> Hot</span>";
            }

            if ($list->sticky == 1) {
                $icons .= "<span class='badge-extra text-bold'><i class='fa fa-thumb-tack text-black' data-toggle='tooltip' title='' data-original-title='Sticky!''></i> Sticky</span>";
            }

            if ($user->updated_at->getTimestamp() < $list->created_at->getTimestamp()) {
                $icons .= "<span class='badge-extra text-bold'><i class='fa fa-magic text-black' data-toggle='tooltip' title='' data-original-title='NEW!'></i> NEW</span>";
            }

            if ($list->highspeed == 1) {
                $icons .= "<span class='badge-extra text-bold'><i class='fa fa-tachometer text-red' data-toggle='tooltip' title='' data-original-title='High Speeds!'></i> High Speeds</span>";
            }

            if ($list->sd == 1) {
                $icons .= "<span class='badge-extra text-bold'><i class='fa fa-ticket text-orange' data-toggle='tooltip' title='' data-original-title='SD Content!'></i> SD Content</span>";
            }

            $status = "";

            $history = History::where('user_id', '=', $user->id)->where('info_hash', '=', $list->info_hash)->first();

            if ($history) {
                if ($history->seeder == 1 && $history->active == 1) {
                    $status .= "<button class='btn btn-success btn-circle' type='button' data-toggle='tooltip' title='' data-original-title='Currently Seeding!'><i class='fa fa-arrow-up'></i></button>";
                }
                if ($history->seeder == 0 && $history->active == 1) {
                    $status .= "<button class='btn btn-warning btn-circle' type='button' data-toggle='tooltip' title='' data-original-title='Currently Leeching!'><i class='fa fa-arrow-down'></i></button>";
                }
                if ($history->seeder == 0 && $history->active == 0 && $history->completed_at == null) {
                    $status .= "<button class='btn btn-info btn-circle' type='button' data-toggle='tooltip' title='' data-original-title='Started Downloading But Never Completed!'><i class='fa fa-hand-paper-o'></i></button>";
                }
                if ($history->seeder == 0 && $history->active == 0 && $history->completed_at != null) {
                    $status .= "<button class='btn btn-danger btn-circle' type='button' data-toggle='tooltip' title='' data-original-title='You Completed This Download But Are No Longer Seeding It!'><i class='fa fa-thumbs-down'></i></button>";
                }
            }

            $datetime = date('Y-m-d H:m:s', strtotime($list->created_at));
            $datetime_inner = $list->created_at->diffForHumans();

            $common_times = trans('common.times');


            $data[] = $sticky .
                "<td>" . $poster . "</td>
            <td>
              <center>
              <a href='{$category_link}'>{$category}</a>
              <br>
              <br>
              <span class='label label-success'>{$list->type}</span>
              </center>
            </td>
            <td>
                <a class='view-torrent' data-id='{$list->id}' data-slug='{$list->slug}' href='{$torrent_link}' data-toggle='tooltip' title='' data-original-title='{$list->name}'>{$list->name}</a>
                <a href='{$download_check_link}'><button class='btn btn-primary btn-circle' type='button' data-toggle='tooltip' title='' data-original-title='DOWNLOAD!'><i class='fa fa-download'></i></button></a>
                {$bookmark}
                {$status}
                <br>
                <strong>
                <span class='badge-extra text-bold'>
                <i class='fa fa-upload'></i> By {$anon}
                </span>

                <a rel='nofollow' href='{$link}'>
                <span class='badge-extra text-bold'>
                  <span class='text-gold movie-rating-stars'>
                    <i class='fa fa-star' data-toggle='tooltip' title='' data-original-title='View More'></i>
                  </span>
                  {$rating}/10 ({$votes} votes)
                </span>
                </a>

                <span class='badge-extra text-bold text-pink'><i class='fa fa-heart' data-toggle='tooltip' title='' data-original-title='Thanks Given'></i>{$thank_count}</span>

                {$icons}
                </strong>
            </td>

            <td><time datetime='{$datetime}'>{$datetime_inner}</time></td>
            <td><span class='badge-extra text-blue text-bold'>" . $list->getSize() . "</span></td>
            <td><a href='{$history_link}'><span class='badge-extra text-orange text-bold'>{$list->times_completed} {$common_times}</span></a></td>
            <td><a href='{$peers_link}'><span class='badge-extra text-green text-bold'>{$list->seeders}</span></a></td>
            <td><a href='{$peers_link}'><span class='badge-extra text-red text-bold'>{$list->leechers}</span></a></td>
            </tr>
            ";
        }
        return $data;
    }

    public static function approveHelper($slug, $id)
    {
        $appurl = config('app.url');
        $appname = config('app.name');

        Torrent::approve($id);
        $torrent = Torrent::withAnyStatus()->where('id', '=', $id)->where('slug', '=', $slug)->first();

        $wishes = Wish::where('imdb', 'tt'.$torrent->imdb)->whereNull('source')->get();
        if ($wishes) {
            foreach ($wishes as $wish) {
                $wish->source = "{$appurl}/torrents/{$torrent->slug}.{$torrent->id}";
                $wish->save();

                // Send Private Message
                $pm = new PrivateMessage;
                $pm->sender_id = 1;
                $pm->receiver_id = $wish->user_id;
                $pm->subject = "Wish List Notice!";
                $pm->message = "The following item, {$wish->title}, from your wishlist has been uploaded to {$appname}! You can view it [url={$appurl}/torrents/" . $torrent->slug . "." . $torrent->id . "] HERE [/url]";
                $pm->save();
            }
        }

        $user = $torrent->user;
        $user_id = $user->id;
        $username = $user->username;
        $anon = $torrent->anon;

        if ($anon == 0) {
            // Auto Shout and Achievements
            $user->unlock(new UserMadeUpload(), 1);
            $user->addProgress(new UserMade25Uploads(), 1);
            $user->addProgress(new UserMade50Uploads(), 1);
            $user->addProgress(new UserMade100Uploads(), 1);
            $user->addProgress(new UserMade200Uploads(), 1);
            $user->addProgress(new UserMade300Uploads(), 1);
            $user->addProgress(new UserMade400Uploads(), 1);
            $user->addProgress(new UserMade500Uploads(), 1);
            $user->addProgress(new UserMade600Uploads(), 1);
            $user->addProgress(new UserMade700Uploads(), 1);
            $user->addProgress(new UserMade800Uploads(), 1);
            $user->addProgress(new UserMade900Uploads(), 1);
        }

        // Announce To Shoutbox
        if ($anon == 0) {
            Message::create(['user_id' => "1", 'chatroom_id' => "1", 'message' => "User [url={$appurl}/" . $username . "." . $user_id . "]" . $username . "[/url] has uploaded [url={$appurl}/torrents/" . $slug . "." . $id . "]" . $torrent->name . "[/url] grab it now! :slight_smile:"]);
        } else {
            Message::create(['user_id' => "1", 'chatroom_id' => "1", 'message' => "An anonymous user has uploaded [url={$appurl}/torrents/" . $slug . "." . $id . "]" . $torrent->name . "[/url] grab it now! :slight_smile:"]);
        }

        // Announce To IRC
        if (config('irc-bot.enabled') == true) {
            $appname = config('app.name');
            $bot = new IRCAnnounceBot();
            if ($anon == 0) {
                $bot->message("#announce", "[" . $appname . "] User " . $username . " has uploaded " . $torrent->name . " grab it now!");
                $bot->message("#announce", "[Category: " . $torrent->category->name . "] [Type: " . $torrent->type . "] [Size:" . $torrent->getSize() . "]");
                $bot->message("#announce", "[Link: {$appurl}/torrents/" . $slug . "." . $id . "]");
            } else {
                $bot->message("#announce", "[" . $appname . "] An anonymous user has uploaded " . $torrent->name . " grab it now!");
                $bot->message("#announce", "[Category: " . $torrent->category->name . "] [Type: " . $torrent->type . "] [Size: " . $torrent->getSize() . "]");
                $bot->message("#announce", "[Link: {$appurl}/torrents/" . $slug . "." . $id . "]");
            }
        }

        // Activity Log
        \LogActivity::addToLog("Torrent " . $torrent->name . " uploaded by " . $username . " has been approved.");
    }
}
