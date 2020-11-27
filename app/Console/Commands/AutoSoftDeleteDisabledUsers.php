<?php
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

namespace App\Console\Commands;

use App\Jobs\SendDeleteUserMail;
use App\Models\Comment;
use App\Models\Follow;
use App\Models\Group;
use App\Models\Invite;
use App\Models\Like;
use App\Models\Message;
use App\Models\Note;
use App\Models\Peer;
use App\Models\Post;
use App\Models\PrivateMessage;
use App\Models\Thank;
use App\Models\Topic;
use App\Models\Torrent;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
/**
 * @see \Tests\Unit\Console\Commands\AutoSoftDeleteDisabledUsersTest
 */
class AutoSoftDeleteDisabledUsers extends \Illuminate\Console\Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auto:softdelete_disabled_users';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'User account must be In disabled group for at least x days';
    /**
     * Execute the console command.
     *
     * @throws \Exception
     *
     * @return mixed
     */
    public function handle()
    {
        if (\config('pruning.user_pruning') == true) {
            $disabled_group = \cache()->rememberForever('disabled_group', fn() => \App\Models\Group::where('slug', '=', 'disabled')->pluck('id'));
            $pruned_group = \cache()->rememberForever('pruned_group', fn() => \App\Models\Group::where('slug', '=', 'pruned')->pluck('id'));
            $current = \Carbon\Carbon::now();
            $users = \App\Models\User::where('group_id', '=', $disabled_group[0])->where('disabled_at', '<', $current->copy()->subDays(\config('pruning.soft_delete'))->toDateTimeString())->get();
            foreach ($users as $user) {
                // Send Email
                \dispatch(new \App\Jobs\SendDeleteUserMail($user));
                $user->can_upload = 0;
                $user->can_download = 0;
                $user->can_comment = 0;
                $user->can_invite = 0;
                $user->can_request = 0;
                $user->can_chat = 0;
                $user->group_id = $pruned_group[0];
                $user->deleted_by = 1;
                $user->save();
                // Removes UserID from Torrents if any and replaces with System UserID (1)
                foreach (\App\Models\Torrent::withAnyStatus()->where('user_id', '=', $user->id)->get() as $tor) {
                    $tor->user_id = 1;
                    $tor->save();
                }
                // Removes UserID from Comments if any and replaces with System UserID (1)
                foreach (\App\Models\Comment::where('user_id', '=', $user->id)->get() as $com) {
                    $com->user_id = 1;
                    $com->save();
                }
                // Removes UserID from Posts if any and replaces with System UserID (1)
                foreach (\App\Models\Post::where('user_id', '=', $user->id)->get() as $post) {
                    $post->user_id = 1;
                    $post->save();
                }
                // Removes UserID from Topic Creators if any and replaces with System UserID (1)
                foreach (\App\Models\Topic::where('first_post_user_id', '=', $user->id)->get() as $topic) {
                    $topic->first_post_user_id = 1;
                    $topic->save();
                }
                // Removes UserID from Topic if any and replaces with System UserID (1)
                foreach (\App\Models\Topic::where('last_post_user_id', '=', $user->id)->get() as $topic) {
                    $topic->last_post_user_id = 1;
                    $topic->save();
                }
                // Removes UserID from PM if any and replaces with System UserID (1)
                foreach (\App\Models\PrivateMessage::where('sender_id', '=', $user->id)->get() as $sent) {
                    $sent->sender_id = 1;
                    $sent->save();
                }
                // Removes UserID from PM if any and replaces with System UserID (1)
                foreach (\App\Models\PrivateMessage::where('receiver_id', '=', $user->id)->get() as $received) {
                    $received->receiver_id = 1;
                    $received->save();
                }
                // Removes all Posts made by User from the shoutbox
                foreach (\App\Models\Message::where('user_id', '=', $user->id)->get() as $shout) {
                    $shout->delete();
                }
                // Removes all notes for user
                foreach (\App\Models\Note::where('user_id', '=', $user->id)->get() as $note) {
                    $note->delete();
                }
                // Removes all likes for user
                foreach (\App\Models\Like::where('user_id', '=', $user->id)->get() as $like) {
                    $like->delete();
                }
                // Removes all thanks for user
                foreach (\App\Models\Thank::where('user_id', '=', $user->id)->get() as $thank) {
                    $thank->delete();
                }
                // Removes all follows for user
                foreach (\App\Models\Follow::where('user_id', '=', $user->id)->get() as $follow) {
                    $follow->delete();
                }
                // Removes UserID from Sent Invites if any and replaces with System UserID (1)
                foreach (\App\Models\Invite::where('user_id', '=', $user->id)->get() as $sent_invite) {
                    $sent_invite->user_id = 1;
                    $sent_invite->save();
                }
                // Removes UserID from Received Invite if any and replaces with System UserID (1)
                foreach (\App\Models\Invite::where('accepted_by', '=', $user->id)->get() as $received_invite) {
                    $received_invite->accepted_by = 1;
                    $received_invite->save();
                }
                // Removes all Peers for user
                foreach (\App\Models\Peer::where('user_id', '=', $user->id)->get() as $peer) {
                    $peer->delete();
                }
                $user->delete();
            }
        }
        $this->comment('Automated Soft Delete Disabled Users Command Complete');
    }
}
