<?php
/**
 * NOTICE OF LICENSE
 *
 * UNIT3D is open-sourced software licensed under the GNU General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     HDVinnie
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

Route::group(['middleware' => 'language'], function () {

    /*
    |------------------------------------------
    | Website (Not Authorized)
    |------------------------------------------
    */
    Route::group(['before' => 'auth', 'middleware' => 'guest'], function () {
        // Authentication Routes
        Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
        Route::post('login', 'Auth\LoginController@login')->name('');

        // Password Reset Routes
        Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
        Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
        Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('');
        Route::get('/password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');

        // Registration Routes
        Route::any('/register/{code?}', 'Auth\RegisterController@register')->name('register');

        // Activation Routes
        Route::get('/activate/{token}', 'Auth\ActivationController@activate')->name('activate');

        // Forgot Username Routes
        Route::get('username/reminder', 'Auth\ForgotUsernameController@showForgotUsernameForm')->name('username.request');
        Route::post('username/reminder', 'Auth\ForgotUsernameController@sendUsernameReminder')->name('username.email');
    });

    Route::group(['before' => 'auth'], function () {
        // Announce
        Route::get('/announce/{passkey}', 'AnnounceController@announce')->name('announce');

        // RSS
        //Route::get('/torrents/rss/{passkey}', 'RssController@getData')->name('rss');
        //Route::get('/rss/{passkey}/download/{id}','RssController@download')->name('rssDownload');
    });

    /*
    |------------------------------------------
    | Website (When Authorized)
    |------------------------------------------
    */
    Route::group(['middleware' => ['auth', 'twostep', 'online', 'banned', 'active', 'private']], function () {

        // Two Step Auth
        Route::get('/twostep/needed', 'Auth\TwoStepController@showVerification')->name('verificationNeeded');
        Route::post('/twostep/verify', 'Auth\TwoStepController@verify')->name('verify');
        Route::post('/twostep/resend', 'Auth\TwoStepController@resend')->name('resend');

        // General
        Route::get('/', 'HomeController@home')->name('home');
        Route::post('logout', 'Auth\LoginController@logout')->name('logout');

        // Article
        Route::get('/articles', 'ArticleController@articles')->name('articles');
        Route::get('/articles/{slug}.{id}', 'ArticleController@post')->name('article');

        // Bonus System
        Route::get('/bonus', 'BonusController@bonus')->name('bonus');
        Route::get('/bonusexchange/{id}', 'BonusController@exchange')->name('bonusexchange');
        Route::post('/bongift', 'BonusController@gift')->name('bongift');

        // Bookmarks
        Route::get('/bookmarks', 'BookmarkController@bookmarks')->name('bookmarks');
        Route::get('/torrents/bookmark/{id}', 'TorrentController@bookmark')->name('bookmark');
        Route::get('/torrents/unbookmark/{id}', 'TorrentController@unBookmark')->name('unbookmark');

        // User/Torrent Report
        Route::post('/report', 'ReportController@postReport')->name('postReport');

        // Bug Report
        Route::get('/bug', 'BugController@bug')->name('bug');
        Route::post('/bug', 'BugController@bug')->name('bug');

        // Category
        Route::get('/categories', 'CategoryController@categories')->name('categories');
        Route::get('/categories/{slug}.{id}', 'CategoryController@category')->name('category');

        // Catalogs
        Route::get('/catalogs', 'CatalogController@catalogs')->name('catalogs');
        Route::get('/catalog/{slug}.{id}', 'CatalogController@catalog')->name('catalog');
        Route::get('/catalog/torrents/{imdb}', 'CatalogController@torrents')->name('catalog_torrents');

        // Contact Us
        Route::get('/contact', 'ContactController@index')->name('contact');
        Route::post('/contact', 'ContactController@contact')->name('sendContact');

        // Page
        Route::get('/p/{slug}.{id}', 'PageController@page')->name('page');

        // Staff List
        Route::get('/staff', 'PageController@staff')->name('staff');

        // Internal List
        Route::get('/internal', 'PageController@internal')->name('internal');

        // Black List
        Route::get('/blacklist', 'PageController@blacklist')->name('blacklist');

        // About Us
        Route::get('/aboutus', 'PageController@about')->name('about');

        // Comments
        Route::post('/comment/article/{slug}.{id}', 'CommentController@article')->name('comment_article');
        Route::post('/comment/torrent/{slug}.{id}', 'CommentController@torrent')->name('comment_torrent');
        Route::get('/comment/thanks/{id}', 'CommentController@quickthanks')->name('comment_thanks');
        Route::post('/comment/request/{id}', 'CommentController@request')->name('comment_request');
        Route::post('/comment/edit/{comment_id}', 'CommentController@editComment')->name('comment_edit');
        Route::get('/comment/delete/{comment_id}', 'CommentController@deleteComment')->name('comment_delete');

        //Extra-Stats
        Route::get('/stats', 'StatsController@index')->name('stats');
        //User
        Route::get('/stats/user/uploaded', 'StatsController@uploaded')->name('uploaded');
        Route::get('/stats/user/downloaded', 'StatsController@downloaded')->name('downloaded');
        Route::get('/stats/user/seeders', 'StatsController@seeders')->name('seeders');
        Route::get('/stats/user/leechers', 'StatsController@leechers')->name('leechers');
        Route::get('/stats/user/uploaders', 'StatsController@uploaders')->name('uploaders');
        Route::get('/stats/user/bankers', 'StatsController@bankers')->name('bankers');
        Route::get('/stats/user/seedtime', 'StatsController@seedtime')->name('seedtime');
        Route::get('/stats/user/seedsize', 'StatsController@seedsize')->name('seedsize');
        //Torrent
        Route::get('/stats/torrent/seeded', 'StatsController@seeded')->name('seeded');
        Route::get('/stats/torrent/leeched', 'StatsController@leeched')->name('leeched');
        Route::get('/stats/torrent/completed', 'StatsController@completed')->name('completed');
        Route::get('/stats/torrent/dying', 'StatsController@dying')->name('dying');
        Route::get('/stats/torrent/dead', 'StatsController@dead')->name('dead');
        //Request
        Route::get('/stats/request/bountied', 'StatsController@bountied')->name('bountied');
        //Groups
        Route::get('/stats/groups', 'StatsController@groups')->name('groups');
        Route::get('/stats/groups/group/{id}', 'StatsController@group')->name('group');

        // Private Messages System
        Route::get('/mail/searchPM', 'PrivateMessageController@searchPM')->name('searchPM');
        Route::get('/mail/inbox', 'PrivateMessageController@getPrivateMessages')->name('inbox');
        Route::get('/mail/message/{id}', 'PrivateMessageController@getPrivateMessageById')->name('message');
        Route::get('/mail/outbox', 'PrivateMessageController@getPrivateMessagesSent')->name('outbox');
        Route::get('/mail/create/{receiver_id}/{username}', 'PrivateMessageController@makePrivateMessage')->name('create');
        Route::get('/mail/mark-all-read', 'PrivateMessageController@markAllAsRead')->name('mark-all-read');
        Route::post('/mail/send', 'PrivateMessageController@sendPrivateMessage')->name('send-pm');
        Route::post('/mail/reply/{id}', 'PrivateMessageController@replyPrivateMessage')->name('reply-pm');
        Route::post('/mail/delete/{id}', 'PrivateMessageController@deletePrivateMessage')->name('delete-pm');

        // Requests
        Route::get('filterRequests', 'RequestController@faceted');
        Route::get('/requests', 'RequestController@requests')->name('requests');
        Route::get('/request/add', 'RequestController@addRequestForm')->name('add_request_form');
        Route::post('/request/add', 'RequestController@addRequest')->name('add_request');
        Route::get('/request/{id}/edit', 'RequestController@editRequestForm')->name('edit_request_form');
        Route::post('/request/{id}/edit', 'RequestController@editRequest')->name('edit_request');
        Route::get('/request/{id}', 'RequestController@request')->name('request');
        Route::get('/request/{id}/accept', 'RequestController@approveRequest')->name('approveRequest');
        Route::post('/request/{id}/delete', 'RequestController@deleteRequest')->name('deleteRequest');
        Route::post('/request/{id}/fill', 'RequestController@fillRequest')->name('fill_request');
        Route::get('/request/{id}/reject', 'RequestController@rejectRequest')->name('rejectRequest');
        Route::post('/request/{id}/vote', 'RequestController@addBonus')->name('add_votes');
        Route::post('/request/{id}/claim', 'RequestController@claimRequest')->name('claimRequest');
        Route::get('/request/{id}/unclaim', 'RequestController@unclaimRequest')->name('unclaimRequest');

        // Torrent
        Route::get('filterTorrents', 'TorrentController@faceted');
        Route::get('/torrents', 'TorrentController@torrents')->name('torrents');
        Route::get('/torrents/{slug}.{id}', 'TorrentController@torrent')->name('torrent');
        Route::get('/torrents/{slug}.{id}/peers', 'TorrentController@peers')->name('peers');
        Route::get('/torrents/{slug}.{id}/history', 'TorrentController@history')->name('history');
        Route::get('/upload/{title?}/{imdb?}/{tmdb?}', 'TorrentController@uploadForm')->name('upload_form');
        Route::post('/upload', 'TorrentController@upload')->name('upload');
        Route::get('/download_check/{slug}.{id}', 'TorrentController@downloadCheck')->name('download_check');
        Route::get('/download/{slug}.{id}', 'TorrentController@download')->name('download');
        Route::get('/torrents/cards', 'TorrentController@cardsLayout')->name('cards');
        Route::post('/torrents/delete', 'TorrentController@deleteTorrent')->name('delete');
        Route::get('/torrents/{slug}.{id}/edit', 'TorrentController@editForm')->name('edit_form');
        Route::post('/torrents/{slug}.{id}/edit', 'TorrentController@edit')->name('edit');
        Route::get('/torrents/{slug}.{id}/torrent_fl', 'TorrentController@grantFL')->name('torrent_fl');
        Route::get('/torrents/{slug}.{id}/torrent_doubleup', 'TorrentController@grantDoubleUp')->name('torrent_doubleup');
        Route::get('/torrents/{slug}.{id}/bumpTorrent', 'TorrentController@bumpTorrent')->name('bumpTorrent');
        Route::get('/torrents/{slug}.{id}/torrent_sticky', 'TorrentController@sticky')->name('torrent_sticky');
        Route::get('/torrents/{slug}.{id}/torrent_feature', 'TorrentController@grantFeatured')->name('torrent_feature');
        Route::get('/torrents/{slug}.{id}/reseed', 'TorrentController@reseedTorrent')->name('reseed');
        Route::post('/torrents/{slug}.{id}/tip_uploader', 'BonusController@tipUploader')->name('tip_uploader');
        Route::get('/torrents/{slug}.{id}/freeleech_token', 'TorrentController@freeleechToken')->name('freeleech_token');
        Route::get('torrents/grouping/categories', 'TorrentController@groupingCategories')->name('grouping_categories');
        Route::get('torrents/grouping/{category_id}', 'TorrentController@groupingLayout')->name('grouping');
        Route::get('torrents/grouping/{category_id}/{imdb}', 'TorrentController@groupingResults')->name('grouping_results');

        // User
        Route::get('/members', 'UserController@members')->name('members');
        Route::get('/members/results', 'UserController@userSearch')->name('userSearch');
        Route::get('/{username}.{id}', 'UserController@profile')->name('profile');
        Route::get('/{username}.{id}/edit', 'UserController@editProfileForm')->name('user_edit_profile_form');
        Route::post('/{username}.{id}/edit', 'UserController@editProfile')->name('user_edit_profile');
        Route::post('/{username}.{id}/photo', 'UserController@changePhoto')->name('user_change_photo');
        Route::get('/{username}.{id}/activate/{token}', 'UserController@activate')->name('user_activate');
        Route::post('/{username}.{id}/about', 'UserController@changeAbout')->name('user_change_about');
        Route::post('/{username}.{id}/photo', 'UserController@changeTitle')->name('user_change_title');
        Route::get('/achievements', 'AchievementsController@index')->name('achievements');
        Route::get('/{username}.{id}/warninglog', 'UserController@getWarnings')->name('warninglog');
        Route::get('/deactivateWarning/{id}', 'UserController@deactivateWarning')->name('deactivateWarning');
        Route::get('/{username}.{id}/myuploads', 'UserController@myUploads')->name('myuploads');
        Route::get('/{username}.{id}/myactive', 'UserController@myActive')->name('myactive');
        Route::get('/{username}.{id}/myhistory', 'UserController@myHistory')->name('myhistory');

        // User Wishlist
        Route::get('/wishlist/{uid}', 'WishController@index')->name('wishlist');
        Route::post('/wish/{uid}', 'WishController@store')->name('wish-store');
        Route::get('/wish/{uid}/delete/{id}', 'WishController@destroy')->name('wish-delete');

        // Follow System
        Route::get('/follow/{user}', 'FollowController@follow')->name('follow');
        Route::get('/unfollow/{user}', 'FollowController@unfollow')->name('unfollow');

        //Thank System
        Route::get('/torrents/{slug}.{id}/thank', 'ThankController@torrentThank')->name('torrentThank');

        // User Settings
        Route::get('/{username}.{id}/settings', 'UserController@settings')->name('user_settings_form');
        Route::post('/{username}.{id}/settings', 'UserController@changeSettings')->name('user_settings');
        Route::post('/{username}.{id}/settings/change_password', 'UserController@changePassword')->name('change_password');
        Route::post('/{username}.{id}/settings/change_email', 'UserController@changeEmail')->name('change_email');
        Route::post('/{username}.{id}/settings/change_pid', 'UserController@changePID')->name('change_pid');

        // User Language
        Route::get('/{locale}/back', 'LanguageController@back')->name('back');

        // User Clients
        Route::get('/{username}.{id}/clients', 'UserController@clients')->name('user_clients');
        Route::post('/{username}.{id}/addcli', 'UserController@authorizeClient')->name('addcli');
        Route::post('/{username}.{id}/rmcli', 'UserController@removeClient')->name('rmcli');

        // Invite System
        Route::get('/invite', 'InviteController@invite')->name('invite');
        Route::post('/invite', 'InviteController@process')->name('process');
        Route::get('/invite/tree/{username}.{id}', 'InviteController@inviteTree')->name('inviteTree');

        // Poll System
        Route::get('/polls', 'PollController@index')->name('polls');
        Route::get('/poll/{slug}', 'PollController@show')->name('poll');
        Route::post('/poll/vote', 'PollController@vote')->middleware('check_ip');
        Route::get('/poll/{slug}/result', 'PollController@result')->name('poll_results');

        // Graveyard System
        Route::get('/graveyard', 'GraveyardController@index')->name('graveyard');
        Route::post('/graveyard/{id}', 'GraveyardController@resurrect')->name('resurrect');

        // Notifications System
        Route::get('/notifications', 'NotificationController@get')->name('get_notifications');
        Route::get('/notification/read/{id}', 'NotificationController@read')->name('read_notification');
        Route::get('/notification/massread', 'NotificationController@massRead')->name('massRead_notifications');
        Route::get('/notification/delete/{id}', 'NotificationController@delete')->name('delete_notification');
        Route::get('/notification/delete', 'NotificationController@deleteAll')->name('delete_notifications');
    });

    /*
    |------------------------------------------
    | ShoutBox Routes Group (when authorized)
    |------------------------------------------
    */
    Route::group(['prefix' => 'chatbox', 'middleware' => ['auth', 'twostep', 'online', 'banned', 'active', 'private']], function () {
        Route::get('/', 'ChatController@index');
        Route::get('chatrooms', 'ChatController@fetchChatrooms');
        Route::post('change-chatroom', 'ChatController@changeChatroom');
        Route::get('messages', 'ChatController@fetchMessages');
        Route::post('messages', 'ChatController@sendMessage');
    });

    /*
    |------------------------------------------
    | Community Routes Group (when authorized)
    |------------------------------------------
    */
    Route::group(['prefix' => 'forums', 'middleware' => ['auth', 'twostep', 'online', 'banned', 'active', 'private']], function () {
        // Display Forum Index
        Route::get('/', 'ForumController@index')->name('forum_index');
        // Search Forums
        Route::any('/search', 'ForumController@search')->name('forum_search');
        // Display Forum Categories
        Route::get('/category/{slug}.{id}', 'ForumController@category')->name('forum_category');
        // Display Topics
        Route::get('/forum/{slug}.{id}', 'ForumController@display')->name('forum_display');
        // Create New Topic
        Route::any('/forum/{slug}.{id}/new-topic', 'ForumController@newTopic')->name('forum_new_topic');
        // View Topic
        Route::get('/topic/{slug}.{id}', 'ForumController@topic')->name('forum_topic');
        // Close Topic
        Route::get('/topic/{slug}.{id}/close', 'ForumController@closeTopic')->name('forum_close');
        // Open Topic
        Route::get('/topic/{slug}.{id}/open', 'ForumController@openTopic')->name('forum_open');
        // Edit Post
        Route::any('/topic/{slug}.{id}/post-{postId}/edit', 'ForumController@postEdit')->name('forum_post_edit');
        // Delete Post
        Route::any('/topic/{slug}.{id}/post-{postId}/delete', 'ForumController@postDelete')->name('forum_post_delete');
        // Reply To Topic
        Route::post('/topic/{slug}.{id}/reply', 'ForumController@reply')->name('forum_reply');
        // Edit Topic
        Route::any('/topic/{slug}.{id}/edit', 'ForumController@editTopic')->name('forum_edit_topic');
        // Delete Topic
        Route::any('/topic/{slug}.{id}/delete', 'ForumController@deleteTopic')->name('forum_delete_topic');
        // Pin Topic
        Route::any('/topic/{slug}.{id}/pin', 'ForumController@pinTopic')->name('forum_pin_topic');
        // Unpin Topic
        Route::any('/topic/{slug}.{id}/unpin', 'ForumController@unpinTopic')->name('forum_unpin_topic');

        // Topic Label System
        Route::get('/topic/{slug}.{id}/approved', 'ForumController@approvedTopic')->name('forum_approved');
        Route::get('/topic/{slug}.{id}/denied', 'ForumController@deniedTopic')->name('forum_denied');
        Route::get('/topic/{slug}.{id}/solved', 'ForumController@solvedTopic')->name('forum_solved');
        Route::get('/topic/{slug}.{id}/invalid', 'ForumController@invalidTopic')->name('forum_invalid');
        Route::get('/topic/{slug}.{id}/bug', 'ForumController@bugTopic')->name('forum_bug');
        Route::get('/topic/{slug}.{id}/suggestion', 'ForumController@suggestionTopic')->name('forum_suggestion');
        Route::get('/topic/{slug}.{id}/implemented', 'ForumController@implementedTopic')->name('forum_implemented');

        // Like - Dislike System
        Route::any('/like/post/{postId}', 'ForumController@likePost')->name('like');
        Route::any('/dislike/post/{postId}', 'ForumController@dislikePost')->name('dislike');
    });


    /*
    |-----------------------------------------------------------------
    | Staff Dashboard Routes Group (when authorized and a staff group)
    |-----------------------------------------------------------------
    */
    Route::group(['prefix' => 'staff_dashboard', 'middleware' => ['auth', 'twostep', 'modo', 'online', 'banned', 'active', 'private'], 'namespace' => 'Staff'], function () {

        // Staff Dashboard
        Route::get('/', 'HomeController@home')->name('staff_dashboard');

        // Codebase Version Check
        Route::get('/check-update', 'VersionController@checkVersion');

        // Ban
        Route::get('/bans', 'BanController@getBans')->name('getBans');
        Route::post('/ban/{username}.{id}', 'BanController@ban')->name('ban');
        Route::post('/unban/{username}.{id}', 'BanController@unban')->name('unban');

        // Flush Ghost Peers
        Route::get('/flush', 'FlushController@deleteOldPeers')->name('flush');

        // User Tools
        Route::get('/user_search', 'UserController@members')->name('user_search');
        Route::get('/user_results', 'UserController@userSearch')->name('user_results');
        Route::get('/user_edit/{username}.{id}', 'UserController@userSettings')->name('user_setting');
        Route::post('/user_edit/{username}.{id}/edit', 'UserController@userEdit')->name('user_edit');
        Route::post('/user_edit/{username}.{id}/permissions', 'UserController@userPermissions')->name('user_permissions');
        Route::get('/user_delete/{username}.{id}', 'UserController@userDelete')->name('user_delete');
        Route::post('/user_edit/{username}.{id}/password', 'UserController@userPassword')->name('user_password');

        // Moderation
        Route::get('/torrents', 'TorrentController@index')->name('staff_torrent_index');
        Route::get('/moderation', 'ModerationController@moderation')->name('moderation');
        Route::get('/moderation/{slug}.{id}/approve', 'ModerationController@approve')->name('moderation_approve');
        Route::post('/moderation/reject', 'ModerationController@reject')->name('moderation_reject');
        Route::post('/moderation/postpone', 'ModerationController@postpone')->name('moderation_postpone');
        Route::get('/torrent_search', 'TorrentController@search')->name('torrent-search');

        // Request section
        Route::get('/request/{id}/reset', 'ModerationController@resetRequest')->name('resetRequest');

        // User Staff Notes
        Route::get('/notes', 'NoteController@getNotes')->name('getNotes');
        Route::post('/note/{username}.{id}', 'NoteController@postNote')->name('postNote');
        Route::get('/note/{id}', 'NoteController@deleteNote')->name('deleteNote');

        // Reports
        Route::get('/reports', 'ReportController@getReports')->name('getReports');
        Route::get('/reports/{report_id}', 'ReportController@getReport')->name('getReport');
        Route::post('/reports/{report_id}/solve', 'ReportController@solveReport')->name('solveReport');

        // Catalog Groups
        Route::get('/catalogs', 'CatalogController@getCatalogs')->name('getCatalog');
        Route::post('/catalogs', 'CatalogController@postCatalog')->name('postCatalog');
        Route::get('/catalogs/{genre_id}/delete', 'CatalogController@deleteCatalog')->name('deleteCatalog');
        Route::post('/catalogs/{catalog_id}/edit', 'CatalogController@editCatalog')->name('editCatalog');

        // Catalog Torrents
        Route::get('/catalog_torrent', 'CatalogController@getCatalogTorrent')->name('getCatalogTorrent');
        Route::post('/catalog_torrent', 'CatalogController@postCatalogTorrent')->name('postCatalogTorrent');
        Route::get('/catalog/{catalog_id}/records', 'CatalogController@getCatalogRecords')->name('getCatalogRecords');

        // Categories
        Route::get('/categories', 'CategoryController@index')->name('staff_category_index');
        Route::get('/categories/new', 'CategoryController@addForm')->name('staff_category_add_form');
        Route::post('/categories/new', 'CategoryController@add')->name('staff_category_add');
        Route::get('/categories/edit/{slug}.{id}', 'CategoryController@editForm')->name('staff_category_edit_form');
        Route::post('/categories/edit/{slug}.{id}', 'CategoryController@edit')->name('staff_category_edit');
        Route::get('/categories/delete/{slug}.{id}', 'CategoryController@delete')->name('staff_category_delete');

        // Types
        Route::get('/types', 'TypeController@index')->name('staff_type_index');
        Route::get('/types/new', 'TypeController@addForm')->name('staff_type_add_form');
        Route::post('/types/new', 'TypeController@add')->name('staff_type_add');
        Route::get('/types/edit/{slug}.{id}', 'TypeController@editForm')->name('staff_type_edit_form');
        Route::post('/types/edit/{slug}.{id}', 'TypeController@edit')->name('staff_type_edit');
        Route::get('/types/delete/{slug}.{id}', 'TypeController@delete')->name('staff_type_delete');

        // Forum
        Route::get('/forums', 'ForumController@index')->name('staff_forum_index');
        Route::any('/forums/new', 'ForumController@add')->name('staff_forum_add');
        Route::any('/forums/edit/{slug}.{id}', 'ForumController@edit')->name('staff_forum_edit');
        Route::get('/forums/delete/{slug}.{id}', 'ForumController@delete')->name('staff_forum_delete');

        //Pages
        Route::get('/pages', 'PageController@index')->name('staff_page_index');
        Route::get('/pages/new', 'PageController@addForm')->name('staff_page_add_form');
        Route::post('/pages/new', 'PageController@add')->name('staff_page_add');
        Route::get('/pages/edit/{slug}.{id}', 'PageController@editForm')->name('staff_page_edit_form');
        Route::post('/pages/edit/{slug}.{id}', 'PageController@edit')->name('staff_page_edit');
        Route::get('/pages/delete/{slug}.{id}', 'PageController@delete')->name('staff_page_delete');

        // Articles
        Route::get('/articles', 'ArticleController@index')->name('staff_article_index');
        Route::get('/articles/new', 'ArticleController@addForm')->name('staff_article_add_form');
        Route::post('/articles/new', 'ArticleController@add')->name('staff_article_add');
        Route::get('/articles/edit/{slug}.{id}', 'ArticleController@editForm')->name('staff_article_edit_form');
        Route::post('/articles/edit/{slug}.{id}', 'ArticleController@edit')->name('staff_article_edit');
        Route::get('/articles/delete/{slug}.{id}', 'ArticleController@delete')->name('staff_article_delete');

        // Groups
        Route::get('/groups', 'GroupsController@index')->name('staff_groups_index');
        Route::get('/groups/add', 'GroupsController@addForm')->name('staff_groups_add_form');
        Route::post('/groups/add', 'GroupsController@add')->name('staff_groups_add');
        Route::get('/groups/edit/{group}.{id}', 'GroupsController@editForm')->name('staff_groups_edit_form');
        Route::post('/groups/edit/{group}.{id}', 'GroupsController@edit')->name('staff_groups_edit');

        // Warnings
        Route::get('/warnings', 'WarningController@getWarnings')->name('getWarnings');

        // Invites
        Route::get('/invites', 'InviteController@getInvites')->name('getInvites');

        // Failed Logins
        Route::get('/failedlogin', 'FailedLoginController@getFailedAttemps')->name('getFailedAttemps');

        // Polls
        Route::get('/polls', 'PollController@polls')->name('getPolls');
        Route::get('/poll/{id}', 'PollController@poll')->name('getPoll');
        Route::get('/polls/create', 'PollController@create')->name('getCreatePoll');
        Route::post('/polls/create', 'PollController@store')->name('postCreatePoll');

        // Activity Log
        Route::get('/activitylog', 'ActivityLogController@getActivity')->name('getActivity');
        Route::get('/activitylog/delete/{id}', 'ActivityLogController@deleteActivity')->name('deleteActivity');

        // System Gifting
        Route::get('/systemgift', 'GiftController@index')->name('systemGift');
        Route::post('/systemgift/send', 'GiftController@gift')->name('sendSystemGift');

        // MassPM
        Route::get('/masspm', 'MassPMController@massPM')->name('massPM');
        Route::post('/masspm/send', 'MassPMController@sendMassPM')->name('sendMassPM');

        // Backup Manager
        Route::get('/backup', 'BackupController@index')->name('backupManager');
        Route::post('/backup/create', 'BackupController@create');
        Route::get('/backup/download/{file_name?}', 'BackupController@download');
        Route::post('/backup/delete/{file_name?}', 'BackupController@delete')->where('file_name', '(.*)');
    });
});
