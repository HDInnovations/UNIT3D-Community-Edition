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

namespace App\Http\Controllers;

use App\Chatroom;
use App\Message;
use App\Events\MessageSent;
use Illuminate\Http\Request;

class ChatController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show chats
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('blocks.chat');
    }

    /**
     * Fetch all chatrooms
     *
     * @return chatrooms
     */
    public function fetchChatrooms()
    {
        return Chatroom::all();
    }

    /**
     * Change current chatroom.
     *
     * @return
     */
    public function changeChatroom(Request $request)
    {
        $user = auth()->user();
        $fields = $request->input('chatroom');
        $chatroom = Chatroom::findOrFail($fields['id']);

        // update users current chatroom
        $user->chatroom()
            ->associate($chatroom)
            ->save();
    }

    /**
     * Fetch all messages in the current users chatroom.
     *
     * @return Message
     */
    public function fetchMessages()
    {
        $user = auth()->user();
        $chatroom = Chatroom::findOrFail($user->chatroom_id);

        $messages = Message::where('chatroom_id', '=', $chatroom->id)->with('user')->get();

        return $messages;
    }

    /**
     * Persist message to database
     *
     * @param Request $request
     * @return Response
     */
    public function sendMessage(Request $request)
    {
        $user = auth()->user();
        $chatroom = Chatroom::findOrFail($user->chatroom_id);

        $message = new Message([
            'message' => $request->input('message')
        ]);

        $message->chatroom()->associate($chatroom);
        $message->user()
            ->associate($user)
            ->save();

        broadcast(new MessageSent($user, $chatroom, $message))->toOthers();

        return response([
            'status' => 'Message Sent!'
        ]);
    }
}