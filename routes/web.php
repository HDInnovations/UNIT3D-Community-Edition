<?php

use Illuminate\Support\Facades\Route;

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
| Here is where you can register all of the routes for an application.
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
Route::group(['middleware' => 'language'], function () {
    /*
    |---------------------------------------------------------------------------------
    | Website (Not Authorized) (Alpha Ordered)
    |---------------------------------------------------------------------------------
    */
    Route::group(['before' => 'auth', 'middleware' => 'guest'], function () {
        // Activation
        Route::get('/activate/{token}', [App\Http\Controllers\Auth\ActivationController::class, 'activate'])->name('activate');

        // Application Signup
        Route::get('/application', [App\Http\Controllers\Auth\ApplicationController::class, 'create'])->name('application.create');
        Route::post('/application', [App\Http\Controllers\Auth\ApplicationController::class, 'store'])->name('application.store');

        // Authentication
        Route::get('login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
        Route::post('login', [App\Http\Controllers\Auth\LoginController::class, 'login'])->name('');

        // Forgot Username
        Route::get('username/reminder', [App\Http\Controllers\Auth\ForgotUsernameController::class, 'showForgotUsernameForm'])->name('username.request');
        Route::post('username/reminder', [App\Http\Controllers\Auth\ForgotUsernameController::class, 'sendUsernameReminder'])->name('username.email');

        // Password Reset
        Route::post('password/email', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
        Route::get('password/reset', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
        Route::post('password/reset', [App\Http\Controllers\Auth\ResetPasswordController::class, 'reset'])->name('');
        Route::get('/password/reset/{token}', [App\Http\Controllers\Auth\ResetPasswordController::class, 'showResetForm'])->name('password.reset');

        // Registration
        Route::get('/register/{code?}', [App\Http\Controllers\Auth\RegisterController::class, 'registrationForm'])->name('registrationForm');
        Route::post('/register/{code?}', [App\Http\Controllers\Auth\RegisterController::class, 'register'])->name('register');
    });

    /*
    |---------------------------------------------------------------------------------
    | Website (Authorized By Key) (Alpha Ordered)
    |---------------------------------------------------------------------------------
    */
    Route::group(['before' => 'auth'], function () {
        // RSS (RSS Key Auth)
        Route::get('/rss/{id}.{rsskey}', [App\Http\Controllers\RssController::class, 'show'])->name('rss.show.rsskey');
        Route::get('/torrent/download/{id}.{rsskey}', [App\Http\Controllers\TorrentDownloadController::class, 'store'])->name('torrent.download.rsskey');
    });

    /*
    |---------------------------------------------------------------------------------
    | Website (When Authorized) (Alpha Ordered)
    |---------------------------------------------------------------------------------
    */
    Route::group(['middleware' => ['auth', 'twostep', 'banned']], function () {

        // General
        Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');
        Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home.index');

        // Achievements System
        Route::group(['prefix' => 'achievements'], function () {
            Route::name('achievements.')->group(function () {
                Route::get('/', [App\Http\Controllers\AchievementsController::class, 'index'])->name('index');
                Route::get('/{username}', [App\Http\Controllers\AchievementsController::class, 'show'])->name('show');
            });
        });

        // Articles System
        Route::group(['prefix' => 'articles'], function () {
            Route::name('articles.')->group(function () {
                Route::get('/', [App\Http\Controllers\ArticleController::class, 'index'])->name('index');
                Route::get('/{id}', [App\Http\Controllers\ArticleController::class, 'show'])->name('show');
            });
        });

        // RSS System
        Route::group(['prefix' => 'rss'], function () {
            Route::name('rss.')->group(function () {
                Route::get('/', [App\Http\Controllers\RssController::class, 'index'])->name('index');
                Route::get('/create', [App\Http\Controllers\RssController::class, 'create'])->name('create');
                Route::post('/store', [App\Http\Controllers\RssController::class, 'store'])->name('store');
                Route::get('/{id}/edit', [App\Http\Controllers\RssController::class, 'edit'])->name('edit');
                Route::patch('/{id}/update', [App\Http\Controllers\RssController::class, 'update'])->name('update');
                Route::delete('/{id}/destroy', [App\Http\Controllers\RssController::class, 'destroy'])->name('destroy');
            });
        });

        // TwoStep Auth System
        Route::group(['prefix' => 'twostep'], function () {
            Route::get('/needed', [App\Http\Controllers\Auth\TwoStepController::class, 'showVerification'])->name('verificationNeeded');
            Route::post('/verify', [App\Http\Controllers\Auth\TwoStepController::class, 'verify'])->name('verify');
            Route::post('/resend', [App\Http\Controllers\Auth\TwoStepController::class, 'resend'])->name('resend');
        });

        // Reports System
        Route::group(['prefix' => 'reports'], function () {
            Route::post('/torrent/{id}', [App\Http\Controllers\ReportController::class, 'torrent'])->name('report_torrent');
            Route::post('/request/{id}', [App\Http\Controllers\ReportController::class, 'request'])->name('report_request');
            Route::post('/user/{username}', [App\Http\Controllers\ReportController::class, 'user'])->name('report_user');
        });

        // Contact Us System
        Route::group(['prefix' => 'contact'], function () {
            Route::name('contact.')->group(function () {
                Route::get('/', [App\Http\Controllers\ContactController::class, 'index'])->name('index');
                Route::post('/store', [App\Http\Controllers\ContactController::class, 'store'])->name('store');
            });
        });

        // Pages System
        Route::group(['prefix' => 'pages'], function () {
            Route::get('/', [App\Http\Controllers\PageController::class, 'index'])->name('pages.index');
            Route::get('/staff', [App\Http\Controllers\PageController::class, 'staff'])->name('staff');
            Route::get('/internal', [App\Http\Controllers\PageController::class, 'internal'])->name('internal');
            Route::get('/blacklist', [App\Http\Controllers\PageController::class, 'blacklist'])->name('blacklist');
            Route::get('/aboutus', [App\Http\Controllers\PageController::class, 'about'])->name('about');
            Route::get('/{id}', [App\Http\Controllers\PageController::class, 'show'])->where('id', '[0-9]+')->name('pages.show');
        });

        // Comments System
        Route::group(['prefix' => 'comments'], function () {
            Route::post('/article/{id}', [App\Http\Controllers\CommentController::class, 'article'])->name('comment_article');
            Route::post('/torrent/{id}', [App\Http\Controllers\CommentController::class, 'torrent'])->name('comment_torrent');
            Route::post('/thanks/{id}', [App\Http\Controllers\CommentController::class, 'quickthanks'])->name('comment_thanks');
            Route::post('/request/{id}', [App\Http\Controllers\CommentController::class, 'request'])->name('comment_request');
            Route::post('/playlist/{id}', [App\Http\Controllers\CommentController::class, 'playlist'])->name('comment_playlist');
            Route::post('/collection/{id}', [App\Http\Controllers\CommentController::class, 'collection'])->name('comment_collection');
            Route::post('/ticket/{id}', [App\Http\Controllers\CommentController::class, 'ticket'])->name('comment_ticket');
            Route::post('/edit/{comment_id}', [App\Http\Controllers\CommentController::class, 'editComment'])->name('comment_edit');
            Route::delete('/delete/{comment_id}', [App\Http\Controllers\CommentController::class, 'deleteComment'])->name('comment_delete');
        });

        // Extra-Stats System
        Route::group(['prefix' => 'stats'], function () {
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
        });

        // Private Messages System
        Route::group(['prefix' => 'mail'], function () {
            Route::post('/searchPMInbox', [App\Http\Controllers\PrivateMessageController::class, 'searchPMInbox'])->name('searchPMInbox');
            Route::post('/searchPMOutbox', [App\Http\Controllers\PrivateMessageController::class, 'searchPMOutbox'])->name('searchPMOutbox');
            Route::get('/inbox', [App\Http\Controllers\PrivateMessageController::class, 'getPrivateMessages'])->name('inbox');
            Route::get('/message/{id}', [App\Http\Controllers\PrivateMessageController::class, 'getPrivateMessageById'])->name('message');
            Route::get('/outbox', [App\Http\Controllers\PrivateMessageController::class, 'getPrivateMessagesSent'])->name('outbox');
            Route::get('/create', [App\Http\Controllers\PrivateMessageController::class, 'makePrivateMessage'])->name('create');
            Route::post('/mark-all-read', [App\Http\Controllers\PrivateMessageController::class, 'markAllAsRead'])->name('mark-all-read');
            Route::delete('/empty-inbox', [App\Http\Controllers\PrivateMessageController::class, 'emptyInbox'])->name('empty-inbox');
            Route::post('/send', [App\Http\Controllers\PrivateMessageController::class, 'sendPrivateMessage'])->name('send-pm');
            Route::post('/{id}/reply', [App\Http\Controllers\PrivateMessageController::class, 'replyPrivateMessage'])->name('reply-pm');
            Route::post('/{id}/destroy', [App\Http\Controllers\PrivateMessageController::class, 'deletePrivateMessage'])->name('delete-pm');
        });

        // Requests System
        Route::group(['prefix' => 'requests'], function () {
            Route::name('requests.')->group(function () {
                Route::get('/', [App\Http\Controllers\RequestController::class, 'index'])->name('index');
            });
        });

        Route::group(['prefix' => 'requests'], function () {
            Route::get('/add/{title?}/{imdb?}/{tmdb?}', [App\Http\Controllers\RequestController::class, 'addRequestForm'])->name('add_request_form');
            Route::post('/add', [App\Http\Controllers\RequestController::class, 'addRequest'])->name('add_request');
            Route::get('/{id}/edit', [App\Http\Controllers\RequestController::class, 'editRequestForm'])->name('edit_request_form');
            Route::post('/{id}/edit', [App\Http\Controllers\RequestController::class, 'editRequest'])->name('edit_request');
            Route::get('/{id}{hash?}', [App\Http\Controllers\RequestController::class, 'request'])->name('request');
            Route::post('/{id}/accept', [App\Http\Controllers\RequestController::class, 'approveRequest'])->name('approveRequest');
            Route::post('/{id}/delete', [App\Http\Controllers\RequestController::class, 'deleteRequest'])->name('deleteRequest');
            Route::post('/{id}/fill', [App\Http\Controllers\RequestController::class, 'fillRequest'])->name('fill_request');
            Route::post('/{id}/reject', [App\Http\Controllers\RequestController::class, 'rejectRequest'])->name('rejectRequest');
            Route::post('/{id}/vote', [App\Http\Controllers\RequestController::class, 'addBonus'])->name('add_votes');
            Route::post('/{id}/claim', [App\Http\Controllers\RequestController::class, 'claimRequest'])->name('claimRequest');
            Route::post('/{id}/unclaim', [App\Http\Controllers\RequestController::class, 'unclaimRequest'])->name('unclaimRequest');
            Route::post('/{id}/reset', [App\Http\Controllers\RequestController::class, 'resetRequest'])->name('resetRequest')->middleware('modo');
        });

        // Top 10 System
        Route::group(['prefix' => 'top10'], function () {
            Route::name('top10.')->group(function () {
                Route::get('/', [App\Http\Controllers\Top10Controller::class, 'index'])->name('index');
            });
        });

        // Torrents System
        Route::group(['prefix' => 'upload'], function () {
            Route::get('/{category_id}/{title?}/{imdb?}/{tmdb?}', [App\Http\Controllers\TorrentController::class, 'create'])->name('upload_form');
            Route::post('/', [App\Http\Controllers\TorrentController::class, 'store'])->name('upload');
            Route::post('/preview', [App\Http\Controllers\TorrentController::class, 'preview']);
        });

        Route::group(['prefix' => 'torrents'], function () {
            Route::get('/', [App\Http\Controllers\TorrentController::class, 'index'])->name('torrents');
            Route::get('/cards', [App\Http\Controllers\TorrentCardController::class, 'index'])->name('cards');
            Route::get('/grouped', [App\Http\Controllers\TorrentGroupController::class, 'index'])->name('grouped');
            Route::get('/{id}{hash?}', [App\Http\Controllers\TorrentController::class, 'show'])->name('torrent');
            Route::get('/{id}/peers', [App\Http\Controllers\TorrentPeerController::class, 'index'])->name('peers');
            Route::get('/{id}/history', [App\Http\Controllers\TorrentHistoryController::class, 'index'])->name('history');
            Route::get('/download_check/{id}', [App\Http\Controllers\TorrentDownloadController::class, 'show'])->name('download_check');
            Route::get('/download/{id}', [App\Http\Controllers\TorrentDownloadController::class, 'store'])->name('download');
            Route::post('/delete', [App\Http\Controllers\TorrentController::class, 'destroy'])->name('delete');
            Route::get('/{id}/edit', [App\Http\Controllers\TorrentController::class, 'edit'])->name('edit_form');
            Route::post('/{id}/edit', [App\Http\Controllers\TorrentController::class, 'update'])->name('edit');
            Route::post('/{id}/reseed', [App\Http\Controllers\ReseedController::class, 'store'])->name('reseed');
            Route::get('/similar/{category_id}.{tmdb}', [App\Http\Controllers\SimilarTorrentController::class, 'show'])->name('torrents.similar');
        });

        Route::group(['prefix' => 'torrent'], function () {
            Route::post('/{id}/torrent_fl', [App\Http\Controllers\TorrentBuffController::class, 'grantFL'])->name('torrent_fl');
            Route::post('/{id}/torrent_doubleup', [App\Http\Controllers\TorrentBuffController::class, 'grantDoubleUp'])->name('torrent_doubleup');
            Route::post('/{id}/bumpTorrent', [App\Http\Controllers\TorrentBuffController::class, 'bumpTorrent'])->name('bumpTorrent');
            Route::post('/{id}/torrent_sticky', [App\Http\Controllers\TorrentBuffController::class, 'sticky'])->name('torrent_sticky');
            Route::post('/{id}/torrent_feature', [App\Http\Controllers\TorrentBuffController::class, 'grantFeatured'])->name('torrent_feature');
            Route::post('/{id}/torrent_revokefeature', [App\Http\Controllers\TorrentBuffController::class, 'revokeFeatured'])->name('torrent_revokefeature');
            Route::post('/{id}/freeleech_token', [App\Http\Controllers\TorrentBuffController::class, 'freeleechToken'])->name('freeleech_token');
        });

        // Warnings System
        Route::group(['prefix' => 'warnings'], function () {
            Route::post('/{id}/deactivate', [App\Http\Controllers\WarningController::class, 'deactivate'])->name('deactivateWarning');
            Route::post('/{username}/mass-deactivate', [App\Http\Controllers\WarningController::class, 'deactivateAllWarnings'])->name('massDeactivateWarnings');
            Route::delete('/{id}', [App\Http\Controllers\WarningController::class, 'deleteWarning'])->name('deleteWarning');
            Route::delete('/{username}/mass-delete', [App\Http\Controllers\WarningController::class, 'deleteAllWarnings'])->name('massDeleteWarnings');
            Route::post('/{id}/restore', [App\Http\Controllers\WarningController::class, 'restoreWarning'])->name('restoreWarning');
            Route::get('/{username}', [App\Http\Controllers\WarningController::class, 'show'])->name('warnings.show');
        });

        // Users System
        Route::group(['prefix' => 'users'], function () {
            Route::get('/{username}', [App\Http\Controllers\UserController::class, 'show'])->name('users.show');
            Route::get('/{username}/edit', [App\Http\Controllers\UserController::class, 'editProfileForm'])->name('user_edit_profile_form');
            Route::post('/{username}/edit', [App\Http\Controllers\UserController::class, 'editProfile'])->name('user_edit_profile');
            Route::get('/{username}/banlog', [App\Http\Controllers\UserController::class, 'getBans'])->name('banlog');
            Route::post('/{username}/userFilters', [App\Http\Controllers\UserController::class, 'myFilter'])->name('myfilter');
            Route::get('/{username}/downloadHistoryTorrents', [App\Http\Controllers\UserController::class, 'downloadHistoryTorrents'])->name('download_history_torrents');
            Route::post('/{username}/flushOwnGhostPeers', [App\Http\Controllers\UserController::class, 'flushOwnGhostPeers'])->name('flush_own_ghost_peers');
            Route::get('/{username}/resurrections', [App\Http\Controllers\UserController::class, 'resurrections'])->name('user_resurrections');
            Route::get('/{username}/requested', [App\Http\Controllers\UserController::class, 'requested'])->name('user_requested');
            Route::get('/{username}/active', [App\Http\Controllers\UserController::class, 'active'])->name('user_active');
            Route::get('/{username}/activeByClient/{ip}/{port}', [App\Http\Controllers\UserController::class, 'activeByClient'])->name('user_active_by_client');
            Route::get('/{username}/torrents', [App\Http\Controllers\UserController::class, 'torrents'])->name('user_torrents');
            Route::get('/{username}/uploads', [App\Http\Controllers\UserController::class, 'uploads'])->name('user_uploads');
            Route::get('/{username}/topics', [App\Http\Controllers\UserController::class, 'topics'])->name('user_topics');
            Route::get('/{username}/posts', [App\Http\Controllers\UserController::class, 'posts'])->name('user_posts');
            Route::get('/{username}/followers', [App\Http\Controllers\UserController::class, 'followers'])->name('user_followers');
            Route::get('/{username}/settings', [App\Http\Controllers\UserController::class, 'settings'])->name('user_settings');
            Route::get('/{username}/settings/privacy{hash?}', [App\Http\Controllers\UserController::class, 'privacy'])->name('user_privacy');
            Route::get('/{username}/settings/security{hash?}', [App\Http\Controllers\UserController::class, 'security'])->name('user_security');
            Route::get('/{username}/settings/notification{hash?}', [App\Http\Controllers\UserController::class, 'notification'])->name('user_notification');
            Route::get('/{username}/settings/change_twostep', [App\Http\Controllers\UserController::class, 'changeTwoStep']);
            Route::post('/{username}/settings/change_settings', [App\Http\Controllers\UserController::class, 'changeSettings'])->name('change_settings');
            Route::post('/{username}/settings/change_password', [App\Http\Controllers\UserController::class, 'changePassword'])->name('change_password');
            Route::post('/{username}/settings/change_email', [App\Http\Controllers\UserController::class, 'changeEmail'])->name('change_email');
            Route::post('/{username}/settings/change_pid', [App\Http\Controllers\UserController::class, 'changePID'])->name('change_pid');
            Route::post('/{username}/settings/change_rid', [App\Http\Controllers\UserController::class, 'changeRID'])->name('change_rid');
            Route::post('/{username}/settings/change_api_token', [App\Http\Controllers\UserController::class, 'changeApiToken'])->name('change_api_token');
            Route::post('/{username}/settings/notification/disable', [App\Http\Controllers\UserController::class, 'disableNotifications'])->name('notification_disable');
            Route::post('/{username}/settings/notification/enable', [App\Http\Controllers\UserController::class, 'enableNotifications'])->name('notification_enable');
            Route::post('/{username}/settings/notification/account', [App\Http\Controllers\UserController::class, 'changeAccountNotification'])->name('notification_account');
            Route::post('/{username}/settings/notification/following', [App\Http\Controllers\UserController::class, 'changeFollowingNotification'])->name('notification_following');
            Route::post('/{username}/settings/notification/forum', [App\Http\Controllers\UserController::class, 'changeForumNotification'])->name('notification_forum');
            Route::post('/{username}/settings/notification/subscription', [App\Http\Controllers\UserController::class, 'changeSubscriptionNotification'])->name('notification_subscription');
            Route::post('/{username}/settings/notification/mention', [App\Http\Controllers\UserController::class, 'changeMentionNotification'])->name('notification_mention');
            Route::post('/{username}/settings/notification/torrent', [App\Http\Controllers\UserController::class, 'changeTorrentNotification'])->name('notification_torrent');
            Route::post('/{username}/settings/notification/bon', [App\Http\Controllers\UserController::class, 'changeBonNotification'])->name('notification_bon');
            Route::post('/{username}/settings/notification/request', [App\Http\Controllers\UserController::class, 'changeRequestNotification'])->name('notification_request');
            Route::post('/{username}/settings/privacy/profile', [App\Http\Controllers\UserController::class, 'changeProfile'])->name('privacy_profile');
            Route::post('/{username}/settings/privacy/forum', [App\Http\Controllers\UserController::class, 'changeForum'])->name('privacy_forum');
            Route::post('/{username}/settings/privacy/torrent', [App\Http\Controllers\UserController::class, 'changeTorrent'])->name('privacy_torrent');
            Route::post('/{username}/settings/privacy/follower', [App\Http\Controllers\UserController::class, 'changeFollower'])->name('privacy_follower');
            Route::post('/{username}/settings/privacy/achievement', [App\Http\Controllers\UserController::class, 'changeAchievement'])->name('privacy_achievement');
            Route::post('/{username}/settings/privacy/request', [App\Http\Controllers\UserController::class, 'changeRequest'])->name('privacy_request');
            Route::post('/{username}/settings/privacy/other', [App\Http\Controllers\UserController::class, 'changeOther'])->name('privacy_other');
            Route::post('/{username}/settings/change_twostep', [App\Http\Controllers\UserController::class, 'changeTwoStep'])->name('change_twostep');
            Route::post('/{username}/settings/hidden', [App\Http\Controllers\UserController::class, 'makeHidden'])->name('user_hidden');
            Route::post('/{username}/settings/visible', [App\Http\Controllers\UserController::class, 'makeVisible'])->name('user_visible');
            Route::post('/{username}/settings/private', [App\Http\Controllers\UserController::class, 'makePrivate'])->name('user_private');
            Route::post('/{username}/settings/public', [App\Http\Controllers\UserController::class, 'makePublic'])->name('user_public');
            Route::post('/accept-rules', [App\Http\Controllers\UserController::class, 'acceptRules'])->name('accept.rules');
            Route::get('/{username}/seedboxes', [App\Http\Controllers\SeedboxController::class, 'index'])->name('seedboxes.index');
            Route::post('/{username}/seedboxes', [App\Http\Controllers\SeedboxController::class, 'store'])->name('seedboxes.store');
            Route::delete('/seedboxes/{id}', [App\Http\Controllers\SeedboxController::class, 'destroy'])->name('seedboxes.destroy');

            // Bonus System
            Route::group(['prefix' => '{username}/bonus'], function () {
                Route::name('earnings.')->prefix('earnings')->group(function () {
                    Route::get('/', [App\Http\Controllers\UserEarningController::class, 'index'])->name('index');
                });
                Route::name('gifts.')->prefix('gifts')->group(function () {
                    Route::get('/', [App\Http\Controllers\UserGiftController::class, 'index'])->name('index');
                    Route::get('/create', [App\Http\Controllers\UserGiftController::class, 'create'])->name('create');
                    Route::post('/', [App\Http\Controllers\UserGiftController::class, 'store'])->name('store');
                });
                Route::name('tips.')->prefix('tips')->group(function () {
                    Route::get('/', [App\Http\Controllers\UserTipController::class, 'index'])->name('index');
                    Route::post('/', [App\Http\Controllers\UserTipController::class, 'store'])->name('store');
                });
                Route::name('transactions.')->prefix('transactions')->group(function () {
                    Route::get('/create', [App\Http\Controllers\UserTransactionController::class, 'create'])->name('create');
                    Route::post('/', [App\Http\Controllers\UserTransactionController::class, 'store'])->name('store');
                });
            });
        });

        // Wishlist System
        Route::group(['prefix' => 'wishes'], function () {
            Route::name('wishes.')->group(function () {
                Route::get('/{username}', [App\Http\Controllers\WishController::class, 'index'])->name('index');
                Route::post('/store', [App\Http\Controllers\WishController::class, 'store'])->name('store');
                Route::delete('/{id}/destroy', [App\Http\Controllers\WishController::class, 'destroy'])->name('destroy');
            });
        });

        // Follow System
        Route::group(['prefix' => 'follow'], function () {
            Route::name('follow.')->group(function () {
                Route::post('/{username}', [App\Http\Controllers\FollowController::class, 'store'])->name('store');
                Route::delete('/{username}', [App\Http\Controllers\FollowController::class, 'destroy'])->name('destroy');
            });
        });

        // Invite System
        Route::group(['prefix' => 'invites'], function () {
            Route::name('invites.')->group(function () {
                Route::get('/create', [App\Http\Controllers\InviteController::class, 'create'])->name('create');
                Route::post('/store', [App\Http\Controllers\InviteController::class, 'store'])->name('store');
                Route::post('/{id}/send', [App\Http\Controllers\InviteController::class, 'send'])->where('id', '[0-9]+')->name('send');
                Route::get('/{username}', [App\Http\Controllers\InviteController::class, 'index'])->name('index');
            });
        });

        // Poll System
        Route::group(['prefix' => 'polls'], function () {
            Route::get('/', [App\Http\Controllers\PollController::class, 'index'])->name('polls');
            Route::post('/vote', [App\Http\Controllers\PollController::class, 'vote']);
            Route::get('/{id}', [App\Http\Controllers\PollController::class, 'show'])->where('id', '[0-9]+')->name('poll');
            Route::get('/{id}/result', [App\Http\Controllers\PollController::class, 'result'])->name('poll_results');
        });

        // Graveyard System
        Route::group(['prefix' => 'graveyard'], function () {
            Route::name('graveyard.')->group(function () {
                Route::get('/', [App\Http\Controllers\GraveyardController::class, 'index'])->name('index');
                Route::post('/{id}/store', [App\Http\Controllers\GraveyardController::class, 'store'])->name('store');
                Route::delete('/{id}/destroy', [App\Http\Controllers\GraveyardController::class, 'destroy'])->name('destroy');
            });
        });

        // Notifications System
        Route::group(['prefix' => 'notifications'], function () {
            Route::name('notifications.')->group(function () {
                Route::get('/filter', [App\Http\Controllers\NotificationController::class, 'faceted']);
                Route::get('/', [App\Http\Controllers\NotificationController::class, 'index'])->name('index');
                Route::post('/{id}/update', [App\Http\Controllers\NotificationController::class, 'update'])->name('update');
                Route::post('/updateall', [App\Http\Controllers\NotificationController::class, 'updateAll'])->name('updateall');
                Route::delete('/{id}/destroy', [App\Http\Controllers\NotificationController::class, 'destroy'])->name('destroy');
                Route::delete('/destroyall', [App\Http\Controllers\NotificationController::class, 'destroyAll'])->name('destroyall');
                Route::get('/{id}', [App\Http\Controllers\NotificationController::class, 'show'])->name('show');
            });
        });

        // Playlist System
        Route::group(['prefix' => 'playlists'], function () {
            Route::name('playlists.')->group(function () {
                Route::get('/', [App\Http\Controllers\PlaylistController::class, 'index'])->name('index');
                Route::get('/create', [App\Http\Controllers\PlaylistController::class, 'create'])->name('create');
                Route::post('/store', [App\Http\Controllers\PlaylistController::class, 'store'])->name('store');
                Route::get('/{id}', [App\Http\Controllers\PlaylistController::class, 'show'])->where('id', '[0-9]+')->name('show');
                Route::get('/{id}/edit', [App\Http\Controllers\PlaylistController::class, 'edit'])->name('edit');
                Route::patch('/{id}/update', [App\Http\Controllers\PlaylistController::class, 'update'])->name('update');
                Route::delete('/{id}/destroy', [App\Http\Controllers\PlaylistController::class, 'destroy'])->name('destroy');
                Route::post('/attach', [App\Http\Controllers\PlaylistTorrentController::class, 'store'])->name('attach');
                Route::delete('/{id}/detach', [App\Http\Controllers\PlaylistTorrentController::class, 'destroy'])->name('detach');
                Route::get('/{id}/download', [App\Http\Controllers\PlaylistController::class, 'downloadPlaylist'])->name('download');
            });
        });

        // Subtitles System
        Route::group(['prefix' => 'subtitles'], function () {
            Route::name('subtitles.')->group(function () {
                Route::get('/', [App\Http\Controllers\SubtitleController::class, 'index'])->name('index');
                Route::get('/create/{torrent_id}', [App\Http\Controllers\SubtitleController::class, 'create'])->where('id', '[0-9]+')->name('create');
                Route::post('/store', [App\Http\Controllers\SubtitleController::class, 'store'])->name('store');
                Route::post('/{id}/update', [App\Http\Controllers\SubtitleController::class, 'update'])->name('update');
                Route::delete('/{id}/delete', [App\Http\Controllers\SubtitleController::class, 'destroy'])->name('destroy');
                Route::get('/{id}/download', [App\Http\Controllers\SubtitleController::class, 'download'])->name('download');
            });
        });

        // Tickets System
        Route::group(['prefix' => 'tickets'], function () {
            Route::name('tickets.')->group(function () {
                Route::get('/', [App\Http\Controllers\TicketController::class, 'index'])->name('index');
                Route::get('/create', [App\Http\Controllers\TicketController::class, 'create'])->name('create');
                Route::post('/store', [App\Http\Controllers\TicketController::class, 'store'])->name('store');
                Route::get('/{id}', [App\Http\Controllers\TicketController::class, 'show'])->where('id', '[0-9]+')->name('show');
                Route::get('/{id}/edit', [App\Http\Controllers\TicketController::class, 'edit'])->name('edit');
                Route::patch('/{id}/update', [App\Http\Controllers\TicketController::class, 'update'])->name('update');
                Route::delete('/{id}/destroy', [App\Http\Controllers\TicketController::class, 'destroy'])->name('destroy');
                Route::post('/{id}/assign', [App\Http\Controllers\TicketController::class, 'assign'])->name('assign');
                Route::post('/{id}/unassign', [App\Http\Controllers\TicketController::class, 'unassign'])->name('unassign');
                Route::post('/{id}/close', [App\Http\Controllers\TicketController::class, 'close'])->name('close');
                Route::post('/attachments/{attachment}/download', [App\Http\Controllers\TicketAttachmentController::class, 'download'])->name('attachment.download');
            });
        });

        // Missing System
        Route::group(['prefix' => 'missing'], function () {
            Route::name('missing.')->group(function () {
                Route::get('/', [App\Http\Controllers\MissingController::class, 'index'])->name('index');
            });
        });
    });

    /*
    |------------------------------------------
    | MediaHub (When Authorized)
    |------------------------------------------
    */
    Route::group(['prefix' => 'mediahub', 'middleware' => ['auth', 'twostep', 'banned']], function () {
        // MediaHub Home
        Route::get('/', [App\Http\Controllers\MediaHub\HomeController::class, 'index'])->name('mediahub.index');

        // Genres
        Route::get('/genres', [App\Http\Controllers\MediaHub\GenreController::class, 'index'])->name('mediahub.genres.index');

        // Genre
        Route::get('/genre/{id}', [App\Http\Controllers\MediaHub\GenreController::class, 'show'])->name('mediahub.genres.show');

        // Networks
        Route::get('/networks', [App\Http\Controllers\MediaHub\NetworkController::class, 'index'])->name('mediahub.networks.index');

        // Network
        Route::get('/network/{id}', [App\Http\Controllers\MediaHub\NetworkController::class, 'show'])->name('mediahub.networks.show');

        // Companies
        Route::get('/companies', [App\Http\Controllers\MediaHub\CompanyController::class, 'index'])->name('mediahub.companies.index');

        // Company
        Route::get('/company/{id}', [App\Http\Controllers\MediaHub\CompanyController::class, 'show'])->name('mediahub.companies.show');

        // TV Shows
        Route::get('/tv-shows', [App\Http\Controllers\MediaHub\TvShowController::class, 'index'])->name('mediahub.shows.index');

        // TV Show
        Route::get('/tv-show/{id}', [App\Http\Controllers\MediaHub\TvShowController::class, 'show'])->name('mediahub.shows.show');

        // TV Show Season
        Route::get('/tv-show/season/{id}', [App\Http\Controllers\MediaHub\TvSeasonController::class, 'show'])->name('mediahub.season.show');

        // Persons
        Route::get('/persons', [App\Http\Controllers\MediaHub\PersonController::class, 'index'])->name('mediahub.persons.index');

        // Person
        Route::get('/persons/{id}', [App\Http\Controllers\MediaHub\PersonController::class, 'show'])->name('mediahub.persons.show');

        // Collections
        Route::get('/collections', [App\Http\Controllers\MediaHub\CollectionController::class, 'index'])->name('mediahub.collections.index');

        // Collection
        Route::get('/collections/{id}', [App\Http\Controllers\MediaHub\CollectionController::class, 'show'])->name('mediahub.collections.show');

        // Movies
        Route::get('/movies', [App\Http\Controllers\MediaHub\MovieController::class, 'index'])->name('mediahub.movies.index');

        // Movie
        Route::get('/movies/{id}', [App\Http\Controllers\MediaHub\MovieController::class, 'show'])->name('mediahub.movies.show');
    });

    /*
    |---------------------------------------------------------------------------------
    | Forums Routes Group (When Authorized) (Alpha Ordered)
    |---------------------------------------------------------------------------------
    */
    Route::group(['prefix' => 'forums', 'middleware' => ['auth', 'twostep', 'banned']], function () {
        // Forum System
        Route::name('forums.')->group(function () {
            Route::get('/', [App\Http\Controllers\ForumController::class, 'index'])->name('index');
            Route::get('/{id}', [App\Http\Controllers\ForumController::class, 'show'])->where('id', '[0-9]+')->name('show');
        });

        // Forum Category System
        Route::group(['prefix' => 'categories'], function () {
            Route::name('forums.categories.')->group(function () {
                Route::get('/{id}', [App\Http\Controllers\ForumCategoryController::class, 'show'])->where('id', '[0-9]+')->name('show');
            });
        });

        // Posts System
        Route::group(['prefix' => 'posts'], function () {
            Route::post('/topic/{id}/reply', [App\Http\Controllers\PostController::class, 'reply'])->name('forum_reply');
            Route::get('/posts/{id}/post-{postId}/edit', [App\Http\Controllers\PostController::class, 'postEditForm'])->name('forum_post_edit_form');
            Route::post('/posts/{postId}/edit', [App\Http\Controllers\PostController::class, 'postEdit'])->name('forum_post_edit');
            Route::delete('/posts/{postId}/delete', [App\Http\Controllers\PostController::class, 'postDelete'])->name('forum_post_delete');
        });

        // Search Forums
        Route::get('/subscriptions', [App\Http\Controllers\ForumController::class, 'subscriptions'])->name('forum_subscriptions');
        Route::get('/latest/topics', [App\Http\Controllers\ForumController::class, 'latestTopics'])->name('forum_latest_topics');
        Route::get('/latest/posts', [App\Http\Controllers\ForumController::class, 'latestPosts'])->name('forum_latest_posts');
        Route::get('/search', [App\Http\Controllers\ForumController::class, 'search'])->name('forum_search_form');

        Route::group(['prefix' => 'topics'], function () {
            Route::get('/forum/{id}/new-topic', [App\Http\Controllers\TopicController::class, 'addForm'])->name('forum_new_topic_form');
            Route::post('/forum/{id}/new-topic', [App\Http\Controllers\TopicController::class, 'newTopic'])->name('forum_new_topic');
            Route::get('/{id}{page?}{post?}', [App\Http\Controllers\TopicController::class, 'topic'])->name('forum_topic');
            Route::post('/{id}/close', [App\Http\Controllers\TopicController::class, 'closeTopic'])->name('forum_close');
            Route::post('/{id}/open', [App\Http\Controllers\TopicController::class, 'openTopic'])->name('forum_open');
            Route::get('/{id}/edit', [App\Http\Controllers\TopicController::class, 'editForm'])->name('forum_edit_topic_form');
            Route::post('/{id}/edit', [App\Http\Controllers\TopicController::class, 'editTopic'])->name('forum_edit_topic');
            Route::delete('/{id}/delete', [App\Http\Controllers\TopicController::class, 'deleteTopic'])->name('forum_delete_topic');
            Route::post('/{id}/pin', [App\Http\Controllers\TopicController::class, 'pinTopic'])->name('forum_pin_topic');
            Route::post('/{id}/unpin', [App\Http\Controllers\TopicController::class, 'unpinTopic'])->name('forum_unpin_topic');
        });

        // Topic Label System
        Route::group(['prefix' => 'topics', 'middleware' => 'modo'], function () {
            Route::name('topics.')->group(function () {
                Route::post('/{id}/approve', [App\Http\Controllers\TopicLabelController::class, 'approve'])->name('approve');
                Route::post('/{id}/deny', [App\Http\Controllers\TopicLabelController::class, 'deny'])->name('deny');
                Route::post('/{id}/solve', [App\Http\Controllers\TopicLabelController::class, 'solve'])->name('solve');
                Route::post('/{id}/invalid', [App\Http\Controllers\TopicLabelController::class, 'invalid'])->name('invalid');
                Route::post('/{id}/bug', [App\Http\Controllers\TopicLabelController::class, 'bug'])->name('bug');
                Route::post('/{id}/suggest', [App\Http\Controllers\TopicLabelController::class, 'suggest'])->name('suggest');
                Route::post('/{id}/implement', [App\Http\Controllers\TopicLabelController::class, 'implement'])->name('implement');
            });
        });

        // Subscription System
        Route::post('/subscribe/topic/{route}.{topic}', [App\Http\Controllers\SubscriptionController::class, 'subscribeTopic'])->name('subscribe_topic');
        Route::post('/unsubscribe/topic/{route}.{topic}', [App\Http\Controllers\SubscriptionController::class, 'unsubscribeTopic'])->name('unsubscribe_topic');
        Route::post('/subscribe/forum/{route}.{forum}', [App\Http\Controllers\SubscriptionController::class, 'subscribeForum'])->name('subscribe_forum');
        Route::post('/unsubscribe/forum/{route}.{forum}', [App\Http\Controllers\SubscriptionController::class, 'unsubscribeForum'])->name('unsubscribe_forum');
    });

    /*
    |---------------------------------------------------------------------------------
    | Staff Dashboard Routes Group (When Authorized And A Staff Group) (Alpha Ordered)
    |---------------------------------------------------------------------------------
    */
    Route::group([
        'namespace'  => 'App\Http\Controllers\Staff',
        'prefix'     => 'dashboard',
        'middleware' => ['auth', 'twostep', 'modo', 'banned'],
    ], function () {
        Route::group(['as' => 'staff.'], function () {
            // Staff Dashboard
            Route::resource('/', HomeController::class)->only(['index'])->name('index', 'dashboard.index');

            // Articles System
            Route::resource('articles', ArticleController::class)->except(['show']);

            // Applications System
            // Can't use route model binding for applications as the hootex/laravel-moderation package global scope interferes with it
            Route::group(['as' => 'applications.', 'controller' => ApplicationController::class, 'prefix' => 'applications'], function () {
                Route::get('/', 'index')->name('index');
                Route::get('/{id}', 'show')->name('show');
                Route::post('/{id}/approve', 'approve')->name('approve');
                Route::post('/{id}/reject', 'reject')->name('reject');
            });

            // Audit Log
            Route::resource('audits', AuditController::class)->only(['index', 'destroy']);

            // Authentications Log
            Route::resource('authentications', AuthenticationController::class)->only(['index']);

            // Backup System
            Route::resource('backups', BackupController::class)->only(['index']);

            // Ban System
            Route::resource('bans', BanController::class)->only(['index', 'store']);
            Route::group(['as' =>'bans.', 'controller' => BanController::class, 'prefix' => 'bans'], function () {
                Route::delete('/{user}/destroy', 'destroy')->name('destroy');
            });

            // Bon Exchanges
            Route::resource('bon-exchanges', BonExchangeController::class)->except(['show']);

            // Categories System
            Route::resource('categories', CategoryController::class)->except(['show']);

            // Chat Bots System
            Route::group(['as' => 'bots.', 'controller' => ChatBotController::class, 'prefix' => 'bots'], function () {
                Route::post('/{bot}/disable', 'disable')->name('disable');
                Route::post('/{bot}/enable', 'enable')->name('enable');
            });
            Route::resource('bots', ChatBotController::class)->except(['show', 'create', 'store']);

            // Chat Rooms System
            Route::resource('chatrooms', ChatRoomController::class)->except(['show']);

            // Chat Statuses System
            Route::resource('chat-statuses', ChatStatusController::class)->except(['show']);

            // Cheaters
            Route::resource('cheaters', CheaterController::class)->only(['index'])->name('index', 'cheaters.index');

            // Codebase Version Check
            Route::resource('UNIT3D', VersionController::class)->only('index')->name('index', 'UNIT3D');

            // Commands
            Route::group(['as' => 'commands.', 'controller' => CommandController::class, 'prefix' => 'commands'], function () {
                Route::get('/', 'index')->name('index');
                Route::post('/maintance-enable', 'maintanceEnable');
                Route::post('/maintance-disable', 'maintanceDisable');
                Route::post('/clear-cache', 'clearCache');
                Route::post('/clear-view-cache', 'clearView');
                Route::post('/clear-route-cache', 'clearRoute');
                Route::post('/clear-config-cache', 'clearConfig');
                Route::post('/clear-all-cache', 'clearAllCache');
                Route::post('/set-all-cache', 'setAllCache');
                Route::post('/test-email', 'testEmail');
            });

            // Flush System
            Route::group(['as' => 'flush.', 'controller' => FlushController::class, 'prefix' => 'flush'], function () {
                Route::post('/peers', 'peers')->name('peers');
                Route::post('/chat', 'chat')->name('chat');
            });

            // Forums System
            Route::resource('forums', ForumController::class)->except(['show']);

            // Groups System
            Route::resource('groups', GroupController::class)->except(['show', 'destroy']);

            // Invites Log
            Route::resource('invites', InviteController::class)->only(['index']);

            // Mass Actions
            Route::group(['controller' => MassActionController::class, 'prefix' => 'mass-actions'], function () {
                Route::get('/validate-users', 'update')->name('mass-actions.validate');
                Route::get('/mass-pm', 'create')->name('mass-pm.create');
                Route::post('/mass-pm/store', 'store')->name('mass-pm.store');
            });

            // Media Lanuages (Languages Used To Populate Language Dropdowns For Subtitles / Audios / Etc.)
            Route::resource('media-languages', MediaLanguageController::class)->except(['show']);

            // Moderation System
            // Can't use route model binding for torrent moderation as the hootex/laravel-moderation package global scope interferes with it
            Route::group(['as' => 'moderation.', 'controller' => ModerationController::class, 'prefix' => 'moderation'], function () {
                Route::get('/', 'index')->name('index');
                Route::post('/{id}/approve', 'approve')->name('approve');
                Route::post('/reject', 'reject')->name('reject');
                Route::post('/postpone', 'postpone')->name('postpone');
            });

            //Pages System
            Route::resource('pages', PageController::class)->except(['show']);

            // Polls System
            Route::resource('polls', PollController::class);

            // Registered Seedboxes
            Route::resource('seedboxes', SeedboxController::class)->only(['index', 'destroy']);

            // Reports
            Route::resource('reports', ReportController::class)->only(['index', 'show', 'update']);

            // Resolutions
            Route::resource('resolutions', ResolutionController::class)->except(['show']);

            // RSS System
            Route::resource('rss', RssController::class)->except(['show']);

            // Types
            Route::resource('types', TypeController::class)->except(['show']);

            // User Gifting (From System)
            Route::resource('gifts', GiftController::class)->only(['index', 'store']);

            // User Staff Notes
            Route::group(['as' => 'notes.', 'controller' => NoteController::class, 'prefix' => 'notes'], function () {
                Route::post('/{username}/store', 'store')->name('store');
            });
            Route::resource('notes', NoteController::class)->only(['index', 'destroy']);
            // User Tools TODO: Leaving since we will be refactoring users and roles
            Route::group(['as' => 'users.', 'controller' => UserController::class, 'prefix' => 'users'], function () {
                Route::get('/', 'index')->name('user_search');
                Route::post('/{username}/edit', 'edit')->name('user_edit');
                Route::get('/{username}/settings', 'settings')->name('user_setting');
                Route::post('/{username}/permissions', 'permissions')->name('user_permissions');
                Route::post('/{username}/password', 'password')->name('user_password');
                Route::delete('/{username}/destroy', 'destroy')->name('user_delete');
                Route::post('/{username}/warn', 'warnUser')->name('user_warn');
            });

            // Warnings Log
            Route::resource('warnings', WarningController::class)->only(['index']);

            // Internals System
            Route::resource('internals', InternalController::class)->except(['show']);

            // Watchlist
            Route::resource('watched-users', WatchlistController::class)->only(['index', 'store', 'destroy']);
        });
    });

    /*
    |---------------------------------------------------------------------------------
    | Staff Dashboard LiveWire Routes Group (When Authorized And A Staff Group) (Alpha Ordered)
    |---------------------------------------------------------------------------------
    */
    Route::group(['prefix' => 'dashboard', 'middleware' => ['auth', 'twostep', 'modo', 'banned']], function () {
        // Laravel Log Viewer
        Route::get('/laravel-log', \App\Http\Livewire\LaravelLogViewer::class)->middleware('owner')->name('staff.laravellog.index');
    });
});
