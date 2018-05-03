<?php

namespace App\Http\Controllers\API;

use App\Chatroom;
use App\Events\UserJoinedChat;
use App\Http\Resources\ChatMessageResource;
use App\Http\Resources\ChatRoomResource;
use App\Http\Resources\UserResource;
use App\Message;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ChatController extends Controller
{

    /* MAIN CHAT ENDPOINT */
    public function chat($id)
    {
        $room = Chatroom::with(['messages.user'])->find($id);

        return new ChatRoomResource($room);
    }

    /* ROOMS */
    public function rooms()
    {
        return response(Chatroom::select(['id', 'name'])->get(), 200);
    }

    public function createRoom(Request $request)
    {
        $room = Chatroom::where('name', $request->get('name'))->first();

        if ($room !== null) {
            return response(['message' => 'The channel already exists!'], 409);
        }

        return new ChatRoomResource(Chatroom::create([
            'name' => $request->get('name')
        ]));
    }

    public function updateRoom(Request $request, $id)
    {
        $room = Chatroom::findOrFail($id);

        $room->update([
            'name' => $request->get('name')
        ]);

        return new ChatRoomResource($room);
    }

    public function destroyRoom($id)
    {
        $room = Chatroom::findOrFail($id);

        $room->delete();

        return response(['success' => 'Successfully removed chat room!'], 200);
    }

    /* MESSAGES */
    public function roomMessages($id)
    {
        $room = ChatRoom::find($id);

        if ($room->messages->count() > 100) {
            $room->messages()->oldest()->first()->delete();
        }

        $messages = $room->messages()
            ->with('user')
            ->latest()
            ->limit(100)
            ->get();

        return ChatMessageResource::collection($messages);
    }

    public function messages()
    {
        return ChatMessageResource::collection(Message::all());
    }

    public function createMessage(Request $request)
    {
        return new ChatMessageResource(Message::create($request->all()));
    }

    public function updateMessage(Request $request, $id)
    {
        $message = Message::findOrFail($id);

        $message->update([
            'message' => $request->get('message')
        ]);

        return new ChatMessageResource($message);
    }

    public function destroyMessage($id)
    {
        $message = Message::findOrFail($id);

        $message->delete();

        return response(['success' => 'Successfully removed chat room!'], 200);
    }

    /* USERS */
    public function userRoom($id)
    {
        return User::with('chatroom')->find($id);
    }

    public function updateUserRoom(Request $request, $id)
    {
        $user = User::with('chatroom')->findOrFail($id);
        $room = Chatroom::findOrFail($request->get('room_id'));

        $user->chatroom()->dissociate();
        $user->chatroom()->associate($room);

        $user->save();

        UserJoinedChat::dispatch($room);

        return response($user, 200);
    }
}
