<?php

namespace App\Http\Controllers\API;

use App\Chatroom;
use App\ChatStatus;
use App\Events\MessageSent;
use App\Http\Resources\ChatMessageResource;
use App\Http\Resources\ChatRoomResource;
use App\Http\Resources\UserResource;
use App\Message;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ChatController extends Controller
{

    /* STATUSES */
    public function statuses()
    {
        return response(ChatStatus::all(), 200);
    }

    /* ROOMS */
    public function rooms()
    {
        $rooms = Chatroom::with(['messages.user.group'])->get();

        return ChatRoomResource::collection($rooms);
    }

    /* MESSAGES */
    public function createMessage(Request $request)
    {
        $broadcast = $request->get('broadcast');
        $save = $request->get('save');

        $message = Message::create($request->except(['broadcast', 'save']));

        if ($broadcast) {
            broadcast(new MessageSent($message));
        }

        if (!$save) {
            $message->delete();
        }

        return $save ? new ChatMessageResource($message) : response('success', 200);
    }

    /* USERS */
    public function updateUserChatStatus(Request $request, $id)
    {
        $user = User::with(['chatStatus', 'chatroom'])->findOrFail($id);
        $status = ChatStatus::findOrFail($request->get('status_id'));

        $user->chatStatus()->dissociate();
        $user->chatStatus()->associate($status);

        $user->save();

        return response($user, 200);
    }

    public function updateUserRoom(Request $request, $id)
    {
        $user = User::with(['chatStatus', 'chatroom'])->findOrFail($id);
        $room = Chatroom::findOrFail($request->get('room_id'));

        $user->chatroom()->dissociate();
        $user->chatroom()->associate($room);

        $user->save();

        return response($user, 200);
    }

}
