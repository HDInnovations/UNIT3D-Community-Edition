<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;

/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D Community Edition is open-sourced software licensed under the GNU Affero General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D Community Edition
 *
 * @author     HDVinnie <hdinnovations@protonmail.com>
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/
if (config('unit3d.proxy_scheme')) {
    URL::forceScheme(config('unit3d.proxy_scheme'));
}

if (config('unit3d.root_url_override')) {
    URL::forceRootUrl(config('unit3d.root_url_override'));
}
Route::middleware('language')->group(function (): void {
    /*
    |---------------------------------------------------------------------------------
    | Website (Not Authorized) (Alpha Ordered)
    |---------------------------------------------------------------------------------
    */
    Route::middleware('guest')->group(function (): void {
        // Application Signup
        Route::get('/application', [App\Http\Controllers\Auth\ApplicationController::class, 'create'])->name('application.create');
        Route::post('/application', [App\Http\Controllers\Auth\ApplicationController::class, 'store'])->name('application.store');

        // This redirect must be kept until all invite emails that use the old syntax have expired
        // Hack so that Fortify can be used (allows query parameters but not route parameters)
        Route::get('/register/{code?}', fn (string $code) => to_route('register', ['code' => $code]));
    });

    /*
    |---------------------------------------------------------------------------------
    | Website (When Authorized) (Alpha Ordered)
    |---------------------------------------------------------------------------------
    */
    Route::middleware(['auth', 'banned', 'verified'])->group(function (): void {
        // General
        Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home.index');

        // Articles System
        Route::prefix('articles')->group(function (): void {
            Route::name('articles.')->group(function (): void {
                Route::get('/', [App\Http\Controllers\ArticleController::class, 'index'])->name('index');
                Route::get('/{article}', [App\Http\Controllers\ArticleController::class, 'show'])->name('show');
            });
        });

        // RSS System
        Route::prefix('rss')->group(function (): void {
            Route::name('rss.')->group(function (): void {
                Route::get('/', [App\Http\Controllers\RssController::class, 'index'])->name('index');
                Route::get('/create', [App\Http\Controllers\RssController::class, 'create'])->name('create');
                Route::post('/store', [App\Http\Controllers\RssController::class, 'store'])->name('store');
                Route::get('/{id}/edit', [App\Http\Controllers\RssController::class, 'edit'])->name('edit');
                Route::patch('/{id}/update', [App\Http\Controllers\RssController::class, 'update'])->name('update');
                Route::delete('/{id}/destroy', [App\Http\Controllers\RssController::class, 'destroy'])->name('destroy');
            });
        });

        // Reports System
        Route::prefix('reports')->group(function (): void {
            Route::post('/torrent/{id}', [App\Http\Controllers\ReportController::class, 'torrent'])->name('report_torrent');
            Route::post('/request/{id}', [App\Http\Controllers\ReportController::class, 'request'])->name('report_request');
            Route::post('/user/{username}', [App\Http\Controllers\ReportController::class, 'user'])->name('report_user');
        });

        // Contact Us System
        Route::prefix('contact')->group(function (): void {
            Route::name('contact.')->group(function (): void {
                Route::get('/', [App\Http\Controllers\ContactController::class, 'index'])->name('index');
                Route::post('/store', [App\Http\Controllers\ContactController::class, 'store'])->name('store');
            });
        });

        // Pages System
        Route::prefix('pages')->group(function (): void {
            Route::get('/', [App\Http\Controllers\PageController::class, 'index'])->name('pages.index');
            Route::get('/staff', [App\Http\Controllers\PageController::class, 'staff'])->name('staff');
            Route::get('/internal', [App\Http\Controllers\PageController::class, 'internal'])->name('internal');
            Route::get('/blacklist/clients', [App\Http\Controllers\PageController::class, 'clientblacklist'])->name('client_blacklist');
            Route::get('/aboutus', [App\Http\Controllers\PageController::class, 'about'])->name('about');
            Route::get('/{page}', [App\Http\Controllers\PageController::class, 'show'])->where('id', '[0-9]+')->name('pages.show');
        });

        // Wiki System
        Route::prefix('wikis')->group(function (): void {
            Route::get('/', [App\Http\Controllers\WikiController::class, 'index'])->name('wikis.index');
            Route::get('/{wiki}', [App\Http\Controllers\WikiController::class, 'show'])->name('wikis.show');
        });

        // Extra-Stats System
        Route::prefix('stats')->group(function (): void {
            Route::get('/', [App\Http\Controllers\StatsController::class, 'index'])->name('stats');
            Route::get('/user/clients', [App\Http\Controllers\StatsController::class, 'clients'])->name('clients');
            Route::get('/user/uploaded', [App\Http\Controllers\StatsController::class, 'uploaded'])->name('uploaded');
            Route::get('/user/downloaded', [App\Http\Controllers\StatsController::class, 'downloaded'])->name('downloaded');
            Route::get('/user/seeders', [App\Http\Controllers\StatsController::class, 'seeders'])->name('seeders');
            Route::get('/user/leechers', [App\Http\Controllers\StatsController::class, 'leechers'])->name('leechers');
            Route::get('/user/uploaders', [App\Http\Controllers\StatsController::class, 'uploaders'])->name('uploaders');
            Route::get('/user/bankers', [App\Http\Controllers\StatsController::class, 'bankers'])->name('bankers');
            Route::get('/user/seedtime', [App\Http\Controllers\StatsController::class, 'seedtime'])->name('seedtime');
            Route::get('/user/seedsize', [App\Http\Controllers\StatsController::class, 'seedsize'])->name('seedsize');
            Route::get('/torrent/seeded', [App\Http\Controllers\StatsController::class, 'seeded'])->name('seeded');
            Route::get('/torrent/leeched', [App\Http\Controllers\StatsController::class, 'leeched'])->name('leeched');
            Route::get('/torrent/completed', [App\Http\Controllers\StatsController::class, 'completed'])->name('completed');
            Route::get('/torrent/dying', [App\Http\Controllers\StatsController::class, 'dying'])->name('dying');
            Route::get('/torrent/dead', [App\Http\Controllers\StatsController::class, 'dead'])->name('dead');
            Route::get('/request/bountied', [App\Http\Controllers\StatsController::class, 'bountied'])->name('bountied');
            Route::get('/groups', [App\Http\Controllers\StatsController::class, 'groups'])->name('groups');
            Route::get('/groups/group/{id}', [App\Http\Controllers\StatsController::class, 'group'])->name('group');
            Route::get('/languages', [App\Http\Controllers\StatsController::class, 'languages'])->name('languages');
            Route::get('/themes', [App\Http\Controllers\StatsController::class, 'themes'])->name('themes');
        });

        // Requests System
        Route::prefix('requests')->name('requests.')->group(function (): void {
            Route::get('/', [App\Http\Controllers\RequestController::class, 'index'])->name('index');
            Route::get('/create', [App\Http\Controllers\RequestController::class, 'create'])->name('create');
            Route::post('/', [App\Http\Controllers\RequestController::class, 'store'])->name('store');
            Route::get('/{torrentRequest}/edit', [App\Http\Controllers\RequestController::class, 'edit'])->name('edit');
            Route::patch('/{torrentRequest}', [App\Http\Controllers\RequestController::class, 'update'])->name('update');
            Route::get('/{torrentRequest}', [App\Http\Controllers\RequestController::class, 'show'])->name('show');
            Route::delete('/{torrentRequest}', [App\Http\Controllers\RequestController::class, 'destroy'])->name('destroy');

            Route::prefix('{torrentRequest}/fills')->name('fills.')->group(function (): void {
                Route::post('/', [App\Http\Controllers\RequestFillController::class, 'store'])->name('store');
                Route::delete('/', [App\Http\Controllers\RequestFillController::class, 'destroy'])->name('destroy');
            });

            Route::prefix('{torrentRequest}/approved-fills')->name('approved_fills.')->group(function (): void {
                Route::post('/', [App\Http\Controllers\ApprovedRequestFillController::class, 'store'])->name('store');
                Route::delete('/', [App\Http\Controllers\ApprovedRequestFillController::class, 'destroy'])->name('destroy')->middleware('modo');
            });

            Route::prefix('{torrentRequest}/bounties')->name('bounties.')->group(function (): void {
                Route::post('/', [App\Http\Controllers\BountyController::class, 'store'])->name('store');
                Route::patch('/{torrentRequestBounty}', [App\Http\Controllers\BountyController::class, 'update'])->name('update');
            });

            Route::prefix('{torrentRequest}/claims')->name('claims.')->group(function (): void {
                Route::post('/', [App\Http\Controllers\ClaimController::class, 'store'])->name('store');
                Route::delete('/{claim}', [App\Http\Controllers\ClaimController::class, 'destroy'])->name('destroy');
            })->scopeBindings();
        });

        // Top 10 System
        Route::prefix('top10')->group(function (): void {
            Route::name('top10.')->group(function (): void {
                Route::get('/', [App\Http\Controllers\Top10Controller::class, 'index'])->name('index');
            });
        });

        // Torrents System
        Route::prefix('torrents')->name('torrents.')->group(function (): void {
            Route::get('/', [App\Http\Controllers\TorrentController::class, 'index'])->name('index');
            Route::get('/create', [App\Http\Controllers\TorrentController::class, 'create'])->name('create');
            Route::post('/', [App\Http\Controllers\TorrentController::class, 'store'])->name('store');
            Route::get('/{id}{hash?}', [App\Http\Controllers\TorrentController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [App\Http\Controllers\TorrentController::class, 'edit'])->name('edit');
            Route::patch('/{id}', [App\Http\Controllers\TorrentController::class, 'update'])->name('update');
            Route::delete('/{id}', [App\Http\Controllers\TorrentController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('torrents')->group(function (): void {
            Route::get('/{id}/peers', [App\Http\Controllers\TorrentPeerController::class, 'index'])->name('peers');
            Route::get('/{id}/history', [App\Http\Controllers\TorrentHistoryController::class, 'index'])->name('history');
            Route::get('/{id}/external-tracker', [App\Http\Controllers\ExternalTorrentController::class, 'show'])->name('torrents.external_tracker')->middleware('modo');
            Route::get('/download_check/{id}', [App\Http\Controllers\TorrentDownloadController::class, 'show'])->name('download_check');
            Route::get('/download/{id}', [App\Http\Controllers\TorrentDownloadController::class, 'store'])->name('download');
            Route::post('/{id}/reseed', [App\Http\Controllers\ReseedController::class, 'store'])->name('reseed');
            Route::get('/similar/{category_id}.{tmdb}', [App\Http\Controllers\SimilarTorrentController::class, 'show'])->name('torrents.similar');
            Route::patch('/similar/{category}.{tmdbId}', [App\Http\Controllers\SimilarTorrentController::class, 'update'])->name('torrents.similar.update');
        });

        Route::prefix('torrent')->group(function (): void {
            Route::post('/{id}/torrent_fl', [App\Http\Controllers\TorrentBuffController::class, 'grantFL'])->name('torrent_fl');
            Route::post('/{id}/torrent_doubleup', [App\Http\Controllers\TorrentBuffController::class, 'grantDoubleUp'])->name('torrent_doubleup');
            Route::post('/{id}/bumpTorrent', [App\Http\Controllers\TorrentBuffController::class, 'bumpTorrent'])->name('bumpTorrent');
            Route::post('/{id}/torrent_sticky', [App\Http\Controllers\TorrentBuffController::class, 'sticky'])->name('torrent_sticky');
            Route::post('/{id}/torrent_feature', [App\Http\Controllers\TorrentBuffController::class, 'grantFeatured'])->name('torrent_feature');
            Route::post('/{id}/torrent_revokefeature', [App\Http\Controllers\TorrentBuffController::class, 'revokeFeatured'])->name('torrent_revokefeature');
            Route::post('/{id}/freeleech_token', [App\Http\Controllers\TorrentBuffController::class, 'freeleechToken'])->name('freeleech_token');
            Route::post('/{id}/refundable', [App\Http\Controllers\TorrentBuffController::class, 'setRefundable'])->name('refundable');
        });

        // Poll System
        Route::prefix('polls')->name('polls.')->group(function (): void {
            Route::get('/', [App\Http\Controllers\PollController::class, 'index'])->name('index');
            Route::get('/{poll}', [App\Http\Controllers\PollController::class, 'show'])->name('show');
            Route::prefix('{poll}/votes')->name('votes.')->group(function (): void {
                Route::post('/', [App\Http\Controllers\PollVoteController::class, 'store'])->name('store');
                Route::get('/', [App\Http\Controllers\PollVoteController::class, 'index'])->name('index');
            });
        });

        // Playlist System
        Route::prefix('playlists')->group(function (): void {
            Route::name('playlists.')->group(function (): void {
                Route::get('/', [App\Http\Controllers\PlaylistController::class, 'index'])->name('index');
                Route::get('/create', [App\Http\Controllers\PlaylistController::class, 'create'])->name('create');
                Route::post('/', [App\Http\Controllers\PlaylistController::class, 'store'])->name('store');
                Route::get('/{playlist}', [App\Http\Controllers\PlaylistController::class, 'show'])->name('show');
                Route::get('/{playlist}/edit', [App\Http\Controllers\PlaylistController::class, 'edit'])->name('edit');
                Route::patch('/{playlist}', [App\Http\Controllers\PlaylistController::class, 'update'])->name('update');
                Route::delete('/{playlist}', [App\Http\Controllers\PlaylistController::class, 'destroy'])->name('destroy');
            });
        });

        Route::prefix('playlist-torrents')->group(function (): void {
            Route::name('playlist_torrents.')->group(function (): void {
                Route::post('/', [App\Http\Controllers\PlaylistTorrentController::class, 'store'])->name('store');
                Route::put('/', [App\Http\Controllers\PlaylistTorrentController::class, 'massUpsert'])->name('massUpsert');
                Route::delete('/{playlistTorrent}', [App\Http\Controllers\PlaylistTorrentController::class, 'destroy'])->name('destroy');
            });
        });

        Route::prefix('playlist-zips')->group(function (): void {
            Route::name('playlist_zips.')->group(function (): void {
                Route::get('/{playlist}', [App\Http\Controllers\PlaylistZipController::class, 'show'])->name('show');
            });
        });

        // Yearly Overview
        Route::prefix('yearly-overviews')->group(function (): void {
            Route::name('yearly_overviews.')->group(function (): void {
                Route::get('/', [App\Http\Controllers\YearlyOverviewController::class, 'index'])->name('index');
                Route::get('/{year}', [App\Http\Controllers\YearlyOverviewController::class, 'show'])->name('show');
            });
        });

        // Subtitles System
        Route::prefix('subtitles')->group(function (): void {
            Route::name('subtitles.')->group(function (): void {
                Route::get('/', [App\Http\Controllers\SubtitleController::class, 'index'])->name('index');
                Route::get('/create', [App\Http\Controllers\SubtitleController::class, 'create'])->name('create');
                Route::post('/', [App\Http\Controllers\SubtitleController::class, 'store'])->name('store');
                Route::patch('/{subtitle}', [App\Http\Controllers\SubtitleController::class, 'update'])->name('update');
                Route::delete('/{subtitle}', [App\Http\Controllers\SubtitleController::class, 'destroy'])->name('destroy');
                Route::get('/{subtitle}/download', [App\Http\Controllers\SubtitleController::class, 'download'])->name('download');
            });
        });

        // Tickets System
        Route::prefix('tickets')->group(function (): void {
            Route::name('tickets.')->group(function (): void {
                Route::get('/', [App\Http\Controllers\TicketController::class, 'index'])->name('index');
                Route::get('/create', [App\Http\Controllers\TicketController::class, 'create'])->name('create');
                Route::post('/', [App\Http\Controllers\TicketController::class, 'store'])->name('store');
                Route::get('/{ticket}', [App\Http\Controllers\TicketController::class, 'show'])->name('show');
                Route::delete('/{ticket}', [App\Http\Controllers\TicketController::class, 'destroy'])->name('destroy');
                Route::post('/{ticket}/assignee', [App\Http\Controllers\TicketAssigneeController::class, 'store'])->name('assignee.store');
                Route::delete('/{ticket}/assignee', [App\Http\Controllers\TicketAssigneeController::class, 'destroy'])->name('assignee.destroy');
                Route::post('/{ticket}/close', [App\Http\Controllers\TicketController::class, 'close'])->name('close');
                Route::post('/{ticket}/attachments/{attachment}/download', [App\Http\Controllers\TicketAttachmentController::class, 'download'])->name('attachment.download');
            })->scopeBindings();
        });

        // Missing System
        Route::prefix('missing')->group(function (): void {
            Route::name('missing.')->group(function (): void {
                Route::get('/', [App\Http\Controllers\MissingController::class, 'index'])->name('index');
            });
        });
    });

    /*
    |------------------------------------------
    | MediaHub (When Authorized)
    |------------------------------------------
    */
    Route::prefix('mediahub')->middleware(['auth', 'banned'])->group(function (): void {
        Route::get('/', [App\Http\Controllers\MediaHub\HomeController::class, 'index'])->name('mediahub.index');
        Route::get('/genres', [App\Http\Controllers\MediaHub\GenreController::class, 'index'])->name('mediahub.genres.index');
        Route::get('/networks', [App\Http\Controllers\MediaHub\NetworkController::class, 'index'])->name('mediahub.networks.index');
        Route::get('/companies', [App\Http\Controllers\MediaHub\CompanyController::class, 'index'])->name('mediahub.companies.index');
        Route::get('/persons', [App\Http\Controllers\MediaHub\PersonController::class, 'index'])->name('mediahub.persons.index');
        Route::get('/persons/{id}', [App\Http\Controllers\MediaHub\PersonController::class, 'show'])->name('mediahub.persons.show');
        Route::get('/collections', [App\Http\Controllers\MediaHub\CollectionController::class, 'index'])->name('mediahub.collections.index');
        Route::get('/collections/{id}', [App\Http\Controllers\MediaHub\CollectionController::class, 'show'])->name('mediahub.collections.show');
    });

    /*
    |---------------------------------------------------------------------------------
    | Forums Routes Group (When Authorized) (Alpha Ordered)
    |---------------------------------------------------------------------------------
    */
    Route::prefix('forums')->middleware(['auth', 'banned'])->group(function (): void {
        // Forum System
        Route::name('forums.')->group(function (): void {
            Route::get('/', [App\Http\Controllers\ForumController::class, 'index'])->name('index');
            Route::get('/{id}', [App\Http\Controllers\ForumController::class, 'show'])->where('id', '[0-9]+')->name('show');
        });

        // Forum Category System
        Route::prefix('categories')->name('forums.categories.')->group(function (): void {
            Route::get('/{id}', [App\Http\Controllers\ForumCategoryController::class, 'show'])->where('id', '[0-9]+')->name('show');
        });

        // Posts System
        Route::prefix('posts')->name('posts.')->group(function (): void {
            Route::get('/', [App\Http\Controllers\PostController::class, 'index'])->name('index');
            Route::post('/', [App\Http\Controllers\PostController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [App\Http\Controllers\PostController::class, 'edit'])->name('edit');
            Route::patch('/{id}', [App\Http\Controllers\PostController::class, 'update'])->name('update');
            Route::delete('/{id}', [App\Http\Controllers\PostController::class, 'destroy'])->name('destroy');
        });

        //Topics System
        Route::prefix('topics')->name('topics.')->group(function (): void {
            Route::get('/', [App\Http\Controllers\TopicController::class, 'index'])->name('index');
            Route::get('/forum/{id}/create', [App\Http\Controllers\TopicController::class, 'create'])->name('create');
            Route::post('/forum/{id}', [App\Http\Controllers\TopicController::class, 'store'])->name('store');
            Route::get('/{topicId}/posts/{postId}', [App\Http\Controllers\TopicController::class, 'permalink'])->name('permalink');
            Route::get('/{id}/latest', [App\Http\Controllers\TopicController::class, 'latestPermalink'])->name('latestPermalink');
            Route::get('/{id}', [App\Http\Controllers\TopicController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [App\Http\Controllers\TopicController::class, 'edit'])->name('edit');
            Route::patch('/{id}', [App\Http\Controllers\TopicController::class, 'update'])->name('update');
            Route::delete('/{id}', [App\Http\Controllers\TopicController::class, 'destroy'])->name('destroy')->middleware('modo');
            Route::post('/{id}/close', [App\Http\Controllers\TopicController::class, 'close'])->name('close')->middleware('modo');
            Route::post('/{id}/open', [App\Http\Controllers\TopicController::class, 'open'])->name('open')->middleware('modo');
            Route::post('/{id}/pin', [App\Http\Controllers\TopicController::class, 'pin'])->name('pin')->middleware('modo');
            Route::post('/{id}/unpin', [App\Http\Controllers\TopicController::class, 'unpin'])->name('unpin')->middleware('modo');
        });

        // Topic Label System
        Route::prefix('topics')->name('topics.')->middleware('modo')->group(function (): void {
            Route::post('/{id}/approve', [App\Http\Controllers\TopicLabelController::class, 'approve'])->name('approve');
            Route::post('/{id}/deny', [App\Http\Controllers\TopicLabelController::class, 'deny'])->name('deny');
            Route::post('/{id}/solve', [App\Http\Controllers\TopicLabelController::class, 'solve'])->name('solve');
            Route::post('/{id}/invalid', [App\Http\Controllers\TopicLabelController::class, 'invalid'])->name('invalid');
            Route::post('/{id}/bug', [App\Http\Controllers\TopicLabelController::class, 'bug'])->name('bug');
            Route::post('/{id}/suggest', [App\Http\Controllers\TopicLabelController::class, 'suggest'])->name('suggest');
            Route::post('/{id}/implement', [App\Http\Controllers\TopicLabelController::class, 'implement'])->name('implement');
        });

        // Subscription System
        Route::prefix('subscriptions')->name('subscriptions.')->group(function (): void {
            Route::get('/', [App\Http\Controllers\SubscriptionController::class, 'index'])->name('index');
            Route::post('/', [App\Http\Controllers\SubscriptionController::class, 'store'])->name('store');
            Route::post('/{id}', [App\Http\Controllers\SubscriptionController::class, 'destroy'])->name('destroy');
        });
    });

    /*
    |-------------------------------------------------------------------------------
    | User Private Routes Group (When authorized) (Alpha ordered)
    |-------------------------------------------------------------------------------
    */
    Route::prefix('users/{user:username}')->name('users.')->middleware(['auth', 'banned'])->scopeBindings()->group(function (): void {
        Route::get('/', [App\Http\Controllers\User\UserController::class, 'show'])->name('show')->withTrashed();
        Route::get('/edit', [App\Http\Controllers\User\UserController::class, 'edit'])->name('edit');
        Route::patch('/', [App\Http\Controllers\User\UserController::class, 'update'])->name('update');
        Route::post('/accept-rules', [App\Http\Controllers\User\UserController::class, 'acceptRules'])->name('accept.rules');

        // Achievements
        Route::prefix('achievements')->name('achievements.')->group(function (): void {
            Route::get('/', [App\Http\Controllers\User\AchievementsController::class, 'index'])->name('index');
        });

        // Earnings
        Route::prefix('earnings')->name('earnings.')->group(function (): void {
            Route::get('/', [App\Http\Controllers\User\EarningController::class, 'index'])->name('index');
        });

        // History
        Route::prefix('torrents')->name('history.')->group(function (): void {
            Route::get('/', [App\Http\Controllers\User\HistoryController::class, 'index'])->name('index');
        });

        // Followers
        Route::prefix('followers')->name('followers.')->group(function (): void {
            Route::get('/', [App\Http\Controllers\User\FollowController::class, 'index'])->name('index');
            Route::post('/', [App\Http\Controllers\User\FollowController::class, 'store'])->name('store');
            Route::delete('/', [App\Http\Controllers\User\FollowController::class, 'destroy'])->name('destroy');
        });

        // Following
        Route::prefix('following')->name('following.')->group(function (): void {
            Route::get('/', [App\Http\Controllers\User\FollowingController::class, 'index'])->name('index');
        });

        // Gifts
        Route::prefix('gifts')->name('gifts.')->group(function (): void {
            Route::get('/', [App\Http\Controllers\User\GiftController::class, 'index'])->name('index');
            Route::get('/create', [App\Http\Controllers\User\GiftController::class, 'create'])->name('create');
            Route::post('/', [App\Http\Controllers\User\GiftController::class, 'store'])->name('store');
        });

        // General settings
        Route::prefix('general-settings')->name('general_settings.')->group(function (): void {
            Route::get('/edit', [App\Http\Controllers\User\GeneralSettingController::class, 'edit'])->name('edit');
            Route::patch('/', [App\Http\Controllers\User\GeneralSettingController::class, 'update'])->name('update');
        });

        // Inbox
        Route::prefix('inbox')->name('received_messages.')->group(function (): void {
            Route::get('/', [App\Http\Controllers\User\ReceivedPrivateMessageController::class, 'index'])->name('index');
            Route::get('/{receivedPrivateMessage}', [App\Http\Controllers\User\ReceivedPrivateMessageController::class, 'show'])->name('show');
            Route::patch('/{receivedPrivateMessage}', [App\Http\Controllers\User\ReceivedPrivateMessageController::class, 'update'])->name('update');
            Route::delete('/{receivedPrivateMessage}', [App\Http\Controllers\User\ReceivedPrivateMessageController::class, 'destroy'])->name('destroy');
            Route::patch('/', [App\Http\Controllers\User\ReceivedPrivateMessageController::class, 'massUpdate'])->name('mass_update');
            Route::delete('/', [App\Http\Controllers\User\ReceivedPrivateMessageController::class, 'massDestroy'])->name('mass_destroy');
        });

        // Outbox
        Route::prefix('outbox')->name('sent_messages.')->group(function (): void {
            Route::get('/', [App\Http\Controllers\User\SentPrivateMessageController::class, 'index'])->name('index');
            Route::get('/create', [App\Http\Controllers\User\SentPrivateMessageController::class, 'create'])->name('create');
            Route::get('/{sentPrivateMessage}', [App\Http\Controllers\User\SentPrivateMessageController::class, 'show'])->name('show');
            Route::post('/', [App\Http\Controllers\User\SentPrivateMessageController::class, 'store'])->name('store');
            Route::patch('/{sentPrivateMessage}', [App\Http\Controllers\User\SentPrivateMessageController::class, 'update'])->name('update');
        });

        // Invites
        Route::prefix('invites')->name('invites.')->group(function (): void {
            Route::get('/create', [App\Http\Controllers\User\InviteController::class, 'create'])->name('create');
            Route::post('/store', [App\Http\Controllers\User\InviteController::class, 'store'])->name('store');
            Route::post('/{sentInvite}/send', [App\Http\Controllers\User\InviteController::class, 'send'])->name('send');
            Route::delete('/{sentInvite}', [App\Http\Controllers\User\InviteController::class, 'destroy'])->name('destroy')->withTrashed();
            Route::get('/', [App\Http\Controllers\User\InviteController::class, 'index'])->name('index')->withTrashed();
        });

        // Notifications
        Route::prefix('notifications')->name('notifications.')->group(function (): void {
            Route::get('/', [App\Http\Controllers\User\NotificationController::class, 'index'])->name('index');
            Route::patch('/mass-update', [App\Http\Controllers\User\NotificationController::class, 'massUpdate'])->name('mass_update');
            Route::patch('/{notification}', [App\Http\Controllers\User\NotificationController::class, 'update'])->name('update');
            Route::delete('/mass-destroy', [App\Http\Controllers\User\NotificationController::class, 'massDestroy'])->name('mass_destroy');
            Route::delete('/{notification}', [App\Http\Controllers\User\NotificationController::class, 'destroy'])->name('destroy');
            Route::get('/{notification}', [App\Http\Controllers\User\NotificationController::class, 'show'])->name('show');
        });

        // Privacy settings
        Route::prefix('privacy-settings')->name('privacy_settings.')->group(function (): void {
            Route::get('/edit', [App\Http\Controllers\User\PrivacySettingController::class, 'edit'])->name('edit');
            Route::patch('/', [App\Http\Controllers\User\PrivacySettingController::class, 'update'])->name('update');
        });

        // Notification settings
        Route::prefix('notification-settings')->name('notification_settings.')->group(function (): void {
            Route::get('/edit', [App\Http\Controllers\User\NotificationSettingController::class, 'edit'])->name('edit');
            Route::patch('/', [App\Http\Controllers\User\NotificationSettingController::class, 'update'])->name('update');
        });

        // Peers
        Route::prefix('active')->name('peers.')->group(function (): void {
            Route::get('/', [App\Http\Controllers\User\PeerController::class, 'index'])->name('index');
            Route::delete('/', [App\Http\Controllers\User\PeerController::class, 'massDestroy'])->name('mass_destroy');
        });

        // Posts
        Route::prefix('posts')->name('posts.')->group(function (): void {
            Route::get('/', [App\Http\Controllers\User\PostController::class, 'index'])->name('index');
        });

        // Resurrections
        Route::prefix('resurrections')->name('resurrections.')->group(function (): void {
            Route::get('/', [App\Http\Controllers\User\ResurrectionController::class, 'index'])->name('index');
            Route::post('/', [App\Http\Controllers\User\ResurrectionController::class, 'store'])->name('store');
            Route::delete('/{resurrection}', [App\Http\Controllers\User\ResurrectionController::class, 'destroy'])->name('destroy');
        })->scopeBindings();

        // Seedboxes
        Route::prefix('seedboxes')->name('seedboxes.')->group(function (): void {
            Route::get('/', [App\Http\Controllers\User\SeedboxController::class, 'index'])->name('index');
            Route::post('/', [App\Http\Controllers\User\SeedboxController::class, 'store'])->name('store');
            Route::delete('/{seedbox}', [App\Http\Controllers\User\SeedboxController::class, 'destroy'])->name('destroy');
        });

        // Two-Factor Authentication
        Route::prefix('two-factor-auth')->name('two_factor_auth.')->group(function (): void {
            Route::get('/edit', [App\Http\Controllers\User\TwoFactorAuthController::class, 'edit'])->name('edit');
        });

        // Email
        Route::prefix('email')->name('email.')->group(function (): void {
            Route::get('/edit', [App\Http\Controllers\User\EmailController::class, 'edit'])->name('edit');
            Route::patch('/', [App\Http\Controllers\User\EmailController::class, 'update'])->name('update');
        });

        // Password
        Route::prefix('password')->name('password.')->group(function (): void {
            Route::get('/edit', [App\Http\Controllers\User\PasswordController::class, 'edit'])->name('edit');
            Route::patch('/', [App\Http\Controllers\User\PasswordController::class, 'update'])->name('update');
        });

        // Passkey
        Route::prefix('passkeys')->name('passkeys.')->group(function (): void {
            Route::get('/', [App\Http\Controllers\User\PasskeyController::class, 'index'])->name('index');
            Route::patch('/', [App\Http\Controllers\User\PasskeyController::class, 'update'])->name('update');
        });

        // Rsskey
        Route::prefix('rsskeys')->name('rsskeys.')->group(function (): void {
            Route::get('/', [App\Http\Controllers\User\RsskeyController::class, 'index'])->name('index');
            Route::patch('/', [App\Http\Controllers\User\RsskeyController::class, 'update'])->name('update');
        });

        // Apikey
        Route::prefix('apikeys')->name('apikeys.')->group(function (): void {
            Route::get('/', [App\Http\Controllers\User\ApikeyController::class, 'index'])->name('index');
            Route::patch('/', [App\Http\Controllers\User\ApikeyController::class, 'update'])->name('update');
        });

        // Tips
        Route::prefix('tips')->name('tips.')->group(function (): void {
            Route::get('/', [App\Http\Controllers\User\TipController::class, 'index'])->name('index');
            Route::post('/', [App\Http\Controllers\User\TipController::class, 'store'])->name('store');
        });

        // Topics
        Route::prefix('topics')->name('topics.')->group(function (): void {
            Route::get('/', [App\Http\Controllers\User\TopicController::class, 'index'])->name('index');
        });

        // Torrent Zip
        Route::prefix('torrent-zip')->name('torrent_zip.')->group(function (): void {
            Route::get('/', [App\Http\Controllers\User\TorrentZipController::class, 'show'])->name('show');
        });

        // Torrents
        Route::prefix('uploads')->name('torrents.')->group(function (): void {
            Route::get('/', [App\Http\Controllers\User\TorrentController::class, 'index'])->name('index');
        });

        // Transactions
        Route::prefix('transactions')->name('transactions.')->group(function (): void {
            Route::get('/create', [App\Http\Controllers\User\TransactionController::class, 'create'])->name('create');
            Route::post('/', [App\Http\Controllers\User\TransactionController::class, 'store'])->name('store');
        });

        // Warnings
        Route::prefix('warnings')->name('warnings.')->group(function (): void {
            Route::post('/', [App\Http\Controllers\User\WarningController::class, 'store'])->name('store');
            Route::delete('/{warning}', [App\Http\Controllers\User\WarningController::class, 'destroy'])->name('destroy');
            Route::delete('/mass-delete', [App\Http\Controllers\User\WarningController::class, 'massDestroy'])->name('mass_destroy');
            Route::patch('/{warning}', [App\Http\Controllers\User\WarningController::class, 'update'])->name('update')->withTrashed();
        });

        // Wishlist
        Route::prefix('wishes')->name('wishes.')->group(function (): void {
            Route::get('/', [App\Http\Controllers\User\WishController::class, 'index'])->name('index');
            Route::post('/', [App\Http\Controllers\User\WishController::class, 'store'])->name('store');
            Route::delete('/{wish}', [App\Http\Controllers\User\WishController::class, 'destroy'])->name('destroy');
        });
    });

    /*
    |---------------------------------------------------------------------------------
    | Staff Dashboard Routes Group (When Authorized And A Staff Group) (Alpha Ordered)
    |---------------------------------------------------------------------------------
    */
    Route::prefix('dashboard')->middleware(['auth', 'modo', 'banned'])->name('staff.')->group(function (): void {
        // Staff Dashboard
        Route::name('dashboard.')->group(function (): void {
            Route::get('/', [App\Http\Controllers\Staff\HomeController::class, 'index'])->name('index');
        });

        // Announces
        Route::prefix('announces')->group(function (): void {
            Route::name('announces.')->group(function (): void {
                Route::get('/', [App\Http\Controllers\Staff\AnnounceController::class, 'index'])->name('index');
            });
        });

        // Apikeys
        Route::prefix('apikeys')->group(function (): void {
            Route::name('apikeys.')->group(function (): void {
                Route::get('/', [App\Http\Controllers\Staff\ApikeyController::class, 'index'])->name('index');
            });
        });

        // Articles System
        Route::prefix('articles')->group(function (): void {
            Route::name('articles.')->group(function (): void {
                Route::get('/', [App\Http\Controllers\Staff\ArticleController::class, 'index'])->name('index');
                Route::get('/create', [App\Http\Controllers\Staff\ArticleController::class, 'create'])->name('create');
                Route::post('/store', [App\Http\Controllers\Staff\ArticleController::class, 'store'])->name('store');
                Route::get('/{article}', [App\Http\Controllers\Staff\ArticleController::class, 'edit'])->name('edit');
                Route::post('/{article}', [App\Http\Controllers\Staff\ArticleController::class, 'update'])->name('update');
                Route::delete('/{article}', [App\Http\Controllers\Staff\ArticleController::class, 'destroy'])->name('destroy');
            });
        });

        // Applications System
        Route::prefix('applications')->group(function (): void {
            Route::name('applications.')->group(function (): void {
                Route::get('/', [App\Http\Controllers\Staff\ApplicationController::class, 'index'])->name('index');
                Route::get('/{id}', [App\Http\Controllers\Staff\ApplicationController::class, 'show'])->where('id', '[0-9]+')->name('show');
                Route::post('/{id}/approve', [App\Http\Controllers\Staff\ApplicationController::class, 'approve'])->name('approve');
                Route::post('/{id}/reject', [App\Http\Controllers\Staff\ApplicationController::class, 'reject'])->name('reject');
            });
        });

        // Audit Log
        Route::prefix('audits')->group(function (): void {
            Route::name('audits.')->group(function (): void {
                Route::get('/', [App\Http\Controllers\Staff\AuditController::class, 'index'])->name('index');
                Route::delete('/{audit}', [App\Http\Controllers\Staff\AuditController::class, 'destroy'])->name('destroy');
            });
        });

        // Authentications Log
        Route::prefix('authentications')->group(function (): void {
            Route::name('authentications.')->group(function (): void {
                Route::get('/', [App\Http\Controllers\Staff\AuthenticationController::class, 'index'])->name('index');
            });
        });

        // Backup System
        Route::prefix('backups')->middleware('owner')->group(function (): void {
            Route::name('backups.')->group(function (): void {
                Route::get('/', [App\Http\Controllers\Staff\BackupController::class, 'index'])->name('index');
            });
        });

        // Ban System
        Route::prefix('bans')->group(function (): void {
            Route::name('bans.')->group(function (): void {
                Route::get('/', [App\Http\Controllers\Staff\BanController::class, 'index'])->name('index');
                Route::post('/', [App\Http\Controllers\Staff\BanController::class, 'store'])->name('store');
                Route::patch('/{ban}', [App\Http\Controllers\Staff\BanController::class, 'update'])->name('update');
            });
        });

        // Unban System
        Route::prefix('unbans')->group(function (): void {
            Route::name('unbans.')->group(function (): void {
                Route::post('/', [App\Http\Controllers\Staff\UnbanController::class, 'store'])->name('store');
            });
        });

        // Blacklist System
        Route::prefix('blacklisted-clients')->group(function (): void {
            Route::name('blacklisted_clients.')->group(function (): void {
                Route::get('/', [App\Http\Controllers\Staff\BlacklistClientController::class, 'index'])->name('index');
                Route::get('/create', [App\Http\Controllers\Staff\BlacklistClientController::class, 'create'])->name('create');
                Route::post('/', [App\Http\Controllers\Staff\BlacklistClientController::class, 'store'])->name('store');
                Route::get('/{blacklistClient}/edit', [App\Http\Controllers\Staff\BlacklistClientController::class, 'edit'])->name('edit');
                Route::patch('/{blacklistClient}', [App\Http\Controllers\Staff\BlacklistClientController::class, 'update'])->name('update');
                Route::delete('/{blacklistClient}', [App\Http\Controllers\Staff\BlacklistClientController::class, 'destroy'])->name('destroy');
            });
        });

        // Block Ip System
        Route::prefix('blocked-ips')->group(function (): void {
            Route::name('blocked_ips.')->group(function (): void {
                Route::get('/', [App\Http\Controllers\Staff\BlockedIpController::class, 'index'])->name('index');
            });
        });

        // Bon Exchanges
        Route::prefix('bon-exchanges')->group(function (): void {
            Route::name('bon_exchanges.')->group(function (): void {
                Route::get('/', [App\Http\Controllers\Staff\BonExchangeController::class, 'index'])->name('index');
                Route::get('/create', [App\Http\Controllers\Staff\BonExchangeController::class, 'create'])->name('create');
                Route::post('/', [App\Http\Controllers\Staff\BonExchangeController::class, 'store'])->name('store');
                Route::get('/{bonExchange}/edit', [App\Http\Controllers\Staff\BonExchangeController::class, 'edit'])->name('edit');
                Route::patch('/{bonExchange}', [App\Http\Controllers\Staff\BonExchangeController::class, 'update'])->name('update');
                Route::delete('/{bonExchange}', [App\Http\Controllers\Staff\BonExchangeController::class, 'destroy'])->name('destroy');
            });
        });

        // Categories System
        Route::prefix('categories')->group(function (): void {
            Route::name('categories.')->group(function (): void {
                Route::get('/', [App\Http\Controllers\Staff\CategoryController::class, 'index'])->name('index');
                Route::get('/create', [App\Http\Controllers\Staff\CategoryController::class, 'create'])->name('create');
                Route::post('/', [App\Http\Controllers\Staff\CategoryController::class, 'store'])->name('store');
                Route::get('/{category}/edit', [App\Http\Controllers\Staff\CategoryController::class, 'edit'])->name('edit');
                Route::patch('/{category}', [App\Http\Controllers\Staff\CategoryController::class, 'update'])->name('update');
                Route::delete('/{category}', [App\Http\Controllers\Staff\CategoryController::class, 'destroy'])->name('destroy');
            });
        });

        // Chat Bots System
        Route::prefix('bots')->group(function (): void {
            Route::name('bots.')->group(function (): void {
                Route::get('/', [App\Http\Controllers\Staff\ChatBotController::class, 'index'])->name('index');
                Route::get('/{bot}/edit', [App\Http\Controllers\Staff\ChatBotController::class, 'edit'])->name('edit');
                Route::patch('/{bot}', [App\Http\Controllers\Staff\ChatBotController::class, 'update'])->name('update');
                Route::delete('/{bot}', [App\Http\Controllers\Staff\ChatBotController::class, 'destroy'])->name('destroy');
                Route::post('/{bot}/disable', [App\Http\Controllers\Staff\ChatBotController::class, 'disable'])->name('disable');
                Route::post('/{bot}/enable', [App\Http\Controllers\Staff\ChatBotController::class, 'enable'])->name('enable');
            });
        });

        // Chat Rooms System
        Route::prefix('chatrooms')->group(function (): void {
            Route::name('chatrooms.')->group(function (): void {
                Route::get('/', [App\Http\Controllers\Staff\ChatRoomController::class, 'index'])->name('index');
                Route::get('/create', [App\Http\Controllers\Staff\ChatRoomController::class, 'create'])->name('create');
                Route::post('/', [App\Http\Controllers\Staff\ChatRoomController::class, 'store'])->name('store');
                Route::get('/{chatroom}/edit', [App\Http\Controllers\Staff\ChatRoomController::class, 'edit'])->name('edit');
                Route::post('/{chatroom}', [App\Http\Controllers\Staff\ChatRoomController::class, 'update'])->name('update');
                Route::delete('/{chatroom}', [App\Http\Controllers\Staff\ChatRoomController::class, 'destroy'])->name('destroy');
            });
        });

        // Chat Statuses System
        Route::prefix('chat-statuses')->group(function (): void {
            Route::name('statuses.')->group(function (): void {
                Route::get('/', [App\Http\Controllers\Staff\ChatStatusController::class, 'index'])->name('index');
                Route::get('/create', [App\Http\Controllers\Staff\ChatStatusController::class, 'create'])->name('create');
                Route::post('/', [App\Http\Controllers\Staff\ChatStatusController::class, 'store'])->name('store');
                Route::get('/{chatStatus}/edit', [App\Http\Controllers\Staff\ChatStatusController::class, 'edit'])->name('edit');
                Route::post('/{chatStatus}', [App\Http\Controllers\Staff\ChatStatusController::class, 'update'])->name('update');
                Route::delete('/{chatStatus}', [App\Http\Controllers\Staff\ChatStatusController::class, 'destroy'])->name('destroy');
            });
        });

        // Cheated Torrents
        Route::prefix('cheated-torrents')->group(function (): void {
            Route::name('cheated_torrents.')->group(function (): void {
                Route::get('/', [App\Http\Controllers\Staff\CheatedTorrentController::class, 'index'])->name('index');
                Route::delete('/{cheatedTorrent}', [App\Http\Controllers\Staff\CheatedTorrentController::class, 'destroy'])->name('destroy');
                Route::delete('/', [App\Http\Controllers\Staff\CheatedTorrentController::class, 'massDestroy'])->name('massDestroy');
            });
        });

        // Cheaters
        Route::prefix('cheaters')->group(function (): void {
            Route::name('cheaters.')->group(function (): void {
                Route::get('/ghost-leechers', [App\Http\Controllers\Staff\CheaterController::class, 'index'])->name('index');
            });
        });

        // Codebase Version Check
        Route::prefix('UNIT3D')->group(function (): void {
            Route::get('/', [App\Http\Controllers\Staff\VersionController::class, 'checkVersion']);
        });

        // Commands
        Route::prefix('commands')->middleware('owner')->group(function (): void {
            Route::get('/', [App\Http\Controllers\Staff\CommandController::class, 'index'])->name('commands.index');
            Route::post('/maintance-enable', [App\Http\Controllers\Staff\CommandController::class, 'maintanceEnable']);
            Route::post('/maintance-disable', [App\Http\Controllers\Staff\CommandController::class, 'maintanceDisable']);
            Route::post('/clear-cache', [App\Http\Controllers\Staff\CommandController::class, 'clearCache']);
            Route::post('/clear-view-cache', [App\Http\Controllers\Staff\CommandController::class, 'clearView']);
            Route::post('/clear-route-cache', [App\Http\Controllers\Staff\CommandController::class, 'clearRoute']);
            Route::post('/clear-config-cache', [App\Http\Controllers\Staff\CommandController::class, 'clearConfig']);
            Route::post('/clear-all-cache', [App\Http\Controllers\Staff\CommandController::class, 'clearAllCache']);
            Route::post('/set-all-cache', [App\Http\Controllers\Staff\CommandController::class, 'setAllCache']);
            Route::post('/test-email', [App\Http\Controllers\Staff\CommandController::class, 'testEmail']);
        });

        // Distributors
        Route::prefix('distributors')->group(function (): void {
            Route::name('distributors.')->group(function (): void {
                Route::get('/', [App\Http\Controllers\Staff\DistributorController::class, 'index'])->name('index');
                Route::get('/create', [App\Http\Controllers\Staff\DistributorController::class, 'create'])->name('create');
                Route::post('/', [App\Http\Controllers\Staff\DistributorController::class, 'store'])->name('store');
                Route::get('/{distributor}/edit', [App\Http\Controllers\Staff\DistributorController::class, 'edit'])->name('edit');
                Route::patch('/{distributor}', [App\Http\Controllers\Staff\DistributorController::class, 'update'])->name('update');
                Route::get('/{distributor}/delete', [App\Http\Controllers\Staff\DistributorController::class, 'delete'])->name('delete');
                Route::delete('/{distributor}', [App\Http\Controllers\Staff\DistributorController::class, 'destroy'])->name('destroy');
            });
        });

        // Flush System
        Route::prefix('flush')->group(function (): void {
            Route::name('flush.')->group(function (): void {
                Route::post('/peers', [App\Http\Controllers\Staff\FlushController::class, 'peers'])->name('peers');
                Route::post('/chat', [App\Http\Controllers\Staff\FlushController::class, 'chat'])->name('chat');
            });
        });

        // Forums System
        Route::prefix('forums')->middleware('admin')->group(function (): void {
            Route::name('forums.')->group(function (): void {
                Route::get('/', [App\Http\Controllers\Staff\ForumController::class, 'index'])->name('index');
                Route::get('/create', [App\Http\Controllers\Staff\ForumController::class, 'create'])->name('create');
                Route::post('/', [App\Http\Controllers\Staff\ForumController::class, 'store'])->name('store');
                Route::get('/{forum}/edit', [App\Http\Controllers\Staff\ForumController::class, 'edit'])->name('edit');
                Route::patch('/{forum}', [App\Http\Controllers\Staff\ForumController::class, 'update'])->name('update');
                Route::delete('/{forum}', [App\Http\Controllers\Staff\ForumController::class, 'destroy'])->name('destroy');
            });
        });

        // Groups System
        Route::prefix('groups')->middleware('admin')->group(function (): void {
            Route::name('groups.')->group(function (): void {
                Route::get('/', [App\Http\Controllers\Staff\GroupController::class, 'index'])->name('index');
                Route::get('/create', [App\Http\Controllers\Staff\GroupController::class, 'create'])->name('create');
                Route::post('/', [App\Http\Controllers\Staff\GroupController::class, 'store'])->name('store');
                Route::get('/{group}/edit', [App\Http\Controllers\Staff\GroupController::class, 'edit'])->name('edit');
                Route::patch('/{group}', [App\Http\Controllers\Staff\GroupController::class, 'update'])->name('update');
            });
        });

        // Gifts Log
        Route::prefix('gifts')->group(function (): void {
            Route::name('gifts.')->group(function (): void {
                Route::get('/', [App\Http\Controllers\Staff\GiftController::class, 'index'])->name('index');
            });
        });

        // History
        Route::prefix('histories')->group(function (): void {
            Route::name('histories.')->group(function (): void {
                Route::get('/', [App\Http\Controllers\Staff\HistoryController::class, 'index'])->name('index');
            });
        });

        // Invites Log
        Route::prefix('invites')->group(function (): void {
            Route::name('invites.')->group(function (): void {
                Route::get('/', [App\Http\Controllers\Staff\InviteController::class, 'index'])->name('index');
            });
        });

        // Laravel Log Viewer
        Route::get('/laravel-log', App\Http\Livewire\LaravelLogViewer::class)->middleware('owner')->name('laravellog.index');

        // Mass Actions
        Route::prefix('mass-actions')->group(function (): void {
            Route::get('/validate-users', [App\Http\Controllers\Staff\MassActionController::class, 'update'])->name('mass-actions.validate');
            Route::get('/mass-pm', [App\Http\Controllers\Staff\MassActionController::class, 'create'])->name('mass-pm.create');
            Route::post('/mass-pm/store', [App\Http\Controllers\Staff\MassActionController::class, 'store'])->name('mass-pm.store');
        });

        // Media Lanuages (Languages Used To Populate Language Dropdowns For Subtitles / Audios / Etc.)
        Route::prefix('media-languages')->group(function (): void {
            Route::name('media_languages.')->group(function (): void {
                Route::get('/', [App\Http\Controllers\Staff\MediaLanguageController::class, 'index'])->name('index');
                Route::get('/create', [App\Http\Controllers\Staff\MediaLanguageController::class, 'create'])->name('create');
                Route::post('/', [App\Http\Controllers\Staff\MediaLanguageController::class, 'store'])->name('store');
                Route::get('/{mediaLanguage}/edit', [App\Http\Controllers\Staff\MediaLanguageController::class, 'edit'])->name('edit');
                Route::patch('/{mediaLanguage}', [App\Http\Controllers\Staff\MediaLanguageController::class, 'update'])->name('update');
                Route::delete('/{mediaLanguage}', [App\Http\Controllers\Staff\MediaLanguageController::class, 'destroy'])->name('destroy');
            });
        });

        // Moderation System
        Route::prefix('moderation')->group(function (): void {
            Route::name('moderation.')->group(function (): void {
                Route::get('/', [App\Http\Controllers\Staff\ModerationController::class, 'index'])->name('index');
                Route::post('/{id}/update', [App\Http\Controllers\Staff\ModerationController::class, 'update'])->name('update');
            });
        });

        //Pages System
        Route::prefix('pages')->group(function (): void {
            Route::name('pages.')->group(function (): void {
                Route::get('/', [App\Http\Controllers\Staff\PageController::class, 'index'])->name('index');
                Route::get('/create', [App\Http\Controllers\Staff\PageController::class, 'create'])->name('create');
                Route::post('/', [App\Http\Controllers\Staff\PageController::class, 'store'])->name('store');
                Route::get('/{page}/edit', [App\Http\Controllers\Staff\PageController::class, 'edit'])->name('edit');
                Route::patch('/{page}', [App\Http\Controllers\Staff\PageController::class, 'update'])->name('update');
                Route::delete('/{page}', [App\Http\Controllers\Staff\PageController::class, 'destroy'])->name('destroy');
            });
        });

        // Passkeys
        Route::prefix('passkeys')->group(function (): void {
            Route::name('passkeys.')->group(function (): void {
                Route::get('/', [App\Http\Controllers\Staff\PasskeyController::class, 'index'])->name('index');
            });
        });

        // Peers
        Route::prefix('peers')->group(function (): void {
            Route::name('peers.')->group(function (): void {
                Route::get('/', [App\Http\Controllers\Staff\PeerController::class, 'index'])->name('index');
            });
        });

        // Polls System
        Route::prefix('polls')->group(function (): void {
            Route::name('polls.')->group(function (): void {
                Route::get('/', [App\Http\Controllers\Staff\PollController::class, 'index'])->name('index');
                Route::get('/create', [App\Http\Controllers\Staff\PollController::class, 'create'])->name('create');
                Route::post('/', [App\Http\Controllers\Staff\PollController::class, 'store'])->name('store');
                Route::get('/{poll}', [App\Http\Controllers\Staff\PollController::class, 'show'])->name('show');
                Route::get('/{poll}/edit', [App\Http\Controllers\Staff\PollController::class, 'edit'])->name('edit');
                Route::patch('/{poll}', [App\Http\Controllers\Staff\PollController::class, 'update'])->name('update');
                Route::delete('/{poll}', [App\Http\Controllers\Staff\PollController::class, 'destroy'])->name('destroy');
            });
        });

        // Regions
        Route::prefix('regions')->group(function (): void {
            Route::name('regions.')->group(function (): void {
                Route::get('/', [App\Http\Controllers\Staff\RegionController::class, 'index'])->name('index');
                Route::get('/create', [App\Http\Controllers\Staff\RegionController::class, 'create'])->name('create');
                Route::post('/', [App\Http\Controllers\Staff\RegionController::class, 'store'])->name('store');
                Route::get('/{region}/edit', [App\Http\Controllers\Staff\RegionController::class, 'edit'])->name('edit');
                Route::patch('/{region}', [App\Http\Controllers\Staff\RegionController::class, 'update'])->name('update');
                Route::delete('/{region}', [App\Http\Controllers\Staff\RegionController::class, 'destroy'])->name('destroy');
            });
        });

        // Registered Seedboxes
        Route::prefix('seedboxes')->group(function (): void {
            Route::name('seedboxes.')->group(function (): void {
                Route::get('/', [App\Http\Controllers\Staff\SeedboxController::class, 'index'])->name('index');
                Route::delete('/{seedbox}', [App\Http\Controllers\Staff\SeedboxController::class, 'destroy'])->name('destroy');
            });
        });

        // Reports
        Route::prefix('reports')->group(function (): void {
            Route::name('reports.')->group(function (): void {
                Route::get('/', [App\Http\Controllers\Staff\ReportController::class, 'index'])->name('index');
                Route::get('/{report}', [App\Http\Controllers\Staff\ReportController::class, 'show'])->name('show');
                Route::patch('/{report}', [App\Http\Controllers\Staff\ReportController::class, 'update'])->name('update');
            });
        });

        // Resolutions
        Route::prefix('resolutions')->group(function (): void {
            Route::name('resolutions.')->group(function (): void {
                Route::get('/', [App\Http\Controllers\Staff\ResolutionController::class, 'index'])->name('index');
                Route::get('/create', [App\Http\Controllers\Staff\ResolutionController::class, 'create'])->name('create');
                Route::post('/', [App\Http\Controllers\Staff\ResolutionController::class, 'store'])->name('store');
                Route::get('/{resolution}/edit', [App\Http\Controllers\Staff\ResolutionController::class, 'edit'])->name('edit');
                Route::patch('/{resolution}', [App\Http\Controllers\Staff\ResolutionController::class, 'update'])->name('update');
                Route::delete('/{resolution}', [App\Http\Controllers\Staff\ResolutionController::class, 'destroy'])->name('destroy');
            });
        });

        // RSS System
        Route::prefix('rss')->group(function (): void {
            Route::name('rss.')->group(function (): void {
                Route::get('/', [App\Http\Controllers\Staff\RssController::class, 'index'])->name('index');
                Route::get('/create', [App\Http\Controllers\Staff\RssController::class, 'create'])->name('create');
                Route::post('/', [App\Http\Controllers\Staff\RssController::class, 'store'])->name('store');
                Route::get('/{rss}/edit', [App\Http\Controllers\Staff\RssController::class, 'edit'])->name('edit');
                Route::patch('/{rss}', [App\Http\Controllers\Staff\RssController::class, 'update'])->name('update');
                Route::delete('/{rss}', [App\Http\Controllers\Staff\RssController::class, 'destroy'])->name('destroy');
            });
        });

        // RSS Keys
        Route::prefix('rsskeys')->group(function (): void {
            Route::name('rsskeys.')->group(function (): void {
                Route::get('/', [App\Http\Controllers\Staff\RsskeyController::class, 'index'])->name('index');
            });
        });

        // Types
        Route::prefix('types')->group(function (): void {
            Route::name('types.')->group(function (): void {
                Route::get('/', [App\Http\Controllers\Staff\TypeController::class, 'index'])->name('index');
                Route::get('/create', [App\Http\Controllers\Staff\TypeController::class, 'create'])->name('create');
                Route::post('/', [App\Http\Controllers\Staff\TypeController::class, 'store'])->name('store');
                Route::get('/{type}/edit', [App\Http\Controllers\Staff\TypeController::class, 'edit'])->name('edit');
                Route::patch('/{type}', [App\Http\Controllers\Staff\TypeController::class, 'update'])->name('update');
                Route::delete('/{type}', [App\Http\Controllers\Staff\TypeController::class, 'destroy'])->name('destroy');
            });
        });

        // User Staff Notes
        Route::prefix('notes')->group(function (): void {
            Route::name('notes.')->group(function (): void {
                Route::get('/', [App\Http\Controllers\Staff\NoteController::class, 'index'])->name('index');
            });
        });

        // User Tools TODO: Leaving since we will be refactoring users and roles
        Route::prefix('users')->name('users.')->group(function (): void {
            Route::get('/', [App\Http\Controllers\Staff\UserController::class, 'index'])->name('index');
            Route::patch('/{user:username}', [App\Http\Controllers\Staff\UserController::class, 'update'])->name('update')->withTrashed();
            Route::get('/{user:username}/edit', [App\Http\Controllers\Staff\UserController::class, 'edit'])->name('edit');
            Route::patch('/{user:username}/permissions', [App\Http\Controllers\Staff\UserController::class, 'permissions'])->name('update_permissions');
            Route::delete('/{user:username}', [App\Http\Controllers\Staff\UserController::class, 'destroy'])->name('destroy');
        });

        // Warnings Log
        Route::prefix('warnings')->group(function (): void {
            Route::name('warnings.')->group(function (): void {
                Route::get('/', [App\Http\Controllers\Staff\WarningController::class, 'index'])->name('index');
            });
        });

        // Internals System
        Route::prefix('internals')->group(function (): void {
            Route::name('internals.')->group(function (): void {
                Route::get('/', [App\Http\Controllers\Staff\InternalController::class, 'index'])->name('index');
                Route::get('/{internal}/edit', [App\Http\Controllers\Staff\InternalController::class, 'edit'])->name('edit');
                Route::patch('/{internal}', [App\Http\Controllers\Staff\InternalController::class, 'update'])->name('update');
                Route::get('/create', [App\Http\Controllers\Staff\InternalController::class, 'create'])->name('create');
                Route::post('/', [App\Http\Controllers\Staff\InternalController::class, 'store'])->name('store');
                Route::delete('/{internal}', [App\Http\Controllers\Staff\InternalController::class, 'destroy'])->name('destroy');
            });
        });

        // Watchlist
        Route::prefix('watchlist')->group(function (): void {
            Route::name('watchlist.')->group(function (): void {
                Route::get('/', [App\Http\Controllers\Staff\WatchlistController::class, 'index'])->name('index');
                Route::post('/', [App\Http\Controllers\Staff\WatchlistController::class, 'store'])->name('store');
                Route::delete('/{watchlist}', [App\Http\Controllers\Staff\WatchlistController::class, 'destroy'])->name('destroy');
            });
        });

        // Wiki Categories System
        Route::prefix('wiki_categories')->group(function (): void {
            Route::name('wiki_categories.')->group(function (): void {
                Route::get('/', [App\Http\Controllers\Staff\WikiCategoryController::class, 'index'])->name('index');
                Route::get('/create', [App\Http\Controllers\Staff\WikiCategoryController::class, 'create'])->name('create');
                Route::post('/store', [App\Http\Controllers\Staff\WikiCategoryController::class, 'store'])->name('store');
                Route::get('/{wikiCategory}/edit', [App\Http\Controllers\Staff\WikiCategoryController::class, 'edit'])->name('edit');
                Route::patch('/{wikiCategory}/update', [App\Http\Controllers\Staff\WikiCategoryController::class, 'update'])->name('update');
                Route::delete('/{wikiCategory}/destroy', [App\Http\Controllers\Staff\WikiCategoryController::class, 'destroy'])->name('destroy');
            });
        });

        // Wiki System
        Route::prefix('wikis')->group(function (): void {
            Route::name('wikis.')->group(function (): void {
                Route::get('/', [App\Http\Controllers\Staff\WikiController::class, 'index'])->name('index');
                Route::get('/create', [App\Http\Controllers\Staff\WikiController::class, 'create'])->name('create');
                Route::post('/store', [App\Http\Controllers\Staff\WikiController::class, 'store'])->name('store');
                Route::get('/{wiki}/edit', [App\Http\Controllers\Staff\WikiController::class, 'edit'])->name('edit');
                Route::patch('/{wiki}/update', [App\Http\Controllers\Staff\WikiController::class, 'update'])->name('update');
                Route::delete('/{wiki}/destroy', [App\Http\Controllers\Staff\WikiController::class, 'destroy'])->name('destroy');
            });
        });
    });
});
