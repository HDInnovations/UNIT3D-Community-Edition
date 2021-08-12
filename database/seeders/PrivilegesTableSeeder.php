<?php

namespace Database\Seeders;

use App\Models\Privilege;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class PrivilegesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        Privilege::query()->truncate();
        Privilege::upsert([

            ['slug'           => 'dashboard_can_view', 'name' => 'Site Tools: Can View Dashboard',
                'description' => 'This allows the user to access the Site Tools dashboard panel. The user will see the modules they are privileged to use only.', ],

            ['slug'           => 'dashboard_can_backup', 'name' => 'Site Tools: Can Run & Access Backups',
                'description' => 'This allows the user to access the Site Tools backup panel. The user will be able to view, create, download and delete backups.', ],

            ['slug'           => 'dashboard_can_commands', 'name' => 'Site Tools: Can Run Server Commands',
                'description' => 'This allows the user to access the Site Tools commands panel. The user will be able to run predefined commands such as cache clearing and more.', ],

            ['slug'           => 'dashboard_can_chat_statuses', 'name' => 'Site Tools: Can Manage Chat Statuses',
                'description' => 'This allows the user to access the Site Tools chat statuses panel. The user will be able to view, create, edit and delete chat statuses.', ],

            ['slug'           => 'dashboard_can_chat_rooms', 'name' => 'Site Tools: Can Manage Chat Rooms',
                'description' => 'This allows the user to access the Site Tools chat rooms panel. The user will be able to view, create, edit and delete chat rooms.', ],

            ['slug'           => 'dashboard_can_chat_bots', 'name' => 'Site Tools: Can Manage Chat Bots',
                'description' => 'This allows the user to access the Site Tools chat bots panel. The user will be able to view, edit, enable and disable chat bots.', ],

            ['slug'           => 'dashboard_can_flush_chat', 'name' => 'Site Tools: Can Flush Chat',
                'description' => 'This allows the user to flush all messages from the chat. This command can be time consuming.', ],

            ['slug'           => 'dashboard_can_articles', 'name' => 'Site Tools: Can Manage Articles',
                'description' => 'This allows the user to access the Site Tools articles/news panel. The user will be able to view, create, edit and delete articles.', ],

            ['slug'           => 'dashboard_can_applications', 'name' => 'Site Tools: Can Moderate Applications',
                'description' => 'This allows the user to access the Site Tools user regisrtation applications panel. The user will be able to view, approve, and deny applications.', ],

            ['slug'           => 'dashboard_can_forums', 'name' => 'Site Tools: Can Manage Forums',
                'description' => 'This allows the user to access the Site Tools forums panel. The user will be able to view, create, edit and delete forums and forum categories.', ],

            ['slug'           => 'dashboard_can_pages', 'name' => 'Site Tools: Can Manage Pages',
                'description' => 'This allows the user to access the Site Tools pages panel. The user will be able to view, create, edit and delete pages.', ],

            ['slug'           => 'dashboard_can_polls', 'name' => 'Site Tools: Can Manage Polls',
                'description' => 'This allows the user to access the Site Tools polls panel. The user will be able to view, create, edit and delete polls.', ],

            ['slug'           => 'dashboard_can_rss', 'name' => 'Site Tools: Can Manage RSS',
                'description' => 'This allows the user to access the Site Tools RSS panel. The user will be able to view, create, edit and delete public RSS feeds.', ],

            ['slug'           => 'dashboard_can_torrent_moderation', 'name' => 'Site Tools: Can Access Torrent Moderation',
                'description' => 'This allows the user to access the Site Tools torrent moderation panel. The user will be able to view, approve, postpone and reject torrents.', ],

            ['slug'           => 'dashboard_can_torrent_categories', 'name' => 'Site Tools: Can Manage Torrent Categories',
                'description' => 'This allows the user to access the Site Tools torrent categories panel. The user will be able to view, create, edit and delete torrent categories.', ],

            ['slug'           => 'dashboard_can_torrent_type', 'name' => 'Site Tools: Can Manage Torrent Types',
                'description' => 'This allows the user to access the Site Tools torrent types panel. The user will be able to view, create, edit and delete torrent types.', ],

            ['slug'           => 'dashboard_can_torrent_resolutions', 'name' => 'Site Tools: Can Manage Torrent Resolutions',
                'description' => 'This allows the user to access the Site Tools torrent resolutions panel. The user will be able to view, create, edit and delete torrent resolutions.', ],

            ['slug'           => 'dashboard_can_media_languages', 'name' => 'Site Tools: Can Manage Media Languages',
                'description' => 'This allows the user to access the Site Tools media languages panel. The user will be able to view, create, edit and delete media languages which are currently used for Subtitles System.', ],

            ['slug'           => 'dashboard_can_flush_ghost_peers', 'name' => 'Site Tools: Can Flush Ghost Peers',
                'description' => 'This allows the user to globally flush all stale ghost peers from the tracker.', ],

            ['slug'           => 'dashboard_can_user_search', 'name' => 'Site Tools: Can Search Users',
                'description' => 'This allows the user to access the Site Tools user search panel. The user will be able to view and search for users.', ],

            ['slug'           => 'dashboard_can_user_watchlist', 'name' => 'Site Tools: Can Manage User Watchlist',
                'description' => 'This allows the user to access the Site Tools user watchlist panel. The user will be able to view and unwatch currently watched users.', ],

            ['slug'           => 'dashboard_can_user_gift', 'name' => 'Site Tools: Can Give Gifts to Users',
                'description' => 'This allows the user to access the Site Tools gifts panel. The user will be able to gift a single user via username with FL Tokens, BON and Invites.', ],

            ['slug'           => 'dashboard_can_mass_pm', 'name' => 'Site Tools: Can Sen A Mass Private Message',
                'description' => 'This allows the user to access the Site Tools mass pm panel. The user will be able to send a mass pm to all users in the database.', ],

            ['slug'           => 'dashboard_can_mass_validate', 'name' => 'Site Tools: Can Mass Validate Users',
                'description' => 'This allows the user to mass validate all user currently stuck in the validatiing role.', ],

            ['slug'           => 'dashboard_can_cheaters', 'name' => 'Site Tools: Can Manage Possible Cheaters',
                'description' => 'This allows the user to access the Site Tools possible cheaters panel. The user will be able to view all possible users that are cheating the tracker.', ],

            ['slug'           => 'dashboard_can_seedboxes', 'name' => 'Site Tools: Can Manage Registered Seedboxes',
                'description' => 'This allow the user to access the Site Tools registered seedboxes panel. The user will be able to view and delete all seedboxes registered on site by users.', ],

            ['slug'           => 'dashboard_can_audit_log', 'name' => 'Site Tools: Can Access Audit Log',
                'description' => 'This allow the user to access the Site Tools audits panel. The user will be able to view and delete all model audits performed on site.', ],

            ['slug'           => 'dashboard_can_ban_log', 'name' => 'Site Tools: Can Access Ban Log',
                'description' => 'This allow the user to access the Site Tools user ban log panel. The user will be able to view all users that have been banned and unbanned from site.', ],

            ['slug'           => 'dashboard_can_failed_login_log', 'name' => 'Site Tools: Can Access Failed Login Log',
                'description' => 'This allow the user to access the Site Tools failed logins log panel. The user will be able to view all recorded failed login attemps agaist the site.', ],

            ['slug'           => 'dashboard_can_invites_log', 'name' => 'Site Tools: Can Access Invites Log',
                'description' => 'This allow the user to access the Site Tools invites log panel. The user will be able to view all invites proccessed on site.', ],

            ['slug'           => 'dashboard_can_user_notes_log', 'name' => 'Site Tools: Can Access User Notes Log',
                'description' => 'This allow the user to access the Site Tools user notes log panel. The user will be able to view and delete all user notes.', ],

            ['slug'           => 'dashboard_can_reports_log', 'name' => 'Site Tools: Can Access Reports Log',
                'description' => 'This allow the user to access the Site Tools reports panel. The user will be able to view and resolve all reports.', ],

            ['slug'           => 'dashboard_can_warning_log', 'name' => 'Site Tools: Can Access Warning Log',
                'description' => 'This allow the user to access the Site Tools warning log panel. The user will be able to view all hit and run warnings in the database.', ],

            ['slug'           => 'torrent_can_view', 'name' => 'Torrents: Can Search & View',
                'description' => 'Can Search and View Torrents on The Site', ],

            ['slug'           => 'torrent_can_create', 'name' => 'Torrents: Can Create/Upload',
                'description' => 'User Can Create a New Torrent on the Site', ],

            ['slug'           => 'torrent_can_bypass_modq', 'name' => 'Torrents: Bypass Moderation',
                'description' => 'When a User Creates a New Torrent, the Torrent will bypass the Moderation Queue', ],

            ['slug'           => 'torrent_can_moderate', 'name' => 'Torrents: Can Moderate Torrents',
                'description' => 'User can Approve, Postpone, or Reject Torrents - Both in Moderation Queue and On-Site', ],

            ['slug'           => 'torrent_can_update', 'name' => 'Torrents: Can Edit Torrents',
                'description' => 'Can edit the fields which have been granted permission. This grants access to the necessary pages.', ],

            ['slug'           => 'torrent_can_update_title', 'name' => 'Torrents: Can Edit Titles',
                'description' => 'Can edit torrents title.', ],

            ['slug'           => 'torrent_can_update_description', 'name' => 'Torrents: Can Edit Descriptions',
                'description' => 'Can edit torrents description.', ],

            ['slug'           => 'torrent_can_update_mediainfo', 'name' => 'Torrents: Can Edit MediaInfo',
                'description' => 'Can edit torrents mediainfo.', ],

            ['slug'           => 'torrent_can_update_bdinfo', 'name' => 'Torrents: Can Edit BDInfo',
                'description' => 'Can edit torrents bdinfo.', ],

            ['slug'           => 'torrent_can_update_category', 'name' => 'Torrents: Can Edit Category',
                'description' => 'Can edit torrents category.', ],

            ['slug'           => 'torrent_can_update_type', 'name' => 'Torrents: Can Edit Type',
                'description' => 'Can edit torrents type.', ],

            ['slug'           => 'torrent_can_update_resolution', 'name' => 'Torrents: Can Edit Resolution',
                'description' => 'Can edit torrents resolution..', ],

            ['slug'           => 'torrent_can_update_meta_ids', 'name' => 'Torrents: Can Edit Meta Id\'s',
                'description' => 'Can edit torrents meta ids.', ],

            ['slug'           => 'torrent_can_update_special', 'name' => 'Torrents: Can Edit Special Attributes',
                'description' => 'Can edit torrents special attributes.', ],

            ['slug'           => 'torrent_can_delete', 'name' => 'Torrents: Can Delete',
                'description' => 'Can delete torrents.', ],

            ['slug'           => 'torrent_can_download', 'name' => 'Torrents: Can Download',
                'description' => 'Can download torrents.', ],

            ['slug'           => 'torrent_can_request_reseed', 'name' => 'Torrents: Can Request Reseed',
                'description' => 'Can request a reseed on torrents.', ],

            ['slug'           => 'torrent_can_bump', 'name' => 'Torrents: Can Bump',
                'description' => 'Can bump torrents to top of list.', ],

            ['slug'           => 'torrent_can_internal', 'name' => 'Torrents: Can Internal',
                'description' => 'Can mark torrents as internal.', ],

            ['slug'           => 'torrent_can_freeleech', 'name' => 'Torrents: Can Freeleech',
                'description' => 'Can mark torrents as freeleech.', ],

            ['slug'           => 'torrent_can_doubleupload', 'name' => 'Torrents: Can Double Upload',
                'description' => 'Can mark torrents as double upload.', ],

            ['slug'           => 'torrent_can_personal_release', 'name' => 'Torrents: Can Personal Release',
                'description' => 'Can mark torrents as personal release.', ],

            ['slug'           => 'torrent_can_sticky', 'name' => 'Torrents: Can Sticky',
                'description' => 'Can mark torrents as sticky.', ],

            ['slug'           => 'torrent_can_feature', 'name' => 'Torrents: Can Feature',
                'description' => 'Can feature torrents.', ],

            ['slug'           => 'torrent_can_revoke_feature', 'name' => 'Torrents: Can Revoke Feature',
                'description' => 'Can revoke featured torrents.', ],

            ['slug'           => 'request_can_view', 'name' => 'Requests: Can View',
                'description' => 'Can view torrent requests.', ],

            ['slug'           => 'request_can_create', 'name' => 'Requests: Can Create',
                'description' => 'Can create torrent requests.', ],

            ['slug'           => 'request_can_update', 'name' => 'Requests: Can Edit',
                'description' => 'Can edit the fields which have been granted permission. This grants access to the necessary pages.', ],

            ['slug'           => 'request_can_update_title', 'name' => 'Requests: Can Edit Title',
                'description' => 'Can edit torrent requests title.', ],

            ['slug'           => 'request_can_update_description', 'name' => 'Requests: Can Edit Description',
                'description' => 'Can edit torrent requests description.', ],

            ['slug'           => 'request_can_update_category', 'name' => 'Requests: Can Update Category',
                'description' => 'Can edit torrent requests category.', ],

            ['slug'           => 'request_can_update_type', 'name' => 'Requests: Can Update Type',
                'description' => 'Can edit torrent requests type.', ],

            ['slug'           => 'request_can_update_resolution', 'name' => 'Requests: Can Update Resolution',
                'description' => 'Can edit torrent requests resolution.', ],

            ['slug'           => 'request_can_update_meta_ids', 'name' => 'Requests: Can Update Meta Id\'s',
                'description' => 'Can edit torrent requests meta ids.', ],

            ['slug'           => 'request_can_unclaim', 'name' => 'Requests: Can Remove Claiming User',
                'description' => 'Can force unclaim, claimed torrent requests.', ],

            ['slug'           => 'request_can_delete', 'name' => 'Requests: Can Delete',
                'description' => 'Can delete torrent requests.', ],

            ['slug'           => 'request_can_approve', 'name' => 'Requests: Can Approve',
                'description' => 'Can approve torrent requests.', ],

            ['slug'           => 'request_can_deny', 'name' => 'Requests: Can Deny',
                'description' => 'Can deny torrent requests.', ],

            ['slug'           => 'request_can_reset', 'name' => 'Requests: Can Reset',
                'description' => 'Can reset torrent requests.', ],

            ['slug'           => 'comment_can_view', 'name' => 'Comments: Can View',
                'description' => 'Can view comments.', ],

            ['slug'           => 'comment_can_create', 'name' => 'Comments: Can Create',
                'description' => 'Can create comments.', ],

            ['slug'           => 'comment_can_update', 'name' => 'Comments: Can Update',
                'description' => 'Can edit comments.', ],

            ['slug'           => 'comment_can_delete', 'name' => 'Comments: Can Delete',
                'description' => 'Can delete comments.', ],

            ['slug'           => 'chat_can_moderate', 'name' => 'Chat: Can Moderate',
                'description' => 'Can delete chat messages', ],

            ['slug'           => 'comments_can_moderate', 'name' => 'Comments: Can Moderate',
                'description' => 'Can edit and delete comments.', ],

            ['slug'           => 'forums_can_view', 'name' => 'Forums (Global): Can View',
                'description' => 'User can view forums.', ],

            ['slug'           => 'forums_can_sticky', 'name' => 'Forums (Global): Can Sticky/Pin',
                'description' => 'User can sticky/pin a forum topic.', ],

            ['slug'           => 'forums_can_bump', 'name' => 'Forums (Global): Can Bump',
                'description' => 'User can bump a forum topic.', ],

            ['slug'           => 'forums_can_create_topic', 'name' => 'Forums (Global): Can Create Topic',
                'description' => 'User can create a forum topic.', ],

            ['slug'           => 'forums_can_edit_topic', 'name' => 'Forums (Global): Can Edit Topic',
                'description' => 'User can edit a forum topic.', ],

            ['slug'           => 'forums_can_delete_topic', 'name' => 'Forums (Global): Can Delete Topic',
                'description' => 'User can delete a forum topic.', ],

            ['slug'           => 'forums_can_moderate', 'name' => 'Forums (Global): Can Moderate',
                'description' => 'User can moderate a forum topic.', ],

            ['slug'           => 'forums_can_comment', 'name' => 'Forums (Global): Can Comment',
                'description' => 'User can post on forum topics.', ],

            ['slug'           => 'forums_can_update_comment', 'name' => 'Forums (Global): Can Update Comment',
                'description' => 'User can edit forum topic posts.', ],

            ['slug'           => 'forums_can_delete_comment', 'name' => 'Forums (Global): Can Delete Comment',
                'description' => 'User can delete forum topic posts.', ],

            ['slug'           => 'forums_sudo', 'name' => 'Forums: Sudo Forums',
                'description' => 'Show Forum, Read Topic, Reply Topic, and Start Topic Privilege on All Forums.', ],

            ['slug'           => 'playlist_can_view', 'name' => 'Playlists: Can View',
                'description' => 'Can view torrent playlists.', ],

            ['slug'           => 'playlist_can_create', 'name' => 'Playlists: Can Create',
                'description' => 'Can create a torrent playlist.', ],

            ['slug'           => 'playlist_can_update', 'name' => 'Playlists: Can Update',
                'description' => 'Can edit a torrent playlist.', ],

            ['slug'           => 'playlist_can_delete', 'name' => 'Playlists: Can Delete',
                'description' => 'Can delete a torrent playlist.', ],

            ['slug'           => 'playlist_can_download_all', 'name' => 'Playlists: Can Download All',
                'description' => 'Can download all torrents in a playlist.', ],

            ['slug'           => 'polls_can_view', 'name' => 'Polls: Can View',
                'description' => 'User can view active polls.', ],

            ['slug'           => 'polls_can_vote', 'name' => 'Polls: Can Vote',
                'description' => 'User can vote on a poll.', ],

            ['slug'           => 'polls_can_create', 'name' => 'Polls: Can Create',
                'description' => 'User can create a new poll.', ],

            ['slug'           => 'polls_can_delete', 'name' => 'Polls: Can Delete',
                'description' => 'User can delete a poll.', ],

            ['slug'           => 'subtitle_can_view', 'name' => 'Subtitle: Can View',
                'description' => 'User can view subtitles.', ],

            ['slug'           => 'subtitle_can_create', 'name' => 'Subtitle: Can Create',
                'description' => 'User can create a subtitle.', ],

            ['slug'           => 'subtitle_can_update', 'name' => 'Subtitle: Can Update',
                'description' => 'User can edit a subtitle.', ],

            ['slug'           => 'subtitle_can_delete', 'name' => 'Subtitle: Can Delete',
                'description' => 'User can delete a subtitle.', ],

            ['slug'           => 'subtitle_can_download', 'name' => 'Subtitle: Can Download',
                'description' => 'User can download a subtitle.', ],

            ['slug'           => 'movie_can_create', 'name' => 'MediaHub - Movies: Can Create',
                'description' => 'User can create a new Movie entry.', ],

            ['slug'           => 'movie_can_update', 'name' => 'MediaHub - Movies: Can Update',
                'description' => 'User can edit a Movie.', ],

            ['slug'           => 'movie_can_update_title', 'name' => 'MediaHub - Movies: Can Update Title',
                'description' => 'User can edit the title of a Movie.', ],

            ['slug'           => 'movie_can_update_original_language', 'name' => 'MediaHub - Movies: Can Update Original Language',
                'description' => 'User can edit the original language of a Movie.', ],

            ['slug'           => 'movie_can_update_backdrop', 'name' => 'MediaHub - Movies: Can Update Backdrop',
                'description' => 'User can edit the backdrop image of a Movie.', ],

            ['slug'           => 'movie_can_update_poster', 'name' => 'MediaHub - Movies: Can Update Poster',
                'description' => 'User can edit the poster image of a Movie.', ],

            ['slug'           => 'movie_can_update_overview', 'name' => 'MediaHub - Movies: Can Update Overview',
                'description' => 'User can edit the overview of a Movie.', ],

            ['slug'           => 'movie_can_update_tagline', 'name' => 'MediaHub - Movies: Can Update Tagline',
                'description' => 'User can edit the tagline of a Movie.', ],

            ['slug'           => 'movie_can_update_runtime', 'name' => 'MediaHub - Movies: Can Update Runtime',
                'description' => 'User can edit the runtime of a Movie.', ],

            ['slug'           => 'movie_can_update_status', 'name' => 'MediaHub - Movies: Can Update Status',
                'description' => 'User can edit the status of a Movie.', ],

            ['slug'           => 'tv_can_create', 'name' => 'MediaHub - TV: Can Create',
                'description' => 'User can create new TV Show entries.', ],

            ['slug'           => 'tv_can_update', 'name' => 'MediaHub - TV: Can Update',
                'description' => 'User can edit meta data of a TV Show.', ],

            ['slug'           => 'tv_can_update_title', 'name' => 'MediaHub - TV: Can Update Title',
                'description' => 'User can edit the title of a TV Show.', ],

            ['slug'           => 'tv_can_update_original_language', 'name' => 'MediaHub - TV: Can Update Original Language',
                'description' => 'User can edit the originnal language of a TV Show.', ],

            ['slug'           => 'tv_can_update_backdrop', 'name' => 'MediaHub - TV: Can Update Backdrop',
                'description' => 'User can edit the backdrop image of a TV Show.', ],

            ['slug'           => 'tv_can_update_poster', 'name' => 'MediaHub - TV: Can Update Poster',
                'description' => 'User can edit the poster image of a TV Show.', ],

            ['slug'           => 'tv_can_update_overview', 'name' => 'MediaHub - TV: Can Update Overview',
                'description' => 'User can edit the overview of a TV Show.', ],

            ['slug'           => 'tv_can_update_tagline', 'name' => 'MediaHub - TV: Can Update Tagline',
                'description' => 'User can edit the tagline of a TV Show.', ],

            ['slug'           => 'tv_can_update_runtime', 'name' => 'MediaHub - TV: Can Update Runtime',
                'description' => 'User can edit the runtime of a TV Show.', ],

            ['slug'           => 'tv_can_update_status', 'name' => 'MediaHub - TV: Can Update Status',
                'description' => 'User can edit the status of a TV Show.', ],

            ['slug'           => 'users_view_private', 'name' => 'Users: View Private Profiles',
                'description' => 'User can view users private profiles.', ],

            ['slug'           => 'users_view_privileges', 'name' => 'Users: View Privileges',
                'description' => 'User can view users priviliges.', ],

            ['slug'           => 'users_edit_privileges', 'name' => 'Users: Edit Users Privileges',
                'description' => 'User can edit users privileges.', ],

            ['slug'           => 'users_view_infractions', 'name' => 'Users: View Bans and Warnings',
                'description' => 'User can view ban and warning logs of other users .', ],

            ['slug'           => 'users_edit_infractions', 'name' => 'Users: Edit Bans and Warnings',
                'description' => 'User can edit bans and warnings of other users.', ],

            ['slug'           => 'users_give_infractions', 'name' => 'Users: Ban Users and Handout Warnings',
                'description' => 'User can ban and manually warn other users.', ],

            ['slug'           => 'users_view_personal', 'name' => 'Users: View Users Personal Information',
                'description' => 'User can view users personal information.', ],

            ['slug'           => 'users_edit_personal', 'name' => 'Users: Edit Users Personal Information',
                'description' => 'User can edit users personal information.', ],

            ['slug'           => 'users_view_security', 'name' => 'Users: View Users Security Information',
                'description' => 'User can view users security information.', ],

            ['slug'           => 'users_edit_security', 'name' => 'Users: Edit Users Security Information',
                'description' => 'User can edit users security settings.', ],

            ['slug'           => 'users_view_torrents', 'name' => 'Users: View Users Torrent History',
                'description' => 'User can view users torrent history.', ],

            ['slug'           => 'users_view_requests', 'name' => 'Users: View Users Request History',
                'description' => 'User can view users torrent request history.', ],

            ['slug'           => 'users_view_wishlist', 'name' => 'Users: View Users Wishlist',
                'description' => 'User can view users wishlists.', ],

            ['slug'           => 'users_view_invites', 'name' => 'Users: View Users Invites',
                'description' => 'User can view users invite logs.', ],

            ['slug'           => 'users_view_rss', 'name' => 'Users: View RSS Feeds',
                'description' => 'User can view users private RSS feeds.', ],

            ['slug'           => 'users_edit_rss', 'name' => 'Users: Edit RSS Feeds',
                'description' => 'User can edit users private RSS feeds.', ],

            ['slug'           => 'users_bypass_notification_preferences', 'name'=> 'Users: Bypass a User Notification Preferences',
                'description' => 'User can bypass users notification preferences.', ],

            ['slug'           => 'user_special_no_auto_role', 'name'=> 'User: Special No Auto Role',
                'description' => 'User is excempt from auto role command.', ],

            ['slug'           => 'user_special_no_auto_privilege', 'name'=> 'User: Special No Auto Privilege',
                'description' => 'User is excempt from auto privlige command.', ],

            ['slug'           => 'user_special_freeleech', 'name'=> 'User: Special Freeleech',
                'description' => 'User has global freeleech buff.', ],

            ['slug'           => 'user_special_double_upload', 'name'=> 'User: Special Double Upload',
                'description' => 'User has global double upload buff.', ],

            ['slug'           => 'user_special_immune', 'name'=> 'User: Special Immunity to Hit and Runs',
                'description' => 'User is immune to hit and runs.', ],

            ['slug'           => 'user_special_staff', 'name'=> 'User: Special Site Staff',
                'description' => 'Can mark users as Staff. Prevents user from being banned.', ],

            ['slug'           => 'user_can_invite', 'name'=> 'User: Can Invite Users',
                'description' => 'Can generate and send out invites.', ],

            ['slug'           => 'user_can_private_message', 'name'=> 'User: Can Send Private Messages',
                'description' => 'Can send private messages on site.', ],

            ['slug'           => 'user_can_report', 'name'=> 'User: Can Report Items on Site',
                'description' => 'Can create content reports on site.', ],

            ['slug'           => 'user_can_rss', 'name'=> 'User: Can Use RSS Feature',
                'description' => 'Can view public and private RSS feeds.', ],

            ['slug'           => 'helpdesk_can_submit', 'name'=> 'Helpdesk: Can Submit Ticket',
                'description' => 'Can create tickets in helpdesk.', ],

            ['slug'           => 'helpdesk_can_handle', 'name'=> 'Helpdesk: Can Handle Ticket',
                'description' => 'Can moderate tickets in helpdesk.', ],

            ['slug'           => 'helpdesk_can_edit', 'name'=> 'Helpdesk: Can Edit Ticket',
                'description' => 'Can edit tckets in helpdesk.', ],

            ['slug'           => 'helpdesk_can_delete', 'name'=> 'Helpdesk: Can Delete Ticket',
                'description' => 'Can delete tickets in helpdesk.', ],

            ['slug'           => 'can_login', 'name' => 'Global: Can Login',
                'description' => 'User can login to site.', ],

            ['slug'           => 'active_user', 'name' => 'Global: Is Active User',
                'description' => 'Users account is active.', ],

            ['slug'           => 'album_can_view', 'name' => 'Gallery Albums: Can View',
                'description' => 'Can view image gallery.', ],

            ['slug'           => 'album_can_create', 'name' => 'Gallery Albums: Can Create',
                'description' => 'Can create album in gallery.', ],

            ['slug'           => 'album_can_delete', 'name' => 'Gallery Albums: Can Delete',
                'description' => 'Can delete albums in gallary.', ],

            ['slug'           => 'graveyard_can_cancel', 'name' => 'Graveyard: Can Cancel Resurrection',
                'description' => 'Can cancel resurrections in torrent graveyard.', ],

            ['slug'           => 'stats_can_view', 'name' => 'Site Stats: Can View Site Stats Pages',
                'description' => 'Can view etra stats pages.', ],

            ['slug'           => 'store_can_buy_upload', 'name' => 'Store: Can Buy Upload',
                'description' => 'Can buy upload credit from store.', ],

            ['slug'           => 'store_can_buy_freeleech', 'name' => 'Store: Can Buy Freeleech',
                'description' => 'Can buy 24 hour freeleech from store.', ],

            ['slug'           => 'store_can_buy_invites', 'name' => 'Store: Can Buy Invites',
                'description' => 'Can buy invites from store.', ],
        ],
            ['slug'], ['name',
                'description', ]);
        $this->call(RolePrivileges::class);
        Schema::enableForeignKeyConstraints();
    }
}
