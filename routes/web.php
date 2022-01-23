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
        // Announce (Pass Key Auth)
        Route::get('/announce/{passkey}', [App\Http\Controllers\AnnounceController::class, 'index'])->name('announce');

        // RSS (RSS Key Auth)
        Route::get('/rss/{id}.{rsskey}', [App\Http\Controllers\RssController::class, 'show'])->name('rss.show.rsskey');
        Route::get('/torrent/download/{id}.{rsskey}', [App\Http\Controllers\TorrentController::class, 'download'])->name('torrent.download.rsskey');
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

        // Albums System
        Route::group(['prefix' => 'albums'], function () {
            Route::name('albums.')->group(function () {
                Route::get('/', [App\Http\Controllers\AlbumController::class, 'index'])->name('index');
                Route::get('/create', [App\Http\Controllers\AlbumController::class, 'create'])->name('create');
                Route::post('/store', [App\Http\Controllers\AlbumController::class, 'store'])->name('store');
                Route::get('/{id}', [App\Http\Controllers\AlbumController::class, 'show'])->name('show');
                Route::delete('/{id}/destroy', [App\Http\Controllers\AlbumController::class, 'destroy'])->name('destroy');
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

        // Bonus System
        Route::group(['prefix' => 'bonus'], function () {
            Route::get('/', [App\Http\Controllers\BonusController::class, 'bonus'])->name('bonus');
            Route::get('/gifts', [App\Http\Controllers\BonusController::class, 'gifts'])->name('bonus_gifts');
            Route::get('/tips', [App\Http\Controllers\BonusController::class, 'tips'])->name('bonus_tips');
            Route::get('/store', [App\Http\Controllers\BonusController::class, 'store'])->name('bonus_store');
            Route::get('/gift', [App\Http\Controllers\BonusController::class, 'gift'])->name('bonus_gift');
            Route::post('/exchange/{id}', [App\Http\Controllers\BonusController::class, 'exchange'])->name('bonus_exchange');
            Route::post('/gift', [App\Http\Controllers\BonusController::class, 'sendGift'])->name('bonus_send_gift');
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
            Route::get('/{category_id}/{title?}/{imdb?}/{tmdb?}', [App\Http\Controllers\TorrentController::class, 'uploadForm'])->name('upload_form');
            Route::post('/', [App\Http\Controllers\TorrentController::class, 'upload'])->name('upload');
            Route::post('/preview', [App\Http\Controllers\TorrentController::class, 'preview']);
        });

        Route::group(['prefix' => 'torrents'], function () {
            Route::get('/', [App\Http\Controllers\TorrentController::class, 'torrents'])->name('torrents');
            Route::get('/cards', [App\Http\Controllers\TorrentCardController::class, 'index'])->name('cards');
            Route::get('/{id}{hash?}', [App\Http\Controllers\TorrentController::class, 'torrent'])->name('torrent');
            Route::get('/{id}/peers', [App\Http\Controllers\TorrentController::class, 'peers'])->name('peers');
            Route::get('/{id}/history', [App\Http\Controllers\TorrentController::class, 'history'])->name('history');
            Route::get('/download_check/{id}', [App\Http\Controllers\TorrentController::class, 'downloadCheck'])->name('download_check');
            Route::get('/download/{id}', [App\Http\Controllers\TorrentController::class, 'download'])->name('download');
            Route::post('/delete', [App\Http\Controllers\TorrentController::class, 'deleteTorrent'])->name('delete');
            Route::get('/{id}/edit', [App\Http\Controllers\TorrentController::class, 'editForm'])->name('edit_form');
            Route::post('/{id}/edit', [App\Http\Controllers\TorrentController::class, 'edit'])->name('edit');
            Route::post('/{id}/reseed', [App\Http\Controllers\TorrentController::class, 'reseedTorrent'])->name('reseed');
            Route::post('/{id}/tip_uploader', [App\Http\Controllers\BonusController::class, 'tipUploader'])->name('tip_uploader');
            Route::get('/similar/{category_id}.{tmdb}', [App\Http\Controllers\TorrentController::class, 'similar'])->name('torrents.similar');
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
            Route::get('/{username}/seeds', [App\Http\Controllers\UserController::class, 'seeds'])->name('user_seeds');
            Route::post('/{username}/flushOwnGhostPeers', [App\Http\Controllers\UserController::class, 'flushOwnGhostPeers'])->name('flush_own_ghost_peers');
            Route::get('/{username}/resurrections', [App\Http\Controllers\UserController::class, 'resurrections'])->name('user_resurrections');
            Route::get('/{username}/requested', [App\Http\Controllers\UserController::class, 'requested'])->name('user_requested');
            Route::get('/{username}/active', [App\Http\Controllers\UserController::class, 'active'])->name('user_active');
            Route::get('/{username}/activeByClient/{ip}/{port}', [App\Http\Controllers\UserController::class, 'activeByClient'])->name('user_active_by_client');
            Route::get('/{username}/torrents', [App\Http\Controllers\UserController::class, 'torrents'])->name('user_torrents');
            Route::get('/{username}/uploads', [App\Http\Controllers\UserController::class, 'uploads'])->name('user_uploads');
            Route::get('/{username}/downloads', [App\Http\Controllers\UserController::class, 'downloads'])->name('user_downloads');
            Route::get('/{username}/unsatisfieds', [App\Http\Controllers\UserController::class, 'unsatisfieds'])->name('user_unsatisfieds');
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

        // Images System
        Route::group(['prefix' => 'images'], function () {
            Route::name('images.')->group(function () {
                Route::get('/{id}/create', [App\Http\Controllers\ImageController::class, 'create'])->name('create');
                Route::post('/store', [App\Http\Controllers\ImageController::class, 'store'])->name('store');
                Route::get('/{id}/download', [App\Http\Controllers\ImageController::class, 'download'])->name('download');
                Route::delete('/{id}/destroy', [App\Http\Controllers\ImageController::class, 'destroy'])->name('destroy');
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
            Route::post('/posts/tip_poster', [App\Http\Controllers\BonusController::class, 'tipPoster'])->name('tip_poster');
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
    Route::group(['prefix' => 'dashboard', 'middleware' => ['auth', 'twostep', 'modo', 'banned']], function () {

        // Staff Dashboard
        Route::name('staff.dashboard.')->group(function () {
            Route::get('/', [App\Http\Controllers\Staff\HomeController::class, 'index'])->name('index');
        });

        // Articles System
        Route::group(['prefix' => 'articles'], function () {
            Route::name('staff.articles.')->group(function () {
                Route::get('/', [App\Http\Controllers\Staff\ArticleController::class, 'index'])->name('index');
                Route::get('/create', [App\Http\Controllers\Staff\ArticleController::class, 'create'])->name('create');
                Route::post('/store', [App\Http\Controllers\Staff\ArticleController::class, 'store'])->name('store');
                Route::get('/{id}/edit', [App\Http\Controllers\Staff\ArticleController::class, 'edit'])->name('edit');
                Route::post('/{id}/update', [App\Http\Controllers\Staff\ArticleController::class, 'update'])->name('update');
                Route::delete('/{id}/destroy', [App\Http\Controllers\Staff\ArticleController::class, 'destroy'])->name('destroy');
            });
        });

        // Applications System
        Route::group(['prefix' => 'applications'], function () {
            Route::name('staff.applications.')->group(function () {
                Route::get('/', [App\Http\Controllers\Staff\ApplicationController::class, 'index'])->name('index');
                Route::get('/{id}', [App\Http\Controllers\Staff\ApplicationController::class, 'show'])->where('id', '[0-9]+')->name('show');
                Route::post('/{id}/approve', [App\Http\Controllers\Staff\ApplicationController::class, 'approve'])->name('approve');
                Route::post('/{id}/reject', [App\Http\Controllers\Staff\ApplicationController::class, 'reject'])->name('reject');
            });
        });

        // Audit Log
        Route::group(['prefix' => 'audits'], function () {
            Route::name('staff.audits.')->group(function () {
                Route::get('/', [App\Http\Controllers\Staff\AuditController::class, 'index'])->name('index');
                Route::delete('/{id}/destroy', [App\Http\Controllers\Staff\AuditController::class, 'destroy'])->name('destroy');
            });
        });

        // Authentications Log
        Route::group(['prefix' => 'authentications'], function () {
            Route::name('staff.authentications.')->group(function () {
                Route::get('/', [App\Http\Controllers\Staff\AuthenticationController::class, 'index'])->name('index');
            });
        });

        // Backup System
        Route::group(['prefix' => 'backups'], function () {
            Route::name('staff.backups.')->group(function () {
                Route::get('/', [App\Http\Controllers\Staff\BackupController::class, 'index'])->name('index');
            });
        });

        // Ban System
        Route::group(['prefix' => 'bans'], function () {
            Route::name('staff.bans.')->group(function () {
                Route::get('/', [App\Http\Controllers\Staff\BanController::class, 'index'])->name('index');
                Route::post('/{username}/store', [App\Http\Controllers\Staff\BanController::class, 'store'])->name('store');
                Route::post('/{username}/update', [App\Http\Controllers\Staff\BanController::class, 'update'])->name('update');
            });
        });

        // Categories System
        Route::group(['prefix' => 'categories'], function () {
            Route::name('staff.categories.')->group(function () {
                Route::get('/', [App\Http\Controllers\Staff\CategoryController::class, 'index'])->name('index');
                Route::get('/create', [App\Http\Controllers\Staff\CategoryController::class, 'create'])->name('create');
                Route::post('/store', [App\Http\Controllers\Staff\CategoryController::class, 'store'])->name('store');
                Route::get('/{id}/edit', [App\Http\Controllers\Staff\CategoryController::class, 'edit'])->name('edit');
                Route::patch('/{id}/update', [App\Http\Controllers\Staff\CategoryController::class, 'update'])->name('update');
                Route::delete('/{id}/destroy', [App\Http\Controllers\Staff\CategoryController::class, 'destroy'])->name('destroy');
            });
        });

        // Chat Bots System
        Route::group(['prefix' => 'chat'], function () {
            Route::name('staff.bots.')->group(function () {
                Route::get('/bots', [App\Http\Controllers\Staff\ChatBotController::class, 'index'])->name('index');
                Route::get('/bots/{id}/edit', [App\Http\Controllers\Staff\ChatBotController::class, 'edit'])->name('edit');
                Route::patch('/bots/{id}/update', [App\Http\Controllers\Staff\ChatBotController::class, 'update'])->name('update');
                Route::delete('/bots/{id}/destroy', [App\Http\Controllers\Staff\ChatBotController::class, 'destroy'])->name('destroy');
                Route::post('/bots/{id}/disable', [App\Http\Controllers\Staff\ChatBotController::class, 'disable'])->name('disable');
                Route::post('/bots/{id}/enable', [App\Http\Controllers\Staff\ChatBotController::class, 'enable'])->name('enable');
            });
        });

        // Chat Rooms System
        Route::group(['prefix' => 'chat'], function () {
            Route::name('staff.rooms.')->group(function () {
                Route::get('/rooms', [App\Http\Controllers\Staff\ChatRoomController::class, 'index'])->name('index');
                Route::post('/rooms/store', [App\Http\Controllers\Staff\ChatRoomController::class, 'store'])->name('store');
                Route::post('/rooms/{id}/update', [App\Http\Controllers\Staff\ChatRoomController::class, 'update'])->name('update');
                Route::delete('/rooms/{id}/destroy', [App\Http\Controllers\Staff\ChatRoomController::class, 'destroy'])->name('destroy');
            });
        });

        // Chat Statuses System
        Route::group(['prefix' => 'chat'], function () {
            Route::name('staff.statuses.')->group(function () {
                Route::get('/statuses', [App\Http\Controllers\Staff\ChatStatusController::class, 'index'])->name('index');
                Route::post('/statuses/store', [App\Http\Controllers\Staff\ChatStatusController::class, 'store'])->name('store');
                Route::post('/statuses/{id}/update', [App\Http\Controllers\Staff\ChatStatusController::class, 'update'])->name('update');
                Route::delete('/statuses/{id}/destroy', [App\Http\Controllers\Staff\ChatStatusController::class, 'destroy'])->name('destroy');
            });
        });

        // Cheaters
        Route::group(['prefix' => 'cheaters'], function () {
            Route::name('staff.cheaters.')->group(function () {
                Route::get('/ghost-leechers', [App\Http\Controllers\Staff\CheaterController::class, 'index'])->name('index');
            });
        });

        // Codebase Version Check
        Route::group(['prefix' => 'UNIT3D'], function () {
            Route::get('/', [App\Http\Controllers\Staff\VersionController::class, 'checkVersion']);
        });

        // Commands
        Route::group(['prefix' => 'commands'], function () {
            Route::get('/', [App\Http\Controllers\Staff\CommandController::class, 'index'])->name('staff.commands.index');
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

        // Flush System
        Route::group(['prefix' => 'flush'], function () {
            Route::name('staff.flush.')->group(function () {
                Route::post('/peers', [App\Http\Controllers\Staff\FlushController::class, 'peers'])->name('peers');
                Route::post('/chat', [App\Http\Controllers\Staff\FlushController::class, 'chat'])->name('chat');
            });
        });

        // Forums System
        Route::group(['prefix' => 'forums'], function () {
            Route::name('staff.forums.')->group(function () {
                Route::get('/', [App\Http\Controllers\Staff\ForumController::class, 'index'])->name('index');
                Route::get('/create', [App\Http\Controllers\Staff\ForumController::class, 'create'])->name('create');
                Route::post('/store', [App\Http\Controllers\Staff\ForumController::class, 'store'])->name('store');
                Route::get('/{id}/edit', [App\Http\Controllers\Staff\ForumController::class, 'edit'])->name('edit');
                Route::post('/{id}/update', [App\Http\Controllers\Staff\ForumController::class, 'update'])->name('update');
                Route::delete('/{id}/destroy', [App\Http\Controllers\Staff\ForumController::class, 'destroy'])->name('destroy');
            });
        });

        // Groups System
        Route::group(['prefix' => 'groups'], function () {
            Route::name('staff.groups.')->group(function () {
                Route::get('/', [App\Http\Controllers\Staff\GroupController::class, 'index'])->name('index');
                Route::get('/create', [App\Http\Controllers\Staff\GroupController::class, 'create'])->name('create');
                Route::post('/store', [App\Http\Controllers\Staff\GroupController::class, 'store'])->name('store');
                Route::get('/{id}/edit', [App\Http\Controllers\Staff\GroupController::class, 'edit'])->name('edit');
                Route::post('/{id}/update', [App\Http\Controllers\Staff\GroupController::class, 'update'])->name('update');
            });
        });

        // Invites Log
        Route::group(['prefix' => 'invites'], function () {
            Route::name('staff.invites.')->group(function () {
                Route::get('/', [App\Http\Controllers\Staff\InviteController::class, 'index'])->name('index');
            });
        });

        // Mass Actions
        Route::group(['prefix' => 'mass-actions'], function () {
            Route::get('/validate-users', [App\Http\Controllers\Staff\MassActionController::class, 'update'])->name('staff.mass-actions.validate');
            Route::get('/mass-pm', [App\Http\Controllers\Staff\MassActionController::class, 'create'])->name('staff.mass-pm.create');
            Route::post('/mass-pm/store', [App\Http\Controllers\Staff\MassActionController::class, 'store'])->name('staff.mass-pm.store');
        });

        // Media Lanuages (Languages Used To Populate Language Dropdowns For Subtitles / Audios / Etc.)
        Route::group(['prefix' => 'media-languages'], function () {
            Route::name('staff.media_languages.')->group(function () {
                Route::get('/', [App\Http\Controllers\Staff\MediaLanguageController::class, 'index'])->name('index');
                Route::get('/create', [App\Http\Controllers\Staff\MediaLanguageController::class, 'create'])->name('create');
                Route::post('/store', [App\Http\Controllers\Staff\MediaLanguageController::class, 'store'])->name('store');
                Route::get('/{id}/edit', [App\Http\Controllers\Staff\MediaLanguageController::class, 'edit'])->name('edit');
                Route::post('/{id}/update', [App\Http\Controllers\Staff\MediaLanguageController::class, 'update'])->name('update');
                Route::delete('/{id}/delete', [App\Http\Controllers\Staff\MediaLanguageController::class, 'destroy'])->name('destroy');
            });
        });

        // Moderation System
        Route::group(['prefix' => 'moderation'], function () {
            Route::name('staff.moderation.')->group(function () {
                Route::get('/', [App\Http\Controllers\Staff\ModerationController::class, 'index'])->name('index');
                Route::post('/{id}/approve', [App\Http\Controllers\Staff\ModerationController::class, 'approve'])->name('approve');
                Route::post('/reject', [App\Http\Controllers\Staff\ModerationController::class, 'reject'])->name('reject');
                Route::post('/postpone', [App\Http\Controllers\Staff\ModerationController::class, 'postpone'])->name('postpone');
            });
        });

        //Pages System
        Route::group(['prefix' => 'pages'], function () {
            Route::name('staff.pages.')->group(function () {
                Route::get('/', [App\Http\Controllers\Staff\PageController::class, 'index'])->name('index');
                Route::get('/create', [App\Http\Controllers\Staff\PageController::class, 'create'])->name('create');
                Route::post('/store', [App\Http\Controllers\Staff\PageController::class, 'store'])->name('store');
                Route::get('/{id}/edit', [App\Http\Controllers\Staff\PageController::class, 'edit'])->name('edit');
                Route::post('/{id}/update', [App\Http\Controllers\Staff\PageController::class, 'update'])->name('update');
                Route::delete('/{id}/destroy', [App\Http\Controllers\Staff\PageController::class, 'destroy'])->name('destroy');
            });
        });

        // Polls System
        Route::group(['prefix' => 'polls'], function () {
            Route::name('staff.polls.')->group(function () {
                Route::get('/', [App\Http\Controllers\Staff\PollController::class, 'index'])->name('index');
                Route::get('/{id}', [App\Http\Controllers\Staff\PollController::class, 'show'])->where('id', '[0-9]+')->name('show');
                Route::get('/create', [App\Http\Controllers\Staff\PollController::class, 'create'])->name('create');
                Route::post('/store', [App\Http\Controllers\Staff\PollController::class, 'store'])->name('store');
                Route::get('/{id}/edit', [App\Http\Controllers\Staff\PollController::class, 'edit'])->name('edit');
                Route::post('/{id}/update', [App\Http\Controllers\Staff\PollController::class, 'update'])->name('update');
                Route::delete('/{id}/destroy', [App\Http\Controllers\Staff\PollController::class, 'destroy'])->name('destroy');
            });
        });

        // Registered Seedboxes
        Route::group(['prefix' => 'seedboxes'], function () {
            Route::name('staff.seedboxes.')->group(function () {
                Route::get('/', [App\Http\Controllers\Staff\SeedboxController::class, 'index'])->name('index');
                Route::delete('/{id}/destroy', [App\Http\Controllers\Staff\SeedboxController::class, 'destroy'])->name('destroy');
            });
        });

        // Reports
        Route::group(['prefix' => 'reports'], function () {
            Route::name('staff.reports.')->group(function () {
                Route::get('/', [App\Http\Controllers\Staff\ReportController::class, 'index'])->name('index');
                Route::get('/{id}', [App\Http\Controllers\Staff\ReportController::class, 'show'])->where('id', '[0-9]+')->name('show');
                Route::post('/{id}/solve', [App\Http\Controllers\Staff\ReportController::class, 'update'])->name('update');
            });
        });

        // Resolutions
        Route::group(['prefix' => 'resolutions'], function () {
            Route::name('staff.resolutions.')->group(function () {
                Route::get('/', [App\Http\Controllers\Staff\ResolutionController::class, 'index'])->name('index');
                Route::get('/create', [App\Http\Controllers\Staff\ResolutionController::class, 'create'])->name('create');
                Route::post('/store', [App\Http\Controllers\Staff\ResolutionController::class, 'store'])->name('store');
                Route::get('/{id}/edit', [App\Http\Controllers\Staff\ResolutionController::class, 'edit'])->name('edit');
                Route::patch('/{id}/update', [App\Http\Controllers\Staff\ResolutionController::class, 'update'])->name('update');
                Route::delete('/{id}/destroy', [App\Http\Controllers\Staff\ResolutionController::class, 'destroy'])->name('destroy');
            });
        });

        // RSS System
        Route::group(['prefix' => 'rss'], function () {
            Route::name('staff.rss.')->group(function () {
                Route::get('/', [App\Http\Controllers\Staff\RssController::class, 'index'])->name('index');
                Route::get('/create', [App\Http\Controllers\Staff\RssController::class, 'create'])->name('create');
                Route::post('/store', [App\Http\Controllers\Staff\RssController::class, 'store'])->name('store');
                Route::get('/{id}/edit', [App\Http\Controllers\Staff\RssController::class, 'edit'])->name('edit');
                Route::patch('/{id}/update', [App\Http\Controllers\Staff\RssController::class, 'update'])->name('update');
                Route::delete('/{id}/destroy', [App\Http\Controllers\Staff\RssController::class, 'destroy'])->name('destroy');
            });
        });

        // Types
        Route::group(['prefix' => 'types'], function () {
            Route::name('staff.types.')->group(function () {
                Route::get('/', [App\Http\Controllers\Staff\TypeController::class, 'index'])->name('index');
                Route::get('/create', [App\Http\Controllers\Staff\TypeController::class, 'create'])->name('create');
                Route::post('/store', [App\Http\Controllers\Staff\TypeController::class, 'store'])->name('store');
                Route::get('/{id}/edit', [App\Http\Controllers\Staff\TypeController::class, 'edit'])->name('edit');
                Route::patch('/{id}/update', [App\Http\Controllers\Staff\TypeController::class, 'update'])->name('update');
                Route::delete('/{id}/destroy', [App\Http\Controllers\Staff\TypeController::class, 'destroy'])->name('destroy');
            });
        });

        // User Gifting (From System)
        Route::group(['prefix' => 'gifts'], function () {
            Route::name('staff.gifts.')->group(function () {
                Route::get('/', [App\Http\Controllers\Staff\GiftController::class, 'index'])->name('index');
                Route::post('/store', [App\Http\Controllers\Staff\GiftController::class, 'store'])->name('store');
            });
        });

        // User Staff Notes
        Route::group(['prefix' => 'notes'], function () {
            Route::name('staff.notes.')->group(function () {
                Route::get('/', [App\Http\Controllers\Staff\NoteController::class, 'index'])->name('index');
                Route::post('/{username}/store', [App\Http\Controllers\Staff\NoteController::class, 'store'])->name('store');
                Route::delete('/{id}/destroy', [App\Http\Controllers\Staff\NoteController::class, 'destroy'])->name('destroy');
            });
        });

        // User Tools TODO: Leaving since we will be refactoring users and roles
        Route::group(['prefix' => 'users'], function () {
            Route::get('/', [App\Http\Controllers\Staff\UserController::class, 'index'])->name('user_search');
            Route::post('/{username}/edit', [App\Http\Controllers\Staff\UserController::class, 'edit'])->name('user_edit');
            Route::get('/{username}/settings', [App\Http\Controllers\Staff\UserController::class, 'settings'])->name('user_setting');
            Route::post('/{username}/permissions', [App\Http\Controllers\Staff\UserController::class, 'permissions'])->name('user_permissions');
            Route::post('/{username}/password', [App\Http\Controllers\Staff\UserController::class, 'password'])->name('user_password');
            Route::delete('/{username}/destroy', [App\Http\Controllers\Staff\UserController::class, 'destroy'])->name('user_delete');
            Route::post('/{username}/warn', [App\Http\Controllers\Staff\UserController::class, 'warnUser'])->name('user_warn');
        });

        // Warnings Log
        Route::group(['prefix' => 'warnings'], function () {
            Route::name('staff.warnings.')->group(function () {
                Route::get('/', [App\Http\Controllers\Staff\WarningController::class, 'index'])->name('index');
            });
        });

        // Internals System
        Route::group(['prefix' => 'internals'], function () {
            Route::name('staff.internals.')->group(function () {
                Route::get('/', [App\Http\Controllers\Staff\InternalController::class, 'index'])->name('index');
                Route::get('/{id}/edit', [App\Http\Controllers\Staff\InternalController::class, 'edit'])->name('edit');
                Route::post('/{id}/update', [App\Http\Controllers\Staff\InternalController::class, 'update'])->name('update');
                Route::get('/create', [App\Http\Controllers\Staff\InternalController::class, 'create'])->name('create');
                Route::post('/store', [App\Http\Controllers\Staff\InternalController::class, 'store'])->name('store');
                Route::delete('/{id}/destroy', [App\Http\Controllers\Staff\InternalController::class, 'destroy'])->name('destroy');
            });
        });

        // Watchlist
        Route::group(['prefix' => 'watchlist'], function () {
            Route::name('staff.watchlist.')->group(function () {
                Route::get('/', [App\Http\Controllers\Staff\WatchlistController::class, 'index'])->name('index');
                Route::post('/{id}/store', [App\Http\Controllers\Staff\WatchlistController::class, 'store'])->name('store');
                Route::delete('/{id}/destroy', [App\Http\Controllers\Staff\WatchlistController::class, 'destroy'])->name('destroy');
            });
        });
    });
});
