<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D is open-sourced software licensed under the GNU General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 *
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     Poppabear
 */

namespace App\Http\Controllers\API;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Auth\AuthManager;
use App\Http\Controllers\Controller;
use App\Repositories\ChatRepository;
use App\Http\Resources\ChatRoomResource;
use App\Http\Resources\ChatMessageResource;

class ChatController extends Controller
{
    /**
     * @var ChatRepository
     */
    private $chat;

    /**
     * @var AuthManager
     */
    private $auth;

    /**
     * ChatController constructor.
     *
     * @param ChatRepository $chat
     * @param AuthManager $auth
     */
    public function __construct(ChatRepository $chat, AuthManager $auth)
    {
        $this->chat = $chat;
        $this->auth = $auth;
    }

    /**
     * Return Chat Statuses.
     *
     * @return mixed
     */
    public function statuses()
    {
        return response($this->chat->statuses(), 200);
    }

    /**
     * Return Chatrooms.
     *
     * @return mixed
     */
    public function rooms()
    {
        return ChatRoomResource::collection($this->chat->rooms());
    }

    /**
     * Return Chat Config.
     *
     * @return mixed
     */
    public function config()
    {
        return response($this->chat->config(), 200);
    }

    /**
     * Return Chatroom Messages.
     *
     * @param $room_id
     *
     * @return mixed
     */
    public function messages($room_id)
    {
        return ChatMessageResource::collection($this->chat->messages($room_id));
    }

    /**
     * Create A New Message.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return ChatMessageResource
     */
    public function createMessage(Request $request)
    {
        $user_id = $request->get('user_id');
        $room_id = $request->get('chatroom_id');
        $message = $request->get('message');
        $save = $request->get('save');

        if ($this->auth->user()->id !== $user_id) {
            return response('error', 401);
        }

        if ($this->auth->user()->can_chat === 0) {
            return response('error', 401);
        }

        // Temp Fix For HTMLPurifier
        if ($message === '<') {
            return response('error', 401);
        }

        $message = $this->chat->message($user_id, $room_id, $message);

        if (! $save) {
            $message->delete();
        }

        return $save ? new ChatMessageResource($message) : response('success', 200);
    }

    /**
     * Delete A Message.
     *
     * @param $id
     *
     * @return mixed
     */
    public function deleteMessage($id)
    {
        $this->chat->deleteMessage($id);

        return response('success', 200);
    }

    /**
     * Update A Users Chat Status.
     *
     * @param \Illuminate\Http\Request $request
     * @param $id
     *
     * @return mixed
     */
    public function updateUserChatStatus(Request $request, $id)
    {
        $user = User::with(['chatStatus', 'chatroom', 'group'])->findOrFail($id);
        $status = $this->chat->statusFindOrFail($request->get('status_id'));

        $user->chatStatus()->dissociate();
        $user->chatStatus()->associate($status);

        $user->save();

        return response($user, 200);
    }

    /**
     * Update A Users Chatroom.
     *
     * @param \Illuminate\Http\Request $request
     * @param $id
     *
     * @return mixed
     */
    public function updateUserRoom(Request $request, $id)
    {
        $user = User::with(['chatStatus', 'chatroom', 'group'])->findOrFail($id);
        $room = $this->chat->roomFindOrFail($request->get('room_id'));

        $user->chatroom()->dissociate();
        $user->chatroom()->associate($room);

        $user->save();

        return response($user, 200);
    }
}
