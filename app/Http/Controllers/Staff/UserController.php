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

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Torrent;
use App\User;
use App\Group;
use App\Comment;
use App\Post;
use App\Topic;
use App\PrivateMessage;
use App\Note;
use App\Message;
use App\Like;
use App\Thank;
use App\Follow;
use \Toastr;

class UserController extends Controller
{
    /**
     * Users List
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function members()
    {
        $users = User::latest()->paginate(25);
        $uploaders = User::where('group_id', 7)->latest()->paginate(25);
        $mods = User::where('group_id', 6)->latest()->paginate(25);
        $admins = User::where('group_id', 4)->latest()->paginate(25);
        $coders = User::where('group_id', 10)->latest()->paginate(25);

        return view('Staff.user.user_search', [
            'users' => $users,
            'uploaders' => $uploaders,
            'mods' => $mods,
            'admins' => $admins,
            'coders' => $coders
        ]);
    }

    /**
     * Search For A User
     *
     * @access public
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function userSearch(Request $request)
    {
        $users = User::where([
            ['username', 'like', '%' . $request->input('username') . '%'],
        ])->paginate(25);
        $users->setPath('?username=' . $request->input('username'));

        return view('Staff.user.user_results', ['users' => $users]);
    }

    /**
     * User Edit Form
     *
     * @param $username
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function userSettings($username, $id)
    {
        $user = User::findOrFail($id);
        $groups = Group::all();
        $notes = Note::where('user_id', $id)->latest()->paginate(25);

        return view('Staff.user.user_edit', [
            'user' => $user,
            'groups' => $groups,
            'notes' => $notes
        ]);
    }

    /**
     * Edit A User
     *
     * @param \Illuminate\Http\Request $request
     * @param $username
     * @param $id
     * @@return Illuminate\Http\RedirectResponse
     */
    public function userEdit(Request $request, $username, $id)
    {
        $user = User::findOrFail($id);
        $staff = auth()->user();

        $user->username = $request->input('username');
        $user->email = $request->input('email');
        $user->uploaded = $request->input('uploaded');
        $user->downloaded = $request->input('downloaded');
        $user->about = $request->input('about');
        $user->group_id = (int)$request->input('group_id');
        $user->save();

        // Activity Log
        \LogActivity::addToLog("Staff Member {$staff->username} has edited {$user->username} account.");

        return redirect()->route('profile', ['username' => $user->username, 'id' => $user->id])
            ->with(Toastr::success('Account Was Updated Successfully!', 'Yay!', ['options']));
    }

    /**
     * Edit A Users Permissions
     *
     * @param \Illuminate\Http\Request $request
     * @param $username
     * @param $id
     * @return Illuminate\Http\RedirectResponse
     */
    public function userPermissions(Request $request, $username, $id)
    {
        $user = User::findOrFail($id);
        $staff = auth()->user();

        $user->can_upload = $request->input('can_upload');
        $user->can_download = $request->input('can_download');
        $user->can_comment = $request->input('can_comment');
        $user->can_invite = $request->input('can_invite');
        $user->can_request = $request->input('can_request');
        $user->can_chat = $request->input('can_chat');
        $user->save();

        // Activity Log
        \LogActivity::addToLog("Staff Member {$staff->username} has edited {$user->username} account permissions.");

        return redirect()->route('profile', ['username' => $user->username, 'id' => $user->id])
            ->with(Toastr::success('Account Permissions Succesfully Edited', 'Yay!', ['options']));
    }

    /**
     * Edit A Users Password
     *
     * @param \Illuminate\Http\Request $request
     * @param $username
     * @param $id
     * @return Illuminate\Http\RedirectResponse
     */
    protected function userPassword(Request $request, $username, $id)
    {
        $user = User::findOrFail($id);
        $staff = auth()->user();

        $new_password = $request->input('new_password');
        $user->password = Hash::make($new_password);
        $user->save();

        // Activity Log
        \LogActivity::addToLog("Staff Member {$staff->username} has changed {$user->username} password.");

        return redirect()->route('profile', ['username' => $user->username, 'id' => $user->id])
            ->with(Toastr::success('Account Password Was Updated Successfully!', 'Yay!', ['options']));
    }

    /**
     * Delete A User
     *
     * @param $username
     * @param $id
     * @return Illuminate\Http\RedirectResponse
     */
    protected function userDelete($username, $id)
    {
        $user = User::findOrFail($id);
        $staff = auth()->user();

        if ($user->group->is_modo || auth()->user()->id == $user->id) {
            return redirect()->route('home')
                ->with(Toastr::error('You Cannot Delete Yourself Or Other Staff', 'Whoops!', ['options']));
        } else {
            // Removes UserID from Torrents if any and replaces with System UserID (0)
            foreach (Torrent::where('user_id', $user->id)->get() as $tor) {
                $tor->user_id = 1;
                $tor->save();
            }
            // Removes UserID from Comments if any and replaces with System UserID (0)
            foreach (Comment::where('user_id', $user->id)->get() as $com) {
                $com->user_id = 1;
                $com->save();
            }
            // Removes UserID from Posts if any and replaces with System UserID (0)
            foreach (Post::where('user_id', $user->id)->get() as $post) {
                $post->user_id = 1;
                $post->save();
            }
            // Removes UserID from Topic Creators if any and replaces with System UserID (0)
            foreach (Topic::where('first_post_user_id', $user->id)->get() as $topic) {
                $topic->first_post_user_id = 1;
                $topic->save();
            }
            // Removes UserID from Topic if any and replaces with System UserID (0)
            foreach (Topic::where('last_post_user_id', $user->id)->get() as $topic) {
                $topic->last_post_user_id = 1;
                $topic->save();
            }
            // Removes UserID from PM if any and replaces with System UserID (0)
            foreach (PrivateMessage::where('sender_id', $user->id)->get() as $sent) {
                $sent->sender_id = 1;
                $sent->save();
            }
            // Removes UserID from PM if any and replaces with System UserID (0)
            foreach (PrivateMessage::where('receiver_id', $user->id)->get() as $received) {
                $received->receiver_id = 1;
                $received->save();
            }
            // Removes all Posts made by User from the shoutbox
            foreach (Message::where('user_id', $user->id)->get() as $shout) {
                $shout->delete();
            }
            // Removes all notes for user
            foreach (Note::where('user_id', $user->id)->get() as $note) {
                $note->delete();
            }
            // Removes all likes for user
            foreach (Like::where('user_id', $user->id)->get() as $like) {
                $like->delete();
            }
            // Removes all thanks for user
            foreach (Thank::where('user_id', $user->id)->get() as $thank) {
                $thank->delete();
            }
            // Removes all follows for user
            foreach (Follow::where('user_id', $user->id)->get() as $follow) {
                $follow->delete();
            }

            // Activity Log
            \LogActivity::addToLog("Staff Member {$staff->username} has deleted {$user->username} account.");

            if ($user->delete()) {
                return redirect('staff_dashboard')
                    ->with(Toastr::success('Account Has Been Removed', 'Yay!', ['options']));
            } else {
                return redirect('staff_dashboard')
                    ->with(Toastr::error('Something Went Wrong!', 'Whoops!', ['options']));
            }
        }
    }

    /**
     * Mass Validate Unvalidated Users
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function massValidateUsers()
    {
        $users = User::where('active', '=', 0)->where('group_id', '=', 1)->get();

        foreach ($users as $user) {
            $user->group_id = 3;
            $user->active = 1;
            $user->save();
        }
        return redirect('staff_dashboard')
            ->with(Toastr::success('Unvalidated Accounts Are Now Validated', 'Yay!', ['options']));
    }
}
