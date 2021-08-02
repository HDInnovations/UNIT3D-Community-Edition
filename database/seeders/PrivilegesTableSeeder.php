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
                'description' => 'This allow the user to access the Site Toolss dashboard panel. The user will see the modules they are privileged to use only.', ],

            ['slug'           => 'dashboard_can_backup', 'name' => 'Site Tools: Can Run & Access Backups',
                'description' => '', ],

            ['slug'           => 'dashboard_can_commands', 'name' => 'Site Tools: Can Run Server Commands',
                'description' => '', ],

            ['slug'           => 'dashboard_can_chat_statuses', 'name' => 'Site Tools: Can Manage Chat Statuses',
                'description' => '', ],

            ['slug'           => 'dashboard_can_chat_rooms', 'name' => 'Site Tools: Can Manage Chat Rooms',
                'description' => '', ],

            ['slug'           => 'dashboard_can_chat_bots', 'name' => 'Site Tools: Can Manage Chat Bots',
                'description' => '', ],

            ['slug'           => 'dashboard_can_flush_chat', 'name' => 'Site Tools: Can Flush Chat',
                'description' => '', ],

            ['slug'           => 'dashboard_can_articles', 'name' => 'Site Tools: Can Manage Articles',
                'description' => '', ],

            ['slug'           => 'dashboard_can_applications', 'name' => 'Site Tools: Can Moderate Applications',
                'description' => '', ],

            ['slug'           => 'dashboard_can_forums', 'name' => 'Site Tools: Can Manage Forums',
                'description' => '', ],

            ['slug'           => 'dashboard_can_pages', 'name' => 'Site Tools: Can Manage Pages',
                'description' => '', ],

            ['slug'           => 'dashboard_can_polls', 'name' => 'Site Tools: Can Manage Polls',
                'description' => '', ],

            ['slug'           => 'dashboard_can_rss', 'name' => 'Site Tools: Can Manage RSS',
                'description' => '', ],

            ['slug'           => 'dashboard_can_torrent_moderation', 'name' => 'Site Tools: Can Access Torrent Moderation',
                'description' => '', ],

            ['slug'           => 'dashboard_can_torrent_categories', 'name' => 'Site Tools: Can Manage Torrent Categories',
                'description' => '', ],

            ['slug'           => 'dashboard_can_torrent_type', 'name' => 'Site Tools: Can Manage Torrent Types',
                'description' => '', ],

            ['slug'           => 'dashboard_can_torrent_resolutions', 'name' => 'Site Tools: Can Manage Torrent Resolutions',
                'description' => '', ],

            ['slug'           => 'dashboard_can_media_languages', 'name' => 'Site Tools: Can Manage Media Languages',
                'description' => '', ],

            ['slug'           => 'dashboard_can_flush_ghost_peers', 'name' => 'Site Tools: Can Flush Ghost Peers',
                'description' => '', ],

            ['slug'           => 'dashboard_can_user_search', 'name' => 'Site Tools: Can Search Users',
                'description' => '', ],

            ['slug'           => 'dashboard_can_user_watchlist', 'name' => 'Site Tools: Can Manage User Watchlist',
                'description' => '', ],

            ['slug'           => 'dashboard_can_user_gift', 'name' => 'Site Tools: Can Give Gifts to Users',
                'description' => '', ],

            ['slug'           => 'dashboard_can_mass_pm', 'name' => 'Site Tools: Can Sen A Mass Private Message',
                'description' => '', ],

            ['slug'           => 'dashboard_can_mass_validate', 'name' => 'Site Tools: Can Mass Validate Users',
                'description' => '', ],

            ['slug'           => 'dashboard_can_cheaters', 'name' => 'Site Tools: Can Manage Possible Cheaters',
                'description' => '', ],

            ['slug'           => 'dashboard_can_seedboxes', 'name' => 'Site Tools: Can Manage Registered Seedboxes',
                'description' => '', ],

            ['slug'           => 'dashboard_can_audit_log', 'name' => 'Site Tools: Can Access Audit Log',
                'description' => '', ],

            ['slug'           => 'dashboard_can_ban_log', 'name' => 'Site Tools: Can Access Ban Log',
                'description' => '', ],

            ['slug'           => 'dashboard_can_failed_login_log', 'name' => 'Site Tools: Can Access Failed Login Log',
                'description' => '', ],

            ['slug'           => 'dashboard_can_invites_log', 'name' => 'Site Tools: Can Access Invites Log',
                'description' => '', ],

            ['slug'           => 'dashboard_can_user_notes_log', 'name' => 'Site Tools: Can Access User Notes Log',
                'description' => '', ],

            ['slug'           => 'dashboard_can_reports_log', 'name' => 'Site Tools: Can Access Reports Log',
                'description' => '', ],

            ['slug'           => 'dashboard_can_warning_log', 'name' => 'Site Tools: Can Access Warning Log',
                'description' => '', ],
            
            ['slug'           => 'torrent_can_view', 'name' => 'Torrents: Can Search & View',
                'description' => 'Can Search and View Torrents on The Site', ],

            ['slug'           => 'torrent_can_create', 'name' => 'Torrents: Can Create/Upload',
                'description' => 'User Can Create a New Torrent on the Site', ],

            ['slug'           => 'torrent_can_bypass_modq', 'name' => 'Torrents: Bypass Moderation',
                'description' => 'When a User Creates a New Torrent, the Torrent will bypass the Moderation Queue', ],

            ['slug'           => 'torrent_can_moderate', 'name' => 'Torrents: Can Moderate Torrents',
                'description' => 'User can Approve, Postpone, or Reject Torrents - Both in Moderation Queue and On-Site', ],

            ['slug'           => 'torrent_can_update', 'name' => 'Torrents: Can Edit Torrents',
                'description' => 'Can Edit the Fields which have been granted permission. This grants access to the necessary pages.', ],

            ['slug'           => 'torrent_can_update_title', 'name' => 'Torrents: Can Edit Titles',
                'description' => 'Can Edit Torrent Titles', ],

            ['slug'           => 'torrent_can_update_description', 'name' => 'Torrents: Can Edit Descriptions',
                'description' => ' ', ],

            ['slug'           => 'torrent_can_update_mediainfo', 'name' => 'Torrents: Can Edit MediaInfo',
                'description' => ' ', ],

            ['slug'           => 'torrent_can_update_category', 'name' => 'Torrents: Can Edit Category',
                'description' => ' ', ],

            ['slug'           => 'torrent_can_update_type', 'name' => 'Torrents: Can Edit Type',
                'description' => ' ', ],

            ['slug'           => 'torrent_can_update_resolution', 'name' => 'Torrents: Can Edit Resolution',
                'description' => ' ', ],

            ['slug'           => 'torrent_can_update_meta_ids', 'name' => 'Torrents: Can Edit Meta Id\'s',
                'description' => ' ', ],

            ['slug'           => 'torrent_can_update_special', 'name' => 'Torrents: Can Edit Special Attributes',
                'description' => ' ', ],

            ['slug'           => 'torrent_can_delete', 'name' => 'Torrents: Can Delete',
                'description' => ' ', ],

            ['slug'           => 'torrent_can_download', 'name' => 'Torrents: Can Download',
                'description' => ' ', ],

            ['slug'           => 'torrent_can_request_reseed', 'name' => 'Torrents: Can Request Reseed',
                'description' => ' ', ],

            ['slug'           => 'torrent_can_bump', 'name' => 'Torrents: Can Bump',
                'description' => ' ', ],

            ['slug'           => 'torrent_can_internal', 'name' => 'Torrents: Can Internal',
                'description' => ' ', ],

            ['slug'           => 'torrent_can_freeleech', 'name' => 'Torrents: Can Freeleech',
                'description' => ' ', ],

            ['slug'           => 'torrent_can_doubleupload', 'name' => 'Torrents: Can Double Upload',
                'description' => ' ', ],

            ['slug'           => 'torrent_can_personal_release', 'name' => 'Torrents: Can Personal Release',
                'description' => ' ', ],

            ['slug'           => 'torrent_can_sticky', 'name' => 'Torrents: Can Sticky',
                'description' => ' ', ],

            ['slug'           => 'torrent_can_feature', 'name' => 'Torrents: Can Feature',
                'description' => ' ', ],

            ['slug'           => 'torrent_can_revoke_feature', 'name' => 'Torrents: Can Revoke Feature',
                'description' => ' ', ],

            ['slug'           => 'request_can_view', 'name' => 'Requests: Can View',
                'description' => ' ', ],

            ['slug'           => 'request_can_create', 'name' => 'Requests: Can Create',
                'description' => ' ', ],

            ['slug'           => 'request_can_update', 'name' => 'Requests: Can Edit',
                'description' => ' ', ],

            ['slug'           => 'request_can_update_title', 'name' => 'Requests: Can Edit Title',
                'description' => ' ', ],

            ['slug'           => 'request_can_update_description', 'name' => 'Requests: Can Edit Description',
                'description' => ' ', ],

            ['slug'           => 'request_can_update_category', 'name' => 'Requests: Can Update Category',
                'description' => ' ', ],

            ['slug'           => 'request_can_update_type', 'name' => 'Requests: Can Update Type',
                'description' => ' ', ],

            ['slug'           => 'request_can_update_resolution', 'name' => 'Requests: Can Update Resolution',
                'description' => ' ', ],

            ['slug'           => 'request_can_update_meta_ids', 'name' => 'Requests: Can Update Meta Id\'s',
                'description' => ' ', ],

            ['slug'           => 'request_can_unclaim', 'name' => 'Requests: Can Remove Claiming User',
                'description' => ' ', ],

            ['slug'           => 'request_can_delete', 'name' => 'Requests: Can Delete',
                'description' => ' ', ],

            ['slug'           => 'request_can_approve', 'name' => 'Requests: Can Approve',
                'description' => ' ', ],

            ['slug'           => 'request_can_deny', 'name' => 'Requests: Can Deny',
                'description' => ' ', ],

            ['slug'           => 'request_can_reset', 'name' => 'Requests: Can Reset',
                'description' => ' ', ],

            ['slug'           => 'comment_can_view', 'name' => 'Comments: Can View',
                'description' => ' ', ],

            ['slug'           => 'comment_can_create', 'name' => 'Comments: Can Create',
                'description' => ' ', ],

            ['slug'           => 'comment_can_update', 'name' => 'Comments: Can Update',
                'description' => ' ', ],

            ['slug'           => 'comment_can_delete', 'name' => 'Comments: Can Delete',
                'description' => ' ', ],

            ['slug'           => 'chat_can_moderate', 'name' => 'Chat: Can Moderate',
                'description' => ' ', ],

            ['slug'           => 'comments_can_moderate', 'name' => 'Comments: Can Moderate',
                'description' => ' ', ],

            ['slug'           => 'forums_can_view', 'name' => 'Forums (Global): Can View',
                'description' => ' ', ],

            ['slug'           => 'forums_can_sticky', 'name' => 'Forums (Global): Can Sticky/Pin',
                'description' => ' ', ],

            ['slug'           => 'forums_can_bump', 'name' => 'Forums (Global): Can Bump',
                'description' => ' ', ],

            ['slug'           => 'forums_can_create_topic', 'name' => 'Forums (Global): Can Create Topic',
                'description' => ' ', ],

            ['slug'           => 'forums_can_edit_topic', 'name' => 'Forums (Global): Can Edit Topic',
                'description' => ' ', ],

            ['slug'           => 'forums_can_delete_topic', 'name' => 'Forums (Global): Can Delete Topic',
                'description' => ' ', ],

            ['slug'           => 'forums_can_moderate', 'name' => 'Forums (Global): Can Moderate',
                'description' => ' ', ],

            ['slug'           => 'forums_can_comment', 'name' => 'Forums (Global): Can Comment',
                'description' => ' ', ],

            ['slug'           => 'forums_can_update_comment', 'name' => 'Forums (Global): Can Update Comment',
                'description' => ' ', ],

            ['slug'           => 'forums_can_delete_comment', 'name' => 'Forums (Global): Can Delete Comment',
                'description' => ' ', ],

            ['slug'           => 'forums_sudo', 'name' => 'Forums: Sudo Forums',
                'description' => 'Show Forum, Read Topic, Reply Topic, and Start Topic Privilege on All Forums', ],

            ['slug'           => 'playlist_can_view', 'name' => 'Playlists: Can View',
                'description' => ' ', ],

            ['slug'           => 'playlist_can_create', 'name' => 'Playlists: Can Create',
                'description' => ' ', ],

            ['slug'           => 'playlist_can_update', 'name' => 'Playlists: Can Update',
                'description' => ' ', ],

            ['slug'           => 'playlist_can_delete', 'name' => 'Playlists: Can Delete',
                'description' => ' ', ],

            ['slug'           => 'playlist_can_download_all', 'name' => 'Playlists: Can Download All',
                'description' => ' ', ],

            ['slug'           => 'polls_can_view', 'name' => 'Polls: Can View',
                'description' => ' ', ],

            ['slug'           => 'polls_can_vote', 'name' => 'Polls: Can Vote',
                'description' => ' ', ],

            ['slug'           => 'polls_can_create', 'name' => 'Polls: Can Create',
                'description' => ' ', ],

            ['slug'           => 'polls_can_delete', 'name' => 'Polls: Can Delete',
                'description' => ' ', ],

            ['slug'           => 'subtitle_can_view', 'name' => 'Subtitled: Can View',
                'description' => ' ', ],

            ['slug'           => 'subtitle_can_create', 'name' => 'Subtitled: Can Create',
                'description' => ' ', ],

            ['slug'           => 'subtitle_can_update', 'name' => 'Subtitled: Can Update',
                'description' => ' ', ],

            ['slug'           => 'subtitle_can_delete', 'name' => 'Subtitled: Can Delete',
                'description' => ' ', ],

            ['slug'           => 'subtitle_can_download', 'name' => 'Subtitled: Can Download',
                'description' => ' ', ],

            ['slug'           => 'movie_can_create', 'name' => 'MediaHub - Movies: Can Create',
                'description' => ' ', ],

            ['slug'           => 'movie_can_update', 'name' => 'MediaHub - Movies: Can Update',
                'description' => ' ', ],

            ['slug'           => 'movie_can_update_title', 'name' => 'MediaHub - Movies: Can Update Title',
                'description' => ' ', ],

            ['slug'           => 'movie_can_update_original_language', 'name' => 'MediaHub - Movies: Can Update Original Language',
                'description' => ' ', ],

            ['slug'           => 'movie_can_update_backdrop', 'name' => 'MediaHub - Movies: Can Update Backdrop',
                'description' => ' ', ],

            ['slug'           => 'movie_can_update_poster', 'name' => 'MediaHub - Movies: Can Update Poster',
                'description' => ' ', ],

            ['slug'           => 'movie_can_update_overview', 'name' => 'MediaHub - Movies: Can Update Overview',
                'description' => ' ', ],

            ['slug'           => 'movie_can_update_tagline', 'name' => 'MediaHub - Movies: Can Update Yagline',
                'description' => ' ', ],

            ['slug'           => 'movie_can_update_runtime', 'name' => 'MediaHub - Movies: Can Update Runtime',
                'description' => ' ', ],

            ['slug'           => 'movie_can_update_status', 'name' => 'MediaHub - Movies: Can Update Status',
                'description' => ' ', ],

            ['slug'           => 'tv_can_create', 'name' => 'MediaHub - TV: Can Create',
                'description' => ' ', ],

            ['slug'           => 'tv_can_update', 'name' => 'MediaHub - TV: Can Update',
                'description' => ' ', ],

            ['slug'           => 'tv_can_update_title', 'name' => 'MediaHub - TV: Can UUpdate Title',
                'description' => ' ', ],

            ['slug'           => 'tv_can_update_original_language', 'name' => 'MediaHub - TV: Can Update Original Language',
                'description' => ' ', ],

            ['slug'           => 'tv_can_update_backdrop', 'name' => 'MediaHub - TV: Can Update Backdrop',
                'description' => ' ', ],

            ['slug'           => 'tv_can_update_poster', 'name' => 'MediaHub - TV: Can Update Poster',
                'description' => ' ', ],

            ['slug'           => 'tv_can_update_overview', 'name' => 'MediaHub - TV: Can Update Overview',
                'description' => ' ', ],

            ['slug'           => 'tv_can_update_tagline', 'name' => 'MediaHub - TV: Can Update Tagline',
                'description' => ' ', ],

            ['slug'           => 'tv_can_update_runtime', 'name' => 'MediaHub - TV: Can Update Runtime',
                'description' => ' ', ],

            ['slug'           => 'tv_can_update_status', 'name' => 'MediaHub - TV: Can Update Status',
                'description' => ' ', ],

            ['slug'           => 'users_view_private', 'name' => 'Users: View Private Profiles',
                'description' => ' ', ],

            ['slug'           => 'users_view_privileges', 'name' => 'Users: View Privileges',
                'description' => ' ', ],

            ['slug'           => 'users_edit_privileges', 'name' => 'Users: Edit Users Privileges',
                'description' => ' ', ],

            ['slug'           => 'users_view_infractions', 'name' => 'Users: View Bans and Warnings',
                'description' => ' ', ],

            ['slug'           => 'users_edit_infractions', 'name' => 'Users: Edit Bans and Warnings',
                'description' => ' ', ],

            ['slug'           => 'users_give_infractions', 'name' => 'Users: Ban Users and Handout Warnings',
                'description' => ' ', ],

            ['slug'           => 'users_view_personal', 'name' => 'Users: View Users Personal Information',
                'description' => ' ', ],

            ['slug'           => 'users_edit_personal', 'name' => 'Users: Edit Users Personal Information',
                'description' => ' ', ],

            ['slug'           => 'users_view_security', 'name' => 'Users: View Users Security Information',
                'description' => ' ', ],

            ['slug'           => 'users_edit_security', 'name' => 'Users: Edit Users Security Information',
                'description' => ' ', ],

            ['slug'           => 'users_view_torrents', 'name' => 'Users: View Users Torrent History',
                'description' => ' ', ],

            ['slug'           => 'users_view_requests', 'name' => 'Users: View Users Request History',
                'description' => ' ', ],

            ['slug'           => 'users_view_wishlist', 'name' => 'Users: View Users Wishlist',
                'description' => ' ', ],

            ['slug'           => 'users_view_invites', 'name' => 'Users: View Users Invites',
                'description' => ' ', ],

            ['slug'           => 'users_view_rss', 'name' => 'Users: View RSS Feeds',
                'description' => ' ', ],

            ['slug'           => 'users_edit_rss', 'name' => 'Users: Edit RSS Feeds',
                'description' => ' ', ],

            ['slug'           => 'users_bypass_notification_preferences', 'name'=> 'Users: Bypass a User Notification Preferences',
                'description' => ' ', ],

            ['slug'           => 'user_special_freeleech', 'name'=> 'User: Special Freeleech',
                'description' => ' ', ],

            ['slug'           => 'user_special_double_upload', 'name'=> 'User: Special Double Upload',
                'description' => ' ', ],

            ['slug'           => 'user_special_immune', 'name'=> 'User: Special Immunity to Hit and Runs',
                'description' => 'User is Immune to Hit and Runs', ],

            ['slug'           => 'user_special_staff', 'name'=> 'User: Special Site Staff',
                'description' => 'Marks User as Staff. Prevents User from being banned.', ],

            ['slug'           => 'user_can_invite', 'name'=> 'User: Can Invite Users',
                'description' => ' ', ],

            ['slug'           => 'user_can_private_message', 'name'=> 'User: Can Send Private Messages',
                'description' => ' ', ],

            ['slug'           => 'user_can_report', 'name'=> 'User: Can Report Items on Site',
                'description' => ' ', ],

            ['slug'           => 'user_can_rss', 'name'=> 'User: Can Use RSS Feature',
                'description' => ' ', ],

            ['slug'           => 'helpdesk_can_submit', 'name'=> 'Helpdesk: Can Submit Ticket',
                'description' => ' ', ],

            ['slug'           => 'helpdesk_can_handle', 'name'=> 'Helpdesk: Can Handle Ticket',
                'description' => ' ', ],

            ['slug'           => 'helpdesk_can_edit', 'name'=> 'Helpdesk: Can Edit Ticket',
                'description' => ' ', ],

            ['slug'           => 'helpdesk_can_delete', 'name'=> 'Helpdesk: Can Delete Ticket',
                'description' => ' ', ],

            ['slug'           => 'can_login', 'name' => 'Global: Can Login',
                'description' => ' ', ],

            ['slug'           => 'active_user', 'name' => 'Global: Is Active User',
                'description' => ' ', ],

            ['slug'           => 'album_can_view', 'name' => 'Gallery Albums: Can View',
                'description' => ' ', ],

            ['slug'           => 'album_can_create', 'name' => 'Gallery Albums: Can Create',
                'description' => ' ', ],

            ['slug'           => 'album_can_delete', 'name' => 'Gallery Albums: Can Delete',
                'description' => ' ', ],

            ['slug'           => 'graveyard_can_cancel', 'name' => 'Graveyard: Can Cancel Resurrection',
                'description' => ' ', ],

            ['slug'           => 'stats_can_view', 'name' => 'Site Stats: Can View Site Stats Pages',
                'description' => ' ', ],
        ],
            ['slug'], ['name',
                'description', ]);
        $this->call(RolePrivileges::class);
        Schema::enableForeignKeyConstraints();
    }
}
