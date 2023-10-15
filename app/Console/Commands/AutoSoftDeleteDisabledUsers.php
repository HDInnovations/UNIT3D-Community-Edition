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
use App\Models\FreeleechToken;
use App\Models\Group;
use App\Models\History;
use App\Models\Like;
use App\Models\Message;
use App\Models\Peer;
use App\Models\Post;
use App\Models\PrivateMessage;
use App\Models\Scopes\ApprovedScope;
use App\Models\Thank;
use App\Models\Topic;
use App\Models\Torrent;
use App\Models\User;
use App\Services\Unit3dAnnounce;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Exception;

/**
 * @see \Tests\Unit\Console\Commands\AutoSoftDeleteDisabledUsersTest
 */
class AutoSoftDeleteDisabledUsers extends Command
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
     * @throws Exception
     */
    public function handle(): void
    {
        if (config('pruning.user_pruning')) {
            $disabledGroup = cache()->rememberForever('disabled_group', fn () => Group::where('slug', '=', 'disabled')->pluck('id'));
            $prunedGroup = cache()->rememberForever('pruned_group', fn () => Group::where('slug', '=', 'pruned')->pluck('id'));

            $current = Carbon::now();
            $users = User::where('group_id', '=', $disabledGroup[0])
                ->where('disabled_at', '<', $current->copy()->subDays(config('pruning.soft_delete'))->toDateTimeString())
                ->get();

            foreach ($users as $user) {
                // Send Email
                dispatch(new SendDeleteUserMail($user));

                $user->can_upload = false;
                $user->can_download = false;
                $user->can_comment = false;
                $user->can_invite = false;
                $user->can_request = false;
                $user->can_chat = false;
                $user->group_id = $prunedGroup[0];
                $user->deleted_by = 1;
                $user->save();

                cache()->forget('user:'.$user->passkey);
                Unit3dAnnounce::addUser($user);

                // Removes UserID from Torrents if any and replaces with System UserID (1)
                foreach (Torrent::withoutGlobalScope(ApprovedScope::class)->where('user_id', '=', $user->id)->get() as $tor) {
                    $tor->user_id = 1;
                    $tor->save();
                }

                // Removes UserID from Comments if any and replaces with System UserID (1)
                foreach (Comment::where('user_id', '=', $user->id)->get() as $com) {
                    $com->user_id = 1;
                    $com->save();
                }

                // Removes UserID from Posts if any and replaces with System UserID (1)
                foreach (Post::where('user_id', '=', $user->id)->get() as $post) {
                    $post->user_id = 1;
                    $post->save();
                }

                // Removes UserID from Topic Creators if any and replaces with System UserID (1)
                foreach (Topic::where('first_post_user_id', '=', $user->id)->get() as $topic) {
                    $topic->first_post_user_id = 1;
                    $topic->save();
                }

                // Removes UserID from Topic if any and replaces with System UserID (1)
                foreach (Topic::where('last_post_user_id', '=', $user->id)->get() as $topic) {
                    $topic->last_post_user_id = 1;
                    $topic->save();
                }

                // Removes UserID from PM if any and replaces with System UserID (1)
                foreach (PrivateMessage::where('sender_id', '=', $user->id)->get() as $sent) {
                    $sent->sender_id = 1;
                    $sent->save();
                }

                // Removes UserID from PM if any and replaces with System UserID (1)
                foreach (PrivateMessage::where('receiver_id', '=', $user->id)->get() as $received) {
                    $received->receiver_id = 1;
                    $received->save();
                }

                // Removes all Posts made by User from the shoutbox
                foreach (Message::where('user_id', '=', $user->id)->get() as $shout) {
                    $shout->delete();
                }

                // Removes all likes for user
                foreach (Like::where('user_id', '=', $user->id)->get() as $like) {
                    $like->delete();
                }

                // Removes all thanks for user
                foreach (Thank::where('user_id', '=', $user->id)->get() as $thank) {
                    $thank->delete();
                }

                // Removes all follows for user
                $user->followers()->detach();
                $user->following()->detach();

                // Removes all Peers for user
                foreach (Peer::where('user_id', '=', $user->id)->get() as $peer) {
                    $peer->delete();
                }

                // Remove all History records for user
                foreach (History::where('user_id', '=', $user->id)->get() as $history) {
                    $history->delete();
                }

                // Removes all FL Tokens for user
                foreach (FreeleechToken::where('user_id', '=', $user->id)->get() as $token) {
                    $token->delete();

                    cache()->forget('freeleech_token:'.$token->user_id.':'.$token->torrent_id);
                }

                $user->delete();
            }
        }

        $this->comment('Automated Soft Delete Disabled Users Command Complete');
    }
}
