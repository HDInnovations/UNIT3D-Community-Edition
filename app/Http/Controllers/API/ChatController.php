<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\ChatMessageResource;
use App\Http\Resources\ChatRoomResource;
use App\Repositories\ChatRepository;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Auth\AuthManager;

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

    public function __construct(ChatRepository $chat, AuthManager $auth)
    {
        $this->chat = $chat;
        $this->auth = $auth;
    }

    /* STATUSES */
    public function statuses()
    {
        return response($this->chat->statuses(), 200);
    }

    /* ROOMS */
    public function rooms()
    {
        return ChatRoomResource::collection($this->chat->rooms());
    }

    public function config()
    {
        return response($this->chat->config(), 200);
    }

    /* MESSAGES */
    public function messages($room_id)
    {
        return ChatMessageResource::collection($this->chat->messages($room_id));
    }

    public function createMessage(Request $request)
    {
        $user_id = $request->get('user_id');
        $room_id = $request->get('chatroom_id');
        $message = $request->get('message');
        $save = $request->get('save');

        if ($this->auth->user()->id !== $user_id) {
            return response('error', 401);
        }

        $message = $this->chat->message($user_id, $room_id, $message);

        if (!$save) {
            $message->delete();
        }

        return $save ? new ChatMessageResource($message) : response('success', 200);
    }

    public function deleteMessage($id)
    {
        $this->chat->deleteMessage($id);
        return response('success', 200);
    }

    /* USERS */
    public function updateUserChatStatus(Request $request, $id)
    {
        $user = User::with(['chatStatus', 'chatroom', 'group'])->findOrFail($id);
        $status = $this->chat->statusFindOrFail($request->get('status_id'));

        $user->chatStatus()->dissociate();
        $user->chatStatus()->associate($status);

        $user->save();

        return response($user, 200);
    }

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
