<?php

namespace App\Http\Controllers\API;

use App\Chatroom;
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

    /* ROOMS */
    public function rooms()
    {
        $rooms = Chatroom::with(['messages.user'])->get();

        return ChatRoomResource::collection($rooms);
    }

    /* MESSAGES */
    public function createMessage(Request $request)
    {
        $message = Message::create($request->all());

        broadcast(new MessageSent($message));

        return new ChatMessageResource($message);
    }

    /* USERS */
    public function updateUserRoom(Request $request, $id)
    {
        $user = User::with('chatroom')->findOrFail($id);
        $room = Chatroom::findOrFail($request->get('room_id'));

        $user->chatroom()->dissociate();
        $user->chatroom()->associate($room);

        $user->save();

        return response($user, 200);
    }
}
